<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDueDeductionHistory extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'order_id',
        'amount',
        'balance_before',
        'balance_after',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

