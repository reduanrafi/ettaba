<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HandCashOrder;
use Illuminate\Http\Request;

class MerchantHistoryController extends Controller
{
    public function Index()
    {
        $orders = HandCashOrder::with('orderItems.product','user')->orderByDesc('id')->get();
        return view('admin.merchants.history',['orders'=>$orders]);
    }
}
