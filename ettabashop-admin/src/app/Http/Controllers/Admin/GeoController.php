<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    /**
     * Display list of Upazilas / City Corporations / Cantonments.
     */
    public function upazilasIndex(Request $request)
    {
        $query = Upazila::with('district.division');

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('bn_name', 'LIKE', "%{$search}%");
            });
        }

        $upazilas = $query->orderBy('name')->paginate(30)->withQueryString();
        $districts = District::orderBy('name')->get();

        return view('admin.geo.upazilas.index', compact('upazilas', 'districts'));
    }

    /**
     * Show form to create Upazila.
     */
    public function upazilaCreate()
    {
        $districts = District::with('division')->orderBy('name')->get();
        return view('admin.geo.upazilas.create', compact('districts'));
    }

    /**
     * Store newly created Upazila.
     */
    public function upazilaStore(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'bn_name' => 'required|string|max:255',
            'type' => 'required|in:upazila,city_corporation,cantonment',
            'ward_count' => 'nullable|integer|min:0|max:200',
        ]);

        Upazila::create([
            'district_id' => $request->district_id,
            'name' => trim($request->name),
            'bn_name' => trim($request->bn_name),
            'type' => $request->type,
            'ward_count' => $request->ward_count ?: 0,
        ]);

        return redirect()->route('geo.upazilas.index')->with('message', 'উপজেলা/সিটি কর্পোরেশন সফলভাবে যুক্ত হয়েছে।');
    }

    /**
     * Show edit form for Upazila.
     */
    public function upazilaEdit($id)
    {
        $upazila = Upazila::findOrFail($id);
        $districts = District::with('division')->orderBy('name')->get();
        return view('admin.geo.upazilas.edit', compact('upazila', 'districts'));
    }

    /**
     * Update Upazila.
     */
    public function upazilaUpdate(Request $request, $id)
    {
        $upazila = Upazila::findOrFail($id);

        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'bn_name' => 'required|string|max:255',
            'type' => 'required|in:upazila,city_corporation,cantonment',
            'ward_count' => 'nullable|integer|min:0|max:200',
        ]);

        $upazila->update([
            'district_id' => $request->district_id,
            'name' => trim($request->name),
            'bn_name' => trim($request->bn_name),
            'type' => $request->type,
            'ward_count' => $request->ward_count ?: 0,
        ]);

        return redirect()->route('geo.upazilas.index')->with('message', 'উপজেলা/সিটি কর্পোরেশন সফলভাবে আপডেট হয়েছে।');
    }

    /**
     * Delete Upazila.
     */
    public function upazilaDestroy($id)
    {
        $upazila = Upazila::findOrFail($id);
        Union::where('upazila_id', $id)->delete();
        $upazila->delete();

        return redirect()->route('geo.upazilas.index')->with('message', 'উপজেলা সফলভাবে ডিলিট করা হয়েছে।');
    }

    /**
     * Display list of Unions / Municipalities / Wards.
     */
    public function unionsIndex(Request $request)
    {
        $query = Union::with('upazila.district');

        if ($request->filled('upazila_id')) {
            $query->where('upazila_id', $request->upazila_id);
        } elseif ($request->filled('district_id')) {
            $query->whereHas('upazila', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('bn_name', 'LIKE', "%{$search}%");
            });
        }

        $unions = $query->orderBy('name')->paginate(30)->withQueryString();
        $districts = District::orderBy('name')->get();
        $upazilas = [];
        if ($request->filled('district_id')) {
            $upazilas = Upazila::where('district_id', $request->district_id)->orderBy('name')->get();
        }

        return view('admin.geo.unions.index', compact('unions', 'districts', 'upazilas'));
    }

    /**
     * Show form to create Union / Municipality.
     */
    public function unionCreate(Request $request)
    {
        $districts = District::orderBy('name')->get();
        $selectedDistrictId = $request->get('district_id');
        $upazilas = [];
        if ($selectedDistrictId) {
            $upazilas = Upazila::where('district_id', $selectedDistrictId)->orderBy('name')->get();
        } else {
            $upazilas = Upazila::orderBy('name')->get();
        }

        return view('admin.geo.unions.create', compact('districts', 'upazilas', 'selectedDistrictId'));
    }

    /**
     * Store newly created Union / Municipality.
     */
    public function unionStore(Request $request)
    {
        $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:255',
            'bn_name' => 'required|string|max:255',
            'type' => 'required|in:union,municipality,zone,ward',
            'ward_count' => 'nullable|integer|min:0|max:100',
        ]);

        Union::create([
            'upazila_id' => $request->upazila_id,
            'name' => trim($request->name),
            'bn_name' => trim($request->bn_name),
            'type' => $request->type,
            'ward_count' => $request->ward_count ?: 9,
        ]);

        return redirect()->route('geo.unions.index', ['upazila_id' => $request->upazila_id])
            ->with('message', 'ইউনিয়ন/পৌরসভা সফলভাবে যুক্ত হয়েছে।');
    }

    /**
     * Show edit form for Union.
     */
    public function unionEdit($id)
    {
        $union = Union::with('upazila')->findOrFail($id);
        $districts = District::orderBy('name')->get();
        $upazilas = Upazila::where('district_id', $union->upazila->district_id ?? 0)->orderBy('name')->get();

        return view('admin.geo.unions.edit', compact('union', 'districts', 'upazilas'));
    }

    /**
     * Update Union.
     */
    public function unionUpdate(Request $request, $id)
    {
        $union = Union::findOrFail($id);

        $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:255',
            'bn_name' => 'required|string|max:255',
            'type' => 'required|in:union,municipality,zone,ward',
            'ward_count' => 'nullable|integer|min:0|max:100',
        ]);

        $union->update([
            'upazila_id' => $request->upazila_id,
            'name' => trim($request->name),
            'bn_name' => trim($request->bn_name),
            'type' => $request->type,
            'ward_count' => $request->ward_count ?: 9,
        ]);

        return redirect()->route('geo.unions.index', ['upazila_id' => $request->upazila_id])
            ->with('message', 'ইউনিয়ন/পৌরসভা সফলভাবে আপডেট হয়েছে।');
    }

    /**
     * Delete Union.
     */
    public function unionDestroy($id)
    {
        $union = Union::findOrFail($id);
        $upzId = $union->upazila_id;
        $union->delete();

        return redirect()->route('geo.unions.index', ['upazila_id' => $upzId])
            ->with('message', 'ইউনিয়ন সফলভাবে ডিলিট করা হয়েছে।');
    }

    /**
     * AJAX helper to get Districts for a Division.
     */
    public function getDistrictsAjax($division_id)
    {
        $districts = District::where('division_id', $division_id)
            ->select('id', 'name', 'bn_name')
            ->orderBy('name')
            ->get();
        return response()->json($districts);
    }

    /**
     * AJAX helper to get Upazilas for a District.
     */
    public function getUpazilasAjax($district_id)
    {
        try {
            $upazilas = Upazila::where('district_id', $district_id)
                ->select('id', 'name', 'bn_name', 'type', 'ward_count')
                ->orderBy('name')
                ->get();
        } catch (\Throwable $e) {
            $upazilas = Upazila::where('district_id', $district_id)
                ->select('id', 'name', 'bn_name')
                ->orderBy('name')
                ->get()
                ->map(function ($item) {
                    $item->type = 'upazila';
                    $item->ward_count = 0;
                    return $item;
                });
        }

        return response()->json($upazilas);
    }

    /**
     * AJAX helper to get Unions / Municipalities for an Upazila.
     */
    public function getUnionsAjax($upazila_id)
    {
        try {
            $unions = Union::where('upazila_id', $upazila_id)
                ->select('id', 'name', 'bn_name', 'type', 'ward_count')
                ->orderBy('bn_name')
                ->get();
        } catch (\Throwable $e) {
            $unions = collect([]);
        }

        return response()->json($unions);
    }

    /**
     * AJAX helper to check if a Custom Shop URL (Slug) is available.
     */
    public function checkShopSlugAjax(Request $request)
    {
        $raw = trim((string)$request->get('slug', ''));
        $ignoreId = (int)$request->get('ignore_id', 0);

        if (empty($raw)) {
            return response()->json([
                'available' => false,
                'message' => 'Please enter a shop URL (slug).'
            ]);
        }

        $formatted = \App\Models\Shop::formatSlugString($raw);
        $reserved = [
            'login', 'register', 'logout', 'forgot-password', 'products', 'category-products',
            'all-products', 'search', 'detail', 'checkout', 'about', 'contact', 'shop',
            'shop-search', 'shop-detail', 'privacy', 'terms', 'orders', 'profile', 'notices',
            'offer', 'section', 'api', 'admin', 'clear-cache', 'run-migrate', 'payment',
        ];

        if (in_array(strtolower($formatted), $reserved, true)) {
            return response()->json([
                'available' => false,
                'slug' => $formatted,
                'message' => 'এই URLটি সিস্টেম রিজার্ভ করা, অনুগ্রহ করে অন্য নাম ব্যবহার করুন।'
            ]);
        }

        $exists = \App\Models\Shop::whereRaw('LOWER(slug) = ?', [strtolower($formatted)])
            ->when($ignoreId > 0, function($q) use ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'available' => false,
                'slug' => $formatted,
                'message' => 'এই URLটি ইতিমধ্যে ব্যবহার করা হয়েছে। অনুগ্রহ করে অন্য নাম দিন।'
            ]);
        }

        return response()->json([
            'available' => true,
            'slug' => $formatted,
            'message' => 'অভিনন্দন! এই Shop URL (slug) টি খালি আছে এবং ব্যবহারযোগ্য।'
        ]);
    }
}
