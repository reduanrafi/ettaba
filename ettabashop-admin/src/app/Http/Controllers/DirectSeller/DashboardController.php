<?php

namespace App\Http\Controllers\DirectSeller;

use App\Http\Controllers\Controller;
use App\Models\DirectSalesOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'direct_seller']);
    }

    public function index()
    {
        $userId = Auth::id();

        // 1. Current Direct Selling Balance
        $user = User::find($userId);
        $directSellingBalance = $user ? $user->direct_selling_balance : 0.00;

        // 2. Today's Customers (unique customer phone count in direct sales orders today)
        $todayCustomers = DirectSalesOrder::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->distinct('customer_phone')
            ->count('customer_phone');

        // 3. Today's Net Profit (sum of qty * (seller_rate - company_rate) today)
        $todayNetProfit = DirectSalesOrder::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->sum(function($order) {
                return $order->qty * ($order->seller_rate - $order->company_rate);
            });

        // 4. Today's Sales (sum of total_seller_rate today)
        $todaySales = DirectSalesOrder::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_seller_rate');

        // 5. Total Customers (unique customer phone count in direct sales orders overall)
        $totalCustomers = DirectSalesOrder::where('user_id', $userId)
            ->distinct('customer_phone')
            ->count('customer_phone');

        // 6. Total Net Profit (sum of qty * (seller_rate - company_rate) overall)
        $totalNetProfit = DirectSalesOrder::where('user_id', $userId)
            ->get()
            ->sum(function($order) {
                return $order->qty * ($order->seller_rate - $order->company_rate);
            });

        // 7. Total Sales (sum of total_seller_rate overall)
        $totalSales = DirectSalesOrder::where('user_id', $userId)
            ->sum('total_seller_rate');

        return view('direct_seller.dashboard', [
            'directSellingBalance' => $directSellingBalance,
            'todayCustomers' => $todayCustomers,
            'todayNetProfit' => $todayNetProfit,
            'todaySales' => $todaySales,
            'totalCustomers' => $totalCustomers,
            'totalNetProfit' => $totalNetProfit,
            'totalSales' => $totalSales,
        ]);
    }
}
