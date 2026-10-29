<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\EarningHistory;
use App\Models\HandCashOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PendingPoint;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserPendingFund;
use App\Models\Withdraw;
use App\Models\WithdrawHistory;
use Carbon\Carbon;
use Facades\App\Services\CompanyFundsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->type == 'direct_selling') {
            return redirect()->route('direct-seller.dashboard');
        } elseif (Auth::user()->type == 'store_owner') {
            return redirect()->route('dashboard.shop');
        } elseif (Auth::user()->type == 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif (Auth::user()->type == 'sales_staff') {
            return redirect()->route('dashboard.sales');
        } elseif (Auth::user()->type == 'store_administrator') {
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
            'earnedMoneyToday' => $earnedMoneyToday,
            'earnedPointsToday' => $earnedPointsToday,
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
        $userId = Auth::id();
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();

        // Row 1: Balance & Reward Metrics
        $eBalance = (float) $this->GetShopBalance(Earning::class);
        $pendingBalance = (float) $this->GetShopBalance(UserPendingFund::class);
        $totalPoint = (float) $this->GetShopBalance(Point::class);
        $pendingPoint = (float) $this->GetShopBalance(PendingPoint::class);

        // Row 3: Earned Breakdown (from EarningHistory when order is completed/delivered)
        $todayEarned = (float) EarningHistory::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('amount');
        $weeklyEarned = (float) EarningHistory::where('user_id', $userId)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->sum('amount');
        $monthlyEarned = (float) EarningHistory::where('user_id', $userId)
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');
        $yearlyEarned = (float) EarningHistory::where('user_id', $userId)
            ->where('created_at', '>=', $yearStart)
            ->sum('amount');
        $historyTotalEarned = (float) EarningHistory::where('user_id', $userId)->sum('amount');

        // Row 7a: Withdrawals
        $todayWithdrawal = (float) WithdrawHistory::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('amount');
        $totalWithdraw = (float) $this->GetShopBalance(Withdraw::class);

        $totalEarned = max($historyTotalEarned, $eBalance + $totalWithdraw);

        // Fetch all OrderItems belonging to this seller (with order & product)
        $hasOwnerCol = false;
        $hasSellerStatusCol = false;
        try {
            $hasOwnerCol = Schema::hasColumn('order_items', 'owner_id');
            $hasSellerStatusCol = Schema::hasColumn('order_items', 'seller_status');
        } catch (\Throwable $e) {
        }

        $sellerItems = OrderItem::with(['order', 'product'])
            ->where(function ($q) use ($userId, $hasOwnerCol) {
                if ($hasOwnerCol) {
                    $q->where('order_items.owner_id', $userId)
                      ->orWhereHas('product', function ($pq) use ($userId) {
                          $pq->where('owner_id', $userId);
                      });
                } else {
                    $q->whereHas('product', function ($pq) use ($userId) {
                        $pq->where('owner_id', $userId);
                    });
                }
            })
            ->whereHas('order', function ($oq) {
                $oq->where('status', '!=', 'pending_payment');
            })
            ->get();

        $ordersGrouped = $sellerItems->groupBy('order_id');

        // Row 2 & Row 4: Orders Count (Today, Weekly, Monthly, Yearly, Total)
        $todayOrders = 0;
        $weeklyOrders = 0;
        $monthlyOrders = 0;
        $yearlyOrders = 0;
        $totalOrders = $ordersGrouped->count();

        // Row 2 & Row 5: Delivered Orders Count (Today, Weekly, Monthly, Yearly, Total)
        $todayDelivered = 0;
        $weeklyDelivered = 0;
        $monthlyDelivered = 0;
        $yearlyDelivered = 0;
        $totalDelivered = 0;

        // Row 2 & Row 6: Sale (ERP Sum of Successfully Delivered Orders)
        $todaySale = 0.0;
        $weeklySale = 0.0;
        $monthlySale = 0.0;
        $yearlySale = 0.0;
        $totalSale = 0.0;

        // Row 7b: Cancelled Orders Count (Today, Total)
        $todayCancelOrders = 0;
        $totalCancelOrders = 0;

        foreach ($ordersGrouped as $orderId => $items) {
            $order = $items->first()->order;
            if (!$order) {
                continue;
            }

            $createdAt = $order->created_at ? Carbon::parse($order->created_at) : null;
            $updatedAt = $order->updated_at ? Carbon::parse($order->updated_at) : $createdAt;

            // Orders placed count
            if ($createdAt) {
                if ($createdAt->isSameDay($today)) {
                    $todayOrders++;
                }
                if ($createdAt->between($weekStart, $weekEnd)) {
                    $weeklyOrders++;
                }
                if ($createdAt->greaterThanOrEqualTo($monthStart)) {
                    $monthlyOrders++;
                }
                if ($createdAt->greaterThanOrEqualTo($yearStart)) {
                    $yearlyOrders++;
                }
            }

            // Check if cancelled (either seller cancelled their items or main order is cancelled)
            $firstItem = $items->first();
            $isSellerCancelled = $hasSellerStatusCol && ($firstItem->seller_status ?? '') === 'canceled';
            $isOrderCancelled = ($order->status ?? '') === 'canceled';

            if ($isSellerCancelled || $isOrderCancelled) {
                $totalCancelOrders++;
                $cancelDate = ($isSellerCancelled && !empty($firstItem->seller_updated_at))
                    ? Carbon::parse($firstItem->seller_updated_at)
                    : $updatedAt;
                if ($cancelDate && $cancelDate->isSameDay($today)) {
                    $todayCancelOrders++;
                }
                continue;
            }

            // Check if delivered / completed
            $isDelivered = in_array($order->status ?? '', ['delivered', 'completed', 'done'], true);
            if ($isDelivered) {
                $totalDelivered++;

                // Calculate ERP Sale for this seller's active items in this delivered order
                $orderErpSum = $items->sum(function ($it) {
                    if (($it->seller_status ?? '') === 'canceled') {
                        return 0;
                    }
                    $unitErp = (float) ($it->price ?: ($it->product ? $it->product->erp_en : 0));
                    return $unitErp * max(1, (int) $it->quantity);
                });

                $totalSale += $orderErpSum;

                if ($updatedAt) {
                    if ($updatedAt->isSameDay($today)) {
                        $todayDelivered++;
                        $todaySale += $orderErpSum;
                    }
                    if ($updatedAt->between($weekStart, $weekEnd)) {
                        $weeklyDelivered++;
                        $weeklySale += $orderErpSum;
                    }
                    if ($updatedAt->greaterThanOrEqualTo($monthStart)) {
                        $monthlyDelivered++;
                        $monthlySale += $orderErpSum;
                    }
                    if ($updatedAt->greaterThanOrEqualTo($yearStart)) {
                        $yearlyDelivered++;
                        $yearlySale += $orderErpSum;
                    }
                }
            }
        }

        // Row 8: Product & Inventory Metrics
        $totalProducts = Product::where('owner_id', $userId)->count();
        $stockShort = Product::where('owner_id', $userId)
            ->where('is_deleted', 0)
            ->where('is_sold_out', 0)
            ->where('quantity', '>', 0)
            ->where('quantity', '<', 10)
            ->count();
        $stockOut = Product::where('owner_id', $userId)
            ->where('is_deleted', 0)
            ->where(function ($q) {
                $q->where('is_sold_out', 1)
                  ->orWhereNull('quantity')
                  ->orWhere('quantity', '<=', 0);
            })
            ->count();
        $hideProducts = Product::where('owner_id', $userId)
            ->where('is_deleted', 1)
            ->count();

        $earnedPointsToday = $this->DailyEarnings(PointHistory::class);

        return view('admin.index', [
            // Row 1
            'eBalance' => $eBalance,
            'pendingBalance' => $pendingBalance,
            'totalPoint' => $totalPoint,
            'pendingPoint' => $pendingPoint,

            // Row 2
            'todayOrders' => $todayOrders,
            'todayDelivered' => $todayDelivered,
            'earnedMoneyToday' => $todayEarned,
            'todaySale' => $todaySale,

            // Row 3
            'weeklyEarned' => $weeklyEarned,
            'monthlyEarned' => $monthlyEarned,
            'yearlyEarned' => $yearlyEarned,
            'totalEarned' => $totalEarned,

            // Row 4
            'weeklyOrders' => $weeklyOrders,
            'monthlyOrders' => $monthlyOrders,
            'yearlyOrders' => $yearlyOrders,
            'totalOrders' => $totalOrders,

            // Row 5
            'weeklyDelivered' => $weeklyDelivered,
            'monthlyDelivered' => $monthlyDelivered,
            'yearlyDelivered' => $yearlyDelivered,
            'totalDelivered' => $totalDelivered,

            // Row 6
            'weeklySale' => $weeklySale,
            'monthlySale' => $monthlySale,
            'yearlySale' => $yearlySale,
            'totalSale' => $totalSale,

            // Row 7
            'todayWithdrawal' => $todayWithdrawal,
            'totalWithdraw' => $totalWithdraw,
            'todayCancelOrders' => $todayCancelOrders,
            'totalCancelOrders' => $totalCancelOrders,

            // Row 8
            'totalProducts' => $totalProducts,
            'stockShort' => $stockShort,
            'stockOut' => $stockOut,
            'hideProducts' => $hideProducts,

            'earnedPointsToday' => $earnedPointsToday,
        ]);
    }

    public function HandCashDashboard()
    {
        $userId = Auth::user()->id;
        $currentEBalance = User::find($userId)->virtual_balance ?? 0;

        $todayReceivedCustomer = HandCashOrder::where('owner_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $todayMerchantRewards = HandCashOrder::where('owner_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('trp');

        $todayWithdrawal = WithdrawHistory::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $totalReceivedCustomer = HandCashOrder::where('owner_id', $userId)->count();
        $totalMerchantRewards = HandCashOrder::where('owner_id', $userId)->sum('trp');
        $totalWithdrawal = Withdraw::where('user_id', $userId)->sum('amount');
        $activeCounter = 0;

        return view('admin.index', [
            'currentEBalance' => $currentEBalance,
            'todayReceivedCustomer' => $todayReceivedCustomer,
            'todayMerchantRewards' => $todayMerchantRewards,
            'todayWithdrawal' => $todayWithdrawal,
            'totalReceivedCustomer' => $totalReceivedCustomer,
            'totalMerchantRewards' => $totalMerchantRewards,
            'totalWithdrawal' => $totalWithdrawal,
            'activeCounter' => $activeCounter,
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
