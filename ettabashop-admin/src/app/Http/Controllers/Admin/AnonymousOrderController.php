<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnonymousOrder;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AnonymousOrderController extends Controller
{
    private  $moduleName="Order";
    private $singularVariableName = 'anonymous_order';
    private $pluralVariableName = 'anonymous_orders';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new AnonymousOrder();
    }

    public function Index(Request $request)
    {
        $this->retrievedDataList =$this->globalObject->GetOrders($request->state);
        //dd($this->retrievedDataList);
        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }
    public function GetOrderByStatus(Request $request)
    {
        $orders = $this->globalObject->GetOrderByStatus($request->state);
        //dd($orders);
        $html = view('admin.orders.order_table', compact('orders'))->render();

        return $html;
    }
    public function ChangeStatus(Request $request)
    {


        try {
            if ($this->globalObject->ChangeOrderStatus($request->id, $request->status)) {



                return redirect()->back()->with(['success' => $this->moduleName . " updated successfully"]);
            }
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
        return redirect()->back()->with(['error' => "Unable to handle this request !"]);
    }
    public function Done(Request $request)
    {
        try {


            $this->globalObject->ChangeOrderStatus($request->id, 'completed');

            return redirect()->back()->with(['success' => $this->moduleName . " updated successfully"]);

        } catch (QueryException $ex) {

            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }

    }
    public function autoVerifyEpsOrder($order)
    {
        if ($order && $order->transaction_id && ($order->payment_status ?? '') !== 'paid') {
            $epsService = new \App\Services\EpsPaymentService();
            $statusResult = $epsService->checkTransactionStatus($order->transaction_id);

            if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                if ($order->status === 'pending_payment') {
                    try {
                        \Illuminate\Support\Facades\DB::beginTransaction();

                        $order->status = 'pending';
                        $order->payment_status = 'paid';
                        $order->save();

                        \Illuminate\Support\Facades\DB::commit();
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\DB::rollBack();
                        \Illuminate\Support\Facades\Log::error("Auto-verify EPS Anonymous Order #{$order->id} failed: " . $e->getMessage());
                    }
                } else {
                    $order->payment_status = 'paid';
                    $order->save();
                }
            }
        }
    }

    public function Detail($id)
    {
        $order = $this->globalObject->GetDetail($id);

        if ($order && ($order->paymentMethod->short_code ?? '') === 'EPS' && ($order->payment_status ?? '') !== 'paid') {
            $this->autoVerifyEpsOrder($order);
            // Refresh detail to get updated status and relations
            $order = $this->globalObject->GetDetail($id);
        }

        $this->singleData = $order;
        $products = Product::all();

        return view('admin.'.$this->pluralVariableName.'.detail',[
            $this->singularVariableName=>$this->singleData,
            'products'=>$products,

        ]);
    }
    public function Invoice($id)
    {
        $this->singleData=$this->globalObject->GetDetail($id);
        //dd( $this->singleData);
        $products = Product::all();
        // $total = $this->globalObject->GetAmount($this->singleData);

        return view('admin.'.$this->pluralVariableName.'.invoice',[
            $this->singularVariableName=>$this->singleData,
            'products'=>$products,

        ]);
    }

    public function Shop(Request $request)
    {
        $shop = new Shop();
        return view('admin.orders.shop',[

            'shop'=>$shop->GetShop($request->id),

        ]);
    }
    public function Discount(Request $request)
    {

        if($this->globalObject->AddDiscountToOrder($request->id,$request->discount_id))
        {
            return redirect()->back()->with(['success'=>'Discount added successfully']);
        }
        return redirect()->back()->with(['error'=>'unable to add Discount']);
    }

    public function destroy($id)
    {
        try{
            if ($this->globalObject->destroy($id)){
                return redirect()->back()->with(['success'=>$this->moduleName."  deleted successfully"]);
            }
        }
        catch (QueryException $exception){
            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
    }

//    public function invoice($id)
//    {
//        $this->singleData=$this->globalObject->GetDetail($id);
//        $products = $this->globalObject->CheckBundle($this->singleData->orderItems);
//        //dd( $this->singleData);
//       // $discounts = Discount::all();
//
//        $total = $this->globalObject->GetAmount($this->singleData);
//
//        return view('admin.'.$this->pluralVariableName.'.invoice',[
//            $this->singularVariableName=>$this->singleData,
//            //'discounts'=>$discounts,
//            'products'=>$products,
//            'total'=>$total
//        ]);
//
//    }
}
