<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Coupon;
use App\Models\HandCashOrder;
use Illuminate\Database\QueryException;
use Validator;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\OrderSuccessEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Notifications\OrderConfirmation;

class HandCashOrderController extends Controller
{
    private  $message;
    private  $moduleName="Order";
    private $singularVariableName = 'order';
    private $pluralVariableName = 'orders';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;
    public function __construct()
    {
        $this->globalObject = new HandCashOrder();
    }
    public function SaveOrder(Request $request)
    {

       //return $this->globalObject->GetOrderAmount(28);
        //$data = json_decode($request->order,true) ;
        //dd($data);
        $rules = [
            //'payment_method_id'=>'required',
            //'address_id'=>'required'
        ];
//        return response()->json([
//            'status'=>'ok',
//            'message'=>$this->globalObject->GetData(($request->order))
//        ]);
        try
        {
            $order = $this->globalObject->create($this->globalObject->GetData(($request->order)));

            if ($order)
            {
                if($this->globalObject->UpdateUniqueOrderId($order->id)) {
//                    $this->globalObject->GetAndUpdateOrderAmount($order->id);
                    $this->message = 'Order Placed Successfully ';
                    //event(new OrderSuccessEvent($order));
                }
                else{
                    $this->message = 'Order could not saved';

                }
            }
        }
        catch (QueryException $ex)
        {
            $this->message = $ex->getMessage();
        }
        return response()->json([
            'status'=>'ok',
            'message'=>$this->message
        ]);


    }

    public function DirectPay(Request $request)
    {
        try {
            $amount = $request->amount; // Total cashback amount
            $userId = $request->user_id; // Customer ID
            $ownerId = \Illuminate\Support\Facades\Auth::id(); // Merchant ID
            $merchant = User::find($ownerId);
            
            // 1. Deduct amount from Merchant
            if ($merchant->virtual_balance < $amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient virtual balance in Merchant Account.'
                ]);
            }
            $merchant->update(['virtual_balance' => $merchant->virtual_balance - $amount]);
            
            // 2. Add 50% to Customer Wallet
            $customer = User::find($userId);
            if ($customer) {
                // Add to customer's e-balance (Earning) directly instead of virtual_balance
                $earningSvc = new \App\Services\EarningService();
                $earning = \App\Models\Earning::where('user_id', $customer->id)->first();
                if (!$earning) {
                    $earningSvc->CreateEarning($customer->id);
                }
                $earningSvc->UpdateEarningOfUser($customer->id, $amount / 2);
                $earningSvc->CreateEarningHistory($customer->id, $amount / 2, "Cashback from HandCash Direct Pay");

                
                // 3. Create a HandCashOrder record to satisfy database tracking and order_id requirement
                $order = HandCashOrder::withoutEvents(function () use ($customer, $ownerId, $amount) {
                    return HandCashOrder::create([
                        'user_id' => $customer->id,
                        'owner_id' => $ownerId,
                        'amount' => $amount,
                        'net_total' => $amount,
                        'erp_total' => $amount,
                        'payment_method_id' => 1, // Cash
                    ]);
                });
                $this->globalObject->UpdateUniqueOrderId($order->id);

                // 4. Point Distribution (The other 50% of amount is treated as base for points)
                // Logic: 1 Point = 25 TK
                $pointsGenerated = ($amount / 2) / 25;

                if ($pointsGenerated > 0) {
                     // Distribute the points just like standard orders
                     $distributionService = new \App\Services\CustomerGenerationCommissionDistributionService();
                     
                     // Set the TRP for the order so that DistributeCommission uses this value
                     $order->update(['trp' => $pointsGenerated]); 
                     
                     // Now we call the distribution service which handles own point and team points 
                     // It will automatically save 1x TRP as own point and 0.5x TRP for team Base, and 10x for bonus pool
                     $distributionService->DistributeCommission($order->id, 'handCash');

                     // Distribute Merchant Referral Commission and own rate/points
                     $merchantCommissionService = new \App\Services\MerchantCommissionDistributionService();
                     $merchantCommissionService->DistributeRateAndPoint($order->id);

                     // Manually trigger the Earning Distribution and Point update services
                     // since HandCashOrder was created using withoutEvents to avoid requests structure crash
                     $earningService = new \App\Services\EarningDistribution();
                     $pointService = new \App\Services\PointService();

                     $earningService->DistributeEarning($order->id);
                     $pointService->UpdatePoint($order->id);
                }
            }
            
            return response()->json([
                'status' => 'ok',
                'message' => 'Cashback Payment Successful.'
            ]);

        } catch (QueryException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function GetOrdersByUser()
    {
       // dd($this->globalObject->GetOrderByUser());
       // return $this->globalObject->GetOrderByUser();
       return response(OrderResource::collection($this->globalObject->GetOrderByUser()),200);
    }

    public function GetOrderDetail($orderId)
    {
        return response()->json([
            'status'=>'ok',
            'order'=>'object'
        ]);
    }
}
