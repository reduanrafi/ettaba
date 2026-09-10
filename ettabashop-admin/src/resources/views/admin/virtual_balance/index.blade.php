@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Virtual balances</h3>
                        {{--<div class="pull-right box-tools">--}}
                        {{--<a href="{{ route('exam.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <div class="box-body">
                        <div class="" id="messageDiv">
                            @if(Session::has('success'))
                                @include('admin.layouts.message.success')
                            @elseif(Session::has('error'))
                                @include('admin.layouts.message.error')
                            @endif
                        </div>
                        <table id="storeDataTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Transaction ID</th>
                                <th> Amount </th>
                                <th> Type </th>
                                <th>Date time</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($virtualBalances as $virtualBalance)
                                <tr>
                                    <td>{{ isset($virtualBalance->user->name)? $virtualBalance->user->name:"" }}</td>
                                    <td>{{ $virtualBalance->transaction_code }}</td>
                                    <td>{{ $virtualBalance->amount }}</td>
                                    <td>{{ $virtualBalance->status }}</td>
                                    <td>
                                        {{ date('Y/M ( D )',strtotime($virtualBalance->created_at)) }}
                                        {{ date('h:i a',strtotime($virtualBalance->created_at)) }}
                                    </td>
                                    <td class="text-center">
                                        @if($virtualBalance->is_accepted==0 & $virtualBalance->is_rejected==0 & $virtualBalance->is_completed==0)
                                        <a href="{{ route('vb.status',['id'=>$virtualBalance->id,'status'=>'accepted']) }}"
                                           class="btn btn-xs label label-sm label-success ">Accept</a>
                                        <a href="{{ route('vb.status',['id'=>$virtualBalance->id,'status'=>'reject']) }}"
                                           class="btn btn-xs label label-sm label-success ">Reject</a>
                                        <a href="{{ route('vb.status',['id'=>$virtualBalance->id,'status'=>'accepted']) }}"
                                           class="btn btn-xs label label-sm label-success ">Completed</a>
                                        @else
                                            <span class="text-green">
                                            {{($virtualBalance->is_accepted?'Accepted':($virtualBalance->is_rejected?"Rejected":"Completed"))}}
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manage Merchant Gateway Charges</h3>
                    </div>
                    <form role="form" method="POST" action="{{ route('admin.gateway-charges.update') }}">
                        @csrf
                        <div class="box-body">
                            @php
                                $gateways = \App\Models\MerchantGateway::all();
                            @endphp
                            @foreach($gateways as $gateway)
                                <div class="form-group">
                                    <label for="charge_{{ $gateway->code }}">{{ $gateway->name }} Charge (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" class="form-control" 
                                               id="charge_{{ $gateway->code }}" 
                                               name="charges[{{ $gateway->code }}]" 
                                               value="{{ $gateway->charge_percent }}" required>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Update Charges</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>

@endsection
@section('extra-script')
    <script>


        $(function () {
            $('#storeDataTable').DataTable(
                {
                    responsive: true,
                    "order": [[0, "desc"]]
                }
            )
        });
    </script>
@endsection
