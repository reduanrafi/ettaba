<?php


namespace App\Services;


use App\Models\Earning;
use App\Models\User;
use App\Models\SearchKeyword;
use App\Models\Withdraw;
use Carbon\Carbon;

class EbalanceService
{
    public function UpdateCustomerEbalance($userId, $amount)
    {
        $user = User::find($userId);
        if ($user && $user->type === 'store_administrator') {
            $currentBalance = floatval($user->virtual_balance ?? 0);
            $newBalance = max(0, $currentBalance + $amount);
            $user->update([
                'virtual_balance' => $newBalance
            ]);
            return;
        }

        $earning = Earning::where('user_id',$userId)->first();

        if ($earning!=null)
        {
            $updatedEarning = $earning->amount + $amount;
            $updatedEarning = $updatedEarning>0?$updatedEarning:0;
            $earning->update([

                'amount' => $updatedEarning

            ]);

        }

        return;
    }
}
