<?php


namespace App\Services;


use App\Models\CompanyFallbackAmount;
use App\Models\Earning;
use App\Models\HandCashOrder;
use App\Models\Order;
use App\Models\User;
use App\Models\UserGenerationGroup;
use App\Models\UserPendingFund;
use App\Observers\HandCashOrderObserver;
use Carbon\Carbon;

class CustomerGenerationCommissionDistributionService
{

    public function DistributeCommission($orderId, $orderType = '')
    {
        $companyFallbackEarning = 0;
        $calculationService = new CalculationService();
        $pointService = new PointService();

        if ($orderType == 'handCash') {
            $order = HandCashOrder::with('orderItems.product')->find($orderId);
        } else {
            $order = Order::with('orderItems.product')->find($orderId);
        }

        if (!$order) return 0;

        $user = User::find($order->user_id);

        // 1. Save 'own' point (1.0x TRP)
        $pointService->SavePendingPoint($order->user_id, $order->id, $order->trp, 'own');

        // 2. Referral Commission (on first order only, except HandCash which is not credited since they get G1 commission)
        // Check if first order
        $isFirstOrder = false;
        if ($orderType == 'handCash') {
             $isFirstOrder = false;
        } else {
             $isFirstOrder = Order::where('user_id', $order->user_id)->count() == 1;
        }

        if ($isFirstOrder && $user && $user->parent_id != null) {
            // Referrer gets 2 TK per point
            $referralCommission = $order->trp * 2;
            $this->saveCommission($user->parent_id, $order->id, $referralCommission, 'referral', $order->user_id);
        }

        // Direct Refer Commission: every order, referrer gets product's direct_refer_commission amount
        $totalDirectReferCommission = 0;
        $orderWithItems = Order::with('orderItems.product')->find($orderId);
        if ($orderWithItems && $orderWithItems->orderItems) {
            foreach ($orderWithItems->orderItems as $item) {
                $product = $item->product;
                if ($product && $product->direct_refer_commission > 0) {
                    $totalDirectReferCommission += $product->direct_refer_commission * $item->quantity;
                }
            }
        }

        $userGroup = $this->getUserGroup($order->user_id);
        Log::info($userGroup);

        if ($totalDirectReferCommission > 0 && $userGroup && $userGroup->g2 != null) {
            $this->saveCommission($userGroup->g2, $order->id, $totalDirectReferCommission, 'direct_refer', $user->id);
            Log::info("Direct refer commission {$totalDirectReferCommission} saved for referrer level 2 (g2) {$userGroup->g2}");
        }
        
        // 3. Team Point Distribution: 0.5 part is for team distribution. 
        $teamBasePoints = $order->trp * 0.5;

        // 4. Money Distribution Pool: (TRP * 10 logic or profit based)
        // If it's handCash, maybe we use a different pool? 
        // But let's stick to the TRP * 10 logic requested earlier if profit isn't clear
        $generationCommissionBonusAmount = $order->trp * 10; 

        if ($userGroup != null) {
            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g1, $orderId, 'g1', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g2, $orderId, 'g2', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g3, $orderId, 'g3', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g4, $orderId, 'g4', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g5, $orderId, 'g5', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->saveGroupData($userGroup->g6, 3, $orderId, 'g6', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->saveGroupData($userGroup->g7, 23, $orderId, 'g7', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
            $companyFallbackEarning += $this->saveGroupData($userGroup->g8, 2, $orderId, 'g8', $generationCommissionBonusAmount, $teamBasePoints, $order->user_id);
        }

        $this->saveCompanyFallBackAmount($orderId, $companyFallbackEarning);

        return 1;
    }

    public function findOrder($orderID)
    {
        return Order::find($orderID);
    }

    function getUserGroup($userId)
    {
        return UserGenerationGroup::where('user_id', $userId)->first();
    }

    public function saveGroupData($userIdString, $totalUser, $orderId, $group, $generationCommissionBonusAmount, $teamBasePoints = 0, $childId = null)
    {
        $calculationService = new CalculationService();
        $pointService = new PointService();

        $userIdArray = explode(',', $userIdString);
        $validUsers = array_filter($userIdArray);
        $numberOfFallBackUser = $totalUser - count($validUsers);

        $bonusAmount = $calculationService->CalculateGenerationBonusForIndividualUser($generationCommissionBonusAmount, $group, 'money');
        // Assuming your CalculationService can handle point calculation or just reuse the logic
        $pointsAmount = $calculationService->CalculateGenerationBonusForIndividualUser($teamBasePoints, $group, 'point');

        foreach ($userIdArray as $userId) {
            if ($userId != null) {
                $this->saveCommission($userId, $orderId, $bonusAmount, 'team', $childId);
                if ($pointsAmount > 0) {
                    $pointService->SavePendingPoint($userId, $orderId, $pointsAmount, 'team');
                }
            }
        }

        return $numberOfFallBackUser * $bonusAmount;
    }

    public function distributeGenerationCommissionBonus($userId, $orderId, $group, $bonusAmount, $teamBasePoints = 0, $childId = null)
    {
        $companyFallbackAmount = 0;
        $calculationService = new CalculationService();
        $pointService = new PointService();

        $calculatedBonusAmount = $calculationService->CalculateGenerationBonusForIndividualUser($bonusAmount, $group, 'money');
        $calculatedPointsAmount = $calculationService->CalculateGenerationBonusForIndividualUser($teamBasePoints, $group, 'point');

        if ($userId != null) {
            $this->saveCommission($userId, $orderId, $calculatedBonusAmount, 'team', $childId);
            if ($calculatedPointsAmount > 0) {
                $pointService->SavePendingPoint($userId, $orderId, $calculatedPointsAmount, 'team');
            }
        } else {
            $companyFallbackAmount += $calculatedBonusAmount;
        }

        return $companyFallbackAmount;
    }

    public function saveCommission($userId, $orderId, $amount = 1, $point_type = 'team', $childId = null)
    {
        return UserPendingFund::create([
            'user_id' => intval($userId),
            'child_id' => $childId,
            'amount' => $amount,
            'order_id' => $orderId,
            'point_type' => $point_type
        ]);
    }

    public function saveCompanyFallBackAmount($orderId, $amount = 1)
    {
        return CompanyFallbackAmount::create([
            'amount' => $amount,
            'order_id' => $orderId,
        ]);
    }

}
