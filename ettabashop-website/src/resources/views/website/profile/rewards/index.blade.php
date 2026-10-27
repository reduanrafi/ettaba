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
                রিওয়ার্ড ও পুরস্কার তালিকা
            </span>
        </div>

        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('user.profile') }}" class="topbar-btn" style="background:#f1f5f9; color:#334155;">
                <i class="fas fa-arrow-left"></i>
                <span>ড্যাশবোর্ড</span>
            </a>
            <a href="{{ route('profile.rewardHistory') }}" class="topbar-btn" style="background:#eef2ff; color:#4f46e5;">
                <i class="fas fa-history"></i>
                <span class="d-none d-sm-inline">আবেদন হিস্টোরি</span>
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-5 py-4">
    <!-- Breadcrumb & Page Title -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h3 class="font-weight-bold text-dark mb-1" style="font-size: 22px;">
                <i class="fas fa-trophy text-warning mr-2"></i> সকল পুরস্কারের তালিকা (All Rewards)
            </h3>
            <p class="text-muted mb-0" style="font-size: 13px;">
                আপনার বর্তমান পয়েন্ট ও বিভিন্ন লেভেলের আকর্ষণীয় উপহারসমূহ
            </p>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge badge-pill badge-primary px-3 py-2" style="font-size: 13px; background: linear-gradient(135deg, #2563eb, #4f46e5);">
                <i class="fas fa-user-tag mr-1"></i> {{ ucfirst($accountType) }} Rewards
            </span>
        </div>
    </div>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- 4.1 Reward Summary Card (Hero Section) -->
    <div class="reward-hero-summary mb-4">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-8 col-12 mb-3 mb-md-0">
                <div class="d-flex align-items-center mb-2">
                    <span class="summary-badge-label">
                        <i class="fas fa-gem text-warning mr-1"></i> পয়েন্ট সামারি
                    </span>
                    <span class="ml-2 text-white-50" style="font-size: 12px;">| রিয়েল-টাইম ব্যালেন্স</span>
                </div>
                <div class="d-flex flex-wrap align-items-baseline mb-2">
                    <h2 class="text-white font-weight-bold mb-0 mr-3" style="font-size: 28px;">
                        {{ number_format($userPoints) }} <small style="font-size: 16px; font-weight: 500;">Points</small>
                    </h2>
                    <span class="text-emerald-300 font-weight-bold" style="font-size: 13.5px; color: #a7f3d0;">
                        সর্বমোট অর্জিত ব্যালেন্স
                    </span>
                </div>

                @if(isset($nextReward))
                    <div class="mt-3 p-3" style="background: rgba(255, 255, 255, 0.12); border-radius: 14px; backdrop-filter: blur(8px);">
                        <div class="d-flex align-items-center justify-content-between mb-1 text-white" style="font-size: 13px;">
                            <span>
                                পরবর্তী লক্ষ্য: <strong>{{ $nextReward->reward_name }}</strong>
                            </span>
                            <span class="badge badge-light text-primary font-weight-bold">
                                বাকি: {{ number_format($nextReward->needed_points) }} Pts
                            </span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px; background: rgba(255,255,255,0.25);">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $nextReward->progress_percent }}%; border-radius: 10px;" 
                                 aria-valuenow="{{ $nextReward->progress_percent }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-5 col-md-4 col-12 text-md-right text-center">
                @if(isset($nextReward))
                    <div class="next-reward-preview-pill d-inline-flex align-items-center text-left">
                        <div class="preview-thumb mr-3">
                            @if(!empty($nextReward->image))
                                <img src="{{ config('app.image_url') . $nextReward->image }}" 
                                     onerror="this.src='{{ asset($nextReward->image) }}'" 
                                     alt="{{ $nextReward->reward_name }}">
                            @else
                                <div class="preview-thumb-placeholder">
                                    <i class="fas fa-gift"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="text-white-50" style="font-size: 11px;">পরবর্তী পুরস্কার</div>
                            <div class="text-white font-weight-bold" style="font-size: 14px;">{{ $nextReward->reward_name }}</div>
                            <div class="text-warning font-weight-bold" style="font-size: 12px;">{{ number_format($nextReward->required_points) }} Points</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 4.2 & 4.3 Reward List Design (Cards / Rows) -->
    <div class="reward-list-container mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size: 16px;">
                <i class="fas fa-list-ol text-primary mr-2"></i> পুরস্কার লেভেলসমূহ
            </h5>
            <span class="text-muted" style="font-size: 12px;">
                মোট: {{ $rewards->count() }} টি পুরস্কার
            </span>
        </div>

        <div class="reward-cards-stack">
            @forelse($rewards as $reward)
                @php
                    $isAvailable = ($reward->user_status === 'available');
                    $isInProgress = ($reward->user_status === 'in_progress');
                    $isReceived = ($reward->user_status === 'received' || $reward->user_status === 'accepted');
                    $isLocked = ($reward->user_status === 'locked');
                    $isRunning = ($reward->user_status === 'running');
                @endphp

                <div class="reward-row-card {{ $isAvailable ? 'card-available' : ($isReceived ? 'card-received' : ($isInProgress ? 'card-in-progress' : '')) }}">
                    <div class="row align-items-center no-gutters">
                        <!-- 1. Reward Level & Number -->
                        <div class="col-lg-1 col-md-1 col-2 text-center">
                            <div class="level-indicator {{ $isReceived ? 'level-done' : ($isAvailable ? 'level-active' : '') }}">
                                @if($isReceived)
                                    <i class="fas fa-check"></i>
                                @else
                                    #{{ $reward->level_no }}
                                @endif
                            </div>
                        </div>

                        <!-- 4. Reward Image -->
                        <div class="col-lg-2 col-md-2 col-3 text-center">
                            <div class="reward-card-img-wrap">
                                @if(!empty($reward->image))
                                    <img src="{{ config('app.image_url') . $reward->image }}" 
                                         onerror="this.src='{{ asset($reward->image) }}'" 
                                         alt="{{ $reward->reward_name }}">
                                @else
                                    <div class="reward-img-fallback">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- 2, 3, 5. Title, Subtitle, Required Points -->
                        <div class="col-lg-5 col-md-5 col-7 px-2 px-md-3">
                            <div class="reward-card-content">
                                <div class="d-flex flex-wrap align-items-center mb-1">
                                    <h5 class="reward-item-title mb-0 mr-2">
                                        {{ $reward->reward_name }}
                                    </h5>
                                    @if($reward->title && $reward->title != $reward->reward_name)
                                        <span class="badge badge-light border text-muted" style="font-size: 11px;">
                                            {{ $reward->title }}
                                        </span>
                                    @endif
                                </div>

                                @if($reward->subtitle)
                                    <p class="reward-item-subtitle text-muted mb-1">
                                        {{ $reward->subtitle }}
                                    </p>
                                @endif

                                <div class="reward-points-required-tag">
                                    <i class="fas fa-coins text-warning mr-1"></i>
                                    প্রয়োজনীয় পয়েন্ট: <strong>{{ number_format($reward->required_points) }}</strong> Point
                                </div>

                                <!-- Running Progress Bar -->
                                @if($isRunning)
                                    <div class="mt-2" style="max-width: 280px;">
                                        <div class="d-flex justify-content-between mb-1" style="font-size: 11px;">
                                            <span class="text-muted">পয়েন্ট অগ্রগতি:</span>
                                            <span class="font-weight-bold text-primary">{{ $reward->progress_percent }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px; border-radius: 6px; background: #e2e8f0;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $reward->progress_percent }}%;"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- 6. Reward Status Badge & Icon -->
                        <div class="col-lg-2 col-md-2 col-6 text-md-center text-left mt-2 mt-md-0 px-3">
                            @if($isReceived)
                                <div class="status-badge-wrap status-badge-received">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span>Received</span>
                                </div>
                            @elseif($isInProgress)
                                <div class="status-badge-wrap status-badge-progress">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    <span>In Progress</span>
                                </div>
                            @elseif($isAvailable)
                                <div class="status-badge-wrap status-badge-available animate-pulse">
                                    <i class="fas fa-gift mr-1"></i>
                                    <span>Available</span>
                                </div>
                            @elseif($isRunning)
                                <div class="status-badge-wrap status-badge-running">
                                    <i class="fas fa-bolt mr-1"></i>
                                    <span>চলমান (Running)</span>
                                </div>
                            @else
                                <div class="status-badge-wrap status-badge-locked">
                                    <i class="fas fa-lock mr-1"></i>
                                    <span>Locked</span>
                                </div>
                            @endif
                        </div>

                        <!-- 7. Action / Arrow (→) to Details Page -->
                        <div class="col-lg-2 col-md-2 col-6 text-right mt-2 mt-md-0 px-3">
                            @if($isAvailable)
                                <a href="{{ route('profile.rewards.detail', $reward->id) }}" class="btn btn-apply-reward-pill">
                                    <span>আবেদন করুন</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            @else
                                <a href="{{ route('profile.rewards.detail', $reward->id) }}" class="btn btn-view-reward-arrow" title="বিস্তারিত দেখুন">
                                    <span class="d-none d-lg-inline mr-1" style="font-size: 12px;">বিস্তারিত</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card p-5 text-center text-muted" style="border-radius: 18px; border: 1px dashed #cbd5e1;">
                    <i class="fas fa-gifts fa-3x mb-3 text-muted"></i>
                    <h5>বর্তমানে কোনো পুরস্কার সক্রিয় নেই</h5>
                    <p class="mb-0">নতুন পুরস্কারের অফার যুক্ত হলে এখানে দেখতে পাবেন।</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- 4.4 Reward অর্জনের উৎসাহমূলক অংশ (Bottom Motivational Section) -->
    <div class="reward-bottom-incentive-box mb-4">
        <div class="row align-items-center">
            <!-- Left: Motivational Message -->
            <div class="col-lg-6 col-12 mb-3 mb-lg-0 border-right-lg">
                <div class="p-3 p-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold" style="border-radius: 20px;">
                            <i class="fas fa-star mr-1"></i> মোটিভেশনাল টিপস
                        </span>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-2">প্রতিদিনের খরচ থেকেই এগিয়ে যান পুরস্কারের পথে!</h5>
                    <p class="text-muted mb-0" style="font-size: 13.5px; line-height: 1.7; text-align: justify;">
                        {{ $motivationalMsg ?? 'আপনি তো প্রতিদিনই কেনাকাটা করছেন বা খরচ করছেন। এবার সেই খরচ থেকেই পুরস্কারের পথে এগিয়ে যান! ইত্তেবা অ্যাপ বা ইত্তেবা’র লোকাল মার্চেন্ট থেকে নিয়মিত কেনাকাটা ও সার্ভিস গ্রহণ করুন, ক্যাশব্যাকের পাশাপাশি পয়েন্ট সংগ্রহ করুন এবং একে এক আনলক করুন তালিকায় উল্লেখিত দারুণসব পুরস্কার।' }}
                    </p>
                </div>
            </div>

            <!-- Right: কীভাবে Point অর্জন করবেন? -->
            <div class="col-lg-6 col-12">
                <div class="p-3 p-md-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-lightbulb text-warning mr-2"></i> কীভাবে Point অর্জন করবেন?
                    </h5>
                    <ul class="how-to-points-list mb-0">
                        @if(isset($howToEarn) && is_array($howToEarn))
                            @foreach($howToEarn as $item)
                                <li class="how-to-item">
                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="how-to-item"><i class="fas fa-check-circle text-success mr-2"></i> 🛒 ইত্তেবা অ্যাপ থেকে কেনাকাটা করুন</li>
                            <li class="how-to-item"><i class="fas fa-check-circle text-success mr-2"></i> 🏪 ইত্তেবা Merchant থেকে কেনাকাটা করুন</li>
                            <li class="how-to-item"><i class="fas fa-check-circle text-success mr-2"></i> 🎯 নির্দিষ্ট Campaign-এ অংশ নিন</li>
                            <li class="how-to-item"><i class="fas fa-check-circle text-success mr-2"></i> 💳 প্রয়োজনীয় পণ্য ও Service গ্রহণ করুন</li>
                            <li class="how-to-item"><i class="fas fa-check-circle text-success mr-2"></i> ⭐ নিয়মিত কেনাকাটার মাধ্যমে Point সংগ্রহ করুন</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 4.5 পুরস্কারের তালিকা পেজের নিচের বার (Terms & Conditions Sticky/Bottom Bar) -->
    <div class="reward-terms-bar mb-4" onclick="toggleTermsModal()">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-contract text-warning mr-2 fa-lg"></i>
                <span class="terms-bar-text">
                    পুরস্কার সম্পর্কিত নিয়ম ও শর্তাবলি পড়তে <span class="font-weight-bold text-primary">এখানে ক্লিক করুন <i class="fas fa-angle-right"></i></span>
                </span>
            </div>
            <i class="fas fa-chevron-up text-muted terms-chevron"></i>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="rewardTermsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="termsModalTitle">
                    <i class="fas fa-shield-alt mr-2"></i> পুরস্কার সম্পর্কিত নিয়ম ও শর্তাবলি
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="font-size: 13.5px; line-height: 1.8; color: #334155; white-space: pre-line;">
                {{ $terms }}
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal" style="border-radius: 20px;">
                    ঠিক আছে, বুঝতে পেরেছি
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* 4. All Rewards Page Ultra-Modern App-like Styles */
.reward-hero-summary {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #4f46e5 100%);
    border-radius: 20px;
    padding: 24px 28px;
    box-shadow: 0 12px 30px -5px rgba(37, 99, 235, 0.35);
}
.summary-badge-label {
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
}
.next-reward-preview-pill {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 10px 16px;
    backdrop-filter: blur(10px);
}
.preview-thumb {
    width: 50px;
    height: 50px;
    min-width: 50px;
    border-radius: 12px;
    background: #fff;
    padding: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.preview-thumb img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 8px;
}
.preview-thumb-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fef3c7;
    color: #d97706;
    border-radius: 8px;
    font-size: 20px;
}

/* Reward List Stack */
.reward-cards-stack {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.reward-row-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 14px 16px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
}
.reward-row-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
}
.reward-row-card.card-available {
    border: 1.5px solid #86efac;
    background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
}
.reward-row-card.card-received {
    border: 1px solid #bfdbfe;
    background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
}
.reward-row-card.card-in-progress {
    border: 1px solid #fde68a;
    background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);
}

