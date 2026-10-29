<!-- Promotional Banner Slider (Section 3.1) -->
<div id="ettaba-hero-carousel" class="carousel slide ettaba-hero-slider" data-ride="carousel" data-interval="5000">
    @if(isset($sliders) && count($sliders) > 0)
        @if(count($sliders) > 1)
            <ol class="carousel-indicators ettaba-carousel-dots">
                @foreach($sliders as $k => $slider)
                    <li data-target="#ettaba-hero-carousel" data-slide-to="{{ $k }}" class="{{ $k == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>
        @endif

        <div class="carousel-inner" style="border-radius: 14px; overflow: hidden;">
            @foreach($sliders as $k => $slider)
                @php
                    $offerUrl = !empty($slider->button_link) ? $slider->button_link : route('website.offer.detail', $slider->id);
                    $title = $slider->title_bn ?: $slider->title_en;
                    $desc = $slider->description_bn ?: $slider->description_en;
                    $btnText = $slider->button_text ?: 'এখনই কেনাকাটা করুন';
                @endphp
                <div class="carousel-item {{ $k == 0 ? 'active' : '' }}">
                    <a href="{{ $offerUrl }}" class="d-block position-relative text-decoration-none">
                        <img class="d-block w-100 ettaba-banner-img"
                             src="{{ config('app.image_url') . $slider->image }}"
                             onerror="this.src='{{ asset($slider->image) }}'"
                             alt="{{ $title ?: 'Ettaba Offer' }}">
                        @if(!empty($title) || !empty($desc))
                            <div class="ettaba-banner-caption d-flex flex-column justify-content-center">
                                @if(!empty($title))
                                    <h3 class="ettaba-banner-heading">{{ $title }}</h3>
                                @endif
                                @if(!empty($desc))
                                    <p class="ettaba-banner-sub">{{ $desc }}</p>
                                @endif
                                <div>
                                    <span class="ettaba-banner-cta">
                                        {{ $btnText }} <i class="fas fa-arrow-right ml-1"></i>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <!-- Default Fallback Reference Banner when no slider is uploaded yet -->
        <div class="ettaba-default-promo-banner position-relative overflow-hidden" style="border-radius: 14px;">
            <div class="row no-gutters align-items-center p-3 p-md-4">
                <div class="col-8 pr-2">
                    <div class="ettaba-promo-title-green">প্রতিদিনের কেনাকাটায়</div>
                    <div class="ettaba-promo-title-red">ক্যাশব্যাক ও রিওয়ার্ড পয়েন্ট</div>
                    <div class="ettaba-promo-bullets mb-2">
                        আপনার প্রয়োজন • আপনার সাশ্রয় • আপনার ইত্তেবা
                    </div>
                    <a href="#product-sections" class="ettaba-banner-cta text-decoration-none">
                        এখনই কেনাকাটা করুন <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="col-4 text-right">
                    <div class="ettaba-promo-badge-circle mx-auto">
                        <span>সাশ্রয়ে<br>স্মার্ট কেনাকাটা<br>ইত্তেবার সাথে</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
