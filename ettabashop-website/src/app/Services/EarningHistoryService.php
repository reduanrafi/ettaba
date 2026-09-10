<?php


namespace App\Services;


use App\Models\Earning;
use App\Models\EarningHistory;
use Carbon\Carbon;

class EarningHistoryService
{
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