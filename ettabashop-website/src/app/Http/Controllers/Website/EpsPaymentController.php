<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AnonymousOrder;
use App\Services\EpsPaymentService;
use App\Services\VirtualBalanceService;
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\MerchantCommissionDistributionService;
use App\Services\EarningDistribution;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EpsPaymentController extends Controller
{
    protected $epsService;

    public function __construct()
    {
        $this->epsService = new EpsPaymentService();
    }

    public function paymentSuccess(Request $request)
    {
        $merchantTransactionId = $request->input('merchantTransactionId');
        $epsTransactionId = $request->input('EPSTransactionId');

        if (!$merchantTransactionId) {
            return redirect()->route('website.index')->with('error', 'Invalid EPS transaction parameters');
        }

        // Verify status with EPS
        $statusResult = $this->epsService->checkTransactionStatus($merchantTransactionId, $epsTransactionId);

        Log::info("EPS Status Result for transaction {$merchantTransactionId}: " . json_encode($statusResult));

        if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
            
            // Find order
            $order = Order::where('transaction_id', $merchantTransactionId)->first();
            $isAnonymous = false;
            
            if (!$order) {
                $order = AnonymousOrder::where('transaction_id', $merchantTransactionId)->first();
                $isAnonymous = true;
            }

            if ($order) {
                Log::info("EPS Order found for transaction {$merchantTransactionId}: ID #{$order->id}, current status: {$order->status}");
                if ($order->status === 'pending_payment') {
                    try {
                        DB::beginTransaction();

                        // 1. Update Order Status to pending and payment_status to paid
                        $order->status = 'pending';
                        $order->payment_status = 'paid';
                        $order->save();

                        // Log status change to order_statuses
                        DB::table('order_statuses')->insert([
                            'order_id' => $order->id,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        if (!$isAnonymous) {
                            // 2. Process Virtual Balance Deduction
                            if ($order->virtual_balance_used > 0) {
                                $vbService = new VirtualBalanceService();
                                $vbService->UpdateUsersVirtualBalance($order->virtual_balance_used, $order->user_id);
                                $vbService->SaveVirtualBalance($order->id, $order->virtual_balance_used);
                            }

                            // 3. Distribute Commission & Rate/Points if not already distributed
                            $hasPendingFunds = \App\Models\UserPendingFund::where('order_id', $order->id)->exists();
                            $hasPendingPoints = \App\Models\PendingPoint::where('order_id', $order->id)->exists();

                            if (!$hasPendingFunds && !$hasPendingPoints) {
                                $commissionService = new CustomerGenerationCommissionDistributionService();
                                $commissionService->DistributeCommission($order->id);

                                $merchantCommissionService = new MerchantCommissionDistributionService();
                                $merchantCommissionService->DistributeRateAndPoint($order->id);
                            }

                        }

                        DB::commit();
                        Log::info("EPS Order paid successfully: #{$order->id} (Transaction ID: {$merchantTransactionId})");
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error completing EPS Order #{$order->id}: " . $e->getMessage());
                        return view('website.payment_result', [
                            'status' => 'error',
                            'message' => 'Payment succeeded, but an error occurred updating your order: ' . $e->getMessage(),
                            'order' => $order
                        ]);
                    }
                }

                return view('website.payment_result', [
                    'status' => 'success',
                    'message' => 'Your payment has been successfully completed.',
                    'order' => $order
                ]);
            }
        }

        return view('website.payment_result', [
            'status' => 'failed',
            'message' => 'We could not verify your payment status with the gateway.'
        ]);
    }

    public function paymentFail(Request $request)
    {
        $merchantTransactionId = $request->input('merchantTransactionId');
        
        $order = Order::where('transaction_id', $merchantTransactionId)->first();
        if (!$order) {
            $order = AnonymousOrder::where('transaction_id', $merchantTransactionId)->first();
        }

        if ($order) {
            $order->orderItems()->delete();
            $order->delete();
        }

        return view('website.payment_result', [
            'status' => 'failed',
            'message' => 'The payment transaction failed at the gateway.'
        ]);
    }

    public function paymentCancel(Request $request)
    {
        $merchantTransactionId = $request->input('merchantTransactionId');
        
        $order = Order::where('transaction_id', $merchantTransactionId)->first();
        if (!$order) {
            $order = AnonymousOrder::where('transaction_id', $merchantTransactionId)->first();
        }

        if ($order) {
            $order->orderItems()->delete();
            $order->delete();
        }

        return view('website.payment_result', [
            'status' => 'cancelled',
            'message' => 'The payment transaction was cancelled by the user.'
        ]);
    }
}
