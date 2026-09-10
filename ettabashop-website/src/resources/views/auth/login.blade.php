@extends('auth.layout')

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
                                <p>আপনার একাউন্টে প্রবেশ করতে অনুগ্রহ করে আপনার মোবাইল নাম্বার এবং পাসওয়ার্ড দিয়ে লগইন করুন। একাউন্ট নিরাপদ রাখতে আপনার পাসওয়ার্ড এবং OTP কারো সাথে শেয়ার করবেন না।</p>
                            </div>
                            <img src="{{ asset('assets/auth/images/banner.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="login-sec">
                            <ul class="sign-control">
                                <li data-tab="tab-1" class="current"><a href="#" title="">{{__('forms.signIn')}}</a></li>

                            </ul>
                            <div class="sign_in_sec current" id="tab-1">
                                <h3>{{__('forms.signIn')}}</h3>
                                <div class="col-md-12" id="messageDiv">
                                    @if(Session::has('success'))
                                        @include('website.layouts.message.success')
                                    @elseif(Session::has('error'))
                                        @include('website.layouts.message.error')
                                    @endif
                                </div>
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 no-pdd">
                                            <div class="sn-field">
                                                <input type="text" name="phone" placeholder="{{__('forms.phonePlaceholder')}}">
                                                <i>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                                    </svg>
                                                </i>
                                                @error('phone')
                                                <div class="text-danger error">{{ $message }}</div>
                                                @enderror
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
                                                @error('password')
                                                <div class="text-danger error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 no-pdd">
                                            <div class="checky-sec">
                                                <div class="fgt-sec">
                                                    <input type="checkbox" name="remember" id="c1">
                                                    <label for="c1">
                                                        <span></span>
                                                    </label>
                                                    <small>Remember me</small>
                                                </div>
                                                <a href="{{ route('forgotPassword') }}" title="">{{__('forms.forgotPassword')}}</a>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 no-pdd">
                                            <button type="submit" value="submit">{{__('buttons.signIn')}}</button>
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
