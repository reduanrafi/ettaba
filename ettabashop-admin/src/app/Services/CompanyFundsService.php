<?php


namespace App\Services;


use App\Models\CompanyFallbackAmount;
use App\Models\CompanyMainFund;
use App\Models\DailyFund;
use App\Models\DistrictFund;
use App\Models\DivisionFund;
use App\Models\Earning;
use App\Models\ExecutiveFund;
use App\Models\IntensiveFund;
use App\Models\LocalFund;
use App\Models\MarketingFund;
use App\Models\Point;
use App\Models\UpFund;
use App\Models\UserPendingFund;
use App\Models\VendorFund;
use App\Models\WeeklyFund;
use App\Models\Withdraw;
use Carbon\Carbon;

class CompanyFundsService
{

    public function GetCompanyFunds()
    {

        $fund['mainFund'] = $this->GetStatistics(CompanyMainFund::class);
        $fund['incentiveFund'] = $this->GetStatistics(IntensiveFund::class);
        $fund['executiveFund'] = $this->GetStatistics(ExecutiveFund::class);
        $fund['dailyFund'] = $this->GetStatistics(DailyFund::class);
        $fund['weeklyFund'] = $this->GetStatistics(WeeklyFund::class);
        $fund['vendorFund'] = $this->GetStatistics(VendorFund::class);
        $fund['localOfficeFund'] = $this->GetStatistics(LocalFund::class);
        $fund['upFund'] = $this->GetStatistics(UpFund::class);
        $fund['districtFund'] = $this->GetStatistics(DistrictFund::class);
        $fund['divisionFund'] = $this->GetStatistics(DivisionFund::class);
        $fund['marketingFund'] = $this->GetStatistics(MarketingFund::class);
        $fund['phishingFund'] = $this->CalculateSum(CompanyFallbackAmount::class);
        $fund['totalWithdraw'] = $this->CalculateSum(Withdraw::class);
        $fund['todayWithdraw'] = $this->DailyWithdraw(Withdraw::class);


        return $fund;
    }



    private function GetStatistics($className)
    {
        $fund = $className::first();

        if ($fund!=null)
        {
            return  number_format((float)$fund->amount, 2, '.', '');
        }
    }


    private function CalculateSum($className)
    {
        $amount =  $className::sum('amount');
        return number_format((float)$amount, 2, '.', '');
    }
    private function DailyWithdraw($className)
    {
        return $className::wheredate('created_at',Carbon::now()->today())->sum('amount');
    }

//    private function TotalEarningOfUser()
//    {
//
//    }

}