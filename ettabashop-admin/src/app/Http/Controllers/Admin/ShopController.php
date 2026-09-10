<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    private $globalObject ;
    private  $moduleName="Shop";
    private $singularVariableName = 'shop';
    private $pluralVariableName = 'shops';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new Shop();
    }

    public function Index()
    {


        $this->singleData=$this->globalObject->GetShop();

        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->singularVariableName=>$this->singleData
        ]);
    }

    public function Create()
    {

        if (!$this->globalObject->GetShop()) {

            return view('admin.' . $this->pluralVariableName . '.create');
        }
        return redirect()->route('shop.index');
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);

        return view('admin.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$data,
            $this->pluralVariableName=>$this->globalObject->all()
        ]);
    }

    public function Store(Request $request)
    {

        try
        {
            if (!$this->globalObject->GetShop()) {
                if ($this->globalObject->create($this->globalObject->GetData($request->all()))) {
                    return redirect()->back()->with(['success' => $this->moduleName . " created successfully"]);
                }
                return redirect()->back()->with(['error' => "Unable to handle this request !"]);
            }
            return redirect()->back()->with(['error' => "You have already created the shop"]);
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }

    public function Update(Request $request)
    {
        $oldData = $this->globalObject->findOrFail($request->shop);

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
