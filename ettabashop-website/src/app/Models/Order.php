<?php

namespace App\Models;

use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    /****************************
     * Property area
     *****************************/

    protected $fillable = [
        'user_id',
        'owner_id',
        'discount_id',
        'address_id',
        'payment_method_id',
        'amount',
        'trp',
        'tcb',
        'net_total',
        'erp_total',
        'rate_total',
        'unique_order_id',
        'coupon_id',
        'delivery_charge_id',
        'delivery_charge',
        'transaction_id',
        'payment_status',
        'virtual_balance_used',
        'status',
        //'total_saved'
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function user()
    {
        return $this->belongsTo(  User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(  PaymentMethod::class);
    }

    public function orderItems()
    {
        return $this->hasMany(  OrderItem::class, 'order_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function status()
    {
        return $this->hasMany(OrderStatus::class);
    }
    public function deliveryCharge()
    {
        return $this->belongsTo(DeliveryCharge::class);
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
        return $this->ObjectToArray($data);
    }
    public function GetCouponId($coupon)
    {
        $couponId =null;
        if (isset($coupon->coupon_id)) {
            $couponId =$coupon->coupon_id;
            $coupon = new Coupon();
            if ($coupon->CheckFrequency($couponId) == 0) {
                $couponId=null;
            }
        }

        return $couponId;
    }
    public function GetOrders($state)
    {
        if ($state=='')
        {

            return $this->GetDailyOrders();
        }
        elseif ($state=='all')
        {
            return $this->all();
        }
        elseif ($state=='new')
        {
            return $this->NewOrders('pending');
        }
        else
        {
            return $this->GetOrderByStatus($state);
        }


    }
    public static function all($key=null)
    {
        return Order::with('user','paymentMethod')->where('orders.status', '!=', 'pending_payment')->orderByDesc('id')->get();
    }
    public function GetDailyOrders()
    {
        return Order::with('user','paymentMethod')->where('orders.status', '!=', 'pending_payment')->where('orders.created_at','>=',Carbon::today())->orderByDesc('id')->get();

    }
    public function NewOrders($status)
    {
        return Order::with('user','paymentMethod')->where('orders.status',$status)->where('orders.created_at','>=',Carbon::today())->orderByDesc('id')->get();

    }
    public function GetOrderByStatus($status)
    {
        return Order::with('user','paymentMethod')->where('orders.status',$status)->orderByDesc('id')->get();
    }
    public function GetDetail($id)
    {
        return Order::with('user','paymentMethod','address','orderItems.product')->where('orders.id',$id)->first();

    }
    public function GetOrderByUser()
    {
        return Order::with('paymentMethod','address','orderItems.product','status')->where('orders.status', '!=', 'pending_payment')->where('user_id',Auth::user()->id)->orderByDesc('id')->get();

    }

    public static function TotalSalesAmount()
    {
        return Order::where('status','done')->orwhere('status','delivered')->sum('amount');
    }
    public function CheckBundle($products)
    {

        foreach($products as $product)
        {
            if($product->is_bundle==1)
            {

                 $test = $this->GetBundleProducts($product->product_id);
                //dd($product->product_id );
            }
        }
        return $products;
    }
    public function GetBundleProducts($id)
    {
        //dd($id);
       return Bundle::with('products')->find($id);
    }
    public function ObjectToArray($orderObject)
    {
        $data['payment_method_id']=$orderObject->payment_method_id;
        $data['address_id']=$orderObject->address_id;
        $data['user_id'] = Auth::user()->id;
        //$data['amount'] = $orderObject->amount;
        $data['trp'] = $orderObject->trp;
        $data['tcb'] = $orderObject->tcb;
        $data['net_total'] = $orderObject->net_total;
        $data['rate_total'] = $orderObject->rate_total;
        $data['erp_total'] = $orderObject->erp_total;
        $data['virtual_balance_used'] = $orderObject->virtual_balance ?? 0;
       // $data['delivery_charge_id'] = (isset($orderObject->delivery_charge_id)?$orderObject->delivery_charge_id:null);
        //$data['coupon_id'] = $this->GetCouponId($orderObject);
        return $data;
    }

    public function GetAndUpdateOrderAmount($id)
    {
        $orderAmount = 0;
        $discount = 0;
//        $order = Order::with('orderItems.product','deliveryCharge')->find($id);
        $order = Order::with('orderItems.product')->find($id);
       // $deliveryCharge = isset($order->deliveryCharge)?$order->deliveryCharge->charge_amount:0;
       // dd($order->deliveryCharge);
        if ($order->orderItems!=null){
            foreach ($order->orderItems as $item)
            {

                $orderAmount+= (($item->product->price_en-$item->product->discount)*$item->quantity);

                if ($item->product->discount>0 || $item->product->discount!=null)
                {
                    $discount+= $item->product->discount;
                }
            }
        }

       // $orderAmount = $orderAmount+$deliveryCharge;
        if ($order->update(
            [
                'amount'=>$orderAmount,
                'total_saved'=>$discount,
                //'delivery_charge'=>$deliveryCharge,
            ]
        ))
        {
            return 1;
        }
        return 0;

    }


    public function AddDiscountToOrder($orderId,$id)
    {
        //dd($id);
        $data['discount_id']=($id=="NULL"?NULL:$id);
        $order = Order::find($orderId);
        return $order->update($data);
    }
    public function UniqueOrderId($orderId=0)
    {
        $carbon =Carbon::now();
//        $orderId =strtoupper($carbon->shortMonthName) .$carbon->day. $this->ShortYear($carbon->year).$orderId ;
        $orderId ="ESL".$orderId ;
        return $orderId;
    }

    public function UpdateUniqueOrderId($id)
    {
        $data['unique_order_id']=$this->UniqueOrderId($id);
        $order = Order::find($id);

        return $order->update($data);
    }

    public function ShortYear($year)
    {
        return substr($year,2);
    }

    /****************************
     * private Methods area
     *****************************/



    public  function CalculateTotalAmount($orderObject)
    {
        $totalAmount = 0;
        foreach ($orderObject->orderItems as $item)
        {
            $totalAmount+=$item->price;
        }
        return $totalAmount;
    }

    public function GetAmount($data)
    {
        $products =$data->orderItems;
        $discount=$data->discount;
        $coupon=$data->coupon;
        $total['itemTotal'] = self::GetTotalPrice($products);
        $total['deliveryCharge'] = self::GetDeliveryChargeAmount($data->deliveryCharge);
        $total['discount'] = self::GetDiscountAmount($total['itemTotal'],$discount);
        $total['coupon'] = self::GetCouponAmount($total['itemTotal'],$coupon);
        $total['deduction'] = self::GetTotalDeducted($total['discount'],$total['coupon']);
        $total['netTotal'] = ($total['deliveryCharge']+$total['itemTotal'])-$total['deduction'];
        return $total;
    }
    public static function GetTotalPrice($products)
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += self::GetProductPrice($product);
        }
        //dd($totalPrice);
        return $totalPrice;
    }

    public static function  GetProductPrice($product)
    {
        //$productPrice = $product->product->price_en+self::GetPercentInValue($product->product->price_en,$product->product->vat_percent);
        $productPrice = $product->price+self::GetPercentInValue($product->price,$product->product->vat_percent);
        $productPrice = $productPrice * $product->quantity;

        return $productPrice;
    }
    public function GetTotalDeducted($discount,$coupon)
    {
        return $discount+$coupon;
    }

    public static function GetPercentInValue($amount,$percent)
    {
        return floatval((intval($percent)*intval($amount))/100);
    }

    public static function GetDiscountAmount($total,$discount)
    {
        if ($discount!=null)
        {
            if ($discount->is_percent==1)
            {
                return self::GetPercentInValue($total,$discount->amount);
            }
            return $discount->amount;
        }
        return 0;
    }
    public static function GetCouponAmount($total,$coupon)
    {
        if ($coupon!=null)
        {
            if ($coupon->is_percent==1)
            {
                return self::GetPercentInValue($total,$coupon->amount);
            }
            return $coupon->amount;
        }
        return 0;
    }

    public static function GetDeliveryChargeAmount($deliveryCharge)
    {
        if ($deliveryCharge!=null)
        {

            return $deliveryCharge->charge_amount;
        }
        return 0;
    }
}
