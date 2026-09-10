<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uzzal\SslCommerz\Client;
use Uzzal\SslCommerz\Customer;

class PaymentController extends Controller
{
    public function sessionRequest(Request $request)
    {
        $customer = new Customer('Reduan', 'mahabub@example.com', '0171xxxxx22');
        $resp = Client::initSession($customer, 29); //29 is the amount
        echo $resp->getGatewayUrl();
    }
}
