@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-file-text-o text-green"></i> অর্ডার বিস্তারিত — #{{ $order->unique_order_id ?: $order->id }}
        @if($isSelfDelivery)
            <span class="label label-warning" style="font-size: 13px; margin-left: 8px;">
                <i class="fa fa-truck"></i> সেলফ ডেলিভারি মোড (Self Delivery Mode)
            </span>
        @else
            <span class="label label-info" style="font-size: 13px; margin-left: 8px;">
                <i class="fa fa-cube"></i> ইত্তেবা ডেলিভারি মোড (Ettaba Delivery Mode)
            </span>
        @endif
        <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-default pull-right">
            <i class="fa fa-arrow-left"></i> অর্ডার তালিকায় ফিরে যান
        </a>
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

    @php
        $adminStatus = $order->status ?? 'pending';
        $isLockedByAdmin = !$isSelfDelivery && in_array($adminStatus, ['on_delivery', 'delivered', 'completed', 'done'], true);
        $isDelivered = in_array($adminStatus, ['delivered', 'completed', 'done'], true) || in_array($sellerStatus, ['delivered', 'done'], true);
    @endphp

    <div class="row">
        {{-- Left Column: Seller Order Status Control --}}
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tasks"></i> আপনার অর্ডার স্ট্যাটাস নিয়ন্ত্রণ</h3>
                </div>
                <div class="box-body">
                    <p>
                        <strong>বর্তমান সেলার স্ট্যাটাস:</strong>
                        @if($sellerStatus === 'new')
                            <span class="label label-warning" style="font-size: 13px;">১. New Order — নতুন অর্ডার</span>
                        @elseif($sellerStatus === 'accepted')
                            <span class="label label-info" style="font-size: 13px;">২. Accepted — অর্ডার গ্রহণ করা হয়েছে</span>
                        @elseif($sellerStatus === 'processing')
                            <span class="label label-primary" style="font-size: 13px;">৩. Processing — পণ্য প্রস্তুত ও প্যাকেজিং হচ্ছে</span>
                        @elseif($sellerStatus === 'on_delivery')
                            <span class="label label-warning" style="font-size: 13px;">৪. On Delivery — ডেলিভারিতে আছে</span>
                        @elseif($sellerStatus === 'ready_for_pickup')
                            <span class="label label-success" style="font-size: 13px;">৪. Ready for Pickup — পিকআপের জন্য প্রস্তুত</span>
                        @elseif($sellerStatus === 'delivered' || $sellerStatus === 'done')
                            <span class="label label-success" style="font-size: 13px;">৫. Delivered — ডেলিভারি সম্পন্ন</span>
                        @elseif($sellerStatus === 'canceled')
                            <span class="label label-danger" style="font-size: 13px;">Canceled — বাতিলকৃত</span>
                        @endif
                    </p>

                    @if($sellerStatus === 'canceled' && !empty($cancelReason))
                        <div class="alert alert-danger" style="padding: 8px 12px;">
                            <strong>বাতিল করার কারণ:</strong> {{ $cancelReason }}
                        </div>
                    @endif

                    <p>
                        <strong>ডেলিভারি স্ট্যাটাস:</strong>
                        @if($adminStatus === 'on_delivery')
                            <span class="label label-warning"><i class="fa fa-truck"></i> On Delivery</span>
                        @elseif(in_array($adminStatus, ['delivered', 'completed', 'done'], true))
                            <span class="label label-success"><i class="fa fa-check-circle"></i> Delivered (ডেলিভারি সম্পন্ন)</span>
                        @elseif($adminStatus === 'canceled')
                            <span class="label label-danger">Canceled</span>
                        @else
                            <span class="label label-default">{{ ucfirst($adminStatus) }}</span>
                        @endif
                    </p>

                    @if($isSelfDelivery)
                        <div class="well well-sm" style="background: #f8fafc; border-left: 3px solid #16a34a; margin-top: 15px;">
                            <div><strong>Seller e-Balance:</strong> <span class="text-green">৳ {{ number_format($sellerEbalance, 2) }}</span></div>
                            <div><strong>এই অর্ডারের Pending Due:</strong> <span class="text-danger">৳ {{ number_format($pendingDue, 2) }}</span></div>
                            @if($sellerItems->first()->seller_due_deducted ?? false)
                                <div class="text-success" style="margin-top: 5px; font-weight: 700;">
                                    <i class="fa fa-check-circle"></i> কোম্পানি মার্জিন/ডিউ সমন্বয় সম্পন্ন হয়েছে।
                                </div>
                            @endif
                        </div>
                    @endif

                    <hr>

                    @if($isLockedByAdmin)
                        <div class="callout callout-success" style="margin-bottom: 0;">
                            <p><i class="fa fa-lock"></i> পিকআপ পরবর্তী কার্যক্রম বর্তমানে লোকাল অফিসের নিয়ন্ত্রণে রয়েছে।</p>
                        </div>
                    @elseif($sellerStatus === 'canceled')
                        <div class="callout callout-danger" style="margin-bottom: 0;">
                            <p>আপনি এই অর্ডারটি বাতিল করেছেন।</p>
                        </div>
                    @elseif($isDelivered)
                        <div class="callout callout-success" style="margin-bottom: 0;">
                            <p><i class="fa fa-check-circle"></i> অর্ডারটি সফলভাবে ডেলিভারি সম্পন্ন হয়েছে।</p>
                        </div>
                    @else
                        @if($isSelfDelivery)
                            {{-- Self Delivery Status Actions --}}
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                @if($sellerStatus === 'new')
                                    <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="seller_status" value="accepted">
                                        <button type="submit" class="btn btn-info btn-block btn-lg">
                                            <i class="fa fa-check"></i> ২. অর্ডার গ্রহণ করুন (Accept)
                                        </button>
                                    </form>
                                @elseif($sellerStatus === 'accepted')
                                    <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="seller_status" value="processing">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                                            <i class="fa fa-cogs"></i> ৩. প্রস্তুতকরণ শুরু করুন (Processing)
                                        </button>
                                    </form>
                                @elseif($sellerStatus === 'processing')
                                    <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="seller_status" value="on_delivery">
                                        <button type="submit" class="btn btn-warning btn-block btn-lg">
                                            <i class="fa fa-truck"></i> ৪. ডেলিভারিতে পাঠান (On Delivery)
                                        </button>
                                    </form>
                                @elseif($sellerStatus === 'on_delivery')
                                    <button type="button" class="btn btn-success btn-block btn-lg"
                                            onclick="triggerSelfDeliveryConfirmation({{ $order->id }}, {{ $pendingDue }}, {{ $sellerEbalance }})">
                                        <i class="fa fa-check-circle"></i> ৫. ডেলিভারি সম্পন্ন করুন (Delivered)
                                    </button>
                                @endif

                                <hr style="margin: 10px 0;">
                                <button type="button" class="btn btn-danger btn-block"
                                        onclick="openCancelModal()">
                                    <i class="fa fa-times-circle"></i> কারণসহ অর্ডার বাতিল (Cancel) করুন
                                </button>
                            </div>
                        @else
                            {{-- Ettaba Delivery Standard Flow --}}
                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" style="margin-bottom: 12px;">
                                @csrf
                                <div class="form-group">
                                    <label>স্ট্যাটাস পরিবর্তন করুন (Ready for Pickup পর্যন্ত):</label>
                                    <select name="seller_status" class="form-control">
                                        <option value="accepted" {{ $sellerStatus === 'accepted' ? 'selected' : '' }}>
                                            ২. Accepted — অর্ডার গ্রহণ করুন
                                        </option>
                                        <option value="processing" {{ $sellerStatus === 'processing' ? 'selected' : '' }}>
                                            ৩. Processing — পণ্য প্রস্তুত ও প্যাকেজিং হচ্ছে
                                        </option>
                                        <option value="ready_for_pickup" {{ $sellerStatus === 'ready_for_pickup' ? 'selected' : '' }}>
                                            ৪. Ready for Pickup — পিকআপের জন্য প্রস্তুত
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-save"></i> স্ট্যাটাস আপডেট করুন
                                </button>
                            </form>

                            <hr>
                            <button type="button" class="btn btn-danger btn-block" onclick="openCancelModal()">
                                <i class="fa fa-times-circle"></i> কারণসহ অর্ডার বাতিল (Cancel) করুন
                            </button>
                        @endif
                    @endif

                    {{-- Custom Delivery Charge Update (Page 10 spec for Self Delivery) --}}
                    @if($isSelfDelivery && !$isDelivered && $sellerStatus !== 'canceled')
                        <hr>
                        <form action="{{ route('seller.orders.updateDeliveryCharge', $order->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label><i class="fa fa-motorcycle"></i> ডেলিভারি চার্জ নির্ধারণ করুন (৳):</label>
                                <div class="input-group">
                                    <input type="number" name="delivery_charge" class="form-control"
                                           value="{{ (float) $order->delivery_charge }}" min="0" step="any" required>
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">আপডেট</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Customer Info & This Seller's Order Items --}}
        <div class="col-md-7">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> কাস্টমার ও অর্ডারের তথ্য</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>কাস্টমার নাম:</strong> {{ optional($order->user->profile)->first_name }} {{ optional($order->user->profile)->last_name }}</p>
                            <p><strong>মোবাইল:</strong> {{ optional($order->user)->phone }}</p>
                            <p><strong>ঠিকানা:</strong> {{ optional($order->user->profile)->address }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>অর্ডার আইডি:</strong> #{{ $order->unique_order_id ?: $order->id }}</p>
                            <p><strong>তারিখ:</strong> {{ $order->created_at ? date('d M Y, h:i A', strtotime($order->created_at)) : '' }}</p>
                            <p><strong>পেমেন্ট মেথড:</strong> {{ optional($order->paymentMethod)->short_code ?: 'COD' }}</p>
                            <p><strong>ডেলিভারি চার্জ:</strong> ৳{{ number_format((float)$order->delivery_charge, 2) }}</p>
                        </div>
                    </div>

                    <hr>
                    <h4><i class="fa fa-cubes"></i> এই অর্ডারে আপনার পণ্যের তালিকা:</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>পণ্যের নাম</th>
                                    <th>কোড</th>
                                    <th>পরিমাণ</th>
                                    <th>একক ERP</th>
                                    <th>সেলার রেট</th>
                                    <th>মোট (ERP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sellerItems as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product_name ?: ($item->product ? $item->product->name_en : 'Product') }}
                                            @if($item->selected_size)
                                                <br><small class="label label-default">Size: {{ $item->selected_size }}</small>
                                            @endif
                                            @if($item->selected_color)
                                                <small class="label label-info">Color: {{ $item->selected_color }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->product ? $item->product->unique_id : 'N/A' }}</td>
                                        <td><strong>{{ $item->quantity }}</strong></td>
                                        <td>৳{{ number_format((float) $item->price, 2) }}</td>
                                        <td>৳{{ number_format($item->product ? (float) $item->product->rate_en : 0, 2) }}</td>
                                        <td><strong>৳{{ number_format((float) $item->price * (int) $item->quantity, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">সর্বমোট সাপ্লাই মূল্য (আপনার সেলার রেট):</th>
                                    <th colspan="2" class="text-green">৳{{ number_format($sellerRateTotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">সর্বমোট বিক্রয়মূল্য (ERP Total):</th>
                                    <th colspan="2">৳{{ number_format($sellerSubtotal, 2) }}</th>
                                </tr>
                                @if($isSelfDelivery)
                                    <tr style="background: #fef2f2;">
                                        <th colspan="4" class="text-right text-danger">Pending Due (কোম্পানি মার্জিন/কমিশন):</th>
                                        <th colspan="2" class="text-danger">৳{{ number_format($pendingDue, 2) }}</th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    {{-- Items belonging to other sellers in this order (Page 10 spec: Self delivery seller can remove other sellers' items) --}}
                    @if($isSelfDelivery && count($otherItems) > 0)
                        <hr>
                        <h4 class="text-muted"><i class="fa fa-cubes"></i> এই অর্ডারে অন্যান্য সেলারের পণ্যসমূহ:</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>পণ্য</th>
                                        <th>পরিমাণ</th>
                                        <th>মূল্য</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($otherItems as $oItem)
                                        <tr>
                                            <td>{{ $oItem->product_name ?: ($oItem->product ? $oItem->product->name_en : 'Product') }}</td>
                                            <td>{{ $oItem->quantity }}</td>
                                            <td>৳{{ number_format((float) $oItem->price * (int) $oItem->quantity, 2) }}</td>
                                            <td>
                                                @if(!$isDelivered && $sellerStatus !== 'canceled')
                                                    <form action="{{ route('seller.orders.removeItem', $oItem->id) }}" method="POST"
                                                          onsubmit="return confirm('আপনি কি নিশ্চিত যে এই আইটেমটি অর্ডার থেকে মুছে ফেলতে চান?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-danger">
                                                            <i class="fa fa-trash"></i> রিমুভ করুন
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">লকড</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Cancel Order Modal --}}
<div class="modal fade" id="sellerCancelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="sellerCancelForm" method="POST" action="{{ route('seller.orders.updateStatus', $order->id) }}">
            @csrf
            <input type="hidden" name="seller_status" value="canceled">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-times-circle"></i> অর্ডার বাতিল করুন</h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        অর্ডার বাতিল করলে স্বয়ংক্রিয়ভাবে স্টক পুনরুদ্ধার হবে এবং মূল অর্ডার থেকে মূল্য বাদ যাবে।
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
        <form id="selfDeliveryConfirmForm" method="POST" action="{{ route('seller.orders.updateStatus', $order->id) }}">
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

{{-- Insufficient Balance Modal --}}
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
    function openCancelModal() {
        $('#sellerCancelModal').modal('show');
    }

    function triggerSelfDeliveryConfirmation(orderId, dueAmount, currentBalance) {
        if (currentBalance < dueAmount) {
            document.getElementById('insufficientDueText').textContent = parseFloat(dueAmount).toFixed(2);
            $('#insufficientBalanceModal').modal('show');
        } else {
            document.getElementById('dueDeductAmountText').textContent = '৳ ' + parseFloat(dueAmount).toFixed(2);
            $('#selfDeliveryConfirmModal').modal('show');
        }
    }
</script>
@endsection
