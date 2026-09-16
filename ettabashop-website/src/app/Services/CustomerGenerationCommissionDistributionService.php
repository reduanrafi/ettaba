<?php


namespace App\Services;


use App\Models\CompanyFallbackAmount;
use App\Models\CompanyMainFund;
use App\Models\CompanyPendingFund;
use App\Models\Earning;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserGenerationGroup;
use App\Models\UserPendingFund;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerGenerationCommissionDistributionService
{

    public function DistributeCommission($orderId)
    {
        $companyFallbackEarning = 0;

        $calculationService = new CalculationService();

        $pointService = new PointService();

        $order = $this->findOrder($orderId);
        $user = User::find($order->user_id);

        // Req #4: Save point marked as 'own'
        $pointService->SavePendingPoint($order->user_id, $order->id, $order->trp, 'own');

        $this->SaveUserPendingFund($order->user_id, $order->id, $order->tcb, 'own');

        // Req #2: Referral Commission (on first order only)
        $orderCount = Order::where('user_id', $order->user_id)->count();
        if ($orderCount == 1 && $user->parent_id != null) {
            // Referrer gets 2 TK per point
            // Temporarily disabled as per request
            // $referralCommission = $order->trp * 2;
            // $this->SaveUserPendingFund($user->parent_id, $order->id, $referralCommission, 'referral', $user->id);
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

        if ($totalDirectReferCommission > 0 && $user && $user->sponsor_id != null) {
            $this->SaveUserPendingFund($user->sponsor_id, $order->id, $totalDirectReferCommission, 'direct_refer', $user->id);
            Log::info("Direct refer commission {$totalDirectReferCommission} saved for sponsor {$user->sponsor_id}");
        }

        // Req #4: Generation Commission (Team Point & Money distribution)
        
        // 1. Point Distribution Pool: 0.5 part is for team distribution. 
        $teamBasePoints = $order->trp * 0.5;

        // 2. Money Distribution Pool:
        // According to new logic, pool is base on TRP * 10 (1 Point = 10 TK)
        $generationCommissionBonusAmount = $order->trp * 10; 

        // Company takes its fund percentage from the generated money pool
        $companyFundPercentage = config('fund.companyFund') / 100;
        $companyPendingFund = $generationCommissionBonusAmount * $companyFundPercentage; 

        $this->saveCompanyPendingFund($orderId, $companyPendingFund);

        if($userGroup!=null) {
            Log::info("Generation is working with Point and Money Distribution");
            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g1, $orderId, 'g1', $generationCommissionBonusAmount, $teamBasePoints);

            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g2, $orderId, 'g2', $generationCommissionBonusAmount, $teamBasePoints);

            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g3, $orderId, 'g3', $generationCommissionBonusAmount, $teamBasePoints);

            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g4, $orderId, 'g4', $generationCommissionBonusAmount, $teamBasePoints);

            $companyFallbackEarning += $this->distributeGenerationCommissionBonus($userGroup->g5, $orderId, 'g5', $generationCommissionBonusAmount, $teamBasePoints);

            // Group 6-8 (3 users)
            $companyFallbackEarning += $this->saveGroupData($userGroup->g6, 3, $orderId, 'g6', $generationCommissionBonusAmount, $teamBasePoints);

            // Group 9-31 (23 users)
            $companyFallbackEarning += $this->saveGroupData($userGroup->g7, 23, $orderId, 'g7', $generationCommissionBonusAmount, $teamBasePoints);

            // Group 32-33 (2 users)
            $companyFallbackEarning += $this->saveGroupData($userGroup->g8, 2, $orderId, 'g8', $generationCommissionBonusAmount, $teamBasePoints);
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

    public function saveGroupData($userIdString, $totalUser, $orderId, $group, $generationCommissionBonusAmount, $teamBasePoints = 0)
    {

        $calculationService = new CalculationService();
        $pointService = new PointService();

        if ($userIdString != null) {

            $userIdArray = explode(',', $userIdString);
            
            // Re-filtering array so empty strings are ignored in count calculation
            $validUsers = array_filter($userIdArray);

            $numberOfFallBackUser = $totalUser - count($validUsers);

            $bonusAmount = $calculationService->CalculateGenerationBonusForIndividualUser($generationCommissionBonusAmount, $group, 'money');
            $pointsAmount = $calculationService->CalculateGenerationBonusForIndividualUser($teamBasePoints, $group, 'point');

            foreach ($userIdArray as $userId) {

                if ($userId != null) {

                    $this->SaveUserPendingFund($userId, $orderId, $bonusAmount);
                    
                    if ($pointsAmount > 0) {
                        $pointService->SavePendingPoint($userId, $orderId, $pointsAmount, 'team');
                    }

                }
            }

        } else {

            $numberOfFallBackUser = $totalUser;

            $bonusAmount = $calculationService->CalculateGenerationBonusForIndividualUser($generationCommissionBonusAmount, $group, 'money');

        }

        return $numberOfFallBackUser * $bonusAmount;
    }

    public function distributeGenerationCommissionBonus($userId, $orderId, $group, $bonusAmount, $teamBasePoints = 0)
    {
        $companyFallbackAmount = 0;

        $calculationService = new CalculationService();
        $pointService = new PointService();

        $calculatedBonusAmount = $calculationService->CalculateGenerationBonusForIndividualUser($bonusAmount, $group, 'money');
        $calculatedPointsAmount = $calculationService->CalculateGenerationBonusForIndividualUser($teamBasePoints, $group, 'point');

        if ($userId != null) {

            $this->SaveUserPendingFund($userId, $orderId, $calculatedBonusAmount);
            
            if ($calculatedPointsAmount > 0) {
                $pointService->SavePendingPoint($userId, $orderId, $calculatedPointsAmount, 'team');
            }

        } else {

            $companyFallbackAmount += $calculatedBonusAmount;
        }

        return $companyFallbackAmount;
    }

    private function SaveUserPendingFund($userId, $orderId, $amount = 1, $point_type = 'team', $child_id = null)
    {

        return UserPendingFund::create([
            'user_id' => intval($userId),
            'amount' => $amount,
            'order_id' => $orderId,
            'point_type' => $point_type,
            'child_id' => $child_id
        ]);

    }

    private function saveCompanyFallBackAmount($orderId, $amount = 1)
    {
        return CompanyFallbackAmount::create([
            'amount' => $amount,
            'order_id' => $orderId,

        ]);
    }

    private function saveCompanyPendingFund($orderId, $amount = 1)
    {
        return CompanyPendingFund::create([
            'amount' => $amount,
            'order_id' => $orderId,

        ]);
    }

}
