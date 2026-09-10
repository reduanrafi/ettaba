<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Detail</a></li>

            

        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Order Info
                <br>
                <b>Order ID:</b> {{ $order->unique_order_id }}<br>
                <b>Payment status:</b> {{ strtoupper($order->payment_status ?? 'DUE') }} ({{ $order->paymentMethod->short_code }} )<br>
                <b>eID:</b> {{$order->user->unique_id}}
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
               Delivery address
                <address>
                    <strong>{{ $order->user->profile->first_name }} {{ $order->user->profile->last_name }}</strong><br>
                    {{ $order->user->profile->address }}<br>

                    Phone: {{ $order->user->phone }}<br>

                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                {{--                <b>Invoice #007612</b><br>--}}


                <b>Total bill:</b> {{ $order->erp_total }}<br>
                <b>Total cash back:</b> {{ $order->tcb }}<br>
                <b>Total point:</b> {{ $order->trp }}<br>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
<br>
        <!-- Table row -->
        <div class="row">

            <div class="col-xs-12 table-responsive">
                <h5 class="text-center"> Ordered Items</h5>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Code</th>

                        <th class="text-center">unite price</th>
                        <th class="text-center">Quantity</th>


                        <th class="text-center">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>

                            <td>{{ $item->product->name_en }}({{ $item->product->unit }})</td>
                            <td>{{ $item->product->unique_id }}</td>

                            <td class="text-center">{{ $item->price }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>

                            <td class="text-center">{{ $item->quantity*$item->price }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>


            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

{{--        <div class="row">--}}
{{--            <!-- accepted payments column -->--}}
{{--            <div class="col-xs-6 col-md-offset-8">--}}
{{--                <p class="lead">Payment Methods: {{ $order->paymentMethod->short_code }} (<span style="font-size: 15px">{{ $order->paymentMethod->name }}</span>)  </p>--}}


{{--            </div>--}}
{{--            <!-- /.col -->--}}
{{--            <div class="col-xs-6">--}}
{{--                --}}{{----}}{{--                <p class="lead">Amount Due 2/22/2014</p>--}}

{{--                <div class="table-responsive ">--}}
{{--                    <table class="table  ">--}}
{{--                        <tbody>--}}

{{--                        <tr>--}}
{{--                            <th>Shipping:</th>--}}
{{--                            <td>1 Taka</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>Total bill:</th>--}}
{{--                            <td>{{ $order->erp_total }} Taka</td>--}}
{{--                        </tr>--}}

{{--                        <tr>--}}
{{--                            <th>Total Cash back:</th>--}}
{{--                            <td>{{ $order->tcb }} Taka</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>Total Point:</th>--}}
{{--                            <td>{{ $order->trp }} </td>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- /.col -->--}}
{{--        </div>--}}
        <!-- /.row -->
    </div><!-- /.card-body -->
</div>