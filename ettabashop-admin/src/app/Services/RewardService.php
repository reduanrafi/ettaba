<?php

namespace App\Services;

use App\Models\HandCashOrder;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\Profile;
use App\Models\Reward;
use App\Models\RewardApplication;
use App\Models\RewardSetting;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RewardService
{
    /**
     * Determine user account type for rewards: 'customer', 'partner', or 'merchant'
     */
    public function getUserAccountType($user)
    {
        if (!$user) {
            return 'customer';
        }

        if ($user->type === 'store_administrator' || $user->type === 'store_owner') {
            return 'merchant';
        }

        if ($user->customer_type === 'buy_earn') {
            return 'partner';
        }

        return 'customer';
    }

    /**
     * Get user's current valid point balance
     */
    public function getUserPoints($user)
    {
        if (!$user) {
            return 0;
        }

        $accountType = $this->getUserAccountType($user);
        $userPointRow = Point::where('user_id', $user->id)->first();
        $storedPoints = $userPointRow ? floatval($userPointRow->amount) : 0;

        // If merchant, check if points or total TRP cashback is higher
        if ($accountType === 'merchant') {
            $totalTrp = HandCashOrder::where('owner_id', $user->id)->sum('trp');
            return max($storedPoints, floatval($totalTrp));
        }

        return $storedPoints;
    }

    /**
     * Get all rewards for an account type decorated with user-specific status
     */
    public function getRewardsForUser($user)
    {
        $accountType = $this->getUserAccountType($user);
        $userPoints = $this->getUserPoints($user);

        $rewards = Reward::where('account_type', $accountType)
            ->where('is_visible', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('level_no', 'asc')
            ->orderBy('required_points', 'asc')
            ->get();

        $applications = RewardApplication::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('reward_id');

        $hasFoundRunning = false;

        $decorated = $rewards->map(function ($reward) use ($userPoints, $applications, &$hasFoundRunning) {
            $rewardApps = $applications->get($reward->id, collect());
            $activeApp = $rewardApps->first(); // latest application for this reward

            $status = 'locked';
            $badgeText = 'লকড';
            $badgeClass = 'status-locked';
            $canApply = false;

            if ($activeApp) {
                if ($activeApp->status === 'received') {
                    $status = 'received';
                    $badgeText = 'পুরস্কার গ্রহণ সম্পন্ন';
                    $badgeClass = 'status-received';
                } elseif ($activeApp->status === 'accepted') {
                    $status = 'accepted';
                    $badgeText = 'অনুমোদিত / গ্রহণের জন্য প্রস্তুত';
                    $badgeClass = 'status-accepted';
                } elseif ($activeApp->status === 'pending') {
                    $status = 'in_progress';
                    $badgeText = 'আবেদন প্রক্রিয়াধীন';
                    $badgeClass = 'status-in-progress';
                }
            }

            // If no active completed or pending application
            if ($status === 'locked') {
                if ($userPoints >= $reward->required_points) {
                    $status = 'available';
                    $badgeText = 'আবেদনযোগ্য (Available)';
                    $badgeClass = 'status-available';
                    $canApply = true;
                } elseif (!$hasFoundRunning) {
                    $status = 'running';
                    $badgeText = 'পয়েন্ট সংগ্রহ চলছে';
                    $badgeClass = 'status-running';
                    $hasFoundRunning = true;
                }
            }

            $req = max(1, $reward->required_points);
            $progressPercent = min(100, round(($userPoints / $req) * 100, 1));
            $neededPoints = max(0, $reward->required_points - $userPoints);

            $reward->user_status = $status;
            $reward->badge_text = $badgeText;
            $reward->badge_class = $badgeClass;
            $reward->can_apply = $canApply;
            $reward->progress_percent = $progressPercent;
            $reward->needed_points = $neededPoints;
            $reward->latest_application = $activeApp;

            return $reward;
        });

        return $decorated;
    }

    /**
     * Get the next targeted reward for dashboard display
     */
    public function getNextRewardForUser($user)
    {
        $allRewards = $this->getRewardsForUser($user);

        // Next reward is the first one that has not been received yet
        $next = $allRewards->first(function ($r) {
            return !in_array($r->user_status, ['received', 'accepted']);
        });

        // Fallback to the first reward if all completed or empty
        return $next ?? $allRewards->first();
    }

    /**
     * Check profile completeness before applying for a reward
     */
    public function checkProfileCompleteness($user)
    {
        $profile = Profile::where('user_id', $user->id)->first();
        $missing = [];

        if (!$profile) {
            return [
                'is_complete' => false,
                'missing' => ['প্রোফাইল তথ্য অনুপস্থিত', 'NID কার্ড নাম্বার', 'পিতার নাম', 'মাতার নাম', 'বর্তমান ঠিকানা', 'প্রোফাইল ছবি'],
            ];
        }

        if (empty(trim($profile->nid ?? ''))) {
            $missing[] = 'NID নাম্বার';
        }
        if (empty(trim($profile->first_name ?? '')) && empty(trim($user->name ?? ''))) {
            $missing[] = 'পূর্ণ নাম';
        }
        if (empty(trim($profile->father_name ?? ''))) {
            $missing[] = 'পিতার নাম';
        }
        if (empty(trim($profile->mother_name ?? ''))) {
            $missing[] = 'মাতার নাম';
        }
        if (empty(trim($profile->date_of_birth ?? ''))) {
            $missing[] = 'জন্ম তারিখ';
        }
        if (empty(trim($profile->address ?? ''))) {
            $missing[] = 'ঠিকানা';
        }
        if (empty(trim($profile->profile_image ?? ''))) {
            $missing[] = 'প্রোফাইল ছবি';
        }

        return [
            'is_complete' => empty($missing),
            'missing' => $missing,
            'profile' => $profile,
        ];
    }

    /**
     * Submit an application for an available reward
     */
    public function submitApplication($user, $rewardId)
    {
        // 1. Verify profile
        $completeness = $this->checkProfileCompleteness($user);
        if (!$completeness['is_complete']) {
            return [
                'success' => false,
                'type' => 'profile_incomplete',
                'message' => 'পুরস্কারের আবেদন করার পূর্বে আপনার প্রোফাইল সম্পূর্ণ আপডেট থাকতে হবে (যেমন: NID নাম্বার, পিতা-মাতার নাম, ঠিকানা ও ছবি)।',
                'missing' => $completeness['missing'],
            ];
        }

        // 2. Fetch reward
        $reward = Reward::where('id', $rewardId)->where('is_visible', 1)->first();
        if (!$reward) {
            return [
                'success' => false,
                'message' => 'নির্ধারিত পুরস্কারটি পাওয়া যায়নি বা বর্তমানে উপলব্ধ নয়।',
            ];
        }

        // 3. Verify user points
        $userPoints = $this->getUserPoints($user);
        if ($userPoints < $reward->required_points) {
            return [
                'success' => false,
                'message' => 'আপনার পর্যাপ্ত পয়েন্ট নেই। এই পুরস্কারের জন্য প্রয়োজন ' . number_format($reward->required_points) . ' পয়েন্ট, আপনার বর্তমান ব্যালেন্স ' . number_format($userPoints) . ' পয়েন্ট।',
            ];
        }

        // 4. Duplicate prevention: check if another pending application exists
        $hasPending = RewardApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return [
                'success' => false,
                'message' => 'আপনার ইতিমধ্যে একটি পুরস্কারের আবেদন প্রক্রিয়াধীন রয়েছে। সেটি নিষ্পত্তি না হওয়া পর্যন্ত নতুন আবেদন করা যাবে না।',
            ];
        }

        // Check if this exact reward was already received or accepted
        $alreadyClaimed = RewardApplication::where('user_id', $user->id)
            ->where('reward_id', $reward->id)
            ->whereIn('status', ['accepted', 'received'])
            ->exists();

        if ($alreadyClaimed) {
            return [
                'success' => false,
                'message' => 'আপনি ইতিমধ্যে এই পুরস্কারটি গ্রহণ করেছেন।',
            ];
        }

        $profile = $completeness['profile'];
        $appNo = 'RWD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $application = RewardApplication::create([
            'application_no' => $appNo,
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'account_type' => $reward->account_type,
            'reward_title' => $reward->title,
            'reward_name' => $reward->reward_name,
            'required_points' => $reward->required_points,
            'reward_image' => $reward->image,
            'user_name' => !empty($profile->first_name) ? ($profile->first_name . ' ' . ($profile->last_name ?? '')) : $user->name,
            'user_phone' => $user->phone,
            'user_nid' => $profile->nid ?? null,
            'status' => 'pending',
        ]);

        return [
            'success' => true,
            'message' => 'আপনার পুরস্কারের আবেদনটি সফলভাবে জমা হয়েছে! এডমিন যাচাই-বাছাই শেষে এটি অনুমোদন করবেন।',
            'application' => $application,
        ];
    }

    /**
     * Admin: Accept reward application with notes and schedule, deduct points once
     */
    public function acceptApplication($applicationId, $adminUser, $note, $collectionDate = null, $collectionTime = null, $collectionPlace = null)
    {
        return DB::transaction(function () use ($applicationId, $adminUser, $note, $collectionDate, $collectionTime, $collectionPlace) {
            $app = RewardApplication::where('id', $applicationId)->lockForUpdate()->firstOrFail();

            if ($app->status !== 'pending') {
                throw new \Exception('এই আবেদনটি ইতিমধ্যে ' . $app->status . ' অবস্থায় রয়েছে।');
            }

            // Lock user's point row
            $pointRow = Point::where('user_id', $app->user_id)->lockForUpdate()->first();
            $currentAmount = $pointRow ? floatval($pointRow->amount) : 0;

            if ($currentAmount < $app->required_points) {
                throw new \Exception('ব্যবহারকারীর অ্যাকাউন্টে পর্যাপ্ত পয়েন্ট নেই (বর্তমান পয়েন্ট: ' . $currentAmount . ', প্রয়োজন: ' . $app->required_points . ')।');
            }

            // Deduct points
            $newAmount = $currentAmount - $app->required_points;
            $pointRow->update(['amount' => $newAmount]);

            // Create PointHistory entry
            PointHistory::create([
                'user_id' => $app->user_id,
                'amount' => -$app->required_points,
                'note' => 'পুরস্কার অনুমোদন: ' . $app->reward_name . ' (' . $app->application_no . ')',
                'created_at' => Carbon::now(),
            ]);

            // Update application
            $app->update([
                'status' => 'accepted',
                'admin_note' => $note,
                'collection_date' => $collectionDate,
                'collection_time' => $collectionTime,
                'collection_place' => $collectionPlace,
                'points_deducted' => 1,
                'points_deducted_amount' => $app->required_points,
                'processed_by' => $adminUser ? $adminUser->id : null,
                'processed_at' => Carbon::now(),
            ]);

            // Create notification for user
            UserNotification::create([
                'user_id' => $app->user_id,
                'title' => 'আপনার পুরস্কারের আবেদন অনুমোদিত হয়েছে! 🎉',
                'message' => 'আপনার ' . $app->reward_name . ' পুরস্কারের আবেদন গ্রহণ করা হয়েছে। ' . ($note ? 'এডমিন নোট: ' . $note : ''),
                'type' => 'reward',
                'link' => route('profile.rewardHistory'),
                'is_read' => 0,
            ]);

            return $app;
        });
    }

    /**
     * Admin: Reject application without point deduction
     */
    public function rejectApplication($applicationId, $adminUser, $note)
    {
        return DB::transaction(function () use ($applicationId, $adminUser, $note) {
            $app = RewardApplication::where('id', $applicationId)->lockForUpdate()->firstOrFail();

            if ($app->status !== 'pending') {
                throw new \Exception('এই আবেদনটি ইতিমধ্যে ' . $app->status . ' অবস্থায় রয়েছে।');
            }

            $app->update([
                'status' => 'rejected',
                'admin_note' => $note,
                'processed_by' => $adminUser ? $adminUser->id : null,
                'processed_at' => Carbon::now(),
            ]);

            UserNotification::create([
                'user_id' => $app->user_id,
                'title' => 'পুরস্কারের আবেদন সংক্রান্ত তথ্য',
                'message' => 'আপনার ' . $app->reward_name . ' আবেদনটি বাতিল করা হয়েছে। কারণ: ' . ($note ?: 'শর্ত পূরণ না হওয়ায়।'),
                'type' => 'reward',
                'link' => route('profile.rewardHistory'),
                'is_read' => 0,
            ]);

            return $app;
        });
    }

    /**
     * Admin: Mark reward as handed over / Received
     */
    public function markAsReceived($applicationId, $adminUser, $note = null)
    {
        $app = RewardApplication::findOrFail($applicationId);
        $app->update([
            'status' => 'received',
            'admin_note' => $note ?: $app->admin_note,
            'processed_by' => $adminUser ? $adminUser->id : $app->processed_by,
        ]);

        UserNotification::create([
            'user_id' => $app->user_id,
            'title' => 'পুরস্কার গ্রহণ সম্পন্ন হয়েছে 🏆',
            'message' => 'আপনার ' . $app->reward_name . ' পুরস্কারটি সফলভাবে হস্তান্তর করা হয়েছে। ইত্তেবার সাথে থাকার জন্য ধন্যবাদ!',
            'type' => 'reward',
            'link' => route('profile.rewardHistory'),
            'is_read' => 0,
        ]);

        return $app;
    }
}
