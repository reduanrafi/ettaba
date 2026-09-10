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
        $data['user_id'] = Auth::user()->id;
        return $data;
    }

    public function GetUsersWithdrawRequests()
    {
        $userId = Auth::user()->id;
        return WithdrawRequest::where('user_id',$userId)->orderby('id','desc')->get();
    }
    public function GetUsersWithdrawRequestTotal()
    {
        $userId = Auth::user()->id;
        return WithdrawRequest::where('user_id',$userId)->where('status','pending')->sum('amount');
    }
    public function CheckUsersWithdrawRequest()
    {
        $existingRequest = WithdrawRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingRequest) {
         return  1;
        }

        return 0;

    }
    /****************************
     * Public Methods area
     *****************************/
}
