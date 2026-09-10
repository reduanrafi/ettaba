<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Models\WithdrawHistory;
use App\Models\WithdrawRequest;
use App\Services\EarningDistribution;
use App\Services\EbalanceService;
use App\Services\WithdrawService;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class WithdrawRequestController extends Controller
{
    private $globalObject ;
    private  $moduleName="WithdrawRequest";
    private $singularVariableName = 'withdrawRequest';
    private $pluralVariableName = 'withdrawRequests';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new WithdrawRequest();
    }

    public function Index()
    {


        $this->retrievedDataList=$this->globalObject->all();

        return view('admin.withdraw_requests.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }
    public function Histories()
    {


        $historyObj = new WithdrawHistory();
        $histories = $historyObj->GetWithdrawRequestHistories();
        //dd($histories);
        return view('admin.withdraw_requests.histories',[
            'histories'=>$histories
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

        $oldData = $this->globalObject->findOrFail($request->id);

        $request['note'] = "Withdraw request ". ucfirst($request->status);

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

    public function done(Request $request)
    {

        $userId = $request->user_id;

        $amount =$request->amount;

        $withdrawService = new WithdrawService();

        $eBalanceService = new EbalanceService();

        try{
                if ($withdrawService->SaveWithdraw($userId,$amount)==1)
                {
                    $eBalanceService->UpdateCustomerEbalance($userId,-$amount);

                    $this->globalObject->updateStatus("done",$request->id);

                    return redirect()->back()->with(['success'=>$this->moduleName."  Withdraw request process finished !"]);

                }

        }
        catch (QueryException $exception){

            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
    }
}
