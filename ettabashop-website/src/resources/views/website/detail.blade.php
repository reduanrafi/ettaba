@extends('website.layouts.layout')

@php
    $ogTitle = trim($product->name_bn ?: $product->name_en) . ' | ইত্তেবা শপ';
    $rawDesc = trim(strip_tags($product->description_bn ?: ($product->description_en ?: '')));
    $ogDesc = $rawDesc !== '' ? \Illuminate\Support\Str::limit(preg_replace('/\s+/u', ' ', $rawDesc), 160) : ($ogTitle . ' - সাশ্রয়ী মূল্যে ক্যাশব্যাক ও রিওয়ার্ড সুবিধাসহ অর্ডার করুন ইত্তেবা শপে।');
    $ogImage = !empty($product->featured_image)
        ? (str_starts_with($product->featured_image, 'http') ? $product->featured_image : rtrim(preg_replace('#/storage/?$#', '', config('app.image_url')), '/') . '/' . ltrim($product->featured_image, '/'))
        : asset('assets/images/logo/logo.png');
    $canonicalShareUrl = $product->detail_url ?? request()->fullUrl();
@endphp

@section('extra-meta')
    <title>{{ $ogTitle }}</title>
    <link rel="canonical" href="{{ $canonicalShareUrl }}" />
    <meta property="og:type" content="product" />
    <meta property="og:site_name" content="Ettaba Shop" />
    <meta property="og:url" content="{{ $canonicalShareUrl }}" />
    <meta property="og:title" content="{{ $ogTitle }}" />
    <meta property="og:description" content="{{ $ogDesc }}" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta property="og:image:secure_url" content="{{ $ogImage }}" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="600" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $ogTitle }}" />
    <meta name="twitter:description" content="{{ $ogDesc }}" />
    <meta name="twitter:image" content="{{ $ogImage }}" />
@endsection

@section('content')
@php
    $toBanglaNum = function ($num) {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($en, $bn, (string) $num);
    };

    $fullTitle = !empty(trim($product->name_bn ?? '')) ? $product->name_bn : $product->name_en;
    $productCode = $product->unique_id ?: $product->id;

    $mrpVal = (float) str_replace(',', '', (string) ($product->mrp_en ?? 0));
    $erpVal = (float) str_replace(',', '', (string) ($product->erp_en ?? $product->price_en ?? 0));
    $tcbVal = (float) str_replace(',', '', (string) ($product->tcb_en ?? 0));
    $trpVal = (float) str_replace(',', '', (string) ($product->trp_en ?? 0));

    $remainingQty = max(0, (int) ($product->quantity ?? 0));
    $soldQty = max(0, (int) ($product->sold_amount ?? 0));
    $totalStock = $remainingQty + $soldQty;
    if ($totalStock <= 0) {
        $totalStock = max(1, $remainingQty);
    }
    $isSoldOut = ((int) ($product->is_sold_out ?? 0) === 1) || ($remainingQty <= 0 && isset($product->quantity) && $product->quantity !== null && (int) $product->quantity === 0);
    $soldPercent = $totalStock > 0 ? min(100, round(($soldQty / $totalStock) * 100)) : 0;

    $reviewCount = isset($product->product_reviews_count)
        ? (int) $product->product_reviews_count
        : ($product->relationLoaded('productReviews') && $product->productReviews ? $product->productReviews->count() : 0);
    $avgRating = isset($product->product_reviews_avg_rating) && $product->product_reviews_avg_rating > 0
        ? round((float) $product->product_reviews_avg_rating, 1)
        : ($reviewCount > 0 && $product->relationLoaded('productReviews') && $product->productReviews ? round((float) $product->productReviews->avg('rating'), 1) : 4.5);
    $displayReviewCount = $reviewCount > 0 ? $reviewCount : 0;

    // Seller & Delivery Area
    $sellerName = 'Official Store';
    $sellerShopUrl = route('website.shop');
    if (!empty($sellerShop)) {
        $sellerName = $sellerShop->name_en ?: $sellerShop->name_bn;
        $sellerShopUrl = url('/' . $sellerShop->url_slug);
    }

    $deliveryAreaText = '';
    if (!empty(trim($product->delivery_area_bn ?? ''))) {
        $deliveryAreaText = trim($product->delivery_area_bn);
    } elseif (!empty(trim($product->delivery_area_en ?? ''))) {
        $deliveryAreaText = trim($product->delivery_area_en);
    } elseif (!empty($sellerShop)) {
        $deliveryAreaText = $sellerShop->delivery_area_display;
    } else {
        $deliveryAreaText = 'সমগ্র বাংলাদেশ';
    }

    $mainImage = !empty($product->featured_image)
        ? (str_starts_with($product->featured_image, 'http') ? $product->featured_image : rtrim(config('app.image_url'), '/') . '/' . ltrim($product->featured_image, '/'))
        : asset('assets/images/logo/logo.png');
