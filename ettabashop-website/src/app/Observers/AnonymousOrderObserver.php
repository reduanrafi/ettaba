<?php

namespace App\Observers;


use App\Models\AnonymousOrder;
use App\Models\AnonymousOrderItem;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;


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
        AnonymousOrderItem::insert($orderService->GetOrderItemsArray(json_decode(json_encode(request()->order))->orderItems,$orderId));
        Log::info($order);
    }

    /**
     * Handle the order "updated" event.
     *
     * @param   \App\Models\Order  $order
     * @return void
     */
    public function updated(AnonymousOrder $order)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param   \App\Models\Order   $order
     * @return void
     */
    public function deleted(AnonymousOrder $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param   \App\Models\Order   $order
     * @return void
     */
    public function restored(AnonymousOrder $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param   \App\Models\Order   $order
     * @return void
     */
    public function forceDeleted(AnonymousOrder $order)
    {
        //
    }
}
