<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/

    public function GetDirectOrders()
    {
        return DirectOrder::with('user')->get();
    }

    public function GetDetail($id)
    {
        return DirectOrder::with('user')->find($id);
    }
}
