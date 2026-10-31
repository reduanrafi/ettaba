@php
    $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    $erp = floatval(str_replace(',', '', $product->erp_en ?? $product->price_en ?? 0));
    $mrp = floatval(str_replace(',', '', $product->mrp_en ?? 0));
    $tcb = floatval(str_replace(',', '', $product->tcb_en ?? 0));
    $trp = floatval(str_replace(',', '', $product->trp_en ?? 0));

    $discountPercent = 0;
    if (!empty($product->discount) && $product->discount > 0) {
        $discountPercent = round(floatval($product->discount));
    } elseif ($mrp > $erp && $mrp > 0) {
        $discountPercent = round((($mrp - $erp) / $mrp) * 100);
    }
    $discountBn = str_replace($enDigits, $bnDigits, (string)$discountPercent);

    $qty = isset($product->quantity) && $product->quantity !== null ? max(0, (int)$product->quantity) : 100;
    $sold = isset($product->sold_amount) && $product->sold_amount !== null ? max(0, (int)$product->sold_amount) : 0;
    $isSoldOut = (!empty($product->is_sold_out) && $product->is_sold_out == 1) || (isset($product->quantity) && $product->quantity !== null && (int)$product->quantity <= 0);
    $soldBn = str_replace($enDigits, $bnDigits, (string)$sold);
    $totalStockBn = str_replace($enDigits, $bnDigits, (string)$qty);

    // Reviews rating & count from real relation or aggregates
    $reviewCount = 0;
    $avgRating = 0;
    if (isset($product->product_reviews_count)) {
        $reviewCount = (int)$product->product_reviews_count;
        $avgRating = round(floatval($product->product_reviews_avg_rating ?? 0), 1);
    } elseif ($product->relationLoaded('productReviews') && $product->productReviews) {
        $reviewCount = $product->productReviews->count();
        $avgRating = $reviewCount > 0 ? round($product->productReviews->avg('rating'), 1) : 0;
    }
    if ($reviewCount == 0) {
        $avgRating = '0.0';
    }

    $trpFormatted = ($trp == (int)$trp) ? number_format($trp, 0) : number_format($trp, 2);
    $productTitle = $product->name_bn ?: $product->name_en;
    $safeJsTitle = str_replace(["\\", "'", "\"", "\r", "\n"], ["\\\\", "\\'", "", " ", " "], (string)$productTitle);
    $detailUrl = $product->detail_url ?? (!empty($product->slug) ? route('product.detail', ['slug' => $product->slug]) : url('/detail/' . $product->id));
@endphp

<div class="ettaba-product-card h-100 d-flex flex-column bg-white">
    <!-- 1. Square Image + 2. Discount Badge + 3. Wishlist Heart -->
    <div class="ettaba-card-img-wrap position-relative">
        <a href="{{ $detailUrl }}" class="d-block w-100 h-100">
            <img src="{{ config('app.image_url') . $product->featured_image }}"
                 onerror="this.src='{{ asset($product->featured_image ?: 'assets/images/logo/logo.png') }}'"
                 alt="{{ $productTitle }}"
                 class="ettaba-card-img"
                 loading="lazy">
        </a>

        @if($isSoldOut)
            <span class="ettaba-discount-badge" style="background: #475569;">
                স্টক আউট
            </span>
        @elseif($discountPercent > 0)
            <span class="ettaba-discount-badge">
                {{ $discountBn }}% ছাড়
            </span>
        @endif

        <button type="button"
                class="ettaba-wishlist-btn"
                onclick="toggleEttabaWishlist(this, '{{ $product->id }}')"
                title="পছন্দের তালিকায় যোগ করুন">
            <i class="far fa-heart"></i>
        </button>
    </div>

    <!-- Card Body -->
    <div class="ettaba-card-body d-flex flex-column flex-grow-1">
        <!-- 4. Product Name (Max 2 lines with ...) -->
        <a href="{{ $detailUrl }}" class="ettaba-card-title text-decoration-none" title="{{ $productTitle }}">
            {{ $productTitle }}
        </a>

        <!-- 5. Price (MRP & ERP) + 6. Stock/Sold + 8. Rating & Reviews -->
        <div class="ettaba-price-meta-row d-flex align-items-center justify-content-between flex-wrap">
            <div class="ettaba-price-group d-flex align-items-baseline">
                @if($mrp > $erp)
                    <span class="ettaba-old-price">{{ number_format($mrp, 0) }}৳</span>
                @endif
                <span class="ettaba-current-price">{{ number_format($erp, 0) }}৳</span>
            </div>

            <div class="ettaba-stock-rating-group d-flex align-items-center">
                @if($isSoldOut)
                    <span class="ettaba-stock-out-text">স্টক আউট</span>
                @else
                    <span class="ettaba-stock-text">স্টক: <span class="ettaba-stock-highlight">{{ $soldBn }}/{{ $totalStockBn }}</span></span>
                @endif
                <span class="ettaba-meta-divider">|</span>
                <span class="ettaba-rating-text">
                    <i class="fas fa-star text-warning"></i>
                    <strong>{{ $avgRating }}/5</strong>
                    <span class="text-muted">({{ $reviewCount }})</span>
                </span>
            </div>
        </div>

        <!-- 9. Cashback & 10. Reward Point Boxes -->
        <div class="ettaba-reward-row row no-gutters my-auto">
            <div class="col-6 pr-1">
                <div class="ettaba-cb-box d-flex align-items-center">
                    <div class="ettaba-cb-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="ettaba-cb-info">
                        <div class="ettaba-cb-val">{{ number_format($tcb, 2) }}৳</div>
                        <div class="ettaba-cb-lbl">ক্যাশব্যাক</div>
                    </div>
                </div>
            </div>
            <div class="col-6 pl-1">
                <div class="ettaba-rp-box d-flex align-items-center">
                    <div class="ettaba-rp-icon">
                        <span>§</span>
                    </div>
                    <div class="ettaba-rp-info">
                        <div class="ettaba-rp-val">{{ $trpFormatted }}</div>
                        <div class="ettaba-rp-lbl">রিওয়ার্ড</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 11. "বিস্তারিত" & 12. "কার্টে যোগ করুন" Buttons -->
        <div class="ettaba-action-row row no-gutters mt-2">
            <div class="col-5 pr-1">
                <a href="{{ $detailUrl }}" class="ettaba-btn-detail w-100">
                    <i class="fas fa-info-circle mr-1"></i> বিস্তারিত
                </a>
            </div>
            <div class="col-7 pl-1">
                @if($isSoldOut)
                    <button type="button" class="ettaba-btn-cart w-100" style="background: #94a3b8; cursor: not-allowed;" disabled>
                        স্টক আউট
                    </button>
                @else
                    <button type="button"
                            class="ettaba-btn-cart w-100"
                            @click="addToCart('{{ $product->id }}', '{{ $safeJsTitle }}', '{{ $erp }}', '{{ $trp }}', '{{ $product->owner_id }}', '{{ $tcb }}', '{{ $product->rate_en }}')">
                        <i class="fas fa-shopping-cart mr-1"></i> কার্টে যোগ করুন
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
