<?php


namespace App\Services;


use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalculationService
{
    public function CalculateProfit($netTotal,$rateTotal)
    {
        return $netTotal-$rateTotal;

    }

    public function CalculateGenerationBonusAmount($profit)
    {
        $generationCommissionPercentage = config('fund.generationCommission');

        return floatval(number_format(($profit*$generationCommissionPercentage)/100,1));
    }

    public function CalculateGenerationBonusForIndividualUser($bonusAmount, $group, $type = 'point')
    {
        $configName = $type === 'money' ? 'generationMoneyBonus' : 'generationBonus';
        $percentage = config($configName . '.' . $group);
        return floatval(number_format(($bonusAmount * $percentage) / 100, 1));
    }
}