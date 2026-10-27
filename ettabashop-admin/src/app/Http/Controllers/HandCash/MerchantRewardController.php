<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardApplication;
use App\Models\RewardSetting;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantRewardController extends Controller
{
    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    public function index()
    {
        $user = Auth::user();
        $rewards = $this->rewardService->getRewardsForUser($user);
        $nextReward = $this->rewardService->getNextRewardForUser($user);
        $userPoints = $this->rewardService->getUserPoints($user);

        $motivationalMsg = RewardSetting::getVal('motivational_message', '');
        $howToEarn = RewardSetting::getVal('how_to_earn_points', []);
        if (is_string($howToEarn)) {
            $howToEarn = json_decode($howToEarn, true) ?: explode("\n", $howToEarn);
        }
        $terms = RewardSetting::getVal('terms_and_conditions', '');

        return view('handcash.rewards.index', [
            'rewards' => $rewards,
            'nextReward' => $nextReward,
            'userPoints' => $userPoints,
            'motivationalMsg' => $motivationalMsg,
            'howToEarn' => $howToEarn,
            'terms' => $terms,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $reward = Reward::where('id', $id)
            ->where('account_type', 'merchant')
            ->where('is_visible', 1)
            ->firstOrFail();

        $allDecorated = $this->rewardService->getRewardsForUser($user);
        $userReward = $allDecorated->firstWhere('id', $reward->id) ?? $reward;
        $userPoints = $this->rewardService->getUserPoints($user);
        $completeness = $this->rewardService->checkProfileCompleteness($user);

        return view('handcash.rewards.show', [
            'reward' => $userReward,
            'userPoints' => $userPoints,
            'completeness' => $completeness,
        ]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $result = $this->rewardService->submitApplication($user, $request->reward_id);

        if (!$result['success']) {
            return redirect()->back()->with(['error' => $result['message']]);
        }

        return redirect()->route('handcash.rewards.history')
            ->with(['success' => $result['message']]);
    }

    public function history()
    {
        $user = Auth::user();
        $applications = RewardApplication::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $userPoints = $this->rewardService->getUserPoints($user);
        $totalUsed = $applications->where('points_deducted', 1)->sum('points_deducted_amount');

        return view('handcash.rewards.history', [
            'applications' => $applications,
            'userPoints' => $userPoints,
            'totalUsed' => $totalUsed,
        ]);
    }
}
