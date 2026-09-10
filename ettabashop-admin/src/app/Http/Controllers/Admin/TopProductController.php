<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopProduct;
use App\Models\TopType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TopProductController extends Controller
{
    private $globalObject ;
    private  $moduleName="TopProducts";
    private $singularVariableName = 'top_product';
    private $pluralVariableName = 'top_products';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new TopProduct();
    }

    public function Index()
    {
        $topTypes = TopType::all();

        return view('admin.'.$this->pluralVariableName.'.index',[
            'topTypes'=>$topTypes
        ]);
    }

    public function Create()
    {
        $this->retrievedDataList=$this->globalObject->all();
        return view('admin.'.$this->pluralVariableName.'.create',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Edit($id)
    {

        return view('admin.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$this->globalObject->findOrFail($id),
            $this->pluralVariableName=>$this->globalObject->all()
        ]);
    }

    public function Store(Request $request)
    {

        try
        {
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

    public function Update(Request $request)
    {



        try
        {

            if ($this->globalObject->UpdateTopProducts($request->all()))
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
}
