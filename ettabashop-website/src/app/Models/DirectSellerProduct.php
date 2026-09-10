<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSellerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_bn',
        'name_en',
        'company_rate',
        'seller_rate',
        'erp',
        'refer_commission',
        'qty',
        'tcb',
        'reward_points',
        'vat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
