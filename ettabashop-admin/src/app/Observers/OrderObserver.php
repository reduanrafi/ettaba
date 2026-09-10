<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserCoupon;
use App\Services\CouponService;
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\OrderService;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;

class OrderObserver
{

    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Services\OrderService
     * @return void
     */
    public function created(Order $order )
    {
        $orderService = new OrderService();
        $orderId = $order->id;
        
        OrderItem::insert($orderService->GetOrderItemsArray(json_decode(json_encode(request()->order))->orderItems,$orderId));


        $commissionDistributionService = new CustomerGenerationCommissionDistributionService();
        $commissionDistributionService->DistributeCommission($orderId, '');
        Log::info("Commission distributed for regular order #" . $orderId);
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
