<?php


namespace App\Services;


use App\Models\SearchKeyword;
use App\Models\Withdraw;
use App\Models\WithdrawHistory;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class WithdrawService
{
    public function SaveWithdraw($userId,$amount)
    {

        $userWithdraws = Withdraw::where('user_id',$userId)->first();

        try{
            if ($userWithdraws!=null) {
                if ($userWithdraws->update([
                    'amount'=>$userWithdraws->amount+$amount
                ]))
                    $this->SaveWithdrawHistory($userId, $amount);

                return 1;
            }
            else{
                if (Withdraw::create([

                    'user_id' => $userId,

                    'amount' => $amount
                ]))
                    $this->SaveWithdrawHistory($userId, $amount);

                return 1;
            }
        }
        catch (QueryException $exception){

           return 0;
        }

    }


    public function SaveWithdrawHistory($userId,$amount)
    {
        try{

            WithdrawHistory::create([

                'user_id'=>$userId,

                'amount'=>$amount,

                'note'=>"You have withdrawn ".$amount." Tk"

            ]);
            return 1;
        }
        catch (QueryException $exception){

            return 0;
        }
    }

}