@endphp

@section('extra-style')
<style>
    .pdetail-breadcrumb {
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 10px;
    }
    .pdetail-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .pdetail-breadcrumb a:hover {
        color: #15803d;
    }
    .pdetail-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, 0.05);
        margin-bottom: 16px;
    }
    .pdetail-gallery-main {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pdetail-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .pdetail-thumb-row {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
    }
    .pdetail-thumb-item {
        width: 64px;
        height: 64px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        cursor: pointer;
        flex-shrink: 0;
        background: #fff;
        padding: 2px;
        transition: border-color 0.15s;
    }
    .pdetail-thumb-item.active,
    .pdetail-thumb-item:hover {
        border-color: #15803d;
    }
    .pdetail-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }
    .pdetail-title {
        font-size: 21px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 4px;
        word-break: break-word;
    }
    .pdetail-code {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .pdetail-stock-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 9px 12px;
        margin-bottom: 12px;
    }
    .pdetail-stock-text {
        font-size: 13px;
        font-weight: 700;
        color: #15803d;
        margin-bottom: 6px;
    }
    .pdetail-progress-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pdetail-progress-bar {
        flex: 1;
        height: 7px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
    }
    .pdetail-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #16a34a, #15803d);
        border-radius: 999px;
    }
    .pdetail-rating-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13.5px;
        margin-bottom: 12px;
    }
    .pdetail-stars {
        color: #f59e0b;
        font-size: 14px;
    }
    .pdetail-mrp-line {
        font-size: 16px;
        color: #94a3b8;
        font-weight: 700;
        line-height: 1.2;
    }
    .pdetail-erp-line {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 14px;
    }
    .pdetail-erp-amount {
        font-size: 30px;
        font-weight: 800;
        color: #dc2626;
        line-height: 1.15;
    }
    .pdetail-erp-label {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 600;
    }
    .pdetail-info-list {
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 0;
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .pdetail-info-item {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        font-size: 14px;
        color: #1e293b;
        line-height: 1.45;
        word-break: break-word;
    }
    .pdetail-info-item i {
        margin-top: 3px;
        width: 18px;
        text-align: center;
        flex-shrink: 0;
    }
    .pdetail-action-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .pdetail-qty-selector {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        height: 44px;
        overflow: hidden;
    }
    .pdetail-qty-btn {
        width: 36px;
        height: 100%;
        border: none;
        background: transparent;
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
    }
    .pdetail-qty-btn:hover {
        background: #e2e8f0;
    }
    .pdetail-qty-input {
        width: 42px;
        border: none;
        background: transparent;
        text-align: center;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        outline: none;
    }
    .pdetail-cart-btn {
        flex: 1;
        min-width: 170px;
        height: 44px;
        border-radius: 10px;
        border: none;
        background: #dc2626;
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        transition: background 0.15s;
    }
    .pdetail-cart-btn:hover {
        background: #b91c1c;
    }
    .pdetail-icon-btn {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.15s;
    }
    .pdetail-icon-btn:hover {
        border-color: #15803d;
        color: #15803d;
    }
    .pdetail-desc-heading {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        border-left: 4px solid #15803d;
        padding-left: 10px;
        margin-bottom: 12px;
    }
</style>
@endsection

<div class="ettaba-page-shell py-3">
    {{-- Breadcrumb --}}
    <div class="pdetail-breadcrumb">
        <a href="{{ route('website.index') }}">Home</a>
        @if($product->category)
            <span class="mx-1">/</span>
            <a href="{{ route('categoryProducts', ['slug' => !empty($product->category->slug) ? $product->category->slug : $product->category->id]) }}">
                {{ $product->category->name_bn ?: $product->category->name_en }}
            </a>
        @endif
        <span class="mx-1">/</span>
        <span class="text-dark font-weight-bold">{{ $fullTitle }}</span>
    </div>

    {{-- Main Product Detail Card --}}
    <div class="pdetail-card">
        <div class="row">
            {{-- Left Column: Image Gallery + Thumbnails --}}
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner pdetail-gallery-main">
                        <div class="carousel-item active">
                            <img id="mainProductDetailImg" src="{{ $mainImage }}" alt="{{ $fullTitle }}"
                                 onerror="this.src='{{ asset('assets/images/logo/logo.png') }}'">
                        </div>
                        @if($product->productImages && count($product->productImages) > 0)
                            @foreach($product->productImages as $image)
                                @php
                                    $subImgUrl = str_starts_with($image->image, 'http')
                                        ? $image->image
                                        : rtrim(config('app.image_url'), '/') . '/' . ltrim($image->image, '/');
                                @endphp
                                <div class="carousel-item">
                                    <img src="{{ $subImgUrl }}" alt="{{ $fullTitle }}"
                                         onerror="this.src='{{ asset('assets/images/logo/logo.png') }}'">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @if($product->productImages && count($product->productImages) > 0)
                        <a class="carousel-control-prev" href="#product-carousel" data-slide="prev"
                           style="width: 34px; height: 34px; background: rgba(255,255,255,0.9); border-radius: 50%; top: 50%; transform: translateY(-50%); left: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                            <i class="fa fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-slide="next"
                           style="width: 34px; height: 34px; background: rgba(255,255,255,0.9); border-radius: 50%; top: 50%; transform: translateY(-50%); right: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                            <i class="fa fa-angle-right text-dark"></i>
                        </a>
                    @endif
                </div>

                {{-- Thumbnails --}}
                <div class="pdetail-thumb-row">
                    <div class="pdetail-thumb-item active" onclick="$('#product-carousel').carousel(0); setActiveThumb(this);">
                        <img src="{{ $mainImage }}" alt="Thumb 1" onerror="this.src='{{ asset('assets/images/logo/logo.png') }}'">
                    </div>
                    @if($product->productImages && count($product->productImages) > 0)
                        @foreach($product->productImages as $idx => $image)
                            @php
                                $subImgUrl = str_starts_with($image->image, 'http')
                                    ? $image->image
                                    : rtrim(config('app.image_url'), '/') . '/' . ltrim($image->image, '/');
                            @endphp
                            <div class="pdetail-thumb-item" onclick="$('#product-carousel').carousel({{ $idx + 1 }}); setActiveThumb(this);">
                                <img src="{{ $subImgUrl }}" alt="Thumb {{ $idx + 2 }}" onerror="this.src='{{ asset('assets/images/logo/logo.png') }}'">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Right Column: 8 Required Product Details Points --}}
            <div class="col-lg-7">
                {{-- 1. Full Product Name & Product Code --}}
                <h1 class="pdetail-title">{{ $fullTitle }}</h1>
                <div class="pdetail-code">পণ্যের কোড: {{ $productCode }}</div>

                {{-- 2. Stock & Sold Info with Progress Bar --}}
                <div class="pdetail-stock-box">
                    @if($isSoldOut)
                        <div class="pdetail-stock-text text-danger">
                            <i class="fas fa-times-circle mr-1"></i> স্টক আউট (বর্তমানে স্টকে নেই)
                        </div>
                    @else
                        <div class="pdetail-stock-text">
                            স্টক: {{ $toBanglaNum($soldQty) }}/{{ $toBanglaNum($remainingQty) }}
                            <span class="text-muted font-weight-normal">
                                (বিক্রি হয়েছে {{ $toBanglaNum($soldQty) }}টি, অবশিষ্ট স্টক {{ $toBanglaNum($remainingQty) }}টি)
                            </span>
                        </div>
                        <div class="pdetail-progress-wrap">
                            <div class="pdetail-progress-bar">
                                <div class="pdetail-progress-fill" style="width: {{ $soldPercent }}%;"></div>
                            </div>
                            <span class="small font-weight-bold text-muted">{{ $soldPercent }}%</span>
                        </div>
                    @endif
                </div>

                {{-- 3. Rating & Total Reviews --}}
                <div class="pdetail-rating-row">
                    <span class="pdetail-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                                <i class="fas fa-star"></i>
                            @elseif($i - $avgRating <= 0.5)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </span>
                    <strong class="text-dark">{{ $toBanglaNum(number_format($avgRating, 1)) }}/৫</strong>
                    <span class="text-muted">({{ $toBanglaNum($displayReviewCount) }}টি রিভিউ)</span>
                </div>

                {{-- 4. Strikethrough MRP & Large Red ERP --}}
                @if($mrpVal > $erpVal)
                    <div class="pdetail-mrp-line">
                        <del>৳{{ number_format($mrpVal, 0) }}</del> <small>MRP</small>
                    </div>
                @endif
                <div class="pdetail-erp-line">
                    <span class="pdetail-erp-amount">৳{{ number_format($erpVal, 0) }}</span>
                    <span class="pdetail-erp-label">ERP (ইত্তেবা’র মূল্য)</span>
                </div>

                {{-- 5, 6, 7. Customer Cashback, Customer Reward, Seller Link, Bangla Delivery Area --}}
                <div class="pdetail-info-list">
                    {{-- 5. Customer Cashback & Customer Reward --}}
                    <div class="pdetail-info-item">
                        <i class="fas fa-coins" style="color: #15803d;"></i>
                        <span>কাস্টমার ক্যাশব্যাক: <strong>৳{{ number_format($tcbVal, 2) }}</strong></span>
                    </div>
                    <div class="pdetail-info-item">
                        <i class="fas fa-star" style="color: #f59e0b;"></i>
                        <span>কাস্টমার রিওয়ার্ড: <strong>{{ number_format($trpVal, 2) }} পয়েন্ট</strong></span>
                    </div>

                    {{-- 6. Seller Name as Clickable Link --}}
                    <div class="pdetail-info-item">
                        <i class="fas fa-store" style="color: #475569;"></i>
                        <span>
                            সেলার:
                            <a href="{{ $sellerShopUrl }}" class="font-weight-bold" style="color: #2563eb; text-decoration: underline;">
                                {{ $sellerName }}
                            </a>
                        </span>
                    </div>

                    {{-- 7. Delivery Area Info in Bangla (Auto-expanding) --}}
                    <div class="pdetail-info-item">
                        <i class="fas fa-map-marker-alt" style="color: #15803d;"></i>
                        <span>
                            <strong>এই সেলার ডেলিভারি দেয়:</strong> {{ $deliveryAreaText }}
                        </span>
                    </div>
                </div>

                {{-- 8. Quantity Selector + Add to Cart Button + Wishlist + Share --}}
                @php
                    $safeDetailTitle = str_replace(["\\", "'", "\"", "\r", "\n"], ["\\\\", "\\'", "", " ", " "], (string) $fullTitle);
                @endphp
                <div class="pdetail-action-row">
                    <div class="pdetail-qty-selector">
                        <button type="button" class="pdetail-qty-btn" @click="changeDetailQty(-1)">−</button>
                        <input type="text" id="detailQtyInput" class="pdetail-qty-input" :value="detailQty" value="1" readonly>
                        <button type="button" class="pdetail-qty-btn" @click="changeDetailQty(1)">+</button>
                    </div>

                    @if($isSoldOut)
                        <button type="button" class="pdetail-cart-btn" style="background: #94a3b8; box-shadow: none; cursor: not-allowed;" disabled>
                            <i class="fas fa-ban"></i> স্টক আউট
                        </button>
                    @else
                        <button type="button" class="pdetail-cart-btn"
                                @click="addToCart('{{ $product->id }}', '{{ $safeDetailTitle }}', '{{ $erpVal }}', '{{ $trpVal }}', '{{ $product->owner_id }}', '{{ $tcbVal }}', '{{ $product->rate_en }}', detailQty)"
                                onclick="if(typeof toggleCartDrawer === 'function') { setTimeout(toggleCartDrawer, 120); }">
                            <i class="fas fa-shopping-cart"></i> কার্টে যুক্ত করুন
                        </button>
                    @endif

                    <button type="button" class="pdetail-icon-btn"
                            onclick="toggleEttabaWishlist(this, '{{ $product->id }}')"
                            title="পছন্দের তালিকায় রাখুন">
                        <i class="far fa-heart"></i>
                    </button>

                    <button type="button"
                            class="pdetail-icon-btn"
                            onclick="shareEttabaProduct('{{ addslashes($canonicalShareUrl) }}', '{{ $safeDetailTitle }}')"
                            title="শেয়ার করুন">
                        <i class="fas fa-share-alt"></i>
                    </button>

                    <button type="button"
                            class="pdetail-icon-btn"
                            onclick="copyEttabaProductLink('{{ addslashes($canonicalShareUrl) }}', this)"
                            title="লিংক কপি করুন ({{ $canonicalShareUrl }})">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
                <div class="mt-2 small text-muted d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <span><i class="fas fa-globe text-success mr-1"></i> পণ্যের শর্ট লিংক:</span>
                    <code class="px-2 py-1 bg-light border rounded text-dark font-weight-bold" style="font-size: 11.5px;">{{ $canonicalShareUrl }}</code>
                </div>
            </div>
        </div>
    </div>

    {{-- 8. Product Description & Reviews Section --}}
    <div class="pdetail-card">
        <h3 class="pdetail-desc-heading">পণ্যের বিবরণ</h3>
        <div class="text-dark" style="font-size: 14px; line-height: 1.7;">
            @if(!empty(trim($product->description_bn ?? '')))
                {!! $product->description_bn !!}
            @elseif(!empty(trim($product->description_en ?? '')))
                {!! $product->description_en !!}
            @else
                <p class="text-muted mb-0">এই পণ্যের বিস্তারিত বিবরণ শীঘ্রই যুক্ত করা হবে।</p>
            @endif
        </div>

        <hr class="my-4">

        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <h5 class="font-weight-bold text-dark mb-2">
                    <i class="fas fa-shipping-fast text-success mr-1"></i> ডেলিভারি নির্দেশিকা
                </h5>
                <p class="small text-muted mb-3" style="line-height: 1.6;">
                    ইত্তেবা শপ লিমিটেড সমগ্র বাংলাদেশে অনলাইন অর্ডার ও ডেলিভারি সেবা পরিচালনা করছে। তবে কাঁচাবাজার, গ্রোসারি ও দ্রুত পচনশীল স্থানীয় পণ্য নির্দিষ্ট লোকাল এলাকায় দ্রুত ডেলিভারি করা হয়। তাই অর্ডার দেওয়ার আগে পণ্যের পাশে উল্লেখিত <strong>ডেলিভারি এলাকা ({{ $deliveryAreaText }})</strong> যাচাই করে নিন।
                </p>

                {{-- Customer Reviews List --}}
                <h5 class="font-weight-bold text-dark mb-2 mt-3">
                    <i class="fas fa-comments text-primary mr-1"></i> কাস্টমার রিভিউসমূহ ({{ $toBanglaNum($displayReviewCount) }})
                </h5>
                @if($product->relationLoaded('productReviews') && $product->productReviews && $product->productReviews->count() > 0)
                    <div class="pr-1" style="max-height: 280px; overflow-y: auto;">
                        @foreach($product->productReviews as $rev)
                            @php
                                $revUserName = 'ভেরিফাইড কাস্টমার';
                                if ($rev->relationLoaded('user') && $rev->user) {
                                    $revUserName = trim(($rev->user->first_name ?? '') . ' ' . ($rev->user->last_name ?? '')) ?: ($rev->user->username ?? 'ভেরিফাইড কাস্টমার');
                                }
                                $isOwnReview = auth()->check() && !empty($rev->user_id) && (int)$rev->user_id === (int)auth()->id();
                            @endphp
                            <div class="border rounded p-2 mb-2 bg-light">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="font-weight-bold text-dark small">
                                        <i class="fas fa-user-check text-success mr-1"></i> {{ $revUserName }}
                                    </div>
                                    <div class="text-warning small">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="{{ $s <= (int)$rev->rating ? 'fas fa-star' : 'far fa-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="text-dark small" style="line-height: 1.45;">{{ $rev->review_message }}</div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <span class="text-muted" style="font-size: 11px;">
                                        {{ $rev->created_at ? $rev->created_at->format('d M, Y') : '' }}
                                    </span>
                                    @if($isOwnReview)
                                        <button type="button"
                                                class="btn btn-link btn-sm p-0 text-primary font-weight-bold"
                                                style="font-size: 11.5px;"
                                                onclick="loadReviewForEdit('{{ $rev->id }}', {{ (int)$rev->rating }}, '{{ addslashes(preg_replace('/\r|\n/', ' ', $rev->review_message)) }}')">
                                            <i class="fas fa-edit mr-1"></i> রিভিউ এডিট করুন
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="border rounded p-3 bg-light text-muted small">
                        এই পণ্যে এখনো কোনো রিভিউ জমা পড়েনি।
                    </div>
                @endif
            </div>

            <div class="col-md-6" id="reviewFormSection">
                <h5 class="font-weight-bold text-dark mb-2">
                    <i class="fas fa-star text-warning mr-1"></i>
                    <span id="reviewFormHeading">
                        {{ (!empty($editableReview) && empty($canAddNewReview)) ? 'আপনার রিভিউ এডিট করুন' : 'আপনার রিভিউ দিন' }}
                    </span>
                </h5>

                @if(Session::has('review_success'))
                    <div class="alert alert-success py-2 px-3 small mb-2">{{ Session::get('review_success') }}</div>
                @endif
                @if(Session::has('review_error'))
                    <div class="alert alert-danger py-2 px-3 small mb-2">{{ Session::get('review_error') }}</div>
                @endif

                @guest
                    <div class="border rounded p-3 bg-light text-center">
                        <p class="small text-muted mb-2">পণ্যটি ক্রয় করে ডেলিভারি গ্রহণের পর রিভিউ দিতে আপনার একাউন্টে লগইন করুন।</p>
                        <a href="{{ route('login') }}" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold">লগইন করুন</a>
                    </div>
                @else
                    @if(!empty($canAddNewReview) || !empty($editableReview))
                        @php
                            $initialReviewId = (!empty($editableReview) && empty($canAddNewReview)) ? $editableReview->id : '';
                            $initialRating = (!empty($editableReview) && empty($canAddNewReview)) ? (int)$editableReview->rating : 5;
                            $initialMessage = (!empty($editableReview) && empty($canAddNewReview)) ? $editableReview->review_message : '';
                        @endphp
                        <form id="productReviewForm" method="POST" action="{{ route('product.review.save') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="review_id" id="reviewIdInput" value="{{ $initialReviewId }}">
                            <input type="hidden" name="rating" id="reviewRatingInput" value="{{ $initialRating }}">

                            <div class="d-flex align-items-center mb-2">
                                <span class="small font-weight-bold mr-2">রেটিং নির্বাচন করুন:</span>
                                <div class="text-warning" style="cursor: pointer; font-size: 18px;">
                                    <i :class="star1" @click="ratingUpdate(1)" @mouseover="ratingUpdate(1)"></i>
                                    <i :class="star2" @click="ratingUpdate(2)" @mouseover="ratingUpdate(2)"></i>
                                    <i :class="star3" @click="ratingUpdate(3)" @mouseover="ratingUpdate(3)"></i>
                                    <i :class="star4" @click="ratingUpdate(4)" @mouseover="ratingUpdate(4)"></i>
                                    <i :class="star5" @click="ratingUpdate(5)" @mouseover="ratingUpdate(5)"></i>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <textarea name="review_message" id="reviewMessageInput" rows="3" class="form-control form-control-sm" required placeholder="পণ্যটি সম্পর্কে আপনার মতামত লিখুন...">{{ old('review_message', $initialMessage) }}</textarea>
                            </div>
                            <button type="submit" id="reviewSubmitBtn" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold">
                                {{ (!empty($editableReview) && empty($canAddNewReview)) ? 'রিভিউ আপডেট করুন' : 'রিভিউ জমা দিন' }}
                            </button>
                            <div class="text-muted mt-2" style="font-size: 11px;">
                                <i class="fas fa-info-circle mr-1"></i> রিভিউ প্রদানের পর আপনি পরবর্তীতে তা এডিট করতে পারবেন (ডিলিট করা যাবে না)।
                            </div>
                        </form>
                    @else
                        <div class="border rounded p-3 bg-light">
                            <div class="small text-dark font-weight-bold mb-1">
                                <i class="fas fa-lock text-warning mr-1"></i> শুধুমাত্র ভেরিফাইড ক্রেতাদের জন্য
                            </div>
                            <p class="small text-muted mb-0" style="line-height: 1.5;">
                                যে কাস্টমার পণ্যটি ক্রয় করবেন শুধু সেই রিভিউ দিতে পারবেন। একবার ক্রয় করে ডেলিভারি সফল হলে একবার রিভিউ দেওয়া যাবে এবং পরবর্তীতে রিভিউ এডিট করা যাবে।
                            </p>
                        </div>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>

