<?php


namespace App\Services;


use App\Models\User;
use App\Models\VirtualBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VirtualBalanceService
{
     public function UpdateUsersVirtualBalance($virtualBalance)
     {
         $uesrId = Auth::user()->id;
         $user = User::find($uesrId);
         return DB::table('users')->where('id',$uesrId)->update(['virtual_balance'=>$user->virtual_balance-$virtualBalance]);
     }
    public function SaveVirtualBalance($orderId,$virtualBalance)
    {

        return DB::table('virtual_balances')->insert([
            'order_id'=>$orderId,
            'amount'=>$virtualBalance,
            'status'=>'outgoing'
        ]);
    }
}
