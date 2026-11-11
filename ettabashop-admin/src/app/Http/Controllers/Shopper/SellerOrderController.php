<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PendingPoint;
use App\Models\SellerDueDeductionHistory;
use App\Models\Shop;
use App\Models\UserPendingFund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $shop = Shop::where('user_id', $sellerId)->first();
        $isSelfDelivery = $shop && $shop->delivery_type === 'self';
        $sellerEbalance = $shop ? (float) ($shop->seller_ebalance ?? 0) : 0;

        $allItems = $this->getSellerItemQuery($sellerId)
            ->orderByDesc('order_id')
            ->get();

        // Group items by order_id so each order appears as one row for this seller
        $groupedOrders = $allItems->groupBy('order_id')->map(function ($items) use ($isSelfDelivery) {
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

            // Pending Due Calculation (Pages 9-11): Difference between ERP Subtotal and Seller Rate
            $pendingDue = max(0, $sellerSubtotal - $sellerRateTotal);
            $isDelivered = in_array($order->status ?? '', ['delivered', 'completed', 'done'], true)
                           || in_array($sellerStatus, ['delivered', 'done'], true);

            return (object) [
                'order' => $order,
                'items' => $items,
                'seller_status' => $sellerStatus,
                'seller_cancel_reason' => $firstItem->seller_cancel_reason ?? null,
                'is_unseen' => $isUnseen,
                'seller_subtotal' => $sellerSubtotal,
                'seller_rate_total' => $sellerRateTotal,
                'pending_due' => $pendingDue,
                'is_delivered' => $isDelivered,
            ];
        });

        // Calculate total pending due for undelivered, uncanceled orders (Pages 10 spec: negative pending balance)
        $totalPendingDue = $groupedOrders->filter(function ($entry) {
            $status = $entry->seller_status;
            $orderStatus = $entry->order->status ?? '';
            return !in_array($status, ['canceled', 'delivered', 'done'], true)
                && !in_array($orderStatus, ['canceled', 'delivered', 'completed', 'done'], true);
        })->sum('pending_due');

        // Counts per status tab
        $counts = [
            'all' => $groupedOrders->count(),
            'unseen' => $groupedOrders->where('is_unseen', true)->count(),
            'new' => $groupedOrders->where('seller_status', 'new')->count(),
            'accepted' => $groupedOrders->where('seller_status', 'accepted')->count(),
            'processing' => $groupedOrders->where('seller_status', 'processing')->count(),
            'on_delivery' => $groupedOrders->where('seller_status', 'on_delivery')->count(),
            'ready_for_pickup' => $groupedOrders->where('seller_status', 'ready_for_pickup')->count(),
            'delivered' => $groupedOrders->filter(function ($entry) {
                return $entry->is_delivered;
            })->count(),
            'canceled' => $groupedOrders->where('seller_status', 'canceled')->count(),
        ];

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'delivered') {
                $groupedOrders = $groupedOrders->filter(function ($entry) {
                    return $entry->is_delivered;
                });
            } else {
                $groupedOrders = $groupedOrders->where('seller_status', $statusFilter);
            }
        }

        return view('admin.seller_orders.index', [
            'sellerOrders' => $groupedOrders,
            'statusFilter' => $statusFilter,
            'counts' => $counts,
            'shop' => $shop,
            'isSelfDelivery' => $isSelfDelivery,
            'sellerEbalance' => $sellerEbalance,
            'totalPendingDue' => $totalPendingDue,
        ]);
    }

    public function show($orderId)
    {
        $sellerId = Auth::id();
        $order = Order::with(['user.profile', 'paymentMethod', 'address'])->findOrFail($orderId);

        $shop = Shop::where('user_id', $sellerId)->first();
        $isSelfDelivery = $shop && $shop->delivery_type === 'self';
        $sellerEbalance = $shop ? (float) ($shop->seller_ebalance ?? 0) : 0;

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

        $pendingDue = max(0, $sellerSubtotal - $sellerRateTotal);

        // Fetch other items in this order (belonging to other sellers) if self delivery
        $otherItems = OrderItem::with(['product'])
            ->where('order_id', $orderId)
            ->whereNotIn('id', $sellerItems->pluck('id')->toArray())
            ->get();

        return view('admin.seller_orders.show', [
            'order' => $order,
            'sellerItems' => $sellerItems,
            'otherItems' => $otherItems,
            'sellerStatus' => $sellerStatus,
            'cancelReason' => $cancelReason,
            'sellerSubtotal' => $sellerSubtotal,
            'sellerRateTotal' => $sellerRateTotal,
            'pendingDue' => $pendingDue,
            'shop' => $shop,
            'isSelfDelivery' => $isSelfDelivery,
            'sellerEbalance' => $sellerEbalance,
        ]);
    }

    public function updateDeliveryCharge(Request $request, $orderId)
    {
        $sellerId = Auth::id();
        $shop = Shop::where('user_id', $sellerId)->first();
        if (!$shop || $shop->delivery_type !== 'self') {
            return redirect()->back()->with('error', 'শুধুমাত্র সেলফ-ডেলিভারি সেলাররা ডেলিভারি চার্জ পরিবর্তন করতে পারবেন।');
        }

        $request->validate([
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($orderId);
        $oldCharge = (float) $order->delivery_charge;
        $newCharge = (float) $request->delivery_charge;
        $diff = $newCharge - $oldCharge;

        $order->delivery_charge = $newCharge;
        $order->net_total = max(0, (float) $order->net_total + $diff);
        $order->amount = max(0, (float) $order->amount + $diff);
        $order->save();

        return redirect()->back()->with('success', 'ডেলিভারি চার্জ সফলভাবে আপডেট করা হয়েছে।');
    }

    public function removeItem(Request $request, $itemId)
    {
        $sellerId = Auth::id();
        $shop = Shop::where('user_id', $sellerId)->first();
        if (!$shop || $shop->delivery_type !== 'self') {
            return redirect()->back()->with('error', 'শুধুমাত্র সেলফ-ডেলিভারি সেলাররা আইটেম সরাতে পারবেন।');
        }

        $item = OrderItem::with('product')->findOrFail($itemId);
        $orderId = $item->order_id;
        $order = Order::findOrFail($orderId);

        DB::beginTransaction();
        try {
            $qty = max(1, (int) $item->quantity);
            $unitErp = (float) ($item->price ?: ($item->product ? $item->product->erp_en : 0));
            $unitTrp = (float) ($item->trp ?: ($item->product ? $item->product->trp_en : 0));
            $unitTcb = (float) ($item->product ? $item->product->tcb_en : 0);
            $unitRate = (float) ($item->product ? $item->product->rate_en : 0);

            $deductErp = $unitErp * $qty;
            $deductTrp = $unitTrp * $qty;
            $deductTcb = $unitTcb * $qty;
            $deductRate = $unitRate * $qty;

            // Restore product stock
            if ($item->product_id) {
                $prod = \App\Models\Product::find($item->product_id);
                if ($prod) {
                    $prod->increment('quantity', $qty);
                    if ($prod->is_sold_out && $prod->quantity > 0) {
                        $prod->is_sold_out = 0;
                        $prod->save();
                    }
                }
            }

            $order->erp_total = max(0, (float) $order->erp_total - $deductErp);
            $order->amount = max(0, (float) $order->amount - $deductErp);
            $order->net_total = max(0, (float) $order->net_total - $deductErp);
            $order->trp = max(0, (float) $order->trp - $deductTrp);
            $order->tcb = max(0, (float) $order->tcb - $deductTcb);
            $order->rate_total = max(0, (float) $order->rate_total - $deductRate);
            $order->save();

            $item->delete();

            DB::commit();
            return redirect()->back()->with('success', 'পণ্যটি অর্ডার থেকে সফলভাবে মুছে দেওয়া হয়েছে এবং স্টক পুনরুদ্ধার করা হয়েছে।');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'পণ্য অপসারণ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $orderId)
    {
        $sellerId = Auth::id();
        $shop = Shop::where('user_id', $sellerId)->first();
        $isSelfDelivery = $shop && $shop->delivery_type === 'self';

        $newStatus = $request->input('seller_status');
        $allowedStatuses = $isSelfDelivery
            ? ['accepted', 'processing', 'on_delivery', 'delivered', 'done', 'canceled']
            : ['accepted', 'processing', 'ready_for_pickup', 'canceled'];

        if (!in_array($newStatus, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'অবৈধ স্ট্যাটাস নির্বাচন করা হয়েছে।');
        }

        $order = Order::with('orderItems.product')->findOrFail($orderId);

        // If Ettaba delivery, cannot update once local office locks it
        if (!$isSelfDelivery && in_array($order->status, ['on_delivery', 'delivered', 'completed', 'done'], true)) {
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

        // ==========================================
        // 1. CANCELLATION FLOW
        // ==========================================
        if ($newStatus === 'canceled') {
            $reason = trim((string) $request->input('cancel_reason', ''));
            if ($reason === '') {
                return redirect()->back()->with('error', 'অর্ডার বাতিল করার কারণ উল্লেখ করা আবশ্যক।');
            }

            DB::beginTransaction();
            try {
                $itemIds = $sellerItems->pluck('id')->toArray();

                // Restore stock for cancelled items
                foreach ($sellerItems as $item) {
                    if (!empty($item->product_id)) {
                        $prod = \App\Models\Product::find($item->product_id);
                        if ($prod) {
                            $prod->increment('quantity', max(1, (int)$item->quantity));
                            if ($prod->is_sold_out && $prod->quantity > 0) {
                                $prod->is_sold_out = 0;
                                $prod->save();
                            }
                        }
                    }
                }

                // Update seller items to canceled
                OrderItem::whereIn('id', $itemIds)->update([
                    'seller_status' => 'canceled',
                    'seller_cancel_reason' => $reason,
                    'seller_seen' => 1,
                    'seller_updated_at' => Carbon::now(),
                ]);

                // Calculate deduction from main order
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

                return redirect()->back()->with('success', 'আপনার অংশের অর্ডারটি বাতিল করা হয়েছে এবং পণ্য স্টক পুনরুদ্ধার করা হয়েছে।');
            } catch (\Throwable $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'অর্ডার বাতিল করতে সমস্যা হয়েছে: ' . $e->getMessage());
            }
        }

        // =========================================================================
        // 2. SELF DELIVERY: DELIVERED / DONE COMPLETION WITH eBALANCE VERIFICATION
        // =========================================================================
        if ($isSelfDelivery && in_array($newStatus, ['delivered', 'done'], true)) {
            // Calculate pending due for this seller's items
            $sellerSubtotal = $sellerItems->sum(function ($it) {
                return (float) $it->price * (int) $it->quantity;
            });
            $sellerRateTotal = $sellerItems->sum(function ($it) {
                $rate = $it->product ? (float) $it->product->rate_en : 0;
                return $rate * (int) $it->quantity;
            });
            $dueAmount = max(0, $sellerSubtotal - $sellerRateTotal);

            // Re-fetch shop with lock
            $shop = Shop::where('user_id', $sellerId)->lockForUpdate()->first();
            $currentEbalance = $shop ? (float) ($shop->seller_ebalance ?? 0) : 0;

            // Check if due has already been deducted for this order's items
            $alreadyDeducted = $sellerItems->first()->seller_due_deducted ?? false;

            if (!$alreadyDeducted && $dueAmount > 0) {
                if ($currentEbalance < $dueAmount) {
                    $msg = "এই অর্ডারটি সম্পন্ন করতে আপনার Seller eBalance-এ ৳" . number_format($dueAmount, 2) . " লোড করুন। এরপর অর্ডারটি সম্পন্ন করার মাধ্যমে কাস্টমারকে ক্যাশব্যাক প্রদান করুন এবং নিজেও পয়েন্ট অর্জন করুন আর আকর্ষণীয় পুরস্কারের পথে আরও এক ধাপ এগিয়ে যান। ধন্যবাদ।";
                    return redirect()->back()->with('error', $msg)->with('need_add_money', true)->with('due_amount', $dueAmount);
                }
            }

            // Wrap in atomic DB Transaction
            DB::beginTransaction();
            try {
                if (!$alreadyDeducted && $dueAmount > 0) {
                    // Deduct from seller_ebalance
                    $balanceBefore = $currentEbalance;
                    $balanceAfter = $balanceBefore - $dueAmount;
                    $shop->seller_ebalance = $balanceAfter;
                    $shop->save();

                    // Mark items as due deducted
                    OrderItem::whereIn('id', $sellerItems->pluck('id')->toArray())->update([
                        'seller_due_deducted' => 1,
                        'seller_due_amount' => $dueAmount,
                    ]);

                    // Log in seller_due_deduction_histories
                    SellerDueDeductionHistory::create([
                        'user_id' => $sellerId,
                        'shop_id' => $shop->id,
                        'order_id' => $order->id,
                        'amount' => $dueAmount,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'note' => "Order #{$order->unique_order_id} delivered (ERP: ৳{$sellerSubtotal} - Rate: ৳{$sellerRateTotal})",
                    ]);

                    Log::info("Self Delivery Due Deducted: Seller {$sellerId} | Shop {$shop->id} | Order {$order->id} | Due: {$dueAmount} | Bal: {$balanceBefore} -> {$balanceAfter}");
                }

                // Update seller items status to delivered
                OrderItem::whereIn('id', $sellerItems->pluck('id')->toArray())->update([
                    'seller_status' => $newStatus,
                    'seller_seen' => 1,
                    'seller_updated_at' => Carbon::now(),
                ]);

                // Update main order status to delivered/completed
                $order->status = $newStatus === 'done' ? 'completed' : 'delivered';
                $order->save();

                // Trigger existing settlement distribution (Customer Cashback, Points, Earning)
                if ($newStatus === 'done' || $newStatus === 'delivered') {
                    $orderStatusModel = new \App\Models\OrderStatus();
                    $orderStatusModel->create([
                        'order_id' => $order->id,
                        'status' => $newStatus,
                    ]);

                    // Execute distribution services safely
                    try {
                        $earningService = new \App\Services\EarningDistribution();
                        $pointService = new \App\Services\PointService();

                        $earningService->DistributeEarning($order->id);
                        $pointService->UpdatePoint($order->id);
                    } catch (\Throwable $ex) {
                        Log::error("Self Delivery Settlement Trigger Exception: " . $ex->getMessage());
                    }
                }

                DB::commit();
                return redirect()->back()->with('success', 'অর্ডারটি সফলভাবে ডেলিভারি সম্পন্ন হয়েছে এবং প্রয়োজনীয় ব্যালেন্স সমন্বয় করা হয়েছে।');

            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Self Delivery Delivery Completion Error: " . $e->getMessage());
                return redirect()->back()->with('error', 'ডেলিভারি সম্পন্ন করতে ত্রুটি হয়েছে: ' . $e->getMessage());
            }
        }

        // ==========================================
        // 3. STANDARD STATUS UPDATES
        // ==========================================
        OrderItem::whereIn('id', $sellerItems->pluck('id')->toArray())->update([
            'seller_status' => $newStatus,
            'seller_seen' => 1,
            'seller_updated_at' => Carbon::now(),
        ]);

        // If self delivery, sync main order status
        if ($isSelfDelivery) {
            if ($newStatus === 'accepted') {
                $order->status = 'accepted';
                $order->save();
            } elseif ($newStatus === 'on_delivery') {
                $order->status = 'on_delivery';
                $order->save();
            }
        } else {
            // Ettaba delivery: if all active sellers accepted, move main order to accepted
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
        }

        $statusLabels = [
            'accepted' => 'Accepted (অর্ডার গ্রহণ করা হয়েছে)',
            'processing' => 'Processing (পণ্য প্রস্তুত ও প্যাকেজিং হচ্ছে)',
            'ready_for_pickup' => 'Ready for Pickup (পিকআপের জন্য প্রস্তুত)',
            'on_delivery' => 'On Delivery (ডেলিভারিতে আছে)',
            'delivered' => 'Delivered (ডেলিভারি সম্পন্ন)',
            'done' => 'Done (সম্পন্ন)',
        ];

        return redirect()->back()->with('success', 'অর্ডার স্ট্যাটাস আপডেট হয়েছে: ' . ($statusLabels[$newStatus] ?? $newStatus));
    }
}
