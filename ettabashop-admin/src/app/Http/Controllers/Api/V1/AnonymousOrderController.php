<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AnonymousOrder;
use App\Models\Coupon;
use Illuminate\Database\QueryException;
use Validator;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;


class OrderController extends Controller
{
    private  $message;
    private  $moduleName="Order";
    private $singularVariableName = 'order';
    private $pluralVariableName = 'orders';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;
    public function __construct()
    {
        $this->globalObject = new AnonymousOrder();
    }
    public function SaveOrder(Request $request)
    {

       //return $this->globalObject->GetOrderAmount(28);
        $data = json_decode($request->order,true) ;
        dd($data);


        try
        {
            $order = $this->globalObject->create($this->globalObject->GetData(json_decode($request->order)));

            if ($order)
            {
                if($this->globalObject->UpdateUniqueOrderId($order->id)) {
                    $this->globalObject->GetAndUpdateOrderAmount($order->id);
                    $this->message = 'Order Placed Successfully ';
                    //event(new OrderSuccessEvent($order));
                }
                else{
                    $this->message = 'Order could not saved';

                }
            }
        }
        catch (QueryException $ex)
        {
            $this->message = $ex->getMessage();
        }
        return response()->json([
            'status'=>'ok',
            'message'=>$this->message
        ]);
       // dd($data);
//        $validator = Validator::make($data, $rules);
//
//        if ($validator->passes()) {
//            try
//            {
//                $order = $this->globalObject->create($this->globalObject->GetData(json_decode($request->order)));
//
//                if ($order)
//                {
//                    if($this->globalObject->UpdateUniqueOrderId($order->id)) {
//                        $this->globalObject->GetAndUpdateOrderAmount($order->id);
//                        $this->message = 'Order Placed Successfully ';
//                        //event(new OrderSuccessEvent($order));
//                    }
//                    else{
//                        $this->message = 'Order could not saved';
//
//                    }
//                }
//            }
//            catch (QueryException $ex)
//            {
//                $this->message = $ex->getMessage();
//            }
//            return response()->json([
//                'status'=>'ok',
//                'message'=>$this->message
//            ]);
//
//        } else {
//
//        return response($validator->errors()->all(), 422);
//    }

    }

    public function GetOrdersByUser()
    {
       // dd($this->globalObject->GetOrderByUser());
       // return $this->globalObject->GetOrderByUser();
       return response(OrderResource::collection($this->globalObject->GetOrderByUser()),200);
    }

    public function GetOrderDetail($orderId)
    {
        return response()->json([
            'status'=>'ok',
            'order'=>'object'
        ]);
    }
}
