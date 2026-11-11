@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-shopping-bag text-green"></i> আমার অর্ডারসমূহ (Seller Order Management)
        @if($isSelfDelivery)
            <span class="label label-warning" style="font-size: 13px; margin-left: 8px;">
                <i class="fa fa-truck"></i> সেলফ ডেলিভারি মোড (Self Delivery Mode)
            </span>
        @else
            <span class="label label-info" style="font-size: 13px; margin-left: 8px;">
                <i class="fa fa-cube"></i> ইত্তেবা ডেলিভারি মোড (Ettaba Delivery Mode)
            </span>
        @endif
        @if(($counts['unseen'] ?? 0) > 0)
            <span class="label label-danger" style="font-size: 13px; margin-left: 8px;">
                {{ $counts['unseen'] }}টি নতুন অর্ডার (Unseen)
            </span>
        @endif
    </h1>
</section>

<section class="content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            @if(session('need_add_money'))
                <div style="margin-top: 10px;">
                    <a href="{{ route('seller.addMoney.index') }}" class="btn btn-warning btn-sm" style="font-weight: 700;">
                        <i class="fa fa-plus-circle"></i> এখনই ব্যালেন্স রিচার্জ (Add Money) করুন
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- eBalance & Pending Due Cards (Pages 10-11 spec) --}}
    @if($isSelfDelivery)
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-green" style="border-radius: 8px;">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Seller e-Balance</span>
                        <span class="info-box-number">৳ {{ number_format($sellerEbalance, 2) }}</span>
                        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                        <span class="progress-description">
                            <a href="{{ route('seller.addMoney.index') }}" style="color: #fff; font-weight: 700; text-decoration: underline;">
                                + Add Money (রিচার্জ করুন)
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-red" style="border-radius: 8px;">
                    <span class="info-box-icon"><i class="fa fa-hourglass-half"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Balance (চলতি অপরিশোধিত ডিউ)</span>
                        <span class="info-box-number">- ৳ {{ number_format($totalPendingDue, 2) }}</span>
                        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                        <span class="progress-description">
                            অর্ডার ডেলিভারির সময় সমন্বিত হবে
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="callout callout-warning" style="margin-bottom: 0; padding: 10px 15px; border-radius: 8px;">
                    <h5 style="margin-top: 0; font-weight: 700;"><i class="fa fa-info-circle"></i> সেলফ ডেলিভারি নির্দেশিকা:</h5>
                    <p style="font-size: 12px; margin-bottom: 0;">
                        পণ্য কাস্টমারকে সরবরাহ করার পর <strong>Delivered</strong> চাপলে আপনার e-Balance থেকে কোম্পানির কমিশন (Pending Due) স্বয়ংক্রিয়ভাবে কর্তন হবে এবং কাস্টমার ক্যাশব্যাক ও পয়েন্ট পাবেন।
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="callout callout-info" style="margin-bottom: 15px;">
            <h4><i class="fa fa-info-circle"></i> সেলার অর্ডার নির্দেশনা:</h4>
            <p style="margin-bottom: 0;">
                ১. আপনার দায়িত্ব শুধু <strong>Ready for Pickup (পিকআপের জন্য প্রস্তুত)</strong> স্ট্যাটাস পর্যন্ত থাকবে।<br>
                ২. এরপরের সব কার্যক্রম (পিকআপ, শিপমেন্ট, কাস্টমারের কাছে ডেলিভারি ও <strong>Delivered</strong> স্ট্যাটাস) লোকাল অফিস পরিচালনা করবেন এবং আপনি এখান থেকে সরাসরি লোকাল অফিসের আপডেট দেখতে পারবেন।
            </p>
        </div>
    @endif

    {{-- Status Filter Tabs --}}
    <div class="box box-success">
        <div class="box-header with-border">
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                <a href="{{ route('seller.orders.index', ['status' => 'all']) }}"
                   class="btn btn-sm {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-default' }}">
                    সকল অর্ডার ({{ $counts['all'] ?? 0 }})
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'new']) }}"
                   class="btn btn-sm {{ $statusFilter === 'new' ? 'btn-warning' : 'btn-default' }}">
                    ১. New Order — নতুন অর্ডার ({{ $counts['new'] ?? 0 }})
                    @if(($counts['unseen'] ?? 0) > 0)
                        <span class="badge bg-red">{{ $counts['unseen'] }} নতুন</span>
                    @endif
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'accepted']) }}"
                   class="btn btn-sm {{ $statusFilter === 'accepted' ? 'btn-info' : 'btn-default' }}">
                    ২. Accepted — গ্রহণকৃত ({{ $counts['accepted'] ?? 0 }})
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'processing']) }}"
                   class="btn btn-sm {{ $statusFilter === 'processing' ? 'btn-primary' : 'btn-default' }}">
                    ৩. Processing — পণ্য প্রস্তুত হচ্ছে ({{ $counts['processing'] ?? 0 }})
                </a>
                @if($isSelfDelivery)
                    <a href="{{ route('seller.orders.index', ['status' => 'on_delivery']) }}"
                       class="btn btn-sm {{ $statusFilter === 'on_delivery' ? 'btn-warning' : 'btn-default' }}">
                        ৪. On Delivery — ডেলিভারিতে আছে ({{ $counts['on_delivery'] ?? 0 }})
                    </a>
                @else
                    <a href="{{ route('seller.orders.index', ['status' => 'ready_for_pickup']) }}"
                       class="btn btn-sm {{ $statusFilter === 'ready_for_pickup' ? 'btn-success' : 'btn-default' }}">
                        ৪. Ready for Pickup — পিকআপের জন্য প্রস্তুত ({{ $counts['ready_for_pickup'] ?? 0 }})
                    </a>
                @endif
                <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}"
                   class="btn btn-sm {{ $statusFilter === 'delivered' ? 'btn-success' : 'btn-default' }}">
                    {{ $isSelfDelivery ? '৫. Delivered — ডেলিভারি সম্পন্ন' : 'Delivered (লোকাল অফিস আপডেট)' }} ({{ $counts['delivered'] ?? 0 }})
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'canceled']) }}"
                   class="btn btn-sm {{ $statusFilter === 'canceled' ? 'btn-danger' : 'btn-default' }}">
                    Canceled — বাতিলকৃত ({{ $counts['canceled'] ?? 0 }})
                </a>
            </div>
        </div>

        <div class="box-body table-responsive">
            <table id="sellerOrdersTable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>অর্ডার আইডি</th>
                        <th>কাস্টমার তথ্য</th>
                        <th>আপনার পণ্যসমূহ</th>
                        <th>বিক্রয়মূল্য (ERP)</th>
                        <th>সেলার রেট</th>
                        @if($isSelfDelivery)
                            <th>Pending Due</th>
                        @endif
                        <th>সেলার স্ট্যাটাস</th>
                        <th>ডেলিভারি স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                        <th>বিস্তারিত</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellerOrders as $entry)
                        @php
                            $order = $entry->order;
                            $sStatus = $entry->seller_status;
                            $adminStatus = $order->status ?? 'pending';
                            $isLockedByAdmin = !$isSelfDelivery && in_array($adminStatus, ['on_delivery', 'delivered', 'completed', 'done'], true);
                        @endphp
                        <tr style="{{ $entry->is_unseen ? 'background-color: #fffbeb; font-weight: 600;' : '' }}">
                            <td>
                                <strong>#{{ $order->unique_order_id ?: $order->id }}</strong>
                                @if($entry->is_unseen)
                                    <br><span class="label label-danger"><i class="fa fa-bell"></i> নতুন (Unseen)</span>
                                @endif
                                <br><small class="text-muted">{{ $order->created_at ? date('d M Y, h:i A', strtotime($order->created_at)) : '' }}</small>
                            </td>
                            <td>
                                @if($order->user)
                                    <strong>{{ optional($order->user->profile)->first_name }} {{ optional($order->user->profile)->last_name }}</strong>
                                    <br><small>{{ $order->user->phone }}</small>
                                @else
                                    <span>Guest Customer</span>
                                @endif
                            </td>
                            <td>
                                <ul style="padding-left: 16px; margin-bottom: 0;">
                                    @foreach($entry->items as $item)
                                        <li>
                                            {{ $item->product_name ?: ($item->product ? $item->product->name_en : 'Product') }}
                                            <strong>(x{{ $item->quantity }})</strong>
                                            @if($item->selected_size)
                                                <small class="label label-default">{{ $item->selected_size }}</small>
                                            @endif
                                            @if($item->selected_color)
                                                <small class="label label-info">{{ $item->selected_color }}</small>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td><strong>৳{{ number_format($entry->seller_subtotal, 2) }}</strong></td>
                            <td><strong class="text-green">৳{{ number_format($entry->seller_rate_total, 2) }}</strong></td>
                            @if($isSelfDelivery)
                                <td>
                                    <strong class="text-danger">৳{{ number_format($entry->pending_due, 2) }}</strong>
                                    @if($entry->is_delivered)
                                        <br><span class="label label-success" style="font-size: 10px;">সমন্বিত</span>
                                    @else
                                        <br><span class="label label-warning" style="font-size: 10px;">অপরিশোধিত</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                @if($sStatus === 'new')
                                    <span class="label label-warning">১. New Order</span>
                                @elseif($sStatus === 'accepted')
                                    <span class="label label-info">২. Accepted</span>
                                @elseif($sStatus === 'processing')
                                    <span class="label label-primary">৩. Processing</span>
                                @elseif($sStatus === 'on_delivery')
                                    <span class="label label-warning">৪. On Delivery</span>
                                @elseif($sStatus === 'ready_for_pickup')
                                    <span class="label label-success">৪. Ready for Pickup</span>
                                @elseif($sStatus === 'delivered' || $sStatus === 'done')
                                    <span class="label label-success">৫. Delivered</span>
                                @elseif($sStatus === 'canceled')
                                    <span class="label label-danger">Canceled</span>
                                    @if(!empty($entry->seller_cancel_reason))
                                        <br><small class="text-danger">কারণ: {{ $entry->seller_cancel_reason }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($adminStatus === 'pending')
                                    <span class="label label-default">Pending</span>
                                @elseif($adminStatus === 'accepted')
                                    <span class="label label-info">Accepted</span>
                                @elseif($adminStatus === 'on_delivery')
                                    <span class="label label-warning"><i class="fa fa-truck"></i> On Delivery</span>
                                @elseif($adminStatus === 'delivered' || $adminStatus === 'completed' || $adminStatus === 'done')
                                    <span class="label label-success"><i class="fa fa-check-circle"></i> Delivered</span>
                                @elseif($adminStatus === 'canceled')
                                    <span class="label label-danger">Order Canceled</span>
                                @else
                                    <span class="label label-default">{{ ucfirst($adminStatus) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($isLockedByAdmin)
                                    <span class="text-muted small"><i class="fa fa-lock"></i> লোকাল অফিস নিয়ন্ত্রণে</span>
                                @elseif($sStatus === 'canceled')
                                    <span class="text-danger small">বাতিলকৃত</span>
                                @elseif($entry->is_delivered)
                                    <span class="text-success small"><i class="fa fa-check-circle"></i> সম্পন্ন হয়েছে</span>
                                @else
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                        @if($sStatus === 'new')
                                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="seller_status" value="accepted">
                                                <button type="submit" class="btn btn-xs btn-success">
                                                    <i class="fa fa-check"></i> Accept
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-xs btn-danger"
                                                    onclick="openSellerCancelModal({{ $order->id }}, '{{ $order->unique_order_id ?: $order->id }}')">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>
                                        @elseif($sStatus === 'accepted')
                                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="seller_status" value="processing">
                                                <button type="submit" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-cogs"></i> Processing
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-xs btn-danger"
                                                    onclick="openSellerCancelModal({{ $order->id }}, '{{ $order->unique_order_id ?: $order->id }}')">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>
                                        @elseif($sStatus === 'processing')
                                            @if($isSelfDelivery)
                                                <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="seller_status" value="on_delivery">
                                                    <button type="submit" class="btn btn-xs btn-warning">
                                                        <i class="fa fa-truck"></i> On Delivery
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="seller_status" value="ready_for_pickup">
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-archive"></i> Ready for Pickup
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($sStatus === 'on_delivery' && $isSelfDelivery)
                                            <button type="button" class="btn btn-xs btn-success"
                                                    onclick="handleSelfDeliveredClick({{ $order->id }}, {{ $entry->pending_due }}, {{ $sellerEbalance }})">
                                                <i class="fa fa-check-circle"></i> Delivered
                                            </button>
                                        @elseif($sStatus === 'ready_for_pickup')
                                            <span class="text-success small"><i class="fa fa-check-circle"></i> পিকআপের জন্য প্রস্তুত</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-xs btn-default">
                                    <i class="fa fa-eye"></i> বিস্তারিত
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isSelfDelivery ? 10 : 9 }}" class="text-center text-muted" style="padding: 25px;">
                                কোনো অর্ডার পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Cancel Order Modal with Mandatory Reason --}}
