<?php


namespace App\Services;


use App\Models\CompanyFallbackAmount;
use App\Models\CompanyMainFund;
use App\Models\CompanyPendingFund;
use App\Models\Earning;
use App\Models\HandCashOrderItem;
use App\Models\HandCashOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserGenerationGroup;
use App\Models\UserPendingFund;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MerchantCommissionDistributionService{

    public function DistributeRateAndPoint($orderId)
    {
        $order = Order::find($orderId);
        $orderType = '';
        if (!$order) {
            $order = HandCashOrder::find($orderId);
            $orderType = 'handCash';
        }

        $orderItems = $this->findOrderItems($orderId,$orderType);

        $this->SaveMerchantData($orderItems, $order ? $order->user_id : null);

        // Distribute merchant referral commission
        if ($order && $orderItems->isEmpty() && ($order instanceof HandCashOrder) && $order->trp > 0) {
            $this->DistributeMerchantReferralCommissionDirect($order->owner_id, $order->trp, $orderId);
        } else {
            $this->DistributeMerchantReferralCommission($orderItems, $orderId);
        }

        Log::info("orderItem fetched");

        return 1;
    }

    public function findOrderItems($orderID,$orderType='')
    {
        if ($orderType=='handCash'){

            return HandCashOrderItem::where('hand_cash_order_id',$orderID)->with('product')->get();
        }
        else{

            return OrderItem::where('order_id',$orderID)->with('product')->get();
        }
    }


    public function SaveMerchantData($orderItems, $childId = null)
    {
        $pointService = new PointService();
        if ($orderItems!=null)
        {
            foreach ($orderItems as $item)
            {

                $this->SaveUserPendingFund($item->owner_id, $item->order_id, $item->product->rate_en, 'merchant', $childId);

                $pointService->SavePendingPoint($item->owner_id,$item->order_id,$item->trp, 'merchant');
            }
        }
    }

    public function DistributeMerchantReferralCommission($orderItems, $orderId)
    {
        if ($orderItems == null) {
            return;
        }

        foreach ($orderItems as $item) {
            $merchantId = $item->owner_id;
            if (!$merchantId) {
                continue;
            }

            $this->DistributeMerchantReferralCommissionDirect($merchantId, $item->trp, $orderId);
        }
    }

    public function DistributeMerchantReferralCommissionDirect($merchantId, $trp, $orderId)
    {
        if (!$merchantId || $trp <= 0) {
            return;
        }

        // Find merchant referral relation
        $merchantReferral = \App\Models\MerchantReferral::where('merchant_id', $merchantId)->first();
        if (!$merchantReferral) {
            return;
        }

        // Level 1 Referrer (Partner A)
        $partnerAId = $merchantReferral->referrer_id;
        if (!$partnerAId) {
            return;
        }

        $partnerA = User::find($partnerAId);
        if (!$partnerA || $partnerA->is_active == 0) {
            return;
        }

        // Level 1 Commission (4%)
        $level1Percent = config('merchantCommission.level_1_percent', 4);
        $pointValue = config('merchantCommission.point_value', 25);
        $commission1 = $trp * ($level1Percent / 100) * $pointValue;

        if ($commission1 > 0) {
            $this->SaveUserPendingFund($partnerAId, $orderId, $commission1, 'merchant_referral', $merchantId);
        }

        // Level 2 Referrer (Partner B)
        $partnerBId = $partnerA->parent_id;
        if ($partnerBId) {
            $partnerB = User::find($partnerBId);
            if ($partnerB && $partnerB->is_active != 0) {
                $level2Percent = config('merchantCommission.level_2_percent', 1);
                $commission2 = $trp * ($level2Percent / 100) * $pointValue;
                if ($commission2 > 0) {
                    $this->SaveUserPendingFund($partnerBId, $orderId, $commission2, 'merchant_referral', $merchantId);
                }

                // Level 3 Referrer (Partner C)
                $partnerCId = $partnerB->parent_id;
                if ($partnerCId) {
                    $partnerC = User::find($partnerCId);
                    if ($partnerC && $partnerC->is_active != 0) {
                        $level3Percent = config('merchantCommission.level_3_percent', 1);
                        $commission3 = $trp * ($level3Percent / 100) * $pointValue;
                        if ($commission3 > 0) {
                            $this->SaveUserPendingFund($partnerCId, $orderId, $commission3, 'merchant_referral', $merchantId);
                        }
                    }
                }
            }
        }
    }


    private function SaveUserPendingFund($userId, $orderId, $amount = 1, $point_type = 'merchant', $childId = null)
    {

        return UserPendingFund::create([
            'user_id' => intval($userId),
            'child_id' => $childId,
            'amount' => $amount,
            'order_id' => $orderId,
            'point_type' => $point_type
        ]);

    }

}