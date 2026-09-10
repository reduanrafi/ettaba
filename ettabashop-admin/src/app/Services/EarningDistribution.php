<?php


namespace App\Services;


use App\Models\CompanyMainFund;
use App\Models\CompanyPendingFund;
use App\Models\DailyFund;
use App\Models\DistrictFund;
use App\Models\DivisionFund;
use App\Models\Earning;
use App\Models\EarningHistory;
use App\Models\ExecutiveFund;
use App\Models\IntensiveFund;
use App\Models\LocalFund;
use App\Models\MarketingFund;
use App\Models\UpFund;
use App\Models\User;
use App\Models\UserPendingFund;
use App\Models\VendorFund;
use App\Models\WeeklyFund;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class EarningDistribution
{
    private $calculationService;
    private $earningService;

    public function __construct()
    {
        $this->calculationService = new CalculationService();
        $this->earningService = new EarningService();

    }

    public function DistributeEarning($orderId)
    {
        $pendingCustomerAmounts = $this->GetCustomerPendingAmountByOrderID($orderId);

        $pendingCompanyAmount = $this->GetCompanyPendingAmountByOrderId($orderId);

        if ($pendingCompanyAmount != null) {

            $this->DistributeInternalEarning($pendingCompanyAmount->amount);

            $this->RemoveCompanyPendingAmount($orderId);
        }
        if ($pendingCustomerAmounts != null) {

            $this->DistributeCustomerEarning($pendingCustomerAmounts);

            $this->RemoveCustomerPendingAmount($orderId);
        }
    }


    public function DistributeCustomerEarning($users)
    {
        foreach ($users as $user) {

            if ($this->CheckUserIsActive($user->user_id) == 1) {
                $message = "Earning history created";
                if ($user->point_type == 'merchant_referral') {
                    $message = "Earned from Merchant Referral Commission";
                } elseif ($user->point_type == 'referral') {
                    $message = "Earned from Customer/Partner Referral";
                } elseif ($user->point_type == 'direct_refer') {
                    $message = "Earned from Direct Referral Commission";
                } elseif ($user->point_type == 'team') {
                    $message = "Earned from Team Generation Commission";
                }

                $this->UpdateCustomerEarning($user->user_id, $user->amount);
                $this->CreateEarningHistory($user->user_id, $user->amount, $message);
            }
        }

        return;
    }

    public function GetCustomerPendingAmountByOrderID($orderId)
    {
        return UserPendingFund::where('order_id', $orderId)->get();
    }

    public function GetCompanyPendingAmountByOrderId($orderId)
    {
        return CompanyPendingFund::where('order_id', $orderId)->first();
    }

    private function UpdateCustomerEarning($userId, $amount)
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

    private function DistributeInternalEarning($pendingCompanyAmount)
    {
        $this->UpdateFunds(CompanyMainFund::class, $pendingCompanyAmount, config('fund.companyFund'));

        $this->UpdateFunds(IntensiveFund::class, $pendingCompanyAmount, config('fund.incentiveFund'));

        $this->UpdateFunds(ExecutiveFund::class, $pendingCompanyAmount, config('fund.executiveFund'));

        $this->UpdateFunds(DailyFund::class, $pendingCompanyAmount, config('fund.dailyFund'));

        $this->UpdateFunds(WeeklyFund::class, $pendingCompanyAmount, config('fund.weeklyFund'));

        $this->UpdateFunds(LocalFund::class, $pendingCompanyAmount, config('fund.localOfficeFund'));

        $this->UpdateFunds(UpFund::class, $pendingCompanyAmount, config('fund.upFund'));

        $this->UpdateFunds(DistrictFund::class, $pendingCompanyAmount, config('fund.districtFund'));

        $this->UpdateFunds(DivisionFund::class, $pendingCompanyAmount, config('fund.divisionFund'));

        $this->UpdateFunds(MarketingFund::class, $pendingCompanyAmount, config('fund.marketingFund'));

        $this->UpdateFunds(VendorFund::class, $pendingCompanyAmount, config('fund.vendorFund'));

        return;
    }


    private function UpdateFunds($className, $pendingCompanyAmount, $percentage)
    {
        $oldFund = $className::first();
        $commissionAmount = $this->calculationService->CalculateCompanyFunds($pendingCompanyAmount, $percentage);

        try {
            if ($oldFund != null) {
                $amount = $oldFund->amount + $commissionAmount;
                $oldFund->update([
                    'amount' => $amount
                ]);
            } else {
                $className::create([
                    'amount' => $commissionAmount
                ]);
            }
        } catch (QueryException $e) {

        }

        return;
    }


    public function RemoveCustomerPendingAmount($orderId)
    {
        return UserPendingFund::where('order_id', $orderId)->delete();
    }

    private function RemoveCompanyPendingAmount($orderId)
    {
        return CompanyPendingFund::where('order_id', $orderId)->delete();
    }

    private function CheckUserIsActive($userId)
    {
        $user = User::find($userId);

        if ($user != null && $user->is_blocked == 0) {
            return 1;
        }

        return 0;
    }

    function CreateEarningHistory($userId, $amount, $message)
    {
        return EarningHistory::create([
            'user_id' => $userId,
            'amount' => $amount,
            'note' => $message,
            'created_at' => Carbon::now()
        ]);

    }
}