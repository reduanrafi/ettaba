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
        'trp',
        'price',
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
            if ($operation == 'add') {
                $amount = $product->price_en + $order->amount;
            } else {
                $amount = max(0, $order->amount - $product->price_en);
            }
            if ($order->update(['amount' => $amount])) {
                return 1;
            }
        }

        return 0;
    }
}
