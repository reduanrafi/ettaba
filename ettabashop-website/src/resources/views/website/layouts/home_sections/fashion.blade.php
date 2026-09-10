<!-- Fashion & Lifestyle Start -->
<section class="container-fluid pt-2 px-xl-5">
    <div class="d-flex justify-content-between align-items-end mb-4 pr-xl-5 pl-xl-2">
        <div>
            <h6 class="text-primary font-weight-bold text-uppercase mb-1" style="letter-spacing: 2px;">Style & Trend</h6>
            <h2 class="font-weight-extra-bold m-0" style="font-weight: 800; color: #1e293b;">ফ্যাশন & লাইফস্টাইল</h2>
        </div>
        <a href="{{ route('allProducts', ["type" => "fashion"]) }}"
            class="btn btn-link text-primary font-weight-bold text-decoration-none">
            View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>

    <div class="row pb-3">
        @if( count($fashion)>0)
            @foreach($fashion as $product)
                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-4 px-2">
                    @include('website.layouts.home_sections.modern_product_item', ['product' => $product])
                </div>
            @endforeach
        @else
            <div class="col-12">
                <h3 class="text-center text-muted py-5">কোন পণ্য পাওয়া যায়নি</h3>
            </div>
        @endif
    </div>
</section>
<!-- Products End -->
