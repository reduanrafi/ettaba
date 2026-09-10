@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Withdraw requests</h3>
                        @if(\Illuminate\Support\Facades\Auth::user()->type=='store_owner')
                            <div class="pull-right box-tools">
                                <a href="{{ route('withdraw.create') }}"
                                   class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i
                                            class="fa fa-plus"></i>Create Withdraw request</a>
                            </div>
                        @Endif
                    </div>
                    <div class="col-md-8 col-md-offset-2" id="messageDiv">
                        @if(Session::has('success'))
                            @include('admin.layouts.message.success')
                        @elseif(Session::has('error'))
                            @include('admin.layouts.message.error')
                        @endif
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <!-- /.box-body -->
                    <div class="box-footer">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User name</th>
                                <th>Phone</th>
                                <th>Bank info</th>
                                <th>Request amount</th>
                                <th>Request time</th>
                                <th>Status</th>
                                {{--                                <th>Note</th>--}}
                                @if(\Illuminate\Support\Facades\Auth::user()->type=="admin")
                                    <th class="text-center">Action</th>
                                @endif

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdrawRequests as $wr)
                                <tr>

                                    <td>{{ $wr->id }}</td>
                                    <td>
                                        <a href="{{ route('customer.show',['customer'=>$wr->user->id]) }}">
                                            @if($wr->user->profile!=null)
                                                {{ $wr->user->profile->first_name }} {{ $wr->user->profile->last_name }}
                                            @else
                                                {{ $wr->user->phone }}
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ $wr->phone }}</td>
                                    <td>{{ $wr->bank_account_number }}</td>
                                    <td>{{ $wr->amount }}</td>
                                    <td class="text-center">{{ date('d M y',strtotime($wr->created_at)) }}</td>

                                    <td><span class="text-success">{{ ucfirst($wr->status) }}</span></td>
                                    {{--                                    <td><span class=" ">{{ $wr->note }}</span></td>--}}
                                    @if(\Illuminate\Support\Facades\Auth::user()->type=="admin")
                                        <td class="text-center">

                                            @if($wr->status=='pending')
                                                <a href="{{ route('withdrawRequest.update',['id'=>$wr->id,'status'=>'accepted']) }}"
                                                   class="btn btn-xs label label-sm label-success ">Accept</a>

                                                <a href="{{ route('withdrawRequest.update',['id'=>$wr->id,'status'=>'canceled']) }}"
                                                   class="btn btn-xs label label-sm label-warning ">Cancel</a>
                                            @elseif($wr->status=='accepted')
                                                <a href="{{ route('withdrawRequest.update',['id'=>$wr->id,'status'=>'canceled']) }}"
                                                   class="btn btn-xs label label-sm label-warning ">Cancel</a>
                                                <a href="{{ route('withdrawRequest.done',['id'=>$wr->id,'user_id'=>$wr->user->id,'amount'=>$wr->amount]) }}"
                                                   class="btn btn-xs label label-sm label-success ">Done</a>
                                            @endif
                                            <a href="{{ route('withdrawRequest.delete',['id'=>$wr->id]) }}"
                                               class="btn btn-xs label label-sm label-danger ">Delete</a>


                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection