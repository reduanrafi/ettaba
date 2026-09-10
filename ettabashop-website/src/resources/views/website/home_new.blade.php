@extends('layouts.frontend')

@section('content')
    <!-- Dashboard Cards Section -->
    @include('partials.dashboard-cards')

    <!-- Main Content Split: Sidebar & Slider -->
    <div class="row mb-4">
        <!-- Sidebar (Hidden on mobile, shown on desktop) -->
        <div class="col-lg-3 d-none d-lg-block">
            @include('partials.sidebar')
        </div>

        <!-- Slider (Full width on mobile, 3/4 on desktop) -->
        <div class="col-lg-9">
            @include('partials.slider')
        </div>
    </div>

    <!-- Mobile Categories (Optional) -->
    <div class="d-lg-none mb-4">
        <h5 class="font-weight-bold mb-3">Shop by Category</h5>
        <div class="row">
            @foreach(['Electronics', 'Fashion', 'Home', 'Beauty'] as $category)
                <div class="col-6 mb-3">
                    <a href="#" class="card border-0 shadow-sm text-center text-decoration-none p-3">
                        <span class="text-dark font-weight-bold">{{ $category }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Product Sections -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h3 class="font-weight-bold text-dark mb-0">Featured Products</h3>
            <a href="#" class="text-primary font-weight-bold">View All</a>
        </div>

        <div class="row">
            <!-- Product Card Loop (Placeholder) -->
            @for($i = 1; $i <= 10; $i++)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                    <div class="product-card h-100">
                        <div class="product-image-wrapper">
                            <!-- Placeholder Image -->
                            <div class="product-image d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="fas fa-image fa-3x text-black-50"></i>
                            </div>
                            <!-- Action Buttons -->
                            <div class="product-actions">
                                <button class="btn-action mb-2" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="btn-action" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            <h6 class="text-truncate mb-1 text-dark" title="Product Name {{ $i }}">Product Name {{ $i }}</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning small mr-1">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <small class="text-muted">(24)</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">$99.00</span>
                                <button class="btn btn-sm btn-outline-primary rounded-circle"
                                    style="width: 32px; height: 32px; padding: 0;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
@endsection