<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HandCashCategory;
use App\Models\HandCashProduct;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->globalObject = new HandCashProduct();


    }

    public function Index()
    {

        $this->retrievedDataList=$this->globalObject->all();

        return view('handcash.hand_cash_products.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Create()
    {

            //return redirect()->back()->with(['message'=>'Please create your shop first']);

        $categories = HandCashCategory::where('user_id',Auth::user()->id)->get();
        return view('handcash.hand_cash_products.create',[

            'categories'=>$categories
        ]);
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);
        $categories = HandCashCategory::where('user_id',Auth::user()->id)->get();
        return view('handcash.hand_cash_products.edit',[
            $this->singularVariableName=>$data,
            $this->pluralVariableName=>$this->globalObject->all(),
            'categories'=>$categories
        ]);
    }

    public function Store(Request $request)
    {

        try
        {
            //dd($this->globalObject->create($this->globalObject->GetData($request->all())));
            if ($this->globalObject->create($this->globalObject->GetData($request->all())))
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

            if ($oldData->update($this->globalObject->GetData($request->all())))
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

        return view('handcash.hand_cash_products.deleted',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }
}
