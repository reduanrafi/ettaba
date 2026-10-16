@extends('admin.layouts.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> উপজেলা / সিটি কর্পোরেশন সম্পাদনা</h3>
                    <div class="pull-right">
                        <a href="{{ route('geo.upazilas.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> তালিকায় ফিরুন</a>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger" style="margin: 15px;">
                        <ul style="margin-bottom: 0;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('geo.upazilas.update', $upazila->id) }}" class="form-horizontal">
                    @csrf
                    @method('PUT')
                    <div class="box-body" style="padding: 20px;">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">জেলা নির্বাচন করুন <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="district_id" class="form-control select2" required>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}" {{ (old('district_id', $upazila->district_id) == $d->id) ? 'selected' : '' }}>
                                            {{ $d->bn_name ?: $d->name }} ({{ $d->name }}) - {{ $d->division->bn_name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">বাংলা নাম <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="bn_name" class="form-control" value="{{ old('bn_name', $upazila->bn_name) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ইংরেজি নাম <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" value="{{ old('name', $upazila->name) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ধরন (Type) <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="type" class="form-control" required>
                                    <option value="upazila" {{ old('type', $upazila->type) == 'upazila' ? 'selected' : '' }}>উপজেলা (Upazila)</option>
                                    <option value="city_corporation" {{ old('type', $upazila->type) == 'city_corporation' ? 'selected' : '' }}>সিটি কর্পোরেশন (City Corporation)</option>
                                    <option value="cantonment" {{ old('type', $upazila->type) == 'cantonment' ? 'selected' : '' }}>সেনানিবাস (Cantonment)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">মোট ওয়ার্ড সংখ্যা</label>
                            <div class="col-sm-9">
                                <input type="number" name="ward_count" class="form-control" value="{{ old('ward_count', $upazila->ward_count) }}" min="0" max="200">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> আপডেট করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
