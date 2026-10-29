@extends('website.layouts.layout')

@section('content')
<div class="ettaba-page-shell py-3">
    {{-- Category Header --}}
    <div class="bg-white rounded-lg shadow-sm border p-3 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <div class="small text-muted mb-1">
                    <a href="{{ route('website.index') }}" class="text-success font-weight-bold text-decoration-none">
                        <i class="fas fa-home mr-1"></i> হোম
                    </a>
                    <i class="fas fa-chevron-right mx-1 small"></i>
                    <span>{{ $parent->name_bn ?: $parent->name_en }}</span>
                </div>
                <h4 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-layer-group text-success mr-1"></i>
                    {{ $parent->name_bn ?: $parent->name_en }}
                </h4>
            </div>
            <span class="badge badge-success px-3 py-2 mt-2 mt-sm-0" style="font-size: 13px; border-radius: 20px;">
                মোট পণ্য: {{ $products->total() }}টি
            </span>
        </div>

        @if(isset($categories) && count($categories) > 0)
            <div class="d-flex flex-wrap mt-3 pt-2 border-top" style="gap: 8px;">
                @foreach($categories as $subCat)
                    <a href="{{ route('categoryProducts', ['slug' => $subCat->slug]) }}"
                       class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 font-weight-bold">
                        {{ $subCat->name_bn ?: $subCat->name_en }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Product Grid with Infinite Scroll --}}
    @if($products->count() > 0)
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
            <h5 class="font-weight-bold text-dark">এই ক্যাটাগরিতে বর্তমানে কোনো পণ্য নেই</h5>
            <p class="text-muted small mb-3">অনুগ্রহ করে অন্য ক্যাটাগরি দেখুন অথবা হোমপেজে ফিরে যান।</p>
            <a href="{{ route('website.index') }}" class="btn btn-success rounded-pill px-4 font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> হোমপেজে ফিরে যান
            </a>
        </div>
    @endif
</div>

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
