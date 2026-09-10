<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantBalanceAddHistory extends Model
{
    use HasFactory;

    protected $table = 'merchant_balance_add_histories';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'gateway_name',
        'gateway_charge',
        'total_paid',
        'status',
        'payment_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_charge' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    /**
     * Get the merchant user associated with the history record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
