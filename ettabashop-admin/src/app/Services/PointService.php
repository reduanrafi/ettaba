<?php


namespace App\Services;


use App\Models\EarningHistory;
use App\Models\PendingPoint;
use App\Models\Point;
use App\Models\PointHistory;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use mysql_xdevapi\Result;

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
          $pendingPoints =  $this->GetPendingPointOfUsers($orderId);

          if ($pendingPoints->isEmpty()) {
              return;
          }

          foreach ($pendingPoints as $pendingPoint)
          {
              $userPoint = $this->GetUserPoint($pendingPoint->user_id);

              if ($userPoint!=null) {
                  $userPoint->update(['amount' => $userPoint->amount + $pendingPoint->amount]);
              }
              else{
                  $this->CreatePoint($pendingPoint->user_id);
                  $userPoint = $this->GetUserPoint($pendingPoint->user_id);
                  $userPoint->update(['amount' => $userPoint->amount + $pendingPoint->amount]);
              }
              $this->CreatePointHistory($pendingPoint->user_id, $pendingPoint->amount, "Point Earned !");
          }

          $this->RemovePendingFund($orderId);
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

     private function GetPendingPointOfUsers($orderId)
     {
         return PendingPoint::where('order_id',$orderId)->get();

     }
     private function GetUserPoint($userId)
     {
         return Point::where('user_id',$userId)->first();
     }

     public  function RemovePendingFund($orderId)
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