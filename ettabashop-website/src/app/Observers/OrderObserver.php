<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\CouponService;
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\CustomerGenerationCommissionGroupService;
use App\Services\MerchantCommissionDistributionService;
use App\Services\OrderService;
use App\Services\VirtualBalanceService;
use App\Services\PointService;
use App\Services\EarningService;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Route;

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

        // Insert order items FIRST so that DistributeCommission can load them
        OrderItem::insert($orderService->GetOrderItemsArray(json_decode(json_encode(request()->order))->orderItems,$orderId));


        $commissionDistributionService = new CustomerGenerationCommissionDistributionService();
        $merchantCommissionService = new MerchantCommissionDistributionService();

        // NOW distribute commissions (direct_refer_commission needs items to be present)
        $commissionDistributionService->DistributeCommission($orderId);

        $merchantCommissionService->DistributeRateAndPoint($orderId);
    }


    /**
     * Handle the order "updated" event.
     *
     * @param   \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->isDirty('status') && ($order->status == 'done' || $order->status == 'delivered')) {
            $pointService = new PointService();
            $pointService->UpdatePoint($order->id);

            $earningService = new EarningService();
            $earningService->UpdateEarning($order->id);
        }
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
