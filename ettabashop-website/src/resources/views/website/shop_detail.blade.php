@extends('website.layouts.layout')

@section('facebook')
    <title>{{ $shop->name_en ?: $shop->name_bn }} - Ettaba Shop</title>
    <meta property="og:title" content="{{ $shop->name_en ?: $shop->name_bn }} - Official Seller on Ettaba Shop" />
    <meta property="og:image" content="{{ !empty($shop->logo_image) ? config('app.image_url') . $shop->logo_image : asset('assets/images/logo/logo.png') }}" />
    <meta property="og:description" content="{{ $shop->description_bn ?: $shop->description_en }}" />
@endsection

@section('content')
@php
    $shopLogo = !empty($shop->logo_image)
        ? (str_starts_with($shop->logo_image, 'http') ? $shop->logo_image : rtrim(config('app.image_url'), '/') . '/' . ltrim($shop->logo_image, '/'))
        : asset('assets/images/logo/logo.png');
    $sellerIdDisplay = $shop->unique_id ?: $shop->user_id;
    $sellerLocationDisplay = $shop->address_en ?: $shop->address_bn ?: 'Bangladesh';
    $deliveryAreaDisplay = $shop->delivery_area_display;
    $shopBaseUrl = url('/' . $shop->url_slug);
@endphp

