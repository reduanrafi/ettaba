<?php

namespace App\Models;

use App\Services\EarningDistribution;
use App\Services\PointService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class OrderStatus extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = ['order_id','status'];

    /****************************
     * Model Relation area
     *****************************/

    public function order()
    {
        return $this->belongsTo(  Order::class);
    }

    /****************************
     * Public Methods area
     *****************************/

    /***
     * Method to get data.
     * @param $data
     * @return
     */

    public function GetData($data)
    {

        $data['order_id'] = $data['id'];

        return $data;
    }

    public function ChangeOrderStatus($id,$status)
    {
        if ($status=='canceled')
        {
            $this->RemovePendingFundAndPoints($id);

            // Sync item-level seller statuses and restore inventory stock (Pages 3 & 13 spec)
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('order_items')) {
                    $orderItems = \App\Models\OrderItem::where('order_id', $id)->get();
                    foreach ($orderItems as $item) {
                        // Restore product stock quantity if not already canceled
                        if (($item->seller_status ?? '') !== 'canceled' && !empty($item->product_id)) {
                            $product = \App\Models\Product::find($item->product_id);
                            if ($product) {
                                $restoreQty = max(1, (int)$item->quantity);
                                $product->increment('quantity', $restoreQty);
                                if ($product->is_sold_out && $product->quantity > 0) {
                                    $product->is_sold_out = 0;
                                    $product->save();
                                }
                            }
                        }

                        // Update seller_status to canceled
                        if (\Illuminate\Support\Facades\Schema::hasColumn('order_items', 'seller_status')) {
                            $item->seller_status = 'canceled';
                            if (\Illuminate\Support\Facades\Schema::hasColumn('order_items', 'seller_updated_at')) {
                                $item->seller_updated_at = now();
                            }
                            $item->save();
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Error syncing canceled order items: " . $e->getMessage());
            }
        }
        return DB::table('orders')->where('id',$id)->update(['status'=>$status]);
    }

    /****************************
     * Public Methods area
     *****************************/
    public function GetOrderStatusByOrder($orderId)
    {

        return OrderStatus::where('order_id',$orderId)->get();
    }

    public function RemovePendingFundAndPoints($orderId)
    {
        $pointService = new PointService();

        $earningService = new EarningDistribution();

        try{

            $earningService->RemoveCustomerPendingAmount($orderId) ;

            $pointService->RemovePendingFund($orderId);

            return 1;
        }

        catch (QueryException $exception)
        {
            return 0;
        }


    }
}
