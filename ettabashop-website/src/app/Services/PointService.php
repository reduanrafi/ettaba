<?php


namespace App\Services;


use App\Models\PendingPoint;
use App\Models\Point;
use App\Models\PointHistory;
use Carbon\Carbon;
use Illuminate\Database\QueryException;


class PointService
{
    function CreatePoint($userId)
    {
        return Point::create([
            'user_id'=> $userId
        ]);

    }

    public function UpdatePoint($orderId)
    {
        $pendingPoints = PendingPoint::where('order_id', $orderId)->get();

        if ($pendingPoints->isEmpty()) {
            return 0;
        }

        foreach ($pendingPoints as $pendingPoint) {
            $userPoint = $this->GetUserPoint($pendingPoint->user_id);
            if (!$userPoint) {
                $this->CreatePoint($pendingPoint->user_id);
                $userPoint = $this->GetUserPoint($pendingPoint->user_id);
            }
            if ($userPoint) {
                $userPoint->update([
                    'amount' => $userPoint->amount + $pendingPoint->amount
                ]);
                $this->CreatePointHistory($pendingPoint->user_id, $pendingPoint->amount, "Point Earned !");
            }
        }

        return $this->RemovePendingPoint($orderId);
    }

    public function SavePendingPoint($userId,$orderId,$amount, $type = 'own')
    {
        $result = 0;
        try{
            return  PendingPoint::create(
                [
                    'user_id'=>$userId,
                    'order_id'=>$orderId,
                    'amount'=>$amount,
                    'point_type' => $type
                ]
            );
        }
        catch (QueryException $e)
        {
            return $result;
        }

    }


    private function GetPendingPointOfUser($orderId)
    {
        return PendingPoint::where('order_id',$orderId)->first();

    }
    private function GetUserPoint($userId)
    {
        return Point::where('user_id',$userId)->first();
    }

    private  function RemovePendingPoint($orderId)
    {
        return PendingPoint::where('order_id', $orderId)->delete();
    }

    public function CreatePointHistory($userId,$amount,$message)
    {
        return PointHistory::create([
            'user_id'=> $userId,
            'amount'=>$amount,
            'note'=>$message,
            'created_at'=>Carbon::now()
        ]);
    }
}