<!-- Vendor Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">{{ __('titles.shops') }}</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel vendor-carousel">
                @foreach ($shops as $shop)
                    <div class="vendor-item border p-4">
                        <a href="{{ route('shop.detail', ['id' => $shop->user_id]) }}">
                            <img src="{{ env('IMAGE_URL') . $shop->logo_image }}" alt="">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Vendor End -->
