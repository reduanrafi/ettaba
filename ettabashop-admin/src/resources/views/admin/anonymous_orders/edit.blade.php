@extends('admin.layouts.layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Slider update</h3>
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
                    @if(Session::has('message'))
                        <div class="col-md-6 col-md-offset-2">
                            <span> {{ Session::get('message') }}</span>
                        </div>
                    @endif
                    <form class="form-horizontal" action="{{ route('slider.update',['slider'=>$slider->id]) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        {!! method_field('PUT') !!}
                        <div class="box-body">
                            <div class="row">
                                @if ($errors->any())
                                    <div class="col-md-6 col-md-offset-2">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <span>{{ $error }}</span>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                    <div class="col-md-8">

                                        <div class="form-group">
                                            <label for="subject-name" class="col-sm-3 control-label">Slider title</label>
                                            <div class="col-sm-9">
                                                <input type="text" value="{{ $slider->title }}" class="form-control" id="product-name"
                                                       name="title" placeholder="Slider title">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">

                                        <div class="form-group">
                                            <label for="subject-name" class="col-sm-3 control-label">Short description</label>
                                            <div class="col-sm-9">
                                                <input type="text" value="{{ $slider->description }}" class="form-control" id="product-name"
                                                       name="description" placeholder="Slider short description">

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

                                        <div class="text-center">
                                            <img class="img-fluid" src="{{ asset($slider->image) }}" width="350" height="400">
                                        </div>
                                    </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Update</label>
                                        <div class="col-sm-9">
                                            <input type="submit" class="form-control btn btn-success" >
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