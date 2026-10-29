@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-file-text-o text-green"></i> অর্ডার বিস্তারিত — #{{ $order->unique_order_id ?: $order->id }}
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
        </div>
    @endif

    @php
        $adminStatus = $order->status ?? 'pending';
        $isLockedByAdmin = in_array($adminStatus, ['on_delivery', 'delivered', 'completed', 'done'], true);
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
                        @elseif($sellerStatus === 'ready_for_pickup')
                            <span class="label label-success" style="font-size: 13px;">৪. Ready for Pickup — পিকআপের জন্য প্রস্তুত</span>
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
                        <strong>অ্যাডমিন / ডেলিভারি স্ট্যাটাস (Read-only):</strong>
                        @if($adminStatus === 'on_delivery')
                            <span class="label label-warning"><i class="fa fa-truck"></i> On Delivery (শিপমেন্টে আছে)</span>
                        @elseif(in_array($adminStatus, ['delivered', 'completed', 'done'], true))
                            <span class="label label-success"><i class="fa fa-check-circle"></i> Delivered (ডেলিভারি সম্পন্ন)</span>
                        @elseif($adminStatus === 'canceled')
                            <span class="label label-danger">Canceled</span>
                        @else
                            <span class="label label-default">{{ ucfirst($adminStatus) }}</span>
                        @endif
                    </p>

                    <hr>

                    @if($isLockedByAdmin)
                        <div class="callout callout-success" style="margin-bottom: 0;">
                            <p><i class="fa fa-lock"></i> পিকআপ পরবর্তী কার্যক্রম বর্তমানে অ্যাডমিনের নিয়ন্ত্রণে রয়েছে।</p>
                        </div>
                    @elseif($sellerStatus === 'canceled')
                        <div class="callout callout-danger" style="margin-bottom: 0;">
                            <p>আপনি এই অর্ডারটি বাতিল করেছেন।</p>
                        </div>
                    @else
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

                        @if($sellerStatus !== 'ready_for_pickup')
                            <hr>
                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST"
                                  onsubmit="return confirm('আপনি কি নিশ্চিতভাবে আপনার অংশের অর্ডারটি বাতিল করতে চান?');">
                                @csrf
                                <input type="hidden" name="seller_status" value="canceled">
                                <div class="form-group">
                                    <label class="text-danger">অর্ডার বাতিল করতে চাইলে কারণ লিখুন:</label>
                                    <textarea name="cancel_reason" class="form-control" rows="2" required placeholder="যেমন: পণ্যটি বর্তমানে স্টকে নেই..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fa fa-times-circle"></i> কারণসহ অর্ডার বাতিল (Cancel) করুন
                                </button>
                            </form>
                        @endif
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
                        </div>
                    </div>

                    <hr>
                    <h4><i class="fa fa-cubes"></i> এই অর্ডারে শুধুমাত্র আপনার পণ্যের তালিকা:</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>পণ্যের নাম</th>
                                    <th>কোড</th>
                                    <th>পরিমাণ</th>
                                    <th>একক মূল্য (ERP)</th>
                                    <th>সেলার রেট</th>
                                    <th>মোট (ERP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sellerItems as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product_name ?: ($item->product ? $item->product->name_en : 'Product') }}
                                            @if($item->product_unit || ($item->product && $item->product->unit))
                                                <small class="text-muted">({{ $item->product_unit ?: $item->product->unit }})</small>
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
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

