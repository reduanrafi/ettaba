<?php


namespace App\Services;

use App\Models\DailyFund;
use App\Models\Earning;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BonusDistributionService
{
    public function GetActiveUsers()
    {
        return User::where('is_active',1)->where('customer_type','buy_earn')->get();
    }

    public function GetDailyBonus()
    {
        return DailyFund::first()->amount;
    }

    public function UpdateBonusFund($className)
    {
         
        return $className::where('id',1)->update(['amount'=>0]);
    }

    public function UpdateCustomerEarning($userId, $amount)
    {
        $earning = Earning::where('user_id', $userId)->first();

        if ($earning != null) {
            $updatedEarning = $earning->amount + $amount;

            $earning->update([

                'amount' => $updatedEarning

            ]);

        } else {

            $this->earningService->CreateEarning($userId);

            $this->UpdateCustomerEarning($userId, $amount);
        }

        return;
    }
}