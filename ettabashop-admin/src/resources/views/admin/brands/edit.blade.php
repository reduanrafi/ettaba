@extends('admin.layouts.layout')

@section('content')

<!-- BEGIN Portlet PORTLET-->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Create new Brand</h3>
                    <div class="pull-right box-tools">
                        <a href="{{ route('brand.index') }} "
                           class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->


                <form class="form-horizontal" action="{{ route('brand.update',['brand'=>$brand->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {!! method_field('PUT') !!}
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-8 col-md-offset-2" id="messageDiv">
                                @if(Session::has('success'))
                                    @include('admin.layouts.message.success')
                                @elseif(Session::has('error'))
                                    @include('admin.layouts.message.error')
                                @endif
                            </div>


                            <div class="col-md-8">

                                <div class="form-group">
                                    <label for="subject-name" class="col-sm-3 control-label">Brand Name</label>
                                    <div class="col-sm-9">
                                        <input type="text"   class="form-control" id="name" name="name" value="{{$brand->name}}" placeholder="Name">
                                        @error('name_en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">

                                <div class="form-group">
                                    <label for="subject-name" class="col-sm-3 control-label">Slug</label>
                                    <div class="col-sm-9">
                                        <input type="text"   class="form-control" id="slug" name="slug" value="{{$brand->slug}}" placeholder="this-is-slug-format">
                                        @error('slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>



                            <input type="hidden" name="id" value="{{ $brand->id }}">

                            <div class="col-md-8">

                                <div class="form-group">
                                    <label for="subject-name" class="col-sm-3 control-label">Featured image</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" onchange="loadFile(event)"
                                               id="image" name="logo" placeholder="Post image">
                                        <p><img id="output" width="200"/></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">

                                <div class="form-group">
                                    <label for="subject-name" class="col-sm-3 control-label">Action</label>
                                    <div class="col-sm-9">
                                        <input type="submit" value="Save" class="btn btn-success">
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </form>


            </div>
        </div>
    </div>
    <!-- /.row -->
    {{--        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>--}}
    {{--        <script>tinymce.init({ selector:'textarea' });</script>--}}
    <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'editor' );
    </script>
</section>
<!-- END Portlet PORTLET-->

@endsection