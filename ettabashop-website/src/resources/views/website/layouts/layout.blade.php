<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile App Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#15803d">

    @yield('extra-meta')
    <meta name="base-url" content="{{ url('/') }}">
    <title>ইত্তেবা শপ — পারিবারিক খরচ হোক সাশ্রয়ী | Ettaba Shop</title>

    <link rel="apple-touch-icon" href="{{ asset('assets/website/img/apple-icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logo/favicon.ico') }}">

    <!-- Fonts: Hind Siliguri (Bangla) + Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('assets/website/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/website/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ettaba-modern-v2.css') }}?v={{ time() }}" rel="stylesheet">

    <style>
        :root {
            --ettaba-green: #15803d;
            --ettaba-green-dark: #166534;
            --ettaba-green-light: #dcfce7;
            --ettaba-red: #dc2626;
            --ettaba-red-dark: #b91c1c;
            --ettaba-red-light: #fef2f2;
            --primary: #15803d;
            --secondary: #dc2626;
            --surface: #ffffff;
            --background: #f4f7f5;
        }

         body {
             font-family: 'Hind Siliguri', 'Poppins', sans-serif !important;
             background-color: #f4f7f5 !important;
             color: #0f172a;
             padding-bottom: 82px !important;
         }

         .ettaba-page-shell {
             max-width: 1180px;
             margin: 0 auto;
             padding-left: 10px;
             padding-right: 10px;
         }

         .ettaba-sec-header {
             display: flex;
             align-items: center;
             justify-content: space-between;
             margin-bottom: 10px;
             padding-bottom: 4px;
         }

         .ettaba-sec-title {
             font-size: 18px;
             font-weight: 800;
             color: #0f172a;
             margin: 0;
             display: flex;
             align-items: center;
             gap: 8px;
         }

         .ettaba-see-all-btn {
             display: inline-flex;
             align-items: center;
             gap: 5px;
             background: #ffffff;
             border: 1px solid #cbd5e1;
             color: #15803d !important;
             border-radius: 50px;
             padding: 4px 14px;
             font-size: 12.5px;
             font-weight: 700;
             text-decoration: none !important;
             box-shadow: 0 1px 3px rgba(0,0,0,0.04);
             transition: all 0.2s;
             white-space: nowrap;
         }

         .ettaba-see-all-btn:hover {
             background: #f0fdf4;
             border-color: #15803d;
         }

         /* ================= HOMEPAGE & GLOBAL COMPONENTS ================= */
         /* Guest Registration Card (Section 3.2 - Compact 2-Column Layout) */
         .ettaba-guest-icon-wrap {
             width: 28px;
             height: 28px;
             min-width: 28px;
             border-radius: 50%;
             background: #dcfce7;
             color: #15803d;
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 13px;
             margin-top: 1px;
         }

         .ettaba-guest-card-icon {
             width: 28px;
             height: 28px;
             min-width: 28px;
             border-radius: 7px;
             background: #dcfce7;
             color: #15803d;
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 13px;
             margin-top: 1px;
         }

         .ettaba-guest-heading {
             font-size: 12.5px;
             font-weight: 800;
             color: #b91c1c;
             line-height: 1.2;
         }

         .ettaba-guest-subtext {
             font-size: 11px;
             font-weight: 600;
             color: #1e293b;
             line-height: 1.25;
         }

         .ettaba-guest-bullets li {
             font-size: 10.5px;
             font-weight: 700;
             color: #1e293b;
             line-height: 1.3;
         }

         .ettaba-guest-login-btn {
             display: block;
             background: #15803d;
             color: #ffffff !important;
             border-radius: 8px;
             padding: 6px 6px;
             box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);
         }

         .ettaba-guest-login-btn .login-main-txt {
             font-size: 12px;
             font-weight: 800;
             color: #ffffff;
         }

         .ettaba-guest-login-btn .login-sub-txt {
             font-size: 9.5px;
             font-weight: 600;
             color: #dcfce7;
         }

         .ettaba-guest-reg-btn {
             display: block;
             background: #ffffff;
             border: 1.5px solid #15803d;
             border-radius: 8px;
             padding: 5px 6px;
             color: #0f172a !important;
         }

         .ettaba-guest-reg-btn .reg-main-txt {
             font-size: 11px;
             font-weight: 800;
             color: #0f172a;
         }

         .ettaba-guest-reg-btn .reg-sub-txt {
             font-size: 9.5px;
             font-weight: 700;
             color: #15803d;
         }

         @media (max-width: 575.98px) {
             .ettaba-guest-icon-wrap,
             .ettaba-guest-card-icon {
                 width: 24px;
                 height: 24px;
                 min-width: 24px;
                 font-size: 11px;
             }
             .ettaba-guest-heading { font-size: 11.5px; }
             .ettaba-guest-subtext { font-size: 10px; }
             .ettaba-guest-bullets li { font-size: 9.5px; line-height: 1.25; }
             .ettaba-guest-login-btn { padding: 5px 4px; }
             .ettaba-guest-login-btn .login-main-txt { font-size: 11px; }
             .ettaba-guest-login-btn .login-sub-txt { font-size: 8.5px; }
             .ettaba-guest-reg-btn { padding: 4px 4px; }
             .ettaba-guest-reg-btn .reg-main-txt { font-size: 10px; }
             .ettaba-guest-reg-btn .reg-sub-txt { font-size: 8.5px; }
         }

         /* Logged-in Next Reward Card (Section 5) */
         .ettaba-reward-card-heading {
             font-size: 16.5px;
             font-weight: 800;
             color: #991b1b;
             white-space: nowrap;
         }

         @media (max-width: 575.98px) {
             .ettaba-reward-card-heading {
                 font-size: 15px;
             }
         }

         .ettaba-reward-body-link {
             cursor: pointer;
             border-radius: 10px;
             transition: background-color 0.15s ease;
         }

         .ettaba-reward-body-link:hover {
             background-color: #f8fafc;
         }

         .ettaba-reward-all-btn {
             background: #f0fdf4;
             border: 1px solid #86efac;
             color: #15803d !important;
             border-radius: 50px;
             padding: 3px 12px;
             font-size: 11.5px;
             font-weight: 700;
             white-space: nowrap;
         }

         .ettaba-reward-gift-thumb {
             width: 58px;
             height: 58px;
             min-width: 58px;
             border-radius: 12px;
             overflow: hidden;
             display: flex;
             align-items: center;
             justify-content: center;
             background: #fff1f2;
             border: 1px solid #fecdd3;
         }

         .ettaba-reward-gift-thumb img {
             width: 100%;
             height: 100%;
             object-fit: cover;
         }

         .ettaba-reward-gift-fallback {
             font-size: 28px;
             color: #dc2626;
         }

         .ettaba-rw-lbl {
             font-size: 10.5px;
             font-weight: 700;
             color: #475569;
         }

         .ettaba-rw-val-red {
             font-size: 15px;
             font-weight: 800;
             color: #dc2626;
         }

         .ettaba-rw-val-dark {
             font-size: 13.5px;
             font-weight: 800;
             color: #0f172a;
         }

         .ettaba-rw-val-green {
             font-size: 13.5px;
             font-weight: 800;
             color: #15803d;
             line-height: 1.1;
         }

         .ettaba-rw-subname {
             font-size: 10px;
             color: #64748b;
             font-weight: 600;
         }

         /* Online Categories 2-Row × 4-Column Grid (Section 6) */
         .ettaba-cat-line {
             flex: 1;
             height: 1px;
             max-width: 90px;
             background: linear-gradient(90deg, rgba(21,128,61,0.08), rgba(21,128,61,0.35), rgba(21,128,61,0.08));
         }

         .ettaba-cat-grid-8 {
             display: grid;
             grid-template-columns: repeat(4, 1fr);
             gap: 10px 8px;
         }

         .ettaba-cat-item-v2 {
             display: flex;
             flex-direction: column;
             align-items: center;
             text-align: center;
             padding: 6px 4px;
             border-radius: 12px;
             transition: all 0.2s ease-in-out;
         }

         .ettaba-cat-item-v2:hover {
             transform: translateY(-2px);
             background: #f8fafc;
         }

         .ettaba-cat-icon-circle {
             width: 52px;
             height: 52px;
             border-radius: 15px;
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 21px;
             margin-bottom: 6px;
             box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
             transition: all 0.2s ease;
         }

         .ettaba-cat-name-v2 {
             font-size: 12px;
             font-weight: 700;
             color: #1e293b;
             line-height: 1.2;
             display: -webkit-box;
             -webkit-line-clamp: 2;
             -webkit-box-orient: vertical;
             overflow: hidden;
         }

         @media (max-width: 575.98px) {
             .ettaba-cat-grid-8 {
                 gap: 8px 4px;
             }
             .ettaba-cat-icon-circle {
                 width: 46px;
                 height: 46px;
                 border-radius: 13px;
                 font-size: 18px;
                 margin-bottom: 5px;
             }
             .ettaba-cat-name-v2 {
                 font-size: 11px;
             }
         }

         /* Section Header & "সব দেখুন" Button (Section 7) */
         .ettaba-section-heading {
             font-size: 19px;
             font-weight: 800;
             color: #0f172a;
         }

         /* Promotional Banner Slider (Section 3.1) */
         .ettaba-hero-slider {
             box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
             border-radius: 14px;
             border: 2px solid #bbf7d0;
             background: #ffffff;
             overflow: hidden;
         }

         .ettaba-banner-img {
             width: 100%;
             height: 230px;
             object-fit: cover;
         }

         @media (max-width: 767.98px) {
             .ettaba-banner-img {
                 height: 122px;
                 object-fit: fill;
             }
         }

         .ettaba-banner-caption {
             position: absolute;
             top: 0;
             left: 0;
             bottom: 0;
             right: 0;
             padding: 16px 22px;
             background: linear-gradient(90deg, rgba(240, 253, 244, 0.92) 0%, rgba(240, 253, 244, 0.65) 55%, rgba(255, 255, 255, 0) 100%);
         }

         .ettaba-banner-heading {
             font-size: 22px;
             font-weight: 800;
             color: #14532d;
             margin-bottom: 4px;
             line-height: 1.25;
         }

         .ettaba-banner-sub {
             font-size: 13px;
             font-weight: 700;
             color: #1e293b;
             margin-bottom: 10px;
         }

         .ettaba-banner-cta {
             display: inline-flex;
             align-items: center;
             background: linear-gradient(135deg, #15803d 0%, #166534 100%);
             color: #ffffff !important;
             padding: 6px 18px;
             border-radius: 50px;
             font-size: 13px;
             font-weight: 700;
             box-shadow: 0 4px 10px rgba(21, 128, 61, 0.3);
         }

         .ettaba-carousel-dots {
             bottom: 6px;
             margin-bottom: 0;
         }

         .ettaba-carousel-dots li {
             width: 8px;
             height: 8px;
             border-radius: 50%;
             background-color: #15803d;
             opacity: 0.4;
         }

         .ettaba-carousel-dots li.active {
             opacity: 1;
             transform: scale(1.2);
         }

         .ettaba-default-promo-banner {
             background: linear-gradient(135deg, #dcfce7 0%, #fef9c3 55%, #bbf7d0 100%);
             border: 1px solid #86efac;
         }

         .ettaba-promo-title-green {
             font-size: 20px;
             font-weight: 800;
             color: #14532d;
             line-height: 1.2;
         }

         .ettaba-promo-title-red {
             font-size: 21px;
             font-weight: 800;
             color: #dc2626;
             line-height: 1.25;
             margin-bottom: 4px;
         }

         .ettaba-promo-bullets {
             font-size: 11.5px;
             font-weight: 700;
             color: #0f172a;
         }

         .ettaba-promo-badge-circle {
             width: 92px;
             height: 92px;
             border-radius: 50%;
             background: linear-gradient(135deg, #facc15 0%, #eab308 100%);
             border: 3px solid #15803d;
             display: flex;
             align-items: center;
             justify-content: center;
             text-align: center;
             font-size: 11px;
             font-weight: 800;
             color: #0f172a;
             line-height: 1.25;
             transform: rotate(-8deg);
             box-shadow: 0 4px 10px rgba(0,0,0,0.12);
         }

         @media (max-width: 575.98px) {
             .ettaba-promo-title-green { font-size: 16px; }
             .ettaba-promo-title-red { font-size: 17px; }
             .ettaba-banner-heading { font-size: 16px; }
             .ettaba-banner-sub { font-size: 11px; margin-bottom: 6px; }
             .ettaba-banner-cta { font-size: 11.5px; padding: 5px 14px; }
         }

         /* Logged-in Customer 6-Card Account Stats (Section 4) */
         .ettaba-stat-card {
             display: flex;
             align-items: center;
             border-radius: 10px;
             padding: 8px 8px;
             height: 100%;
             border: 1px solid #e2e8f0;
             transition: transform 0.2s, box-shadow 0.2s;
         }

         .ettaba-stat-card:hover {
             transform: translateY(-1px);
             box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
         }

         .stat-green { background: #f0fdf4; border-color: #dcfce7; }
         .stat-red { background: #fff1f2; border-color: #ffe4e6; }
         .stat-amber { background: #fffbeb; border-color: #fef3c7; }
         .stat-purple { background: #faf5ff; border-color: #f3e8ff; }
         .stat-orange { background: #fff7ed; border-color: #ffedd5; }

         .ettaba-stat-icon {
             width: 30px;
             height: 30px;
             min-width: 30px;
             border-radius: 8px;
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 16px;
             margin-right: 6px;
         }

         .icon-green { color: #15803d; }
         .icon-red { color: #dc2626; }
         .icon-purple { color: #7e22ce; }
         .icon-orange { color: #ea580c; }

         .ettaba-stat-body {
             flex-grow: 1;
             min-width: 0;
             line-height: 1.15;
         }

         .ettaba-stat-lbl {
             font-size: 11px;
             font-weight: 700;
             color: #1e293b;
             white-space: nowrap;
             overflow: hidden;
             text-overflow: ellipsis;
             margin-bottom: 2px;
         }

         .ettaba-stat-val {
             font-size: 13px;
             font-weight: 800;
             white-space: nowrap;
             overflow: hidden;
             text-overflow: ellipsis;
         }

         .ettaba-stat-val small {
             font-size: 10px;
             font-weight: 600;
         }

         .val-green { color: #15803d; }
         .val-red { color: #b91c1c; }

         .ettaba-stat-arrow {
             font-size: 10px;
             color: #64748b;
             margin-left: 3px;
         }

         @media (max-width: 575.98px) {
             .ettaba-stat-card {
                 padding: 6px 5px !important;
             }
             .ettaba-stat-lbl {
                 font-size: 10.5px !important;
             }
             .ettaba-stat-val {
                 font-size: 11.5px !important;
             }
             .ettaba-stat-val small {
                 font-size: 9px !important;
             }
         }

        [v-cloak] {
            display: none !important;
        }

        /* ================= HEADER & SEARCH ================= */
        .ettaba-header {
            position: sticky;
            top: 0;
            z-index: 1050;
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            padding-top: 8px;
            padding-bottom: 10px;
        }

        .ettaba-brand-wrap {
            display: inline-flex;
            align-items: center;
            text-decoration: none !important;
        }

        .ettaba-brand-logo-icon {
            height: 40px;
            width: auto;
            margin-right: 8px;
        }

        .ettaba-brand-title {
            font-family: 'Poppins', 'Hind Siliguri', sans-serif;
            font-size: 21px;
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.3px;
            margin: 0;
            white-space: nowrap;
        }

        .ettaba-brand-title .text-ettaba-green {
            color: #15803d;
        }

        .ettaba-brand-title .text-ettaba-red {
            color: #dc2626;
        }

        .ettaba-brand-slogan {
            font-size: 11.5px;
            font-weight: 600;
            color: #15803d;
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
        }

        .ettaba-location-pill {
            display: inline-flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            padding: 3px 9px;
            font-size: 11.5px;
            font-weight: 700;
            color: #0f172a;
            max-width: 190px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        @media (max-width: 575.98px) {
            .ettaba-brand-logo-icon {
                height: 36px;
                margin-right: 6px;
            }
            .ettaba-brand-title {
                font-size: 19px;
            }
            .ettaba-brand-slogan {
                font-size: 10.5px;
            }
            .ettaba-location-pill {
                padding: 2px 7px;
                font-size: 10.5px;
                max-width: 145px;
            }
            .ettaba-notify-btn {
                width: 30px !important;
                height: 30px !important;
                font-size: 18px !important;
            }
        }

        .ettaba-location-icon-only {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #15803d;
            font-size: 20px;
        }

        .ettaba-notify-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #15803d !important;
            font-size: 21px;
            text-decoration: none !important;
            transition: background 0.2s;
        }

        .ettaba-notify-btn:hover {
            background: #f0fdf4;
        }

        .ettaba-notify-badge {
            position: absolute;
            top: 1px;
            right: 1px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 50px;
            background: #dc2626;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #ffffff;
            line-height: 1;
        }

        .ettaba-search-bar {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 50px;
            padding: 3px 4px 3px 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .ettaba-search-bar:focus-within {
            border-color: #15803d;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.12);
        }

        .ettaba-search-icon {
            color: #475569;
            font-size: 14px;
            margin-right: 8px;
        }

        .ettaba-search-input {
            border: none !important;
            background: transparent !important;
            width: 100%;
            font-size: 13px;
            color: #0f172a;
            padding: 6px 4px;
            outline: none !important;
            box-shadow: none !important;
        }

        .ettaba-search-input::placeholder {
            color: #64748b;
            font-size: 12.5px;
        }

        .ettaba-search-submit {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 50px;
            padding: 6px 20px;
            font-size: 13.5px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);
        }

        .ettaba-search-submit:hover {
            background: #166534;
        }

        /* ================= PRODUCT CARD (SECTION 8) ================= */
        .ettaba-product-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .ettaba-product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
            border-color: #bbf7d0;
        }

        .ettaba-card-img-wrap {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #f8fafc;
            overflow: hidden;
            border-bottom: 1px solid #f1f5f9;
        }

        .ettaba-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .ettaba-product-card:hover .ettaba-card-img {
            transform: scale(1.04);
        }

        .ettaba-discount-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #dc2626;
            color: #ffffff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 50px;
            z-index: 2;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
        }

        .ettaba-wishlist-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid #e2e8f0;
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            z-index: 2;
            padding: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .ettaba-wishlist-btn.wishlisted,
        .ettaba-wishlist-btn:hover {
            color: #dc2626;
            border-color: #fecaca;
            background: #fff1f2;
        }

        .ettaba-card-body {
            padding: 8px 9px 9px;
        }

        .ettaba-card-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a !important;
            line-height: 1.32;
            height: 34px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 5px;
        }

        .ettaba-price-meta-row {
            margin-bottom: 6px;
            gap: 2px;
        }

        .ettaba-old-price {
            font-size: 11.5px;
            color: #64748b;
            text-decoration: line-through;
            text-decoration-color: #dc2626;
            margin-right: 4px;
            font-weight: 600;
        }

        .ettaba-current-price {
            font-size: 14.5px;
            font-weight: 800;
            color: #dc2626;
        }

        .ettaba-stock-rating-group {
            font-size: 10.5px;
            white-space: nowrap;
        }

        .ettaba-stock-text {
            color: #15803d;
            font-weight: 700;
        }

        .ettaba-stock-highlight {
            background: #dcfce7;
            border: 1px solid #86efac;
            padding: 0px 4px;
            border-radius: 4px;
            color: #166534;
            font-weight: 700;
        }

        .ettaba-stock-out-text {
            background: #fef2f2;
            color: #dc2626;
            font-weight: 700;
            padding: 0 4px;
            border-radius: 4px;
        }

        .ettaba-meta-divider {
            color: #cbd5e1;
            margin: 0 3px;
        }

        .ettaba-rating-text {
            color: #1e293b;
            font-size: 10.5px;
        }

        /* Cashback & Reward Boxes */
        .ettaba-cb-box {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 7px;
            padding: 4px 5px;
            height: 100%;
        }

        .ettaba-cb-icon {
            color: #16a34a;
            font-size: 16px;
            margin-right: 5px;
            line-height: 1;
        }

        .ettaba-cb-val {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .ettaba-cb-lbl {
            font-size: 10px;
            font-weight: 700;
            color: #15803d;
            line-height: 1.1;
        }

        .ettaba-rp-box {
            background: #fff7ed;
            border: 1px solid #ffedd5;
            border-radius: 7px;
            padding: 4px 5px;
            height: 100%;
        }

        .ettaba-rp-icon {
            width: 20px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            background: #f59e0b;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
        }

        .ettaba-rp-val {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .ettaba-rp-lbl {
            font-size: 9.5px;
            font-weight: 700;
            color: #7c2d12;
            line-height: 1.1;
            white-space: nowrap;
        }

        /* Action Buttons */
        .ettaba-btn-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1.5px solid #16a34a;
            color: #15803d !important;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 4px;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .ettaba-btn-detail:hover {
            background: #f0fdf4;
        }

        .ettaba-btn-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dc2626;
            border: 1.5px solid #dc2626;
            color: #ffffff !important;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 4px;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ettaba-btn-cart:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        @media (max-width: 420px) {
            .ettaba-card-body {
                padding: 6px 6px 7px;
            }
            .ettaba-card-title {
                font-size: 12px;
                height: 31px;
            }
            .ettaba-current-price {
                font-size: 13px;
            }
            .ettaba-old-price {
                font-size: 10.5px;
            }
            .ettaba-stock-rating-group {
                font-size: 9.5px;
            }
            .ettaba-btn-detail, .ettaba-btn-cart {
                font-size: 10.5px;
                padding: 4px 2px;
            }
        }

        /* ================= BOTTOM NAVIGATION BAR (SECTION 10) ================= */
        .ettaba-bottom-nav {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 68px !important;
            background: #ffffff !important;
            z-index: 2000 !important;
            display: flex !important;
            justify-content: space-around !important;
            align-items: center !important;
            border-top: 1px solid #e2e8f0 !important;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06) !important;
            padding: 0 6px !important;
        }

        .ettaba-bottom-item {
            flex: 1;
            max-width: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b !important;
            text-decoration: none !important;
            font-size: 11.5px;
            font-weight: 700;
            padding: 6px 4px;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .ettaba-bottom-item i {
            font-size: 19px;
            margin-bottom: 2px;
        }

        .ettaba-bottom-item.active {
            background: #e6f4ea;
            color: #15803d !important;
        }

        .ettaba-cart-fab-wrap {
            flex: 0 0 68px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ettaba-cart-fab-btn {
            width: 58px !important;
            height: 58px !important;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-top: -26px !important;
            border: 4px solid #ffffff !important;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.38) !important;
            position: relative;
            text-decoration: none !important;
            font-size: 22px;
        }

        .ettaba-cart-fab-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            min-width: 20px;
            height: 20px;
            border-radius: 50px;
            background: #15803d;
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        /* Drawers */
        .side-drawer {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 310px !important;
            max-width: 85vw !important;
            height: 100% !important;
            background: white !important;
            z-index: 2100 !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            flex-direction: column !important;
            transform: translateX(-100%) !important;
            box-shadow: 10px 0 30px rgba(0,0,0,0.12) !important;
        }

        .side-drawer.active {
            transform: translateX(0) !important;
        }

        .drawer-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: rgba(0, 0, 0, 0.5) !important;
            z-index: 2099 !important;
            display: none !important;
        }

        .drawer-overlay.active {
            display: block !important;
        }

        .cart-drawer {
            position: fixed !important;
            top: 0 !important;
            right: 0 !important;
            width: 380px !important;
            max-width: 100% !important;
            height: 100% !important;
            background: white !important;
            z-index: 2100 !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            flex-direction: column !important;
            transform: translateX(100%) !important;
            box-shadow: -10px 0 30px rgba(0,0,0,0.12) !important;
        }

        .cart-drawer.active {
            transform: translateX(0) !important;
        }
    </style>

    @yield('extra-style')
</head>

<body>
    <div id="app" v-cloak>
        <!-- Drawer Overlays -->
        <div id="drawer-overlay" class="drawer-overlay" onclick="toggleDrawer()"></div>
        <div id="cart-overlay" class="drawer-overlay" onclick="toggleCartDrawer()"></div>

        <!-- Side Drawer -->
        <div id="side-drawer" class="side-drawer">
            <div class="drawer-header d-flex justify-content-between align-items-center p-3 border-bottom" style="background: #f0fdf4;">
                <a href="{{ url('/') }}" class="ettaba-brand-wrap">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="ettaba-brand-logo-icon" alt="Ettaba">
                    <div>
                        <div class="ettaba-brand-title">
                            <span class="text-ettaba-green">Ettaba</span> <span class="text-ettaba-red">Shop</span>
                        </div>
                        <div class="ettaba-brand-slogan">পারিবারিক খরচ হোক সাশ্রয়ী</div>
                    </div>
                </a>
                <button onclick="toggleDrawer()" class="btn btn-light rounded-circle p-2 border">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="drawer-body p-3 overflow-auto flex-grow-1">
                <nav class="drawer-nav">
                    <p class="text-muted small text-uppercase font-weight-bold mb-2">মেনু (Menu)</p>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-1">
                            <a href="{{ url('/') }}" class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded {{ Request::is('/') ? 'bg-light text-success' : '' }}">
                                <i class="fas fa-home mr-3 text-success"></i> হোমপেজ
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('website.notices.index') }}" class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded {{ Request::is('notices*') ? 'bg-light text-success' : '' }}">
                                <i class="fas fa-bell mr-3 text-danger"></i> পাবলিক নোটিশ
                                @if(!empty($unreadNoticeCount) && $unreadNoticeCount > 0)
                                    <span class="badge badge-danger ml-auto">{{ $unreadNoticeCount }}</span>
                                @endif
                            </a>
                        </li>
                        @auth
                            <li class="mb-1">
                                <a href="{{ route('user.profile') }}" class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded">
                                    <i class="fas fa-user-circle mr-3 text-success"></i> আমার একাউন্ট
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('profile.rewards') }}" class="text-dark font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded">
                                    <i class="fas fa-gift mr-3 text-warning"></i> সকল পুরস্কারের তালিকা
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ url('/logout') }}" class="text-danger font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded">
                                    <i class="fas fa-sign-out-alt mr-3"></i> লগআউট (Logout)
                                </a>
                            </li>
                        @else
                            <li class="mb-1">
                                <a href="{{ route('login') }}" class="text-success font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded">
                                    <i class="fas fa-sign-in-alt mr-3"></i> লগইন করুন
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('register') }}" class="text-danger font-weight-bold text-decoration-none d-flex align-items-center py-2 px-3 rounded">
                                    <i class="fas fa-user-plus mr-3"></i> নতুন একাউন্ট খুলুন (ফ্রি)
                                </a>
                            </li>
                        @endauth
                    </ul>

                    @if(isset($categories) && count($categories) > 0)
                        <p class="text-muted small text-uppercase font-weight-bold mt-3 mb-2">পণ্য ক্যাটাগরি</p>
                        <div class="row no-gutters">
                            @foreach($categories as $category)
                                @php
                                    $catName = is_array($category) ? ($category['name']['bn'] ?? $category['name']['en']) : ($category->name_bn ?? $category->name_en ?? $category->name ?? '');
                                    $catSlug = is_array($category) ? ($category['slug'] ?? '') : ($category->slug ?? '');
                                @endphp
                                @if($catSlug)
                                    <div class="col-6 p-1">
                                        <a href="{{ route('categoryProducts', ['slug' => $catSlug]) }}"
                                           class="btn btn-light border text-dark btn-sm w-100 py-2 rounded text-truncate text-left px-2 font-weight-bold">
                                            <i class="fas fa-angle-right text-success mr-1"></i> {{ $catName }}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Header -->
        @include('website.layouts.includes.header')

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('website.layouts.includes.footer')

        <!-- Bottom Navigation Bar (Visible on both Logged-in & Logged-out states) -->
        <nav class="ettaba-bottom-nav">
            <a href="{{ url('/') }}" class="ettaba-bottom-item {{ Request::is('/') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>হোম</span>
            </a>

            <a href="javascript:void(0)" onclick="showComingSoonModal('local_shop')" class="ettaba-bottom-item">
                <i class="fas fa-store"></i>
                <span>লোকাল শপ</span>
            </a>

            <div class="ettaba-cart-fab-wrap">
                <a href="javascript:void(0)" class="ettaba-cart-fab-btn" onclick="toggleCartDrawer(); return false;">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="ettaba-cart-fab-badge" v-if="cartItems && cartItems.length > 0">@{{ cartItems.length }}</span>
                </a>
            </div>

            <a href="javascript:void(0)" onclick="showComingSoonModal('local_merchant')" class="ettaba-bottom-item">
                <i class="fas fa-store-alt"></i>
                <span>লোকাল মার্চেন্ট</span>
            </a>

            @auth
                <a href="{{ route('user.profile') }}" class="ettaba-bottom-item {{ Request::is('profile*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>একাউন্ট</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="ettaba-bottom-item {{ Request::is('login*') || Request::is('register*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>একাউন্ট</span>
                </a>
            @endauth
        </nav>

        <!-- Coming Soon Modal for Local Shop & Local Merchant (Section 10) -->
        <div class="modal fade" id="comingSoonFeatureModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 64px; height: 64px; background: #dcfce7; color: #15803d; font-size: 28px;">
                            <i class="fas fa-store" id="comingSoonIcon"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2" style="color: #15803d;" id="comingSoonTitle">কাজ চলছে...</h5>
                        <p class="mb-4" style="font-size: 15px; color: #334155; font-weight: 600; line-height: 1.6;" id="comingSoonText">
                            কাজ চলছে... শীঘ্রই আপনার এলাকায় ইত্তেবা’র লোকাল অনলাইন শপগুলো দেখতে পারবেন।
                        </p>
                        <button type="button" class="btn btn-success rounded-pill px-5 font-weight-bold" data-dismiss="modal" style="background: #15803d;">
                            ঠিক আছে
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/website/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/website/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Vue & Cart Scripts -->
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
    <script src="{{ asset('assets/website/js/calc.js') }}?v=1.0.3"></script>
    <script src="{{ asset('assets/website/js/main.js') }}"></script>

    <!-- Smart App Deep Linking & Play Store Redirect (Pages 11-12) -->
    <script>
        (function() {
            var ua = navigator.userAgent || navigator.vendor || window.opera || '';
            var isAndroid = /android/i.test(ua);
            var isAppUser = /EttabaShopApp|wv/i.test(ua);
            var isBot = /bot|googlebot|crawler|spider|robot|crawling/i.test(ua);

            // Trigger deep link attempt for Android browsers (non-app, non-bot)
            if (isAndroid && !isAppUser && !isBot) {
                var currentPath = window.location.pathname.replace(/^\/+/, '');
                var currentSearch = window.location.search || '';

                // Only deep-link relevant customer routes (product, shop, category, rewards, ref)
                var shouldDeepLink = false;
                if (currentPath.indexOf('/') !== -1 || currentPath.indexOf('detail') !== -1 || currentPath.indexOf('rewards') !== -1 || currentPath.indexOf('register') !== -1 || currentSearch.indexOf('ref=') !== -1) {
                    shouldDeepLink = true;
                }

                if (shouldDeepLink && !sessionStorage.getItem('ettaba_app_redirect_attempted')) {
                    sessionStorage.setItem('ettaba_app_redirect_attempted', '1');
                    var fullUrl = window.location.href;
                    var appIntent = 'intent://' + fullUrl.replace(/^https?:\/\//i, '') + '#Intent;scheme=https;package=com.ettabashop.customer;S.browser_fallback_url=' + encodeURIComponent(fullUrl) + ';end;';
                    
                    // Fallback timer if app is not installed
                    var start = Date.now();
                    window.location.href = appIntent;
                }
            }
        })();
    </script>

    <script>
        function toggleDrawer() {
            document.getElementById('side-drawer').classList.toggle('active');
            document.getElementById('drawer-overlay').classList.toggle('active');
            document.body.classList.toggle('overflow-hidden');
        }

        function toggleCartDrawer() {
            document.getElementById('cartDrawer').classList.toggle('active');
            document.getElementById('cart-overlay').classList.toggle('active');
            document.body.classList.toggle('overflow-hidden');
        }

        function showComingSoonModal(type) {
            var textEl = document.getElementById('comingSoonText');
            var iconEl = document.getElementById('comingSoonIcon');
            if (type === 'local_merchant') {
                textEl.innerText = 'কাজ চলছে... শীঘ্রই আপনার এলাকায় ইত্তেবা’র লোকাল মার্চেন্টগুলো দেখতে পারবেন।';
                iconEl.className = 'fas fa-store-alt';
            } else {
                textEl.innerText = 'কাজ চলছে... শীঘ্রই আপনার এলাকায় ইত্তেবা’র লোকাল অনলাইন শপগুলো দেখতে পারবেন।';
                iconEl.className = 'fas fa-store';
            }
            $('#comingSoonFeatureModal').modal('show');
        }

        function toggleEttabaWishlist(btn, productId) {
            try {
                var list = JSON.parse(localStorage.getItem('ettaba_wishlist') || '[]');
                var idx = list.indexOf(String(productId));
                var icon = btn.querySelector('i');
                if (idx === -1) {
                    list.push(String(productId));
                    btn.classList.add('wishlisted');
                    if (icon) { icon.className = 'fas fa-heart text-danger'; }
                } else {
                    list.splice(idx, 1);
                    btn.classList.remove('wishlisted');
                    if (icon) { icon.className = 'far fa-heart'; }
                }
                localStorage.setItem('ettaba_wishlist', JSON.stringify(list));
            } catch (e) {}
        }
    </script>
    @yield('extra-script')
</body>

</html>
