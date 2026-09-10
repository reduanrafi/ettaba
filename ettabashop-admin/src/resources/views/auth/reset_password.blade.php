@extends('layouts.auth')

@section('content')
    <header>
        <div class="container">
            <div class="header-data">
                <div class="logo">
                    <a href="{{route('website.index') }}" title=""><img src="{{ asset('assets/images/logo/logo.png') }}" alt=""></a>
                </div>



                <div class="user-account">
                    <div class="user-info">
                        @if(\Illuminate\Support\Facades\Auth::user())
                            <a href="#" title="">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>
                            <i class="bi bi-user"></i>
                        @else
                            <a href="{{ route('website.index') }}" title="">{{__('menu.home')}}</a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </header>
    <div class="sign-in-page">
        <div class="signin-popup">
            <div class="signin-pop">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="cmp-info">
                            <div class="cm-logo">
                                <img src="images/cm-logo.png" alt="">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta fugit nobis dolorum dignissimos, optio commodi et quisquam? Odit, libero ullam nisi explicabo </p>
                            </div>
                            <img src="{{ asset('assets/auth/images/banner.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="login-sec">
                            <ul class="sign-control">
                                <li data-tab="tab-1" class="current"><a href="#" title=""> </a></li>

                            </ul>
                            <div class="sign_in_sec current" id="tab-1">
                                <h3>{{__('forms.forgotPassword')}} ? </h3>
                                <div class="col-md-12" id="messageDiv">
                                    @if(Session::has('success'))
                                        @include('website.layouts.message.success')
                                    @elseif(Session::has('error'))
                                        @include('website.layouts.message.error')
                                    @endif
                                </div>

                                <form action="{{ route('resetPassword') }}" method="post">
                                    @csrf
                                    <div class="row">

                                        <div class="col-lg-12 no-pdd">
                                            <div class="sn-field">
                                                <input type="hidden" name="phone"value="{{ $phone }}">

                                            </div>
                                        </div>
                                        <div class="col-lg-12 no-pdd">
                                            <div class="sn-field">
                                                <input type="password" name="password" placeholder="{{__('forms.passwordPlaceholder')}}">
                                                <i>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                                    </svg>
                                                </i>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 no-pdd">
                                            <button type="submit" value="submit">{{__('buttons.submit')}}</button>
                                        </div>
                                    </div>
                                </form>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footy-sec">
            <div class="container">
                <ul>
                    <li><a href="" title="">{{__('menu.help')}}</a></li>
                    <li><a href="{{ route('website.about') }}" title="">{{__('menu.about')}}</a></li>
                    <li><a href="#" title="">{{__('menu.privacy')}}</a></li>
                    <li><a href="#" title="">{{__('menu.terms')}}</a></li>
{{--                    <li><a href="#" title="">Cookies Policy</a></li>--}}
{{--                    <li><a href="#" title="">Career</a></li>--}}
{{--                    <li><a href="forum.html" title="">Forum</a></li>--}}
{{--                    <li><a href="#" title="">Language</a></li>--}}
{{--                    <li><a href="#" title="">Copyright Policy</a></li>--}}
                </ul>

            </div>
        </div>
    </div>
@endsection
