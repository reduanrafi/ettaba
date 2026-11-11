<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerBalanceAddHistory extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'transaction_id',
        'amount',
        'gateway_name',
        'gateway_charge',
        'total_paid',
        'payment_reference',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}

