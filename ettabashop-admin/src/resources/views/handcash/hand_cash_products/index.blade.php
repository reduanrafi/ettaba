@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Products</h3>

                        <div class="pull-right box-tools">
                        <a href="{{ route('handcash-product.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>
                        </div>

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
                                    <th>Name</th>
                                    <th>Code</th>

                                    <th>Created date</th>
                                    <th>Action</th>
                                </tr>

                                </thead>
                                <tbody>
                               @if($products)
                                   @foreach($products as $product)
                                       <tr>
                                           <td>{{ $product->id }}</td>

                                           <td>{{ $product->name }}</td>
                                           <td>{{ $product->short_name }}</td>

                                           <td>{{ date('y-m-d',strtotime($product->created_at)) }}</td>

                                          <td>
                                               <a href="{{ route('handcash-product.edit',['handcash_product'=>$product->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>

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
