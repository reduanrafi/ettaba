@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Products</h3>

                        <div class="pull-right box-tools">
                        <a href="{{ route('product.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>
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
                                    <th>Code</th>
                                    <th>Product name</th>
                                    <th>Thumb</th>
                                    <th>Created date</th>
                                    <th>Featured</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>

                                </thead>
                                <tbody>
                               @if($products)
                                   @foreach($products as $product)
                                       <tr>
                                           <td>{{ $product->id }}</td>
                                           <td>{{ $product->unique_id }}</td>
                                           <td>{{ $product->name_en }}</td>
                                           <td>
                                               @if(isset($product->featured_image))
                                                   <img src="{{ asset($product->featured_image) }}" width="50" height="50">
                                               @endif
                                           </td>
                                           <td>{{ date('y-m-d',strtotime($product->created_at)) }}</td>
                                           <td class="text-center">

                                               @if($product->is_featured==1)
                                                   <a href="{{ route('product.toggleFeature',['id'=>$product->id]) }}"
                                                      class="btn btn-xs btn-success"><i class="fa fa-toggle-on"></i></a>
                                                   <br>
                                                   <span  style="color: #0d3349; font-size: 15px;font-style: italic">Featured</span>
                                               @else
                                                   <a href="{{ route('product.toggleFeature',['id'=>$product->id]) }}"
                                                      class="btn btn-xs btn-danger"><i class="fa fa-toggle-off"></i></a>
                                                   <br>
                                                   <span style="color: #0d3349; font-size: 15px;font-style: italic">Not Featured</span>
                                               @endif
                                           </td>
                                           <td>
                                               <a href="{{ route('product-image.index',['product'=>$product->id]) }}" class="btn btn-xs btn-warning"><i class="fa fa-image"></i> Add image</a>

                                           </td>
                                           {{--<td ><a href="{{ route('subject.config',['subject_id'=>$product->id]) }}" class=" btn btn-xs btn-success">Config</a></td>--}}
                                           <td>
                                               <a href="{{ route('product.edit',['product'=>$product->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                               <a href="{{ route('product.hardDelete',['product'=>$product->id]) }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                               <a href="{{ route('product.revert',['product'=>$product->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i> Un Hide</a>

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
                    "order": [[0, "desc"]]
                }
            )
        });
    </script>
@endsection