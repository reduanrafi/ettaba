<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SD App') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">

    <main class="py-4">

        @yield('content')
    </main>
</div>
<style>
    /*<a href="https://www.freepik.com/free-photos-vectors/background">Background photo created by starline - www.freepik.com</a>*/
    body {
        background-color: #c0c0c263;
        {{--background-image: url({{asset("assets/authimages/bg3.jpg")}});--}}
        /*background-image: url('');*/
        background-position: center;
        background-repeat: no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-attachment: fixed;
    }
    .card {
        margin-top: 20%;
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: rgba(192, 192, 194,.3);
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
        background: rgb(192, 192, 194);
    }
    .form-control {
        display: block;
        width: 100%;
        height: calc(2.19rem + 2px);
        padding: .375rem .75rem;
        font-size: .9rem;
        line-height: 1.6;
        color: #495057;
        background-color: #fff0;
        background-clip: padding-box;
        border: 1px solid #504c4c;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
</style>
</body>
</html>
