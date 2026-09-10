<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VirtualBalance;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class VirtualBalanceController extends Controller
{
    private $globalObject ;
    private  $moduleName="Virtual balance";
    private $singularVariableName = 'virtualBalance';
    private $pluralVariableName = 'virtualBalances';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new VirtualBalance();
    }

    public function Index()
    {

        $this->retrievedDataList=$this->globalObject->AllVirtualBalances();
        return view('admin.virtual_balance.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function ChangeStatus(Request $request)
    {


        try {
            if ($this->globalObject->ChangeStatus($request->id, $request->status)) {

                //$this->globalObject->create($this->globalObject->GetData($request->all()));

                return redirect()->back()->with(['success' => $this->moduleName . " updated successfully"]);
            }
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
        return redirect()->back()->with(['error' => "Unable to handle this request !"]);
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
