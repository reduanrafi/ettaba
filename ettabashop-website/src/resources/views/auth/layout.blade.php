<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @yield('facebook')
    @yield('extra-meta')
    <title>{{ config('app.name', '') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{-- <link rel="stylesheet" href="{{asset('assets/website/')}}"> --}}
    <!-- Font Awesome -->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/line-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/line-awesome-font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/responsive.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .padding-select2 {
            padding-bottom: 10px;
            padding-top: 10px;
        }
    </style>
    @yield('extra-style')
</head>

<body class="">
    <div class="wrapper">
        {{-- @include('website.layouts.includes.navigation') --}}

        <!-- All Dynamic Content Placed Here -->
        @section('content') @show


    </div>
    <script type="text/javascript" src="{{ asset('assets/auth/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/js/popper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/js/jquery.mCustomScrollbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/js/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/js/scrollbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/js/script.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $('#selectDivision').select2({
            theme: 'bootstrap4',
        });
        $('#selectDistrict').select2({
            theme: 'bootstrap4',
        });
        $('#selectUpazila').select2({
            theme: 'bootstrap4',
        });
        $("[data-toggle=tooltip]").tooltip();
    </script>
    @yield('extra-script')


</body>

</html>
