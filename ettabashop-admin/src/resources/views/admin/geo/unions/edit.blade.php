@extends('admin.layouts.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> ইউনিয়ন / পৌরসভা সম্পাদনা</h3>
                    <div class="pull-right">
                        <a href="{{ route('geo.unions.index', ['upazila_id' => $union->upazila_id]) }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> তালিকায় ফিরুন</a>
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

                <form method="POST" action="{{ route('geo.unions.update', $union->id) }}" class="form-horizontal">
                    @csrf
                    @method('PUT')
                    <div class="box-body" style="padding: 20px;">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">উপজেলা / সিটি কর্পোরেশন <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="upazila_id" id="upazila_select" class="form-control" required>
                                    @foreach($upazilas as $u)
                                        <option value="{{ $u->id }}" {{ old('upazila_id', $union->upazila_id) == $u->id ? 'selected' : '' }}>
                                            {{ $u->bn_name ?: $u->name }} ({{ $u->district->bn_name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">বাংলা নাম <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="bn_name" class="form-control" value="{{ old('bn_name', $union->bn_name) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ইংরেজি নাম <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" value="{{ old('name', $union->name) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ধরন (Type) <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="type" class="form-control" required>
                                    <option value="union" {{ old('type', $union->type) == 'union' ? 'selected' : '' }}>ইউনিয়ন (Union)</option>
                                    <option value="municipality" {{ old('type', $union->type) == 'municipality' ? 'selected' : '' }}>পৌরসভা (Municipality)</option>
                                    <option value="zone" {{ old('type', $union->type) == 'zone' ? 'selected' : '' }}>অঞ্চল / জোন (Zone)</option>
                                    <option value="ward" {{ old('type', $union->type) == 'ward' ? 'selected' : '' }}>ওয়ার্ড (Ward)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ওয়ার্ড সংখ্যা</label>
                            <div class="col-sm-9">
                                <input type="number" name="ward_count" class="form-control" value="{{ old('ward_count', $union->ward_count) }}" min="1" max="100">
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
