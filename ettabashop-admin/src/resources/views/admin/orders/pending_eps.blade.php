@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            {{-- Summary boxes --}}
            <div class="col-md-4">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending EPS Orders</span>
                        <span class="info-box-number">{{ $totalPending }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pending Amount</span>
                        <span class="info-box-number">৳ {{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-refresh"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bulk Action</span>
                        <form action="{{ route('order.bulkVerifyEps') }}" method="POST" style="margin-top: 5px;" onsubmit="return confirm('Are you sure you want to verify ALL pending EPS orders?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-default" {{ $totalPending == 0 ? 'disabled' : '' }}>
                                <i class="fa fa-check-circle"></i> Verify All Pending
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> Pending EPS Payment Orders</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('order.index') }}" class="btn btn-sm btn-default">
                                <i class="fa fa-arrow-left"></i> Back to All Orders
                            </a>
                        </div>
                    </div>

                    @if(Session::has('success'))
                        <div class="col-md-8 col-md-offset-2" style="margin-top: 10px;">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <i class="fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="col-md-8 col-md-offset-2" style="margin-top: 10px;">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <i class="fa fa-times"></i> {{ Session::get('error') }}
                            </div>
                        </div>
                    @endif

                    <div class="box-body">
                        @if($orders->isEmpty())
                            <div class="text-center" style="padding: 40px 0;">
                                <i class="fa fa-check-circle fa-3x" style="color: #00a65a;"></i>
                                <h4 style="margin-top: 15px; color: #00a65a;">No Pending EPS Payments</h4>
                                <p class="text-muted">All EPS payment orders have been verified.</p>
                            </div>
                        @else
                            <table id="pendingEpsTable" class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Unique ID</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Amount (৳)</th>
                                    <th>Order Status</th>
                                    <th>Payment Status</th>
                                    <th>Transaction ID</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->unique_order_id }}</td>
                                        <td>
                                            @if($order->user && $order->user->profile)
                                                {{ $order->user->profile->first_name }} {{ $order->user->profile->last_name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->user->phone ?? 'N/A' }}</td>
                                        <td><strong>৳ {{ number_format($order->erp_total, 2) }}</strong></td>
                                        <td>
                                            @if($order->status == 'pending_payment')
                                                <span class="label label-danger">Pending Payment</span>
                                            @elseif($order->status == 'pending')
                                                <span class="label label-warning">Pending</span>
                                            @else
                                                <span class="label label-default">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($order->payment_status ?? '') === 'paid')
                                                <span class="label label-success">Paid</span>
                                            @else
                                                <span class="label label-danger">Unpaid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->transaction_id)
                                                <code style="font-size: 11px;">{{ $order->transaction_id }}</code>
                                            @else
                                                <span class="text-muted">No TXN ID</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ date('d M Y', strtotime($order->created_at)) }}<br>
                                            <small class="text-muted">{{ date('h:i a', strtotime($order->created_at)) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('order.verifyEps', $order->id) }}"
                                               class="btn btn-xs btn-info"
                                               title="Verify with EPS Gateway">
                                                <i class="fa fa-refresh"></i> Verify
                                            </a>
                                            <a href="{{ route('order.detail', ['id' => $order->id]) }}"
                                               class="btn btn-xs btn-default"
                                               title="View Order Details">
                                                <i class="fa fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('extra-script')
    <script>
        $(function () {
            $('#pendingEpsTable').DataTable({
                responsive: true,
                "order": [[0, "desc"]],
                draw: false,
                stateSave: true
            });
        });
    </script>
@endsection
