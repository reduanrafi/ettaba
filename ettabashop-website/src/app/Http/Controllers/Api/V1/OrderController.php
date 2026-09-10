<?php

namespace App\Http\Controllers\Api\V1;


use App\Services\VirtualBalanceService;
use Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;


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
        $this->globalObject = new Order();
    }
    public function SaveOrder(Request $request)
    {


        $data = json_decode(json_encode($request->order),true) ;
        $paymentMethodId = json_decode(json_encode($request->order))->payment_method_id;
        
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

        $virtualBalance = json_decode(json_encode($request->order))->virtual_balance;
        $virtualBalanceService = new VirtualBalanceService();
//        return response()->json([
//                'status'=>'ok',
//
//                'data'=>$virtualBalance,
//                'message'=>$this->message="Test"
//            ]);

        try
        {
            // Start the transaction
            DB::beginTransaction();

            $paymentMethodId = json_decode(json_encode($request->order))->payment_method_id;
            $orderData = $this->globalObject->GetData(json_decode(json_encode($request->order)));
            
            if ($paymentMethodId == 2) {
                $orderData['status'] = 'pending_payment';
                $orderData['payment_status'] = 'pending';
            }

            // Create the order
            $order = $this->globalObject->create($orderData);

            if ($order) {
                // Update the unique order ID
                if ($this->globalObject->UpdateUniqueOrderId($order->id)) {
                    if ($paymentMethodId == 2) {
                        $epsPaymentService = new \App\Services\EpsPaymentService();
                        $result = $epsPaymentService->initializePayment($order, false);
                        
                        if ($result['status'] === 'success') {
                            DB::commit();
                            return response()->json([
                                'status' => 'ok',
                                'redirect_url' => $result['redirect_url'],
                                'message' => 'Redirecting to payment gateway...'
                            ]);
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'status' => 'error',
                                'message' => $result['message']
                            ], 400);
                        }
                    } else {
                        // Update virtual balance and save the virtual balance for the order
                        $virtualBalanceService->UpdateUsersVirtualBalance($virtualBalance);
                        $virtualBalanceService->SaveVirtualBalance($order->id, $virtualBalance);

                        // Commit the transaction if everything is successful
                        DB::commit();

                        $this->message = 'Order Placed Successfully ';
                    }
                } else {
                    // Roll back if updating the order ID fails
                    DB::rollBack();
                    $this->message = 'Order could not be saved';
                }
            } else {
                // Roll back if creating the order fails
                DB::rollBack();
                $this->message = 'Order creation failed';
            }
        } catch (QueryException $ex) {
            // Roll back on any exception
            DB::rollBack();
            $this->message = $ex->getMessage();
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
