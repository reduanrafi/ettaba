@extends('admin.layouts.layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit post</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('post.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <div class="col-md-8 col-md-offset-2" id="messageDiv">
                        @if(Session::has('success'))
                            @include('admin.layouts.message.success')
                        @elseif(Session::has('error'))
                            @include('admin.layouts.message.error')
                        @endif
                    </div>

                    <form class="form-horizontal" action="{{ route('post.update',['id'=>$post->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">

 
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Product Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ $post->title }}" min="0" class="form-control" id="post-name" name="title" placeholder="Title">
                                            @error('title')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Post title</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="category_id">
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Product Description</label>
                                        <div class="col-sm-9">
                                            <textarea name="description" class="form-control" rows="10" id="editor"  >{{ $post->description }}</textarea>

                                            @error('description')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Product image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" onchange="loadFile(event)"
                                                   id="image" name="image" placeholder="Product image">
                                            <p><img id="output" width="200"/></p>
                                        </div>
                                    </div>
                                </div>
                                    <input type="hidden" name="id" value="{{$post->id}}">
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
        <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace( 'editor' );
        </script>
    </section>
@endsection