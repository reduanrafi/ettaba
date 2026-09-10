<div id="header-carousel" class="carousel slide" data-ride="carousel">
    <style>
        #header-carousel .carousel-item { height: 450px; }
        @media (max-width: 992px) { #header-carousel .carousel-item { height: 350px; } }
        @media (max-width: 768px) { #header-carousel .carousel-item { height: 250px; } }
        @media (max-width: 576px) { #header-carousel .carousel-item { height: 180px; } }
        #header-carousel .carousel-item img { object-fit: cover; width: 100%; height: 100%; }
    </style>
    <div class="carousel-inner">
        @foreach($sliders as $k=>$slider)
        <div class="carousel-item @if($k==0) active @endif">
            <img class="img-fluid" src="{{env('IMAGE_URL').$slider->image }}" alt="Image">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <div class="p-3" style="max-width: 700px;">
                    <h4 class="text-light text-uppercase font-weight-medium mb-3">{{ (app()->getLocale()=='en'?$slider->title_en:$slider->title_bn) }}</h4>
                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">{{ (app()->getLocale()=='en'?$slider->description_en:$slider->description_bn)}}</h3>
                    <a href="" class="btn btn-light py-2 px-3">{{ __('buttons.shopNow') }}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-prev-icon mb-n2"></span>
        </div>
    </a>
    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-next-icon mb-n2"></span>
        </div>
    </a>
</div>