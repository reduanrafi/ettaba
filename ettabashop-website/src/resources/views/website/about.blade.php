@extends('website.layouts.layout')
@section('content')

    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{__('menu.about')}}</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route('website.index') }}">{{__('menu.home')}}</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">{{__('menu.about')}}</p>
            </div>
        </div>
    </div>


    <!-- Close Banner -->

    <!-- Start Section -->
    <section class="container py-5">
        <div class="row text-center pt-5 pb-3">
            <div class="col-lg-10 m-auto">

                <section style="
    font-family: 'Lucida Console', 'Courier New', monospace;
    text-align: justify;
    line-height: 2;
">
                    <h2>ইত্তেবা শপ লিমিটেড (ettabashop.com)</h2>
                    <p>
                        ইত্তেবা শপ লিমিটেড বাংলাদেশের একটি উদ্ভাবনী ই-কমার্স ও রিসেলিং প্ল্যাটফর্ম, যা অনলাইন ও অফলাইনের সমন্বয়ে দেশের মানুষের কাছে মানসম্পন্ন পণ্য, আধুনিক সেবা এবং বিশ্বমানের গ্রাহক সহায়তা পৌঁছে দিতে প্রতিশ্রুতিবদ্ধ।
                        আমরা বিশ্বাস করি — <strong>প্রযুক্তি</strong>, <strong>উদ্যোগ</strong> এবং <strong>বিশ্বাস</strong> এর সমন্বয়ই একটি উন্নত অর্থনৈতিক ভবিষ্যতের চাবিকাঠি।
                    </p>

                    <p>
                        আমাদের মাধ্যমে আপনি শুধু নিজের পছন্দের যেকোনো পণ্য বা সেবা কিনতেই পারবেন না, বরং
                        <strong>ক্যাশব্যাক</strong>, <strong>ডিসকাউন্ট</strong>, <strong>রিওয়ার্ড পয়েন্ট</strong> এবং
                        বিভিন্ন অফারের সুবিধা উপভোগ করতে পারবেন। শহরের যানজট, ভিড় এবং সময়ের অপচয় ছাড়াই, ঘরে বসে
                        বা নিকটস্থ ইত্তেবা মার্চেন্ট পয়েন্ট থেকে সহজেই কেনাকাটা করে পাবেন নিশ্চিত ক্যাশব্যাক।
                    </p>

                    <h3>আমাদের কার্যক্রম ও সেবা</h3>
                    <ul>
                        <li>অনলাইন শপিং: ওয়েবসাইট ও মোবাইল অ্যাপের মাধ্যমে দেশের যেকোনো প্রান্ত থেকে অর্ডার।</li>
                        <li>অফলাইন সাপোর্ট: স্থানীয় মার্চেন্ট ও ডেলিভারি নেটওয়ার্কের মাধ্যমে গ্রাহক সেবা।</li>
                        <li>রিসেলিং সিস্টেম: বিনা বিনিয়োগে নিজের অনলাইন ব্যবসা শুরু করার সুযোগ।</li>
                        <li>পার্টনারশিপ প্রোগ্রাম: আর্থিক স্বাধীনতা, লিডারশিপ ও টিম বিল্ডিং সুযোগসহ ১৫টি বিশেষ সুবিধা কোনো ইনভেস্ট ছাড়াই।</li>
                    </ul>

                    <p>
                        প্রতিটি কেনাকাটায় কাস্টমার পাচ্ছেন নিশ্চিত ক্যাশব্যাকসহ কাস্টম অর্ডার সুবিধা, যেমন
                        <em>‘আমার চাহিদা’</em> অপশন থেকে আপনার প্রয়োজনীয় পণ্য যোগ করার অনুরোধ।
                    </p>

                    <h3>আমাদের উদ্দেশ্য ও ভিশন</h3>
                    <p>
                        আমরা শুধু একটি ই-কমার্স নয়, আমরা একটি <strong>স্মার্ট বিজনেস কমিউনিটি</strong> গড়ে তুলছি, যেখানে দেশের হাজারো মানুষ আর্থিক স্বাধীনতা, কর্মসংস্থান এবং ব্যবসায়িক দক্ষতা অর্জনের সুযোগ পাচ্ছেন।
                    </p>
                    <p>
                        আমাদের ভিশন — বাংলাদেশের প্রতিটি জেলা, উপজেলা, এমনকি প্রতিটি ইউনিয়ন পর্যন্ত প্রযুক্তি-ভিত্তিক ব্যবসা ও সেবার বিস্তার ঘটানো, যেনো কাস্টমার, উদ্যোক্তা ও পার্টনার সবাই একসাথে লাভবান হয়।
                    </p>

                    <p>
                        <strong>ইত্তেবা শপ লিমিটেড</strong> — আপনার কেনাকাটা, আয়ের সুযোগ ও ভবিষ্যৎ গড়ার এক অনন্য প্ল্যাটফর্ম।
                        আজই যুক্ত হন আমাদের যাত্রায়, কারণ এখানেই আছে আপনার <strong>স্মার্ট জীবনযাত্রার</strong> শুরু।
                    </p>

                    <blockquote>
                        ইত্তেবা শপ লিমিটেড — স্বপ্ন থেকে সফলতার পথে...
                    </blockquote>
                </section>

            </div>
        </div>
{{--        <div class="row">--}}

{{--            <div class="col-md-6 col-lg-3 pb-5">--}}
{{--                <div class="h-100 py-5 services-icon-wap shadow">--}}
{{--                    <div class="h1 text-success text-center"><i class="fa fa-truck fa-lg"></i></div>--}}
{{--                    <h2 class="h5 mt-4 text-center">Delivery Services</h2>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-6 col-lg-3 pb-5">--}}
{{--                <div class="h-100 py-5 services-icon-wap shadow">--}}
{{--                    <div class="h1 text-success text-center"><i class="fas fa-exchange-alt"></i></div>--}}
{{--                    <h2 class="h5 mt-4 text-center">Shipping & Return</h2>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-6 col-lg-3 pb-5">--}}
{{--                <div class="h-100 py-5 services-icon-wap shadow">--}}
{{--                    <div class="h1 text-success text-center"><i class="fa fa-percent"></i></div>--}}
{{--                    <h2 class="h5 mt-4 text-center">Promotion</h2>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-6 col-lg-3 pb-5">--}}
{{--                <div class="h-100 py-5 services-icon-wap shadow">--}}
{{--                    <div class="h1 text-success text-center"><i class="fa fa-user"></i></div>--}}
{{--                    <h2 class="h5 mt-4 text-center">24 Hours Service</h2>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </section>
    <!-- End Section -->

    <!-- Start Brands -->
{{--    <section class="bg-white py-5">--}}
{{--        <div class="container my-4">--}}
{{--            <div class="row text-center py-3">--}}
{{--                <div class="col-lg-6 m-auto">--}}
{{--                    <h1 class="h1">Our strategies</h1>--}}
{{--                    <p>--}}
{{--                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod--}}
{{--                        Lorem ipsum dolor sit amet.--}}
{{--                    </p>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
    <!--End Brands-->
@endsection()

