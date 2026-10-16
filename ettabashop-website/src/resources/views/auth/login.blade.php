@extends('auth.layout')

@section('extra-style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --ettaba-green: #0b5e42;
        --ettaba-green-light: #12825c;
        --ettaba-red: #d32f2f;
        --ettaba-red-hover: #b71c1c;
        --ettaba-bg: #f4f6f9;
        --card-border: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        font-family: 'Hind Siliguri', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--ettaba-bg);
        color: var(--text-dark);
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 14px;
        box-sizing: border-box;
    }

    .login-container {
        width: 100%;
        max-width: 440px;
        margin: 0 auto;
    }

    /* Top Pill Badge */
    .login-top-pill {
        background-color: var(--ettaba-green);
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 15.5px;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
    }

    .pill-icon-circle {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    /* Main Login Card */
    .login-card {
        background: #ffffff;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.07), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        padding: 24px 22px 28px;
        border: 1px solid var(--card-border);
        border-top: none;
        box-sizing: border-box;
    }

    /* Brand Header */
    .brand-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .brand-header .logo-img {
        max-height: 44px;
        width: auto;
        object-fit: contain;
        transition: transform 0.2s ease;
    }

    .brand-header .logo-img:hover {
        transform: scale(1.02);
    }

    .brand-header .slogan-text {
        color: var(--ettaba-green);
        font-size: 13.5px;
        font-weight: 600;
        margin-top: 4px;
        letter-spacing: 0.3px;
    }

    .login-greeting {
        text-align: center;
        margin-bottom: 20px;
    }

    .login-title {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .login-subtitle {
        font-size: 13px;
        color: var(--text-muted);
    }

    /* Form Inputs */
    .form-group-custom {
        position: relative;
        margin-bottom: 14px;
    }

    .form-group-custom .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }

    .form-control-custom {
        width: 100%;
        height: 46px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 14px 8px 40px;
        font-size: 14.5px;
        color: #1e293b;
        background-color: #ffffff;
        transition: all 0.2s ease;
        outline: none;
        box-sizing: border-box;
    }

    .form-control-custom:focus {
        border-color: var(--ettaba-red);
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.12);
    }

    .form-control-custom.is-invalid {
        border-color: #ef4444;
        background-color: #fffaf0;
    }

    .input-toggle-pwd {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        cursor: pointer;
        font-size: 15px;
        padding: 6px;
        z-index: 3;
    }

    .input-toggle-pwd:hover {
        color: #1e293b;
    }

    .field-hint.error {
        color: #dc2626;
        font-size: 12px;
        font-weight: 500;
        margin-top: 4px;
        display: block;
    }

    /* Remember & Forgot Row */
    .login-options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 10px 0 18px;
        font-size: 13.5px;
    }

    .custom-checkbox-label {
        display: flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        margin-bottom: 0;
        user-select: none;
        color: #475569;
    }

    .custom-checkbox-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--ettaba-green);
        cursor: pointer;
        margin: 0;
    }

    .forgot-link {
        color: var(--ettaba-red);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .forgot-link:hover {
        color: var(--ettaba-red-hover);
        text-decoration: underline;
    }

    /* Submit Button */
    .btn-login-submit {
        width: 100%;
        height: 48px;
        background-color: var(--ettaba-red);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(211, 47, 47, 0.25);
    }

    .btn-login-submit:hover {
        background-color: var(--ettaba-red-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(211, 47, 47, 0.35);
    }

    .btn-login-submit:active {
        transform: translateY(0);
    }

    /* Prompts */
    .register-prompt {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
        color: #64748b;
    }

    .register-prompt a {
        color: var(--ettaba-green);
        font-weight: 700;
        text-decoration: none;
    }

    .register-prompt a:hover {
        text-decoration: underline;
    }

    .back-home-prompt {
        text-align: center;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .back-home-prompt a {
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }

    .back-home-prompt a:hover {
        color: var(--text-dark);
    }

    /* Compact Mobile Specific Styles */
    @media (max-width: 576px) {
        .login-wrapper {
            padding: 12px 10px;
            justify-content: center;
        }

        .login-card {
            padding: 18px 16px 22px;
            border-radius: 0 0 14px 14px;
        }

        .brand-header {
            margin-bottom: 14px;
        }

        .brand-header .logo-img {
            max-height: 38px;
        }

        .brand-header .slogan-text {
            font-size: 12.5px;
            margin-top: 2px;
        }

        .login-greeting {
            margin-bottom: 16px;
        }

        .login-title {
            font-size: 17.5px;
        }

        .form-control-custom {
            height: 44px;
            font-size: 14px;
        }

        .btn-login-submit {
            height: 44px;
            font-size: 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-container">

        <!-- Top Pill -->
        <div class="login-top-pill">
            <span class="pill-icon-circle"><i class="fa fa-sign-in-alt"></i></span>
            <span>একাউন্টে লগইন করুন</span>
        </div>

        <!-- Main Card -->
        <div class="login-card">

            <!-- Brand Header -->
            <div class="brand-header">
                <a href="{{ route('website.index') }}">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Ettaba Shop" class="logo-img">
                </a>
                <div class="slogan-text">পরিবারের খরচ হোক সাশ্রয়ী</div>
            </div>

            <!-- Greeting -->
            <div class="login-greeting">
                <div class="login-title">স্বাগতম!</div>
                <div class="login-subtitle">আপনার মোবাইল নম্বর ও পাসওয়ার্ড প্রদান করে এগিয়ে যান</div>
            </div>

            <!-- Alerts -->
            @if(Session::has('success'))
                <div class="alert alert-success py-2 px-3 mb-3 text-center" style="font-size: 13.5px; border-radius: 8px;">
                    <i class="fa fa-check-circle mr-1"></i> {{ Session::get('success') }}
                </div>
            @endif

            @if(Session::has('error'))
                <div class="alert alert-danger py-2 px-3 mb-3 text-center" style="font-size: 13.5px; border-radius: 8px;">
                    <i class="fa fa-exclamation-circle mr-1"></i> {{ Session::get('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" autocomplete="on">
                @csrf

                <!-- Phone / Username -->
                <div class="form-group-custom">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text"
                           name="phone"
                           id="login_phone"
                           class="form-control-custom @error('phone') is-invalid @enderror"
                           placeholder="মোবাইল নম্বর / ইউজার নেম"
                           value="{{ old('phone') }}"
                           required
                           autofocus
                           autocomplete="username">
                </div>
                @error('phone')
                    <small class="field-hint error mb-2">{{ $message }}</small>
                @enderror

                <!-- Password -->
                <div class="form-group-custom">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password"
                           name="password"
                           id="login_password"
                           class="form-control-custom @error('password') is-invalid @enderror"
                           placeholder="পাসওয়ার্ড লিখুন"
                           required
                           autocomplete="current-password">
                    <span class="input-toggle-pwd" onclick="toggleLoginPassword(this)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <small class="field-hint error mb-2">{{ $message }}</small>
                @enderror

                <!-- Remember Me & Forgot Password -->
                <div class="login-options-row">
                    <label class="custom-checkbox-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>মনে রাখুন</span>
                    </label>
                    <a href="{{ route('forgotPassword') }}" class="forgot-link">পাসওয়ার্ড ভুলে গেছেন?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login-submit">
                    <span>লগইন করুন</span>
                    <i class="fa fa-arrow-right"></i>
                </button>
            </form>

            <!-- Register Prompt -->
            <div class="register-prompt">
                নতুন গ্রাহক? <a href="{{ route('register') }}">নতুন একাউন্ট তৈরি করুন</a>
            </div>

            <!-- Home Link -->
            <div class="back-home-prompt">
                <a href="{{ route('website.index') }}">
                    <i class="fa fa-home"></i> হোমপেইজে ফিরে যান
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
    function toggleLoginPassword(el) {
        const field = document.getElementById('login_password');
        const icon = el.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
