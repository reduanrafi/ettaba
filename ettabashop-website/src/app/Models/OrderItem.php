<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = ['order_id','product_id','quantity','trp','price'];

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
       //dd($order);
       if ($product!=null && $order !=null) {
           if ($operation == 'add') {
               $amount = $product->price_en + $order->amount;

           } else {
               $amount = $order->amount - $product->price_en;

           }
           if($order->update(['amount'=>$amount]))
           {
               return 1;
           }
       }


       return 0;
    }


}
