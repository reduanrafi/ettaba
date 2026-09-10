<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DirectOrderController extends Controller
{
    private  $moduleName="Direct Order";
    private $singularVariableName = 'direct_order';
    private $pluralVariableName = 'direct_orders';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new DirectOrder();
    }

    public function Index(Request $request)
    {
        $this->retrievedDataList =$this->globalObject->GetDirectOrders($request->state);
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


    public function Detail($id)
    {
        $this->singleData=$this->globalObject->GetDetail($id);
        // dd( $this->singleData);
        $products = Product::all();
        // $total = $this->globalObject->GetAmount($this->singleData);

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
