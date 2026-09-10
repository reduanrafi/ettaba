@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            @if($shop)
                <div class="col-md-12">
                    <div class="box box-widget widget-user" >
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-aqua-active" style="background: url({{ asset($shop->banner_image) }}) center center;">

                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="{{ asset($shop->logo_image) }}" alt="User Avatar">
                        </div>

                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">Mobile</h5>
                                        <span class="description-text">{{ $shop->mobile }}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $shop->name_en }}</h5>
                                        <span class="description-text">{{ $shop->name_bn }}</span>
                                        <br>
                                        <a href="{{ route('shop.edit',['shop'=>$shop->id]) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-edit"></i>
                                            Edit shop
                                        </a>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">Unique ID</h5>
                                        <span class="description-text">{{ $shop->unique_id }}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 > Shop description english</h4>
                                    {{$shop->description_en}}
                                </div>
                                <div class="col-md-6">
                                    <h4 > Shop description english</h4>
                                    {{$shop->description_bn}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 > Shop address english</h4>
                                    {{$shop->address_en}}
                                    <h4 > Shop address Bangla</h4>
                                    {{$shop->address_bn}}
                                </div>
                                <div class="col-md-6">
                                    <h4> Youtube link</h4>
                                    {{$shop->youtube_link}}
                                    <h4> Facebook link</h4>
                                    {{$shop->youtube_link}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">

                    <h1 class="text-center"><a href="{{ route('shop.create') }}">Create your shop </a></h1>
                </div>
            @endif
        </div>
        <!-- /.row -->
    </section>
@endsection