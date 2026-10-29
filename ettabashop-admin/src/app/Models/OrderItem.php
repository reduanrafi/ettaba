<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'owner_id',
        'quantity',
        'price',
        'trp',
        'product_name',
        'product_unit',
        'seller_status',
        'seller_cancel_reason',
        'seller_seen',
        'seller_updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function GetData($data)
    {
        $data['price'] = $this->GetProductPrice($data['product_id']);
        return $data;
    }

    public function GetProductPrice($productId)
    {
        return Product::find($productId)->price_en;
    }

    public function UpdateOrderValue($request, $operation = 'add')
    {
        $product = Product::find($request->product_id);
        $order = Order::find($request->order_id);

        if ($product != null && $order != null) {
            $trp = max(0, $order->trp - (float) $product->trp_en);
            $tcb = max(0, $order->tcb - (float) $product->tcb_en);
            $erp = max(0, $order->erp_total - (float) $product->erp_en);
            $rate = max(0, $order->rate_total - (float) $product->rate_en);

            if ($order->update([
                'trp' => $trp,
                'tcb' => $tcb,
                'erp_total' => $erp,
                'rate_total' => $rate,
            ])) {
                return 1;
            }
        }

        return 0;
    }
}
