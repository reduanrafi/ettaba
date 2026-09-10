@extends('admin.layouts.layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Slider Create Form</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('slider.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i
                                        class="fa fa-mail-forward"></i> View All</a>
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

                    <form class="form-horizontal" action="{{ route('slider.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
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
                                        <label for="subject-name" class="col-sm-3 control-label">Slider title English</label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   min="0"
                                                   class="form-control"
                                                   id="product-name"
                                                   name="title_en"
                                                   placeholder="Slider title">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Slider title Banlga</label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   min="0"
                                                   class="form-control"
                                                   id="product-name"
                                                   name="title_bn"
                                                   placeholder="Slider title">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Short description English</label>
                                        <div class="col-sm-9">
                                            <input type="text" min="0" class="form-control" id="product-name"
                                                   name="description_en"
                                                   placeholder="Slider short description">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Short description Bangla</label>
                                        <div class="col-sm-9">
                                            <input type="text" min="0" class="form-control" id="product-name"
                                                   name="description_bn"
                                                   placeholder="Slider short description">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Slider image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" id="product-image"
                                                   name="image" placeholder="Product image">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Save</label>
                                        <div class="col-sm-9">
                                            <input type="submit" class="form-control btn btn-success">
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
    </section>
@endsection