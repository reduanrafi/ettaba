@extends('website.layouts.layout')
@section('facebook')
    {{--
    <meta property="og:title" content="{{ $product->name_en}}" /> --}}
    {{--
    <meta property="og:image" content="{{'http://mahedipublications.com/'.$product->featured_image }}" /> --}}
    {{--
    <meta property="og:description" content="{{ $product->description_en }}" /> --}}
@endsection
@section('content')
    <div class="shop-hero-section py-5 bg-white border-bottom">
        <div class="container-fluid px-xl-5">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-4 text-center mb-4 mb-md-0">
                    <img src="{{ env('IMAGE_URL') . $shop->logo_image }}"
                        class="img-fluid rounded-circle shadow-sm border p-2" style="max-height: 120px;">
                </div>
                <div class="col-lg-10 col-md-8">
                    <h6 class="text-primary font-weight-bold text-uppercase mb-1">Official Merchant</h6>
                    <h1 class="font-weight-bold mb-2">{{ $local == 'bn' ? $shop->name_bn : $shop->name_en }}</h1>
                    <div class="d-flex flex-wrap gap-4 text-muted small">
                        <span class="mr-4"><i class="fas fa-phone-alt mr-2"></i> {{ $shop->mobile }}</span>
                        <span><i class="fas fa-map-marker-alt mr-2"></i> {{ $shop->address_en }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">


        <div class="row px-xl-5 pb-3">
            @foreach($products as $k => $product)
                <div class="col-lg-3 col-md-6 col-6 pb-1">
                    <div class="card product-item border-0 mb-4">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <img class="img-fluid w-100" src="{{env('IMAGE_URL') . $product->featured_image}}" alt="">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3">{{ $local == 'bn' ? $product->name_en : $product->name_en}} </h6>
                            <div class="d-flex justify-content-center">
                                <h6>{{__('titles.price')}} : {{ $local == 'bn' ? $product->erp_en : $product->erp_en}}</h6>

                                <h6 class="text-muted ml-2">
                                    <del>{{ $local == 'bn' ? $product->mrp_en : $product->mrp_en}}</del>
                                </h6>

                                <h6 class="pl-2">{{__('titles.cashBack')}} : {{ $local == 'bn' ? $product->tcb_en : $product->tcb_en}}
                                </h6>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                            <a href="{{ route('product.detail', ['slug' => $product->slug]) }}" class="btn btn-sm text-dark p-0"><i
                                    class="fas fa-eye text-primary mr-1"></i>{{__("buttons.viewDetail")}}</a>
                            @if($local == 'en')
                                <a class="btn btn-sm text-dark p-0" @click="addToCart(
                                               '{{$product->id}}',
                                               '{{$product->name_en}}',
                                               '{{$product->erp_en}}',
                                               '{{$product->trp_en}}',
                                               '{{$product->owner_id}}',
                                               '{{$product->tcb_en}}',
                                               '{{$product->rate_en}}',
                                               )">
                                    <i class="fas fa-shopping-cart text-primary mr-1"></i>
                                    {{__("buttons.cart")}}
                                </a>
                            @else
                                <a class="btn btn-sm text-dark p-0" @click="addToCart(
                                               '{{$product->id}}',
                                               '{{$product->name_en}}',
                                               '{{$product->erp_en}}',
                                               '{{$product->trp_en}}',
                                               '{{$product->owner_id}}',
                                                '{{$product->tcb_en}}',
                                                '{{$product->rate_en}}',
                                               )">
                                    <i class="fas fa-shopping-cart text-primary mr-1"></i>
                                    {{__("buttons.cart")}}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Shop Detail End -->

    <style>
        .bg-image {
            /* The image used */
            background-image: url('http://localhost:81/ettabaShop/{{ $shop->banner_image }}');

            /* Add the blur effect */
            filter: blur(8px);
            -webkit-filter: blur(8px);

            /* Full height */
            height: 100%;

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Position text in the middle of the page/image */
        .bg-text {
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/opacity/see-through */
            color: white;
            font-weight: bold;
            border: 3px solid #f1f1f1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            width: 80%;
            padding: 20px;
            text-align: center;
        }
    </style>
@endsection()