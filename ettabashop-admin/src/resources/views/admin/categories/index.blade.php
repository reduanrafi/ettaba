@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Categories</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('category.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add category</a>
                        </div>
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
                                <th>Category title</th>
                                <th>Thumb</th>
                                <th>Created date</th>
                                <th>Toggle hide</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($categories)
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->name_en }}</td>
                                        <td>
                                            @if(isset($category->image))
                                                <img src="{{ asset($category->image) }}" width="50" height="50">
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('y-m-d',strtotime($category->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('category.toggleHide',['id'=>$category->id,'hide'=>($category->is_deleted==0?1:0)]) }}" class="btn btn-xs btn-warning ">
                                                 {{ $category->is_deleted==0?"Hide":"Unhide" }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('category.edit',['category'=>$category->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
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