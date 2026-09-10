@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Orders</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('slider.create') }}"
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i>
                                create</a>
                        </div>
                    </div>
                    @if(Session::has('message'))
                        <div class="col-md-6 col-md-offset-2" style="color: red" id="successMessage">
                            <span> {{ Session::get('message') }}</span>
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="col-md-6 col-md-offset-2" style="color: green" id="successMessage">
                            <span> {{ Session::get('success') }}</span>
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="col-md-6 col-md-offset-2" style="color: red" id="errorMessage">
                            <span> {{ Session::get('error') }}</span>
                        </div>
                    @endif
                <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-6">
                            <select class="form-control" id="state">
                                <option>Select one</option>
                                <option value="all" {{ request('state') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="pending" {{ request('state') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ request('state') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="canceled" {{ request('state') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="on_delivery" {{ request('state') == 'on_delivery' ? 'selected' : '' }}>On Way</option>
                                <option value="delivered" {{ request('state') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ request('state') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending_payment" {{ request('state') == 'pending_payment' ? 'selected' : '' }}>Pending Payment (EPS)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('order.index',['state'=>'new']) }}" class="btn btn-sm btn-success">New Orders</a>
                            <a href="{{ route('order.index',['state'=>'']) }}" class="btn btn-sm btn-success">Orders today</a>
                            <a href="{{ route('order.index',['state'=>'all']) }}" class="btn btn-sm btn-success">All Orders</a>
                            <a href="{{ route('order.index',['state'=>'completed']) }}" class="btn btn-sm btn-info">Completed Orders</a>
                            <a href="{{ route('order.index',['state'=>'pending_payment']) }}" class="btn btn-sm btn-danger">Pending Payment</a>
                            <a href="{{ route('order.pendingEps') }}" class="btn btn-sm btn-warning"><i class="fa fa-exclamation-triangle"></i> Pending EPS</a>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <table id="orderTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>

                                <th>Order Id</th>
                                <th>unique Order Id</th>
                                <th>Status</th>
                                <th>Payment</th>

                                <th class="text-center">Change status</th>
                                <th class="text-center">Final Stage</th>
                                <th>Created date</th>
                                <th>Created time</th>
                                <th class="text-center">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($orders)
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>
                                            {{ $order->unique_order_id }}
                                        </td>
                                        <td class="text-center">
                                            @if($order->status=='delivered')
                                                <span class=" label label-sm label-success"><i class="fa fa-check"></i></span>
                                            @elseif($order->status=='canceled')
                                                <span class="label label-sm label-danger"><i
                                                            class="fa fa-times"></i></span>
                                            @elseif($order->status=='pending')
                                                <span class="label label-sm label-warning">{{ ucfirst($order->status) }}</span>
                                            @elseif($order->status=='completed')
                                                <span class="label label-sm label-success">{{ ucfirst($order->status) }}</span>
                                            @elseif($order->status=='pending_payment')
                                                <span class="label label-sm label-danger">Pending Payment</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($order->paymentMethod->short_code ?? '') === 'EPS')
                                                @if(($order->payment_status ?? '') === 'paid')
                                                    <span class="label label-success">Gateway Paid</span>
                                                @else
                                                    <span class="label label-warning">Pending (EPS)</span>
                                                @endif
                                            @else
                                                <span class="label label-info">{{ $order->paymentMethod->short_code ?? 'COD' }}</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($order->status=='pending')
                                                <a href="{{ route('order.changeStatus',['id'=>$order->id,'status'=>'accepted']) }}"
                                                   class="btn btn-xs label label-sm label-default ">Accept</a>
                                                <a href="{{ route('order.changeStatus',['id'=>$order->id,'status'=>'canceled']) }}"

                                                   class="btn btn-xs label label-sm label-danger ">Cancel</a>
                                            @elseif($order->status=='accepted')
                                                <a href="{{ route('order.changeStatus',['id'=>$order->id,'status'=>'on_delivery']) }}"
                                                   class="btn btn-xs label label-sm label-warning ">On Delivery</a>
                                                <a href="{{ route('order.changeStatus',['id'=>$order->id,'status'=>'canceled']) }}"

                                                   class="btn btn-xs label label-sm label-danger ">Cancel</a>
                                            @elseif($order->status=='on_delivery')
                                                <a href="{{ route('order.changeStatus',['id'=>$order->id,'status'=>'delivered']) }}"
                                                   class="btn btn-xs label label-sm label-success ">Delivered</a>
                                                <a href="{{ route('order.changeStatus',['id'=>$order->id,'status'=>'canceled']) }}"

                                                   class="btn btn-xs label label-sm label-danger ">Cancel</a>
                                            @elseif($order->status=='canceled')
                                                <span class="text-warning">Order is canceled</span>
                                            @elseif($order->status=='delivered')
                                                <span class="text-success">Order delivered</span>
                                            @elseif($order->status=='completed')
                                                <span class="text-success">Order completed</span>
                                            @elseif($order->status=='pending_payment')
                                                <a href="{{ route('order.verifyEps', $order->id) }}" class="btn btn-xs label label-sm label-info">
                                                    <i class="fa fa-refresh"></i> Verify EPS
                                                </a>
                                            @endif


                                        </td>

                                        <td class="text-center">
                                            @if($order->status=='delivered')
                                                <a href="{{ route('order.done',['id'=>$order->id]) }}"

                                                   class="btn btn-xs label label-sm label-default ">Done</a>

                                            @elseif($order->status=='completed')
                                                <span style="font-size: 15px; font-style: italic">Order is completed</span>

                                            @elseif($order->status=='canceled')
                                                <span style="font-size: 15px; font-style: italic;color: darkred;">Order is canceled</span>

                                            @else  <a href="#" class="btn btn-xs label label-sm label-default ">Done</a>
                                            <br>
                                            <span style="font-size: 10px; font-style: italic">(button will work if the order is delivered)</span>
                                            @endif
                                        </td>

                                        <td>{{ date('d M Y',strtotime($order->created_at)) }}</td>
                                        <td>{{ date('h:i a',strtotime($order->created_at)) }}</td>
                                        <td class="text-center">
                                            <a href="{{  route('order.detail',['id'=>$order->id]) }}"
                                               class="btn btn-xs btn-default"><i class="fa fa-eye"></i> Detail</a>
                                            @if($order->status!='pending' && $order->status!='canceled')
                                                <a href="{{  route('order.invoice',['id'=>$order->id]) }}"
                                                   class="btn btn-xs btn-primary"><i class="fa fa-check"></i>Invoice</a>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                            @endif
                            </tbody>

                        </table>
                        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="text-center" style="margin-top: 15px;">
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection
@section('extra-script')
    <script>
        $("#state").change(function () {
            //this is the #state dom element
            var state = $(this).val()
            // window.location = 'http://localhost:81/ed/public/private-panel/orders/index?state='+state;
            window.location = '{{route('order.index')}}' + '?state=' + state;
            {{--var state = $(this).val();--}}

            {{--// parameter 1 : url--}}
            {{--// parameter 2: post data--}}
            {{--//parameter 3: callback function--}}
            {{--$.get( '{{route('order.status')}}' , { state : state } , function(htmlCode){ //htmlCode is the code retured from your controller--}}
            {{--    $("#table tbody").html(htmlCode);--}}
            {{--});--}}
        });
        $(function () {
            @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                $('#orderTable').DataTable(
                    {
                        responsive: true,
                        "order": [[0, "desc"]],
                        paging: false,
                        info: false,
                        searching: true
                    }
                );
            @else
                $('#orderTable').DataTable(
                    {
                        responsive: true,
                        "order": [[0, "desc"]],
                        draw:false,
                        stateSave: true
                    }
                );
            @endif
        });
        // $(document).ready(function() {
        //     $('#orderTable').dataTable( {
        //
        //     } );
        // } );
    </script>
@endsection
