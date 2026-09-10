<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HandCashCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $globalObject ;
    private  $moduleName="Category";
    private $singularVariableName = 'category';
    private $pluralVariableName = 'categories';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new HandCashCategory();
    }

    public function Index()
    {


        $this->retrievedDataList=$this->globalObject->all();

        return view('handcash.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Create()
    {

        $this->retrievedDataList=$this->globalObject->all();

        return view('handcash.'.$this->pluralVariableName.'.create',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);
//        dd($this->globalObject->all());
        return view('handcash.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$data,
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

    public function Update(Request $request,$id)
    {
        $oldData = $this->globalObject->findOrFail($id);
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
    public function toggleHide(Request $request)
    {

        try
        {

            if (($this->globalObject->toggleHide($request)))
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
