@extends('website.layouts.layout')

@section('content')
    <div class="container-fluid px-xl-5 py-5">
        <div class="section-title position-relative text-center mb-5">
            <h2 class="font-weight-bold">Search Results</h2>
            <p class="text-muted">Showing results for "{{ request('keywords') }}"</p>
        </div>

        @if(count($products) > 0)
            <div class="row px-xl-5">
                @foreach($products as $product)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-4 px-2">
                        @include('website.layouts.home_sections.modern_product_item', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted opacity-2"></i>
                </div>
                <h3 class="font-weight-bold text-muted">No products found</h3>
                <p class="text-muted">Try searching with different keywords or browse our categories.</p>
                <a href="{{ route('website.shop') }}" class="btn btn-primary rounded-pill px-5 py-3 mt-3 shadow-lg">
                    Browse All Products
                </a>
            </div>
        @endif
    </div>
@endsection