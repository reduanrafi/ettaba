<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Models\MerchantGateway;
use App\Models\SellerBalanceAddHistory;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SellerAddMoneyController extends Controller
{
    /**
     * Display the Seller Add Money panel.
     */
    public function index()
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        $currentEbalance = $shop ? (float) ($shop->seller_ebalance ?? 0) : 0;

        $gateways = [];
        if (Schema::hasTable('merchant_gateways')) {
            $gateways = MerchantGateway::where('status', true)
                ->where('code', '!=', 'eps')
                ->get();
        }

        $histories = [];
        if (Schema::hasTable('seller_balance_add_histories')) {
            $histories = SellerBalanceAddHistory::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('admin.seller.add_money', compact('user', 'shop', 'currentEbalance', 'gateways', 'histories'));
    }

    /**
     * Initiate payment transaction for Seller eBalance.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway_code' => 'required|string|exists:merchant_gateways,code',
        ]);

        $gateway = MerchantGateway::where('code', $request->gateway_code)
            ->where('status', true)
            ->firstOrFail();

        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // Calculate charges
        $amount = (float) $request->amount;
        $chargePercent = (float) $gateway->charge_percent;
        $gatewayCharge = ($amount * $chargePercent) / 100.0;
        $totalPaid = $amount + $gatewayCharge;

        // Generate Transaction ID
        $transactionId = 'STXN-' . date('YmdHis') . strtoupper(Str::random(4));

        // Create transaction log
        $history = SellerBalanceAddHistory::create([
            'user_id' => $user->id,
            'shop_id' => $shop ? $shop->id : null,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'gateway_name' => $gateway->name,
            'gateway_charge' => $gatewayCharge,
            'total_paid' => $totalPaid,
            'status' => 'pending',
        ]);

        Log::info("Seller Add Money initiated - User: {$user->id} | TrxID: $transactionId | Amount: $amount | Gateway: {$gateway->name} | Charge: $gatewayCharge | Total: $totalPaid");

        if (in_array($gateway->code, ['eps', 'mfs', 'card', 'amex'])) {
            $epsPaymentService = new \App\Services\EpsPaymentService();
            // Adapt for merchant / seller payment
            $result = $epsPaymentService->initializeMerchantPayment($history);

            if ($result['status'] === 'success') {
                return redirect($result['redirect_url']);
            } else {
                $history->update(['status' => 'failed']);
                return redirect()->route('seller.addMoney.index')->with('error', $result['message']);
            }
        }

        return redirect()->route('seller.addMoney.gateway', ['transaction_id' => $transactionId]);
    }

    /**
     * Simulated Payment Gateway portal page for testing.
     */
    public function simulateGateway($transaction_id)
    {
        $history = SellerBalanceAddHistory::where('transaction_id', $transaction_id)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('admin.seller.simulate_gateway', compact('history'));
    }

    /**
     * Process simulated payment.
     */
    public function simulateProcess(Request $request, $transaction_id)
    {
        $request->validate([
            'action' => 'required|string|in:success,fail,cancel',
        ]);

        $history = SellerBalanceAddHistory::where('transaction_id', $transaction_id)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($request->action === 'success') {
            return redirect()->route('seller.addMoney.success', ['transaction_id' => $transaction_id]);
        } elseif ($request->action === 'fail') {
            return redirect()->route('seller.addMoney.fail', ['transaction_id' => $transaction_id]);
        } else {
            return redirect()->route('seller.addMoney.cancel', ['transaction_id' => $transaction_id]);
        }
    }

    /**
     * Successful payment callback.
     */
    public function paymentSuccess($transaction_id)
    {
        try {
            DB::beginTransaction();

            $history = SellerBalanceAddHistory::where('transaction_id', $transaction_id)
                ->lockForUpdate()
                ->first();

            if (!$history) {
                DB::rollBack();
                return redirect()->route('seller.addMoney.index')->with('error', 'Transaction not found.');
            }

            if ($history->status !== 'pending') {
                DB::rollBack();
                return redirect()->route('seller.addMoney.index')->with('info', 'This transaction is already processed.');
            }

            // Verify payment status if gateway is EPS
            $gateway = MerchantGateway::where('name', $history->gateway_name)->first();
            $paymentReference = 'MOCK-SELLER-' . strtoupper(Str::random(8));

            if ($gateway && in_array($gateway->code, ['eps', 'mfs', 'card', 'amex'])) {
                $epsService = new \App\Services\EpsPaymentService();
                $epsTransactionId = request()->query('EPSTransactionId');
                
                $statusResult = $epsService->checkTransactionStatus($transaction_id, $epsTransactionId);
                
                if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                    $paymentReference = $epsTransactionId ?? ('EPS-SELLER-' . strtoupper(Str::random(8)));
                } else {
                    $history->update(['status' => 'failed']);
                    DB::commit();
                    Log::warning("Seller Add Money EPS verification failed - TrxID: $transaction_id");
                    return redirect()->route('seller.addMoney.index')->with('error', 'EPS Payment verification failed.');
                }
            }

            // Update history status
            $history->update([
                'status' => 'success',
                'payment_reference' => $paymentReference,
            ]);

            // Credit funds into shop's seller_ebalance
            $shop = Shop::where('user_id', $history->user_id)->lockForUpdate()->first();
            if ($shop) {
                $shop->increment('seller_ebalance', $history->amount);
            }

            DB::commit();

            Log::info("Seller Add Money SUCCESS - User: {$history->user_id} | TrxID: $transaction_id | Amount: {$history->amount}");

            return redirect()->route('seller.addMoney.index')->with('success', "৳ " . number_format($history->amount, 2) . " সফলভাবে আপনার Seller e-Balance এ যোগ করা হয়েছে।");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Seller Add Money Callback Exception - TrxID: $transaction_id | Error: " . $e->getMessage());
            return redirect()->route('seller.addMoney.index')->with('error', 'পেমেন্ট ভেরিফিকেশনে ত্রুটি ঘটেছে।');
        }
    }

    /**
     * Failed payment callback.
     */
    public function paymentFail($transaction_id)
    {
        $history = SellerBalanceAddHistory::where('transaction_id', $transaction_id)->first();

        if ($history && $history->status === 'pending') {
            $history->update(['status' => 'failed']);
            Log::warning("Seller Add Money FAILED - User: {$history->user_id} | TrxID: $transaction_id");
        }

        return redirect()->route('seller.addMoney.index')->with('error', 'পেমেন্ট ব্যর্থ হয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
    }

    /**
     * Cancelled payment callback.
     */
    public function paymentCancel($transaction_id)
    {
        $history = SellerBalanceAddHistory::where('transaction_id', $transaction_id)->first();

        if ($history && $history->status === 'pending') {
            $history->update(['status' => 'cancelled']);
            Log::info("Seller Add Money CANCELLED - User: {$history->user_id} | TrxID: $transaction_id");
        }

        return redirect()->route('seller.addMoney.index')->with('warning', 'পেমেন্ট বাতিল করা হয়েছে।');
    }
}

