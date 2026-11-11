<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $globalObject ;
    private  $moduleName="Product";
    private $singularVariableName = 'product';
    private $pluralVariableName = 'products';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new Product();


    }

    public function Index()
    {

        $this->retrievedDataList=$this->globalObject->all();

        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Create()
    {
        if ($this->globalObject->CheckShopOwnerEligibilityToAddProducts()==0)
        {
            return redirect()->back()->with(['message'=>'Please create your shop first']);
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        $allowedCategories = null;
        $marginPercent = 5.0;

        if ($user && $user->type == 'store_owner' && $user->shop) {
            $marginPercent = (float)($user->shop->erp_margin_percent ?: 5.0);
            if (!empty($user->shop->allowed_categories)) {
                $catIds = json_decode($user->shop->allowed_categories, true);
                if (is_array($catIds) && count($catIds) > 0) {
                    $allowedCategories = $catIds;
                }
            }
        }

        $categoriesQuery = Category::where('is_deleted', 0)->where('is_active', 1);
        if ($allowedCategories !== null) {
            $categoriesQuery->whereIn('id', $allowedCategories);
        }
        $categories = $categoriesQuery->get();

        return view('admin.'.$this->pluralVariableName.'.create',[
            'categories' => $categories,
            'marginPercent' => $marginPercent,
        ]);
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();
        $allowedCategories = null;
        $marginPercent = 5.0;

        if ($user && $user->type == 'store_owner' && $user->shop) {
            $marginPercent = (float)($user->shop->erp_margin_percent ?: 5.0);
            if (!empty($user->shop->allowed_categories)) {
                $catIds = json_decode($user->shop->allowed_categories, true);
                if (is_array($catIds) && count($catIds) > 0) {
                    $allowedCategories = $catIds;
                }
            }
        }

        $categoriesQuery = Category::where('is_deleted', 0)->where('is_active', 1);
        if ($allowedCategories !== null) {
            $categoriesQuery->whereIn('id', $allowedCategories);
        }
        $categories = $categoriesQuery->get();

        return view('admin.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$data,
            $this->pluralVariableName=>$this->globalObject->all(),
            'categories'=>$categories,
            'marginPercent' => $marginPercent,
        ]);
    }

    public function Store(Request $request)
    {

        try
        {
            $data = $request->all();
            $rate = floatval($data['rate_en'] ?? 0);
            $erp = floatval($data['erp_en'] ?? 0);
            $cb = floatval($data['cb_en'] ?? 0);
            $directRefer = floatval($data['direct_refer_commission'] ?? 0);
            
            $netMargin = max(0, $erp - $rate);
            $maxCombinedLimit = $netMargin * 0.50;

            // Per-seller configurable ERP margin percentage (default 5%)
            $user = \Illuminate\Support\Facades\Auth::user();
            $marginPercent = 5.0;
            if ($user && $user->type == 'store_owner' && $user->shop) {
                $marginPercent = (float)($user->shop->erp_margin_percent ?: 5.0);
            }

            $minDiff = $rate * ($marginPercent / 100);
            $minErp = $rate + $minDiff;

            if ($erp > 0 && $erp < $minErp) {
                return redirect()->back()->withInput()->with([
                    'error' => "ERP অবশ্যই Seller Rate থেকে ({$marginPercent}%) এর ব্যবধান হতে হবে (সর্বনিম্ন: " . number_format($minErp, 2) . " টাকা)। কাস্টমারের সুবিধার্থে সেলার ইচ্ছে করলে, আরো বেশি ব্যবধান রাখতে পারেন।"
                ]);
            }

            if (($cb + $directRefer) > ($maxCombinedLimit + 0.05)) {
                return redirect()->back()->withInput()->with(['error' => 'Direct Customer Cashback ও Direct Refer Commission মিলে সর্বোচ্চ ' . number_format($maxCombinedLimit, 2) . ' টাকা দেওয়া যাবে (Net ERP Amount এর ৫০%)।']);
            }
            if ($cb < 0 || $directRefer < 0) {
                return redirect()->back()->withInput()->with(['error' => 'Cashback অথবা Refer Commission ঋণাত্মক হতে পারবে না।']);
            }

            // Mandatory Product Weight
            if (!isset($data['weight']) || !is_numeric($data['weight']) || floatval($data['weight']) <= 0) {
                return redirect()->back()->withInput()->with(['error' => 'Product Weight (gm/kg) দেওয়া আবশ্যক।']);
            }

            if (isset($data['quantity'])) {
                $data['quantity'] = is_numeric($data['quantity']) ? intval($data['quantity']) : 0;
            }

            $data['weight'] = floatval($data['weight']);
            if (isset($data['weight_unit'])) {
                $data['weight_unit'] = in_array(strtolower($data['weight_unit']), ['gm', 'kg']) ? strtolower($data['weight_unit']) : 'gm';
            }

            $data['sizes'] = !empty($data['sizes']) ? trim($data['sizes']) : null;
            $data['colors'] = !empty($data['colors']) ? trim($data['colors']) : null;

            if(!isset($data['name_bn'])) {
                $data['name_bn'] = $data['name_en'];
            }

            if ($this->globalObject->create($this->globalObject->GetData($data)))
            {
                return redirect()->back()->with(['success'=> $this->moduleName." created successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }

    public function Update(Request $request,$id)
    {
        $oldData = $this->globalObject->findOrFail($id);
        $request['id'] = $id;


        try
        {
            $data = $request->all();
            $rate = floatval($data['rate_en'] ?? 0);
            $erp = floatval($data['erp_en'] ?? 0);
            $cb = floatval($data['cb_en'] ?? 0);
            $directRefer = floatval($data['direct_refer_commission'] ?? 0);
            
            $netMargin = max(0, $erp - $rate);
            $maxCombinedLimit = $netMargin * 0.50;

            // Per-seller configurable ERP margin percentage (default 5%)
            $user = \Illuminate\Support\Facades\Auth::user();
            $marginPercent = 5.0;
            if ($user && $user->type == 'store_owner' && $user->shop) {
                $marginPercent = (float)($user->shop->erp_margin_percent ?: 5.0);
            }

            $minDiff = $rate * ($marginPercent / 100);
            $minErp = $rate + $minDiff;

            if ($erp > 0 && $erp < $minErp) {
                return redirect()->back()->withInput()->with([
                    'error' => "ERP অবশ্যই Seller Rate থেকে ({$marginPercent}%) এর ব্যবধান হতে হবে (সর্বনিম্ন: " . number_format($minErp, 2) . " টাকা)। কাস্টমারের সুবিধার্থে সেলার ইচ্ছে করলে, আরো বেশি ব্যবধান রাখতে পারেন।"
                ]);
            }

            if (($cb + $directRefer) > ($maxCombinedLimit + 0.05)) {
                return redirect()->back()->withInput()->with(['error' => 'Direct Customer Cashback ও Direct Refer Commission মিলে সর্বোচ্চ ' . number_format($maxCombinedLimit, 2) . ' টাকা দেওয়া যাবে (Net ERP Amount এর ৫০%)।']);
            }
            if ($cb < 0 || $directRefer < 0) {
                return redirect()->back()->withInput()->with(['error' => 'Cashback অথবা Refer Commission ঋণাত্মক হতে পারবে না।']);
            }

            // Mandatory Product Weight
            if (!isset($data['weight']) || !is_numeric($data['weight']) || floatval($data['weight']) <= 0) {
                return redirect()->back()->withInput()->with(['error' => 'Product Weight (gm/kg) দেওয়া আবশ্যক।']);
            }

            if (isset($data['quantity'])) {
                $data['quantity'] = is_numeric($data['quantity']) ? intval($data['quantity']) : 0;
            }

            $data['weight'] = floatval($data['weight']);
            if (isset($data['weight_unit'])) {
                $data['weight_unit'] = in_array(strtolower($data['weight_unit']), ['gm', 'kg']) ? strtolower($data['weight_unit']) : 'gm';
            }

            $data['sizes'] = !empty($data['sizes']) ? trim($data['sizes']) : null;
            $data['colors'] = !empty($data['colors']) ? trim($data['colors']) : null;

            if(!isset($data['name_bn'])) {
                $data['name_bn'] = $data['name_en'] ?? $oldData->name_en;
            }
            
            $isNameChanged = isset($data['name_en']) && $data['name_en'] != $oldData->name_en;
            $isDescChanged = isset($data['description_en']) && trim(strip_tags($data['description_en'])) != trim(strip_tags($oldData->description_en));
            $isDelAreaChanged = isset($data['delivery_area_en']) && $data['delivery_area_en'] != $oldData->delivery_area_en;
            $isRateChanged = isset($data['rate_en']) && $data['rate_en'] != $oldData->rate_en;
            $isMrpChanged = isset($data['mrp_en']) && $data['mrp_en'] != $oldData->mrp_en;
            $isErpChanged = isset($data['erp_en']) && $data['erp_en'] != $oldData->erp_en;
            $isCbChanged = isset($data['cb_en']) && $data['cb_en'] != $oldData->cb_en;
            $isDirectReferChanged = isset($data['direct_refer_commission']) && $data['direct_refer_commission'] != $oldData->direct_refer_commission;
            $isQuantityChanged = isset($data['quantity']) && $data['quantity'] != $oldData->quantity;
            $isVatChanged = isset($data['vat_percent']) && $data['vat_percent'] != $oldData->vat_percent;
            $isTcbChanged = isset($data['tcb_en']) && $data['tcb_en'] != $oldData->tcb_en;
            $isTrpChanged = isset($data['trp_en']) && $data['trp_en'] != $oldData->trp_en;

            if ($isNameChanged || $isDescChanged || $isDelAreaChanged || $isRateChanged || $isMrpChanged || $isErpChanged || $isCbChanged || $isDirectReferChanged || $isQuantityChanged || $isVatChanged || $isTcbChanged || $isTrpChanged) {
                \App\Models\ProductHistory::create([
                    'product_id' => $id,
                    'old_name' => $oldData->name_en,
                    'old_description' => $oldData->description_en,
                    'old_delivery_area' => $oldData->delivery_area_en,
                    'old_rate' => $oldData->rate_en,
                    'old_mrp' => $oldData->mrp_en,
                    'old_erp' => $oldData->erp_en,
                    'old_cb' => $oldData->cb_en,
                    'old_direct_refer_commission' => $oldData->direct_refer_commission,
                    'old_quantity' => $oldData->quantity,
                    'old_vat' => $oldData->vat_percent,
                    'old_tcb' => $oldData->tcb_en,
                    'old_trp' => $oldData->trp_en,
                ]);
            }

            if ($oldData->update($this->globalObject->GetData($data)))
            {
                // Auto reset is_sold_out if quantity > 0 (Page 13 spec)
                if (isset($data['quantity']) && intval($data['quantity']) > 0 && $oldData->is_sold_out) {
                    $oldData->is_sold_out = 0;
                    $oldData->save();
                }

                // Clear any product or homepage query caches
                try {
                    \Illuminate\Support\Facades\Cache::flush();
                } catch (\Throwable $e) {
                }

                return redirect()->back()->with(['success'=>$this->moduleName."  updated successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to update"]);

        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }
    public function MarkUnMarkFeatured(Request $request)
    {
        try
        {

            if ($this->globalObject->MarkUnMarkFeatured($request->id))
            {
                return redirect()->back()->with(['success'=>$this->moduleName."  updated successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to update"]);

        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }
    public function Delete($id)
    {

        try{
            if ($this->globalObject->destroy($id)){
                return redirect()->back()->with(['success'=>$this->moduleName."  deleted successfully"]);
            }
        }
        catch (QueryException $exception){
            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);

    }
    public function Hide($id)
    {

        try{
            if ($this->globalObject->Hide($id)){
                return redirect()->back()->with(['success'=> "  This product is hidden"]);
            }
        }
        catch (QueryException $exception){
            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);

    }
    public function UnHide($id)
    {

        try{
            if ($this->globalObject->UnHide($id)){
                return redirect()->back()->with(['success'=> "  This product is hidden"]);
            }
        }
        catch (QueryException $exception){
            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);

    }

    public function HiddenProducts()
    {

        $this->retrievedDataList=$this->globalObject->deletedProducts();

        return view('admin.'.$this->pluralVariableName.'.deleted',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }
    
    public function History($id)
    {
        if (Auth::user()->type !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        $product = Product::findOrFail($id);
        $histories = \App\Models\ProductHistory::where('product_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin.products.history', compact('product', 'histories'));
    }
}
