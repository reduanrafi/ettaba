@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manage customers</h3>
                        {{--<div class="pull-right box-tools">--}}
                        {{--<a href="{{ route('exam.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @if(Session::has('success'))
                        <div class="col-md-6 col-md-offset-2" id="successMessage">
                            <span class="label label-success"> {{ Session::get('success') }}</span>
                        </div>
                    @elseif(Session::has('error'))
                        <div class="col-md-6 col-md-offset-2" id="successMessage">
                            <span class="label label-warning"> {{ Session::get('error') }}</span>
                        </div>
                    @endif
                    <div class="box-body">
                        <div class="col-md-6">
                            <select class="form-control" id="state">
                                <option value="all">All</option>
                                <option value="new">New</option>
                                <option value="inactive">Inactive</option>
                                <option value="active">Active</option>
                                <option value="blocked">Blocked</option>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('customer.index',['state'=>'all']) }}" class="btn btn-sm btn-info">All
                                customers</a>
                            <a href="{{ route('customer.index',['state'=>'new']) }}" class="btn btn-sm btn-success">Latest
                                customers</a>
                            <a href="{{ route('customer.index',['state'=>'active']) }}" class="btn btn-sm btn-success">Active
                                customers</a>
                            <a href="{{ route('customer.index',['state'=>'inactive']) }}"
                               class="btn btn-sm btn-success">Inactive customers</a>

                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <table id="customerDataTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th class="text-center">Status</th>

                                <th>Type</th>
                                <th class="text-center">Referred By (eID)</th>
                                <th class="text-center">Earned point</th>

                                @if(\Illuminate\Support\Facades\Auth::user()->type=='admin')
                                    <th>Ref. Limit (Partner/Customer) </th>
                                @endif
                                <th class="text-center">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td class="text-center">
                                        @if($customer->is_active==1)
                                            <span class="label label-sm label-primary">Active</span>
                                        @elseif($customer->is_new==1)
                                            <span class="label label-sm label-primary">New</span>
                                        @elseif($customer->is_blocked==1)
                                            <span class="label label-sm label-primary">Blocked</span>
                                        @endif
                                    </td>

                                    <td> {{ ucfirst($customer->type) }}</td>
                                    <td class="text-center">
                                        @php
                                            $referrer = $customer->parent_id ? \App\Models\User::find($customer->parent_id) : null;
                                        @endphp
                                        {{ $referrer ? $referrer->unique_id : 'N/A' }}
                                    </td>

                                    <td class="text-center">

                                        <span class="label label-sm label-warning mx-4">
                                            {{ optional($customer->point)->amount ?? 0 }}
                                        </span>

                                    </td>
                                    {{--<td>{{ date('D m Y',strtotime($customer->created_at)) }}</td>--}}
                                    @if(\Illuminate\Support\Facades\Auth::user()->type=='admin')
                                        <td>
                                            @if($customer->customer_type == 'buy_only')
                                                <div style="display:flex; flex-direction:column; gap:5px; opacity: 0.7;">
                                                    <div>
                                                        <label style="font-size:10px;margin:0;color:#999;">Partner:</label>
                                                        <input style="width:50px; font-size:12px; background-color:#e9ecef; cursor:not-allowed;" type="number" value="{{ $customer->partner_limit }}" disabled>
                                                    </div>
                                                    <div>
                                                        <label style="font-size:10px;margin:0;color:#999;">Customer:</label>
                                                        <input style="width:50px; font-size:12px; background-color:#e9ecef; cursor:not-allowed;" type="number" value="{{ $customer->customer_limit }}" disabled>
                                                    </div>
                                                    <div>
                                                        <label style="font-size:10px;margin:0;color:#999;">Merchant:</label>
                                                        <input style="width:50px; font-size:12px; background-color:#e9ecef; cursor:not-allowed;" type="number" value="{{ $customer->merchant_limit }}" disabled>
                                                    </div>
                                                    <span class="label label-sm label-danger text-center" style="display:inline-block; padding: 4px; font-weight: bold; background-color: #d9534f;">Blocked</span>
                                                </div>
                                            @elseif($customer->customer_type == 'buy_earn' && $customer->account_number == 'subsequent')
                                                <form action="{{ route('customer.referralLimit') }}" method="post">
                                                    @csrf
                                                    <div style="display:flex; flex-direction:column; gap:5px;">
                                                        <div>
                                                            <label style="font-size:10px;margin:0;">Partner:</label>
                                                            <input style="width:50px; font-size:12px;" type="number" min="0" value="{{ $customer->partner_limit }}" name="partner_limit">
                                                        </div>
                                                        <div>
                                                            <label style="font-size:10px;margin:0;color:#999;">Customer:</label>
                                                            <input style="width:50px; font-size:12px; background-color:#e9ecef; cursor:not-allowed;" type="number" value="{{ $customer->customer_limit }}" disabled>
                                                        </div>
                                                        <div>
                                                            <label style="font-size:10px;margin:0;color:#999;">Merchant:</label>
                                                            <input style="width:50px; font-size:12px; background-color:#e9ecef; cursor:not-allowed;" type="number" value="{{ $customer->merchant_limit }}" disabled>
                                                        </div>
                                                        <input type="hidden" name="id" value="{{ $customer->id }}">
                                                        <input type="submit" value="Save" class="btn btn-xs btn-primary">
                                                    </div>
                                                </form>
                                            @else
                                                <form action="{{ route('customer.referralLimit') }}" method="post">
                                                    @csrf
                                                    <div style="display:flex; flex-direction:column; gap:5px;">
                                                        <div>
                                                            <label style="font-size:10px;margin:0;">Partner:</label>
                                                            <input style="width:50px; font-size:12px;" type="number" min="0" value="{{ $customer->partner_limit }}" name="partner_limit">
                                                        </div>
                                                        <div>
                                                            <label style="font-size:10px;margin:0;">Customer:</label>
                                                            <input style="width:50px; font-size:12px;" type="number" min="0" value="{{ $customer->customer_limit }}" name="customer_limit">
                                                        </div>
                                                        <div>
                                                            <label style="font-size:10px;margin:0;">Merchant:</label>
                                                            <input style="width:50px; font-size:12px;" type="number" min="0" value="{{ $customer->merchant_limit }}" name="merchant_limit">
                                                        </div>
                                                        <input type="hidden" name="id" value="{{ $customer->id }}">
                                                        <input type="submit" value="Save" class="btn btn-xs btn-primary">
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <a href="{{ route('customer.show',['customer'=>$customer->id]) }}"
                                           class="btn btn-primary btn-xs">Detail</a>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

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
            window.location = '{{route('customer.index')}}' + '?state=' + state;
            {{--var state = $(this).val();--}}

            {{--// parameter 1 : url--}}
            {{--// parameter 2: post data--}}
            {{--//parameter 3: callback function--}}
            {{--$.get( '{{route('order.status')}}' , { state : state } , function(htmlCode){ //htmlCode is the code retured from your controller--}}
            {{--    $("#table tbody").html(htmlCode);--}}
            {{--});--}}
        });

        $(function () {
            $('#customerDataTable').DataTable(
                {
                    responsive: true,
                    "order": [[0, "desc"]],
                    draw: false,
                    stateSave: true
                }
            )
        });
    </script>
@endsection
