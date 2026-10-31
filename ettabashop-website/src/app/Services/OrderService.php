<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class OrderService
{
    public function GetOrderItemsArray($orderItems, $orderId)
    {
        $data = [];
        $hasSellerStatus = false;
        $hasSellerSeen = false;

        try {
            $hasSellerStatus = Schema::hasColumn('order_items', 'seller_status');
            $hasSellerSeen = Schema::hasColumn('order_items', 'seller_seen');
        } catch (\Throwable $e) {
        }

        foreach ($orderItems as $k => $item) {
            $product = \App\Models\Product::find($item->product_id);
            $orderedQty = max(1, (int) ($item->quantity ?? 1));

            // Deduct ordered quantity from product's main stock and increment sold_amount
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

            $row = [
                'product_id' => $item->product_id,
                'owner_id' => !empty($item->owner_id) ? $item->owner_id : ($product ? $product->owner_id : null),
                'order_id' => $orderId,
                'quantity' => $orderedQty,
                'price' => isset($item->price) ? $item->price : ($product ? $product->erp_en : 0),
                'trp' => isset($item->trp) ? $item->trp : ($product ? $product->trp_en : 0),
                'product_name' => isset($item->name) && !empty($item->name) ? $item->name : ($product ? $product->name_en : null),
                'product_unit' => $product ? $product->unit : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if ($hasSellerStatus) {
                $row['seller_status'] = 'new';
            }
            if ($hasSellerSeen) {
                $row['seller_seen'] = 0;
            }

            $data[$k] = $row;
        }

        return $data;
    }
}
