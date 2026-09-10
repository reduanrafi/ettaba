<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandCashOrderItem extends Model
{
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = ['hand_cash_order_id','owner_id','hand_cash_product_id','quantity','price','trp','tcb'];

    /****************************
     * Model Relation area
     *****************************/

    public function order()
    {
        return $this->belongsTo(HandCashOrder::class, 'hand_cash_order_id');
    }
    public function product()
    {
        return $this->belongsTo(HandCashProduct::class, 'hand_cash_product_id');
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
        $data['price'] = $this->GetProductPrice($data['product_id']);

        return $data;
    }
    public function GetProductPrice($productId)
    {
        return HandCashProduct::find($productId)->price_en;
    }
    public function UpdateOrderValue($request,$operation="add")
    {
        $product = Product::find($request->product_id);
        $order = Order::find($request->order_id);



        if ($product!=null && $order !=null) {

            $trp= $order->trp-$product->trp_en;
            $tcb= $order->tcb-$product->tcb_en;
            $erp= $order->erp_total-$product->erp_en;
            $rate= $order->rate_total-$product->rate_en;
            //dd($order->update(['tr'=>3]));

            if($order->update([
                'trp'=>$trp,
                'tcb'=>$tcb,
                'erp_total'=>$erp,
                'rate_total'=>$rate,
            ]))
            {
                return 1;
            }
        }


        return 0;
    }

}
