<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EpsPaymentService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $storeId;
    protected $merchantId;
    protected $hashKey;

    public function __construct()
    {
        $this->baseUrl = env('EPS_URL', 'https://pgapi.eps.com.bd');
        $this->username = env('EPS_USERNAME', 'dt_merchant@eps.com.bd');
        $this->password = env('EPS_PASSWORD', 'your_eps_password');
        $this->storeId = env('EPS_STORE_ID', '35b518f6-XXXX-XXXX');
        $this->merchantId = env('EPS_MERCHANT_ID', '094980ee-XXX-XXX-XXX');
        $this->hashKey = env('EPS_HASH_KEY', 'SFNLQHJlY2lwZXdhbGEjYTc3Zi1mOTQ5NWZhY2M2ZTZuZXQ=');
    }

    /**
     * Generate x-hash for EPS headers.
     */
    public function generateHash($data)
    {
        return base64_encode(hash_hmac('sha512', $data, $this->hashKey, true));
    }

    /**
     * Get bearer token from EPS Auth.
     */
    public function getToken()
    {
        $hash = $this->generateHash($this->username);
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-hash' => $hash
            ])->post($this->baseUrl . '/v1/Auth/GetToken', [
                'userName' => $this->username,
                'password' => $this->password
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['token'] ?? null;
            }
            
            Log::error("EPS GetToken Failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("EPS GetToken Exception: " . $e->getMessage());
        }
        
        return null;
    }

    /**
     * Initialize payment session.
     */
    public function initializePayment($order, $isAnonymous = false)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['status' => 'error', 'message' => 'Unable to authenticate with EPS Gateway'];
        }

        // Generate unique merchantTransactionId of at least 10 digits
        // Format: YmdHis + order_id
        $merchantTransactionId = date('YmdHis') . $order->id;

        // Save transaction ID on the order
        $order->update(['transaction_id' => $merchantTransactionId]);

        $hash = $this->generateHash($merchantTransactionId);

        $customerName = $isAnonymous ? ($order->username ?? 'Guest Customer') : (($order->user->profile->first_name ?? 'Customer') . ' ' . ($order->user->profile->last_name ?? ''));
        $customerEmail = $isAnonymous ? 'guest@ettabashop.com' : ($order->user->email ?? 'customer@ettabashop.com');
        $customerPhone = $isAnonymous ? ($order->phone ?? '01700000000') : ($order->user->phone ?? '01700000000');
        $customerAddress = $isAnonymous ? ($order->address ?? 'N/A') : ($order->user->profile->address ?? 'N/A');

        $body = [
            'storeId' => $this->storeId,
            'merchantId' => $this->merchantId,
            'CustomerOrderId' => $order->unique_order_id ?? ('ESL' . $order->id),
            'merchantTransactionId' => $merchantTransactionId,
            'transactionTypeId' => 1, // 1 = Web
            'financialEntityId' => 0,
            'transitionStatusId' => 0,
            'totalAmount' => (float)$order->net_total,
            'ipAddress' => request()->ip() ?? '127.0.0.1',
            'version' => '1',
            'successUrl' => route('payment.success'),
            'failUrl' => route('payment.fail'),
            'cancelUrl' => route('payment.cancel'),
            'customerName' => trim($customerName),
            'customerEmail' => $customerEmail,
            'customerAddress' => $customerAddress,
            'customerAddress2' => '',
            'customerCity' => 'Khulna',
            'customerState' => 'Khulna',
            'customerPostcode' => '1200',
            'customerCountry' => 'BD',
            'customerPhone' => $customerPhone,
            'productName' => 'Ettaba Shop Products',
            'noOfItem' => '1',
            'shippingMethod' => 'NO'
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'x-hash' => $hash
            ])->post($this->baseUrl . '/v1/EPSEngine/InitializeEPS', $body);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['RedirectURL']) && $data['RedirectURL']) {
                    return [
                        'status' => 'success',
                        'redirect_url' => $data['RedirectURL'],
                        'transaction_id' => $merchantTransactionId
                    ];
                }
                return ['status' => 'error', 'message' => $data['ErrorMessage'] ?? 'Unknown EPS error'];
            }

            Log::error("EPS InitializeEPS Failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("EPS InitializeEPS Exception: " . $e->getMessage());
        }

        return ['status' => 'error', 'message' => 'EPS Gateway initialization failed'];
    }

    /**
     * Initialize payment session for Merchant Add Money.
     */
    public function initializeMerchantPayment($history)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['status' => 'error', 'message' => 'Unable to authenticate with EPS Gateway'];
        }

        $merchantTransactionId = $history->transaction_id;
        $hash = $this->generateHash($merchantTransactionId);

        $user = $history->user;
        $customerName = $user ? (($user->profile->first_name ?? 'Merchant') . ' ' . ($user->profile->last_name ?? '')) : 'Merchant';
        $customerEmail = $user->email ?? 'merchant@ettabashop.com';
        $customerPhone = $user->phone ?? '01700000000';
        $customerAddress = $user->profile->address ?? 'N/A';

        $successRoute = 'handcash.add_money.success';
        $failRoute = 'handcash.add_money.fail';
        $cancelRoute = 'handcash.add_money.cancel';

        if (str_starts_with($history->transaction_id, 'TXN-DS-')) {
            $successRoute = 'direct-seller.add_money.success';
            $failRoute = 'direct-seller.add_money.fail';
            $cancelRoute = 'direct-seller.add_money.cancel';
        }

        $body = [
            'storeId' => $this->storeId,
            'merchantId' => $this->merchantId,
            'CustomerOrderId' => $history->transaction_id,
            'merchantTransactionId' => $merchantTransactionId,
            'transactionTypeId' => 1, // 1 = Web
            'financialEntityId' => 0,
            'transitionStatusId' => 0,
            'totalAmount' => (float)$history->total_paid,
            'ipAddress' => request()->ip() ?? '127.0.0.1',
            'version' => '1',
            'successUrl' => route($successRoute, ['transaction_id' => $history->transaction_id]),
            'failUrl' => route($failRoute, ['transaction_id' => $history->transaction_id]),
            'cancelUrl' => route($cancelRoute, ['transaction_id' => $history->transaction_id]),
            'customerName' => trim($customerName),
            'customerEmail' => $customerEmail,
            'customerAddress' => $customerAddress,
            'customerAddress2' => '',
            'customerCity' => 'Khulna',
            'customerState' => 'Khulna',
            'customerPostcode' => '1200',
            'customerCountry' => 'BD',
            'customerPhone' => $customerPhone,
            'productName' => 'Merchant Balance Add',
            'noOfItem' => '1',
            'shippingMethod' => 'NO'
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'x-hash' => $hash
            ])->post($this->baseUrl . '/v1/EPSEngine/InitializeEPS', $body);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['RedirectURL']) && $data['RedirectURL']) {
                    return [
                        'status' => 'success',
                        'redirect_url' => $data['RedirectURL'],
                        'transaction_id' => $merchantTransactionId
                    ];
                }
                return ['status' => 'error', 'message' => $data['ErrorMessage'] ?? 'Unknown EPS error'];
            }

            Log::error("EPS Merchant InitializeEPS Failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("EPS Merchant InitializeEPS Exception: " . $e->getMessage());
        }

        return ['status' => 'error', 'message' => 'EPS Gateway initialization failed'];
    }

    /**
     * Check transaction status.
     */
    public function checkTransactionStatus($merchantTransactionId, $epsTransactionId = '')
    {
        $token = $this->getToken();
        if (!$token) {
            return ['status' => 'error', 'message' => 'Unable to authenticate with EPS Gateway'];
        }

        $hash = $this->generateHash($merchantTransactionId);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'x-hash' => $hash
            ])->get($this->baseUrl . '/v1/EPSEngine/CheckMerchantTransactionStatus', [
                'merchantTransactionId' => $merchantTransactionId,
                'EPSTransactionId' => $epsTransactionId
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json()
                ];
            }

            Log::error("EPS CheckStatus Failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("EPS CheckStatus Exception: " . $e->getMessage());
        }

        return ['status' => 'error', 'message' => 'Failed to check transaction status'];
    }
}
