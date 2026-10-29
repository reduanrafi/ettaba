@extends('website.layouts.layout')

@section('content')
<div class="ettaba-page-shell py-3">
    <div class="bg-white rounded-lg shadow-sm border p-3 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
            <div>
                <h4 class="font-weight-bold mb-1 text-dark">
                    <i class="fas fa-store text-success mr-1"></i> সকল ভেরিফাইড সেলার শপ
                </h4>
                <p class="text-muted small mb-0">আপনার পছন্দের সেলার শপে ক্লিক করে সরাসরি কেনাকাটা করুন</p>
            </div>
            <span class="badge badge-success px-3 py-2 mt-2 mt-sm-0" style="font-size: 13px; border-radius: 20px;">
                মোট শপ: {{ count($shops) }}টি
            </span>
        </div>

        <form action="{{ route('shop.search') }}" method="get" class="mt-2">
            <div class="ettaba-search-bar">
                <i class="fas fa-search ettaba-search-icon"></i>
                <input type="text"
                       name="keywords"
                       value="{{ request('keywords') }}"
                       class="ettaba-search-input"
                       placeholder="সেলার শপের নাম বা সেলার আইডি দিয়ে খুঁজুন...">
                <button type="submit" class="ettaba-search-submit">খুঁজুন</button>
            </div>
        </form>
    </div>

    @if(count($shops) > 0)
        <div class="row mx-n1">
            @foreach($shops as $shop)
                @php
                    $shopLogo = !empty($shop->logo_image)
                        ? (str_starts_with($shop->logo_image, 'http') ? $shop->logo_image : rtrim(config('app.image_url'), '/') . '/' . ltrim($shop->logo_image, '/'))
                        : asset('assets/images/logo/logo.png');
                    $shopUrl = url('/' . $shop->url_slug);
                @endphp
                <div class="col-12 col-sm-6 col-md-4 px-1 mb-2">
                    <a href="{{ $shopUrl }}" class="d-flex align-items-center bg-white border rounded-lg p-3 shadow-sm text-decoration-none text-dark h-100" style="transition: transform 0.15s;">
                        <img src="{{ $shopLogo }}"
                             alt="{{ $shop->name_en }}"
                             style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #15803d; flex-shrink: 0;"
                             onerror="this.src='{{ asset('assets/images/logo/logo.png') }}'">
                        <div class="ml-3 flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 6px;">
                                <span class="badge badge-success px-2 py-1" style="font-size: 9px;">OFFICIAL SELLER</span>
                                <span class="small text-muted">ID: {{ $shop->unique_id ?: $shop->user_id }}</span>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-1 text-truncate">
                                {{ $shop->name_en ?: $shop->name_bn }}
                            </h6>
                            @if(!empty($shop->address_en) || !empty($shop->address_bn))
                                <div class="small text-muted text-truncate">
                                    <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                    {{ $shop->address_en ?: $shop->address_bn }}
                                </div>
                            @endif
                        </div>
                        <i class="fas fa-chevron-right text-success ml-2"></i>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border p-5 text-center my-3">
            <i class="fas fa-store-slash fa-3x text-muted mb-3"></i>
            <h5 class="font-weight-bold text-dark">কোনো সেলার শপ পাওয়া যায়নি</h5>
            <a href="{{ route('website.shop') }}" class="btn btn-success rounded-pill px-4 mt-2 font-weight-bold">
                সকল শপ দেখুন
            </a>
        </div>
    @endif
</div>
@endsection
