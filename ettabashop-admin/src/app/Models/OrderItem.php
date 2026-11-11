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
            $qty = max(1, (int) ($request->quantity ?? 1));
            $unitTrp = (float) $product->trp_en;
            $unitTcb = (float) $product->tcb_en;
            $unitErp = (float) $product->erp_en;
            $unitRate = (float) $product->rate_en;

            if ($operation === 'remove') {
                $trp = max(0, $order->trp - ($unitTrp * $qty));
                $tcb = max(0, $order->tcb - ($unitTcb * $qty));
                $erp = max(0, $order->erp_total - ($unitErp * $qty));
                $rate = max(0, $order->rate_total - ($unitRate * $qty));
                $amount = max(0, (float) $order->amount - ($unitErp * $qty));
                $netTotal = max(0, (float) $order->net_total - ($unitErp * $qty));
            } else {
                $trp = $order->trp + ($unitTrp * $qty);
                $tcb = $order->tcb + ($unitTcb * $qty);
                $erp = $order->erp_total + ($unitErp * $qty);
                $rate = $order->rate_total + ($unitRate * $qty);
                $amount = (float) $order->amount + ($unitErp * $qty);
                $netTotal = (float) $order->net_total + ($unitErp * $qty);
            }

            if ($order->update([
                'trp' => $trp,
                'tcb' => $tcb,
                'erp_total' => $erp,
                'rate_total' => $rate,
                'amount' => $amount,
                'net_total' => $netTotal,
            ])) {
                return 1;
            }
        }

        return 0;
    }
}
