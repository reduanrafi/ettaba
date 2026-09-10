@extends('admin.layouts.layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add product images</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('product.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i
                                        class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <form class="form-horizontal" action="{{ route('product-image.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="box-body">
                            <div class="row ">
                                <div class="col-md-8 col-md-offset-2" id="messageDiv">
                                    @if(Session::has('success'))
                                        @include('admin.layouts.message.success')
                                    @elseif(Session::has('error'))
                                        @include('admin.layouts.message.error')
                                    @endif
                                </div>

                                <div class="col-md-offset-2 col-md-8 border-info">
                                    <div class="box-body">
                                        @if ($errors->any())
                                            <div class="col-md-6 col-md-offset-2">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <span>{{ $error }}</span>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if(Session::has('message'))
                                            <div class="col-md-6 col-md-offset-2">
                                                <span> {{ Session::get('message') }}</span>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="productNameBn">Add images</label>
                                            <input type="file" class="form-control" id="product-image"
                                                   name="image" placeholder="Product image">
                                        </div>
                                        <input name="product_id" value="{{ $_GET['product'] }}" type="hidden">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Save">
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <!-- /.box-body -->
                                <div class=" ">
                                    <table id="productDataTable"
                                           class="table table-bordered table-hover table-responsive no-padding">

                                        <tbody>
                                        @if($images)
                                            @foreach($images as $image)
                                                <tr>

                                                    <td>

                                                        <div class="col-md-4">
                                                            <img src="{{ asset($image->image) }}" width="250"
                                                                 height="250"
                                                                 class="img-responsive">

                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="{{ route('image.hardDelete',['product'=>$image->id]) }}"
                                                               class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                    </td>


                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection