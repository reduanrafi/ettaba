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

<style>
    .ettaba-hero-slider {
        box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
        border-radius: 14px;
    }

    .ettaba-banner-img {
        width: 100%;
        height: 240px;
        object-fit: cover;
    }

    @media (max-width: 767.98px) {
        .ettaba-banner-img {
            height: 155px;
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
</style>
