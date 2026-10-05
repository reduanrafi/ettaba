<?php


namespace App\Services;


use Carbon\Carbon;

class OrderService
{
    function GetOrderItemsArray($orderItems,$orderId)
    {
        $data[]=[];

        foreach ($orderItems as $k=>$item)
        {

            $data[$k]['product_id']=$item->product_id;
            $data[$k]['order_id']=$orderId;
            $data[$k]['quantity']=$item->quantity;
            $product = \App\Models\Product::find($item->product_id);
            $data[$k]['product_name'] = isset($item->name) && !empty($item->name) ? $item->name : ($product ? $product->name_en : null);
            $data[$k]['product_unit'] = $product ? $product->unit : null;
            //$data[$k]['is_bundle']=(isset($item->is_bundle)?$item->is_bundle:0);
            $data[$k]['created_at']=Carbon::now();
            $data[$k]['updated_at']=Carbon::now();
        }
        return $data;
    }

}