@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-plus-circle text-success"></i> নতুন কাস্টম পেজ তৈরি করুন
        <a href="{{ route('admin.customPages.index') }}" class="btn btn-sm btn-default pull-right">
            <i class="fa fa-arrow-left"></i> পেজ তালিকায় ফিরে যান
        </a>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-success">
                <form action="{{ route('admin.customPages.store') }}" method="POST">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>পেজের শিরোনাম (Page Title) <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: Terms & Conditions, Privacy Policy, রেজিস্ট্রেশন শর্তাবলী" required value="{{ old('title') }}">
                        </div>

                        <div class="form-group">
                            <label>কাস্টম ইউআরএল (Custom URL Slug) <small class="text-muted">(ঐচ্ছিক, ফাঁকা রাখলে স্বয়ংক্রিয়ভাবে তৈরি হবে)</small></label>
                            <input type="text" name="slug" class="form-control" placeholder="যেমন: terms-and-conditions, refund-policy" value="{{ old('slug') }}">
                        </div>

                        <div class="form-group">
                            <label>পেজের মূল বিষয়বস্তু (Content) <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="12" placeholder="পেজের সকল শর্তাবলী, নোটিশ বা পলিসি টেক্সট এখানে বিস্তারিত লিখুন..." required>{{ old('content') }}</textarea>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_published" value="1" checked> অবিলম্বে ওয়েবসাইটে প্রকাশ করুন (Published)
                            </label>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> পেজ সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

