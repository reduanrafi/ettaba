@extends('website.layouts.layout')
@section('content')
    <div class="container-fluid bg-light py-4 border-bottom">
        <div class="row px-xl-5">
            <div class="col-12 text-center py-5">
                <h6 class="text-primary font-weight-bold text-uppercase mb-2">{{ $parent->name_en }}</h6>
                <h1 class="display-4 font-weight-bold mb-3">{{ $parent->name_en }} তালিকায় অন্তর্ভুক্ত পণ্য সমূহ</h1>
                <div class="d-inline-flex align-items-center">
                    <a href="{{ route('website.index') }}" class="text-muted text-decoration-none">Home</a>
                    <i class="fas fa-chevron-right mx-2 small text-muted"></i>
                    <span class="text-dark font-weight-bold">{{ $parent->name_en }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-12">


                <!-- Color Start -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">{{ $parent->name_en }}</h5>
                    @foreach ($categories as $category)
                        <div class="d-flex align-items-center justify-content-between ">
                            <a href="{{ route('categoryProducts', ['slug' => $category->slug]) }}" class="nav-link">
                                {{ $category->name_en }}
                            </a>

                        </div>
                    @endforeach

                </div>

                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">{{ __('menu.categories') }}</h5>
                    @foreach ($parents as $category)
                        <div class="d-flex align-items-center justify-content-between ">
                            <a href="{{ route('categoryProducts', ['slug' => $category->slug]) }}" class="nav-link">
                                {{ $category->name_en }}
                            </a>

                        </div>
                    @endforeach

                </div>
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-6 col-6 pb-1">
                            <div class="card product-item border-0 mb-4">
                                <div
                                    class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    <img class="img-fluid w-100" src="{{ env('IMAGE_URL') . $product->featured_image }}" alt="">
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3">
                                        {{ $local == 'bn' ? $product->name_en : $product->name_en }}
                                    </h6>
                                    <div class="d-flex justify-content-center">
                                        <h6>{{ $local == 'bn' ? $product->erp_en : $product->erp_en }}</h6>
                                        <h6 class="text-muted ml-2">
                                            <del>{{ $local == 'bn' ? $product->mrp_en : $product->mrp_en }}</del>
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between bg-light border">
                                    <a href="{{ route('product.detail', ['slug' => $product->slug]) }}"
                                        class="btn btn-sm text-dark p-0"><i
                                            class="fas fa-eye text-primary mr-1"></i>{{ __('buttons.viewDetail') }}</a>
                                    @if ($local == 'en')
                                        <a class="btn btn-sm text-dark p-0" @click="addToCart(
                                               '{{ $product->id }}',
                                               '{{ $product->name_en }}',
                                               '{{ $product->erp_en }}',
                                               '{{ $product->trp_en }}',
                                               '{{ $product->owner_id }}',
                                               '{{ $product->tcb_en }}',
                                               '{{ $product->rate_en }}',
                                               )">
                                            <i class="fas fa-shopping-cart text-primary mr-1"></i>
                                            {{ __('buttons.cart') }}
                                        </a>
                                    @else
                                        <a class="btn btn-sm text-dark p-0" @click="addToCart(
                                               '{{ $product->id }}',
                                               '{{ $product->name_en }}',
                                               '{{ $product->erp_en }}',
                                               '{{ $product->trp_en }}',
                                               '{{ $product->owner_id }}',
                                                '{{ $product->tcb_en }}',
                                                '{{ $product->rate_en }}',
                                               )">
                                            <i class="fas fa-shopping-cart text-primary mr-1"></i>
                                            {{ __('buttons.cart') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-12 pb-1">
                        <div class="col-12 pb-1">
                            <div class="pull-right">
                                {{ $products->withQueryString()->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
@endsection()