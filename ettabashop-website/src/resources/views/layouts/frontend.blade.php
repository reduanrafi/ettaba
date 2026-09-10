<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Mobile App Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#6366f1">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Styles -->
    <link href="{{ asset('css/custom-frontend.css') }}" rel="stylesheet">
</head>

<body>

    <div id="app" class="d-flex flex-column min-vh-100">
        @include('partials.navbar')

        <main class="flex-grow-1 container py-4">
            @yield('content')
        </main>

        @include('partials.mobile-footer')

        <!-- Mobile App Drawer -->
        <div id="drawer-backdrop" class="drawer-backdrop" onclick="toggleDrawer()"></div>
        <div id="drawer-content" class="drawer-content d-flex flex-column">
            <div class="drawer-header d-flex justify-content-between align-items-center">
                <span class="font-weight-bold h5 mb-0 text-primary">Explore Shop</span>
                <button onclick="toggleDrawer()" class="btn btn-light rounded-circle p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="drawer-body">
                <div class="mb-4">
                    <p class="text-muted small text-uppercase font-weight-bold mb-3">Main Menu</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 px-2 py-1"><a href="{{ url('/') }}"
                                class="text-dark font-weight-bold text-decoration-none d-flex align-items-center"><i
                                    class="fas fa-home mr-3 text-primary"></i> Home</a></li>
                        <li class="mb-3 px-2 py-1"><a href="#"
                                class="text-dark font-weight-bold text-decoration-none d-flex align-items-center"><i
                                    class="fas fa-shopping-bag mr-3 text-primary"></i> Shop</a></li>
                        <li class="mb-3 px-2 py-1"><a href="#"
                                class="text-dark font-weight-bold text-decoration-none d-flex align-items-center"><i
                                    class="fas fa-th-large mr-3 text-primary"></i> Categories</a></li>
                    </ul>
                </div>

                <div class="border-top pt-4">
                    <p class="text-muted small text-uppercase font-weight-bold mb-3">Top Categories</p>
                    <div class="row no-gutters">
                        @foreach(['Electronics', 'Fashion', 'Home', 'Beauty', 'Sports', 'Grocery'] as $category)
                            <div class="col-6 p-1">
                                <a href="#"
                                    class="btn btn-outline-light text-dark border text-left btn-sm w-100 py-2 px-3 rounded-lg shadow-sm">
                                    {{ $category }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 p-4 bg-light rounded-20 text-center shadow-sm">
                    <p class="small text-muted mb-3">Contact us for any help</p>
                    <a href="tel:+880123456789" class="btn btn-primary btn-block rounded-pill">Call Support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <script>
        function toggleDrawer() {
            const backdrop = document.getElementById('drawer-backdrop');
            const content = document.getElementById('drawer-content');

            backdrop.classList.toggle('show');
            content.classList.toggle('show');
        }
    </script>
</body>

</html>