<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'old_name',
        'old_description',
        'old_delivery_area',
        'old_rate',
        'old_mrp',
        'old_erp',
        'old_cb',
        'old_direct_refer_commission'
    ];
}
