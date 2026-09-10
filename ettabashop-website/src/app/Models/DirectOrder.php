<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DirectOrder extends Model
{
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'user_id',
        'product_name',
        'quantity',
        'username',
        'phone',
        'address',
        'rate',
        'erp',
        'cash_back',
        'status',
        'image'

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
        $data['user_id'] = (Auth::user()?Auth::user()->id:null);
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/
}
