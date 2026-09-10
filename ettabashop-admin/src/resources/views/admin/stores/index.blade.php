@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manage Shops</h3>
                        {{--<div class="pull-right box-tools">--}}
                        {{--<a href="{{ route('exam.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @if(Session::has('message'))
                        <div class="col-md-6 col-md-offset-2" id="successMessage">
                            <span> {{ Session::get('message') }}</span>
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
                            <a href="{{ route('store.index',['state'=>'all']) }}" class="btn btn-sm btn-info">All
                                Shops</a>
                            <a href="{{ route('store.index',['state'=>'new']) }}" class="btn btn-sm btn-success">Latest
                                Shops</a>
                            <a href="{{ route('store.index',['state'=>'active']) }}" class="btn btn-sm btn-success">Active
                                Shops</a>
                            <a href="{{ route('store.index',['state'=>'inactive']) }}"
                               class="btn btn-sm btn-success">Inactive Shops</a>

                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <table id="storeDataTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Status</th>

                                <th>Type</th>
                                <th class="text-center">View Shop</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td>{{ $store->id }}</td>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->email }}</td>
                                    <td class="text-center">
                                        @if($store->is_active==1)
                                            <span class="label label-sm label-primary">Active</span>
                                        @elseif($store->is_new==1)
                                            <span class="label label-sm label-primary">New</span>
                                        @elseif($store->is_blocked==1)
                                            <span class="label label-sm label-primary">Blocked</span>
                                        @endif
                                    </td>

                                    <td> {{ ucfirst($store->type) }}</td>
                                    <td class="text-center">
                                        @if($store->shop==null)
                                            <span class="label label-sm label-warning">Shop is not yet been created</span>
                                            <br>
                                            <br>
                                        <a   href="#"   class="btn btn-xs label label-sm label-warning">Notify</a>
                                        @endif
                                    </td>
                                    {{--<td>{{ date('y-m-d',strtotime($store->created_at)) }}</td>--}}
                                    <td class="text-center">
                                        @if($store->is_active==1&& $store->is_new==0 && $store->is_blocked==0)
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'inactivate']) }}"
                                               class="btn btn-xs label label-sm label-default ">Mark Inactive</a>
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'block']) }}"
                                               class="btn btn-xs label label-sm label-danger ">Block</a>
                                        @elseif($store->is_active==0&& $store->is_new==0 && $store->is_blocked==0)
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'activate']) }}"
                                               class="btn btn-xs label label-sm label-default ">Activate</a>
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'block']) }}"
                                               class="btn btn-xs label label-sm label-danger ">Block</a>
                                        @elseif($store->is_active==0&& $store->is_new==1 && $store->is_blocked==0)
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'activate']) }}"
                                               class="btn btn-xs label label-sm label-default ">Activate</a>
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'block']) }}"
                                               class="btn btn-xs label label-sm label-danger ">Block</a>
                                        @elseif($store->is_active==0&& $store->is_new==0 && $store->is_blocked==1)
                                            <a href="{{ route('store.changeStatus',['id'=>$store->id,'status'=>'activate']) }}"
                                               class="btn btn-xs label label-sm label-danger ">Activate</a>

                                        @endif


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
    <div class="modal fade" id="shopModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Basic Modal</h4>
                </div>
                <div class="modal-body">

                    <h3>Modal Body</h3>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $("#state").change(function () {
            //this is the #state dom element
            var state = $(this).val()
            // window.location = 'http://localhost:81/ed/public/private-panel/orders/index?state='+state;
            window.location = '{{route('store.index')}}' + '?state=' + state;
            {{--var state = $(this).val();--}}

            {{--// parameter 1 : url--}}
            {{--// parameter 2: post data--}}
            {{--//parameter 3: callback function--}}
            {{--$.get( '{{route('order.status')}}' , { state : state } , function(htmlCode){ //htmlCode is the code retured from your controller--}}
            {{--    $("#table tbody").html(htmlCode);--}}
            {{--});--}}
        });

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