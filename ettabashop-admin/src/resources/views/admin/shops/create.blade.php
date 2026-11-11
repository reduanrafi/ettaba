@extends('admin.layouts.layout')

@section('extra-style')
<style>
    .shop-profile-box {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
    }
    .shop-profile-box .box-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 24px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .shop-profile-box .box-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    .shop-form-card {
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
        transition: all 0.2s ease;
        height: auto;
    }
    .form-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .input-hint {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }
    .slug-input-group {
        display: flex;
        gap: 8px;
    }
    .btn-check-slug {
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 16px;
        white-space: nowrap;
        background: #0284c7;
        color: #fff;
        border: none;
    }
    .btn-check-slug:hover {
        background: #0369a1;
        color: #fff;
    }
    .slug-status-badge {
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
        display: none;
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
            <div class="shop-profile-box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-store text-primary"></i> Create Shop Profile</h3>
                    <div class="pull-right">
                        <a href="{{ route('shop.index') }}" class="btn btn-sm btn-default btn-flat">
                            <i class="fa fa-arrow-left"></i> Back to Shop
                        </a>
                    </div>
                </div>

                <div class="shop-form-card">
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

                    <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- 1. Basic Shop Info -->
                        <div class="form-section-title"><i class="fa fa-id-badge"></i> 1. Shop Identity</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Shop Name (English/Bangla) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name') }}" placeholder="e.g. Gadgets Bro / গ্যাজেটস ব্রো"
                                           required oninput="autoUpdateSlug(this.value)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Custom Shop URL (Slug) <span class="text-danger">*</span></label>
                                    <div class="slug-input-group">
                                        <input type="text" class="form-control" id="slug" name="slug"
                                               value="{{ old('slug') }}" placeholder="e.g. Gadgets_Bro" required>
                                        <button type="button" class="btn btn-check-slug" onclick="verifyShopSlug()">
                                            <i class="fa fa-search"></i> Check
                                        </button>
                                    </div>
                                    <small class="input-hint">
                                        Your public shop link: <strong>ettabashop.com/<span id="slugPreviewText">Gadgets_Bro</span></strong>
                                    </small>
                                    <div id="slugStatusMsg" class="slug-status-badge"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Legal / Registration Details -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-file-text-o"></i> 2. Legal & Identification</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nid_tin">Seller NID/TIN Certificate No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nid_tin" name="nid_tin"
                                           value="{{ old('nid_tin') }}"
                                           placeholder="ব্যক্তি মালিকানা ব্যবসার ক্ষেত্রে NID এবং কোম্পানির ক্ষেত্রে TIN Certificate No ব্যবহার করুন"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                           value="{{ old('date_of_birth') }}"
                                           placeholder="NID এর ক্ষেত্রে জন্ম তারিখ এবং TIN এর ক্ষেত্রে TIN তৈরি তারিখ উল্লেখ করুন"
                                           required>
                                    <small class="input-hint">NID এর ক্ষেত্রে জন্ম তারিখ এবং TIN এর ক্ষেত্রে TIN তৈরি তারিখ উল্লেখ করুন</small>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Cascading Shop Address -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-map-marker"></i> 3. Shop Address</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="division_id">Division <span class="text-danger">*</span></label>
                                    <select class="form-control" id="division_id" name="division_id" required onchange="loadDistricts(this.value)">
                                        <option value="">-- Select Division --</option>
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>
                                                {{ $div->bn_name ?: $div->name }} ({{ $div->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="district_id">District <span class="text-danger">*</span></label>
                                    <select class="form-control" id="district_id" name="district_id" required onchange="loadUpazilas(this.value)">
                                        <option value="">-- Select Division First --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upazila_id">Upazila / City Corporation <span class="text-danger">*</span></label>
                                    <select class="form-control" id="upazila_id" name="upazila_id" required onchange="loadUnions(this.value)">
                                        <option value="">-- Select District First --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="union_name">Municipality / Union <span class="text-danger">*</span></label>
                                    <div class="input-group" style="width: 100%;">
                                        <select class="form-control" id="union_select" onchange="applyUnionSelection(this.value)">
                                            <option value="">-- Select Union / Municipality --</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" id="union_name" name="union_name"
                                           value="{{ old('union_name') }}" placeholder="বা সরাসরি ইউনিয়ন/পৌরসভার নাম লিখুন"
                                           style="margin-top: 6px;" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ward_no">Ward No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ward_no" name="ward_no"
                                           value="{{ old('ward_no') }}" placeholder="e.g. 01, 02, বা ১, ২" required>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Delivery Area & Contact -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-truck"></i> 4. Delivery Area & Mobile</div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="delivery_area_bn">Delivery area: (Bangla/English) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="delivery_area_bn" name="delivery_area_bn" rows="3"
                                              placeholder="আপনার পণ্য কোথায় ডেলিভারি দেওয়া হয় স্পষ্ট করে লিখুন। যেমন- নির্দিষ্ট কোনো জেলা, উপজেলা, পৌরসভা বা ইউনিয়ন? নাকি সমগ্র বাংলাদেশ? সেটা লিখুন। একাধিক এলাকা লিখতে কমা (,) ব্যবহার করুন"
                                              required>{{ old('delivery_area_bn') }}</textarea>
                                    <small class="input-hint">
                                        সেলার পেজে এবং প্রোডাক্ট ডিটেইলস পেজে 'এই সেলার ডেলিভারি দেয়-' এর পাশে এটি দেখাবে।
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                           value="{{ old('mobile', Auth::user()->phone ?? '') }}"
                                           placeholder="e.g. 01712345678" maxlength="11" pattern="01[0-9]{9}" required>
                                    <small class="input-hint">English digits only, must start with 01 and be exactly 11 digits.</small>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Social Media Links -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-share-alt"></i> 5. Social Media Links (Optional)</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="youtube_link">Youtube channel link</label>
                                    <input type="url" class="form-control" id="youtube_link" name="youtube_link"
                                           value="{{ old('youtube_link') }}" placeholder="https://youtube.com/@channel">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fb_link">Facebook page link</label>
                                    <input type="url" class="form-control" id="fb_link" name="fb_link"
                                           value="{{ old('fb_link') }}" placeholder="https://facebook.com/page">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instagram_link">Instagram profile link</label>
                                    <input type="url" class="form-control" id="instagram_link" name="instagram_link"
                                           value="{{ old('instagram_link') }}" placeholder="https://instagram.com/profile">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <small class="text-muted" style="font-weight: 600; display: block; margin-top: -5px; margin-bottom: 15px;">
                                    <i class="fa fa-info-circle text-info"></i> লোকাল অফিস/এডমিনের অনুমোদন সাপেক্ষে এই প্রোফাইল আপনার সেলার পেজে শো করবে।
                                </small>
                            </div>
                        </div>

                        <!-- 6. Logo Image -->
                        <div class="form-section-title" style="margin-top: 15px;"><i class="fa fa-image"></i> 6. Shop Logo</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo_image">Logo image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="logo_image" name="logo_image"
                                           accept="image/*" onchange="previewLogo(event)" required>
                                    <small class="input-hint">Square format recommended (200x200px or larger).</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Logo Preview:</label>
                                <div class="logo-preview-box">
                                    <img id="logoOutput" src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo Preview">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success btn-lg" style="border-radius: 8px; font-weight: 700; padding: 12px 30px;">
                                    <i class="fa fa-save"></i> Save Shop Profile
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
    function autoUpdateSlug(val) {
        var formatted = val.trim().replace(/\s+/g, '_').replace(/[^A-Za-z0-9_\-\u0980-\u09FF]/g, '');
        var slugInput = document.getElementById('slug');
        var preview = document.getElementById('slugPreviewText');
        if (slugInput && !slugInput.dataset.manuallyEdited) {
            slugInput.value = formatted;
        }
        if (preview) preview.textContent = (slugInput ? slugInput.value : formatted) || 'Gadgets_Bro';
    }

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manuallyEdited = "1";
        var preview = document.getElementById('slugPreviewText');
        if (preview) preview.textContent = this.value || 'Gadgets_Bro';
    });

    function verifyShopSlug() {
        var slugVal = document.getElementById('slug').value.trim();
        var statusBadge = document.getElementById('slugStatusMsg');
        if (!slugVal) {
            alert('Please enter a shop slug to check.');
            return;
        }
        statusBadge.style.display = 'block';
        statusBadge.innerHTML = '<span class="text-info"><i class="fa fa-spinner fa-spin"></i> Checking availability...</span>';

        fetch("{{ route('ajax.checkShopSlug') }}?slug=" + encodeURIComponent(slugVal))
            .then(res => res.json())
            .then(data => {
                if (data.available) {
                    statusBadge.innerHTML = '<span class="text-success"><i class="fa fa-check-circle"></i> ' + data.message + '</span>';
                } else {
                    statusBadge.innerHTML = '<span class="text-danger"><i class="fa fa-times-circle"></i> ' + data.message + '</span>';
                }
            })
            .catch(err => {
                statusBadge.innerHTML = '<span class="text-warning"><i class="fa fa-exclamation-triangle"></i> Verification failed, please try again.</span>';
            });
    }

    function loadDistricts(divId) {
        var districtSelect = document.getElementById('district_id');
        var upazilaSelect = document.getElementById('upazila_id');
        var unionSelect = document.getElementById('union_select');

        districtSelect.innerHTML = '<option value="">Loading districts...</option>';
        upazilaSelect.innerHTML = '<option value="">-- Select District First --</option>';
        unionSelect.innerHTML = '<option value="">-- Select Upazila First --</option>';

        if (!divId) return;

        fetch("{{ url('private-panel/ajax/geo/districts') }}/" + divId)
            .then(res => res.json())
            .then(data => {
                var html = '<option value="">-- Select District --</option>';
                data.forEach(function(d) {
                    html += '<option value="' + d.id + '">' + (d.bn_name || d.name) + ' (' + d.name + ')</option>';
                });
                districtSelect.innerHTML = html;
            });
    }

    function loadUpazilas(distId) {
        var upazilaSelect = document.getElementById('upazila_id');
        var unionSelect = document.getElementById('union_select');

        upazilaSelect.innerHTML = '<option value="">Loading upazilas...</option>';
        unionSelect.innerHTML = '<option value="">-- Select Upazila First --</option>';

        if (!distId) return;

        fetch("{{ url('private-panel/ajax/geo/upazilas') }}/" + distId)
            .then(res => res.json())
            .then(data => {
                var html = '<option value="">-- Select Upazila / City Corp --</option>';
                data.forEach(function(u) {
                    html += '<option value="' + u.id + '">' + (u.bn_name || u.name) + ' (' + u.name + ')</option>';
                });
                upazilaSelect.innerHTML = html;
            });
    }

    function loadUnions(upzId) {
        var unionSelect = document.getElementById('union_select');
        unionSelect.innerHTML = '<option value="">Loading unions...</option>';

        if (!upzId) return;

        fetch("{{ url('private-panel/ajax/geo/unions') }}/" + upzId)
            .then(res => res.json())
            .then(data => {
                var html = '<option value="">-- Select Union / Municipality --</option>';
                if (data && data.length > 0) {
                    data.forEach(function(un) {
                        html += '<option value="' + (un.bn_name || un.name) + '">' + (un.bn_name || un.name) + '</option>';
                    });
                } else {
                    html += '<option value="">(উপরে সরাসরি নাম টাইপ করুন)</option>';
                }
                unionSelect.innerHTML = html;
            });
    }

    function applyUnionSelection(val) {
        if (val) {
            document.getElementById('union_name').value = val;
        }
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
