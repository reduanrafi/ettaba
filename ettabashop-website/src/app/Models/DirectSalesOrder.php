<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'product_id',
        'qty',
        'company_rate',
        'seller_rate',
        'erp',
        'refer_commission',
        'tcb',
        'reward_points',
        'vat',
        'total_company_rate',
        'total_seller_rate',
        'total_erp',
        'total_refer_commission',
        'total_tcb',
        'total_reward_points',
        'total_vat',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(DirectSellerProduct::class, 'product_id');
    }
}
