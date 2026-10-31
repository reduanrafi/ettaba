<div class="dash-settings-card">
    <!-- Settings Header -->
    <div class="settings-section-header">
        <div class="settings-section-icon">
            <i class="fas fa-user-edit"></i>
        </div>
        <div class="flex-grow-1">
            <h4 class="settings-section-title">প্রোফাইল তথ্য ও সেটিংস</h4>
            <p class="settings-section-subtitle">আপনার ব্যক্তিগত তথ্য, পরিচিতি ও ডেলিভারি ঠিকানা আপডেট করুন</p>
        </div>
        @if(isset($profile) && optional($profile->user)->is_approved == 1)
            <span class="badge badge-success px-3 py-2 d-none d-sm-inline-flex align-items-center" style="font-size: 12px; border-radius: 50px;">
                <i class="fas fa-check-circle mr-1"></i> ভেরিফাইড প্রোফাইল
            </span>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(Session::has('error'))
        @include('website.layouts.message.error')
    @endif
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px;">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('profile.save') }}" enctype="multipart/form-data">
        @csrf

        <!-- Block 1: Profile Photo -->
        <div class="form-card-block">
            <h6 class="form-card-title">
                <i class="fas fa-camera"></i> প্রোফাইল ছবি (ঐচ্ছিক)
            </h6>
            <div class="avatar-upload-block">
                <div>
                    <img id="output" 
                         class="avatar-preview-circle" 
                         src="{{ isset($profile->profile_image) && $profile->profile_image != '' ? asset($profile->profile_image) : asset('assets/images/user.png') }}"
                         alt="Avatar Preview">
                </div>
                <div class="avatar-upload-btn-wrap">
                    <label for="image" class="btn-upload-avatar mb-0">
                        <i class="fas fa-cloud-upload-alt"></i> নতুন ছবি নির্বাচন করুন
                    </label>
                    <input type="file" 
                           id="image" 
                           name="profile_image" 
                           onchange="loadFile(event)" 
                           accept="image/*" 
                           style="display: none;">
                    <small class="text-muted" style="font-size: 12px;">
                        পরিষ্কার মুখের ছবি ব্যবহার করুন (JPG, PNG বা JPEG, সর্বোচ্চ 2MB)
                    </small>
                </div>
            </div>
        </div>

        <!-- Block 2: Personal Information -->
        <div class="form-card-block">
            <h6 class="form-card-title">
                <i class="fas fa-user"></i> ব্যক্তিগত তথ্য (Personal Details)
            </h6>
            
            <div class="row">
                <!-- First Name -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>নামের প্রথম অংশ (First Name)</span>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-user dash-input-icon"></i>
                        <input type="text" 
                               name="first_name" 
                               required 
                               class="dash-control" 
                               value="{{ isset($profile->first_name) ? $profile->first_name : '' }}" 
                               placeholder="উদাঃ মোঃ রহিম">
                    </div>
                </div>

                <!-- Last Name -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>নামের শেষ অংশ (Last Name)</span>
                        <span class="input-label-badge">ঐচ্ছিক</span>
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-user dash-input-icon"></i>
                        <input type="text" 
                               name="last_name" 
                               class="dash-control" 
                               value="{{ isset($profile->last_name) ? $profile->last_name : '' }}" 
                               placeholder="উদাঃ চৌধুরী">
                    </div>
                </div>

                <!-- Father's Name -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>পিতার নাম (Father's Name)</span>
                        <span class="input-label-badge">ঐচ্ছিক</span>
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-user-tie dash-input-icon"></i>
                        <input type="text" 
                               name="father_name" 
                               class="dash-control" 
                               value="{{ isset($profile->father_name) ? $profile->father_name : '' }}" 
                               placeholder="পিতার পুরো নাম লিখুন (ঐচ্ছিক)">
                    </div>
                </div>

                <!-- Mother's Name -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>মাতার নাম (Mother's Name)</span>
                        <span class="input-label-badge">ঐচ্ছিক</span>
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-female dash-input-icon"></i>
                        <input type="text" 
                               name="mother_name" 
                               class="dash-control" 
                               value="{{ isset($profile->mother_name) ? $profile->mother_name : '' }}" 
                               placeholder="মাতার পুরো নাম লিখুন (ঐচ্ছিক)">
                    </div>
                </div>

                <!-- Blood Group -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>রক্তের গ্রুপ (Blood Group)</span>
                        <span class="input-label-badge">ঐচ্ছিক</span>
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-tint dash-input-icon text-danger"></i>
                        <select name="blood_group" id="inputBloodGroup" class="dash-control">
                            <option value="">ব্লাড গ্রুপ নির্বাচন করুন (ঐচ্ছিক)</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <option value="{{ $bg }}" {{ (isset($profile->blood_group) && $profile->blood_group == $bg) ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Block 3: Identity & Date of Birth -->
        <div class="form-card-block">
            <h6 class="form-card-title">
                <i class="fas fa-id-card"></i> জাতীয় পরিচয় ও জন্মতারিখ (Identity & Security)
            </h6>

            <div class="row">
                <!-- NID / BC -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>জাতীয় পরিচয়পত্র / জন্ম নিবন্ধন (NID / BC)</span>
                        @if(isset($profile->nid) && $profile->nid != '')
                            <span class="input-label-badge locked"><i class="fas fa-lock mr-1"></i>লক করা</span>
                        @else
                            <span class="input-label-badge">ঐচ্ছিক</span>
                        @endif
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-id-card dash-input-icon"></i>
                        <input type="text" 
                               name="nid" 
                               class="dash-control" 
                               value="{{ isset($profile->nid) ? $profile->nid : '' }}" 
                               {{ (isset($profile->nid) && $profile->nid != '') ? 'readonly' : '' }}
                               placeholder="১০, ১৩ বা ১৭ ডিজিটের এনআইডি বা জন্ম নিবন্ধন">
                    </div>
                    @if(isset($profile->nid) && $profile->nid != '')
                        <small class="text-muted mt-1 d-block" style="font-size: 11.5px;">
                            <i class="fas fa-info-circle text-primary mr-1"></i> নিরাপত্তার স্বার্থে একবার সাবমিটের পর NID পরিবর্তনযোগ্য নয়।
                        </small>
                    @endif
                </div>

                <!-- Date of Birth -->
                <div class="col-md-6 mb-3">
                    <label class="input-label-modern">
                        <span>জন্মতারিখ (Date of Birth)</span>
                        @if(isset($profile->date_of_birth) && $profile->date_of_birth != '')
                            <span class="input-label-badge locked"><i class="fas fa-lock mr-1"></i>লক করা</span>
                        @else
                            <span class="input-label-badge">ঐচ্ছিক</span>
                        @endif
                    </label>
                    <div class="dash-input-wrap">
                        <i class="fas fa-calendar-alt dash-input-icon"></i>
                        <input type="date" 
                               name="date_of_birth" 
                               class="dash-control" 
                               value="{{ isset($profile->date_of_birth) ? $profile->date_of_birth : '' }}" 
                               {{ (isset($profile->date_of_birth) && $profile->date_of_birth != '') ? 'readonly' : '' }}>
                    </div>
                </div>
            </div>
        </div>

        <!-- Block 4: Delivery Address -->
        <div class="form-card-block">
            <h6 class="form-card-title">
                <i class="fas fa-map-marker-alt"></i> ডেলিভারি ঠিকানা (Delivery Address)
            </h6>

            <div class="mb-2">
                <label class="input-label-modern">
                    <span>পূর্ণাঙ্গ ডেলিভারির ঠিকানা</span>
                    <span class="text-danger">*</span>
                </label>
                <div class="dash-input-wrap">
                    <i class="fas fa-home dash-input-icon" style="top: 18px;"></i>
                    <textarea name="address" 
                              required 
                              class="dash-textarea" 
                              placeholder="বাড়ি/হোল্ডিং নম্বর, রোড, গ্রাম/মহল্লা, ইউনিয়ন, উপজেলা, জেলা লিখুন">{{ isset($profile->address) ? $profile->address : '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="btn-submit-profile">
                <i class="fas fa-check-circle"></i> তথ্য সংরক্ষণ করুন / Save Changes
            </button>
        </div>
    </form>
</div>
