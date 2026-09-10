<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\virtualBalance;
use App\Models\WithdrawHistory;
use App\Models\WithdrawRequest;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VirtualBalanceController extends Controller
{
    private $message;
    private $moduleName = "Virtual balance";
    private $singularVariableName = 'virtualBalance';
    private $pluralVariableName = 'virtualBalances';
    private $globalObject;
    private $virtualBalance;

    private $retrievedDataList;
    private $singleData;
    public function __construct()
    {
        $this->globalObject = new VirtualBalance();

    }

    public function index()
    {

    }
    public function histories()
    {
        $message='';
        $virtualBalance  = $this->virtualBalance->GetvirtualBalance();

        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);

        $withdrawHistories = WithdrawHistory::where('user_id',Auth::user()->id)->paginate(10);

        if(!$virtualBalance) {
            $message =  'You do not have a virtualBalance, please create a virtualBalance to activate you account !';
        }
        return view('website.virtualBalance.index',[
            'virtualBalance'=>$virtualBalance,
            'message'=>$message,
            'earnings'=>$earnings,
            'withdrawHistories'=>$withdrawHistories
        ]);
    }
    public function Store(Request $request)
    {
//        dd($request->all());
        $requestAmount = $request->amount;

        if($requestAmount<500)
        {
            return redirect()->back()->with(['error'=>"আপনি   500  টাকার কম ব্যাল্যান্স অ্যাড করতে পারবেন না! "]);
        }
//        $test = $this->globalObject->GetData($request->all());
//        dd($this->globalObject->create($this->globalObject->GetData($request->all())));
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
}
