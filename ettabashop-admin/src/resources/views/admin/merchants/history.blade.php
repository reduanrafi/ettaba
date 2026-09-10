@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Merchants Cashback History</h3>

                    </div>
                    @if(Session::has('message'))
                        <div class="col-md-6 col-md-offset-2" style="color: red" id="successMessage">
                            <span> {{ Session::get('message') }}</span>
                        </div>
                    @endif
                    <!-- /.box-header -->
                    <!-- form start -->

                        <!-- /.box-body -->
                        <div class="box-body table table-bordered table-hover table-responsive no-padding ">
                            <table id="productDataTable" class="table table-bordered table-hover table-responsive no-padding">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Code</th>
                                    <th>Username</th>
                                    <th>Total</th>
                                    <th>Created date</th>
                                    <th>Action</th>
                                </tr>

                                </thead>
                                <tbody>
                               @if(count($orders)>0)
                                   @foreach($orders as $order)
                                       <tr>
                                           <td>{{ $order->id }}</td>
                                           <td>{{ $order->unique_order_id }}</td>
                                           <td>{{ $order->user->name }}</td>
                                           <td>{{ $order->net_total }} Tk</td>

                                           <td>{{ date('Y-m-d h:i A',strtotime($order->created_at)) }}</td>
                                           <td>
                                               <a class="btn btn-sm btn-success" href="#">View detail</a>
                                           </td>
                                       </tr>
                                   @endforeach
                               @endif
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
@section('extra-script')
    <script>

        $(function () {
            $('#productDataTable').DataTable(
                {
                    responsive: true,
                    "order": [[0, "desc"]],
                    draw:false,
                    stateSave: true
                }
            )
        });
    </script>
@endsection
