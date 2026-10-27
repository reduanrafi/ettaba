<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineCategory;
use Illuminate\Http\Request;

class OnlineCategoryController extends Controller
{
    public function index()
    {
        $categories = OnlineCategory::orderBy('sort_order', 'asc')->get();

        return view('admin.online_categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $maxOrder = OnlineCategory::max('sort_order') ?? 0;

        OnlineCategory::create([
            'name' => $request->name,
            'icon' => $request->icon ?: 'fas fa-th',
            'sort_order' => $request->filled('sort_order') ? $request->sort_order : ($maxOrder + 1),
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        return redirect()->back()->with(['success' => 'অনলাইন ক্যাটাগরি সফলভাবে যুক্ত করা হয়েছে!']);
    }

    public function update(Request $request, $id)
    {
        $cat = OnlineCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $cat->update([
            'name' => $request->name,
            'icon' => $request->icon ?: $cat->icon,
            'sort_order' => $request->input('sort_order', $cat->sort_order),
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        return redirect()->back()->with(['success' => 'অনলাইন ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে!']);
    }

    public function toggleHide($id)
    {
        $cat = OnlineCategory::findOrFail($id);
        $cat->is_visible = !$cat->is_visible;
        $cat->save();

        $statusStr = $cat->is_visible ? 'দৃশ্যমান (Visible)' : 'লুকানো (Hidden)';
        return redirect()->back()->with(['success' => "ক্যাটাগরি '{$cat->name}' এখন {$statusStr} করা হয়েছে।"]);
    }

    public function destroy($id)
    {
        $cat = OnlineCategory::findOrFail($id);
        $cat->delete();

        return redirect()->back()->with(['success' => 'ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে!']);
    }
}
