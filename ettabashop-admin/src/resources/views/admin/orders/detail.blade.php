@extends('admin.layouts.layout')
@section('content')
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Ettaba shop.
                    <small class="pull-right">Date: {{ date('y-m-d',strtotime($order->created_at)) }}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>Ettabashop</strong><br>
                    Kishoregonj sadar<br>
                    Kishoregonj<br>
                    Phone: 01926261878<br>
                    Email: info@ettabashop.com
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>{{ $order->user->profile->first_name }} {{ $order->user->profile->last_name }}</strong><br>
                    {{ $order->user->profile->address }}<br>

                    Phone: {{ $order->user->phone }}<br>
                    Email: {{ $order->user->email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                {{--                <b>Invoice #007612</b><br>--}}
                <br>
                <b>Order ID:</b> {{ $order->unique_order_id }}<br>
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
                <b>eID:</b> {{$order->user->unique_id}}
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Owner</th>
                        <th>Quantity</th>
                        <th>unite price</th>


                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>

                            <td>{{ $item->product->name_en }}({{ $item->product->unit }})</td>
                            <td>{{ $item->product->unique_id }}</td>
                            <td>
                                <a href="{{ route('order.shop',['id'=>$item->product->owner->id]) }}">
                                    {{ ($item->product->owner->shop!=null?$item->product->owner->shop->name_en:$item->product->owner->name) }}
                                </a>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>


                            <td>{{ $item->quantity*$item->price }}</td>
                            <td>
                                <a href="{{ route('item.remove',['id'=>$item->id,'product_id'=>$item->product->id,'order_id'=>$item->order_id]) }}"
                                   class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead">Payment Methods:</p>
                <h4> {{ $order->paymentMethod->short_code }} (<span
                            style="font-size: 15px">{{ $order->paymentMethod->name }}</span>) </h4>

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    সম্মানীত গ্রাহক, সাইটের বর্ণনার সাথে পণ্যের মিল থাকার পরেও যদি আপনি অর্ডারকৃত পণ্য গ্রহণ না করেন,
                    তবে পরবর্তী অর্ডার থেকে আপনার জন্য ক্যাশ অন ডেলিভারি সুবিধা বন্ধ হয়ে যাবে। ধন্যবাদ।

                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                {{--                <p class="lead">Amount Due 2/22/2014</p>--}}

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        {{--                        <tr>--}}
                        {{--                            <th style="width:50%">Subtotal:</th>--}}
                        {{--                            <td>$250.30</td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th>Tax (9.3%)</th>--}}
                        {{--                            <td>$10.34</td>--}}
                        {{--                        </tr>--}}
                        <tr>
                            <th>Shipping:</th>

                            @if($order->created_at>"2022-10-04 03:33:15")
                                <td>5 Taka</td>
                            @else
                                <td> 10 Taka</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>{{ $order->erp_total }} Taka</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        {{--        <div class="row no-print">--}}
        {{--            <div class="col-xs-12">--}}
        {{--                <a onclick="javascript:window.print();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>--}}

        {{--            </div>--}}
        {{--        </div>--}}
    </section>
@endsection
@section('extra-script')
    {{--    <script src="https://adminlte.io/themes/AdminLTE/bower_components/fastclick/lib/fastclick.js"></script>--}}
@endsection