@section('extra-script')
<script>
    function changeDetailQty(delta) {
        var input = document.getElementById('detailQtyInput');
        if (!input) return;
        var current = parseInt(input.value || '1', 10);
        var next = current + delta;
        if (next < 1) next = 1;
        input.value = next;
    }

    function setActiveThumb(el) {
        var thumbs = document.querySelectorAll('.pdetail-thumb-item');
        thumbs.forEach(function (t) { t.classList.remove('active'); });
        el.classList.add('active');
    }

    function loadReviewForEdit(reviewId, rating, message) {
        var idEl = document.getElementById('reviewIdInput');
        var msgEl = document.getElementById('reviewMessageInput');
        var ratingEl = document.getElementById('reviewRatingInput');
        var headingEl = document.getElementById('reviewFormHeading');
        var btnEl = document.getElementById('reviewSubmitBtn');

        if (idEl) idEl.value = reviewId;
        if (msgEl) {
            msgEl.value = message;
            msgEl.focus();
        }
        if (ratingEl) ratingEl.value = rating;
        if (headingEl) headingEl.textContent = 'আপনার রিভিউ এডিট করুন';
        if (btnEl) btnEl.textContent = 'রিভিউ আপডেট করুন';

        var sec = document.getElementById('reviewFormSection');
        if (sec && sec.scrollIntoView) {
            sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function shareEttabaProduct(shareUrl, title) {
        if (navigator.share) {
            navigator.share({
                title: title,
                text: title + ' - ইত্তেবা শপ',
                url: shareUrl
            }).catch(function () {});
        } else {
            window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank', 'width=600,height=450');
        }
    }

    function copyEttabaProductLink(shareUrl, btnEl) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(shareUrl).then(function () {
                showCopyFeedback(btnEl);
            });
        } else {
            var temp = document.createElement('input');
            document.body.appendChild(temp);
            temp.value = shareUrl;
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showCopyFeedback(btnEl);
        }
    }

    function showCopyFeedback(btnEl) {
        if (!btnEl) return;
        var origHtml = btnEl.innerHTML;
        btnEl.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(function () {
            btnEl.innerHTML = origHtml;
        }, 1800);
    }
</script>
@endsection
@endsection
