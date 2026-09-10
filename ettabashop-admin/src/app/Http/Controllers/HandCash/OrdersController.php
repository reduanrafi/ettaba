<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;

use App\Http\Resources\ProfileResource;
use App\Models\HandCashOrder;
use App\Models\HandCashProduct;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = HandCashOrder::with('orderItems.product','user')->where('owner_id',Auth::user()->id)->get();
       // dd($orders);
        return view('handcash.orders.index',['orders'=>$orders]);
    }

    // API method


}
