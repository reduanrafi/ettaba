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


        return view('admin.'.$this->pluralVariableName.'.create',[

            'categories'=>Category::where('is_deleted', 0)->get()
        ]);
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);
        $categories = Category::where('is_deleted', 0)->get();
        return view('admin.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$data,
            $this->pluralVariableName=>$this->globalObject->all(),
            'categories'=>$categories
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
            
            $vat = $erp * 0.15;
            $netErp = $erp - $vat;
            $maxLimit = $netErp * 0.25;
            
            if ($erp > 0 && $erp < ($rate + 5)) {
                return redirect()->back()->with(['error' => 'Seller Rate থেকে ERP এর মধ্যে কমপক্ষে ৫ টাকা ব্যবধান থাকতে হবে।']);
            }
            if ($cb > $maxLimit) {
                return redirect()->back()->with(['error' => 'Direct Customer Cashback Max limit is ' . number_format($maxLimit, 2)]);
            }
            if ($directRefer > $maxLimit) {
                return redirect()->back()->with(['error' => 'Direct Refer Commission Max limit is ' . number_format($maxLimit, 2)]);
            }

            if(!isset($data['name_bn'])) {
                $data['name_bn'] = $data['name_en'];
            }
            //dd($this->globalObject->create($this->globalObject->GetData($data)));
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
            
            $vat = $erp * 0.15;
            $netErp = $erp - $vat;
            $maxLimit = $netErp * 0.25;
            
            if ($erp > 0 && $erp < ($rate + 5)) {
                return redirect()->back()->with(['error' => 'Seller Rate থেকে ERP এর মধ্যে কমপক্ষে ৫ টাকা ব্যবধান থাকতে হবে।']);
            }
            if ($cb > $maxLimit) {
                return redirect()->back()->with(['error' => 'Direct Customer Cashback Max limit is ' . number_format($maxLimit, 2)]);
            }
            if ($directRefer > $maxLimit) {
                return redirect()->back()->with(['error' => 'Direct Refer Commission Max limit is ' . number_format($maxLimit, 2)]);
            }

            if(!isset($data['name_bn'])) {
                $data['name_bn'] = $data['name_en'] ?? $oldData->name_en;
            }
            
            $isNameChanged = isset($data['name_en']) && $data['name_en'] != $oldData->name_en;
            $isDescChanged = isset($data['description_en']) && $data['description_en'] != $oldData->description_en;
            $isDelAreaChanged = isset($data['delivery_area_en']) && $data['delivery_area_en'] != $oldData->delivery_area_en;
            $isRateChanged = isset($data['rate_en']) && $data['rate_en'] != $oldData->rate_en;
            $isMrpChanged = isset($data['mrp_en']) && $data['mrp_en'] != $oldData->mrp_en;
            $isErpChanged = isset($data['erp_en']) && $data['erp_en'] != $oldData->erp_en;
            $isCbChanged = isset($data['cb_en']) && $data['cb_en'] != $oldData->cb_en;
            $isDirectReferChanged = isset($data['direct_refer_commission']) && $data['direct_refer_commission'] != $oldData->direct_refer_commission;

            if ($isNameChanged || $isDescChanged || $isDelAreaChanged || $isRateChanged || $isMrpChanged || $isErpChanged || $isCbChanged || $isDirectReferChanged) {
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
                ]);
            }

            if ($oldData->update($this->globalObject->GetData($data)))
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
        $product = Product::findOrFail($id);
        $histories = \App\Models\ProductHistory::where('product_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin.products.history', compact('product', 'histories'));
    }
}
