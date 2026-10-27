@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        নতুন পুরস্কার যোগ করুন
        <small>অ্যাকাউন্ট অনুযায়ী পুরস্কার কনফিগারেশন</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('admin.rewards.index', $currentType) }}">পুরস্কার তালিকা</a></li>
        <li class="active">নতুন যোগ</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-gift text-primary"></i> পুরস্কারের তথ্য ফরম</h3>
                    <a href="{{ route('admin.rewards.index', $currentType) }}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-arrow-left"></i> তালিকায় ফিরে যান
                    </a>
                </div>

                <div class="col-md-8 col-md-offset-2" id="messageDiv" style="margin-top: 10px;">
                    @if(Session::has('success'))
                        @include('admin.layouts.message.success')
                    @elseif(Session::has('error'))
                        @include('admin.layouts.message.error')
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.rewards.store') }}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="box-body" style="padding: 25px;">
                        <!-- Account Type -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">অ্যাকাউন্ট টাইপ <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="account_type" class="form-control" required>
                                    <option value="customer" {{ $currentType == 'customer' ? 'selected' : '' }}>কাস্টমার (Customer Account)</option>
                                    <option value="partner" {{ $currentType == 'partner' ? 'selected' : '' }}>পার্টনার (Partner Account)</option>
                                    <option value="merchant" {{ $currentType == 'merchant' ? 'selected' : '' }}>মার্চেন্ট (Merchant Account)</option>
                                </select>
                                <span class="help-block text-muted">এই পুরস্কারটি কোন ধরনের ব্যবহারকারীর জন্য প্রযোজ্য হবে।</span>
                            </div>
                        </div>

                        <!-- Level No & Sort Order -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কার লেভেল নং <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="number" name="level_no" value="{{ old('level_no', $nextLevel) }}" class="form-control" required min="1">
                                <span class="help-block text-muted">যেমন: 1, 2, 7 ইত্যাদি</span>
                            </div>
                            <label class="col-sm-2 control-label">প্রদর্শন ক্রম</label>
                            <div class="col-sm-3">
                                <input type="number" name="sort_order" value="{{ old('sort_order', $nextLevel) }}" class="form-control">
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কার টাইটেল <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="যেমন: Ettaba Premium Customer" required>
                            </div>
                        </div>

                        <!-- Subtitle -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">সাব-টাইটেল / সংক্ষিপ্ত তথ্য</label>
                            <div class="col-sm-9">
                                <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control" placeholder="যেমন: আকর্ষণীয় রাইস কুকার উপহার">
                            </div>
                        </div>

                        <!-- Reward Name & Points -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">উপহারের নাম (Reward Name) <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" name="reward_name" value="{{ old('reward_name') }}" class="form-control" placeholder="যেমন: রাইস কুকার" required>
                            </div>
                            <label class="col-sm-2 control-label">প্রয়োজনীয় পয়েন্ট <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="number" name="required_points" value="{{ old('required_points', 1000) }}" class="form-control" required min="1">
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কারের ছবি</label>
                            <div class="col-sm-9">
                                <input type="file" name="image" id="reward_image_input" onchange="loadFile(event)" accept="image/*">
                                <span class="help-block text-muted">JPG, PNG বা WEBP ছবি আপলোড করুন (সর্বোচ্চ 3MB)।</span>
                                <div style="margin-top: 10px;">
                                    <img id="output" style="max-width: 120px; max-height: 120px; border-radius: 8px; border: 1px dashed #ccc; padding: 3px; display: none;">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">বিস্তারিত বিবরণ</label>
                            <div class="col-sm-9">
                                <textarea name="description" rows="3" class="form-control" placeholder="পুরস্কার সম্পর্কিত বিস্তারিত তথ্য লিখুন...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <hr>
                        <h4 style="margin-left: 15px; color: #2c3e50;"><i class="fa fa-list-ul"></i> ডিটেইলস পেইজের ডাইনামিক সেকশন</h4>

                        <!-- Info Section -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">তথ্য সেকশন শিরোনাম</label>
                            <div class="col-sm-9">
                                <input type="text" name="info_title" value="{{ old('info_title', 'পুরস্কার সম্পর্কিত তথ্য:') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">তথ্যসমূহ (প্রতি লাইনে একটি)</label>
                            <div class="col-sm-9">
                                <textarea name="info_items" rows="3" class="form-control" placeholder="প্রতি লাইনে একটি করে বুলেট পয়েন্ট লিখুন...">{{ old('info_items') }}</textarea>
                            </div>
                        </div>

                        <!-- Howto Section -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পয়েন্ট অর্জনের শিরোনাম</label>
                            <div class="col-sm-9">
                                <input type="text" name="howto_title" value="{{ old('howto_title', 'কীভাবে Point অর্জন করবেন?') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পয়েন্ট অর্জনের নিয়ম (প্রতি লাইনে একটি)</label>
                            <div class="col-sm-9">
                                <textarea name="howto_items" rows="3" class="form-control" placeholder="প্রতি লাইনে একটি করে পয়েন্ট নির্দেশিকা লিখুন...">{{ old('howto_items') }}</textarea>
                            </div>
                        </div>

                        <!-- Is Visible -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_visible" value="1" checked> 
                                        <strong>পুরস্কারটি তালিকায় প্রদর্শন (Show) করুন</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <a href="{{ route('admin.rewards.index', $currentType) }}" class="btn btn-default">বাতিল</a>
                        <button type="submit" class="btn btn-primary" style="padding-left: 25px; padding-right: 25px;">
                            <i class="fa fa-save"></i> সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
