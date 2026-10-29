@extends('website.layouts.layout')

@section('content')
<div class="ettaba-page-shell py-3">
    <div class="bg-white rounded-lg shadow-sm border p-3 mb-3 d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <div class="small text-muted mb-1">
                <a href="{{ route('website.index') }}" class="text-success font-weight-bold text-decoration-none">
                    <i class="fas fa-home mr-1"></i> হোম
                </a>
                <i class="fas fa-chevron-right mx-1 small"></i>
                <span>অনুসন্ধান ফলাফল</span>
            </div>
            <h5 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-search text-success mr-1"></i>
                "{{ request('keywords') }}" এর অনুসন্ধান ফলাফল
            </h5>
        </div>
        <span class="badge badge-success px-3 py-2 mt-2 mt-sm-0" style="font-size: 13px; border-radius: 20px;">
            পণ্য পাওয়া গেছে: {{ $products->total() }}টি
        </span>
    </div>

    {{-- Matched Sellers --}}
    @if(isset($matchedShops) && $matchedShops->count() > 0)
        <div class="mb-4">
            <h6 class="font-weight-bold text-dark mb-2">
                <i class="fas fa-store text-success mr-1"></i> প্রাপ্ত সেলার / শপসমূহ ({{ $matchedShops->count() }})
            </h6>
            <div class="row mx-n1">
                @foreach($matchedShops as $shop)
                    @php
                        $shopUrl = !empty($shop->slug)
                            ? url('/' . $shop->slug)
                            : route('shop.detail', ['id' => $shop->user_id]);
                        $shopLogo = !empty($shop->logo_image)
                            ? config('app.image_url') . $shop->logo_image
                            : asset('images/logo.png');
                    @endphp
                    <div class="col-12 col-md-6 px-1 mb-2">
                        <a href="{{ $shopUrl }}" class="d-flex align-items-center bg-white border rounded-lg p-2 shadow-sm text-decoration-none text-dark">
                            <img src="{{ $shopLogo }}" alt="{{ $shop->name_en }}"
                                 style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #15803d;"
                                 onerror="this.src='{{ asset('images/logo.png') }}'">
                            <div class="ml-3 flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-success px-2 py-1 mr-2" style="font-size: 10px;">OFFICIAL SELLER</span>
                                    <span class="small text-muted">সেলার আইডি: {{ $shop->unique_id ?: $shop->user_id }}</span>
                                </div>
                                <div class="font-weight-bold text-dark" style="font-size: 15px;">
                                    {{ $shop->name_bn ?: $shop->name_en }}
                                </div>
                                @if(!empty($shop->address_bn) || !empty($shop->address_en))
                                    <div class="small text-muted text-truncate">
                                        <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                        {{ $shop->address_bn ?: $shop->address_en }}
                                    </div>
                                @endif
                            </div>
                            <i class="fas fa-chevron-right text-success ml-2"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Matched Products --}}
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
    @elseif(!isset($matchedShops) || $matchedShops->count() === 0)
        <div class="bg-white rounded-lg shadow-sm border p-5 text-center my-3">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="font-weight-bold text-dark">কোনো পণ্য বা সেলার পাওয়া যায়নি</h5>
            <p class="text-muted small mb-3">ভিন্ন নাম, পণ্যের কোড অথবা সেলার কোড দিয়ে পুনরায় চেষ্টা করুন।</p>
            <a href="{{ route('website.index') }}" class="btn btn-success rounded-pill px-4 font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> হোমপেজে ফিরে যান
            </a>
        </div>
    @endif
</div>

@section('extra-script')
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
@endsection
@endsection
