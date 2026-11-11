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


class AnonymousOrderController extends Controller
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
        $data = json_decode(json_encode($request->order),true) ;
        $paymentMethodId = json_decode(json_encode($request->order))->payment_method_id;
        
        // Validate anonymous customer information (Page 16 spec)
        $validator = Validator::make($data, [
            'username' => [
                'required',
                'string',
                'regex:/^[\p{Bengali}a-zA-Z\s]+$/u'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^01[0-9]{9}$/'
            ],
            'address' => [
                'required',
                'string',
                'regex:/^[\p{Bengali}a-zA-Z0-9\s,\-\.\#\/]+$/u'
            ],
        ], [
            'username.required' => 'নাম দেওয়া আবশ্যক।',
            'username.regex' => 'নামে শুধু বাংলা অথবা ইংরেজি বর্ণ ব্যবহার করুন (কোনো সংখ্যা বা চিহ্ন নয়)।',
            'phone.required' => 'মোবাইল নম্বর দেওয়া আবশ্যক।',
            'phone.regex' => 'মোবাইল নম্বরে শুধুমাত্র ১১ সংখ্যার ইংরেজি সংখ্যা দিন (01 দিয়ে শুরু হতে হবে)।',
            'address.required' => 'ঠিকানা দেওয়া আবশ্যক।',
            'address.regex' => 'ঠিকানায় শুধু বাংলা, ইংরেজি বর্ণ ও স্ট্যান্ডার্ড চিহ্ন ব্যবহার করুন।',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Security check for Advance Payment Required categories
        $orderItems = isset($data['orderItems']) ? $data['orderItems'] : [];
        $productIds = array_column($orderItems, 'product_id');
        $advancePaymentRequired = false;
        
        if (!empty($productIds)) {
            $products = \App\Models\Product::whereIn('id', $productIds)->get();
            foreach ($products as $product) {
                if ($product->category_id) {
                    $catId = $product->category_id;
                    while ($catId) {
                        $categoryObj = \App\Models\Category::find($catId);
                        if ($categoryObj) {
                            if ($categoryObj->advance_payment_required) {
                                $advancePaymentRequired = true;
                                break 2;
                            }
                            $catId = $categoryObj->parent_id;
                        } else {
                            break;
                        }
                    }
                }
            }
        }
        
        if ($advancePaymentRequired && $paymentMethodId != 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Online payment is required for this order.'
            ], 400);
        }

//        return response()->json([
//                'status'=>'ok',
//
//                'data'=>$this->globalObject->GetData(json_decode(json_encode($request->order))),
//                'message'=>$this->message="Test"
//            ]);
        try
        {
            $paymentMethodId = json_decode(json_encode($request->order))->payment_method_id;
            $orderData = $this->globalObject->GetData(json_decode(json_encode($request->order)));
            
            if ($paymentMethodId == 2) {
                $orderData['status'] = 'pending_payment';
                $orderData['payment_status'] = 'pending';
            }

            $order = $this->globalObject->create($orderData);

            if ($order)
            {
                if($this->globalObject->UpdateUniqueOrderId($order->id)) {
                    if ($paymentMethodId == 2) {
                        $epsPaymentService = new \App\Services\EpsPaymentService();
                        $result = $epsPaymentService->initializePayment($order, true);
                        
                        if ($result['status'] === 'success') {
                            return response()->json([
                                'status' => 'ok',
                                'redirect_url' => $result['redirect_url'],
                                'message' => 'Redirecting to payment gateway...'
                            ]);
                        } else {
                            $order->delete();
                            return response()->json([
                                'status' => 'error',
                                'message' => $result['message']
                            ], 400);
                        }
                    } else {
                        $this->message = 'Order Placed Successfully ';
                    }
                }
                else{
                    $this->message = 'Order could not saved';
                }
            }
        }
        catch (QueryException $ex)
        {
            $this->message = $ex->getMessage();
            // $this->message = 'Server error! Please try again later';
        }
        return response()->json([
            'status'=>'ok',
            'message'=>$this->message
        ]);


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
