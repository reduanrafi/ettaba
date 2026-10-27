<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardApplication;
use App\Models\RewardSetting;
use App\Models\UserNotification;
use App\Services\RewardService;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * All Rewards Page (Section 4)
     */
    public function index()
    {
        $user = Auth::user();
        $accountType = $this->rewardService->getUserAccountType($user);
        $userPoints = $this->rewardService->getUserPoints($user);

        $rewards = $this->rewardService->getRewardsForUser($user);
        $nextReward = $this->rewardService->getNextRewardForUser($user);

        // Motivational Message and Rules
        $motivationalMsg = RewardSetting::getVal('motivational_message', 'আপনি তো প্রতিদিনই কেনাকাটা করছেন বা খরচ করছেন। এবার সেই খরচ থেকেই পুরস্কারের পথে এগিয়ে যান! ইত্তেবা অ্যাপ বা ইত্তেবা’র লোকাল মার্চেন্ট থেকে নিয়মিত কেনাকাটা ও সার্ভিস গ্রহণ করুন, ক্যাশব্যাকের পাশাপাশি পয়েন্ট সংগ্রহ করুন এবং একে এক আনলক করুন তালিকায় উল্লেখিত দারুণসব পুরস্কার।');
        
        $howToEarn = RewardSetting::getVal('how_to_earn_points', [
            '🛒 ইত্তেবা অ্যাপ থেকে কেনাকাটা করুন',
            '🏪 ইত্তেবা Merchant থেকে কেনাকাটা করুন',
            '🎯 নির্দিষ্ট Campaign-এ অংশ নিন',
            '💳 প্রয়োজনীয় পণ্য ও Service গ্রহণ করুন',
            '⭐ নিয়মিত কেনাকাটার মাধ্যমে Point সংগ্রহ করুন'
        ]);
        if (is_string($howToEarn)) {
            $howToEarn = json_decode($howToEarn, true) ?: explode("\n", $howToEarn);
        }

        $terms = RewardSetting::getVal('terms_and_conditions', "পুরস্কার সম্পর্কিত নিয়ম ও শর্তাবলি:\n১. অর্জিত পয়েন্ট নির্দিষ্ট মেয়াদের মধ্যে ব্যবহারযোগ্য।\n২. পুরস্কারের জন্য আবেদনের পূর্বে এনআইডি (NID) ভেরিফিকেশন সহ প্রোফাইল সম্পূর্ণ আপডেট থাকতে হবে।\n৩. ব্যবহারকারীর আবেদন এডমিন যাচাই-বাছাই করে অনুমোদন (Accept) করলে স্বয়ংক্রিয়ভাবে পয়েন্ট কাটা হবে।\n৪. আবেদন বাতিল বা রিজেক্ট হলে কোনো পয়েন্ট কাটা হবে না।\n৫. এডমিনের নোট ও উল্লেখিত নির্দিষ্ট তারিখ, সময় এবং অফিস ঠিকানায় উপস্থিত হয়ে পুরস্কার গ্রহণ করতে হবে।");

        return view('website.profile.rewards.index', [
            'rewards' => $rewards,
            'nextReward' => $nextReward,
            'userPoints' => $userPoints,
            'accountType' => $accountType,
            'motivationalMsg' => $motivationalMsg,
            'howToEarn' => $howToEarn,
            'terms' => $terms,
        ]);
    }

    /**
     * Reward Details Page (Section 4.6)
     */
    public function show($id)
    {
        $user = Auth::user();
        $accountType = $this->rewardService->getUserAccountType($user);
        $userPoints = $this->rewardService->getUserPoints($user);

        $reward = Reward::where('id', $id)
            ->where('account_type', $accountType)
            ->where('is_visible', 1)
            ->firstOrFail();

        $allDecorated = $this->rewardService->getRewardsForUser($user);
        $userReward = $allDecorated->firstWhere('id', $reward->id) ?? $reward;

        $completeness = $this->rewardService->checkProfileCompleteness($user);

        return view('website.profile.rewards.show', [
            'reward' => $userReward,
            'userPoints' => $userPoints,
            'completeness' => $completeness,
        ]);
    }

    /**
     * Submit Reward Application (Section 6, 7)
     */
    public function apply(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $result = $this->rewardService->submitApplication($user, $request->reward_id);

        if (!$result['success']) {
            if (isset($result['type']) && $result['type'] === 'profile_incomplete') {
                return redirect()->route('user.profile')->with([
                    'profile_warning' => $result['message'],
                    'missing_fields' => $result['missing'],
                ]);
            }

            return redirect()->back()->with(['error' => $result['message']]);
        }

        return redirect()->route('profile.rewardHistory')->with(['message' => $result['message']]);
    }

    /**
     * Reward Application History / Total Reward Used (Section 9)
     */
    public function history()
    {
        $user = Auth::user();
        $applications = RewardApplication::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $userPoints = $this->rewardService->getUserPoints($user);
        $totalUsedPoints = $applications->where('points_deducted', 1)->sum('points_deducted_amount');

        // Mark user notifications as read
        UserNotification::where('user_id', $user->id)->update(['is_read' => 1]);

        return view('website.profile.rewards.history', [
            'applications' => $applications,
            'userPoints' => $userPoints,
            'totalUsedPoints' => $totalUsedPoints,
        ]);
    }
}
