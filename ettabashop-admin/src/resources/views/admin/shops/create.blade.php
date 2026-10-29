@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create your shop</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('shop.index') }}"
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>

                    <form class="form-horizontal" action="{{ route('shop.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2" id="messageDiv">
                                    @if(Session::has('success'))
                                        @include('admin.layouts.message.success')
                                    @elseif(Session::has('error'))
                                        @include('admin.layouts.message.error')
                                    @endif
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name_en" class="col-sm-3 control-label">Shop name english</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name_en" name="name_en" placeholder="e.g. Gadgets Bro" required oninput="autoUpdateShopSlug(this.value)">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name_bn" class="col-sm-3 control-label">Shop name bangla</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name_bn" name="name_bn" placeholder="বাংলায় আপনার দোকানের নাম লিখুন" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="slug" class="col-sm-3 control-label">Custom Shop URL (Slug)</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Gadgets_Bro" required>
                                            <small class="text-muted">আপনার শপ লিংক হবে: <strong>ettabashop.com/<span id="slugPreviewText">Gadgets_Bro</span></strong> (একই নামে একাধিক লিংক থাকলে স্বয়ংক্রিয়ভাবে ইউনিক হয়ে যাবে)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="description_en" class="col-sm-3 control-label">Shop description english</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="description_en" name="description_en" placeholder="Write few words about your shop"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="description_bn" class="col-sm-3 control-label">Shop description Bangla</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="description_bn" name="description_bn" placeholder="বাংলায় আপনার দোকান সম্পর্কে কিছু লিখুন"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="address_en" class="col-sm-3 control-label">Shop address english</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="address_en" name="address_en" placeholder="e.g. Kishoreganj Sadar, Kishoreganj" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="address_bn" class="col-sm-3 control-label">Shop address bangla</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="address_bn" name="address_bn" placeholder="বাংলায় আপনার দোকানের অবস্থান লিখুন" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="delivery_area_bn" class="col-sm-3 control-label">ডেলিভারি এলাকা (বাংলায়)</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="delivery_area_bn" name="delivery_area_bn" placeholder="যেমন: শুধু কিশোরগঞ্জ সদর অথবা কিশোরগঞ্জ সদর, করিমগঞ্জ পৌরসভা, কটিয়াদি পৌরসভা"></textarea>
                                            <small class="text-muted">সেলার পেজে এবং প্রোডাক্ট ডিটেইলস পেজে 'এই সেলার ডেলিভারি দেয়-' এর পাশে এটি দেখাবে।</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="mobile" class="col-sm-3 control-label">Mobile</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile number" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="fb_link" class="col-sm-3 control-label">Facebook page link</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Facebook page link">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="youtube_link" class="col-sm-3 control-label">Youtube channel link</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="youtube_link" name="youtube_link" placeholder="Youtube channel link">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="logo_image" class="col-sm-3 control-label">Logo image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" onchange="loadFile(event)"
                                                   id="logo_image" name="logo_image" required>
                                            <p><img id="output" width="200"/></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="banner_image" class="col-sm-3 control-label">Banner image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" id="banner_image" name="banner_image" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Action</label>
                                        <div class="col-sm-9">
                                            <input type="submit" value="Save" class="btn btn-success">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function autoUpdateShopSlug(val) {
                var formatted = val.trim().replace(/\s+/g, '_').replace(/[^A-Za-z0-9_\-]/g, '');
                var slugInput = document.getElementById('slug');
                var preview = document.getElementById('slugPreviewText');
                if (slugInput) slugInput.value = formatted;
                if (preview) preview.textContent = formatted || 'Gadgets_Bro';
            }
        </script>
    </section>
@endsection
