<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Earning;
use App\Models\EarningHistory;
use App\Models\HandCashOrder;
use App\Models\Order;
use App\Models\PendingPoint;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserPendingFund;
use App\Models\Withdraw;
use Carbon\Carbon;
use Facades\App\Services\CompanyFundsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class DashboardController extends Controller
{


    public function index()
    {

        if (Auth::user()->type == 'direct_selling') {
            return redirect()->route('direct-seller.dashboard');

        } else if (Auth::user()->type == 'store_owner') {

            return redirect()->route('dashboard.shop');
        } else if (Auth::user()->type == 'admin') {

            return redirect()->route('dashboard.admin');
        } else if (Auth::user()->type == 'sales_staff') {
            //dd("test");
            return redirect()->route('dashboard.sales');
        } else if (Auth::user()->type == 'store_administrator') {
            //dd("test");
            return redirect()->route('dashboard.handcash');
        }
    }

    public function AdminDashboard()
    {
        $orders = Order::count();

        $products = Product::count();

        $shops = Shop::count();

        $customers = User::where('type', 'customer')->where('is_active', 1)->count();

        $funds = CompanyFundsService::GetCompanyFunds();

        $earnedMoneyToday = $this->DailyAdminEarnings(EarningHistory::class);

        $earnedPointsToday = $this->DailyAdminEarnings(PointHistory::class);

        return view('admin.index', [
            'orders' => $orders,
            'products' => $products,
            'shops' => $shops,
            'customers' => $customers,
            'funds' => $funds,
            "earnedMoneyToday" => $earnedMoneyToday,
            "earnedPointsToday" => $earnedPointsToday,
        ]);
    }

    public function SalesDashboard()
    {
        $orders = Order::count();

        $products = Product::count();

        $shops = Shop::count();

        $customers = User::where('type', 'customer')->where('is_active', 1)->count();



        return view('admin.index', [
            'orders' => $orders,
            'products' => $products,
            'shops' => $shops,
            'customers' => $customers,

        ]);
    }

    public function ShopDashboard()
    {


        $eBalance = $this->GetShopBalance(Earning::class);

        $pendingBalance = $this->GetShopBalance(UserPendingFund::class);

        $pendingPoint = $this->GetShopBalance(PendingPoint::class);

        $totalPoint = $this->GetShopBalance(Point::class);

        $totalWithdraw = $this->GetShopBalance(Withdraw::class);

        $earnedMoneyToday = $this->DailyEarnings(EarningHistory::class);

        $earnedPointsToday = $this->DailyEarnings(PointHistory::class);


        return view('admin.index', [
            "eBalance" => $eBalance,
            "pendingBalance" => $pendingBalance,
            "totalPoint" => $totalPoint,
            "pendingPoint" => $pendingPoint,
            "totalWithdraw" => $totalWithdraw,
            "earnedMoneyToday" => $earnedMoneyToday,
            "earnedPointsToday" => $earnedPointsToday,
        ]);
    }
    public function HandCashDashboard()
    {
        $userId = Auth::user()->id;

        // 1. Current e-Balance
        $currentEBalance = User::find($userId)->virtual_balance ?? 0;

        // 2. Today Received Customer (Every cashback/purchasing today counts as a customer)
        $todayReceivedCustomer = HandCashOrder::where('owner_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 3. Today Merchant Rewards (Sum of trp/points sold today)
        $todayMerchantRewards = HandCashOrder::where('owner_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('trp');

        // 4. Today Withdrawal (Inactive for now)
        $todayWithdrawal = 0;

        // 5. Total Received Customer (Every cashback/purchasing overall counts as a customer)
        $totalReceivedCustomer = HandCashOrder::where('owner_id', $userId)
            ->count();

        // 6. Total Merchant Rewards (Sum of trp/points sold overall)
        $totalMerchantRewards = HandCashOrder::where('owner_id', $userId)
            ->sum('trp');

        // 7. Total Withdrawal (Inactive for now)
        $totalWithdrawal = 0;

        // 8. Active Counter (Inactive for now)
        $activeCounter = 0;

        return view('admin.index', [
            "currentEBalance" => $currentEBalance,
            "todayReceivedCustomer" => $todayReceivedCustomer,
            "todayMerchantRewards" => $todayMerchantRewards,
            "todayWithdrawal" => $todayWithdrawal,
            "totalReceivedCustomer" => $totalReceivedCustomer,
            "totalMerchantRewards" => $totalMerchantRewards,
            "totalWithdrawal" => $totalWithdrawal,
            "activeCounter" => $activeCounter,
        ]);
    }
    private function GetShopBalance($className)
    {
        $userId = Auth::user()->id;

        return $className::where('user_id', $userId)->sum('amount');
    }
    private function DailyEarnings($className)
    {
        $userId = Auth::user()->id;

        return $className::where('user_id', $userId)->whereDate('created_at', '=', Carbon::today())->sum('amount');
    }
    private function DailyAdminEarnings($className)
    {
        $amount = $className::whereDate('created_at', '=', Carbon::today())->sum('amount');
        return number_format((float) $amount, 2, '.', '');
    }
}
