@extends('website.layouts.layout')

@section('content')
    <!-- Main Hero Section -->
    <div class="container-fluid px-xl-5 mt-4">
        <div class="row">
            <!-- Desktop Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                @include('website.layouts.includes.sidebar')
            </div>

            <!-- Content Area: Slider & Main Navigation -->
            <div class="col-lg-9">
                <div class="modern-card p-0 overflow-hidden border-0 shadow-lg rounded-20">
                    @include('website.layouts.includes.slider')
                </div>

                @if(\Illuminate\Support\Facades\Auth::user())
                    <div class="">
                        @include('website.layouts.home_sections.points')
                    </div>
                @endif

                <!-- Stats / Features -->
                <div class="mt-4">
                    @include('website.layouts.home_sections.features')
                </div>

                <!-- Promo & Top Categories (Relocated to eliminate gap) -->
                <div class="row mt-4">
                    <div class="col-lg-7 mb-4">
                        <div class="modern-card bg-primary text-white border-0 shadow-lg p-4 h-100 d-flex flex-column justify-content-center animate-fade-in" 
                             style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;">
                            <div class="d-flex align-items-center">
                                <div class="mr-4">
                                    <h4 class="font-weight-bold mb-2">Special Offer!</h4>
                                    <p class="small opacity-8 mb-3">Get up to 20% cashback on your first order this month. Don't miss out on our seasonal deals!</p>
                                    <a href="{{ route('website.shop') }}" class="btn btn-light rounded-pill font-weight-bold px-4 py-2 shadow-sm">Shop Now</a>
                                </div>
                                <div class="d-none d-md-block ml-auto">
                                    <i class="fas fa-gift fa-4x opacity-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mb-4">
                        <div class="modern-card bg-white border-0 shadow-lg p-4 h-100 animate-fade-in">
                            <h6 class="font-weight-bold mb-3">Top Categories</h6>
                            <div class="row no-gutters">
                                @php
                                    $topCats = [
                                        ['name' => 'Fashion', 'icon' => 'fa-tshirt', 'color' => '#f43f5e'],
                                        ['name' => 'Gadgets', 'icon' => 'fa-mobile-alt', 'color' => '#10b981'],
                                        ['name' => 'Grocery', 'icon' => 'fa-shopping-basket', 'color' => '#f59e0b'],
                                        ['name' => 'Health', 'icon' => 'fa-heartbeat', 'color' => '#6366f1']
                                    ];
                                @endphp
                                @foreach($topCats as $cat)
                                    <div class="col-6 p-1">
                                        <a href="#" class="d-flex align-items-center p-2 rounded bg-light text-decoration-none transition-all hover-shadow">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                                 style="width: 30px; height: 30px; background: {{ $cat['color'] }}20; color: {{ $cat['color'] }};">
                                                <i class="fas {{ $cat['icon'] }} small"></i>
                                            </div>
                                            <span class="small font-weight-bold text-dark">{{ $cat['name'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Sections -->
    <div class="sections-wrapper py-4">
        @if(count($mostPoint) > 0)
            @include('website.layouts.home_sections.most_point')
        @endif

        @if(count($featured) > 0)
            @include('website.layouts.home_sections.featured')
        @endif

        @if(count($newArrival) > 0)
            @include('website.layouts.home_sections.new')
        @endif

        @if(count($dailyNeeds) > 0)
            @include('website.layouts.home_sections.dailyNeeds')
        @endif

        @if(count($fashion) > 0)
            @include('website.layouts.home_sections.fashion')
        @endif

        {{-- Add more sections here as needed, following the same modern pattern --}}
    </div>

    <!-- Shop Lists -->
    @if(count($shops) > 0)
        @include('website.layouts.home_sections.shops')
    @endif

    <!-- Modern Offer Modal (Kept for functionality) -->
    <div id="offer-modal">
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-xl rounded-20 overflow-hidden">
                    <div class="modal-body text-center p-5">
                        <button type="button" class="close position-absolute" style="top: 20px; right: 20px;"
                            data-dismiss="modal">&times;</button>
                        <h1 class="display-4 font-weight-extra-bold text-primary mb-4" style="font-weight: 900;">পবিত্র ঈদুল
                            ফিতরের শুভেচ্ছা</h1>
                        <p class="lead text-muted mb-4">প্রিয় গ্রাহক, ঈদের আগের দিন ডেলিভারি পেতে অনুগ্রহ করে ১ তারিখ দুপুর
                            ১২.০০ টার পূর্বেই অর্ডার নিশ্চিত করুন।</p>
                        <div class="p-3 bg-light rounded-lg border mb-4">
                            <span class="font-weight-bold text-danger">১ তারিখ দুপুর ১২.০০ টার পর যেকোনাে পণ্য অর্ডারের
                                ক্ষেত্রে ৫ তারিখ ডেলিভারি সম্পন্ন করা হবে।</span>
                        </div>
                        <a class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg" data-dismiss="modal" href="#">Shop
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-style')
    <style>
        .rounded-20 {
            border-radius: 20px !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .sections-wrapper section {
            margin-bottom: 3rem;
        }

        .font-weight-extra-bold {
            font-weight: 800;
        }

        .opacity-8 {
            opacity: 0.8;
        }

        .opacity-2 {
            opacity: 0.2;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
            background: white !important;
        }
    </style>
@endsection