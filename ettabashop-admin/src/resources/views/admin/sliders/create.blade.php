@extends('admin.layouts.layout')
@section('content')
    <section class="content-header">
        <h1>নতুন প্রচারমূলক ব্যানার যোগ করুন (Add Banner)</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">ব্যানার ও অফারের তথ্য</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('slider.index') }}" class="btn btn-primary btn-flat btn-sm">
                                <i class="fa fa-list"></i> সকল ব্যানার দেখুন
                            </a>
                        </div>
                    </div>

                    <form class="form-horizontal" action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            @if(Session::has('success'))
                                @include('admin.layouts.message.success')
                            @elseif(Session::has('error'))
                                @include('admin.layouts.message.error')
                            @endif

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ব্যানার ছবি <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="image" onchange="loadFile(event)" accept="image/*" required>
                                    <small class="text-muted">প্রস্তাবিত সাইজ: 1200x400px বা 1000x350px</small>
                                    <p style="margin-top: 8px;"><img id="output" width="300" style="border-radius: 8px;"/></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ব্যানার শিরোনাম (বাংলা - ঐচ্ছিক)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="title_bn" placeholder="যেমন: প্রতিদিনের কেনাকাটায় ক্যাশব্যাক ও রিওয়ার্ড পয়েন্ট">
                                    <small class="text-muted">ব্যানার ছবিতে লেখা থাকলে এটি খালি রাখতে পারেন, অথবা অফার পেজের শিরোনাম হিসেবে ব্যবহৃত হবে।</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">সংক্ষিপ্ত বিবরণ (বাংলা - ঐচ্ছিক)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="description_bn" placeholder="যেমন: আপনার প্রয়োজন • আপনার সাশ্রয় • আপনার ইত্তেবা">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">বাটনের লেখা (Button Text)</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="button_text" value="এখনই কেনাকাটা করুন">
                                </div>
                                <label class="col-sm-2 control-label">প্রদর্শন ক্রম</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="sort_order" value="1">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">কাস্টম লিংক (URL - ঐচ্ছিক)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="button_link" placeholder="খালি রাখলে ব্যানারে ক্লিক করলে নিচের অফার বিস্তারিত পেজ খুলবে">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">অফার সম্পর্কে বিস্তারিত বিবরণ</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="offer_details" rows="5" placeholder="ব্যানারে ক্লিক করলে গ্রাহক এই অফার সম্পর্কে বিস্তারিত যা পড়তে পারবেন তা এখানে লিখুন..."></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">স্ট্যাটাস</label>
                                <div class="col-sm-8">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="is_active" value="1" checked>
                                            <strong>হোমপেজে প্রদর্শন করুন (Active)</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer text-right">
                            <a href="{{ route('slider.index') }}" class="btn btn-default">বাতিল</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> সংরক্ষণ করুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
