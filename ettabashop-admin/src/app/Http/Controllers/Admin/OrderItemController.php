<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnonymousOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Refund;
use App\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    private $globalObject;
    private $moduleName='Order Items';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new OrderItem();
    }
    public function RemoveItem(Request $request)
    {
//        dd($this->globalObject->destroy(intval($request->id)));
        if ($this->globalObject->destroy($request->id))
        {
            if ($this->globalObject->UpdateOrderValue($request,'remove')==1)
            {
                return redirect()->back()->with(['success'=>'Item  removed successfully']);

            }
            else{
                return redirect()->back()->with(['error'=>  "Unable to update price But product removed successfully"]);

            }

        }
        return redirect()->back()->with(['error'=>'unable to remove item ']);

    }
    public function Refund(Request $request)
    {

        $order = Order::where('unique_order_id',$request->order_id)->first();
        $product_id = (isset($request->product_id)?$request->product_id:null);
        $wallet = new Wallet();
        $refund = new Refund();
        if ($order)
        {

            try
            {
                $refund = $refund->create($refund->GetData($order->id,$product_id,$order->user_id,$request->amount));
                if ($refund)
                {
                    $wallet->SaveOrUpdateWallet($order->user_id,$request->amount,'add');
                    $this->globalObject->destroy($request->id);
                    return redirect()->back()->with(['success'=> $this->moduleName." created successfully"]);
                }
                return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
            }
            catch (QueryException $ex)
            {
                return redirect()->back()->with(['error'=>$ex->getMessage()]);
            }
        }
        return redirect()->back()->with(['error'=>"Invalid order ID"]);

    }

    public function AddItem(Request $request)
    {
        //dd($request->all());
        try
        {
            if ($this->globalObject->create($this->globalObject->GetData($request->all())))
            {
                if ($this->globalObject->UpdateOrderValue($request,'add')==1)
                {
                    return redirect()->back()->with(['success'=> $this->moduleName." created successfully"]);

                }
                else{
                    return redirect()->back()->with(['error'=>  "Unable to update price But product added successfully"]);

                }


            }
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
    }
}
