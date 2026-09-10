@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(!Session::has('otp'))
                            <form method="POST" action="{{ route('generateOtp') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="phone"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Enter your phone') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="text"
                                               class="form-control @error('phone') is-invalid @enderror" name="phone"
                                               value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-outline-secondary">
                                            {{ __('Send Password Reset OTP') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                        @elseif(Session::has('otp'))
                            <form method="POST" action="{{ route('verifyOTP') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="otp"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Enter OTP') }}</label>

                                    <div class="col-md-6">
                                        <input id="otp" type="text"
                                               class="form-control @error('otp') is-invalid @enderror" name="otp"
                                               value="{{ old('otp') }}" required autocomplete="otp" autofocus>

                                        @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="phone" value="{{Session::get('phone')}}">
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-outline-secondary">
                                            {{ __('Verify OTP') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
