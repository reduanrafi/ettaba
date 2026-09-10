<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\DirectOrder;
use App\Models\Profile;
use App\Models\User;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectOrderController extends Controller
{
    public $message = '';
    //public $view = 'website.checkout';
    public $globalObject;
    public $profile;
    public function __construct()
    {
        $this->globalObject = new DirectOrder();
        $this->profile = new Profile();
    }
    public function Order(Request $request)
    {
        //dd($this->globalObject->GetData($request->all()));
        try
        {
            if ($this->globalObject->create($this->globalObject->GetData($request->all())))
            {
                return redirect()->back()->with(['message'=>" order created successfully"]);
            }
            return redirect()->back()->with(['message'=>"Unable to handle this request !"]);
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['message'=>$ex->getMessage()]);
        }

    }

    public function DirectOrders()
    {
        $message='';
        $profile  = $this->profile->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        $orders = DirectOrder::where('user_id',Auth::user()->id)->get();
        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'orders'=>$orders,
        ]);
    }



}
