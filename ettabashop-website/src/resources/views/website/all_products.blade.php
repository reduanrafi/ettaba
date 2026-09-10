@extends('website.layouts.layout')
@section('content')
    @include('website.layouts.includes.header')

    <div class="container-fluid mb-5">

        <div class="row border-top px-xl-5">
            <div class="col-lg-3">
                @include('website.layouts.includes.sidebar')
            </div>

            <div class="col-lg-9">
                @include('website.layouts.includes.navigation')
                @include('website.layouts.home_sections.products')

            </div>

        </div>
    </div>

@endsection()

