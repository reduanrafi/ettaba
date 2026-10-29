@extends('website.layouts.layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="mb-3">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> হোমপেজে ফিরে যান
                </a>
            </div>

            <div class="bg-white border shadow-sm overflow-hidden" style="border-radius: 16px;">
                @if(!empty($slider->image))
                    <div class="bg-light text-center border-bottom">
                        <img src="{{ config('app.image_url') . $slider->image }}"
                             onerror="this.src='{{ asset($slider->image) }}'"
                             alt="{{ $slider->title_bn ?: $slider->title_en }}"
                             class="img-fluid w-100"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <div class="p-4">
                    <span class="badge badge-danger px-3 py-1 mb-2" style="border-radius: 20px; font-size: 12px;">
                        <i class="fas fa-gift mr-1"></i> বিশেষ অফার ও ক্যাম্পেইন
                    </span>
                    <h4 class="font-weight-bold mb-2" style="color: #0f172a;">
                        {{ $slider->title_bn ?: $slider->title_en ?: 'ইত্তেবা শপ বিশেষ অফার' }}
                    </h4>
                    @if($slider->description_bn || $slider->description_en)
                        <p class="text-success font-weight-bold mb-3" style="font-size: 15px;">
                            {{ $slider->description_bn ?: $slider->description_en }}
                        </p>
                    @endif

                    <hr>

                    <div style="font-size: 15px; line-height: 1.8; color: #334155; white-space: pre-line;">
                        @if(!empty($slider->offer_details))
                            {!! nl2br(e($slider->offer_details)) !!}
                        @else
                            প্রতিদিনের কেনাকাটায় ইত্তেবা শপের সাথে উপভোগ করুন আকর্ষণীয় ক্যাশব্যাক ও রিওয়ার্ড পয়েন্ট সুবিধা! আপনার পছন্দের পণ্য অর্ডার করুন এখনই।
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="{{ !empty($slider->button_link) ? $slider->button_link : url('/#product-sections') }}"
                           class="btn btn-success rounded-pill px-5 py-2 font-weight-bold shadow-sm"
                           style="background: #15803d;">
                            {{ $slider->button_text ?: 'এখনই কেনাকাটা করুন' }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

