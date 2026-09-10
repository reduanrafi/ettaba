@extends('website.layouts.layout')
@section('facebook')
    {{--
    <meta property="og:title" content="{{ $product->name_en}}" /> --}}
    {{--
    <meta property="og:image" content="{{'http://mahedipublications.com/'.$product->featured_image }}" /> --}}
    {{--
    <meta property="og:description" content="{{ $product->description_en }}" /> --}}
@endsection
@section('content')
    <div class="container-fluid bg-light py-5 border-bottom">
        <div class="row px-xl-5">
            <div class="col-12 text-center">
                <h1 class="display-4 font-weight-bold mb-3">{{ __('titles.shops') }}</h1>
                <p class="text-muted">Discover the best shops and products near you</p>

                <div class="d-flex justify-content-center mt-4">
                    <div class="col-lg-6">
                        <form action="{{ route('shop.search') }}" method="get">
                            <div class="search-container shadow-sm border bg-white">
                                <input type="text" name="keywords" class="search-input" placeholder="Search for shops...">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 mr-1">FIND</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($shops) > 0)
        <div class="row px-xl-5 pb-3">
            @foreach ($shops as $shop)
                <div class="col-6 col-md-4 col-lg-2 mb-4 px-2">
                    <div class="card shadow-sm border h-100 text-center rounded shop-card-hover" style="transition: all 0.3s ease;">
                        <a href="{{ route('shop.detail', ['id' => $shop->user_id]) }}" class="text-decoration-none text-dark d-flex flex-column h-100 p-3">
                            <div class="d-flex justify-content-center mb-3 mt-2">
                                <div class="rounded-circle overflow-hidden shadow-sm" style="width: 70px; height: 70px; border: 2px solid #f8f9fa;">
                                    <img class="img-fluid w-100 h-100" style="object-fit: cover;" src="{{ env('IMAGE_URL') . $shop->logo_image }}" alt="{{ $shop->name_bn }}">
                                </div>
                            </div>
                            <div class="mt-auto">
                                <h6 class="font-weight-bold mb-0 text-truncate" style="font-size: 14px;">{{ $shop->name_bn }}</h6>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .shop-card-hover:hover {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                transform: translateY(-5px);
                border-color: #007bff !important;
            }
        </style>
    @else
        <div class="text-center mb-4">

            <h1 style="color: #7a7a7a">{{ __('messages.noShop') }}</h1>

        </div>
    @endif
    </div>

    <!-- Shop Detail End -->
@endsection()
@section('extra-script')
    <script>
        var loadFile = function (event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endsection()