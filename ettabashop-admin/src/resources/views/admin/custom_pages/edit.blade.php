@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-edit text-primary"></i> পেজ সম্পাদনা করুন — {{ $page->title }}
        <a href="{{ route('admin.customPages.index') }}" class="btn btn-sm btn-default pull-right">
            <i class="fa fa-arrow-left"></i> পেজ তালিকায় ফিরে যান
        </a>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <form action="{{ route('admin.customPages.update', $page->id) }}" method="POST">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>পেজের শিরোনাম (Page Title) <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title', $page->title) }}">
                        </div>

                        <div class="form-group">
                            <label>কাস্টম ইউআরএল (Custom URL Slug)</label>
                            <input type="text" name="slug" class="form-control" required value="{{ old('slug', $page->slug) }}">
                        </div>

                        <div class="form-group">
                            <label>পেজের মূল বিষয়বস্তু (Content) <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="12" required>{{ old('content', $page->content) }}</textarea>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_published" value="1" {{ $page->is_published ? 'checked' : '' }}> অবিলম্বে ওয়েবসাইটে প্রকাশ করুন (Published)
                            </label>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> আপডেট সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

