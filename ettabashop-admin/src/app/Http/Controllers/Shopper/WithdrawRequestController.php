<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Profile;
use App\Models\WithdrawHistory;
use App\Models\WithdrawRequest;

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

    }

    public function index()
    {

        $withdrawRequests = $this->globalObject->GetShopperWithdraws();

        //dd($withdrawRequests);
        return view('admin.withdraw_requests.index',[

            'withdrawRequests'=>$withdrawRequests
        ]);
    }
    public function create()
    {



        return view('admin.withdraw_requests.create',[

        ]);
    }
    public function histories()
    {

        $withdrawHistories = WithdrawHistory::where('user_id',Auth::user()->id)->paginate(10);

        return view('website.profile.index',[

            'withdrawHistories'=>$withdrawHistories
        ]);
    }
    public function Store(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $data = $this->globalObject->GetData($request->all());
        $data['user_id'] = $userId;

        $requestAmount = floatval($request->amount);

        if ($user->type === 'store_administrator') {
            $availableBalance = floatval($user->virtual_balance ?? 0);
        } else {
            $earning = Earning::where('user_id', $userId)->first();
            $availableBalance = $earning ? floatval($earning->amount) : 0;
        }

        if ($requestAmount < 100) {
            return redirect()->back()->with(['error' => "আপনি 100 টাকার কম উত্তোলন করতে পারবেন না ! "]);
        } else if ($availableBalance < 100) {
            return redirect()->back()->with(['error' => "আপনার একাউন্টে পর্যাপ্ত ব্যাল্যান্স নেই । উত্তোলনের জন্য অ্যাকাউন্টে কমপক্ষে 100 টাকা থাকতে হবে !"]);
        } else if ($requestAmount > $availableBalance) {
            return redirect()->back()->with(['error' => "আপনি " . $availableBalance . " টাকার বেশি উত্তোলন করতে পারবেন না ! "]);
        }

        if ($this->globalObject->CheckUsersWithdrawRequest() > 0) {
            return redirect()->back()->with(['error' => "আপনি ইতিমধ্যে একটি রিকোয়েস্ট করেছেন , যা প্রসেসিং হচ্ছে । পূর্বের রিকোয়েস্ট সম্পন্ন না হওয়া পর্যন্ত আপনি আর রিকোয়েস্ট করতে পারবেন না ! "]);
        }

        try {
            if ($this->globalObject->create($data)) {
                $redirectRoute = ($user->type === 'store_administrator') ? 'handcash.mywithdraws' : 'mywithdraws';
                return redirect()->route($redirectRoute)->with(['success' => $this->moduleName . " created successfully"]);
            }
            return redirect()->back()->with(['error' => "Unable to handle this request !"]);
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }

    }
}
