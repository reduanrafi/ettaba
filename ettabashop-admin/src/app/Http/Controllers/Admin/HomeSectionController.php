<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomeSectionController extends Controller
{
    private function ensureDefaultSections()
    {
        if (!Schema::hasTable('home_sections')) {
            return;
        }
        if (HomeSection::count() === 0) {
            $now = now();
            $defaultSections = [
                ['title_bn' => 'লোকাল পণ্য', 'title_en' => 'Local Products', 'slug' => 'local-products', 'icon' => 'fas fa-shopping-basket', 'icon_color' => '#16a34a', 'section_type' => 'category', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 1, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'অধিক ক্যাশব্যাকের পণ্য', 'title_en' => 'High Cashback Products', 'slug' => 'high-cashback', 'icon' => 'fas fa-percentage', 'icon_color' => '#16a34a', 'section_type' => 'auto_cashback', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 2, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'অধিক পয়েন্টের পণ্য', 'title_en' => 'High Point Products', 'slug' => 'high-point', 'icon' => 'fas fa-coins', 'icon_color' => '#f59e0b', 'section_type' => 'auto_point', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 3, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'ফিচার পণ্য', 'title_en' => 'Featured Products', 'slug' => 'featured-products', 'icon' => 'fas fa-bolt', 'icon_color' => '#eab308', 'section_type' => 'featured', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 4, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'অর্গানিক পণ্য', 'title_en' => 'Organic Products', 'slug' => 'organic-products', 'icon' => 'fas fa-leaf', 'icon_color' => '#15803d', 'section_type' => 'category', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 5, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'ফ্যাশন ও লাইফস্টাইল', 'title_en' => 'Fashion & Lifestyle', 'slug' => 'fashion-lifestyle', 'icon' => 'fas fa-tshirt', 'icon_color' => '#ec4899', 'section_type' => 'category', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 6, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'ইলেকট্রনিক্স পণ্য', 'title_en' => 'Electronics Products', 'slug' => 'electronics', 'icon' => 'fas fa-tv', 'icon_color' => '#2563eb', 'section_type' => 'category', 'category_ids' => json_encode([]), 'product_limit' => 4, 'sort_order' => 7, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['title_bn' => 'সকল পণ্য', 'title_en' => 'All Products', 'slug' => 'all-products', 'icon' => 'fas fa-th-large', 'icon_color' => '#dc2626', 'section_type' => 'all', 'category_ids' => json_encode([]), 'product_limit' => 10, 'sort_order' => 8, 'is_visible' => 1, 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('home_sections')->insert($defaultSections);
        }
    }

    public function index()
    {
        $this->ensureDefaultSections();
        $sections = Schema::hasTable('home_sections')
            ? HomeSection::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get()
            : collect([]);
        $categories = Category::where('is_deleted', 0)->orderBy('id', 'asc')->get();
        $featuredCount = Product::where('is_deleted', 0)->where('is_featured', 1)->count();

        return view('admin.home_sections.index', compact('sections', 'categories', 'featuredCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_bn' => 'required|string|max:255',
            'section_type' => 'required|string',
            'product_limit' => 'nullable|integer|min:1|max:50',
        ]);

        $maxOrder = HomeSection::max('sort_order') ?? 0;
        $slug = Str::slug($request->title_en ?: $request->title_bn);
        if (empty($slug) || HomeSection::where('slug', $slug)->exists()) {
            $slug = 'section-' . time() . '-' . rand(10, 99);
        }

        $catIds = $request->input('category_ids', []);
        $catIds = is_array($catIds) ? array_values(array_map('intval', $catIds)) : [];

        HomeSection::create([
            'title_bn' => $request->title_bn,
            'title_en' => $request->title_en ?: $request->title_bn,
            'slug' => $slug,
            'icon' => $request->icon ?: 'fas fa-bolt',
            'icon_color' => $request->icon_color ?: '#16a34a',
            'section_type' => $request->section_type ?: 'category',
            'category_ids' => json_encode($catIds),
            'product_limit' => $request->input('product_limit', 4),
            'sort_order' => $request->filled('sort_order') ? (int)$request->sort_order : ($maxOrder + 1),
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'নতুন হোমপেজ পণ্য সেকশন সফলভাবে যুক্ত করা হয়েছে!');
    }

    public function update(Request $request, $id)
    {
        $section = HomeSection::findOrFail($id);

        $request->validate([
            'title_bn' => 'required|string|max:255',
            'product_limit' => 'nullable|integer|min:1|max:50',
        ]);

        $catIds = $request->input('category_ids', []);
        $catIds = is_array($catIds) ? array_values(array_map('intval', $catIds)) : [];

        $section->update([
            'title_bn' => $request->title_bn,
            'title_en' => $request->title_en ?: $section->title_en,
            'icon' => $request->icon ?: $section->icon,
            'icon_color' => $request->icon_color ?: $section->icon_color,
            'section_type' => $request->input('section_type', $section->section_type),
            'category_ids' => json_encode($catIds),
            'product_limit' => $request->input('product_limit', $section->product_limit),
            'sort_order' => $request->input('sort_order', $section->sort_order),
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'পণ্য সেকশন সফলভাবে আপডেট করা হয়েছে!');
    }

    public function toggleVisibility($id)
    {
        $section = HomeSection::findOrFail($id);
        $section->is_visible = !$section->is_visible;
        $section->save();

        $status = $section->is_visible ? 'দৃশ্যমান (Visible)' : 'লুকানো (Hidden)';
        return redirect()->back()->with('success', "সেকশন '{$section->title_bn}' এখন {$status} করা হয়েছে।");
    }

    public function moveOrder(Request $request, $id)
    {
        $section = HomeSection::findOrFail($id);
        $direction = $request->get('dir', 'up');

        if ($direction === 'up') {
            $swapWith = HomeSection::where('sort_order', '<', $section->sort_order)
                ->orderBy('sort_order', 'desc')
                ->first();
        } else {
            $swapWith = HomeSection::where('sort_order', '>', $section->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();
        }

        if ($swapWith) {
            $temp = $section->sort_order;
            $section->sort_order = $swapWith->sort_order;
            $swapWith->sort_order = $temp;
            if ($section->sort_order == $swapWith->sort_order) {
                $section->sort_order += ($direction === 'up' ? -1 : 1);
            }
            $section->save();
            $swapWith->save();
        } else {
            $section->sort_order = max(1, $section->sort_order + ($direction === 'up' ? -1 : 1));
            $section->save();
        }

        return redirect()->back()->with('success', 'সেকশনের প্রদর্শনের ক্রম পরিবর্তন করা হয়েছে!');
    }

    public function destroy($id)
    {
        $section = HomeSection::findOrFail($id);
        $section->delete();

        return redirect()->back()->with('success', 'হোমপেজ সেকশন মুছে ফেলা হয়েছে!');
    }
}

