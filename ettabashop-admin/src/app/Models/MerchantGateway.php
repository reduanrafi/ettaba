<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantGateway extends Model
{
    use HasFactory;

    protected $table = 'merchant_gateways';

    protected $fillable = [
        'name',
        'code',
        'charge_percent',
        'status',
    ];

    protected $casts = [
        'charge_percent' => 'decimal:2',
        'status' => 'boolean',
    ];
}
