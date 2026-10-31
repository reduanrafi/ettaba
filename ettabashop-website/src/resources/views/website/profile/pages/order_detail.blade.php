<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Detail</a></li>
        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        @php
            $orderDeliveryCharge = (float) ($order->delivery_charge ?? 0);
            $orderGrandTotal = (float) $order->erp_total + $orderDeliveryCharge;
        @endphp
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Order Info
                <br>
                <b>Order ID:</b> {{ $order->unique_order_id }}<br>
                <b>Payment status:</b> {{ strtoupper($order->payment_status ?? 'DUE') }} ({{ optional($order->paymentMethod)->short_code }} )<br>
                <b>eID:</b> {{ optional($order->user)->unique_id }}
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
               Delivery address
                <address>
                    <strong>{{ optional($order->user->profile)->first_name }} {{ optional($order->user->profile)->last_name }}</strong><br>
                    {{ optional($order->user->profile)->address }}<br>

                    Phone: {{ optional($order->user)->phone }}<br>

                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Products Total:</b> {{ $order->erp_total }} Tk<br>
                <b>Delivery charge:</b> {{ $orderDeliveryCharge }} Tk<br>
                <b>Total bill:</b> <strong>{{ $orderGrandTotal }} Tk</strong><br>
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
                            <td>{{ $item->product_name ?? ($item->product ? $item->product->name_en : 'Product') }}{{ ($item->product_unit ?? ($item->product ? $item->product->unit : '')) ? ' (' . ($item->product_unit ?? $item->product->unit) . ')' : '' }}</td>
                            <td>{{ $item->product ? $item->product->unique_id : 'N/A' }}</td>
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
    </div><!-- /.card-body -->
</div>
