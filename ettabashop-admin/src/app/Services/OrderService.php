<?php

namespace App\Services;

use Carbon\Carbon;

class OrderService
{
    function GetOrderItemsArray($orderItems, $orderId)
    {
        $data = [];

        foreach ($orderItems as $k => $item) {
            $orderedQty = max(1, (int) ($item->quantity ?? 1));
            $product = \App\Models\Product::find($item->product_id);

            if ($product) {
                $currentStock = max(0, (int) ($product->quantity ?? 0));
                $newStock = max(0, $currentStock - $orderedQty);
                $currentSold = max(0, (int) ($product->sold_amount ?? 0));

                $product->quantity = $newStock;
                $product->sold_amount = $currentSold + $orderedQty;
                if ($newStock <= 0) {
                    $product->is_sold_out = 1;
                }
                $product->save();
            }

            $data[$k]['product_id'] = $item->product_id;
            $data[$k]['order_id'] = $orderId;
            $data[$k]['quantity'] = $orderedQty;
            $data[$k]['product_name'] = isset($item->name) && !empty($item->name) ? $item->name : ($product ? $product->name_en : null);
            $data[$k]['product_unit'] = $product ? $product->unit : null;
            $data[$k]['created_at'] = Carbon::now();
            $data[$k]['updated_at'] = Carbon::now();
        }
        return $data;
    }
}
