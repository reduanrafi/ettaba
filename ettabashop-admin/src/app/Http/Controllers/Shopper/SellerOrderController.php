<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PendingPoint;
use App\Models\UserPendingFund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SellerOrderController extends Controller
{
    protected function getSellerItemQuery($sellerId)
    {
        return OrderItem::with(['product', 'order.user.profile', 'order.paymentMethod', 'order.address'])
            ->where(function ($q) use ($sellerId) {
                if (Schema::hasColumn('order_items', 'owner_id')) {
                    $q->where('order_items.owner_id', $sellerId)
                      ->orWhereHas('product', function ($pq) use ($sellerId) {
                          $pq->where('owner_id', $sellerId);
                      });
                } else {
                    $q->whereHas('product', function ($pq) use ($sellerId) {
                        $pq->where('owner_id', $sellerId);
                    });
                }
            })
            ->whereHas('order', function ($oq) {
                $oq->where('status', '!=', 'pending_payment');
            });
    }

    public static function getUnseenOrderCountForSeller($sellerId)
    {
        try {
            if (!Schema::hasTable('order_items')) {
                return 0;
            }
            $hasSeenCol = Schema::hasColumn('order_items', 'seller_seen');
            $hasStatusCol = Schema::hasColumn('order_items', 'seller_status');

            $query = OrderItem::where(function ($q) use ($sellerId) {
                if (Schema::hasColumn('order_items', 'owner_id')) {
                    $q->where('owner_id', $sellerId)
                      ->orWhereHas('product', function ($pq) use ($sellerId) {
                          $pq->where('owner_id', $sellerId);
                      });
                } else {
                    $q->whereHas('product', function ($pq) use ($sellerId) {
                        $pq->where('owner_id', $sellerId);
                    });
                }
            })->whereHas('order', function ($oq) {
                $oq->whereNotIn('status', ['pending_payment', 'canceled', 'completed', 'delivered']);
            });

            if ($hasSeenCol) {
                $query->where('seller_seen', 0);
            } elseif ($hasStatusCol) {
                $query->where('seller_status', 'new');
            }

            return $query->distinct('order_id')->count('order_id');
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $statusFilter = $request->get('status', 'all');

        $allItems = $this->getSellerItemQuery($sellerId)
            ->orderByDesc('order_id')
            ->get();

        // Group items by order_id so each order appears as one row for this seller
        $groupedOrders = $allItems->groupBy('order_id')->map(function ($items) {
            $firstItem = $items->first();
            $order = $firstItem->order;
            $sellerStatus = $firstItem->seller_status ?? 'new';
            $isUnseen = $items->contains(function ($it) {
                return isset($it->seller_seen) && (int) $it->seller_seen === 0;
            });

            $sellerSubtotal = $items->sum(function ($it) {
                return (float) $it->price * (int) $it->quantity;
            });

            $sellerRateTotal = $items->sum(function ($it) {
                $rate = $it->product ? (float) $it->product->rate_en : 0;
                return $rate * (int) $it->quantity;
            });

            return (object) [
                'order' => $order,
                'items' => $items,
                'seller_status' => $sellerStatus,
                'seller_cancel_reason' => $firstItem->seller_cancel_reason ?? null,
                'is_unseen' => $isUnseen,
                'seller_subtotal' => $sellerSubtotal,
                'seller_rate_total' => $sellerRateTotal,
            ];
        });

        // Counts per status tab
        $counts = [
            'all' => $groupedOrders->count(),
            'unseen' => $groupedOrders->where('is_unseen', true)->count(),
            'new' => $groupedOrders->where('seller_status', 'new')->count(),
            'accepted' => $groupedOrders->where('seller_status', 'accepted')->count(),
            'processing' => $groupedOrders->where('seller_status', 'processing')->count(),
            'ready_for_pickup' => $groupedOrders->where('seller_status', 'ready_for_pickup')->count(),
            'canceled' => $groupedOrders->where('seller_status', 'canceled')->count(),
        ];

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'delivered') {
                $groupedOrders = $groupedOrders->filter(function ($entry) {
                    return in_array($entry->order->status ?? '', ['delivered', 'completed', 'done'], true);
                });
            } else {
                $groupedOrders = $groupedOrders->where('seller_status', $statusFilter);
            }
        }

        return view('admin.seller_orders.index', [
            'sellerOrders' => $groupedOrders,
            'statusFilter' => $statusFilter,
            'counts' => $counts,
        ]);
    }

    public function show($orderId)
    {
        $sellerId = Auth::id();
        $order = Order::with(['user.profile', 'paymentMethod', 'address'])->findOrFail($orderId);

        $sellerItems = $this->getSellerItemQuery($sellerId)
            ->where('order_id', $orderId)
            ->get();

        if ($sellerItems->isEmpty()) {
            abort(403, 'এই অর্ডারে আপনার কোনো পণ্য নেই।');
        }

        // Mark this seller's items in this order as seen
        try {
            if (Schema::hasColumn('order_items', 'seller_seen')) {
                OrderItem::whereIn('id', $sellerItems->pluck('id')->toArray())
                    ->where('seller_seen', 0)
                    ->update(['seller_seen' => 1]);
            }
        } catch (\Throwable $e) {
        }

        $firstItem = $sellerItems->first();
        $sellerStatus = $firstItem->seller_status ?? 'new';
        $cancelReason = $firstItem->seller_cancel_reason ?? null;

        $sellerSubtotal = $sellerItems->sum(function ($it) {
            return (float) $it->price * (int) $it->quantity;
        });

        $sellerRateTotal = $sellerItems->sum(function ($it) {
            $rate = $it->product ? (float) $it->product->rate_en : 0;
            return $rate * (int) $it->quantity;
        });

        return view('admin.seller_orders.show', [
            'order' => $order,
            'sellerItems' => $sellerItems,
            'sellerStatus' => $sellerStatus,
            'cancelReason' => $cancelReason,
            'sellerSubtotal' => $sellerSubtotal,
            'sellerRateTotal' => $sellerRateTotal,
        ]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $sellerId = Auth::id();
        $newStatus = $request->input('seller_status');
        $allowedStatuses = ['accepted', 'processing', 'ready_for_pickup', 'canceled'];

        if (!in_array($newStatus, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'অবৈধ স্ট্যাটাস নির্বাচন করা হয়েছে।');
        }

        $order = Order::with('orderItems.product')->findOrFail($orderId);

        if (in_array($order->status, ['on_delivery', 'delivered', 'completed', 'done'], true)) {
            return redirect()->back()->with('error', 'পিকআপের পরবর্তী স্ট্যাটাস শুধুমাত্র অ্যাডমিন পরিবর্তন করতে পারবেন।');
        }

        $sellerItems = $this->getSellerItemQuery($sellerId)
            ->where('order_id', $orderId)
            ->get();

        if ($sellerItems->isEmpty()) {
            return redirect()->back()->with('error', 'এই অর্ডারে আপনার কোনো পণ্য পাওয়া যায়নি।');
        }

        $currentSellerStatus = $sellerItems->first()->seller_status ?? 'new';
        if ($currentSellerStatus === 'canceled') {
            return redirect()->back()->with('error', 'বাতিলকৃত অর্ডারের স্ট্যাটাস পরিবর্তন করা যাবে না।');
        }

        if ($newStatus === 'canceled') {
            $reason = trim((string) $request->input('cancel_reason', ''));
            if ($reason === '') {
                return redirect()->back()->with('error', 'অর্ডার বাতিল করার কারণ উল্লেখ করা আবশ্যক।');
            }

            DB::beginTransaction();
            try {
                $itemIds = $sellerItems->pluck('id')->toArray();

                // Update seller items to canceled
                OrderItem::whereIn('id', $itemIds)->update([
                    'seller_status' => 'canceled',
                    'seller_cancel_reason' => $reason,
                    'seller_seen' => 1,
                    'seller_updated_at' => Carbon::now(),
                ]);

                // Calculate deduction from main order for this seller's cancelled items
                $deductErp = 0;
                $deductTrp = 0;
                $deductTcb = 0;
                $deductRate = 0;

                foreach ($sellerItems as $item) {
                    $qty = max(1, (int) $item->quantity);
                    $unitErp = (float) ($item->price ?: ($item->product ? $item->product->erp_en : 0));
                    $unitTrp = (float) ($item->trp ?: ($item->product ? $item->product->trp_en : 0));
                    $unitTcb = (float) ($item->product ? $item->product->tcb_en : 0);
                    $unitRate = (float) ($item->product ? $item->product->rate_en : 0);

                    $deductErp += ($unitErp * $qty);
                    $deductTrp += ($unitTrp * $qty);
                    $deductTcb += ($unitTcb * $qty);
                    $deductRate += ($unitRate * $qty);
                }

                $order->erp_total = max(0, (float) $order->erp_total - $deductErp);
                $order->amount = max(0, (float) $order->amount - $deductErp);
                $order->net_total = max(0, (float) $order->net_total - $deductErp);
                $order->trp = max(0, (float) $order->trp - $deductTrp);
                $order->tcb = max(0, (float) $order->tcb - $deductTcb);
                $order->rate_total = max(0, (float) $order->rate_total - $deductRate);

                // Remove this seller's pending funds and pending points for this order
                UserPendingFund::where('order_id', $order->id)
                    ->where('user_id', $sellerId)
                    ->delete();

                UserPendingFund::where('order_id', $order->id)
                    ->where('point_type', 'merchant_referral')
                    ->where('child_id', $sellerId)
                    ->delete();

                PendingPoint::where('order_id', $order->id)
                    ->where('user_id', $sellerId)
                    ->delete();

                // Check if ALL items in the main order are now canceled
                $remainingActiveCount = OrderItem::where('order_id', $order->id)
                    ->where(function ($q) {
                        $q->whereNull('seller_status')
                          ->orWhere('seller_status', '!=', 'canceled');
                    })
                    ->count();

                if ($remainingActiveCount === 0) {
                    $order->status = 'canceled';
                    UserPendingFund::where('order_id', $order->id)->delete();
                    PendingPoint::where('order_id', $order->id)->delete();
                }

                $order->save();
                DB::commit();

                return redirect()->back()->with('success', 'আপনার অংশের অর্ডারটি বাতিল করা হয়েছে এবং মূল অর্ডার থেকে মূল্য সমন্বয় করা হয়েছে।');
            } catch (\Throwable $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'অর্ডার বাতিল করতে সমস্যা হয়েছে: ' . $e->getMessage());
            }
        }

        // Update seller items to accepted / processing / ready_for_pickup
        OrderItem::whereIn('id', $sellerItems->pluck('id')->toArray())->update([
            'seller_status' => $newStatus,
            'seller_seen' => 1,
            'seller_updated_at' => Carbon::now(),
        ]);

        // If main order is still 'pending' and seller accepted, we can optionally mark main order 'accepted' if all active sellers accepted
        if ($order->status === 'pending' && in_array($newStatus, ['accepted', 'processing', 'ready_for_pickup'], true)) {
            $unacceptedCount = OrderItem::where('order_id', $order->id)
                ->where(function ($q) {
                    $q->whereNull('seller_status')
                      ->orWhere('seller_status', 'new');
                })
                ->count();

            if ($unacceptedCount === 0) {
                $order->update(['status' => 'accepted']);
            }
        }

        $statusLabels = [
            'accepted' => 'Accepted (অর্ডার গ্রহণ করা হয়েছে)',
            'processing' => 'Processing (পণ্য প্রস্তুত ও প্যাকেজিং হচ্ছে)',
            'ready_for_pickup' => 'Ready for Pickup (পিকআপের জন্য প্রস্তুত)',
        ];

        return redirect()->back()->with('success', 'অর্ডার স্ট্যাটাস আপডেট হয়েছে: ' . ($statusLabels[$newStatus] ?? $newStatus));
    }
}

