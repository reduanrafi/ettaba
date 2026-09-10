<?php


namespace App\Services;


use App\Models\Earning;
use App\Models\PendingPoint;
use App\Models\Point;
use App\Models\UserPendingFund;
use App\Models\Withdraw;
use Carbon\Carbon;

class EarningStatisticsService
{

    public function EarningStatistics($userId)
    {

        $earning['pendingEarning'] = $this->PendingEarningOfUser($userId);
        $earning['pendingPoint'] = $this->PendingPointOfUser($userId);
        $earning['totalEarning'] =  floatval($this->GetStatistics(Earning::class, $userId));
        $earning['totalWithdraw'] = floatval($this->GetStatistics(Withdraw::class, $userId));
        $earning['totalPoint'] =    floatval($this->GetStatistics(Point::class, $userId));

        return $earning;
    }

    private function PendingEarningOfUser($userId)
    {
        $earning  = UserPendingFund::where('user_id',$userId)->sum('amount');

        if ($earning!=null)
        {
            return $earning;
        }
    }
    private function PendingPointOfUser($userId)
    {
        $earning  = PendingPoint::where('user_id',$userId)->sum('amount');

        if ($earning!=null)
        {
            return $earning;
        }
    }


    private function GetStatistics($className, $userId)
    {
        $earning = $className::where('user_id',$userId)->first();

        if ($earning!=null)
        {
            return number_format((float)$earning->amount, 2, '.', '');;

        }
    }


//    private function TotalEarningOfUser()
//    {
//
//    }

}