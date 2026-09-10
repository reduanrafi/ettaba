<!-- Products Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h6 class="section-title px-5"><span class="px-2">অনলাইন লাইব্রেরি </span></h6>
    </div>
    <div class="row  pb-3">
        @if( count($library)>0)
            @foreach($library as $product)
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="card product-item border-0 mb-4">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <img class=" "   height="250" src=" {{env('IMAGE_URL').$product->featured_image}}" alt="">
                            {{--                            <img class="img-fluid w-100"--}}
                            {{--                                 src="{{ asset('assets/website/img/product-'.rand(1,8).'.jpg')}}" alt="">--}}
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3">{{ $local=='bn'?$product->name_en:$product->name_en}} </h6>
                            <div class="d-flex justify-content-center">
                                <h6>{{__('titles.price')}} : {{ $local=='bn'?$product->erp_en:$product->erp_en}}</h6>

                                <h6 class="text-muted ml-2">
                                    <del>{{ $local=='bn'?$product->mrp_en:$product->mrp_en}}</del>
                                </h6>
                                <br>
                                <h6 class="pl-2">{{__('titles.cashBack')}} : {{ $local=='bn'?$product->tcb_en:$product->tcb_en}}</h6>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                            <a href="{{ route('product.detail',['slug'=>$product->slug]) }}"
                               class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>{{__("buttons.viewDetail")}}</a>
                            @if($local=='en')
                                <a class="btn btn-sm text-dark p-0"
                                   @click="addToCart(
                                   '{{$product->id}}',
                                   '{{$product->name_en}}',
                                   '{{$product->erp_en}}',
                                   '{{$product->trp_en}}',
                                   '{{$product->owner_id}}',
                                   '{{$product->tcb_en}}',
                                   '{{$product->rate_en}}',
                                   )">
                                    <i class="fas fa-shopping-cart text-primary mr-1"></i>
                                    {{__("buttons.cart")}}
                                </a>
                            @else
                                <a class="btn btn-sm text-dark p-0"
                                   @click="addToCart(
                                   '{{$product->id}}',
                                   '{{$product->name_en}}',
                                   '{{$product->erp_en}}',
                                   '{{$product->trp_en}}',
                                   '{{$product->owner_id}}',
                                   '{{$product->tcb_en}}',
                                   '{{$product->rate_en}}',
                                   )">
                                    <i class="fas fa-shopping-cart text-primary mr-1"></i>
                                    {{__("buttons.cart")}}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
{{--                <div class="col-12 pb-1">--}}
{{--                    <div class="pull-right">--}}
{{--                        {{ $products->links() }}--}}
{{--                    </div>--}}

{{--                </div>--}}
            @else
            <h1 class="text-center">কোন পণ্য  পাওয়া যায়নি </h1>
        @endif
    </div>
</div>
<!-- Products End -->
