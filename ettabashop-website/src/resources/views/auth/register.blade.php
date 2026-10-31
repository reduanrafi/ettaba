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
    }

    .registration-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px 40px;
    }

    .reg-container {
        width: 100%;
        max-width: 490px;
        margin: 0 auto;
    }

    /* Step Top Pill Badge */
    .step-top-pill {
        background-color: var(--ettaba-green);
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 10px 10px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .step-top-pill.success-pill {
        background-color: #15803d;
    }

    .step-num-circle {
        background-color: #ffffff;
        color: var(--ettaba-green);
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
    }

    .step-top-pill.success-pill .step-num-circle {
        color: #15803d;
    }

    /* Main Card */
    .reg-card {
        background: #ffffff;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        padding: 28px 26px 32px;
        border: 1px solid var(--card-border);
        border-top: none;
        position: relative;
    }

    /* Brand Header */
    .brand-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .brand-header .logo-img {
        max-height: 48px;
        width: auto;
        object-fit: contain;
    }

    .brand-header .slogan-text {
        color: var(--ettaba-green);
        font-size: 14px;
        font-weight: 600;
        margin-top: 6px;
        letter-spacing: 0.3px;
    }

    /* Progress Indicator */
    .step-progress-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        margin: 20px 10px 26px;
    }

    .step-progress-track {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #e2e8f0;
        transform: translateY(-50%);
        z-index: 1;
    }

    .step-progress-fill {
        height: 100%;
        background-color: var(--ettaba-red);
        transition: width 0.3s ease;
    }

    .step-progress-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #cbd5e1;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .step-progress-dot.active {
        background-color: var(--ettaba-red);
        transform: scale(1.25);
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2);
    }

    .step-progress-dot.completed {
        background-color: var(--ettaba-red);
    }

    /* Step Icon & Title Area */
    .step-icon-wrapper {
        width: 64px;
        height: 64px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: #334155;
        font-size: 26px;
        transition: transform 0.2s ease;
    }

    .step-title {
        font-size: 19px;
        font-weight: 700;
        text-align: center;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .step-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
        text-align: center;
        margin-bottom: 22px;
    }

    /* Inputs & Selects */
    .form-group-custom {
        position: relative;
        margin-bottom: 16px;
    }

    .form-group-custom .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
        z-index: 2;
    }

    .form-control-custom {
        width: 100%;
        height: 48px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 14px 10px 42px;
        font-size: 15px;
        color: #1e293b;
        background-color: #ffffff;
        transition: all 0.2s ease;
        outline: none;
    }

    .form-control-custom:focus {
        border-color: var(--ettaba-red);
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.12);
    }

    .form-control-custom.is-invalid {
        border-color: #ef4444;
        background-color: #fffaf0;
    }

    .form-control-custom.is-valid {
        border-color: #10b981;
    }

    .form-control-custom.no-icon {
        padding-left: 14px;
    }

    select.form-control-custom {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 14px 10px;
        cursor: pointer;
    }

    .input-toggle-pwd {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        cursor: pointer;
        font-size: 16px;
        padding: 6px;
        z-index: 3;
    }

    .input-toggle-pwd:hover {
        color: #1e293b;
    }

    /* Helper & Feedback Text */
    .field-hint {
        font-size: 12.5px;
        color: var(--text-muted);
        margin-top: 5px;
        display: block;
    }

    .field-hint.error {
        color: #dc2626;
        font-weight: 500;
    }

    .field-hint.success {
        color: #16a34a;
        font-weight: 500;
    }

    /* Info Alert Card */
    .info-note-box {
        background-color: #fef2f2;
        border: 1px solid #fee2e2;
        border-radius: 8px;
        padding: 10px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
        font-size: 13px;
        color: #991b1b;
        line-height: 1.45;
    }

    .info-note-box i {
        font-size: 16px;
        margin-top: 1px;
        flex-shrink: 0;
    }

    /* Action Buttons */
    .btn-step-next {
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
        margin-top: 14px;
    }

    .btn-step-next:hover:not(:disabled) {
        background-color: var(--ettaba-red-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(211, 47, 47, 0.35);
    }

    .btn-step-next:disabled {
        background-color: #f87171;
        opacity: 0.65;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .btn-step-back {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin: 12px auto 0;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        transition: color 0.2s ease;
    }

    .btn-step-back:hover {
        color: #0f172a;
        background-color: #f1f5f9;
    }

    /* Upload Area */
    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px 16px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .upload-dropzone:hover {
        border-color: var(--ettaba-red);
        background-color: #fffaf0;
    }

    .upload-dropzone .upload-icon {
        font-size: 38px;
        color: #94a3b8;
        margin-bottom: 10px;
    }

    .upload-dropzone .upload-text {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .upload-dropzone .upload-hint {
        font-size: 12.5px;
        color: #64748b;
    }

    .image-preview-wrapper {
        display: none;
        text-align: center;
        margin-top: 14px;
    }

    .image-preview-wrapper img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #10b981;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .image-preview-wrapper .change-img-btn {
        display: inline-block;
        margin-top: 8px;
        font-size: 13px;
        color: var(--ettaba-red);
        cursor: pointer;
        font-weight: 600;
    }

    /* Step 8 Success Styling */
    .success-celebration-box {
        text-align: center;
        padding: 10px 0 16px;
    }

    .success-circle-icon {
        width: 80px;
        height: 80px;
        background: #16a34a;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 18px;
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.25);
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-title {
        font-size: 24px;
        font-weight: 700;
        color: #16a34a;
        margin-bottom: 12px;
    }

    .success-message {
        font-size: 15px;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 24px;
        padding: 0 10px;
    }

    .btn-home {
        width: 100%;
        height: 48px;
        background-color: #16a34a;
        color: #ffffff;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        margin-bottom: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.25);
    }

    .btn-home:hover {
        background-color: #15803d;
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .btn-profile-view {
        width: 100%;
        height: 48px;
        background-color: #ffffff;
        color: #334155;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-profile-view:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
        text-decoration: none;
    }

    /* Confetti particle decor */
    .confetti-decor {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 2px;
    }

    /* Spinner */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }

    .login-prompt {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
        color: #64748b;
    }

    .login-prompt a {
        color: var(--ettaba-red);
        font-weight: 600;
        text-decoration: none;
    }

    .login-prompt a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="registration-wrapper">
    <div class="reg-container">

        <!-- Top Step Ribbon / Pill -->
        <div class="step-top-pill" id="stepPill">
            <span class="step-num-circle" id="pillStepNum">১</span>
            <span id="pillStepTitle">পুরো নাম লিখুন</span>
        </div>

        <!-- Main Registration Card -->
        <div class="reg-card">
            <!-- Brand Header -->
            <div class="brand-header">
                <a href="{{ route('website.index') }}">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Ettaba Shop" class="logo-img">
                </a>
                <div class="slogan-text">পরিবারের খরচ হোক সাশ্রয়ী</div>
            </div>

            <!-- Progress Bar -->
            <div class="step-progress-bar" id="progressBar">
                <div class="step-progress-track">
                    <div class="step-progress-fill" id="progressFill" style="width: 14%;"></div>
                </div>
                <div class="step-progress-dot active" data-step="1"></div>
                <div class="step-progress-dot" data-step="2"></div>
                <div class="step-progress-dot" data-step="3"></div>
                <div class="step-progress-dot" data-step="4"></div>
                <div class="step-progress-dot" data-step="5"></div>
                <div class="step-progress-dot" data-step="6"></div>
                <div class="step-progress-dot" data-step="7"></div>
                <div class="step-progress-dot" data-step="8"></div>
            </div>

            <!-- Global Error / Alert Display -->
            <div id="globalAlert" style="display: none;" class="alert alert-danger py-2 px-3 mb-3 text-center" style="font-size: 13.5px;"></div>

            <!-- Multi-step Form -->
            <form id="multiStepForm" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="customer">
                <input type="hidden" name="profile_image_base64" id="profile_image_base64">

                <!-- ================= STEP 1: Full Name ================= -->
                <div class="step-section" id="step-1">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="step-title">আপনার পুরো নাম লিখুন</div>
                    <div class="step-subtitle">(বাংলা/ইংরেজি)</div>

                    <div class="form-group-custom">
                        <i class="fa fa-user input-icon"></i>
                        <input type="text"
                               name="name"
                               id="name_input"
                               class="form-control-custom"
                               placeholder="আপনার পুরো নাম লিখুন (বাংলা/ইংরেজি)"
                               value="{{ old('name') }}"
                               autofocus>
                        <small class="field-hint" id="name_hint">কমপক্ষে ২টি বর্ণ লিখুন। কোনো সংখ্যা বা চিহ্ন লিখা যাবে না।</small>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-1" disabled>
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>

                    <div class="login-prompt">
                        ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="{{ route('login') }}">লগইন করুন</a>
                    </div>
                </div>

                <!-- ================= STEP 2: Mobile / Username ================= -->
                <div class="step-section" id="step-2" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-mobile-alt"></i>
                    </div>
                    <div class="step-title">আপনার মোবাইল নম্বর লিখুন</div>
                    <div class="step-subtitle">আপনার মোবাইল নম্বরটি ইউজার নেম হিসেবে ব্যবহৃত হবে</div>

                    <div class="form-group-custom">
                        <i class="fa fa-phone input-icon"></i>
                        <input type="text"
                               name="phone"
                               id="phone_input"
                               class="form-control-custom"
                               placeholder="মোবাইল/ইউজার নম্বর"
                               value="{{ old('phone') }}">
                        <small class="field-hint" id="phone_hint">শুধু ইংরেজি সংখ্যা ও লেটার। কমপক্ষে ৫টি অক্ষর বা সংখ্যা হতে হবে।</small>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-2" disabled>
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn-step-back" onclick="goToStep(1)">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 3: Account Type Selection ================= -->
                <div class="step-section" id="step-3" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-id-card"></i>
                    </div>
                    <div class="step-title">একাউন্ট নির্বাচন করুন</div>
                    <div class="step-subtitle">আপনার প্রয়োজনীয় একাউন্টের ধরন নির্বাচন করুন</div>

                    <div class="form-group-custom">
                        <i class="fa fa-user-tag input-icon"></i>
                        <select name="customer_type" id="customer_type_select" class="form-control-custom">
                            <option value="" disabled selected>একাউন্ট নির্বাচন করুন</option>
                            <option value="buy_only">কাস্টমার একাউন্ট</option>
                            <option value="buy_earn">পার্টনার একাউন্ট/পেইড এজেন্ট</option>
                            <option value="virtual_agent">ভার্চুয়াল এজেন্ট</option>
                        </select>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-3" disabled>
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn-step-back" onclick="goToStep(2)">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 3.1: Partner Extra Info (Mandatory) ================= -->
                <div class="step-section" id="step-3-partner" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <div class="step-title">পার্টনার একাউন্টের জন্য অতিরিক্ত তথ্য দিন</div>
                    <div class="step-subtitle">সঠিক NID, জন্মতারিখ ও পিতা-মাতার নাম প্রদান আবশ্যক (ব্লাড গ্রুপ ঐচ্ছিক)</div>

                    <div class="form-group-custom">
                        <i class="fa fa-id-badge input-icon"></i>
                        <input type="text"
                               name="nid"
                               id="nid_input"
                               class="form-control-custom"
                               placeholder="জাতীয় পরিচয়পত্র (NID) নম্বর * (সর্বনিম্ন ১০ সংখ্যা)"
                               maxlength="30">
                        <small class="field-hint" id="nid_hint">শুধুমাত্র ইংরেজি সংখ্যা লিখুন (সর্বনিম্ন ১০ সংখ্যা) <span class="text-danger">*</span></small>
                    </div>

                    <div class="form-group-custom">
                        <i class="fa fa-calendar-alt input-icon"></i>
                        <input type="date"
                               name="date_of_birth"
                               id="dob_input"
                               class="form-control-custom">
                        <small class="field-hint">জন্মতারিখ নির্বাচন করুন <span class="text-danger">*</span></small>
                    </div>

                    <div class="form-group-custom">
                        <i class="fa fa-user input-icon"></i>
                        <input type="text"
                               name="partner_father_name"
                               id="partner_father_name"
                               class="form-control-custom"
                               placeholder="পিতার নাম লিখুন *">
                        <small class="field-hint">পিতার নাম <span class="text-danger">*</span></small>
                    </div>

                    <div class="form-group-custom">
                        <i class="fa fa-user input-icon"></i>
                        <input type="text"
                               name="partner_mother_name"
                               id="partner_mother_name"
                               class="form-control-custom"
                               placeholder="মাতার নাম লিখুন *">
                        <small class="field-hint">মাতার নাম <span class="text-danger">*</span></small>
                    </div>

                    <div class="form-group-custom">
                        <i class="fa fa-tint input-icon"></i>
                        <select name="partner_blood_group" id="partner_blood_group" class="form-control-custom">
                            <option value="" selected>ব্লাড গ্রুপ নির্বাচন করুন (ঐচ্ছিক)</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                        <small class="field-hint">ব্লাড গ্রুপ (ঐচ্ছিক)</small>
                    </div>

                    <div class="form-group-custom">
                        <i class="fa fa-list-ol input-icon"></i>
                        <select name="account_number" id="account_number_select" class="form-control-custom">
                            <option value="" disabled selected>কত নম্বর একাউন্ট নির্বাচন করুন *</option>
                            <option value="first">প্রথম একাউন্ট</option>
                            <option value="subsequent">দ্বিতীয় বা পরবর্তী একাউন্ট</option>
                        </select>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-3-partner" disabled>
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn-step-back" onclick="goToStep(3)">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 4: Referral eID ================= -->
                <div class="step-section" id="step-4" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <div class="step-title">রেফার eID লিখুন</div>
                    <div class="step-subtitle" id="referral_subtitle">আপনার রেফারকারী eID লিখুন</div>

                    <!-- Single Box for Customer & Virtual Agent -->
                    <div id="customer_referral_box">
                        <div class="form-group-custom">
                            <i class="fa fa-user-tag input-icon"></i>
                            <input type="text"
                                   name="referral_code_2"
                                   id="customer_referral_input"
                                   class="form-control-custom"
                                   placeholder="রেফার eID লিখুন *"
                                   value="{{ request('ref') ?? '' }}">
                            <small class="field-hint" id="customer_referral_hint">
                                (কারো রেফার না থাকলে, রেফার eID হিসেবে 134 সংখ্যাটি ব্যবহার করুন)
                            </small>
                        </div>
                    </div>

                    <!-- Dual Box for Partner -->
                    <div id="partner_referral_box" style="display: none;">
                        <div class="form-group-custom">
                            <i class="fa fa-user-friends input-icon"></i>
                            <input type="text"
                                   name="partner_referral_code_2"
                                   id="partner_referral_input"
                                   class="form-control-custom"
                                   placeholder="রেফার eID লিখুন *"
                                   value="{{ request('ref') ?? '' }}">
                            <small class="field-hint" id="partner_referral_hint">কার পরিচয়ে যুক্ত হচ্ছেন, তার eID লিখুন (13 দিয়ে শুরু) *</small>
                        </div>

                        <div class="form-group-custom">
                            <i class="fa fa-sitemap input-icon"></i>
                            <input type="text"
                                   name="referral_code"
                                   id="partner_placement_input"
                                   class="form-control-custom"
                                   placeholder="প্লেসমেন্ট eID লিখুন *"
                                   value="{{ request('ref') ?? '' }}">
                            <small class="field-hint" id="partner_placement_hint">যার নিচে একাউন্ট বসবে, তার eID লিখুন (13 দিয়ে শুরু) *</small>
                        </div>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-4" disabled>
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn-step-back" id="btn-back-4">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 5: Delivery Address ================= -->
                <div class="step-section" id="step-5" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-map-marker-alt"></i>
                    </div>
                    <div class="step-title">আপনার ঠিকানা নির্বাচন করুন</div>
                    <div class="step-subtitle">ডেলিভারির জন্য সঠিক ঠিকানা নির্বাচন করুন</div>

                    <!-- 1. Division -->
                    <div class="form-group-custom">
                        <i class="fa fa-map input-icon"></i>
                        <select name="division_id" id="division_select" class="form-control-custom">
                            <option value="" disabled selected>১. বিভাগ নির্বাচন করুন</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}">{{ $div->bn_name ?: $div->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. District -->
                    <div class="form-group-custom">
                        <i class="fa fa-city input-icon"></i>
                        <select name="district_id" id="district_select" class="form-control-custom" disabled>
                            <option value="" disabled selected>২. জেলা নির্বাচন করুন</option>
                        </select>
                    </div>

                    <!-- 3. Upazila / City Corp / Cantonment -->
                    <div class="form-group-custom">
                        <i class="fa fa-building input-icon"></i>
                        <select name="upazila_id" id="upazila_select" class="form-control-custom" disabled>
                            <option value="" disabled selected>৩. উপজেলা/সিটি কর্পোরেশন নির্বাচন করুন</option>
                        </select>
                    </div>

                    <!-- 4. Municipality / Union / Ward-Zone -->
                    <div class="form-group-custom" id="union_box">
                        <i class="fa fa-landmark input-icon" id="union_icon"></i>
                        <select name="union_name" id="union_select" class="form-control-custom" disabled>
                            <option value="" disabled selected>৪. পৌরসভা/ইউনিয়ন নির্বাচন করুন</option>
                        </select>
                    </div>

                    <!-- 5. Ward No -->
                    <div class="form-group-custom" id="ward_box">
                        <i class="fa fa-home input-icon"></i>
                        <select name="ward_no" id="ward_select" class="form-control-custom" disabled>
                            <option value="" disabled selected>৫. ওয়ার্ড নম্বর নির্বাচন করুন</option>
                        </select>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-5" disabled>
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn-step-back" onclick="goToStep(4)">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 6: Profile Image Upload (Optional) ================= -->
                <div class="step-section" id="step-6" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-camera"></i>
                    </div>
                    <div class="step-title">আপনার একটি ছবি আপলোড করুন (ঐচ্ছিক)</div>
                    <div class="step-subtitle">চাইলে এখন ছবি আপলোড করতে পারেন অথবা সরাসরি পরবর্তী ধাপে যেতে পারেন</div>

                    <input type="file"
                           name="profile_image"
                           id="profile_image_file"
                           accept="image/png, image/jpeg, image/jpg"
                           style="display: none;">

                    <div class="upload-dropzone" id="dropzone_box" onclick="document.getElementById('profile_image_file').click()">
                        <div class="upload-icon">
                            <i class="fa fa-cloud-upload-alt"></i>
                        </div>
                        <div class="upload-text">ছবি নির্বাচন করুন (ঐচ্ছিক)</div>
                        <div class="upload-hint">(JPG, PNG ফরম্যাট এবং সর্বোচ্চ 2MB পাসপোর্ট সাইজের ছবি ব্যবহার করুন)</div>
                    </div>

                    <div class="image-preview-wrapper" id="preview_wrapper">
                        <img id="image_preview_el" src="#" alt="Profile Preview">
                        <div>
                            <span class="change-img-btn" onclick="document.getElementById('profile_image_file').click()">
                                <i class="fa fa-sync-alt"></i> অন্য ছবি নির্বাচন করুন
                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-next-6">
                        <span>পরবর্তী ধাপে যান</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn-step-back" onclick="goToStep(5)">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 7: Password Setup ================= -->
                <div class="step-section" id="step-7" style="display: none;">
                    <div class="step-icon-wrapper">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="step-title">পাসওয়ার্ড সেট করুন</div>
                    <div class="step-subtitle">আপনার একাউন্ট নিরাপদ রাখতে শক্তিশালী পাসওয়ার্ড দিন</div>

                    <!-- Password Input -->
                    <div class="form-group-custom">
                        <i class="fa fa-lock input-icon"></i>
                        <input type="password"
                               name="password"
                               id="password_input"
                               class="form-control-custom"
                               placeholder="আপনার পাসওয়ার্ড লিখুন"
                               autocomplete="new-password">
                        <span class="input-toggle-pwd" onclick="togglePasswordVisibility('password_input', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="form-group-custom">
                        <i class="fa fa-lock input-icon"></i>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirm_input"
                               class="form-control-custom"
                               placeholder="কনফার্ম পাসওয়ার্ড লিখুন"
                               autocomplete="new-password">
                        <span class="input-toggle-pwd" onclick="togglePasswordVisibility('password_confirm_input', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <div class="info-note-box">
                        <i class="fa fa-exclamation-circle"></i>
                        <span id="password_requirement_text">পাসওয়ার্ড কমপক্ষে ৮ থেকে ৩২ বর্ণ/সংখ্যার মধ্যে হতে হবে।</span>
                    </div>

                    <button type="button" class="btn-step-next" id="btn-submit-account" disabled>
                        <span id="submit-btn-text">একাউন্ট তৈরি করুন</span>
                        <i class="fa fa-arrow-right" id="submit-btn-icon"></i>
                    </button>
                    <button type="button" class="btn-step-back" onclick="goToStep(6)">
                        <i class="fa fa-arrow-left"></i> আগের ধাপে যান
                    </button>
                </div>

                <!-- ================= STEP 8: Success Screen ================= -->
                <div class="step-section" id="step-8" style="display: none;">
                    <div class="success-celebration-box">
                        <div class="success-circle-icon">
                            <i class="fa fa-check"></i>
                        </div>
                        <div class="success-title">অভিনন্দন!</div>
                        <div class="success-message">
                            আপনার একাউন্ট সফলভাবে তৈরি হয়েছে!<br>
                            এখন থেকে আপনি ইত্তেবা শপের যেকোনো পণ্য কেনাকাটা করে ক্যাশব্যাক গ্রহণ ও রিওয়ার্ড পয়েন্ট অর্জন করতে পারবেন।
                        </div>

                        <a href="{{ route('website.index') }}" class="btn-home" id="btn_goto_home">
                            <i class="fa fa-home"></i> হোম পেজে যান ➔
                        </a>
                        <a href="{{ route('user.profile') }}" class="btn-profile-view" id="btn_goto_profile">
                            <i class="fa fa-user"></i> প্রোফাইল দেখুন/আপডেট করুন
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
    let currentStep = 1;
    let selectedAccountType = '';
    let isUsernameAvailable = false;
    let isEidValid = false;

    const stepTitles = {
        1: { num: '১', title: 'পুরো নাম লিখুন' },
        2: { num: '২', title: 'মোবাইল নাম্বার লিখুন' },
        3: { num: '৩', title: 'একাউন্ট নির্বাচন করুন' },
        '3-partner': { num: '৩.১', title: 'পার্টনার অতিরিক্ত তথ্য' },
        '3-customer': { num: '৩.১', title: 'কাস্টমার অতিরিক্ত তথ্য (ঐচ্ছিক)' },
        4: { num: '৪', title: 'রেফার eID লিখুন' },
        5: { num: '৫', title: 'ঠিকানা নির্বাচন করুন' },
        6: { num: '৬', title: 'ছবি আপলোড করুন (ঐচ্ছিক)' },
        7: { num: '৭', title: 'পাসওয়ার্ড সেট করুন' },
        8: { num: '৮', title: 'একাউন্ট সফলভাবে তৈরি' }
    };

    function updateStepPill(step) {
        const info = stepTitles[step] || { num: step, title: '' };
        $('#pillStepNum').text(info.num);
        $('#pillStepTitle').text(info.title);

        if (step === 8) {
            $('#stepPill').addClass('success-pill');
        } else {
            $('#stepPill').removeClass('success-pill');
        }

        // Update progress bar
        let stepIdx = (step === '3-partner' || step === '3-customer') ? 3 : step;
        let percentage = (stepIdx / 8) * 100;
        $('#progressFill').css('width', percentage + '%');

        $('.step-progress-dot').each(function() {
            let dotStep = parseInt($(this).data('step'));
            if (dotStep < stepIdx) {
                $(this).removeClass('active').addClass('completed');
            } else if (dotStep === stepIdx) {
                $(this).addClass('active').removeClass('completed');
            } else {
                $(this).removeClass('active completed');
            }
        });
    }

    function goToStep(step) {
        $('.step-section').hide();
        $('#step-' + step).show();
        currentStep = step;
        updateStepPill(step);
        $('#globalAlert').hide();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Password visibility toggle
    function togglePasswordVisibility(fieldId, iconEl) {
        const field = document.getElementById(fieldId);
        const icon = iconEl.querySelector('i');
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

    $(document).ready(function() {

        // ==================== STEP 1: Full Name ====================
        $('#name_input').on('input', function() {
            let val = $(this).val();
            let sanitized = val.replace(/[^a-zA-Z\u0980-\u09FF\s]/g, '');
            if (val !== sanitized) {
                $(this).val(sanitized);
                val = sanitized;
            }

            let trimmed = val.trim();
            if (trimmed.length >= 2) {
                $('#btn-next-1').prop('disabled', false);
                $('#name_hint').text('নামের সঠিক ফরম্যাট পাওয়া গেছে।').removeClass('error').addClass('success');
            } else {
                $('#btn-next-1').prop('disabled', true);
                if (val.length > 0) {
                    $('#name_hint').text('নাম কমপক্ষে ২টি অক্ষরের হতে হবে।').addClass('error').removeClass('success');
                } else {
                    $('#name_hint').text('কমপক্ষে ২টি বর্ণ লিখুন। কোনো সংখ্যা বা চিহ্ন লিখা যাবে না।').removeClass('error success');
                }
            }
        });

        $('#btn-next-1').on('click', function() {
            if ($('#name_input').val().trim().length >= 2) {
                goToStep(2);
                $('#phone_input').focus();
            }
        });

        // ==================== STEP 2: Mobile / Username ====================
        let usernameTimer = null;
        $('#phone_input').on('input', function() {
            let val = $(this).val();
            let sanitized = val.replace(/[^a-zA-Z0-9]/g, '');
            if (val !== sanitized) {
                $(this).val(sanitized);
                val = sanitized;
            }

            clearTimeout(usernameTimer);
            isUsernameAvailable = false;
            $('#btn-next-2').prop('disabled', true);

            if (val.length < 5) {
                $('#phone_hint').text('কমপক্ষে ৫টি সংখ্যা বা লেটার হতে হবে।').addClass('error').removeClass('success');
                return;
            }

            $('#phone_hint').text('ইউজার নেম যাচাই করা হচ্ছে...').removeClass('error success');

            usernameTimer = setTimeout(function() {
                $.ajax({
                    url: "{{ route('check.username') }}",
                    method: 'GET',
                    data: { username: val },
                    success: function(res) {
                        if (res.valid) {
                            isUsernameAvailable = true;
                            $('#phone_hint').text(res.message).removeClass('error').addClass('success');
                            $('#btn-next-2').prop('disabled', false);
                        } else {
                            isUsernameAvailable = false;
                            $('#phone_hint').text(res.message).addClass('error').removeClass('success');
                            $('#btn-next-2').prop('disabled', true);
                        }
                    },
                    error: function() {
                        $('#phone_hint').text('যাচাই করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।').addClass('error');
                    }
                });
            }, 300);
        });

        $('#btn-next-2').on('click', function() {
            if (isUsernameAvailable) {
                goToStep(3);
            }
        });

        // ==================== STEP 3: Account Selection ====================
        $('#customer_type_select').on('change', function() {
            selectedAccountType = $(this).val();
            if (selectedAccountType) {
                $('#btn-next-3').prop('disabled', false);
            } else {
                $('#btn-next-3').prop('disabled', true);
            }
        });

        $('#btn-next-3').on('click', function() {
            if (selectedAccountType === 'buy_earn') {
                goToStep('3-partner');
                checkPartnerExtraValidity();
            } else if (selectedAccountType === 'buy_only' || selectedAccountType === 'virtual_agent') {
                setupStep4ForCustomerOrVirtual();
                goToStep(4);
            }
        });

        // Step 3.1: Partner Extra Info (Mandatory)
        function checkPartnerExtraValidity() {
            let nid = $('#nid_input').val().trim();
            let dob = $('#dob_input').val();
            let father = $('#partner_father_name').val().trim();
            let mother = $('#partner_mother_name').val().trim();
            let accNum = $('#account_number_select').val();

            if (nid.length >= 10 && dob && father.length >= 2 && mother.length >= 2 && accNum) {
                $('#btn-next-3-partner').prop('disabled', false);
            } else {
                $('#btn-next-3-partner').prop('disabled', true);
            }
        }

        $('#nid_input').on('input', function() {
            let val = $(this).val();
            let sanitized = val.replace(/[^0-9]/g, '');
            if (val !== sanitized) {
                $(this).val(sanitized);
                val = sanitized;
            }
            if (val.length < 10 && val.length > 0) {
                $('#nid_hint').html('<span class="text-danger">NID নম্বর সর্বনিম্ন ১০ সংখ্যার হতে হবে (বর্তমান দৈর্ঘ্য: ' + val.length + ')</span>');
            } else {
                $('#nid_hint').html('শুধুমাত্র ইংরেজি সংখ্যা লিখুন (সর্বনিম্ন ১০ সংখ্যা) <span class="text-danger">*</span>');
            }
            checkPartnerExtraValidity();
        });

        $('#dob_input, #account_number_select').on('change', function() {
            checkPartnerExtraValidity();
        });

        $('#partner_father_name, #partner_mother_name').on('input', function() {
            checkPartnerExtraValidity();
        });

        $('#btn-next-3-partner').on('click', function() {
            setupStep4ForPartner();
            goToStep(4);
        });

        // Setup Step 4 based on Account Type
        function setupStep4ForCustomerOrVirtual() {
            $('#customer_referral_box').show();
            $('#partner_referral_box').hide();
            $('#referral_subtitle').text('আপনার রেফারকারী eID লিখুন');
            $('#btn-back-4').attr('onclick', "goToStep(3)");
            validateCustomerReferral();
        }

        function setupStep4ForPartner() {
            $('#customer_referral_box').hide();
            $('#partner_referral_box').show();
            $('#referral_subtitle').text('রেফার eID ও প্লেসমেন্ট eID প্রদান করুন');
            $('#btn-back-4').attr('onclick', "goToStep('3-partner')");
            validatePartnerReferral();
        }

        // ==================== STEP 4: Referral eID ====================
        let refTimer = null;

        function validateCustomerReferral() {
            let val = $('#customer_referral_input').val().trim();

            if (val.length === 0) {
                $('#customer_referral_hint').html('রেফার eID দেওয়া আবশ্যক। কারো রেফার না থাকলে <a href="javascript:void(0)" id="use_default_company_ref" style="color: #10b981; font-weight: 700; text-decoration: underline;">কোম্পানির রেফার 134 ব্যবহার করুন</a>').removeClass('error success');
                $('#btn-next-4').prop('disabled', true);
                return;
            }

            let sanitized = val.replace(/[^0-9]/g, '');
            if (val !== sanitized) {
                $('#customer_referral_input').val(sanitized);
                val = sanitized;
            }

            if (val.length < 2) {
                $('#customer_referral_hint').text('eID কমপক্ষে ৩ সংখ্যার হতে হবে এবং ১৩ দিয়ে শুরু হতে হবে।').addClass('error').removeClass('success');
                $('#btn-next-4').prop('disabled', true);
                return;
            }

            if (!val.startsWith('13')) {
                $('#customer_referral_hint').text('আপনি যে eID লিখেছেন, সেটি সঠিক ফরম্যাটের নয় (১৩ দিয়ে শুরু হতে হবে)।').addClass('error').removeClass('success');
                $('#btn-next-4').prop('disabled', true);
                return;
            }

            if (val.length >= 3) {
                $('#customer_referral_hint').text('eID যাচাই করা হচ্ছে...').removeClass('error success');
                clearTimeout(refTimer);
                refTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('check.eid') }}",
                        method: 'GET',
                        data: { eid: val, registering_type: selectedAccountType },
                        success: function(res) {
                            if (res.valid) {
                                $('#customer_referral_hint').text('যাচাইকৃত রেফারকারী: ' + res.name).removeClass('error').addClass('success');
                                $('#btn-next-4').prop('disabled', false);
                            } else {
                                $('#customer_referral_hint').text(res.message).addClass('error').removeClass('success');
                                $('#btn-next-4').prop('disabled', true);
                            }
                        },
                        error: function() {
                            $('#customer_referral_hint').text('eID যাচাই করতে সমস্যা হয়েছে।').addClass('error');
                            $('#btn-next-4').prop('disabled', true);
                        }
                    });
                }, 300);
            }
        }

        $(document).on('click', '#use_default_company_ref', function() {
            $('#customer_referral_input').val('134').trigger('input');
        });

        $('#customer_referral_input').on('input', function() {
            validateCustomerReferral();
        });

        // Partner Referral & Placement
        let partnerRefTimer = null;
        let partnerPlaceTimer = null;
        let isPartnerRefVerified = false;
        let isPartnerPlaceVerified = false;

        function checkPartnerNextBtn() {
            if (isPartnerRefVerified && isPartnerPlaceVerified) {
                $('#btn-next-4').prop('disabled', false);
            } else {
                $('#btn-next-4').prop('disabled', true);
            }
        }

        function validatePartnerReferralField() {
            let refVal = $('#partner_referral_input').val().trim();
            isPartnerRefVerified = false;
            checkPartnerNextBtn();

            if (refVal.length === 0) {
                $('#partner_referral_hint').text('কার পরিচয়ে যুক্ত হচ্ছেন, তার eID লিখুন (13 দিয়ে শুরু) *').removeClass('error success');
                return;
            }

            if (refVal.length >= 2 && !refVal.startsWith('13')) {
                $('#partner_referral_hint').text('আপনি যে eID লিখেছেন, সেটি সঠিক ফরম্যাটের নয় (১৩ দিয়ে শুরু হতে হবে)।').addClass('error').removeClass('success');
                return;
            }

            if (refVal.startsWith('13') && refVal.length >= 3) {
                $('#partner_referral_hint').text('রেফার eID যাচাই করা হচ্ছে...').removeClass('error success');
                clearTimeout(partnerRefTimer);
                partnerRefTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('check.eid') }}",
                        method: 'GET',
                        data: { eid: refVal, registering_type: 'buy_earn' },
                        success: function(res) {
                            if (res.valid) {
                                $('#partner_referral_hint').text('যাচাইকৃত রেফারকারী: ' + res.name).removeClass('error').addClass('success');
                                isPartnerRefVerified = true;
                            } else {
                                $('#partner_referral_hint').text(res.message).addClass('error').removeClass('success');
                                isPartnerRefVerified = false;
                            }
                            checkPartnerNextBtn();
                        },
                        error: function() {
                            $('#partner_referral_hint').text('eID যাচাই করতে সমস্যা হয়েছে।').addClass('error');
                            isPartnerRefVerified = false;
                            checkPartnerNextBtn();
                        }
                    });
                }, 300);
            }
        }

        function validatePartnerPlacementField() {
            let placeVal = $('#partner_placement_input').val().trim();
            isPartnerPlaceVerified = false;
            checkPartnerNextBtn();

            if (placeVal.length === 0) {
                $('#partner_placement_hint').text('যার নিচে একাউন্ট বসবে, তার eID লিখুন (13 দিয়ে শুরু) *').removeClass('error success');
                return;
            }

            if (placeVal.length >= 2 && !placeVal.startsWith('13')) {
                $('#partner_placement_hint').text('আপনি যে eID লিখেছেন, সেটি সঠিক ফরম্যাটের নয় (১৩ দিয়ে শুরু হতে হবে)।').addClass('error').removeClass('success');
                return;
            }

            if (placeVal.startsWith('13') && placeVal.length >= 3) {
                $('#partner_placement_hint').text('প্লেসমেন্ট eID যাচাই করা হচ্ছে...').removeClass('error success');
                clearTimeout(partnerPlaceTimer);
                partnerPlaceTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('check.eid') }}",
                        method: 'GET',
                        data: { eid: placeVal, registering_type: 'buy_earn' },
                        success: function(res) {
                            if (res.valid) {
                                $('#partner_placement_hint').text('যাচাইকৃত প্লেসমেন্ট: ' + res.name).removeClass('error').addClass('success');
                                isPartnerPlaceVerified = true;
                            } else {
                                $('#partner_placement_hint').text(res.message).addClass('error').removeClass('success');
                                isPartnerPlaceVerified = false;
                            }
                            checkPartnerNextBtn();
                        },
                        error: function() {
                            $('#partner_placement_hint').text('eID যাচাই করতে সমস্যা হয়েছে।').addClass('error');
                            isPartnerPlaceVerified = false;
                            checkPartnerNextBtn();
                        }
                    });
                }, 300);
            }
        }

        function validatePartnerReferral() {
            validatePartnerReferralField();
            validatePartnerPlacementField();
        }

        $('#partner_referral_input').on('input', function() {
            let val = $(this).val();
            let sanitized = val.replace(/[^0-9]/g, '');
            if (val !== sanitized) {
                $(this).val(sanitized);
            }
            validatePartnerReferralField();
        });

        $('#partner_placement_input').on('input', function() {
            let val = $(this).val();
            let sanitized = val.replace(/[^0-9]/g, '');
            if (val !== sanitized) {
                $(this).val(sanitized);
            }
            validatePartnerPlacementField();
        });

        $('#btn-next-4').on('click', function() {
            goToStep(5);
        });

        // ==================== STEP 5: Cascading Address ====================
        let currentSelectedUpazilaType = 'upazila';

        function resetUnionAndWard() {
            $('#union_select').html('<option value="" disabled selected>৪. পৌরসভা/ইউনিয়ন নির্বাচন করুন</option>').prop('disabled', true);
            $('#ward_select').html('<option value="" disabled selected>৫. ওয়ার্ড নম্বর নির্বাচন করুন</option>').prop('disabled', true);
            $('#ward_box').show();
        }

        $('#division_select').on('change', function() {
            let divId = $(this).val();
            $('#district_select').html('<option value="" disabled selected>লোড হচ্ছে...</option>').prop('disabled', true);
            $('#upazila_select').html('<option value="" disabled selected>৩. উপজেলা/সিটি কর্পোরেশন নির্বাচন করুন</option>').prop('disabled', true);
            resetUnionAndWard();

            $.get("{{ url('/api/geo/districts') }}/" + divId, function(data) {
                let options = '<option value="" disabled selected>২. জেলা নির্বাচন করুন</option>';
                $.each(data, function(i, dist) {
                    options += '<option value="' + dist.id + '">' + (dist.bn_name || dist.name) + '</option>';
                });
                $('#district_select').html(options).prop('disabled', false);
                checkAddressValidity();
            });
        });

        $('#district_select').on('change', function() {
            let distId = $(this).val();
            $('#upazila_select').html('<option value="" disabled selected>লোড হচ্ছে...</option>').prop('disabled', true);
            resetUnionAndWard();

            $.get("{{ url('/api/geo/upazilas') }}/" + distId, function(data) {
                let options = '<option value="" disabled selected>৩. উপজেলা/সিটি কর্পোরেশন নির্বাচন করুন</option>';
                $.each(data, function(i, upz) {
                    let type = upz.type || 'upazila';
                    let typeLabel = '';
                    if (type === 'city_corporation') typeLabel = ' (সিটি কর্পোরেশন)';
                    else if (type === 'cantonment') typeLabel = ' (সেনানিবাস)';
                    options += '<option value="' + upz.id + '" data-type="' + type + '" data-wards="' + (upz.ward_count || 0) + '">' + (upz.bn_name || upz.name) + typeLabel + '</option>';
                });
                $('#upazila_select').html(options).prop('disabled', false);
                checkAddressValidity();
            });
        });

        $('#upazila_select').on('change', function() {
            let upzId = $(this).val();
            let selectedOpt = $(this).find('option:selected');
            currentSelectedUpazilaType = selectedOpt.data('type') || 'upazila';
            let wardCount = parseInt(selectedOpt.data('wards')) || 0;

            if (currentSelectedUpazilaType === 'city_corporation') {
                $('#union_icon').attr('class', 'fa fa-city input-icon');
                $('#union_select').html('<option value="" disabled selected>লোড হচ্ছে...</option>').prop('disabled', true);
                $('#ward_box').hide();

                $.get("{{ url('/api/geo/unions') }}/" + upzId, function(data) {
                    let options = '<option value="" disabled selected>৪. ওয়ার্ড/অঞ্চল নির্বাচন করুন *</option>';
                    if (data && data.length > 0) {
                        $.each(data, function(i, u) {
                            options += '<option value="' + (u.bn_name || u.name) + '">' + (u.bn_name || u.name) + '</option>';
                        });
                    } else {
                        let maxWards = wardCount > 0 ? wardCount : 54;
                        for (let w = 1; w <= maxWards; w++) {
                            let wStr = (w < 10 ? '০' : '') + w.toString().replace(/\d/g, d => '০১২৩৪৫৬৭৮৯'[d]);
                            options += '<option value="ওয়ার্ড নং ' + wStr + '">ওয়ার্ড নং ' + wStr + '</option>';
                        }
                    }
                    $('#union_select').html(options).prop('disabled', false);
                    checkAddressValidity();
                });
            } else {
                $('#union_icon').attr('class', 'fa fa-landmark input-icon');
                $('#union_select').html('<option value="" disabled selected>লোড হচ্ছে...</option>').prop('disabled', true);
                $('#ward_select').html('<option value="" disabled selected>৫. ওয়ার্ড নম্বর নির্বাচন করুন</option>').prop('disabled', true);
                $('#ward_box').show();

                $.get("{{ url('/api/geo/unions') }}/" + upzId, function(data) {
                    let options = '<option value="" disabled selected>৪. পৌরসভা/ইউনিয়ন নির্বাচন করুন *</option>';
                    if (data && data.length > 0) {
                        $.each(data, function(i, u) {
                            let uType = u.type === 'municipality' ? ' (পৌরসভা)' : ' (ইউনিয়ন)';
                            options += '<option value="' + (u.bn_name || u.name) + '" data-wards="' + (u.ward_count || 9) + '">' + (u.bn_name || u.name) + uType + '</option>';
                        });
                    } else {
                        options += '<option value="সদর ইউনিয়ন/পৌরসভা" data-wards="9">সদর ইউনিয়ন/পৌরসভা</option>';
                    }
                    $('#union_select').html(options).prop('disabled', false);
                    checkAddressValidity();
                });
            }
        });

        $('#union_select').on('change', function() {
            let selectedOpt = $(this).find('option:selected');
            let unionVal = $(this).val();

            if (currentSelectedUpazilaType === 'city_corporation') {
                $('#ward_select').html('<option value="' + unionVal + '" selected>' + unionVal + '</option>');
            } else {
                let wards = parseInt(selectedOpt.data('wards')) || 9;
                let wardOptions = '<option value="" disabled selected>৫. ওয়ার্ড নম্বর নির্বাচন করুন *</option>';
                for (let w = 1; w <= wards; w++) {
                    let bnNum = w.toString().replace(/\d/g, d => '০১২৩৪৫৬৭৮৯'[d]);
                    wardOptions += '<option value="' + bnNum + ' নম্বর ওয়ার্ড">' + bnNum + ' নম্বর ওয়ার্ড</option>';
                }
                $('#ward_select').html(wardOptions).prop('disabled', false);
            }
            checkAddressValidity();
        });

        $('#ward_select').on('change', function() {
            checkAddressValidity();
        });

        function checkAddressValidity() {
            let div = $('#division_select').val();
            let dist = $('#district_select').val();
            let upz = $('#upazila_select').val();
            let union = $('#union_select').val();
            let ward = $('#ward_select').val();

            if (div && dist && upz && union && (currentSelectedUpazilaType === 'city_corporation' || ward)) {
                $('#btn-next-5').prop('disabled', false);
            } else {
                $('#btn-next-5').prop('disabled', true);
            }
        }

        $('#btn-next-5').on('click', function() {
            goToStep(6);
        });

        // ==================== STEP 6: Profile Image (Optional) ====================
        let isImageUploaded = false;

        $('#profile_image_file').on('change', function(e) {
            let file = e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('ছবির আকার সর্বোচ্চ 2MB হতে পারবে। অনুগ্রহ করে ছোট ছবি নির্বাচন করুন।');
                $(this).val('');
                return;
            }

            let reader = new FileReader();
            reader.onload = function(event) {
                let img = new Image();
                img.onload = function() {
                    let canvas = document.createElement('canvas');
                    let maxDimension = 600;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxDimension) {
                            height = Math.round((height * maxDimension) / width);
                            width = maxDimension;
                        }
                    } else {
                        if (height > maxDimension) {
                            width = Math.round((width * maxDimension) / height);
                            height = maxDimension;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    let compressedDataUrl = canvas.toDataURL('image/jpeg', 0.75);

                    $('#image_preview_el').attr('src', compressedDataUrl);
                    $('#dropzone_box').hide();
                    $('#preview_wrapper').show();

                    $('#profile_image_base64').val(compressedDataUrl);

                    isImageUploaded = true;
                    $('#btn-next-6').prop('disabled', false);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        $('#btn-next-6').on('click', function() {
            goToStep(7);
        });

        // ==================== STEP 7: Password Setup ====================
        function checkPasswordValidity() {
            let pwd = $('#password_input').val();
            let confirmPwd = $('#password_confirm_input').val();

            if (pwd.length < 8 || pwd.length > 32) {
                $('#password_requirement_text').text('পাসওয়ার্ড কমপক্ষে ৮ থেকে ৩২ বর্ণ/সংখ্যার মধ্যে হতে হবে।').css('color', '#991b1b');
                $('#btn-submit-account').prop('disabled', true);
                return;
            }

            if (confirmPwd.length > 0 && pwd !== confirmPwd) {
                $('#password_requirement_text').text('পাসওয়ার্ড দুটি মেলেনি।').css('color', '#dc2626');
                $('#btn-submit-account').prop('disabled', true);
                return;
            }

            if (pwd === confirmPwd && pwd.length >= 8 && pwd.length <= 32) {
                $('#password_requirement_text').text('পাসওয়ার্ড সঠিক হয়েছে।').css('color', '#16a34a');
                $('#btn-submit-account').prop('disabled', false);
            } else {
                $('#btn-submit-account').prop('disabled', true);
            }
        }

        $('#password_input, #password_confirm_input').on('input', function() {
            checkPasswordValidity();
        });

        // ==================== FINAL FORM SUBMIT ====================
        $('#btn-submit-account').on('click', function(e) {
            e.preventDefault();

            let submitBtn = $(this);
            submitBtn.prop('disabled', true);
            $('#submit-btn-text').text('একাউন্ট তৈরি হচ্ছে...');
            $('#submit-btn-icon').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin');
            $('#globalAlert').hide();

            let formElement = document.getElementById('multiStepForm');
            let formData = new FormData(formElement);

            formData.set('customer_type', selectedAccountType);

            if (selectedAccountType === 'buy_earn') {
                formData.set('referral_code_2', $('#partner_referral_input').val().trim());
                formData.set('referral_code', $('#partner_placement_input').val().trim());
                formData.set('father_name', $('#partner_father_name').val().trim());
                formData.set('mother_name', $('#partner_mother_name').val().trim());
                formData.set('blood_group', $('#partner_blood_group').val());
                formData.set('nid', $('#nid_input').val().trim());
                formData.set('date_of_birth', $('#dob_input').val());
                formData.set('account_number', $('#account_number_select').val());
            } else {
                let custRef = $('#customer_referral_input').val().trim() || '134';
                formData.set('referral_code', custRef);
                formData.set('referral_code_2', custRef);
            }

            formData.set('division_id', $('#division_select').val());
            formData.set('district_id', $('#district_select').val());
            formData.set('upazila_id', $('#upazila_select').val());
            formData.set('union_name', $('#union_select').val());
            let finalWard = (currentSelectedUpazilaType === 'city_corporation') ? $('#union_select').val() : $('#ward_select').val();
            formData.set('ward_no', finalWard);

            $.ajax({
                url: "{{ route('register') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        goToStep(8);
                        if (response.home_url) {
                            $('#btn_goto_home').attr('href', response.home_url);
                        }
                        if (response.profile_url) {
                            $('#btn_goto_profile').attr('href', response.profile_url);
                        }
                    } else {
                        $('#globalAlert').text(response.message || 'একটি ত্রুটি ঘটেছে।').show();
                        submitBtn.prop('disabled', false);
                        $('#submit-btn-text').text('একাউন্ট তৈরি করুন');
                        $('#submit-btn-icon').removeClass('fa-spinner fa-spin').addClass('fa-arrow-right');
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false);
                    $('#submit-btn-text').text('একাউন্ট তৈরি করুন');
                    $('#submit-btn-icon').removeClass('fa-spinner fa-spin').addClass('fa-arrow-right');

                    let errorMsg = 'রেজিস্ট্রেশন সম্পন্ন করতে সমস্যা হয়েছে।';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            let firstKey = Object.keys(xhr.responseJSON.errors)[0];
                            errorMsg = xhr.responseJSON.errors[firstKey][0];
                        }
                    }
                    $('#globalAlert').text(errorMsg).show();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

    });
</script>
@endsection
