<?php

namespace App\Services;

use App\Models\DirectSalesOrder;
use App\Models\DirectSellerProduct;
use App\Models\User;
use App\Models\Earning;
use App\Models\Point;
use App\Models\CompanyMainFund;
use App\Models\CompanyFallbackAmount;
use App\Models\UserGenerationGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DirectSellingOrderService
{
    public function createOrder($sellerId, $productId, $qty, $customerPhone, $customerName = null, $customerAddress = null)
    {
        $product = DirectSellerProduct::find($productId);
        if (!$product || $product->user_id != $sellerId) {
            return ['status' => 'error', 'message' => 'Product not found or access denied.'];
        }

        if ($product->qty < $qty) {
            return ['status' => 'error', 'message' => 'Insufficient stock for this product. Available: ' . $product->qty];
        }

        $totalCompanyRate = $product->company_rate * $qty;
        $totalSellerRate = $product->seller_rate * $qty;
        $totalErp = $product->erp * $qty;
        $totalReferCommission = $product->refer_commission * $qty;
        $totalTcb = $product->tcb * $qty;
        $totalRewardPoints = $product->reward_points * $qty;
        $totalVat = $product->vat * $qty;

        try {
            DB::beginTransaction();

            // Lock and fetch direct seller
            $seller = User::lockForUpdate()->find($sellerId);
            if ($seller->direct_selling_balance < $totalErp) {
                DB::rollBack();
                return ['status' => 'error', 'message' => 'Insufficient direct selling balance. Required: ৳' . number_format($totalErp, 2) . ', Available: ৳' . number_format($seller->direct_selling_balance, 2)];
            }

            // Deduct balance and product quantity
            $seller->decrement('direct_selling_balance', $totalErp);
            $product->decrement('qty', $qty);

            // Create the Direct Sales Order record
            $order = DirectSalesOrder::create([
                'user_id' => $sellerId,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_address' => $customerAddress,
                'product_id' => $productId,
                'qty' => $qty,
                'company_rate' => $product->company_rate,
                'seller_rate' => $product->seller_rate,
                'erp' => $product->erp,
                'refer_commission' => $product->refer_commission,
                'tcb' => $product->tcb,
                'reward_points' => $product->reward_points,
                'vat' => $product->vat,
                'total_company_rate' => $totalCompanyRate,
                'total_seller_rate' => $totalSellerRate,
                'total_erp' => $totalErp,
                'total_refer_commission' => $totalReferCommission,
                'total_tcb' => $totalTcb,
                'total_reward_points' => $totalRewardPoints,
                'total_vat' => $totalVat,
                'status' => 'completed',
            ]);

            // Services
            $earningSvc = new EarningService();
            $pointSvc = new PointService();

            // 1. Seller Retail Profit: (total_seller_rate - total_company_rate)
            $retailProfit = $totalSellerRate - $totalCompanyRate;
            if ($retailProfit > 0) {
                $earningSvc->UpdateEarningOfUser($sellerId, $retailProfit);
                $earningSvc->CreateEarningHistory($sellerId, $retailProfit, "Retail Profit from Direct Selling order #{$order->id}");
            }

            // Check if customer exists
            $customer = User::where('phone', $customerPhone)->first();
            if ($customer) {
                // Ensure customer has Earning and Point records
                $customerEarning = Earning::where('user_id', $customer->id)->first();
                if (!$customerEarning) {
                    $earningSvc->CreateEarning($customer->id);
                }
                $customerPoint = Point::where('user_id', $customer->id)->first();
                if (!$customerPoint) {
                    $pointSvc->CreatePoint($customer->id);
                }

                // 2. Customer TCB (Cashback)
                if ($totalTcb > 0) {
                    $earningSvc->UpdateEarningOfUser($customer->id, $totalTcb);
                    $earningSvc->CreateEarningHistory($customer->id, $totalTcb, "Cashback from Direct Selling purchase order #{$order->id}");
                }

                // 3. Customer Reward Points (Points)
                if ($totalRewardPoints > 0) {
                    $customerPoint = Point::where('user_id', $customer->id)->first();
                    $customerPoint->increment('amount', $totalRewardPoints);
                    $pointSvc->CreatePointHistory($customer->id, $totalRewardPoints, "Points from Direct Selling purchase order #{$order->id}");
                }

                // 4. Referrer Direct Commission
                if ($customer->parent_id != null && $totalReferCommission > 0) {
                    $parentEarning = Earning::where('user_id', $customer->parent_id)->first();
                    if (!$parentEarning) {
                        $earningSvc->CreateEarning($customer->parent_id);
                    }
                    $earningSvc->UpdateEarningOfUser($customer->parent_id, $totalReferCommission);
                    $earningSvc->CreateEarningHistory($customer->parent_id, $totalReferCommission, "Direct Refer Commission from direct purchase order #{$order->id} by customer eID {$customer->unique_id}");
                }

                // 5. Multi-Level Generation Commission (Upline)
                if ($totalRewardPoints > 0) {
                    $generationPool = $totalRewardPoints * 10;
                    
                    // Company share (40%)
                    $companyFundShare = $generationPool * 0.40;
                    $companyMainFund = CompanyMainFund::first();
                    if (!$companyMainFund) {
                        CompanyMainFund::create(['amount' => 0.00]);
                        $companyMainFund = CompanyMainFund::first();
                    }
                    $companyMainFund->increment('amount', $companyFundShare);

                    // Distribute Generation levels
                    $userGroup = UserGenerationGroup::where('user_id', $customer->id)->first();
                    $fallbackSum = 0;

                    if ($userGroup) {
                        $teamBasePoints = $totalRewardPoints * 0.5;

                        $fallbackSum += $this->distributeDirectLevel($userGroup->g1, $order->id, 'g1', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        $fallbackSum += $this->distributeDirectLevel($userGroup->g2, $order->id, 'g2', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        $fallbackSum += $this->distributeDirectLevel($userGroup->g3, $order->id, 'g3', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        $fallbackSum += $this->distributeDirectLevel($userGroup->g4, $order->id, 'g4', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        $fallbackSum += $this->distributeDirectLevel($userGroup->g5, $order->id, 'g5', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        
                        $fallbackSum += $this->distributeDirectGroup($userGroup->g6, 3, $order->id, 'g6', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        $fallbackSum += $this->distributeDirectGroup($userGroup->g7, 23, $order->id, 'g7', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                        $fallbackSum += $this->distributeDirectGroup($userGroup->g8, 2, $order->id, 'g8', $generationPool, $teamBasePoints, $earningSvc, $pointSvc);
                    } else {
                        // All generation pool falls back if no generation mapping exists
                        $fallbackSum += $generationPool * 0.60;
                    }

                    // Save Fallback log
                    if ($fallbackSum > 0) {
                        CompanyFallbackAmount::create([
                            'order_id' => $order->id,
                            'amount' => $fallbackSum,
                            'status' => 'success'
                        ]);
                        $companyMainFund->increment('amount', $fallbackSum);
                    }
                }
            } else {
                // If customer is unregistered, the company gets the remaining commissions/points pools
                if ($totalRewardPoints > 0) {
                    $generationPool = $totalRewardPoints * 10;
                    $companyMainFund = CompanyMainFund::first();
                    if (!$companyMainFund) {
                        CompanyMainFund::create(['amount' => 0.00]);
                        $companyMainFund = CompanyMainFund::first();
                    }
                    $companyMainFund->increment('amount', $generationPool);
                    
                    CompanyFallbackAmount::create([
                        'order_id' => $order->id,
                        'amount' => $generationPool * 0.60,
                        'status' => 'success'
                    ]);
                }
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Sale confirmed and commissions distributed successfully.'];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Direct Selling Sale Confirmation Exception - Order Product: $productId | Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()];
        }
    }

    private function distributeDirectLevel($userId, $orderId, $group, $bonusAmount, $teamBasePoints, $earningSvc, $pointSvc)
    {
        $calculationService = new CalculationService();
        $calculatedBonus = $calculationService->CalculateGenerationBonusForIndividualUser($bonusAmount, $group, 'money');
        $calculatedPoints = $calculationService->CalculateGenerationBonusForIndividualUser($teamBasePoints, $group, 'point');

        if ($userId != null) {
            $user = User::find($userId);
            if ($user) {
                // Earning Update
                $earning = Earning::where('user_id', $userId)->first();
                if (!$earning) {
                    $earningSvc->CreateEarning($userId);
                }
                $earningSvc->UpdateEarningOfUser($userId, $calculatedBonus);
                $earningSvc->CreateEarningHistory($userId, $calculatedBonus, "Team Generation Commission from direct purchase order #{$orderId}");

                // Points Update
                if ($calculatedPoints > 0) {
                    $point = Point::where('user_id', $userId)->first();
                    if (!$point) {
                        $pointSvc->CreatePoint($userId);
                        $point = Point::where('user_id', $userId)->first();
                    }
                    $point->increment('amount', $calculatedPoints);
                    $pointSvc->CreatePointHistory($userId, $calculatedPoints, "Team Generation Points from direct purchase order #{$orderId}");
                }
                return 0;
            }
        }
        return $calculatedBonus;
    }

    private function distributeDirectGroup($userIdString, $totalUser, $orderId, $group, $bonusAmount, $teamBasePoints, $earningSvc, $pointSvc)
    {
        $calculationService = new CalculationService();
        
        $bonusAmountEach = $calculationService->CalculateGenerationBonusForIndividualUser($bonusAmount, $group, 'money');
        $pointsAmountEach = $calculationService->CalculateGenerationBonusForIndividualUser($teamBasePoints, $group, 'point');

        $fallbackUserCount = $totalUser;

        if ($userIdString != null) {
            $userIdArray = array_filter(explode(',', $userIdString));
            $fallbackUserCount = $totalUser - count($userIdArray);

            foreach ($userIdArray as $userId) {
                $user = User::find($userId);
                if ($user) {
                    // Earning Update
                    $earning = Earning::where('user_id', $userId)->first();
                    if (!$earning) {
                        $earningSvc->CreateEarning($userId);
                    }
                    $earningSvc->UpdateEarningOfUser($userId, $bonusAmountEach);
                    $earningSvc->CreateEarningHistory($userId, $bonusAmountEach, "Team Generation Commission from direct purchase order #{$orderId}");

                    // Points Update
                    if ($pointsAmountEach > 0) {
                        $point = Point::where('user_id', $userId)->first();
                        if (!$point) {
                            $pointSvc->CreatePoint($userId);
                            $point = Point::where('user_id', $userId)->first();
                        }
                        $point->increment('amount', $pointsAmountEach);
                        $pointSvc->CreatePointHistory($userId, $pointsAmountEach, "Team Generation Points from direct purchase order #{$orderId}");
                    }
                } else {
                    $fallbackUserCount++;
                }
            }
        }

        return $fallbackUserCount * $bonusAmountEach;
    }
}
