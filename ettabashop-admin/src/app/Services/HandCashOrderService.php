<?php


namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HandCashOrderService
{
    function GetOrderItemsArray($orderItems,$orderId)
    {
        $data[]=[];

        foreach ($orderItems as $k=>$item)
        {

            $data[$k]['hand_cash_product_id']=$item['product_id'];
            $data[$k]['hand_cash_order_id']=$orderId;
            $data[$k]['owner_id']=Auth::user()->id;
            $data[$k]['quantity']=$item['quantity'];
            $data[$k]['price']=(isset($item['price'])?$item['price']:0);
            $data[$k]['trp']=(isset($item['trp'])?$item['trp']:0);
            $data[$k]['tcb']=(isset($item['tcb'])?$item['tcb']:0);
            $product = \App\Models\HandCashProduct::find($item['product_id']);
            $data[$k]['product_name'] = isset($item['name']) && !empty($item['name']) ? $item['name'] : ($product ? $product->name_en : null);
            $data[$k]['product_unit'] = $product ? $product->unit : null;
            //$data[$k]['is_bundle']=(isset($item->is_bundle)?$item->is_bundle:0);
            $data[$k]['created_at']=Carbon::now();
            $data[$k]['updated_at']=Carbon::now();
        }
        return $data;
    }

}
