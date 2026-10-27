@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        পুরস্কার সম্পাদনা করুন
        <small>#{{ $reward->level_no }} - {{ $reward->title }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('admin.rewards.index', $reward->account_type) }}">পুরস্কার তালিকা</a></li>
        <li class="active">সম্পাদনা</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit text-primary"></i> পুরস্কারের তথ্য পরিবর্তন</h3>
                    <a href="{{ route('admin.rewards.index', $reward->account_type) }}" class="btn btn-default btn-sm pull-right">
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

                <form method="POST" action="{{ route('admin.rewards.update', $reward->id) }}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="box-body" style="padding: 25px;">
                        <!-- Account Type -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">অ্যাকাউন্ট টাইপ <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="account_type" class="form-control" required>
                                    <option value="customer" {{ $reward->account_type == 'customer' ? 'selected' : '' }}>কাস্টমার (Customer Account)</option>
                                    <option value="partner" {{ $reward->account_type == 'partner' ? 'selected' : '' }}>পার্টনার (Partner Account)</option>
                                    <option value="merchant" {{ $reward->account_type == 'merchant' ? 'selected' : '' }}>মার্চেন্ট (Merchant Account)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Level No & Sort Order -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কার লেভেল নং <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="number" name="level_no" value="{{ old('level_no', $reward->level_no) }}" class="form-control" required min="1">
                            </div>
                            <label class="col-sm-2 control-label">প্রদর্শন ক্রম</label>
                            <div class="col-sm-3">
                                <input type="number" name="sort_order" value="{{ old('sort_order', $reward->sort_order) }}" class="form-control">
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কার টাইটেল <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="title" value="{{ old('title', $reward->title) }}" class="form-control" required>
                            </div>
                        </div>

                        <!-- Subtitle -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">সাব-টাইটেল / সংক্ষিপ্ত বিবরণ</label>
                            <div class="col-sm-9">
                                <input type="text" name="subtitle" value="{{ old('subtitle', $reward->subtitle) }}" class="form-control">
                            </div>
                        </div>

                        <!-- Reward Name & Points -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">উপহারের নাম (Reward Name) <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" name="reward_name" value="{{ old('reward_name', $reward->reward_name) }}" class="form-control" required>
                            </div>
                            <label class="col-sm-2 control-label">প্রয়োজনীয় পয়েন্ট <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="number" name="required_points" value="{{ old('required_points', $reward->required_points) }}" class="form-control" required min="1">
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কারের ছবি</label>
                            <div class="col-sm-9">
                                <input type="file" name="image" id="reward_image_input" onchange="loadFile(event)" accept="image/*">
                                <span class="help-block text-muted">নতুন ছবি আপলোড করতে চাইলে নির্বাচন করুন।</span>
                                <div style="margin-top: 10px;">
                                    @if(!empty($reward->image))
                                        <img src="{{ asset($reward->image) }}" id="current_image" style="max-width: 120px; max-height: 120px; border-radius: 8px; border: 1px solid #ddd; padding: 3px;">
                                    @endif
                                    <img id="output" style="max-width: 120px; max-height: 120px; border-radius: 8px; border: 1px dashed #27ae60; padding: 3px; display: none;">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">বিস্তারিত বিবরণ</label>
                            <div class="col-sm-9">
                                <textarea name="description" rows="3" class="form-control">{{ old('description', $reward->description) }}</textarea>
                            </div>
                        </div>

                        <hr>
                        <h4 style="margin-left: 15px; color: #2c3e50;"><i class="fa fa-list-ul"></i> ডিটেইলস পেইজের ডাইনামিক সেকশন</h4>

                        <!-- Info Section -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">তথ্য সেকশন শিরোনাম</label>
                            <div class="col-sm-9">
                                <input type="text" name="info_title" value="{{ old('info_title', $reward->info_title) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">তথ্যসমূহ (প্রতি লাইনে একটি)</label>
                            <div class="col-sm-9">
                                <textarea name="info_items" rows="3" class="form-control">{{ old('info_items', $reward->info_items) }}</textarea>
                            </div>
                        </div>

                        <!-- Howto Section -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পয়েন্ট অর্জনের শিরোনাম</label>
                            <div class="col-sm-9">
                                <input type="text" name="howto_title" value="{{ old('howto_title', $reward->howto_title) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পয়েন্ট অর্জনের নিয়ম (প্রতি লাইনে একটি)</label>
                            <div class="col-sm-9">
                                <textarea name="howto_items" rows="3" class="form-control">{{ old('howto_items', $reward->howto_items) }}</textarea>
                            </div>
                        </div>

                        <!-- Is Visible -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_visible" value="1" {{ $reward->is_visible ? 'checked' : '' }}> 
                                        <strong>পুরস্কারটি তালিকায় প্রদর্শন (Show) করুন</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <a href="{{ route('admin.rewards.index', $reward->account_type) }}" class="btn btn-default">বাতিল</a>
                        <button type="submit" class="btn btn-primary" style="padding-left: 25px; padding-right: 25px;">
                            <i class="fa fa-save"></i> পরিবর্তন সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
