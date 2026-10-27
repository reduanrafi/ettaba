<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index(Request $request, $type = 'customer')
    {
        if (!in_array($type, ['customer', 'partner', 'merchant'])) {
            $type = 'customer';
        }

        $rewards = Reward::where('account_type', $type)
            ->orderBy('sort_order', 'asc')
            ->orderBy('level_no', 'asc')
            ->get();

        $titles = [
            'customer' => 'কাস্টমার পুরস্কার তালিকা (Customer Rewards)',
            'partner' => 'পার্টনার পুরস্কার তালিকা (Partner Rewards)',
            'merchant' => 'মার্চেন্ট পুরস্কার তালিকা (Merchant Rewards)',
        ];

        return view('admin.rewards.index', [
            'rewards' => $rewards,
            'currentType' => $type,
            'pageTitle' => $titles[$type],
        ]);
    }

    public function create(Request $request, $type = 'customer')
    {
        if (!in_array($type, ['customer', 'partner', 'merchant'])) {
            $type = 'customer';
        }

        $nextLevel = (Reward::where('account_type', $type)->max('level_no') ?? 0) + 1;

        return view('admin.rewards.create', [
            'currentType' => $type,
            'nextLevel' => $nextLevel,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:customer,partner,merchant',
            'level_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'reward_name' => 'required|string|max:255',
            'required_points' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:3072',
            'info_title' => 'nullable|string|max:255',
            'info_items' => 'nullable|string',
            'howto_title' => 'nullable|string|max:255',
            'howto_items' => 'nullable|string',
            'is_visible' => 'nullable',
        ]);

        $reward = new Reward();
        $data = $reward->GetData($request->all());
        $data['is_visible'] = $request->has('is_visible') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', $request->level_no);

        Reward::create($data);

        return redirect()->route('admin.rewards.index', ['type' => $request->account_type])
            ->with(['success' => 'নতুন পুরস্কার সফলভাবে যুক্ত হয়েছে!']);
    }

    public function edit($id)
    {
        $reward = Reward::findOrFail($id);

        return view('admin.rewards.edit', [
            'reward' => $reward,
            'currentType' => $reward->account_type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        $validated = $request->validate([
            'account_type' => 'required|in:customer,partner,merchant',
            'level_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'reward_name' => 'required|string|max:255',
            'required_points' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:3072',
            'info_title' => 'nullable|string|max:255',
            'info_items' => 'nullable|string',
            'howto_title' => 'nullable|string|max:255',
            'howto_items' => 'nullable|string',
            'is_visible' => 'nullable',
        ]);

        $data = $reward->GetData($request->all());
        $data['is_visible'] = $request->has('is_visible') ? 1 : 0;

        $reward->update($data);

        return redirect()->route('admin.rewards.index', ['type' => $reward->account_type])
            ->with(['success' => 'পুরস্কারের তথ্য সফলভাবে আপডেট হয়েছে!']);
    }

    public function toggleHide($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->is_visible = !$reward->is_visible;
        $reward->save();

        $statusStr = $reward->is_visible ? 'দৃশ্যমান (Visible)' : 'লুকানো (Hidden)';
        return redirect()->back()->with(['success' => "পুরস্কারটি এখন {$statusStr} করা হয়েছে।"]);
    }

    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        $type = $reward->account_type;
        $reward->delete();

        return redirect()->route('admin.rewards.index', ['type' => $type])
            ->with(['success' => 'পুরস্কার সফলভাবে মুছে ফেলা হয়েছে!']);
    }
}
