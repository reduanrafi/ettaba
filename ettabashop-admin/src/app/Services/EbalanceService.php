<?php


namespace App\Services;


use App\Models\Earning;
use App\Models\SearchKeyword;
use App\Models\Withdraw;
use Carbon\Carbon;

class EbalanceService
{
    public function UpdateCustomerEbalance($userId, $amount)
    {
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