<style>
    .seller-info-card {
        background: #ffffff;
        border: 1px solid #dbe3ee;
        border-radius: 16px;
        padding: 12px 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
        margin-bottom: 10px;
    }
    .seller-info-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .seller-info-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1 1 52%;
        min-width: 0;
        padding-right: 10px;
        border-right: 1px solid #e2e8f0;
    }
    .seller-logo-wrap {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 3px solid #f1f5f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        overflow: hidden;
        flex-shrink: 0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .seller-logo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .seller-official-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #16a34a;
        color: #ffffff;
        font-size: 9.5px;
        font-weight: 800;
        letter-spacing: 0.4px;
        padding: 2px 8px;
        border-radius: 999px;
        text-transform: uppercase;
        margin-bottom: 3px;
    }
    .seller-title-name {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 3px;
        word-break: break-word;
    }
    .seller-id-meta {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .seller-info-right {
        flex: 1 1 48%;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .seller-contact-line {
        font-size: 12px;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.35;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        word-break: break-word;
    }
    .seller-contact-line i {
        color: #334155;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .seller-delivery-pill {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 5px 8px;
        font-size: 11.5px;
        font-weight: 700;
        color: #15803d;
        line-height: 1.4;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-top: 2px;
        white-space: normal;
        word-break: break-word;
    }
    .seller-delivery-pill i {
        color: #0f172a;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Seller Search Box */
    .seller-search-box {
        background: #f1f5f9;
        border: 1.5px solid #dbe3ee;
        border-radius: 999px;
        padding: 3px 5px 3px 14px;
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    .seller-search-box input {
        border: none;
        background: transparent;
        flex: 1;
        font-size: 12.5px;
        color: #1e293b;
        outline: none;
        padding: 6px 6px;
    }
    .seller-search-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: #f8b4b4;
        color: #111827;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .seller-search-btn:hover {
        background: #f87171;
        color: #ffffff;
    }

    /* Section Icon Badges */
    .seller-sec-badge-circle {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 800;
        font-size: 12px;
        flex-shrink: 0;
    }

    @media (max-width: 575.98px) {
        .seller-info-card {
            padding: 10px;
        }
        .seller-info-grid {
            gap: 8px;
        }
        .seller-logo-wrap {
            width: 58px;
            height: 58px;
        }
        .seller-title-name {
            font-size: 15px;
        }
        .seller-official-badge {
            font-size: 8.5px;
            padding: 2px 6px;
        }
        .seller-id-meta,
        .seller-contact-line,
        .seller-delivery-pill {
            font-size: 10.5px;
        }
    }
</style>

<div class="ettaba-page-shell pt-2 pb-4">
    {{-- 1. Seller Information Card (Section 2 of Seller Doc) --}}
    <div class="seller-info-card">
        <div class="seller-info-grid">
            {{-- Left: Seller Logo + Official Badge + Name + Seller ID --}}
            <div class="seller-info-left">
                <a href="{{ $shopBaseUrl }}" class="seller-logo-wrap">
                    <img src="{{ $shopLogo }}"
                         alt="{{ $sellerName }}"
                         onerror="this.src='{{ asset('assets/images/logo/logo.png') }}'">
                </a>
                <div class="min-w-0">
                    <div class="seller-official-badge">
                        <i class="fas fa-check-circle"></i> OFFICIAL SELLER
                    </div>
                    <a href="{{ $shopBaseUrl }}" class="text-decoration-none">
                        <div class="seller-title-name">{{ $sellerName }}</div>
                    </a>
                    <div class="seller-id-meta">
                        <i class="fas fa-store text-secondary"></i>
                        <span>সেলার আইডি: <strong>{{ $sellerIdDisplay }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Right: Phone + Location + Delivery Area --}}
            <div class="seller-info-right">
                <div class="seller-contact-line">
                    <i class="fas fa-phone-alt"></i>
                    <span><strong>ফোন:</strong> {{ $shop->mobile ?: 'N/A' }}</span>
                </div>
                <div class="seller-contact-line">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><strong>লোকেশন:</strong> {{ $sellerLocationDisplay }}</span>
                </div>
                <div class="seller-delivery-pill">
                    <i class="fas fa-truck"></i>
                    <span>এই সেলার ডেলিভারি দেয়- {{ $deliveryAreaDisplay }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Seller-Only Search Box --}}
    <form action="{{ $shopBaseUrl }}" method="GET">
        <div class="seller-search-box">
            <i class="fas fa-search text-dark mr-1" style="font-size: 13px;"></i>
            <input type="text"
                   name="q"
                   value="{{ $searchQuery ?? '' }}"
                   placeholder="নাম ও কোড দিয়ে পণ্য খুঁজুন। (শুধু এই সেলারের পণ্য দেখা যাবে)">
            @if(!empty($searchQuery))
                <a href="{{ $shopBaseUrl }}" class="text-muted small mr-2 text-decoration-none" title="রিসেট">
                    <i class="fas fa-times-circle"></i>
                </a>
            @endif
            <button type="submit" class="seller-search-btn" aria-label="Search seller products">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    {{-- 3. Logged-in Customer Account Info (6 Cards — shown ONLY when logged in, matching 'লগইন সেলার পেজ') --}}
    @auth
        @php
            $eBalance = isset($statistics['currentBalance']) ? (float) $statistics['currentBalance'] : 0;
            $pendingBalance = isset($statistics['pendingBalance']) ? (float) $statistics['pendingBalance'] : 0;
            $totalPoints = isset($statistics['totalPoint']) ? (float) $statistics['totalPoint'] : 0;
            $pendingPoints = isset($statistics['pendingPoints']) ? (float) $statistics['pendingPoints'] : 0;
            $totalIncome = isset($statistics['totalEarnings']) ? (float) $statistics['totalEarnings'] : 0;
            $totalWithdraw = isset($statistics['totalWithdraw']) ? (float) $statistics['totalWithdraw'] : 0;
        @endphp
        <div class="user-stat-grid mb-3">
            <a href="{{ route('user.profile') }}" class="user-stat-card stat-green">
                <div class="user-stat-icon"><i class="fas fa-wallet"></i></div>
                <div class="user-stat-meta">
                    <div class="user-stat-label">ই-ওয়ালেট ব্যালেন্স</div>
                    <div class="user-stat-val text-green">{{ number_format($eBalance, 2) }} <small>টাকা</small></div>
                </div>
                <i class="fas fa-chevron-right user-stat-arrow"></i>
            </a>

            <a href="{{ route('user.profile') }}" class="user-stat-card stat-red">
                <div class="user-stat-icon"><i class="far fa-clock"></i></div>
                <div class="user-stat-meta">
                    <div class="user-stat-label">পেন্ডিং ব্যালেন্স</div>
                    <div class="user-stat-val text-red">{{ number_format($pendingBalance, 2) }} <small>টাকা</small></div>
                </div>
                <i class="fas fa-chevron-right user-stat-arrow"></i>
            </a>

            <a href="{{ route('profile.rewards') }}" class="user-stat-card stat-gold">
                <div class="user-stat-icon"><i class="fas fa-coins"></i></div>
                <div class="user-stat-meta">
                    <div class="user-stat-label">মোট পয়েন্ট</div>
                    <div class="user-stat-val text-green">{{ number_format($totalPoints, 2) }}</div>
                </div>
                <i class="fas fa-chevron-right user-stat-arrow"></i>
            </a>

            <a href="{{ route('profile.rewards') }}" class="user-stat-card stat-purple">
                <div class="user-stat-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="user-stat-meta">
                    <div class="user-stat-label">পেন্ডিং পয়েন্ট</div>
                    <div class="user-stat-val text-red">{{ number_format($pendingPoints, 2) }}</div>
                </div>
                <i class="fas fa-chevron-right user-stat-arrow"></i>
            </a>

            <a href="{{ route('user.profile') }}" class="user-stat-card stat-teal">
                <div class="user-stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="user-stat-meta">
                    <div class="user-stat-label">মোট আয়</div>
                    <div class="user-stat-val text-green">{{ number_format($totalIncome, 2) }} <small>টাকা</small></div>
                </div>
                <i class="fas fa-chevron-right user-stat-arrow"></i>
            </a>

            <a href="{{ route('profile.withdrawHistories') }}" class="user-stat-card stat-orange">
                <div class="user-stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="user-stat-meta">
                    <div class="user-stat-label">মোট উত্তোলন</div>
                    <div class="user-stat-val text-red">{{ number_format($totalWithdraw, 2) }} <small>টাকা</small></div>
                </div>
                <i class="fas fa-chevron-right user-stat-arrow"></i>
            </a>
        </div>
    @endauth

    {{-- 4. Product Display Sections (Either Filtered/Search View OR 5 Default Seller Sections) --}}
    @if(!empty($isFilteredView))
        <div class="ettaba-sec-header mt-2 mb-2">
            <h2 class="ettaba-sec-title" style="font-size: 16px;">
                <i class="fas fa-box-open text-success"></i>
                <span>{{ $filterTitle }}</span>
            </h2>
            <a href="{{ $shopBaseUrl }}" class="ettaba-see-all-btn">
                <i class="fas fa-arrow-left mr-1"></i> সেলার শপে ফিরুন
            </a>
        </div>

        @if(count($products) > 0)
            <div class="row mx-n1" id="infinite-product-grid">
                @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 px-1 mb-2">
                        @include('website.layouts.home_sections.modern_product_item', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div id="infinite-scroll-loader" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-success" role="status"></div>
                <div class="small font-weight-bold text-muted mt-1">আরও পণ্য লোড হচ্ছে...</div>
            </div>

            @if($products->hasMorePages())
                <div class="text-center py-3" id="infinite-scroll-trigger"
                     data-next-page="{{ $products->currentPage() + 1 }}"
                     data-has-more="1">
                    <button type="button" id="load-more-btn" class="btn btn-outline-success rounded-pill px-4 font-weight-bold">
                        আরও পণ্য দেখুন <i class="fas fa-arrow-down ml-1"></i>
                    </button>
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-sm border p-5 text-center my-3">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h6 class="font-weight-bold text-dark">এই সেলারের কোনো পণ্য পাওয়া যায়নি</h6>
                <a href="{{ $shopBaseUrl }}" class="btn btn-success btn-sm rounded-pill px-4 mt-2 font-weight-bold">
                    সকল পণ্য দেখুন
                </a>
            </div>
        @endif
    @else
        {{-- 5 Ordered Seller Product Sections --}}
        @foreach($sellerSections as $sec)
            <section class="mb-3">
                <div class="ettaba-sec-header">
                    <h2 class="ettaba-sec-title" style="font-size: 16px;">
                        @if($sec['badge_type'] === 'fire')
                            <i class="fas fa-fire" style="color: #dc2626; font-size: 20px;"></i>
                        @elseif($sec['badge_type'] === 'percent')
                            <span class="seller-sec-badge-circle" style="background: #15803d;">%</span>
                        @elseif($sec['badge_type'] === 'dollar')
                            <span class="seller-sec-badge-circle" style="background: #f59e0b;">$</span>
                        @elseif($sec['badge_type'] === 'new')
                            <span class="seller-sec-badge-circle" style="background: #6d28d9; font-size: 8.5px; letter-spacing: -0.3px;">NEW</span>
                        @else
                            <i class="fas fa-th-large" style="color: #dc2626; font-size: 18px;"></i>
                        @endif
                        <span>{{ $sec['title'] }}</span>
                    </h2>

                    <a href="{{ route('seller.shop.section', ['shop_slug' => $shop->url_slug, 'section_type' => $sec['key']]) }}"
                       class="ettaba-see-all-btn">
                        সব দেখুন <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                @if(count($sec['products']) > 0)
                    <div class="row mx-n1">
                        @foreach($sec['products'] as $product)
                            <div class="col-6 col-md-4 col-lg-3 px-1 mb-2">
                                @include('website.layouts.home_sections.modern_product_item', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg border p-3 text-center text-muted small mb-2">
                        এই সেকশনে বর্তমানে কোনো পণ্য নেই।
                    </div>
                @endif
            </section>
        @endforeach
    @endif
</div>

@if(!empty($isFilteredView))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var trigger = document.getElementById('infinite-scroll-trigger');
    if (!trigger) return;

    var grid = document.getElementById('infinite-product-grid');
    var loader = document.getElementById('infinite-scroll-loader');
    var loadMoreBtn = document.getElementById('load-more-btn');
    var loading = false;

    function loadNextPage() {
        if (loading || trigger.getAttribute('data-has-more') !== '1') return;
        loading = true;
        loader.style.display = 'block';
        if (loadMoreBtn) loadMoreBtn.style.display = 'none';

        var nextPage = trigger.getAttribute('data-next-page');
        var url = new URL(window.location.href);
        url.searchParams.set('page', nextPage);
        url.searchParams.set('ajax', '1');

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.html) {
                grid.insertAdjacentHTML('beforeend', data.html);
            }
            if (data.hasMore) {
                trigger.setAttribute('data-next-page', data.nextPage);
                trigger.setAttribute('data-has-more', '1');
                if (loadMoreBtn) loadMoreBtn.style.display = 'inline-block';
            } else {
                trigger.setAttribute('data-has-more', '0');
                trigger.style.display = 'none';
            }
        })
        .catch(function () {
            if (loadMoreBtn) loadMoreBtn.style.display = 'inline-block';
        })
        .finally(function () {
            loading = false;
            loader.style.display = 'none';
        });
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadNextPage);
    }

    window.addEventListener('scroll', function () {
        if (trigger.getAttribute('data-has-more') !== '1') return;
        var rect = trigger.getBoundingClientRect();
        if (rect.top <= window.innerHeight + 350) {
            loadNextPage();
        }
    });
});
</script>
@endif
@endsection
