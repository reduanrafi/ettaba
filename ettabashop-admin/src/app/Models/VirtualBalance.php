<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VirtualBalance extends Model
{
    use HasFactory;

    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'user_id',
        'amount',
        'transaction_code',
        'status',
        'is_accepted',
        'is_rejected',
        'is_completed',
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function user()
    {
        return $this->belongsTo(  User::class, 'user_id');
    }

    /****************************
     * Public Methods area
     *****************************/

    /***
     * Method to get data.
     * @param $data
     * @return
     */

    public function GetData($data)
    {
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/
    public function AllVirtualBalances()
    {
        return VirtualBalance::with('user')->get();
    }

    public function ChangeStatus($id,$status)
    {
        if ($status=='accepted'||$status=='completed')
        {
              $vBalance = VirtualBalance::find($id);
              $user = DB::table('users')->where('id', $vBalance->user_id)->first();
              $currentBalance = $user ? ($user->virtual_balance ?? 0) : 0;
              DB::table('users')->where('id', $vBalance->user_id)->update([
                 'virtual_balance' => $currentBalance + $vBalance->amount
              ]);
              DB::table('virtual_balances')->where('id',$id)->update([
                 'is_accepted'=>1,
                 'is_rejected'=>0,
                 'is_completed'=>1
             ]);
        }
        if ($status=='rejected')
        {

            DB::table('virtual_balances')->where('id',$id)->update([
                'is_accepted'=>0,
                'is_rejected'=>1,
                'is_completed'=>0
            ]);
        }
        return 1;
    }

}
