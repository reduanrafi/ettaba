<?php

namespace App\Http\Controllers\Website\Profile;

use App\Http\Controllers\Controller;
use App\Models\HandCashOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $message;
    private $moduleName = "Profile";
    private $singularVariableName = 'profile';
    private $pluralVariableName = 'profiles';
    private $globalObject;
    private $profile;

    private $retrievedDataList;
    private $singleData;
    public function __construct()
    {
        $this->globalObject = new Order();
        $this->profile = new Profile();
    }
    public function Order()
    {
        $message='';
        $profile  = $this->profile->GetProfile();
//        dd($profile);
        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message
        ]);
    }
    public function Detail(Request $request)
    {
        $message='';
        $profile  = $this->profile->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        $order = $this->globalObject->GetDetail($request->id);

        if ($order && ($order->paymentMethod->short_code ?? '') === 'EPS' && ($order->payment_status ?? '') !== 'paid') {
            $this->autoVerifyEpsOrder($order, false);
            // Refresh order
            $order = $this->globalObject->GetDetail($request->id);
        }

        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'order'=>$order,
        ]);
    }

    public function handcashOrders()
    {
        $message='';
        $profile  = $this->profile->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);

        $hcOrders = HandCashOrder::with('orderItems.product','user')->where('user_id',Auth::user()->id)->orderby('id','desc')->get();
        //dd($orders);
        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'hcOrders'=>$hcOrders,
        ]);
    }

    public function HcDetail(Request $request)
    {
        $message='';
        $profile  = $this->profile->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        $order = $this->globalObject->GetDetail($request->id);

        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'order'=>$order,
        ]);
    }

    public function autoVerifyEpsOrder($order, $isAnonymous = false)
    {
        if ($order && $order->transaction_id && ($order->payment_status ?? '') !== 'paid') {
            $epsService = new \App\Services\EpsPaymentService();
            $statusResult = $epsService->checkTransactionStatus($order->transaction_id);

            if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                try {
                    \Illuminate\Support\Facades\DB::beginTransaction();

                    $statusChanged = false;
                    if ($order->status === 'pending_payment') {
                        $order->status = 'delivered';
                        $statusChanged = true;
                    }
                    $order->payment_status = 'paid';
                    $order->save();

                    // Log status change to order_statuses
                    \Illuminate\Support\Facades\DB::table('order_statuses')->insert([
                        'order_id' => $order->id,
                        'status' => 'delivered',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    if ($statusChanged && !$isAnonymous && $order->virtual_balance_used > 0) {
                        $vbService = new \App\Services\VirtualBalanceService();
                        $vbService->UpdateUsersVirtualBalance($order->virtual_balance_used, $order->user_id);
                        $vbService->SaveVirtualBalance($order->id, $order->virtual_balance_used);
                    }

                    if (!$isAnonymous) {
                        // Check if commissions were already distributed
                        $hasPendingFunds = \App\Models\UserPendingFund::where('order_id', $order->id)->exists();
                        $hasPendingPoints = \App\Models\PendingPoint::where('order_id', $order->id)->exists();

                        if (!$hasPendingFunds && !$hasPendingPoints) {
                            $commissionService = new \App\Services\CustomerGenerationCommissionDistributionService();
                            $commissionService->DistributeCommission($order->id);

                            $merchantCommissionService = new \App\Services\MerchantCommissionDistributionService();
                            $merchantCommissionService->DistributeRateAndPoint($order->id);
                        }

                    }

                    \Illuminate\Support\Facades\DB::commit();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    \Illuminate\Support\Facades\Log::error("Auto-verify EPS Order #{$order->id} on Website failed: " . $e->getMessage());
                }
            }
        }
    }
}
