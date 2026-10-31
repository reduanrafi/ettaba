@extends('admin.layouts.layout')
@section('content')
    <section class="invoice">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check-circle"></i> {{ Session::get('success') }}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-exclamation-triangle"></i> {{ Session::get('error') }}
            </div>
        @endif

        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Ettaba Shop.
                    <small class="pull-right">Date: {{ date('Y-m-d', strtotime($order->created_at)) }}</small>
                </h2>
            </div>
        </div>

        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>Ettabashop</strong><br>
                    Kishoreganj Sadar<br>
                    Kishoreganj<br>
                    Phone: 01926261878<br>
                    Email: info@ettabashop.com
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>{{ optional(optional($order->user)->profile)->first_name }} {{ optional(optional($order->user)->profile)->last_name }}</strong><br>
                    {{ optional(optional($order->user)->profile)->address }}<br>
                    Phone: {{ optional($order->user)->phone }}<br>
                    Email: {{ optional($order->user)->email }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <br>
                <b>Order ID:</b> {{ $order->unique_order_id ?: $order->id }}<br>
                <b>Order Status:</b> <span class="label label-primary">{{ ucfirst($order->status) }}</span><br>
                <b>Payment status:</b>
                @if(($order->paymentMethod->short_code ?? '') === 'EPS')
                    @if(($order->payment_status ?? '') === 'paid')
                        <span class="label label-success">Gateway Payment</span>
                    @else
                        <span class="label label-warning">Pending (EPS)</span>
                    @endif
                @else
                    <span class="label label-info">{{ strtoupper($order->payment_status ?? 'DUE') }} ({{ $order->paymentMethod->short_code ?? 'COD' }})</span>
                @endif
                <br>
                @if(($order->paymentMethod->short_code ?? '') === 'EPS' && ($order->payment_status ?? '') !== 'paid')
                    <a href="{{ route('order.verifyEps', $order->id) }}" class="btn btn-xs btn-info" style="margin-top: 5px; margin-bottom: 5px;">
                        <i class="fa fa-refresh"></i> Verify EPS Status
                    </a><br>
                @endif
                <b>eID:</b> {{ optional($order->user)->unique_id }}
            </div>
        </div>

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Seller / Owner</th>
                        <th>Seller Status (সেলার আপডেট)</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->orderItems as $item)
                        @php
                            $isSellerCanceled = ($item->seller_status ?? '') === 'canceled';
                            $sellerStatus = $item->seller_status ?? 'new';
                            $ownerUser = $item->product ? $item->product->owner : null;
                        @endphp
                        <tr style="{{ $isSellerCanceled ? 'background-color: #fff5f5;' : '' }}">
                            <td>
                                <div style="{{ $isSellerCanceled ? 'filter: blur(0.7px); opacity: 0.5; text-decoration: line-through;' : '' }}">
                                    {{ $item->product_name ?? ($item->product ? $item->product->name_en : 'Product') }}
                                    {{ ($item->product_unit ?? ($item->product ? $item->product->unit : '')) ? ' (' . ($item->product_unit ?? $item->product->unit) . ')' : '' }}
                                </div>
                                @if($isSellerCanceled)
                                    <div style="margin-top: 4px;">
                                        <span class="label label-danger"><i class="fa fa-ban"></i> সেলার কর্তৃক বাতিলকৃত (মূল হিসাব থেকে বাদ)</span>
                                        @if(!empty($item->seller_cancel_reason))
                                            <small class="text-danger" style="display:block; margin-top:2px;">
                                                <strong>কারণ:</strong> {{ $item->seller_cancel_reason }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td style="{{ $isSellerCanceled ? 'filter: blur(0.7px); opacity: 0.5; text-decoration: line-through;' : '' }}">
                                {{ $item->product ? $item->product->unique_id : 'N/A' }}
                            </td>
                            <td>
                                @if($ownerUser)
                                    <a href="{{ route('order.shop', ['id' => $ownerUser->id]) }}">
                                        {{ $ownerUser->shop != null ? $ownerUser->shop->name_en : $ownerUser->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($sellerStatus === 'new')
                                    <span class="label label-warning">New Order</span>
                                @elseif($sellerStatus === 'accepted')
                                    <span class="label label-info">Accepted</span>
                                @elseif($sellerStatus === 'processing')
                                    <span class="label label-primary">Processing</span>
                                @elseif($sellerStatus === 'ready_for_pickup')
                                    <span class="label label-success"><i class="fa fa-check"></i> Ready for Pickup</span>
                                @elseif($sellerStatus === 'canceled')
                                    <span class="label label-danger">Canceled by Seller</span>
                                @endif
                            </td>
                            <td style="{{ $isSellerCanceled ? 'filter: blur(0.7px); opacity: 0.5; text-decoration: line-through;' : '' }}">
                                {{ $item->quantity }}
                            </td>
                            <td style="{{ $isSellerCanceled ? 'filter: blur(0.7px); opacity: 0.5; text-decoration: line-through;' : '' }}">
                                ৳{{ $item->price }}
                            </td>
                            <td style="{{ $isSellerCanceled ? 'filter: blur(0.7px); opacity: 0.5; text-decoration: line-through;' : '' }}">
                                ৳{{ $item->quantity * $item->price }}
                            </td>
                            <td>
                                @if(!$isSellerCanceled && $item->product)
                                    <a href="{{ route('item.remove', ['id' => $item->id, 'product_id' => $item->product->id, 'order_id' => $item->order_id]) }}"
                                       class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @php
            $currentDeliveryCharge = isset($order->delivery_charge) && $order->delivery_charge !== null
                ? (float) $order->delivery_charge
                : 0;
            $grandTotal = (float) $order->erp_total + $currentDeliveryCharge;
        @endphp

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <p class="lead">Payment Methods:</p>
                <h4>{{ optional($order->paymentMethod)->short_code }} (<span style="font-size: 15px">{{ optional($order->paymentMethod)->name }}</span>)</h4>

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    সম্মানীত গ্রাহক, সাইটের বর্ণনার সাথে পণ্যের মিল থাকার পরেও যদি আপনি অর্ডারকৃত পণ্য গ্রহণ না করেন,
                    তবে পরবর্তী অর্ডার থেকে আপনার জন্য ক্যাশ অন ডেলিভারি সুবিধা বন্ধ হয়ে যাবে। ধন্যবাদ।
                </p>
            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:45%">Products Total (ERP):</th>
                            <td><strong>৳{{ number_format((float) $order->erp_total, 2) }} Taka</strong></td>
                        </tr>
                        <tr>
                            <th>Shipping / ডেলিভারি চার্জ:</th>
                            <td>
                                <form action="{{ route('order.updateDeliveryCharge', $order->id) }}" method="POST" class="form-inline" style="display:flex; gap:6px; align-items:center;">
                                    @csrf
                                    <input type="number" step="0.01" min="0" name="delivery_charge" value="{{ $currentDeliveryCharge }}" class="form-control input-sm" style="width: 110px;">
                                    <button type="submit" class="btn btn-xs btn-primary">
                                        <i class="fa fa-save"></i> আপডেট করুন
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <th>Grand Total (সর্বমোট):</th>
                            <td><strong style="font-size: 16px; color: #d9534f;">৳{{ number_format($grandTotal, 2) }} Taka</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
