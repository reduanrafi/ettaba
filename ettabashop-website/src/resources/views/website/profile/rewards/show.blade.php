@extends('website.profile.layouts.layout')

@section('content')
<!-- Top Navigation Bar -->
<div class="profile-topbar">
    <div class="container-fluid px-lg-5 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('assets/images/logo/logo.png') }}" class="brand-logo" alt="Ettaba Shop">
            </a>
            <span class="d-none d-md-inline-block ml-3 text-muted" style="font-size: 13px; font-weight: 500; border-left: 2px solid #e2e8f0; padding-left: 12px;">
                পুরস্কারের বিস্তারিত বিবরণ
            </span>
        </div>

        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('profile.rewards') }}" class="topbar-btn" style="background:#f1f5f9; color:#334155;">
                <i class="fas fa-arrow-left"></i>
                <span>পুরস্কার তালিকা</span>
            </a>
            <a href="{{ route('user.profile') }}" class="topbar-btn" style="background:#eff6ff; color:#2563eb;">
                <i class="fas fa-home"></i>
                <span class="d-none d-sm-inline">হোম ড্যাশবোর্ড</span>
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-5 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <!-- Breadcrumb Navigation -->
            <div class="mb-3">
                <a href="{{ route('profile.rewards') }}" class="text-muted text-decoration-none" style="font-size: 13px;">
                    <i class="fas fa-arrow-left mr-1"></i> পুরস্কার তালিকা
                </a>
                <span class="text-muted mx-2">/</span>
                <span class="text-dark font-weight-bold" style="font-size: 13px;">{{ $reward->reward_name }}</span>
            </div>

            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Main Reward Detail Card (4.6 Reward Details UI) -->
            <div class="reward-detail-card shadow-sm mb-4">
                <!-- Header Badge Strip -->
                <div class="detail-header-strip d-flex align-items-center justify-content-between p-3 px-md-4">
                    <span class="badge badge-dark px-3 py-1 font-weight-bold" style="border-radius: 20px; font-size: 12px;">
                        <i class="fas fa-crown text-warning mr-1"></i> Reward Number {{ $reward->level_no }}
                    </span>
                    <span class="badge badge-light border font-weight-bold px-3 py-1" style="font-size: 12px; color: #334155;">
                        {{ $reward->title }}
                    </span>
                </div>

                <!-- Reward Showcase (Image, Name, Points) -->
                <div class="p-4 text-center border-bottom">
                    <div class="detail-reward-image-wrap mb-3">
                        @if(!empty($reward->image))
                            <img src="{{ $reward->image_url ?? (rtrim(preg_replace('#/storage/?$#', '', config('app.image_url')), '/') . '/' . ltrim($reward->image, '/')) }}" 
                                 onerror="this.onerror=null; this.src='{{ asset($reward->image) }}';" 
                                 alt="{{ $reward->reward_name }}" 
                                 class="img-fluid">
                        @else
                            <div class="detail-reward-fallback">
                                <i class="fas fa-gift fa-3x"></i>
                            </div>
                        @endif
                    </div>

                    <h3 class="detail-reward-name font-weight-bold text-dark mb-1">
                        {{ $reward->reward_name }}
                    </h3>
                    @if($reward->subtitle)
                        <p class="text-muted mb-2" style="font-size: 14px;">{{ $reward->subtitle }}</p>
                    @endif

                    <div class="detail-points-tag d-inline-block">
                        <i class="fas fa-diamond text-warning mr-1"></i>
                        প্রয়োজনীয় পয়েন্ট: <strong>{{ number_format($reward->required_points) }}</strong> Point
                    </div>

                    @if($reward->description)
                        <div class="detail-desc-box mt-3 text-muted text-justify">
                            {{ $reward->description }}
                        </div>
                    @endif
                </div>

                <!-- Customer Progress Card -->
                <div class="p-4 bg-light border-bottom">
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-chart-line text-primary mr-2"></i> আপনার পয়েন্ট অগ্রগতি (Customer Progress)
                    </h6>

                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="progress-stat-pill">
                                <span class="stat-lbl">আপনার Point</span>
                                <span class="stat-val text-primary">{{ number_format($userPoints) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="progress-stat-pill">
                                <span class="stat-lbl">প্রয়োজন</span>
                                <span class="stat-val text-warning">{{ number_format($reward->required_points) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="progress-stat-pill">
                                <span class="stat-lbl">বাকি পয়েন্ট</span>
                                <span class="stat-val text-danger">{{ number_format($reward->needed_points) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                            <span class="text-muted">টার্গেট পূরণ:</span>
                            <span class="font-weight-bold text-dark">{{ $reward->progress_percent }}%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px; background: #e2e8f0;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $reward->progress_percent }}%; border-radius: 10px;" 
                                 aria-valuenow="{{ $reward->progress_percent }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Sections from Admin -->
                <div class="p-4">
                    <!-- Dynamic Info Section -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i> {{ $reward->info_title ?: 'পুরস্কার সম্পর্কিত তথ্য:' }}
                        </h6>
                        <ul class="dynamic-bullet-list">
                            @php
                                $infoList = $reward->info_items_array;
                            @endphp
                            @if(!empty($infoList) && count($infoList) > 0)
                                @foreach($infoList as $item)
                                    <li><i class="fas fa-check text-success mr-2"></i> {{ $item }}</li>
                                @endforeach
                            @else
                                <li><i class="fas fa-check text-success mr-2"></i> আসল ব্র্যান্ডেড ও শতভাগ মানসম্মত পণ্য</li>
                                <li><i class="fas fa-check text-success mr-2"></i> ইত্তেবা অফিস থেকে সরাসরি ডেলিভারি ও হ্যান্ডওভার</li>
                                <li><i class="fas fa-check text-success mr-2"></i> সার্ভিস ও ব্র্যান্ড ওয়ারেন্টি প্রযোজ্য</li>
                            @endif
                        </ul>
                    </div>

                    <!-- Dynamic How-to Section -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-coins text-warning mr-2"></i> {{ $reward->howto_title ?: 'কীভাবে Point অর্জন করবেন?' }}
                        </h6>
                        <ul class="dynamic-bullet-list">
                            @php
                                $howtoList = $reward->howto_items_array;
                            @endphp
                            @if(!empty($howtoList) && count($howtoList) > 0)
                                @foreach($howtoList as $item)
                                    <li><i class="fas fa-angle-right text-primary mr-2"></i> {{ $item }}</li>
                                @endforeach
                            @else
                                <li><i class="fas fa-angle-right text-primary mr-2"></i> ইত্তেবা অ্যাপ থেকে নিয়মিত কেনাকাটা করুন</li>
                                <li><i class="fas fa-angle-right text-primary mr-2"></i> নিকটস্থ ইত্তেবা মার্চেন্ট শপ থেকে শপিং ও সার্ভিস গ্রহণ করুন</li>
                                <li><i class="fas fa-angle-right text-primary mr-2"></i> নির্দিষ্ট মেগা ক্যাম্পেইন ও ক্যাশব্যাক অফারে অংশ নিন</li>
                            @endif
                        </ul>
                    </div>

                    <!-- NID / Profile Alert if incomplete -->
                    @if(!$completeness['is_complete'])
                        <div class="alert alert-warning border-0" style="border-radius: 12px; background: #fffbeb; color: #92400e; font-size: 13px;">
                            <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                            <strong>প্রোফাইল আপডেট প্রয়োজন:</strong>
                            পুরস্কারের আবেদনের জন্য আপনার এনআইডি ও প্রোফাইল সম্পূর্ণ থাকা বাধ্যতামূলক।
                            <br>ঘাটতি: <span class="font-weight-bold text-danger">{{ implode(', ', $completeness['missing']) }}</span>
                            <div class="mt-2">
                                <a href="{{ route('user.profile') }}" class="btn btn-warning btn-sm" style="border-radius: 20px; font-weight: 600;">
                                    <i class="fas fa-user-edit mr-1"></i> প্রোফাইল আপডেট করুন
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- 6.1 Apply Reward Button -->
                    <div class="reward-action-footer mt-4 pt-3 border-top text-center">
                        @if($reward->can_apply)
                            @if($completeness['is_complete'])
                                <form method="POST" action="{{ route('profile.rewards.apply') }}" onsubmit="return confirm('আপনি কি নিশ্চিত এই পুরস্কারের জন্য আবেদন করতে চান?');">
                                    @csrf
                                    <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                    <button type="submit" class="btn btn-apply-now btn-lg btn-block shadow">
                                        <i class="fas fa-paper-plane mr-2"></i> এই পুরস্কারের জন্য আবেদন করুন
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('user.profile') }}" class="btn btn-apply-disabled btn-lg btn-block">
                                    <i class="fas fa-user-lock mr-2"></i> আবেদন করতে প্রোফাইল আপডেট করুন
                                </a>
                            @endif
                        @elseif($reward->user_status === 'in_progress')
                            <button type="button" class="btn btn-secondary btn-lg btn-block" disabled>
                                <i class="fas fa-spinner fa-spin mr-2"></i> আপনার আবেদন প্রক্রিয়াধীন রয়েছে
                            </button>
                            <small class="text-muted d-block mt-2">এডমিন যাচাই-বাছাই সম্পন্ন হলে নোটিফিকেশন পাবেন।</small>
                        @elseif($reward->user_status === 'accepted' || $reward->user_status === 'received')
                            <button type="button" class="btn btn-success btn-lg btn-block" disabled>
                                <i class="fas fa-check-circle mr-2"></i> পুরস্কারটি গ্রহণ সম্পন্ন হয়েছে
                            </button>
                        @else
                            <button type="button" class="btn btn-apply-disabled btn-lg btn-block" disabled>
                                <i class="fas fa-lock mr-2"></i> এই পুরস্কারের জন্য আবেদন করুন (লকড)
                            </button>
                            <small class="text-muted d-block mt-2">
                                আবেদন করতে আরও <strong>{{ number_format($reward->needed_points) }}</strong> পয়েন্ট প্রয়োজন।
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 4.6 Reward Details Modern UI */
.reward-detail-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}
.detail-header-strip {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.detail-reward-image-wrap {
    width: 140px;
    height: 140px;
    margin: 0 auto;
    border-radius: 20px;
    background: #fff;
    border: 2px solid #e2e8f0;
    padding: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
}
.detail-reward-image-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 14px;
}
.detail-reward-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fef3c7;
    color: #d97706;
    border-radius: 14px;
}
.detail-reward-name {
    font-size: 22px;
}
.detail-points-tag {
    background: #fef9c3;
    color: #854d0e;
    border: 1px solid #fef08a;
    font-size: 13px;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 20px;
}
.detail-desc-box {
    max-width: 600px;
    margin: 15px auto 0;
    font-size: 13.5px;
    line-height: 1.7;
    background: #f8fafc;
    padding: 12px 16px;
    border-radius: 12px;
}

.progress-stat-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 8px 10px;
}
.stat-lbl {
    display: block;
    font-size: 11px;
    color: #64748b;
}
.stat-val {
    font-size: 14px;
    font-weight: 700;
}

.dynamic-bullet-list {
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
}
.dynamic-bullet-list li {
    font-size: 13.5px;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.btn-apply-now {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff !important;
    font-weight: 700;
    font-size: 16px;
    padding: 14px 20px;
    border-radius: 14px;
    border: none;
    transition: all 0.25s ease;
}
.btn-apply-now:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(22, 163, 74, 0.4) !important;
}
.btn-apply-disabled {
    background: #e2e8f0;
    color: #94a3b8;
    font-weight: 600;
    font-size: 15px;
    padding: 14px 20px;
    border-radius: 14px;
    border: none;
    cursor: not-allowed;
}
</style>
@endsection
