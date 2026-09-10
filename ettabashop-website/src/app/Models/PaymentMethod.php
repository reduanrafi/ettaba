<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'name',
        'short_code'
    ];

    /****************************
     * Model Relation area
     *****************************/
    public function orders()
    {
        return $this->hasMany(Order::class);
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
}
