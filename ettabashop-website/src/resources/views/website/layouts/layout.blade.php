<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile App Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#6366f1">

    @yield('extra-meta')
    <meta name="base-url" content="{{ url('/') }}">
    <title>{{ __("titles.pageTitle") }} | Ettaba Shop</title>

    <link rel="apple-touch-icon" href="{{ asset('assets/website/img/apple-icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo/favicon.ico')}}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('assets/website/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/website/css/style.css') }}" rel="stylesheet">

    <!-- Modern Design System -->
    <link href="{{ asset('css/ettaba-modern-v2.css') }}" rel="stylesheet">

    <style>
        /* CRITICAL APP SHELL CSS - ABSOLUTELY FORCED */
        :root {
            --primary: #6366f1;
            --secondary: #f43f5e;
            --surface: #ffffff;
            --background: #f8fafc;
        }

        [v-cloak] {
            display: none !important;
        }

        @media (max-width: 991.98px) {
            .bottom-nav {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                height: 70px !important;
                background: white !important;
                z-index: 2000 !important;
                display: flex !important;
                justify-content: space-around !important;
                align-items: center !important;
                border-top: 1px solid #e2e8f0 !important;
                box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05) !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }

        .bottom-nav-item {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            color: #64748b !important;
            text-decoration: none !important;
            font-size: 11px !important;
        }

        .bottom-nav-item.active {
            color: #6366f1 !important;
        }

        .bottom-nav-item i {
            font-size: 20px !important;
            margin-bottom: 2px !important;
        }

        .search-container {
            display: flex !important;
            background: #f1f5f9 !important;
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            overflow: hidden !important;
            width: 100% !important;
        }

        .search-input {
            border: none !important;
            background: transparent !important;
            padding: 10px 15px !important;
            width: 100% !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .modern-header {
            position: sticky !important;
            top: 0 !important;
            z-index: 1050 !important;
            background: white !important;
        }

        .side-drawer {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 320px !important;
            height: 100% !important;
            background: white !important;
            z-index: 2100 !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            flex-direction: column !important;
            transform: translateX(-100%) !important;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1) !important;
        }

        .side-drawer.active {
            transform: translateX(0) !important;
        }

        .drawer-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: rgba(0, 0, 0, 0.5) !important;
            z-index: 2099 !important;
            display: none !important;
        }

        .drawer-overlay.active {
            display: block !important;
        }

        .cart-fab-inner {
            width: 55px !important;
            height: 55px !important;
            background: #6366f1 !important;
            color: white !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-top: -35px !important;
            border: 4px solid white !important;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3) !important;
        }

        .cart-drawer {
            position: fixed !important;
            top: 0 !important;
            right: 0 !important;
            width: 380px !important;
            max-width: 100% !important;
            height: 100% !important;
            background: white !important;
            z-index: 2100 !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            flex-direction: column !important;
            transform: translateX(100%) !important;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1) !important;
        }

        .cart-drawer.active {
            transform: translateX(0) !important;
        }
    </style>

    @yield('extra-style')
</head>