.level-indicator {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
}
.level-active {
    background: #dcfce7;
    color: #16a34a;
}
.level-done {
    background: #dbeafe;
    color: #2563eb;
}

.reward-card-img-wrap {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    border-radius: 12px;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}
.reward-card-img-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 8px;
}
.reward-img-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    color: #94a3b8;
    border-radius: 8px;
    font-size: 22px;
}

.reward-item-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}
.reward-item-subtitle {
    font-size: 12.5px;
    line-height: 1.4;
}
.reward-points-required-tag {
    font-size: 12px;
    color: #475569;
    background: #f1f5f9;
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
}

.status-badge-wrap {
    display: inline-flex;
    align-items: center;
    font-size: 11.5px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
}
.status-badge-available {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #86efac;
}
.status-badge-progress {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.status-badge-received {
    background: #e0f2fe;
    color: #0369a1;
    border: 1px solid #7dd3fc;
}
.status-badge-running {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.status-badge-locked {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #cbd5e1;
}

.btn-apply-reward-pill {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #fff !important;
    font-size: 12.5px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 30px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(22, 163, 74, 0.25);
}
.btn-apply-reward-pill:hover {
    transform: scale(1.04);
}
.btn-view-reward-arrow {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #334155 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.btn-view-reward-arrow:hover {
    background: #2563eb;
    color: #fff !important;
    border-color: #2563eb;
    transform: translateX(3px);
}

/* Bottom Incentive Box */
.reward-bottom-incentive-box {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 8px 20px -5px rgba(15, 23, 42, 0.04);
}
.how-to-points-list {
    list-style: none;
    padding-left: 0;
}
.how-to-item {
    font-size: 13px;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

/* Terms Bottom Bar */
.reward-terms-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}
.reward-terms-bar:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.terms-bar-text {
    font-size: 13px;
    color: #475569;
}
</style>

@section('extra-script')
<script>
function toggleTermsModal() {
    $('#rewardTermsModal').modal('show');
}
</script>
@endsection
@endsection
