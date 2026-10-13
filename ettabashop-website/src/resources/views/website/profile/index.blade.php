@extends('website.profile.layouts.layout')
@section('facebook')
    {{--    <meta property="og:title" content="{{ $product->name_en}}"/>--}}
    {{--    <meta property="og:image" content="{{'http://mahedipublications.com/'.$product->featured_image }}"/>--}}
    {{--    <meta property="og:description" content="{{ $product->description_en }}"/>--}}
@endsection
@section('content')
    <!-- Modern Top Navigation Bar -->
    <div class="profile-topbar">
        <div class="container-fluid px-lg-5 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="brand-logo" alt="Ettaba Shop">
                </a>
                <span class="d-none d-md-inline-block ml-3 text-muted" style="font-size: 13px; font-weight: 500; border-left: 2px solid #e2e8f0; padding-left: 12px;">
                    পরিবারের খরচ হোক সাশ্রয়ী
                </span>
            </div>

            <div class="d-flex align-items-center" style="gap: 10px;">
                <a href="{{ route('website.index') }}" class="topbar-btn topbar-btn-home">
                    <i class="fas fa-home"></i>
                    <span>হোম পেজ</span>
                </a>
                <a href="{{ url('/') }}" class="topbar-btn d-none d-sm-inline-flex" style="background:#f1f5f9; color:#334155;">
                    <i class="fas fa-store"></i>
                    <span>শপিং করুন</span>
                </a>
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();" 
                   class="topbar-btn topbar-btn-logout" title="লগআউট">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-sm-inline">লগআউট</span>
                </a>
                <form id="profile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Container -->
    <div class="container-fluid px-lg-5 py-4">
        
        <!-- Hero Greeting Banner -->
        <div class="dash-hero-banner">
            <div class="dash-hero-content">
                <div class="hero-avatar-wrap">
                    <img src="{{ isset($profile->profile_image) && $profile->profile_image != '' ? asset($profile->profile_image) : asset('assets/images/user.png') }}" 
                         class="hero-avatar-img" 
                         alt="User Profile">
                    <span class="hero-avatar-badge" title="Active Account"></span>
                </div>
                
                <div class="hero-user-details flex-grow-1">
                    <h2>স্বাগতম, {{ !empty($profile->first_name) ? $profile->first_name . ' ' . ($profile->last_name ?? '') : Auth::user()->name }} 👋</h2>
                    <div class="hero-badges-row">
                        @if(Auth::user()->customer_type == 'buy_earn')
                            <span class="dash-badge dash-badge-partner">
                                <i class="fas fa-crown text-warning"></i> পার্টনার / এজেন্ট
                            </span>
                        @else
                            <span class="dash-badge dash-badge-customer">
                                <i class="fas fa-shopping-bag text-info"></i> কাস্টমার একাউন্ট
                            </span>
                        @endif

                        <span class="dash-badge dash-badge-eid" onclick="copyEid('{{ Auth::user()->referral_code }}')" title="কপি করতে ক্লিক করুন">
                            <i class="fas fa-id-card"></i> eID: <strong>{{ Auth::user()->referral_code }}</strong>
                            <i class="far fa-copy ml-1"></i>
                        </span>

                        <span class="dash-badge" style="background: rgba(255,255,255,0.18); color: #fff;">
                            <i class="fas fa-phone-alt"></i> {{ Auth::user()->phone }}
                        </span>

                        @if(Auth::user()->is_approved == 1)
                            <span class="dash-badge dash-badge-verified">
                                <i class="fas fa-check-circle"></i> ভেরিফাইড প্রোফাইল
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Metric Cards (Dashboard Grid) -->
        <div class="dash-stats-grid">
            <!-- eBalance -->
            <div class="dash-stat-card">
                <div>
                    <div class="stat-top-row">
                        <span class="stat-title">eBalance (উপলব্ধ)</span>
                        <div class="stat-icon-wrap icon-emerald">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="stat-value">৳ {{ number_format($earnings['totalEarning'] ?? 0, 2) }}</div>
                </div>
                <a href="{{ route('profile.withdrawRequests') }}" class="stat-action-link">
                    উত্তোলন রিকোয়েস্ট <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Total Earned -->
            <div class="dash-stat-card">
                <div>
                    <div class="stat-top-row">
                        <span class="stat-title">সর্বমোট অর্জিত আয়</span>
                        <div class="stat-icon-wrap icon-indigo">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="stat-value">৳ {{ number_format(($earnings['totalEarning'] ?? 0) + ($earnings['totalWithdraw'] ?? 0), 2) }}</div>
                </div>
                <span class="text-muted" style="font-size: 11.5px; margin-top: 10px;">
                    কমিশন ও ক্যাশব্যাক থেকে
                </span>
            </div>

            <!-- Reward Points -->
            <div class="dash-stat-card">
                <div>
                    <div class="stat-top-row">
                        <span class="stat-title">রিওয়ার্ড পয়েন্ট</span>
                        <div class="stat-icon-wrap icon-amber">
                            <i class="fas fa-gem"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($earnings['totalPoint'] ?? 0) }} <small style="font-size: 13px;">Pts</small></div>
                </div>
                <span class="text-muted" style="font-size: 11.5px; margin-top: 10px;">
                    শপিং রিওয়ার্ড পয়েন্ট ব্যালেন্স
                </span>
            </div>

            <!-- Total Withdrawn -->
            <div class="dash-stat-card">
                <div>
                    <div class="stat-top-row">
                        <span class="stat-title">মোট উত্তোলিত অর্থ</span>
                        <div class="stat-icon-wrap icon-teal">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                    <div class="stat-value">৳ {{ number_format($earnings['totalWithdraw'] ?? 0, 2) }}</div>
                </div>
                <a href="{{ route('profile.withdrawHistories') }}" class="stat-action-link">
                    হিস্টোরি দেখুন <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Mobile Horizontal Quick Navigation Bar (Touch Friendly App-like Bar) -->
        <div class="mobile-quick-nav-wrap">
            <div class="mobile-quick-nav-scroll">
                <a href="{{ route('user.profile') }}" class="m-nav-pill {{ Request::is('profile/index') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> <span>প্রোফাইল সেটিংস</span>
                </a>
                <a href="{{ route('profile.order') }}" class="m-nav-pill {{ Request::is('profile/orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i> <span>অনলাইন অর্ডার</span>
                </a>
                <a href="{{ route('profile.handcash.order') }}" class="m-nav-pill {{ Request::is('profile/hand-cash-orders') ? 'active' : '' }}">
                    <i class="fas fa-store"></i> <span>মার্চেন্ট ক্যাশব্যাক</span>
                </a>
                <a href="{{ route('profile.withdrawRequests') }}" class="m-nav-pill {{ Request::is('profile/withdraw-requests') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i> <span>উত্তোলন রিকোয়েস্ট</span>
                </a>
                <a href="{{ route('profile.withdrawHistories') }}" class="m-nav-pill {{ Request::is('profile/withdraw-histories') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> <span>উত্তোলন হিস্টোরি</span>
                </a>
                <a href="{{ route('profile.teamTree') }}" class="m-nav-pill {{ Request::is('profile/team-tree') ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i> <span>টিম ও ট্রি</span>
                </a>
                @if(Auth::user()->customer_type == 'buy_earn')
                    <a href="{{ route('profile.trainings') }}" class="m-nav-pill {{ Request::is('profile/trainings') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i> <span>ট্রেনিং</span>
                    </a>
                    <a href="{{ route('profile.referral') }}" class="m-nav-pill {{ Request::is('profile/referral') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i> <span>রেফারেল</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Content Area: Sidebar + Body -->
        <div class="row">
            <div class="col-lg-3 col-md-4 d-none d-md-block">
                @include('website.profile.layouts.partials.sidebar')
            </div>

            <div class="col-lg-9 col-md-8 col-12">
                @if(auth()->user()->is_approved == 0)
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b !important;">
                        <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                        {{ __('messages.profileCreateWarning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(Request::is('profile/index'))
                    @include('website.profile.pages.settings')
                @elseif(Request::is('profile/orders'))
                        @include('website.profile.pages.orders')
                    @elseif(Request::is('profile/hand-cash-orders'))
                        @include('website.profile.pages.hc_orders')
                @elseif(Request::is('profile/order-requests'))
                    @include('website.profile.pages.orders_requests')
                @elseif(Request::is('profile/order-detail'))
                    @include('website.profile.pages.order_detail')

                @elseif(Request::is('profile/trainings'))
                        @if(\Illuminate\Support\Facades\Auth::user()->customer_type=='buy_earn')
                            @include('website.profile.pages.trainings')
                        @endif
                @elseif(Request::is('profile/referral'))
                    @if(\Illuminate\Support\Facades\Auth::user()->customer_type=='buy_earn')
                        @include('website.profile.pages.referral')
                    @endif

                @elseif(Request::is('profile/withdraw-requests'))
{{--                    @if(\Illuminate\Support\Facades\Auth::user()->customer_type=='buy_earn')--}}
                        @include('website.profile.pages.withdraw_request')
{{--                    @endif--}}
                @elseif(Request::is('profile/withdraw-histories'))
{{--                    @if(\Illuminate\Support\Facades\Auth::user()->customer_type=='buy_earn')--}}
                        @include('website.profile.pages.withdraw_histories')
{{--                    @endif--}}
                @elseif(Request::is('profile/team-tree'))
                    @include('website.profile.pages.team_tree')
                @endif
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- Shop Detail End -->
@endsection()
@section('extra-script')
    <script>
        var loadFile = function (event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);

        };
        var loadNid = function (event) {

            var image = document.getElementById('nid');
            image.src = URL.createObjectURL(event.target.files[0]);

        };
    </script>
@endsection()

