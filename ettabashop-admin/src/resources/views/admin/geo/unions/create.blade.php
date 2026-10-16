@extends('admin.layouts.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-circle"></i> নতুন ইউনিয়ন / পৌরসভা / অঞ্চল যোগ করুন</h3>
                    <div class="pull-right">
                        <a href="{{ route('geo.unions.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> তালিকায় ফিরুন</a>
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

                <form method="POST" action="{{ route('geo.unions.store') }}" class="form-horizontal">
                    @csrf
                    <div class="box-body" style="padding: 20px;">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">জেলা নির্বাচন করুন</label>
                            <div class="col-sm-9">
                                <select id="district_select" class="form-control select2">
                                    <option value="" disabled selected>-- জেলা নির্বাচন করুন (উপজেলা ফিল্টার করতে) --</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}" {{ $selectedDistrictId == $d->id ? 'selected' : '' }}>
                                            {{ $d->bn_name ?: $d->name }} ({{ $d->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">উপজেলা / সিটি কর্পোরেশন <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="upazila_id" id="upazila_select" class="form-control" required>
                                    <option value="" disabled selected>-- উপজেলা নির্বাচন করুন --</option>
                                    @foreach($upazilas as $u)
                                        <option value="{{ $u->id }}" {{ old('upazila_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->bn_name ?: $u->name }} ({{ $u->district->bn_name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">বাংলা নাম <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="bn_name" class="form-control" value="{{ old('bn_name') }}" placeholder="যেমন: বনানী / বাড্ডা / আমিনবাজার" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ইংরেজি নাম <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Banani / Badda / Aminbazar" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ধরন (Type) <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="type" class="form-control" required>
                                    <option value="union" {{ old('type') == 'union' ? 'selected' : '' }}>ইউনিয়ন (Union)</option>
                                    <option value="municipality" {{ old('type') == 'municipality' ? 'selected' : '' }}>পৌরসভা (Municipality)</option>
                                    <option value="zone" {{ old('type') == 'zone' ? 'selected' : '' }}>অঞ্চল / জোন (Zone)</option>
                                    <option value="ward" {{ old('type') == 'ward' ? 'selected' : '' }}>ওয়ার্ড (Ward)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">ওয়ার্ড সংখ্যা</label>
                            <div class="col-sm-9">
                                <input type="number" name="ward_count" class="form-control" value="{{ old('ward_count', 9) }}" min="1" max="100">
                                <span class="help-block text-muted">সাধারণত বাংলাদেশের প্রতিটি ইউনিয়নে ৯টি ওয়ার্ড থাকে (ডিফল্ট: ৯)।</span>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-script')
<script>
    $('#district_select').on('change', function() {
        let distId = $(this).val();
        let upzSelect = $('#upazila_select');
        upzSelect.html('<option value="">লোড হচ্ছে...</option>');

        $.get("{{ url('/private-panel/geo/ajax/upazilas') }}/" + distId, function(data) {
            let options = '<option value="" disabled selected>-- উপজেলা নির্বাচন করুন --</option>';
            $.each(data, function(i, item) {
                options += '<option value="' + item.id + '">' + (item.bn_name || item.name) + '</option>';
            });
            upzSelect.html(options);
        });
    });
</script>
@endsection
