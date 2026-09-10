<section class="col-lg-5 connectedSortable">

    <div class="box  no-border">
        <div class="box-header no-border">
            <h3 class="box-title">Most viewed posts</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <ul class="products-list product-list-in-box">
                @foreach($mostViewedPosts as $post)
                    <li class="item">
                        <div class="product-img">
                            <img src="{{ asset($post->image) }}" class="img img-responsive">
                        </div>
                        <div class="product-info">
                            <a href="{{ route('post.detail',['slug'=>$post->slug]) }}" class="product-title">{{ $post->title }}
                                <span class="label label-warning pull-right">{{ $post->views }} Views</span></a>
                            <span class="product-description">
                                       {!! substr($post->short_description,0,120) !!}
                                      <a href="{{ route('post.detail',['slug'=>$post->slug]) }}"> view detail</a>
                                    </span>
                        </div>
                    </li>
                @endforeach

            <!-- /.item -->

                <!-- /.item -->
            </ul>
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-center no-border">
            <a href="{{ route('website.index') }}" class="uppercase">View All</a>
        </div>
        <!-- /.box-footer -->
    </div>
    <div class="box   no-border">
        <div class="box-header no-border">
            <h3 class="box-title">Most liked posts</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <ul class="products-list product-list-in-box">
                @foreach($mostLikedPosts as $post)
                    <li class="item">
                        <div class="product-img">
                            <img src="{{ asset($post->image) }}" class="img img-responsive">
                        </div>
                        <div class="product-info">
                            <a href="{{ route('post.detail',['slug'=>$post->slug]) }}" class="product-title">{{ $post->title }}
                                <span class="label label-warning pull-right">{{ $post->likes }} Likes</span></a>
                            <span class="product-description">
                                       {!!   substr($post->short_description,0,50) !!}
                                      <a href="{{ route('post.detail',['slug'=>$post->slug]) }}"> view detail</a>
                                    </span>
                        </div>
                    </li>
            @endforeach
            <!-- /.item -->

                <!-- /.item -->
            </ul>
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-center no-border">
            <a href="javascript:void(0)" class="uppercase">View All Products</a>
        </div>
        <!-- /.box-footer -->
    </div>

</section>