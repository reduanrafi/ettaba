@extends('admin.layouts.layout')
@section('content')

    <!-- BEGIN Portlet PORTLET-->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create new Category</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('category.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->


                    <form class="form-horizontal" action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-12 col-md-offset-2" id="messageDiv">
                                    @if(Session::has('success'))
                                        @include('admin.layouts.message.success')
                                    @elseif(Session::has('error'))
                                        @include('admin.layouts.message.error')
                                    @endif
                                </div>


                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Category Name English</label>
                                        <div class="col-sm-9">
                                            <input type="text"   class="form-control" id="name_en" name="name_en" placeholder="Name">
                                            @error('name_en')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Category Name Bangla</label>
                                        <div class="col-sm-9">
                                            <input type="text"   class="form-control" id="name_bn" name="name_bn" placeholder="Name">
                                            @error('name_bn')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Slug</label>
                                        <div class="col-sm-9">
                                            <input type="text"   class="form-control" id="slug" name="slug" placeholder="this-is-slug-format">
                                            @error('slug')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Parent Categories</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="parent_id">
                                                <option></option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name_en}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" value="1" name="category_id">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="advance_payment_required" class="col-sm-3 control-label">Advance Payment Required</label>
                                        <div class="col-sm-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="advance_payment_required" name="advance_payment_required" value="1">
                                                    ON (COD disabled)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Featured image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" onchange="loadFile(event)"
                                                   id="image" name="image" placeholder="Post image">
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

