<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;
use App\Models\MerchantGateway;
use App\Models\MerchantBalanceAddHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MerchantAddMoneyController extends Controller
{
    /**
     * Display the Merchant Add Money panel.
     */
    public function create()
    {
        $user = Auth::user();
        $gateways = MerchantGateway::where('status', true)
            ->where('code', '!=', 'eps')
            ->get();
        $histories = MerchantBalanceAddHistory::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('handcash.virtual_balance.add_money', compact('user', 'gateways', 'histories'));
    }

    /**
     * Initiate payment transaction.
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

        // Calculate charges
        $amount = (float)$request->amount;
        $chargePercent = (float)$gateway->charge_percent;
        $gatewayCharge = ($amount * $chargePercent) / 100.0;
        $totalPaid = $amount + $gatewayCharge;

        // Generate Transaction ID
        $transactionId = 'TXN-' . date('YmdHis') . strtoupper(Str::random(4));

        // Create transaction log
        $history = MerchantBalanceAddHistory::create([
            'user_id' => Auth::id(),
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'gateway_name' => $gateway->name,
            'gateway_charge' => $gatewayCharge,
            'total_paid' => $totalPaid,
            'status' => 'pending',
        ]);

        Log::info("Merchant Add Money initiated - User: " . Auth::id() . " | TrxID: $transactionId | Amount: $amount | Gateway: {$gateway->name} | Charge: $gatewayCharge | Total: $totalPaid");

        if (in_array($gateway->code, ['eps', 'mfs', 'card', 'amex'])) {
            $epsPaymentService = new \App\Services\EpsPaymentService();
            $result = $epsPaymentService->initializeMerchantPayment($history);

            if ($result['status'] === 'success') {
                return redirect($result['redirect_url']);
            } else {
                $history->update(['status' => 'failed']);
                return redirect()->route('handcash.virtual_balance.create')->with('error', $result['message']);
            }
        }

        return redirect()->route('handcash.add_money.gateway', ['transaction_id' => $transactionId]);
    }

    /**
     * Simulated Payment Gateway portal page.
     */
    public function simulateGateway($transaction_id)
    {
        $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('handcash.virtual_balance.simulate_gateway', compact('history'));
    }

    /**
     * Process simulated payment.
     */
    public function simulateProcess(Request $request, $transaction_id)
    {
        $request->validate([
            'action' => 'required|string|in:success,fail,cancel',
        ]);

        $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($request->action === 'success') {
            return redirect()->route('handcash.add_money.success', ['transaction_id' => $transaction_id]);
        } elseif ($request->action === 'fail') {
            return redirect()->route('handcash.add_money.fail', ['transaction_id' => $transaction_id]);
        } else {
            return redirect()->route('handcash.add_money.cancel', ['transaction_id' => $transaction_id]);
        }
    }

    /**
     * Successful payment callback.
     */
    public function paymentSuccess($transaction_id)
    {
        try {
            DB::beginTransaction();

            $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)
                ->lockForUpdate()
                ->first();

            if (!$history) {
                DB::rollBack();
                return redirect()->route('handcash.virtual_balance.create')->with('error', 'Transaction not found.');
            }

            if ($history->status !== 'pending') {
                DB::rollBack();
                return redirect()->route('handcash.virtual_balance.create')->with('info', 'This transaction is already processed.');
            }

            // Verify payment status if gateway is EPS
            $gateway = MerchantGateway::where('name', $history->gateway_name)->first();
            $paymentReference = 'MOCK-REF-' . strtoupper(Str::random(8));

            if ($gateway && in_array($gateway->code, ['eps', 'mfs', 'card', 'amex'])) {
                $epsService = new \App\Services\EpsPaymentService();
                $epsTransactionId = request()->query('EPSTransactionId');
                
                $statusResult = $epsService->checkTransactionStatus($transaction_id, $epsTransactionId);
                
                if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                    $paymentReference = $epsTransactionId ?? ('EPS-REF-' . strtoupper(Str::random(8)));
                } else {
                    $history->update(['status' => 'failed']);
                    DB::commit();
                    Log::warning("Merchant Add Money EPS verification failed - TrxID: $transaction_id");
                    return redirect()->route('handcash.virtual_balance.create')->with('error', 'EPS Payment verification failed.');
                }
            }

            // Update history status
            $history->update([
                'status' => 'success',
                'payment_reference' => $paymentReference,
            ]);

            // Update user balance
            $user = User::lockForUpdate()->find($history->user_id);
            if ($user) {
                $user->increment('virtual_balance', $history->amount);
            }

            DB::commit();

            Log::info("Merchant Add Money SUCCESS - User: {$history->user_id} | TrxID: $transaction_id | Amount added: {$history->amount}");

            return redirect()->route('handcash.virtual_balance.create')->with('success', "৳ {$history->amount} has been successfully added to your Merchant Balance.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Merchant Add Money Success Callback Exception - TrxID: $transaction_id | Error: " . $e->getMessage());
            return redirect()->route('handcash.virtual_balance.create')->with('error', 'Something went wrong during payment verification.');
        }
    }

    /**
     * Failed payment callback.
     */
    public function paymentFail($transaction_id)
    {
        $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)->first();

        if ($history && $history->status === 'pending') {
            $history->update(['status' => 'failed']);
            Log::warning("Merchant Add Money FAILED - User: {$history->user_id} | TrxID: $transaction_id");
        }

        return redirect()->route('handcash.virtual_balance.create')->with('error', 'Payment transaction failed. Please try again.');
    }

    /**
     * Cancelled payment callback.
     */
    public function paymentCancel($transaction_id)
    {
        $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)->first();

        if ($history && $history->status === 'pending') {
            $history->update(['status' => 'cancelled']);
            Log::info("Merchant Add Money CANCELLED - User: {$history->user_id} | TrxID: $transaction_id");
        }

        return redirect()->route('handcash.virtual_balance.create')->with('warning', 'Payment transaction was cancelled.');
    }
}
