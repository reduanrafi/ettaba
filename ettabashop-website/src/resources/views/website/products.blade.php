@extends('website.layouts.layout')

@section('content')
    <div class="container-fluid px-xl-5 mt-4">
        <div class="row">
            <!-- Desktop Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                @include('website.layouts.includes.sidebar')
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="section-title position-relative text-center mb-5">
                    <h2 class="font-weight-bold">Products</h2>
                </div>

                @if(count($products) > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-4 px-2">
                                @include('website.layouts.home_sections.modern_product_item', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4 text-muted opacity-2">
                            <i class="fas fa-shopping-basket fa-4x"></i>
                        </div>
                        <h3 class="font-weight-bold text-muted">No products found in this category</h3>
                        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-5 py-3 mt-3 shadow-lg">
                            Back to Home
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