<div class="modal fade" id="sellerCancelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="sellerCancelForm" method="POST" action="">
            @csrf
            <input type="hidden" name="seller_status" value="canceled">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-times-circle"></i> অর্ডার বাতিল করুন (Order #<span id="cancelOrderNoText"></span>)</h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        পণ্য স্টকে না থাকলে বা অন্য কোনো কারণে অর্ডার পূরণ করা সম্ভব না হলে নিচে কারণ উল্লেখ করে অর্ডারটি প্রত্যাখ্যান করুন। বাতিল করলে স্বয়ংক্রিয়ভাবে স্টক পুনরুদ্ধার হবে।
                    </p>
                    <div class="form-group">
                        <label>বাতিল করার কারণ উল্লেখ করুন <span class="text-danger">*</span></label>
                        <textarea name="cancel_reason" class="form-control" rows="3" required placeholder="যেমন: পণ্যটি বর্তমানে স্টকে নেই..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> বাতিল নিশ্চিত করুন</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Self Delivery Delivered Confirmation Modal --}}
<div class="modal fade" id="selfDeliveryConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="selfDeliveryConfirmForm" method="POST" action="">
            @csrf
            <input type="hidden" name="seller_status" value="delivered">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-check-circle"></i> ডেলিভারি নিশ্চিতকরণ</h4>
                </div>
                <div class="modal-body">
                    <p style="font-size: 15px; line-height: 1.5; color: #1e293b;">
                        আপনার Seller eBalance থেকে <strong class="text-danger" id="dueDeductAmountText">৳ 0.00</strong> কেটে নেওয়া হবে। আপনি কি নিশ্চিত?
                    </p>
                    <p class="text-muted" style="font-size: 12px; margin-bottom: 0;">
                        ডেলিভারি সম্পন্ন করার পর কাস্টমার ক্যাশব্যাক ও পয়েন্ট পাবেন এবং আপনার রিওয়ার্ড অগ্রগতি বৃদ্ধি পাবে।
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">না</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> হ্যাঁ, নিশ্চিত</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Self Delivery Insufficient Balance Warning Modal --}}
<div class="modal fade" id="insufficientBalanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-yellow">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> অপর্যাপ্ত Seller e-Balance</h4>
            </div>
            <div class="modal-body">
                <p style="font-size: 14.5px; line-height: 1.6; color: #1e293b;">
                    এই অর্ডারটি সম্পন্ন করতে আপনার Seller eBalance-এ <strong class="text-danger">৳<span id="insufficientDueText"></span></strong> লোড করুন। এরপর অর্ডারটি সম্পন্ন করার মাধ্যমে কাস্টমারকে ক্যাশব্যাক প্রদান করুন এবং নিজেও পয়েন্ট অর্জন করুন আর আকর্ষণীয় পুরস্কারের পথে আরও এক ধাপ এগিয়ে যান। ধন্যবাদ।
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                <a href="{{ route('seller.addMoney.index') }}" class="btn btn-warning" style="font-weight: 700;">
                    <i class="fa fa-plus-circle"></i> Add Money (ব্যালেন্স রিচার্জ করুন)
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openSellerCancelModal(orderId, orderNo) {
        var form = document.getElementById('sellerCancelForm');
        form.action = '{{ url("/private-panel/seller-orders/update-status") }}/' + orderId;
        document.getElementById('cancelOrderNoText').textContent = orderNo;
        $('#sellerCancelModal').modal('show');
    }

    function handleSelfDeliveredClick(orderId, dueAmount, currentBalance) {
        if (currentBalance < dueAmount) {
            document.getElementById('insufficientDueText').textContent = parseFloat(dueAmount).toFixed(2);
            $('#insufficientBalanceModal').modal('show');
        } else {
            document.getElementById('dueDeductAmountText').textContent = '৳ ' + parseFloat(dueAmount).toFixed(2);
            var form = document.getElementById('selfDeliveryConfirmForm');
            form.action = '{{ url("/private-panel/seller-orders/update-status") }}/' + orderId;
            $('#selfDeliveryConfirmModal').modal('show');
        }
    }
</script>
@endsection
