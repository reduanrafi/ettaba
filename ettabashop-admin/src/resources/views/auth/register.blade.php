@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">Account Type</label>

                            <div class="col-md-6">
{{--                                <input id="type" type="email" readonly value="store_owner"--}}
{{--                                       class="form-control @error('type') is-invalid @enderror" --}}
{{--                                       name="type" --}}
{{--                                       value="{{ old('type') }}" required autocomplete="type">--}}
                                <select  name="type" class="form-control @error('type') is-invalid @enderror">
                                    <option value="store_owner" {{ old('type') == 'store_owner' ? 'selected' : '' }}>Online Shop</option>
                                    <option value="store_administrator" {{ old('type') == 'store_administrator' ? 'selected' : '' }}>Merchant</option>
                                    <option value="direct_selling" {{ old('type') == 'direct_selling' ? 'selected' : '' }}>Direct Seller</option>
                                </select>

                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" id="email_group">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" id="referral_code_group" style="display: none;">
                            <label for="referral_code" class="col-md-4 col-form-label text-md-right">Referral eID</label>

                            <div class="col-md-6">
                                <input id="referral_code" type="text" class="form-control @error('referral_code') is-invalid @enderror" name="referral_code" value="{{ old('referral_code') }}">

                                @error('referral_code')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.querySelector('select[name="type"]');
        const referralGroup = document.getElementById('referral_code_group');
        const referralInput = document.getElementById('referral_code');
        const emailGroup = document.getElementById('email_group');
        const emailInput = document.getElementById('email');

        function toggleFields() {
            if (typeSelect.value === 'store_administrator') {
                referralGroup.style.display = 'flex';
                referralInput.setAttribute('required', 'required');
                
                emailGroup.style.display = 'none';
                emailInput.removeAttribute('required');
            } else {
                referralGroup.style.display = 'none';
                referralInput.removeAttribute('required');
                
                emailGroup.style.display = 'flex';
                emailInput.setAttribute('required', 'required');
            }
        }

        typeSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial check on load
    });
</script>
@endsection
