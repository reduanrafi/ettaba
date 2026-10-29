@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-shopping-bag text-green"></i> আমার অর্ডারসমূহ (Seller Order Management)
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
        </div>
    @endif

    <div class="callout callout-info" style="margin-bottom: 15px;">
        <h4><i class="fa fa-info-circle"></i> সেলার অর্ডার নির্দেশনা:</h4>
        <p style="margin-bottom: 0;">
            ১. আপনার দায়িত্ব শুধু <strong>Ready for Pickup (পিকআপের জন্য প্রস্তুত)</strong> স্ট্যাটাস পর্যন্ত থাকবে।<br>
            ২. এরপরের সব কার্যক্রম (পিকআপ, শিপমেন্ট, কাস্টমারের কাছে ডেলিভারি ও <strong>Delivered</strong> স্ট্যাটাস) অ্যাডমিন পরিচালনা করবেন এবং আপনি এখান থেকে সরাসরি অ্যাডমিনের আপডেট দেখতে পারবেন।
        </p>
    </div>

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
                <a href="{{ route('seller.orders.index', ['status' => 'ready_for_pickup']) }}"
                   class="btn btn-sm {{ $statusFilter === 'ready_for_pickup' ? 'btn-success' : 'btn-default' }}">
                    ৪. Ready for Pickup — পিকআপের জন্য প্রস্তুত ({{ $counts['ready_for_pickup'] ?? 0 }})
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'canceled']) }}"
                   class="btn btn-sm {{ $statusFilter === 'canceled' ? 'btn-danger' : 'btn-default' }}">
                    Canceled — বাতিলকৃত ({{ $counts['canceled'] ?? 0 }})
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}"
                   class="btn btn-sm {{ $statusFilter === 'delivered' ? 'btn-success' : 'btn-default' }}">
                    Delivered (এডমিন আপডেট)
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
                        <th>সেলার স্ট্যাটাস (আপনার প্যানেল)</th>
                        <th>অ্যাডমিন / ডেলিভারি স্ট্যাটাস</th>
                        <th>অ্যাকশন (৪টি ধাপ)</th>
                        <th>বিস্তারিত</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellerOrders as $entry)
                        @php
                            $order = $entry->order;
                            $sStatus = $entry->seller_status;
                            $adminStatus = $order->status ?? 'pending';
                            $isLockedByAdmin = in_array($adminStatus, ['on_delivery', 'delivered', 'completed', 'done'], true);
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
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td><strong>৳{{ number_format($entry->seller_subtotal, 2) }}</strong></td>
                            <td><strong class="text-green">৳{{ number_format($entry->seller_rate_total, 2) }}</strong></td>
                            <td>
                                @if($sStatus === 'new')
                                    <span class="label label-warning">১. New Order (নতুন অর্ডার)</span>
                                @elseif($sStatus === 'accepted')
                                    <span class="label label-info">২. Accepted (গ্রহণকৃত)</span>
                                @elseif($sStatus === 'processing')
                                    <span class="label label-primary">৩. Processing (প্রস্তুত হচ্ছে)</span>
                                @elseif($sStatus === 'ready_for_pickup')
                                    <span class="label label-success">৪. Ready for Pickup (পিকআপের জন্য প্রস্তুত)</span>
                                @elseif($sStatus === 'canceled')
                                    <span class="label label-danger">Canceled (বাতিলকৃত)</span>
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
                                    <span class="label label-warning"><i class="fa fa-truck"></i> On Delivery (শিপমেন্টে আছে)</span>
                                @elseif($adminStatus === 'delivered' || $adminStatus === 'completed' || $adminStatus === 'done')
                                    <span class="label label-success"><i class="fa fa-check-circle"></i> Delivered (সম্পন্ন)</span>
                                @elseif($adminStatus === 'canceled')
                                    <span class="label label-danger">Order Canceled</span>
                                @else
                                    <span class="label label-default">{{ ucfirst($adminStatus) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($isLockedByAdmin)
                                    <span class="text-muted small"><i class="fa fa-lock"></i> অ্যাডমিন নিয়ন্ত্রণে ({{ ucfirst($adminStatus) }})</span>
                                @elseif($sStatus === 'canceled')
                                    <span class="text-danger small">আপনি অর্ডারটি বাতিল করেছেন</span>
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
                                                    <i class="fa fa-cogs"></i> Processing (প্রস্তুত হচ্ছে)
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-xs btn-danger"
                                                    onclick="openSellerCancelModal({{ $order->id }}, '{{ $order->unique_order_id ?: $order->id }}')">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>
                                        @elseif($sStatus === 'processing')
                                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="seller_status" value="ready_for_pickup">
                                                <button type="submit" class="btn btn-xs btn-success">
                                                    <i class="fa fa-archive"></i> Ready for Pickup
                                                </button>
                                            </form>
                                        @elseif($sStatus === 'ready_for_pickup')
                                            <span class="text-success small"><i class="fa fa-check-circle"></i> পিকআপের জন্য প্রস্তুত (অ্যাডমিন পিকআপ করবেন)</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-xs btn-default">
                                    <i class="fa fa-eye"></i> বিস্তারিত দেখুন
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 25px;">
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
                        পণ্য স্টকে না থাকলে বা অন্য কোনো কারণে অর্ডার পূরণ করা সম্ভব না হলে নিচে কারণ উল্লেখ করে অর্ডারটি প্রত্যাখ্যান করুন। (বাতিল করলে মূল অর্ডার থেকে শুধুমাত্র আপনার পণ্যের মূল্য বাদ যাবে এবং ব্লার হয়ে থাকবে।)
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

<script>
    function openSellerCancelModal(orderId, orderNo) {
        var form = document.getElementById('sellerCancelForm');
        form.action = '{{ url("/private-panel/seller-orders/update-status") }}/' + orderId;
        document.getElementById('cancelOrderNoText').textContent = orderNo;
        $('#sellerCancelModal').modal('show');
    }
</script>
@endsection

