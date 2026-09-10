@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Brands</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('brand.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add brand</a>
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
                    <div class="box-footer">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Brand name</th>
                                <th>Thumb</th>
                                <th>Created date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($brands)
                                @foreach($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->name }}</td>
                                        <td>
                                            <img src="{{ asset($brand->logo) }}" width="50" height="50">
                                        </td>
                                        <td>{{ date('y-m-d',strtotime($brand->created_at)) }}</td>

                                        <td>
                                            <a href="{{ route('brand.edit',['brand'=>$brand->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>

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