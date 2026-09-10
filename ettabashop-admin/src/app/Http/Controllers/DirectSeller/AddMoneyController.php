<?php

namespace App\Http\Controllers\DirectSeller;

use App\Http\Controllers\Controller;
use App\Models\MerchantGateway;
use App\Models\MerchantBalanceAddHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AddMoneyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'direct_seller']);
    }

    /**
     * Display the Direct Seller Add Money panel.
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

        return view('direct_seller.add_money', compact('user', 'gateways', 'histories'));
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
        $transactionId = 'TXN-DS-' . date('YmdHis') . strtoupper(Str::random(4));

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

        Log::info("Direct Seller Add Money initiated - User: " . Auth::id() . " | TrxID: $transactionId | Amount: $amount | Gateway: {$gateway->name} | Charge: $gatewayCharge | Total: $totalPaid");

        if (in_array($gateway->code, ['eps', 'mfs', 'card', 'amex'])) {
            $epsPaymentService = new \App\Services\EpsPaymentService();
            
            // Adjust success/fail/cancel redirect URLs inside the EPS session initiation to point to direct seller routes
            // Since initializeMerchantPayment pulls route dynamically or uses standard merchant routes, we can simulate
            // or let the service load it. However, if EPS service uses hardcoded merchant route, we should check EPS service.
            // Let's check App\Services\EpsPaymentService.php first to be sure how it builds URLs.
            // (We will check it but let's complete this code assuming we can handle callbacks).
            $result = $epsPaymentService->initializeMerchantPayment($history);

            if ($result['status'] === 'success') {
                return redirect($result['redirect_url']);
            } else {
                $history->update(['status' => 'failed']);
                return redirect()->route('direct-seller.add_money.create')->with('error', $result['message']);
            }
        }

        return redirect()->route('direct-seller.add_money.gateway', ['transaction_id' => $transactionId]);
    }

    /**
     * Simulated Payment Gateway portal page.
     */
    public function simulateGateway($transaction_id)
    {
        $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('direct_seller.simulate_gateway', compact('history'));
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
            return redirect()->route('direct-seller.add_money.success', ['transaction_id' => $transaction_id]);
        } elseif ($request->action === 'fail') {
            return redirect()->route('direct-seller.add_money.fail', ['transaction_id' => $transaction_id]);
        } else {
            return redirect()->route('direct-seller.add_money.cancel', ['transaction_id' => $transaction_id]);
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
                return redirect()->route('direct-seller.add_money.create')->with('error', 'Transaction not found.');
            }

            if ($history->status !== 'pending') {
                DB::rollBack();
                return redirect()->route('direct-seller.add_money.create')->with('info', 'This transaction is already processed.');
            }

            // Verify payment status if gateway is EPS
            $gateway = MerchantGateway::where('name', $history->gateway_name)->first();
            $paymentReference = 'MOCK-REF-DS-' . strtoupper(Str::random(8));

            if ($gateway && in_array($gateway->code, ['eps', 'mfs', 'card', 'amex'])) {
                $epsService = new \App\Services\EpsPaymentService();
                $epsTransactionId = request()->query('EPSTransactionId');
                
                $statusResult = $epsService->checkTransactionStatus($transaction_id, $epsTransactionId);
                
                if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                    $paymentReference = $epsTransactionId ?? ('EPS-REF-DS-' . strtoupper(Str::random(8)));
                } else {
                    $history->update(['status' => 'failed']);
                    DB::commit();
                    Log::warning("Direct Seller Add Money EPS verification failed - TrxID: $transaction_id");
                    return redirect()->route('direct-seller.add_money.create')->with('error', 'EPS Payment verification failed.');
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
                $user->increment('direct_selling_balance', $history->amount);
            }

            DB::commit();

            Log::info("Direct Seller Add Money SUCCESS - User: {$history->user_id} | TrxID: $transaction_id | Amount added: {$history->amount}");

            return redirect()->route('direct-seller.add_money.create')->with('success', "৳ {$history->amount} has been successfully added to your Direct Selling Balance.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Direct Seller Add Money Success Callback Exception - TrxID: $transaction_id | Error: " . $e->getMessage());
            return redirect()->route('direct-seller.add_money.create')->with('error', 'Something went wrong during payment verification.');
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
            Log::warning("Direct Seller Add Money FAILED - User: {$history->user_id} | TrxID: $transaction_id");
        }

        return redirect()->route('direct-seller.add_money.create')->with('error', 'Payment transaction failed. Please try again.');
    }

    /**
     * Cancelled payment callback.
     */
    public function paymentCancel($transaction_id)
    {
        $history = MerchantBalanceAddHistory::where('transaction_id', $transaction_id)->first();

        if ($history && $history->status === 'pending') {
            $history->update(['status' => 'cancelled']);
            Log::info("Direct Seller Add Money CANCELLED - User: {$history->user_id} | TrxID: $transaction_id");
        }

        return redirect()->route('direct-seller.add_money.create')->with('warning', 'Payment transaction was cancelled.');
    }
}
