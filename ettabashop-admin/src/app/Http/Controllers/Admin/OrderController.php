<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;

use App\Models\Shop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use function Psy\debug;

class OrderController extends Controller
{
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

    public function Index(Request $request)
    {
        // Auto-verify EPS orders that are pending_payment from today
        $pendingEpsToday = \App\Models\Order::with('paymentMethod')
            ->where('status', 'pending_payment')
            ->where('created_at', '>=', \Carbon\Carbon::today())
            ->whereHas('paymentMethod', function ($q) {
                $q->where('short_code', 'EPS');
            })
            ->get();

        foreach ($pendingEpsToday as $order) {
            $this->autoVerifyEpsOrder($order, false);
        }

        $this->retrievedDataList =$this->globalObject->GetOrders($request->state);
        
        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }
    public function GetOrderByStatus(Request $request)
    {
        $orders = $this->globalObject->GetOrderByStatus($request->state);
        
        $html = view('admin.orders.order_table', compact('orders'))->render();

        return $html;
    }


    public function Detail($id)
    {
        $order = $this->globalObject->GetDetail($id);

        if ($order && ($order->paymentMethod->short_code ?? '') === 'EPS' && ($order->payment_status ?? '') !== 'paid') {
            $this->autoVerifyEpsOrder($order, false);
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

    private function settleOrderCommissions($order, $isAnonymous)
    {
        if ($isAnonymous) {
            return;
        }

        // Check if commissions were already distributed
        $hasPendingFunds = \App\Models\UserPendingFund::where('order_id', $order->id)->exists();
        $hasPendingPoints = \App\Models\PendingPoint::where('order_id', $order->id)->exists();

        if (!$hasPendingFunds && !$hasPendingPoints) {
            // Distribute commissions to PENDING tables only
            $commissionService = new \App\Services\CustomerGenerationCommissionDistributionService();
            $commissionService->DistributeCommission($order->id, '');

            $merchantCommissionService = new \App\Services\MerchantCommissionDistributionService();
            $merchantCommissionService->DistributeRateAndPoint($order->id);
        }

        // NOTE: DistributeEarning() and UpdatePoint() are NOT called here.
        // Settlement from pending → main balance happens via OrderObserver::updated()
        // when the order status changes to 'done' or 'delivered'.
    }

    public function autoVerifyEpsOrder($order, $isAnonymous = false)
    {
        if ($order && $order->transaction_id && ($order->payment_status ?? '') !== 'paid') {
            $epsService = new \App\Services\EpsPaymentService();
            $statusResult = $epsService->checkTransactionStatus($order->transaction_id);

            if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                try {
                    \Illuminate\Support\Facades\DB::beginTransaction();

                    $statusChanged = false;
                    if ($order->status === 'pending_payment') {
                        $order->status = 'pending';
                        $statusChanged = true;
                    }
                    $order->payment_status = 'paid';
                    $order->save();

                    if ($statusChanged && !$isAnonymous && $order->virtual_balance_used > 0) {
                        // Process virtual balance deduction if used
                        $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $order->user_id)->first();
                        if ($user) {
                            \Illuminate\Support\Facades\DB::table('users')->where('id', $order->user_id)->update([
                                'virtual_balance' => $user->virtual_balance - $order->virtual_balance_used
                            ]);
                        }
                        \Illuminate\Support\Facades\DB::table('virtual_balances')->insert([
                            'user_id' => $order->user_id,
                            'amount' => $order->virtual_balance_used,
                            'status' => 'outgoing',
                            'is_completed' => 1,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    $this->settleOrderCommissions($order, $isAnonymous);

                    \Illuminate\Support\Facades\DB::commit();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    \Illuminate\Support\Facades\Log::error("Auto-verify EPS Order #{$order->id} failed: " . $e->getMessage());
                }
            }
        }
    }

    public function verifyEpsPayment($id)
    {
        $order = Order::find($id);
        $isAnonymous = false;

        if (!$order) {
            $order = \App\Models\AnonymousOrder::find($id);
            $isAnonymous = true;
        }

        if (!$order) {
            return redirect()->back()->with(['error' => 'Order not found']);
        }

        if (!$order->transaction_id) {
            return redirect()->back()->with(['error' => 'Order does not have an associated EPS Transaction ID']);
        }

        $epsService = new \App\Services\EpsPaymentService();
        $statusResult = $epsService->checkTransactionStatus($order->transaction_id);

        if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
            try {
                \Illuminate\Support\Facades\DB::beginTransaction();

                $statusChanged = false;
                if ($order->status === 'pending_payment') {
                    $order->status = 'pending';
                    $statusChanged = true;
                }
                $order->payment_status = 'paid';
                $order->save();

                if ($statusChanged && !$isAnonymous && $order->virtual_balance_used > 0) {
                    // Process virtual balance deduction if used
                    $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $order->user_id)->first();
                    if ($user) {
                        \Illuminate\Support\Facades\DB::table('users')->where('id', $order->user_id)->update([
                            'virtual_balance' => $user->virtual_balance - $order->virtual_balance_used
                        ]);
                    }
                    \Illuminate\Support\Facades\DB::table('virtual_balances')->insert([
                        'user_id' => $order->user_id,
                        'amount' => $order->virtual_balance_used,
                        'status' => 'outgoing',
                        'is_completed' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $this->settleOrderCommissions($order, $isAnonymous);

                \Illuminate\Support\Facades\DB::commit();
                return redirect()->back()->with(['success' => 'Payment verified and settled successfully!']);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                \Illuminate\Support\Facades\Log::error("Manual verify EPS Order #{$order->id} failed: " . $e->getMessage());
                return redirect()->back()->with(['error' => 'Payment verified but order processing failed: ' . $e->getMessage()]);
            }
        }

        $gatewayStatus = $statusResult['data']['Status'] ?? ($statusResult['message'] ?? 'Unknown');
        return redirect()->back()->with(['error' => 'Gateway returned status: ' . $gatewayStatus]);
    }

    public function pendingEpsOrders()
    {
        // Get all orders that have pending_payment status OR are EPS orders with unpaid payment_status
        $pendingOrders = Order::with('user.profile', 'paymentMethod', 'orderItems.product')
            ->where(function ($query) {
                $query->where('status', 'pending_payment');
            })
            ->orWhere(function ($query) {
                $query->whereHas('paymentMethod', function ($q) {
                    $q->where('short_code', 'EPS');
                })->where('payment_status', '!=', 'paid');
            })
            ->orderByDesc('id')
            ->get();

        $totalPending = $pendingOrders->count();
        $totalAmount = $pendingOrders->sum('erp_total');

        return view('admin.orders.pending_eps', [
            'orders' => $pendingOrders,
            'totalPending' => $totalPending,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function bulkVerifyEps()
    {
        $pendingOrders = Order::with('paymentMethod')
            ->where('status', 'pending_payment')
            ->whereHas('paymentMethod', function ($q) {
                $q->where('short_code', 'EPS');
            })
            ->get();

        $verified = 0;
        $failed = 0;

        foreach ($pendingOrders as $order) {
            $this->autoVerifyEpsOrder($order, false);

            // Re-fetch to check if it was verified
            $refreshed = Order::find($order->id);
            if ($refreshed && $refreshed->payment_status === 'paid') {
                $verified++;
            } else {
                $failed++;
            }
        }

        $message = "{$verified} order(s) verified successfully.";
        if ($failed > 0) {
            $message .= " {$failed} order(s) could not be verified (payment not completed at gateway).";
        }

        return redirect()->route('order.pendingEps')->with(['success' => $message]);
    }

}
