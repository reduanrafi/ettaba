@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            @if($shop)
                <div class="col-md-12">
                    <div class="box box-widget widget-user">
                        <div class="widget-user-header bg-aqua-active" style="background: url({{ asset($shop->banner_image) }}) center center; background-size: cover;">
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="{{ asset($shop->logo_image) }}" alt="Shop Logo">
                        </div>

                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">Mobile</h5>
                                        <span class="description-text">{{ $shop->mobile }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $shop->name_en }}</h5>
                                        <span class="description-text">{{ $shop->name_bn }}</span>
                                        <div style="margin-top: 6px;">
                                            <span class="label label-success" style="font-size: 12px;">
                                                URL: /{{ $shop->slug }}
                                            </span>
                                        </div>
                                        <br>
                                        <a href="{{ route('shop.edit', ['shop' => $shop->id]) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-edit"></i> Edit Shop
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">Seller ID</h5>
                                        <span class="description-text">{{ $shop->unique_id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Shop Description (English)</h4>
                                    {{ $shop->description_en }}
                                </div>
                                <div class="col-md-6">
                                    <h4>Shop Description (Bangla)</h4>
                                    {{ $shop->description_bn }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Shop Address (English)</h4>
                                    <p>{{ $shop->address_en }}</p>
                                    <h4>Shop Address (Bangla)</h4>
                                    <p>{{ $shop->address_bn }}</p>
                                    <h4>ডেলিভারি এলাকা (Delivery Area)</h4>
                                    <p class="text-success"><strong>{{ $shop->delivery_area_bn ?: ($shop->delivery_area_en ?: 'শুধু ' . ($shop->address_bn ?: $shop->address_en)) }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h4>Custom Shop Link (কাস্টম সেলার লিংক)</h4>
                                    <p><code>{{ config('app.website_url', 'https://ettabashop.com') }}/{{ $shop->slug }}</code></p>
                                    <h4>Youtube Link</h4>
                                    <p>{{ $shop->youtube_link }}</p>
                                    <h4>Facebook Link</h4>
                                    <p>{{ $shop->fb_link }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <h1 class="text-center"><a href="{{ route('shop.create') }}">Create your shop</a></h1>
                </div>
            @endif
        </div>
    </section>
@endsection
