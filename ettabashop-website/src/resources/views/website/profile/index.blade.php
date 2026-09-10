@extends('website.profile.layouts.layout')
@section('facebook')
    {{--    <meta property="og:title" content="{{ $product->name_en}}"/>--}}
    {{--    <meta property="og:image" content="{{'http://mahedipublications.com/'.$product->featured_image }}"/>--}}
    {{--    <meta property="og:description" content="{{ $product->description_en }}"/>--}}
@endsection
@section('content')
    <!-- Shop Detail Start -->

    <div class="row px-xl-5 py-3 align-items-center bg-white shadow-sm mb-4">
        <div class="col-lg-2 col-6">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo/logo.png') }}" height="50">
            </a>
        </div>

        <div class="col-lg-10 col-6 text-right">
            <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill px-4 font-weight-bold">
                <i class="fas fa-home mr-2"></i> Back to Home
            </a>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="row">

            <div class="col-md-3">

                <!-- Profile Image -->
               @include('website.profile.layouts.partials.sidebar')
                <!-- /.card -->


            </div>
            <!-- /.col -->
            <div class="col-md-9">
                @if(auth()->user()->is_approved==0)
                    <div class="alert alert-warning alert-dismissible" role="alert">
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

