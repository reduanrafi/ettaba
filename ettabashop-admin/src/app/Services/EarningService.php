<?php


namespace App\Services;


use App\Models\Earning;
use App\Models\EarningHistory;
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

    public function SavPendingFund($userId, $orderId, $amount = 1,$child_id=null)
    {

        return UserPendingFund::create([
            'user_id' => intval($userId),
            'amount' => $amount,
            'order_id' => $orderId,
            'child_id' => $child_id
        ]);

    }

    public function GetReferralPendingFund($child_id)
    {
        return UserPendingFund::where('child_id',$child_id)->first();
    }

    public function AddReferralBonus($userId)
    {
        $referralBonusObj = $this->GetReferralPendingFund($userId);
        if ($referralBonusObj!=null)
        {
            $this->UpdateEarningOfUser($referralBonusObj->user_id,$referralBonusObj->amount);
            $this->RemovePendingReferralBonus($userId);
            return true;
        }
        return false;
    }

    private function RemovePendingReferralBonus($child_id)
    {
        return UserPendingFund::where('child_id',$child_id)->delete();
    }

    function CreateEarningHistory($userId,$amount,$message)
    {
        return EarningHistory::create([
            'user_id'=> $userId,
            'amount'=>$amount,
            'note'=>$message,
            'created_at'=>Carbon::now()
        ]);

    }
}