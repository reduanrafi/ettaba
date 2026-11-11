@extends('admin.layouts.layout')

@section('extra-style')
<style>
    .admin-shop-box {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
    }
    .admin-shop-box .box-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 24px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .admin-shop-box .box-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    .admin-form-card {
        padding: 24px;
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #0284c7;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e0f2fe;
    }
    .form-group label {
        font-weight: 600;
        color: #334155;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .form-control {
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13.5px;
        height: auto;
    }
    .admin-control-panel {
        background: #f1f5f9;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .logo-preview-box {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8fafc;
        margin-top: 10px;
    }
    .logo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="admin-shop-box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-cogs text-primary"></i> Admin Shop Management: {{ $shop->name_en ?: $shop->name_bn }}</h3>
                    <div class="pull-right">
                        <a href="{{ route('store.index') }}" class="btn btn-sm btn-default btn-flat">
                            <i class="fa fa-arrow-left"></i> Back to Shops List
                        </a>
                    </div>
                </div>

                <div class="admin-form-card">
                    @if(Session::has('success'))
                        @include('admin.layouts.message.success')
                    @elseif(Session::has('error'))
                        @include('admin.layouts.message.error')
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" style="border-radius: 8px;">
                            <ul style="margin-bottom: 0;">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="shop" id="shop_id" value="{{ $shop->id }}">

                        <!-- Admin Controls Section -->
                        <div class="admin-control-panel">
                            <h4 style="margin-top: 0; font-weight: 700; color: #0f172a;">
                                <i class="fa fa-sliders text-primary"></i> Admin Configuration & Approvals
                            </h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="social_approved">Social Media Links Status</label>
                                        <select class="form-control" id="social_approved" name="social_approved">
                                            <option value="1" {{ ($shop->social_approved == 1) ? 'selected' : '' }}>Approved (Visible on public seller page)</option>
                                            <option value="0" {{ ($shop->social_approved != 1) ? 'selected' : '' }}>Pending / Hidden</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="delivery_type">Delivery Mode</label>
                                        <select class="form-control" id="delivery_type" name="delivery_type">
                                            <option value="ettaba" {{ ($shop->delivery_type == 'ettaba') ? 'selected' : '' }}>Option 1: Ettaba Delivery System</option>
                                            <option value="self" {{ ($shop->delivery_type == 'self') ? 'selected' : '' }}>Option 2: Online Seller Self Delivery</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="erp_margin_percent">Per-Seller ERP Margin %</label>
                                        <div class="input-group">
                                            <input type="number" step="0.1" min="1" max="100" class="form-control"
                                                   id="erp_margin_percent" name="erp_margin_percent"
                                                   value="{{ old('erp_margin_percent', $shop->erp_margin_percent ?: 5.0) }}" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" style="margin-top: 10px;">
                                    <label>Allowed Product Categories for this Seller:</label>
                                    <div class="row" style="max-height: 160px; overflow-y: auto; background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                        @php
                                            $allowed = !empty($shop->allowed_categories) ? json_decode($shop->allowed_categories, true) : [];
                                            if (!is_array($allowed)) $allowed = [];
                                        @endphp
                                        @foreach($categories as $cat)
                                            <div class="col-md-4 col-sm-6">
                                                <label style="font-weight: 500; font-size: 13px; cursor: pointer;">
                                                    <input type="checkbox" name="allowed_categories[]" value="{{ $cat->id }}"
                                                           {{ (empty($allowed) || in_array($cat->id, $allowed)) ? 'checked' : '' }}>
                                                    {{ $cat->name_en ?: $cat->name_bn }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">If none selected, seller can post in all categories by default.</small>
                                </div>
                            </div>
                        </div>

                        <!-- 1. Shop Identity -->
                        <div class="form-section-title"><i class="fa fa-id-badge"></i> 1. Shop Identity</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Shop Name (English/Bangla) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $shop->name_en ?: $shop->name_bn) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Custom Shop URL (Slug) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                           value="{{ old('slug', $shop->slug) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Legal Details -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-file-text-o"></i> 2. Legal Details</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nid_tin">Seller NID/TIN Certificate No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nid_tin" name="nid_tin"
                                           value="{{ old('nid_tin', $shop->nid_tin ?: optional($shop->user->profile)->nid) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                           value="{{ old('date_of_birth', $shop->date_of_birth ? date('Y-m-d', strtotime($shop->date_of_birth)) : (optional($shop->user->profile)->date_of_birth ? date('Y-m-d', strtotime(optional($shop->user->profile)->date_of_birth)) : '')) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Address -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-map-marker"></i> 3. Address</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="division_id">Division <span class="text-danger">*</span></label>
                                    <select class="form-control" id="division_id" name="division_id" required onchange="loadDistricts(this.value)">
                                        <option value="">-- Select Division --</option>
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->id }}" {{ (old('division_id', $shop->division_id ?: optional($shop->user)->division_id) == $div->id) ? 'selected' : '' }}>
                                                {{ $div->bn_name ?: $div->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="district_id">District <span class="text-danger">*</span></label>
                                    <select class="form-control" id="district_id" name="district_id" required onchange="loadUpazilas(this.value)">
                                        @foreach($districts as $dst)
                                            <option value="{{ $dst->id }}" {{ (old('district_id', $shop->district_id ?: optional($shop->user)->district_id) == $dst->id) ? 'selected' : '' }}>
                                                {{ $dst->bn_name ?: $dst->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upazila_id">Upazila / City Corporation <span class="text-danger">*</span></label>
                                    <select class="form-control" id="upazila_id" name="upazila_id" required onchange="loadUnions(this.value)">
                                        @foreach($upazilas as $upz)
                                            <option value="{{ $upz->id }}" {{ (old('upazila_id', $shop->upazila_id ?: optional($shop->user)->upazila_id) == $upz->id) ? 'selected' : '' }}>
                                                {{ $upz->bn_name ?: $upz->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="union_name">Municipality / Union <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="union_name" name="union_name"
                                           value="{{ old('union_name', $shop->union_name ?: optional($shop->user->profile)->union_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ward_no">Ward No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ward_no" name="ward_no"
                                           value="{{ old('ward_no', $shop->ward_no ?: optional($shop->user->profile)->ward_no) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Delivery Area & Mobile -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-truck"></i> 4. Delivery Area & Mobile</div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="delivery_area_bn">Delivery area: (Bangla/English) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="delivery_area_bn" name="delivery_area_bn" rows="3" required>{{ old('delivery_area_bn', $shop->delivery_area_bn ?: $shop->delivery_area_en) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                           value="{{ old('mobile', $shop->mobile ?: optional($shop->user)->phone) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Social Links -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-share-alt"></i> 5. Social Links</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="youtube_link">Youtube channel link</label>
                                    <input type="url" class="form-control" id="youtube_link" name="youtube_link"
                                           value="{{ old('youtube_link', $shop->youtube_link) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fb_link">Facebook page link</label>
                                    <input type="url" class="form-control" id="fb_link" name="fb_link"
                                           value="{{ old('fb_link', $shop->fb_link) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instagram_link">Instagram profile link</label>
                                    <input type="url" class="form-control" id="instagram_link" name="instagram_link"
                                           value="{{ old('instagram_link', $shop->instagram_link) }}">
                                </div>
                            </div>
                        </div>

                        <!-- 6. Logo Image -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-image"></i> 6. Logo Image</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo_image">Change Logo Image (Optional)</label>
                                    <input type="file" class="form-control" id="logo_image" name="logo_image"
                                           accept="image/*" onchange="previewLogo(event)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Current Logo Preview:</label>
                                <div class="logo-preview-box">
                                    <img id="logoOutput" src="{{ !empty($shop->logo_image) ? asset($shop->logo_image) : asset('assets/images/logo/logo.png') }}" alt="Shop Logo">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 8px; font-weight: 700; padding: 12px 30px;">
                                    <i class="fa fa-save"></i> Save Admin Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function loadDistricts(divId) {
        var districtSelect = document.getElementById('district_id');
        var upazilaSelect = document.getElementById('upazila_id');
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        if (!divId) return;
        fetch("{{ url('private-panel/ajax/geo/districts') }}/" + divId)
            .then(res => res.json())
            .then(data => {
                var html = '<option value="">-- Select District --</option>';
                data.forEach(function(d) {
                    html += '<option value="' + d.id + '">' + (d.bn_name || d.name) + '</option>';
                });
                districtSelect.innerHTML = html;
            });
    }

    function loadUpazilas(distId) {
        var upazilaSelect = document.getElementById('upazila_id');
        upazilaSelect.innerHTML = '<option value="">Loading...</option>';
        if (!distId) return;
        fetch("{{ url('private-panel/ajax/geo/upazilas') }}/" + distId)
            .then(res => res.json())
            .then(data => {
                var html = '<option value="">-- Select Upazila --</option>';
                data.forEach(function(u) {
                    html += '<option value="' + u.id + '">' + (u.bn_name || u.name) + '</option>';
                });
                upazilaSelect.innerHTML = html;
            });
    }

    function previewLogo(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('logoOutput');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection

