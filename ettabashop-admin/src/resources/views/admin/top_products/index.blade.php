@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top Products</h3>
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
                                <th> # </th>
                                <th> Top Type Name </th>
                                <th> Status </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topTypes as $key=>$topType)

                                <tr>
                                    <td> {{ ++$key }} </td>
                                    <td> {{ $topType->name }} </td>

                                    <td>
                                        <a href="{{ route('topProduct.update',['id'=>$topType->id]) }}" class="btn btn-xs label label-sm label-success ">Update</a>

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