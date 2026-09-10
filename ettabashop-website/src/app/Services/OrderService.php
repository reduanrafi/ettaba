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
            $data[$k]['owner_id']=$item->owner_id;
            $data[$k]['order_id']=$orderId;
            $data[$k]['quantity']=$item->quantity;
            $data[$k]['price']=(isset($item->price)?$item->price:0);
            $data[$k]['trp']=(isset($item->trp)?$item->trp:0);
            $data[$k]['tcb']=(isset($item->tcb)?$item->tcb:0);
            //$data[$k]['is_bundle']=(isset($item->is_bundle)?$item->is_bundle:0);
            $data[$k]['created_at']=Carbon::now();
            $data[$k]['updated_at']=Carbon::now();
        }
        return $data;
    }

}