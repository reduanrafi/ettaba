<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\Shop;
use App\Models\Union;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    private $globalObject;
    private $moduleName = "Shop";
    private $singularVariableName = 'shop';
    private $pluralVariableName = 'shops';

    public function __construct()
    {
        $this->globalObject = new Shop();
    }

    public function Index()
    {
        $shop = $this->globalObject->GetShop();

        return view('admin.' . $this->pluralVariableName . '.index', [
            $this->singularVariableName => $shop
        ]);
    }

    public function Create()
    {
        $shop = $this->globalObject->GetShop();
        if (!$shop) {
            $divisions = Division::select('id', 'name', 'bn_name')->orderBy('bn_name')->get();
            return view('admin.' . $this->pluralVariableName . '.create', compact('divisions'));
        }
        return redirect()->route('shop.index');
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);

        // Security check: sellers can only edit their own shop unless admin
        if (Auth::user()->type !== 'admin' && $data->user_id !== Auth::id()) {
            return redirect()->route('shop.index')->with(['error' => 'Unauthorized access']);
        }

        $divisions = Division::select('id', 'name', 'bn_name')->orderBy('bn_name')->get();
        $districts = $data->division_id ? District::where('division_id', $data->division_id)->get() : collect([]);
        $upazilas = $data->district_id ? Upazila::where('district_id', $data->district_id)->get() : collect([]);
        $unions = $data->upazila_id ? Union::where('upazila_id', $data->upazila_id)->get() : collect([]);
        $categories = Category::where('is_active', 1)->get();

        return view('admin.' . $this->pluralVariableName . '.edit', [
            $this->singularVariableName => $data,
            'divisions' => $divisions,
            'districts' => $districts,
            'upazilas' => $upazilas,
            'unions' => $unions,
            'categories' => $categories,
        ]);
    }

    public function Store(Request $request)
    {
        $this->validateShopRequest($request, true);

        try {
            if (!$this->globalObject->GetShop()) {
                if ($this->globalObject->create($this->globalObject->GetData($request->all()))) {
                    return redirect()->route('shop.index')->with(['success' => $this->moduleName . " created successfully"]);
                }
                return redirect()->back()->with(['error' => "Unable to handle this request !"]);
            }
            return redirect()->back()->with(['error' => "You have already created the shop"]);
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function Update(Request $request)
    {
        $shopId = $request->shop ?: $request->id;
        $oldData = $this->globalObject->findOrFail($shopId);

        if (Auth::user()->type !== 'admin' && $oldData->user_id !== Auth::id()) {
            return redirect()->route('shop.index')->with(['error' => 'Unauthorized access']);
        }

        $this->validateShopRequest($request, false);

        try {
            if ($oldData->update($this->globalObject->GetData($request->all()))) {
                return redirect()->back()->with(['success' => $this->moduleName . " updated successfully"]);
            }
            return redirect()->back()->with(['error' => "Unable to update"]);
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function Delete($id)
    {
        try {
            if ($this->globalObject->destroy($id)) {
                return redirect()->back()->with(['success' => $this->moduleName . " deleted successfully"]);
            }
        } catch (QueryException $exception) {
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
        return redirect()->back()->with(['error' => "Unable to handle this request !"]);
    }

    /**
     * Admin Edit Shop Profile for any seller
     */
    public function adminEdit($id)
    {
        $shop = Shop::where('id', $id)->orWhere('user_id', $id)->firstOrFail();
        $divisions = Division::select('id', 'name', 'bn_name')->orderBy('bn_name')->get();
        $districts = $shop->division_id ? District::where('division_id', $shop->division_id)->get() : collect([]);
        $upazilas = $shop->district_id ? Upazila::where('district_id', $shop->district_id)->get() : collect([]);
        $unions = $shop->upazila_id ? Union::where('upazila_id', $shop->upazila_id)->get() : collect([]);
        $categories = Category::all();

        return view('admin.shops.admin_edit', compact('shop', 'divisions', 'districts', 'upazilas', 'unions', 'categories'));
    }

    /**
     * Admin Update Shop Profile
     */
    public function adminUpdate(Request $request, $id)
    {
        $shop = Shop::where('id', $id)->orWhere('user_id', $id)->firstOrFail();
        $request->merge(['shop' => $shop->id, 'user_id' => $shop->user_id]);

        $this->validateShopRequest($request, false);

        $data = $this->globalObject->GetData($request->all());

        // Admin controls
        if ($request->has('social_approved')) {
            $data['social_approved'] = $request->social_approved ? 1 : 0;
        }
        if ($request->has('delivery_type')) {
            $data['delivery_type'] = $request->delivery_type;
        }
        if ($request->has('erp_margin_percent')) {
            $data['erp_margin_percent'] = (float)$request->erp_margin_percent;
        }
        if ($request->has('allowed_categories')) {
            $data['allowed_categories'] = json_encode($request->allowed_categories);
        }

        $shop->update($data);

        return redirect()->back()->with(['success' => 'Seller shop profile updated successfully by Admin.']);
    }

    /**
     * Admin toggle seller social links approval
     */
    public function toggleSocialApproved($id)
    {
        $shop = Shop::where('id', $id)->orWhere('user_id', $id)->firstOrFail();
        $shop->social_approved = $shop->social_approved == 1 ? 0 : 1;
        $shop->save();

        $statusStr = $shop->social_approved == 1 ? 'Approved' : 'Hidden/Pending';
        return redirect()->back()->with(['success' => "Seller social links are now {$statusStr}."]);
    }

    /**
     * Centralized validation following Page 4-5 requirements
     */
    protected function validateShopRequest(Request $request, $isCreate = false)
    {
        $logoRules = $isCreate ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'nid_tin' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'division_id' => 'required|integer',
            'district_id' => 'required|integer',
            'upazila_id' => 'required|integer',
            'union_name' => 'required|string|max:255',
            'ward_no' => 'required|string|max:50',
            'delivery_area_bn' => 'required|string|max:500',
            'mobile' => [
                'required',
                'regex:/^01[0-9]{9}$/'
            ],
            'logo_image' => $logoRules,
            'youtube_link' => 'nullable|url|max:255',
            'fb_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
        ], [
            'name.required' => 'Shop Name is required.',
            'slug.required' => 'Custom Shop URL (Slug) is required.',
            'nid_tin.required' => 'Seller NID/TIN Certificate No is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'division_id.required' => 'Division is required.',
            'district_id.required' => 'District is required.',
            'upazila_id.required' => 'Upazila / City Corp is required.',
            'union_name.required' => 'Municipality / Union is required.',
            'ward_no.required' => 'Ward No is required.',
            'delivery_area_bn.required' => 'Delivery Area is required.',
            'mobile.required' => 'Mobile Number is required.',
            'mobile.regex' => 'Mobile number must be exactly 11 English digits starting with 01.',
            'logo_image.required' => 'Logo image is required.',
        ]);
    }
}

