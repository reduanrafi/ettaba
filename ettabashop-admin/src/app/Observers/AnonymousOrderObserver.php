<?php

namespace App\Observers;


use App\Models\AnonymousOrder;
use App\Models\AnonymousOrderItem;
use App\Services\OrderService;


class AnonymousOrderObserver
{

    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Services\OrderService
     * @return void
     */
    public function created(AnonymousOrder $order )
    {

        $orderService = new OrderService();

        $orderId = $order->id;
        AnonymousOrderItem::insert($orderService->GetOrderItemsArray(json_decode(request()->order)->orderItems,$orderId));

    }

    /**
     * Handle the order "updated" event.
     *
     * @param   \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param   \App\Models\Order   $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param   \App\Models\Order   $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param   \App\Models\Order   $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
