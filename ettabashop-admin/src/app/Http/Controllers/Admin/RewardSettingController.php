<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardSetting;
use Illuminate\Http\Request;

class RewardSettingController extends Controller
{
    public function index()
    {
        $motivationalMsg = RewardSetting::getVal('motivational_message', '');
        $terms = RewardSetting::getVal('terms_and_conditions', '');
        $howToEarn = RewardSetting::getVal('how_to_earn_points', '[]');

        $howToEarnArray = json_decode($howToEarn, true);
        if (!is_array($howToEarnArray)) {
            $howToEarnArray = explode("\n", $howToEarn);
        }
        $howToEarnText = implode("\n", $howToEarnArray);

        return view('admin.rewards.settings', [
            'motivationalMsg' => $motivationalMsg,
            'terms' => $terms,
            'howToEarnText' => $howToEarnText,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'motivational_message' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'how_to_earn_points' => 'nullable|string',
        ]);

        if ($request->has('motivational_message')) {
            RewardSetting::setVal('motivational_message', $request->motivational_message);
        }

        if ($request->has('terms_and_conditions')) {
            RewardSetting::setVal('terms_and_conditions', $request->terms_and_conditions);
        }

        if ($request->has('how_to_earn_points')) {
            $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->how_to_earn_points))));
            RewardSetting::setVal('how_to_earn_points', array_values($lines));
        }

        return redirect()->back()->with(['success' => 'পুরস্কার সংক্রান্ত সেটিংস ও মেসেজ সফলভাবে আপডেট হয়েছে!']);
    }
}
