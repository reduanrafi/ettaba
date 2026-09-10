@extends('admin.layouts.layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Create Form</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('product.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @if ($errors->any())
                        <div class="col-md-6 col-md-offset-2">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <span>{{ $error }}</span>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(Session::has('message'))
                        <div class="col-md-6 col-md-offset-2">
                            <span> {{ Session::get('message') }}</span>
                        </div>
                    @endif
                    <form class="form-horizontal" action="{{ route('product.update',['product'=>$product->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {!! method_field('PUT') !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2" id="messageDiv">
                                    @if(Session::has('success'))
                                        @include('admin.layouts.message.success')
                                    @elseif(Session::has('error'))
                                        @include('admin.layouts.message.error')
                                    @endif
                                </div>

                                <div class="col-md-offset-2 col-md-8">
                                    <div class="box-body">
                                        @if ($errors->any())
                                            <div class="col-md-6 col-md-offset-2">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <span>{{ $error }}</span>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if(Session::has('message'))
                                            <div class="col-md-6 col-md-offset-2">
                                                <span> {{ Session::get('message') }}</span>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="productNameEn">Product Name English</label>
                                                    <input type="text" min="0" class="form-control" id="productNameEn"
                                                           name="name_en" value="{{ $product->name_en }}" placeholder="Product Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6 pull-right col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="productNameBn">Product Name Bangla</label>
                                                    <input type="text" min="0" class="form-control" id="productNameBn"
                                                           name="name_bn" value="{{ $product->name_bn }}" placeholder="Product Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input type="text" min="0" class="form-control" id="slug"
                                                     value="{{ $product->slug }}" readonly placeholder="Slug">
                                        </div>


                                        <div class="form-group">
                                            <label for="description_en">Product
                                                description English</label>
                                            <textarea id="editor1" name="description_en" class="form-control" rows="10"> {{ $product->description_en }} </textarea>
                                        </div>


                                            <div class="form-group">
                                                <label for="description_bn">Delivery area en</label>
                                                <textarea name="delivery_area_en" class="form-control" rows="4">{{ $product->delivery_area_en }}</textarea>
                                            </div>

                                        <div class="form-group">
                                            <label for="productNameBn">Product Category</label>
                                            <select name="category_id" class="form-control" id="selectCategory">
                                                @foreach($categories as $category)
                                                    <option class="form-control"
                                                            @if($product->category_id ==$category->id) selected @endif
                                                            value="{{$category->id}}">
                                                        {{ $category->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="priceEn">Price English</label>
                                                    <input type="text" min="0" class="form-control" id="priceEn"
                                                           name="mrp_en" value="{{ $product->mrp_en }}" placeholder="Price">
                                                </div>
                                            </div>

                                            <div class="col-md-6 pull-right col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="priceBn">Price Bangla</label>
                                                    <input type="text" min="0" class="form-control" id="priceBn"
                                                           name="mrp_bn" value="{{ $product->mrp_bn }}" placeholder="Price in bangla">
                                                </div>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="col-md-2 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="priceEn">Rate English</label>
                                                        <input type="text" min="0" class="form-control" id="priceEn"
                                                               name="rate_en"
                                                               value="{{ $product->rate_en }}"
                                                               placeholder="Ettaba Retail Price">
                                                    </div>
                                                </div>


                                                <div class="col-md-2 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="erp_en">ERP English</label>
                                                        <input type="text" min="0" class="form-control" id="erp_en"
                                                               name="erp_en"
                                                               value="{{ $product->erp_en }}"
                                                               placeholder="Ettaba Retail Price">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="cb_en">Cash back English</label>
                                                        <input type="text" min="0" class="form-control" id="cb_en"
                                                               name="cb_en"
                                                               value="{{ $product->cb_en }}"
                                                               placeholder="Cash back">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="tcb_en">TCB English</label>
                                                        <input type="text" min="0" class="form-control" id="tcb_en"
                                                               name="tcb_en"
                                                               readonly
                                                               value="{{ $product->tcb_en }}"
                                                               placeholder="Total Cash Back">
                                                    </div>
                                                </div>


                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="TRPen">TRP English</label>
                                                        <input type="text" min="0" class="form-control" id="TRPen"
                                                               name="trp_en"
                                                               readonly
                                                               value="{{ $product->trp_en }}"
                                                               placeholder="Total Rewards Points">
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">
                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="text" min="0" class="form-control" id="quantity"
                                                           name="quantity" value="{{ $product->quantity }}" placeholder="Quantity">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="unit">Unit</label>
                                                    <input type="text" min="0" class="form-control" id="unit"
                                                           name="unit" value="{{ $product->unit }}" placeholder="Unit">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="direct_refer_commission">Direct Refer Commission</label>
                                                    <input type="number" min="0" class="form-control" id="direct_refer_commission"
                                                           name="direct_refer_commission" value="{{ $product->direct_refer_commission }}" placeholder="Amount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="vat">VAT</label>
                                                    <input type="text" min="0" class="form-control" id="vat"
                                                           name="vat_percent" value="{{ $product->vat_percent }}" placeholder="VAT">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="discount">Discount</label>
                                                    <input type="text" min="0" class="form-control" id="discount"
                                                           name="discount" value="{{ $product->discount }}" placeholder="Discount">
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="soldAmount">Sold amount</label>
                                                    <input type="text" min="0" class="form-control" id="soldAmount"
                                                           name="sold_amount" value="{{ $product->sold_amount }}" placeholder="Sold amount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="productNameBn">Featured image</label>
                                            <input type="file" class="form-control" id="product-image"
                                                   name="featured_image" placeholder="Product image">
                                            <div class="text-center">
                                                <img class="img-fluid" src="{{ asset($product->featured_image) }}" width="350" height="400">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Save">
                                        </div>
                                    </div>
                                </div>


                            </div>


                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection
@section('extra-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection
@section('extra-script')
    <script src="{{asset('assets/admin')}}/bower_components/ckeditor/ckeditor.js"></script>
    <script src="{{asset('assets/admin')}}/bower_components/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $('#selectCategory').select2({
            theme: 'bootstrap4',
        });
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor1')
            //bootstrap WYSIHTML5 - text editor
            //$('.textarea').wysihtml5()

            function calculateTRP() {
                let rate = parseFloat($('input[name="rate_en"]').val()) || 0;
                let erp = parseFloat($('#erp_en').val()) || 0;
                let cb = parseFloat($('#cb_en').val()) || 0;
                let directRefer = parseFloat($('#direct_refer_commission').val()) || 0;

                let trp = (erp - (rate + cb + directRefer)) / 25;
                if (trp < 0) trp = 0;
                
                $('#TRPen').val(trp.toFixed(2));

                let tcb = cb + (trp * 2);
                $('#tcb_en').val(tcb.toFixed(2));
            }

            $('input[name="rate_en"], #erp_en, #cb_en, #direct_refer_commission').on('input', function() {
                calculateTRP();
            });
        })
    </script>
@endsection