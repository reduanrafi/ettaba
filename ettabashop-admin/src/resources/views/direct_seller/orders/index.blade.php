@extends('admin.layouts.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 700; color: #333;">Sales History</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('direct-seller.order.create') }}" class="btn btn-primary btn-flat pull-right btn-sm">
                                <i class="fa fa-cart-plus"></i> Confirm New Sale
                            </a>
                        </div>
                    </div>

                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible" style="margin: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <div class="box-body table-responsive">
                        <table id="directOrderDataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr style="background-color: #f4f4f4;">
                                    <th>Order ID</th>
                                    <th>Customer Details</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Unit ERP</th>
                                    <th>Total ERP (Deducted)</th>
                                    <th>Retail Profit Earned</th>
                                    <th>Points Issued</th>
                                    <th>Cashback (TCB) Issued</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>
                                            <strong>{{ $order->customer_name ?? 'Guest Customer' }}</strong><br>
                                            <small><i class="fa fa-phone text-muted"></i> {{ $order->customer_phone }}</small><br>
                                            @if($order->customer_address)
                                                <small><i class="fa fa-map-marker text-muted"></i> {{ $order->customer_address }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $order->product ? $order->product->name_en : 'N/A' }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>৳{{ number_format($order->erp, 2) }}</td>
                                        <td><strong class="text-red">৳{{ number_format($order->total_erp, 2) }}</strong></td>
                                        <td><strong class="text-green">৳{{ number_format($order->total_seller_rate - $order->total_company_rate, 2) }}</strong></td>
                                        <td><span class="badge bg-purple">{{ number_format($order->total_reward_points, 2) }}</span></td>
                                        <td><span class="badge bg-olive">৳{{ number_format($order->total_tcb, 2) }}</span></td>
                                        <td>
                                            @if($order->status === 'completed')
                                                <span class="label label-success">Completed</span>
                                            @else
                                                <span class="label label-danger">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-script')
    <script>
        $(function () {
            $('#directOrderDataTable').DataTable({
                responsive: true,
                "order": [[0, "desc"]]
            });
        });
    </script>
@endsection
