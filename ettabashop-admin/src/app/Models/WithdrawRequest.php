<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WithdrawRequest extends Model
{
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'user_id',
        'amount',
        'note',
        'status',
        'phone',
        'bank_account_number'
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function user()
    {
        return $this->belongsTo(  User::class);
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

    public function GetRequestsWithUser()
    {
        return WithdrawRequest::with('user.profile')->get();
    }

    public function GetShopperWithdraws()
    {
        return WithdrawRequest::with('user.profile')->where('user_id',Auth::user()->id)->get();
    }

    public function CheckUsersWithdrawRequest()
    {

        $withdrawRequest = WithdrawRequest::where('status','!=','done')->where('user_id',Auth::user()->id)->get();

       return count($withdrawRequest);

    }

    public function updateStatus($status,$id)
    {
        return WithdrawRequest::where('id',$id)->update(['status'=>$status]);
    }
    /****************************
     * Public Methods area
     *****************************/
}
