<?php

namespace App\Http\Controllers\Website\Profile;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Profile;
use App\Models\WithdrawHistory;
use App\Models\WithdrawRequest;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawRequestController extends Controller
{
    private $message;
    private $moduleName = "Withdraw Request";
    private $singularVariableName = 'withdrawRequest';
    private $pluralVariableName = 'withdrawRequests';
    private $globalObject;
    private $profile;

    private $retrievedDataList;
    private $singleData;
    public function __construct()
    {
        $this->globalObject = new WithdrawRequest();
        $this->profile = new Profile();
    }

    public function index()
    {
        $message='';
        $profile  = $this->profile->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        $withdrawRequests = $this->globalObject->GetUsersWithdrawRequests();
//        $withdrawRequestTotal = $this->globalObject->GetUsersWithdrawRequestTotal();

        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }
//        dd($withdrawRequestTotal);
        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'withdrawRequests'=>$withdrawRequests,
//            'withdrawRequestTotal'=>$withdrawRequestTotal
        ]);
    }
    public function histories()
    {
        $message='';
        $profile  = $this->profile->GetProfile();

        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);

        $withdrawHistories = WithdrawHistory::where('user_id',Auth::user()->id)->orderby('id','desc')->paginate(10);

        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }
        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'withdrawHistories'=>$withdrawHistories
        ]);
    }
    public function Store(Request $request)
    {
        //dd($request->all());
        $requestAmount = $request->amount;
        $earning  = Earning::where('user_id',Auth::user()->id)->first();

        if($requestAmount<50)
        {
            return redirect()->back()->with(['error'=>"আপনি   50  টাকার কম  উত্তোলন করতে পারবেন না ! "]);
        }

        if ($requestAmount>$earning->amount)
        {
            return redirect()->back()->with(['error'=>"আপনি   " .$earning->amount. " টাকার বেশি উত্তোলন করতে পারবেন না ! "]);
        }
        else if ($earning->amount<50)
        {
            return redirect()->back()->with(['error'=>"আপনার একাউন্টে পর্যাপ্ত ব্যাল্যান্স নেই । উত্তোলনের জন্য অ্যাকাউন্টে  কমপক্ষে 50 টাকা থাকতে হবে !"]);
        }

        if ( $this->globalObject->CheckUsersWithdrawRequest()>0)
        {
            return redirect()->back()->with(['error'=>"আপনি ইতিমধ্যে একটি রিকোয়েস্ট করেছেন , যা প্রসেসিং হচ্ছে । পূর্বের রিকোয়েস্ট সম্পন্ন না হওয়া পর্যন্ত  আপনি আর রিকোয়েস্ট করতে পারবেন না ! "]);
        }
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
