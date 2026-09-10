<?php

namespace App\Observers;

use App\Models\HandCashOrder;
use App\Models\HandCashOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CouponService;
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\EarningDistribution;
use App\Services\HandCashOrderService;
use App\Services\MerchantCommissionDistributionService;
use App\Services\OrderService;
use App\Services\PointService;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Route;

class HandCashOrderObserver
{

    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Services\OrderService
     * @return void
     */
    public function created(HandCashOrder $order )
    {

        $orderService = new HandCashOrderService();
        $commissionDistributionService = new CustomerGenerationCommissionDistributionService();
        $merchantCommissionService = new MerchantCommissionDistributionService();
        $earningService = new EarningDistribution();
        $pointService = new PointService();

        $orderId = $order->id;
        HandCashOrderItem::insert($orderService->GetOrderItemsArray(request()['order']['orderItems'],$orderId));

        $commissionDistributionService->DistributeCommission($orderId,'handCash');
    \Log::info($orderService->GetOrderItemsArray(request()['order']['orderItems'],$orderId));
       // $couponService->UserCoupon(json_decode(request()->order));

        $merchantCommissionService->DistributeRateAndPoint($orderId);

        $earningService->DistributeEarning($orderId);

        $pointService->UpdatePoint($orderId);
    }

    /**
     * Handle the order "updated" event.
     *
     * @param   \App\Models\Order  $order
     * @return void
     */
//    public function updated(Order $order)
//    {
//        //
//    }
//
//    /**
//     * Handle the order "deleted" event.
//     *
//     * @param   \App\Models\Order   $order
//     * @return void
//     */
//    public function deleted(Order $order)
//    {
//        //
//    }
//
//    /**
//     * Handle the order "restored" event.
//     *
//     * @param   \App\Models\Order   $order
//     * @return void
//     */
//    public function restored(Order $order)
//    {
//        //
//    }
//
//    /**
//     * Handle the order "force deleted" event.
//     *
//     * @param   \App\Models\Order   $order
//     * @return void
//     */
//    public function forceDeleted(Order $order)
//    {
//        //
//    }
}
