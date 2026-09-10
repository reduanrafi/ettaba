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
