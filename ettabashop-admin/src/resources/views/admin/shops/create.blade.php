@extends('admin.layouts.layout')
@section('content')

    <!-- BEGIN Portlet PORTLET-->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create your shop</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('shop.index') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->


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
                                            <input type="text"   class="form-control" id="name_en" name="name_en" placeholder="Shop Name English" required>
                                            @error('name_en')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="name_bn" class="col-sm-3 control-label">Shop name bangla</label>
                                        <div class="col-sm-9">
                                            <input type="text"   class="form-control" id="name_bn" name="name_bn" placeholder="বাংলায় আপনার দোকানের নাম লিখুন " required>
                                            @error('name_en')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Slug</label>
                                        <div class="col-sm-9">
                                            <input type="text"   class="form-control" id="slug" name="slug" placeholder="this-is-slug-format" required>
                                            @error('slug')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="description_en" class="col-sm-3 control-label">Shop description english</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="description_en" name="description_en" placeholder="Write few words about your shop"></textarea>
                                            @error('description_en')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="description_bn" class="col-sm-3 control-label">Shop description Bangla</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="description_bn" name="description_bn" placeholder="বাংলায় আপনার দোকান সম্পর্কে কিছু লিখুন "></textarea>
                                            @error('description_bn')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="address_en" class="col-sm-3 control-label">Shop address english</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="address_en" name="address_en" placeholder="Your shop location" required></textarea>
                                            @error('address_en')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="address_bn" class="col-sm-3 control-label">Shop address bangla</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="address_bn" name="address_bn" placeholder="বাংলায় আপনার দোকানের অবস্থান লিখুন" required></textarea>
                                            @error('address_bn')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="mobile" class="col-sm-3 control-label">Mobile</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile number" required>
                                            @error('mobile')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="fb_link" class="col-sm-3 control-label">Facebook page link</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Facebook page link">
                                            @error('fb_link')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="youtube_link" class="col-sm-3 control-label">Youtube channel link</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="youtube_link" name="youtube_link" placeholder="Youtube channel link">
                                            @error('youtube_link')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="logo_image" class="col-sm-3 control-label">Logo image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" onchange="loadFile(event)"
                                                   id="logo_image" name="logo_image" placeholder="insert your logo" required>
                                            <p><img id="output" width="200"/></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="banner_image" class="col-sm-3 control-label">Banner image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control"
                                                   id="banner_image" name="banner_image" placeholder="insert banner image" required>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Action</label>
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
        <!-- /.row -->
        {{--        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>--}}
        {{--        <script>tinymce.init({ selector:'textarea' });</script>--}}
        <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace( 'editor' );
        </script>
    </section>
    <!-- END Portlet PORTLET-->

@endsection

