@extends('admin.layouts.layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create New Page</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('page.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
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
                    <form class="form-horizontal" action="{{ route('page.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
{{--                                @if ($message->any())--}}
{{--                                    <div class="col-md-6 col-md-offset-2">--}}
{{--                                        <ul>--}}
{{--                                            @foreach ($message->all() as $error)--}}
{{--                                                <span>{{ $error }}</span>--}}
{{--                                            @endforeach--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                                @if(Session::has('message'))
                                    <div class="col-md-6 col-md-offset-2">
                                        <span> {{ Session::get('message') }}</span>
                                    </div>
                                @endif


                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Page title</label>
                                        <div class="col-sm-9">
                                            <input type="text"  min="0" class="form-control" id="page-name" name="title" placeholder="Page Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Page sub title</label>
                                        <div class="col-sm-9">
                                            <input type="text"  min="0" class="form-control" id="page-name" name="sub_title" placeholder="Page Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Page Description</label>
                                        <div class="col-sm-9">
                                            <textarea name="description" class="form-control" rows="20"  ></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                <div class="form-group">
                                    <label for="subject-name" class="col-sm-3 control-label">Page image</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="page-image" name="page_image" placeholder="Page image">
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
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({selector:'textarea'});</script>
        <!-- /.row -->
    </section>
@endsection