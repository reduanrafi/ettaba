@extends('website.layouts.layout')
@section('facebook')
    <title>{{ $product->name_en}}</title>
    <meta property="og:title" content="{{ $product->name_en}}" />
    <meta property="og:image" content="{{env('IMAGE_URL') . $product->featured_image }}" />
    <meta property="og:description" content="{{ $product->description_en }}" />
@endsection
@section('content')
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">

                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="{{env('IMAGE_URL') . $product->featured_image}}" alt="Image">
                        </div>
                        @foreach($product->productImages as $image)
                            <div class="carousel-item">
                                <img class="w-100 h-100" src="{{env('IMAGE_URL') . $image->image}}" alt="Image">
                            </div>
                        @endforeach

                        {{-- <div class="carousel-item">--}}
                            {{-- <img class="w-100 h-100" src="{{asset('assets/website/img/product-3.jpg')}}"
                                alt="Image">--}}
                            {{-- </div>--}}
                        {{-- <div class="carousel-item">--}}
                            {{-- <img class="w-100 h-100" src="{{asset('assets/website/img/product-4.jpg')}}"
                                alt="Image">--}}
                            {{-- </div>--}}
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{ $product->name_en }}</h3>
                <span>Code : {{ $product->unique_id }}</span>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">({{ rand(1, 48) }} Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">{{ $product->mrp_en }} BDT<span style="font-size: 12px">MRP</span>
                </h3>

                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 mr-3">ERP(Ettaba Retail Price)
                        : {{ $product->erp_en }}</p>

                </div>
                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">TRP( Total Rewards Points
                        ): {{ number_format((float) $product->trp_en, 2, '.', '') }}</p>

                </div>
                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">TCB(Total Cash Back ): {{ $product->tcb_en }}</p>

                </div>
                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control bg-secondary text-center" value="1">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button @click="addToCart(
                                           '{{$product->id}}',
                                           '{{$product->name_en}}',
                                           '{{$product->erp_en}}',
                                           '{{$product->trp_en}}',
                                           '{{$product->owner_id}}',
                                           '{{$product->tcb_en}}',
                                           '{{$product->rate_en}}',
                                           )" class="btn btn-primary px-3">
                        <i class="fa fa-shopping-cart mr-1"></i>{{__("buttons.cart")}}</button>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">

                        <a class="text-dark px-2"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ request()->fullUrl() }}&display=popup">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        {{-- <a class="text-dark px-2" href="">--}}
                            {{-- <i class="fab fa-twitter"></i>--}}
                            {{-- </a>--}}
                        {{-- <a class="text-dark px-2" href="">--}}
                            {{-- <i class="fab fa-linkedin-in"></i>--}}
                            {{-- </a>--}}
                        {{-- <a class="text-dark px-2" href="">--}}
                            {{-- <i class="fab fa-pinterest"></i>--}}
                            {{-- </a>--}}
                    </div>
                </div>
                <div class="pt-5">


                    <section>

                        <p>
                            ইত্তেবা শপ লিমিটেড বর্তমানে সমগ্র বাংলাদেশে অনলাইন অর্ডার ও ডেলিভারি সেবা পরিচালনা


                            করছে।
                            আমাদের প্ল্যাটফর্মে থাকা অধিকাংশ পণ্য এখন দেশের যেকোনো প্রান্ত থেকে অর্ডার করা যায়
                            এবং নির্ধারিত সময়ের মধ্যে আপনার হাতে পৌঁছে দেওয়া হয়।
                        </p>

                        <aside>
                            <h5>বিশেষ নোট</h5>
                            <p>
                                আমাদের কিছু সেলার স্থানীয়ভাবে সীমিত এলাকায় পণ্য সরবরাহ করেন, যেমন—
                            </p>
                            <ol>
                                <li>কাঁচাবাজারের পণ্য,</li>
                                <li>গ্রোসারি ও দৈনন্দিন প্রয়োজনীয় সামগ্রী,</li>
                                <li>দ্রুত নষ্ট হয় এমন খাদ্যপণ্য বা রান্না জাতীয় খাবার,</li>
                            </ol>
                            <p>
                                এসব পণ্য নির্দিষ্ট কিছু লোকাল এলাকায় দ্রুত ডেলিভারি করা হয়, যেখানে লোকাল সেলারগণ
                                ইত্তেবা শপের লোকাল হাবের মাধ্যমে সরাসরি গ্রাহকের কাছে পণ্য পৌঁছে দেন।
                                ভবিষ্যতে এ ধরনের লোকাল সেবা দেশের প্রান্তিক পর্যায়ে চালু করার পরিকল্পনা রয়েছে,
                                ইনশাআল্লাহ।
                            </p>
                        </aside>

                        <p>
                            <strong>সুতরাং যেকোনো অর্ডার দেওয়ার আগে পণ্যের পাশে উল্লেখিত
                                <span>ডেলিভারি এরিয়া</span> চেক করে নিন।<br></strong>
                            এটি আপনাকে নিশ্চিত করবে যে, পণ্যটি কোথায় ডেলিভারির সুবিধা রয়েছে।
                        </p>

                        <p>
                            চিন্তা করবেন না, আপনার এলাকায় লোকাল ডেলিভারি সেবা না থাকলে <strong>শীঘ্রই আমরা আসছি</strong>,
                            ইনশাআল্লাহ।<br>
                            কারণ আমরা ধাপে ধাপে দেশব্যাপী লোকাল সেলার ও ডেলিভারি নেটওয়ার্ক বিস্তার করে চলছি,
                            যাতে ক্রেতারা দ্রুত, সাশ্রয়ীও
                            নির্ভরযোগ্য সেবা পেতে পারেন।
                        </p>

                        <hr>

                        {{-- <p>--}}
                            {{-- <strong>ইত্তেবা শপ মানেই- কাস্টমার জিতবেন প্রতিবার</strong>--}}
                            {{-- </p>--}}
                    </section>
                    <p class="mb-4" style="line-height: 2; text-align: justify">
                        <a class="btn  btn-primary" style="color: #fff;"><i class="fas fa-shipping-fast"></i> ডিলেভারি
                            এরিয়া </a> {{ $product->delivery_area_en }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    {{-- <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Information</a>--}}
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Reviews (0)</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3"></h4>
                        <p>{!! $product->description_en !!}</p>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-3">
                        <div class="row">
                            <div class="col-md-6" style="visibility: hidden">
                                <h4 class="mb-4">1 review for "Colorful Stylish Shirt"</h4>
                                <div class="media mb-4">
                                    <img src="img/user.jpg" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                    <div class="media-body">
                                        <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>
                                        <div class="text-primary mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <p>Diam amet duo labore stet elitr ea clita ipsum, tempor labore accusam ipsum
                                            et no at. Kasd diam tempor rebum magna dolores sed sed eirmod ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-4">Leave a review</h4>
                                <small>Your email address will not be published. Required fields are marked *</small>
                                <div class="d-flex my-3">
                                    <p class="mb-0 mr-2">Your Rating * :</p>
                                    <div class="text-primary">
                                        <i :class="star1" @mouseover="ratingUpdate(1)"></i>
                                        <i :class="star2" @mouseover="ratingUpdate(2)"></i>
                                        <i :class="star3" @mouseover="ratingUpdate(3)"></i>
                                        <i :class="star4" @mouseover="ratingUpdate(4)"></i>
                                        <i :class="star5" @mouseover="ratingUpdate(5)"></i>

                                    </div>
                                </div>
                                <form v-on:submit.prevent="saveRating">
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea v-model="ratingText" id="message" cols="30" rows="5"
                                            class="form-control"></textarea>
                                    </div>

                                    <div class="form-group mb-0">
                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Detail End -->
@endsection()