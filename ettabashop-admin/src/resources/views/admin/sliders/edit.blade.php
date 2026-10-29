@extends('admin.layouts.layout')
@section('content')
    <section class="content-header">
        <h1>ব্যানার ও অফার সম্পাদনা (Edit Banner)</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">ব্যানার আপডেট করুন</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('slider.index') }}" class="btn btn-primary btn-flat btn-sm">
                                <i class="fa fa-list"></i> সকল ব্যানার দেখুন
                            </a>
                        </div>
                    </div>

                    <form class="form-horizontal" action="{{ route('slider.update', ['slider' => $slider->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {!! method_field('PUT') !!}
                        <div class="box-body">
                            @if(Session::has('success'))
                                @include('admin.layouts.message.success')
                            @elseif(Session::has('error'))
                                @include('admin.layouts.message.error')
                            @endif

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ব্যানার ছবি পরিবর্তন</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="image" onchange="loadFile(event)" accept="image/*">
                                    @if(!empty($slider->image))
                                        <p style="margin-top: 8px;"><img id="output" src="{{ asset($slider->image) }}" width="300" style="border-radius: 8px;"/></p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ব্যানার শিরোনাম (বাংলা)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="title_bn" value="{{ $slider->title_bn ?: $slider->title_en }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">সংক্ষিপ্ত বিবরণ (বাংলা)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="description_bn" value="{{ $slider->description_bn ?: $slider->description_en }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">বাটনের লেখা (Button Text)</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="button_text" value="{{ $slider->button_text ?: 'এখনই কেনাকাটা করুন' }}">
                                </div>
                                <label class="col-sm-2 control-label">প্রদর্শন ক্রম</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="sort_order" value="{{ $slider->sort_order ?? 1 }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">কাস্টম লিংক (URL - ঐচ্ছিক)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="button_link" value="{{ $slider->button_link }}" placeholder="খালি রাখলে ব্যানারে ক্লিক করলে অফার বিস্তারিত পেজ খুলবে">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">অফার সম্পর্কে বিস্তারিত বিবরণ</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="offer_details" rows="5">{{ $slider->offer_details }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">স্ট্যাটাস</label>
                                <div class="col-sm-8">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="is_active" value="1" {{ (!isset($slider->is_active) || $slider->is_active) ? 'checked' : '' }}>
                                            <strong>হোমপেজে প্রদর্শন করুন (Active)</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer text-right">
                            <a href="{{ route('slider.index') }}" class="btn btn-default">বাতিল</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> আপডেট করুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
