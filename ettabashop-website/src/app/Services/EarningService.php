<?php


namespace App\Services;


use App\Models\Earning;
use App\Models\UserPendingFund;
use Carbon\Carbon;

class EarningService
{
     function CreateEarning($userId)
     {
        return Earning::create([
           'user_id'=> $userId
        ]);

     }
     function UpdateEarningOfUser($userId,$amount)
     {
         $earning = Earning::where('user_id',$userId)->first();

         $total = $earning->amount+$amount;

         if($earning->update(['amount'=>$total]))
         {
             return 1;
         }

         return 0;
     }

    public function UpdateEarning($orderId)
    {
        $pendingFunds = UserPendingFund::where('order_id', $orderId)->get();

        if ($pendingFunds->isEmpty()) {
            return 0;
        }

        foreach ($pendingFunds as $pendingFund) {
            $earning = Earning::where('user_id', $pendingFund->user_id)->first();
            if ($earning) {
                $earning->update([
                    'amount' => $earning->amount + $pendingFund->amount
                ]);

                $message = "Earning history created";
                if ($pendingFund->point_type == 'merchant_referral') {
                    $message = "Earned from Merchant Referral Commission";
                } elseif ($pendingFund->point_type == 'referral') {
                    $message = "Earned from Customer/Partner Referral";
                } elseif ($pendingFund->point_type == 'direct_refer') {
                    $message = "Earned from Direct Referral Commission";
                } elseif ($pendingFund->point_type == 'team') {
                    $message = "Earned from Team Generation Commission";
                }

                $earningHistoryService = new EarningHistoryService();
                $earningHistoryService->CreateEarningHistory($pendingFund->user_id, $pendingFund->amount, $message);
            }
        }

        return UserPendingFund::where('order_id', $orderId)->delete();
    }

    public function SavPendingFund($userId, $orderId, $amount = 1,$child_id=null)
    {

        return UserPendingFund::create([
            'user_id' => intval($userId),
            'amount' => $amount,
            'order_id' => $orderId,
            'child_id' => $child_id
        ]);

    }
}