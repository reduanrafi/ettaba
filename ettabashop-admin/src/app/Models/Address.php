<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class Address extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = ['address','user_id','is_active','receiver_name','receiver_phone'];

    /****************************
     * Model Relation area
     *****************************/

    public function user()
    {
        return $this->belongsTo(  User::class, 'user_id');
    }
    public function order()
    {
        return $this->hasMany(  Order::class);
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
        $data['is_active'] = true;
        return $data;
    }

    public function GetUserAddress()
    {
        return Address::where('user_id',Auth::user()->id)->where('is_active',1)->get();
    }

    public function DeleteUserAddress($id)
    {
        return Address::where('id',$id)->update(['is_active'=>0]);
    }
    /****************************
     * Public Methods area
     *****************************/
}
