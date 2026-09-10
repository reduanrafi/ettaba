<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = ['order_id','product_id','quantity','price'];

    /****************************
     * Model Relation area
     *****************************/

    public function order()
    {
        return $this->belongsTo(  User::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
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
        return Product::find($productId)->price_en;
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
