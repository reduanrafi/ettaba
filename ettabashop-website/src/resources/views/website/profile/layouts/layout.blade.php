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
    {{--    <link rel="stylesheet" href="{{asset('assets/website/')}}">--}}
<!-- Font Awesome -->

    <link rel="apple-touch-icon" href="{{ asset('assets/website/img/apple-icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo/favicon.ico')}}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/website/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/website/css/style.css') }}" rel="stylesheet">
    
    <!-- Ultra-Modern Profile Dashboard Styles -->
    <style>
    :root {
        --dash-primary: #2563eb;
        --dash-primary-dark: #1d4ed8;
        --dash-primary-gradient: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
        --dash-hero-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #4f46e5 100%);
        --dash-surface: #ffffff;
        --dash-bg: #f8fafc;
        --dash-border: #e2e8f0;
        --dash-text-main: #0f172a;
        --dash-text-muted: #64748b;
        --dash-emerald: #10b981;
        --dash-amber: #f59e0b;
        --dash-indigo: #6366f1;
        --dash-purple: #8b5cf6;
        --dash-rose: #f43f5e;
        --dash-radius: 18px;
        --dash-radius-sm: 12px;
        --dash-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
        --dash-shadow-lg: 0 20px 35px -10px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    body {
        background-color: var(--dash-bg) !important;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        color: var(--dash-text-main);
        overflow-x: hidden;
    }

    /* Modern Top Navigation Bar */
    .profile-topbar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--dash-border);
        padding: 12px 24px;
        position: sticky;
        top: 0;
        z-index: 1020;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }
    .profile-topbar .brand-logo {
        height: 42px;
        transition: transform 0.2s;
    }
    .profile-topbar .brand-logo:hover {
        transform: scale(1.02);
    }
    .topbar-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 13.5px;
        text-decoration: none !important;
        transition: all 0.25s ease;
    }
    .topbar-btn-home {
        background: #eff6ff;
        color: var(--dash-primary);
        border: 1px solid #bfdbfe;
    }
    .topbar-btn-home:hover {
        background: var(--dash-primary);
        color: #fff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .topbar-btn-logout {
        background: #fef2f2;
        color: var(--dash-rose);
        border: 1px solid #fecaca;
    }
    .topbar-btn-logout:hover {
        background: var(--dash-rose);
        color: #fff;
    }

    /* Hero Banner */
    .dash-hero-banner {
        background: var(--dash-hero-gradient);
        border-radius: var(--dash-radius);
        padding: 28px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: var(--dash-shadow-lg);
    }
    .dash-hero-banner::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .dash-hero-banner::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .dash-hero-content {
        display: flex;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }
    .hero-avatar-wrap {
        position: relative;
        width: 84px;
        height: 84px;
        flex-shrink: 0;
    }
    .hero-avatar-img {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        object-fit: cover;
        border: 3.5px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: #fff;
    }
    .hero-avatar-badge {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 20px;
        height: 20px;
        background: #10b981;
        border: 2.5px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
    }
    .hero-user-details h2 {
        color: #ffffff;
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 6px 0;
        letter-spacing: -0.3px;
    }
    .hero-badges-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .dash-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 12.5px;
        font-weight: 500;
        backdrop-filter: blur(8px);
    }
    .dash-badge-partner {
        background: rgba(245, 158, 11, 0.25);
        border: 1px solid rgba(245, 158, 11, 0.6);
        color: #fef3c7;
    }
    .dash-badge-customer {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
    }
    .dash-badge-eid {
        background: rgba(0, 0, 0, 0.22);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    .dash-badge-eid:hover {
        background: rgba(0, 0, 0, 0.38);
        border-color: #fff;
        transform: translateY(-1px);
    }
    .dash-badge-verified {
        background: rgba(16, 185, 129, 0.25);
        border: 1px solid rgba(16, 185, 129, 0.5);
        color: #a7f3d0;
    }

    /* Stat Metric Cards Grid */
    .dash-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }
    @media (max-width: 991px) {
        .dash-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }
    @media (max-width: 575px) {
        .dash-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }
    .dash-stat-card {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius-sm);
        padding: 18px;
        box-shadow: var(--dash-shadow);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .dash-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .stat-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .stat-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .icon-emerald { background: #ecfdf5; color: var(--dash-emerald); }
    .icon-indigo  { background: #eef2ff; color: var(--dash-indigo); }
    .icon-amber   { background: #fffbeb; color: var(--dash-amber); }
    .icon-teal    { background: #f0fdfa; color: #0d9488; }

    .stat-title {
        font-size: 13px;
        font-weight: 500;
        color: var(--dash-text-muted);
        margin: 0;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--dash-text-main);
        margin: 2px 0 0 0;
        letter-spacing: -0.5px;
    }
    .stat-action-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        color: var(--dash-primary);
        text-decoration: none !important;
        margin-top: 10px;
        transition: gap 0.2s;
    }
    .stat-action-link:hover {
        color: var(--dash-primary-dark);
        gap: 7px;
    }

    /* Mobile Quick Navigation Bar (Horizontal Scroll) */
    .mobile-quick-nav-wrap {
        display: none;
        margin-bottom: 18px;
    }
    @media (max-width: 991px) {
        .mobile-quick-nav-wrap {
            display: block;
        }
    }
    .mobile-quick-nav-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 6px 2px 10px 2px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .mobile-quick-nav-scroll::-webkit-scrollbar {
        display: none;
    }
    .m-nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
        text-decoration: none !important;
        background: #ffffff;
        color: var(--dash-text-muted);
        border: 1px solid var(--dash-border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .m-nav-pill.active {
        background: var(--dash-primary-gradient);
        color: #ffffff !important;
        border-color: transparent;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Desktop Sidebar Menu */
    .dash-sidebar-card {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        padding: 16px;
        box-shadow: var(--dash-shadow);
        margin-bottom: 24px;
    }
    .dash-menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .dash-menu-item {
        margin-bottom: 4px;
    }
    .dash-menu-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: var(--dash-radius-sm);
        color: var(--dash-gray-700);
        text-decoration: none !important;
        font-size: 13.5px;
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .dash-menu-link .link-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .dash-menu-link .menu-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .dash-menu-link:hover {
        background: #f8fafc;
        color: var(--dash-primary);
        transform: translateX(3px);
    }
    .dash-menu-link:hover .menu-icon {
        background: #eff6ff;
        color: var(--dash-primary);
    }
    .dash-menu-link.active {
        background: var(--dash-primary-gradient);
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }
    .dash-menu-link.active .menu-icon {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Form Settings Redesign */
    .dash-settings-card {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        padding: 28px;
        box-shadow: var(--dash-shadow);
        margin-bottom: 24px;
    }
    @media (max-width: 575px) {
        .dash-settings-card {
            padding: 18px 14px;
        }
    }
    .settings-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--dash-border);
        margin-bottom: 24px;
    }
    .settings-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #eff6ff;
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .settings-section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dash-text-main);
        margin: 0;
    }
    .settings-section-subtitle {
        font-size: 13px;
        color: var(--dash-text-muted);
        margin: 2px 0 0 0;
    }

    /* Form Group Card Sections */
    .form-card-block {
        background: #f8fafc;
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius-sm);
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-card-title i {
        color: var(--dash-primary);
    }

    .input-label-modern {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .input-label-badge {
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        background: #e2e8f0;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .input-label-badge.locked {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .dash-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .dash-input-icon {
        position: absolute;
        left: 14px;
        font-size: 14px;
        color: #94a3b8;
        pointer-events: none;
        z-index: 2;
    }
    .dash-control {
        width: 100%;
        height: 48px;
        padding: 10px 14px 10px 42px;
        font-size: 14px;
        border: 1.5px solid var(--dash-border);
        border-radius: 10px;
        background: #ffffff;
        color: var(--dash-text-main);
        transition: all 0.2s ease;
        outline: none;
    }
    .dash-control:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        background: #ffffff;
    }
    .dash-control[readonly] {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }
    .dash-textarea {
        width: 100%;
        min-height: 85px;
        padding: 12px 14px 12px 42px;
        font-size: 14px;
        border: 1.5px solid var(--dash-border);
        border-radius: 10px;
        background: #ffffff;
        color: var(--dash-text-main);
        transition: all 0.2s ease;
        resize: vertical;
        outline: none;
    }
    .dash-textarea:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Avatar Upload Preview Block */
    .avatar-upload-block {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .avatar-preview-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .avatar-upload-btn-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .btn-upload-avatar {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 10px;
        background: #eff6ff;
        color: var(--dash-primary);
        border: 1.5px dashed #93c5fd;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-upload-avatar:hover {
        background: #dbeafe;
        border-color: var(--dash-primary);
    }

    /* Save Submit Button */
    .btn-submit-profile {
        width: 100%;
        height: 52px;
        background: var(--dash-primary-gradient);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.25);
        transition: all 0.25s ease;
    }
    .btn-submit-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
        color: #fff;
    }
    .btn-submit-profile:active {
        transform: translateY(0);
    }

    /* Mobile Fixed Bottom Nav */
    .mobile-bottom-nav {
        display: none;
    }
    @media (max-width: 991px) {
        .mobile-bottom-nav {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 62px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--dash-border);
            z-index: 1050;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
            justify-content: space-around;
            align-items: center;
            padding: 0 4px;
        }
        body {
            padding-bottom: 70px !important;
        }
    }
    .b-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        text-decoration: none !important;
        color: #64748b;
        font-size: 10.5px;
        font-weight: 500;
        flex: 1;
        height: 100%;
        transition: all 0.2s;
    }
    .b-nav-item i {
        font-size: 18px;
        transition: all 0.2s;
    }
    .b-nav-item.active {
        color: var(--dash-primary);
        font-weight: 700;
    }
    .b-nav-item.active i {
        transform: translateY(-2px);
        color: var(--dash-primary);
    }

    /* Toast Notification */
    .dash-toast {
        position: fixed;
        bottom: 80px;
        right: 20px;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 50px;
        font-size: 13.5px;
        font-weight: 500;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: none;
        z-index: 2000;
        animation: fadeInToast 0.3s ease;
    }
    @keyframes fadeInToast {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Unified Card, Table & Badge Styling for all profile tabs */
    .card {
        background: var(--dash-surface) !important;
        border: 1px solid var(--dash-border) !important;
        border-radius: var(--dash-radius) !important;
        box-shadow: var(--dash-shadow) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .card-header {
        background: #ffffff !important;
        border-bottom: 1px solid var(--dash-border) !important;
        padding: 16px 20px !important;
    }
    .card-header .nav-pills .nav-link {
        border-radius: 50px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        padding: 8px 18px !important;
        color: var(--dash-text-muted) !important;
        transition: all 0.2s;
    }
    .card-header .nav-pills .nav-link.active {
        background: var(--dash-primary-gradient) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
    }
    .card-body {
        padding: 24px !important;
    }
    @media (max-width: 575px) {
        .card-body {
            padding: 16px !important;
        }
    }
    .table thead th {
        border-top: none !important;
        border-bottom: 2px solid var(--dash-border) !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: var(--dash-text-muted) !important;
        background: #f8fafc !important;
        padding: 14px 16px !important;
    }
    .table td {
        vertical-align: middle !important;
        border-top: 1px solid var(--dash-border) !important;
        font-size: 13.5px !important;
        padding: 14px 16px !important;
    }
    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }
    .badge {
        padding: 6px 12px !important;
        font-weight: 600 !important;
        border-radius: 50px !important;
    }
    </style>
    @yield('extra-style')
</head>

<body class="">
<div id="app">
{{--    @include('website.layouts.includes.navigation')--}}


<!-- All Dynamic Content Placed Here -->
    @section('content') @show


    @include('website.layouts.includes.footer')
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/website/lib/easing/easing.min.js')}}"></script>
<script src="{{ asset('assets/website/lib/owlcarousel/owl.carousel.min.js')}}"></script>

<!-- Contact Javascript File -->
<script src="{{ asset('assets/website/mail/jqBootstrapValidation.min.js')}}"></script>
<script src="{{ asset('assets/website/mail/contact.js')}}"></script>

<!-- Template Javascript -->
<script src="{{ asset('assets/website/js/main.js')}}"></script>
<script src="https://unpkg.com/vue@next"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>

{{--<script>--}}
{{--    const app = {--}}

{{--        data() {--}}
{{--            return {--}}

{{--            }--}}

{{--        },--}}
{{--        methods: {--}}

{{--        },--}}
{{--        mounted: function () {--}}


{{--        },--}}
{{--        computed: {--}}

{{--        },--}}

{{--    };--}}

{{--    Vue.createApp(app).mount('#app')--}}
{{--</script>--}}
@yield('extra-script')

<div class="modal fade" id="virtualBalanceModal"  tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add virtual balance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('profile.virtualBalanceSave')}}" method="post" enctype="multipart/form-data">
                    @csrf

                        <div class="form-group">
                            <label for="amount">পরিমান</label>
                            <input type="text" name="amount" class="form-control" id="amount"
                                   placeholder="কত টাকা পাঠিয়েছেন লিখুন">
                        </div>
                        <div class="form-group">
                            <label for="transaction_code">বিকাশ অথবা নগদ ট্রান্সেকশন আইডি</label>
                            <input type="text" name="transaction_code" class="form-control"
                                   id="transaction_code" placeholder="TrxID:BHK1T....">
                        </div>

                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::user()->id }}">
                        <input type="hidden" name="status" value="incoming">

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Fixed Bottom Navigation Bar -->
<div class="mobile-bottom-nav">
    <a href="{{ route('website.index') }}" class="b-nav-item">
        <i class="fas fa-home"></i>
        <span>হোম</span>
    </a>
    <a href="{{ route('profile.order') }}" class="b-nav-item {{ Request::is('profile/orders') ? 'active' : '' }}">
        <i class="fas fa-shopping-bag"></i>
        <span>অর্ডার</span>
    </a>
    <a href="{{ route('user.profile') }}" class="b-nav-item {{ Request::is('profile/index') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>প্রোফাইল</span>
    </a>
    <a href="{{ route('profile.withdrawRequests') }}" class="b-nav-item {{ Request::is('profile/withdraw-requests') || Request::is('profile/withdraw-histories') ? 'active' : '' }}">
        <i class="fas fa-wallet"></i>
        <span>উত্তোলন</span>
    </a>
    <a href="{{ route('profile.teamTree') }}" class="b-nav-item {{ Request::is('profile/team-tree') ? 'active' : '' }}">
        <i class="fas fa-sitemap"></i>
        <span>টিম ট্রি</span>
    </a>
</div>

<!-- Copy eID Toast -->
<div id="dashToast" class="dash-toast">
    <i class="fas fa-check-circle text-success mr-2"></i> <span id="toastMsg">eID কপি করা হয়েছে!</span>
</div>

<script>
function copyEid(code) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(() => showToast('eID (' + code + ') সফলভাবে কপি হয়েছে!'));
    } else {
        let tempInput = document.createElement("input");
        tempInput.value = code;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        showToast('eID (' + code + ') সফলভাবে কপি হয়েছে!');
    }
}
function showToast(msg) {
    let t = document.getElementById('dashToast');
    let m = document.getElementById('toastMsg');
    if (m) m.innerText = msg;
    if (t) {
        t.style.display = 'block';
        setTimeout(() => { t.style.display = 'none'; }, 2500);
    }
}
</script>
</body>
</html>