<body class="bg-light">
    <div id="app" v-cloak>
        <!-- Drawer Overlay -->
        <div id="drawer-overlay" class="drawer-overlay" onclick="toggleDrawer()"></div>
        <div id="cart-overlay" class="drawer-overlay" onclick="toggleCartDrawer()"></div>

        <!-- Side Drawer -->
        <div id="side-drawer" class="side-drawer">
            <div class="drawer-header d-flex justify-content-between align-items-center p-3 border-bottom">
                <img src="{{ asset('assets/images/logo/logo.png') }}" height="35">
                <button onclick="toggleDrawer()" class="btn btn-light rounded-circle p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="drawer-body p-3 overflow-auto">
                <nav class="drawer-nav">
                    <p class="text-muted small text-uppercase font-weight-bold mb-3">Menu</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ url('/') }}"
                                class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded-lg {{ Request::is('/') ? 'bg-primary-light text-primary' : '' }}">
                                <i class="fas fa-home mr-3 {{ Request::is('/') ? 'text-primary' : 'text-muted' }}"></i> Home
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('website.shop') }}"
                                class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded-lg {{ Request::is('shop*') ? 'bg-primary-light text-primary' : '' }}">
                                <i class="fas fa-shopping-bag mr-3 {{ Request::is('shop*') ? 'text-primary' : 'text-muted' }}"></i> Shop
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('user.profile') }}"
                                class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded-lg {{ Request::is('profile*') ? 'bg-primary-light text-primary' : '' }}">
                                <i class="fas fa-user mr-3 {{ Request::is('profile*') ? 'text-primary' : 'text-muted' }}"></i> My Account
                            </a>
                        </li>
                        @auth
                            <li class="mb-2">
                                <a href="{{ url('/logout') }}"
                                    class="text-danger font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded-lg">
                                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                                </a>
                            </li>
                        @else
                            <li class="mb-2">
                                <a href="{{ route('login') }}"
                                    class="text-primary font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded-lg">
                                    <i class="fas fa-sign-in-alt mr-3"></i> Login
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('register') }}"
                                    class="text-success font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded-lg">
                                    <i class="fas fa-user-plus mr-3"></i> Register
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <p class="text-muted small text-uppercase font-weight-bold mt-4 mb-3">Product Categories</p>
                    <div class="row no-gutters">
                        @if(isset($categories))
                            @foreach($categories->take(12) as $category)
                                <div class="col-6 p-1">
                                    <a href="{{ route('categoryProducts', ['slug' => $category->slug]) }}"
                                        class="btn btn-light border text-dark btn-sm w-100 py-2 rounded-lg truncate text-left px-2">
                                        <i class="fas fa-chevron-right small mr-1 opacity-5"></i> {{ $category->name_en }}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-5 p-4 bg-primary text-white rounded-20 text-center shadow">
                        <i class="fas fa-headset fa-2x mb-3 opacity-5"></i>
                        <p class="small mb-3 font-weight-bold">Need assistance?</p>
                        <a href="tel:+8801911122252"
                            class="btn btn-light btn-block rounded-pill text-primary font-weight-bold">Call Support</a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Header -->
        @include('website.layouts.includes.header')

        <!-- Main Content -->
        <main class="animate-fade-in">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('website.layouts.includes.footer')

        <!-- Mobile Bottom Navigation -->
        <nav class="bottom-nav d-lg-none">
            <a href="{{ url('/') }}" class="bottom-nav-item {{ Request::is('/') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('website.shop') }}" class="bottom-nav-item {{ Request::is('shop*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>
                <span>Shop</span>
            </a>
            <div class="cart-fab">
                <a href="#" class="cart-fab-inner" onclick="toggleCartDrawer(); return false;">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="modern-badge" v-if="cartItems.length > 0">@{{ cartItems.length }}</span>
                </a>
            </div>
            <a href="#" class="bottom-nav-item" onclick="toggleDrawer(); return false;">
                <i class="fas fa-th-large"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('user.profile') }}"
                class="bottom-nav-item {{ Request::is('profile*') || Request::is('login') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Account</span>
            </a>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/website/lib/easing/easing.min.js')}}"></script>
    <script src="{{ asset('assets/website/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Legacy App Scripts -->
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
    <script src="{{ asset('assets/website/js/calc.js')}}?v=1.0.1"></script>
    <script src="{{ asset('assets/website/js/main.js')}}"></script>

    <script>
        function toggleDrawer() {
            document.getElementById('side-drawer').classList.toggle('active');
            document.getElementById('drawer-overlay').classList.toggle('active');
            document.body.classList.toggle('overflow-hidden');
        }

        function toggleCartDrawer() {
            document.getElementById('cartDrawer').classList.toggle('active');
            document.getElementById('cart-overlay').classList.toggle('active');
            document.body.classList.toggle('overflow-hidden');
        }
    </script>
    @yield('extra-script')
</body>

</html>