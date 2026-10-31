<!-- Reward Section: Next Reward & Motivational Banner (Specification Part A - Section 3 & 3.1) -->
<div class="reward-section-wrap mb-4">
    <!-- Header -->
    <div class="reward-section-header d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <div class="reward-header-icon mr-2">
                <i class="fas fa-trophy"></i>
            </div>
            <div>
                <h5 class="reward-header-title mb-0">আপনার পরবর্তী পুরস্কার</h5>
                <span class="text-muted" style="font-size: 12px;">পয়েন্ট সংগ্রহ করে পরবর্তী পুরস্কার আনলক করুন</span>
            </div>
        </div>
        <a href="{{ route('profile.rewards') }}" class="btn-all-rewards-link d-none d-sm-inline-flex align-items-center">
            <span>সকল পুরস্কার</span>
            <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- 3-Part Reward Card (Left: Next Reward, Middle: Motivational Message, Right: All Rewards CTA) -->
    <div class="reward-banner-card">
        <div class="row align-items-stretch no-gutters">
            <!-- Left: Next Reward Information -->
            <div class="col-lg-5 col-md-6 col-12 border-right-lg">
                <div class="next-reward-box p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Title & Status Badge -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge badge-reward-level">
                                <i class="fas fa-crown mr-1"></i> Level {{ $nextReward->level_no ?? 1 }}
                            </span>
                            @if(isset($nextReward->user_status))
                                @if($nextReward->user_status == 'available')
                                    <span class="badge badge-reward-status status-available animate-pulse">
                                        <i class="fas fa-gift mr-1"></i> আবেদনযোগ্য
                                    </span>
                                @elseif($nextReward->user_status == 'in_progress')
                                    <span class="badge badge-reward-status status-in-progress">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> প্রক্রিয়াধীন
                                    </span>
                                @elseif($nextReward->user_status == 'received')
                                    <span class="badge badge-reward-status status-received">
                                        <i class="fas fa-check-circle mr-1"></i> প্রাপ্ত
                                    </span>
                                @else
                                    <span class="badge badge-reward-status status-running">
                                        <i class="fas fa-bolt mr-1"></i> চলমান
                                    </span>
                                @endif
                            @endif
                        </div>

                        <!-- Reward Name & Target Points -->
                        <div class="d-flex align-items-center my-3">
                            <div class="reward-thumb-box mr-3">
                                @if(!empty($nextReward->image))
                                    <img src="{{ $nextReward->image_url ?? (rtrim(preg_replace('#/storage/?$#', '', config('app.image_url')), '/') . '/' . ltrim($nextReward->image, '/')) }}" 
                                         onerror="this.onerror=null; this.src='{{ asset($nextReward->image) }}';" 
                                         alt="{{ $nextReward->reward_name ?? 'Reward' }}">
                                @else
                                    <div class="reward-thumb-placeholder">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="next-reward-name mb-1">
                                    {{ $nextReward->reward_name ?? 'রাইস কুকার (Rice Cooker)' }}
                                </h4>
                                <div class="next-reward-title text-muted mb-1" style="font-size: 13px;">
                                    {{ $nextReward->title ?? 'Ettaba Premium Customer' }}
                                </div>
                                <div class="reward-target-points">
                                    <i class="fas fa-bullseye text-warning mr-1"></i>
                                    প্রয়োজন: <strong>{{ number_format($nextReward->required_points ?? 3000) }}</strong> Point
                                </div>
                            </div>
                        </div>

                        <!-- Point Status Indicators -->
                        <div class="reward-metrics-grid">
                            <div class="r-metric-pill">
                                <span class="r-metric-label">আপনার বর্তমান:</span>
                                <span class="r-metric-value text-primary font-weight-bold">
                                    {{ number_format($userPoints ?? 0) }} Pts
                                </span>
                            </div>
                            <div class="r-metric-pill">
                                <span class="r-metric-label">আনলক করতে বাকি:</span>
                                <span class="r-metric-value text-danger font-weight-bold">
                                    {{ number_format($nextReward->needed_points ?? max(0, ($nextReward->required_points ?? 3000) - ($userPoints ?? 0))) }} Pts
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-3 pt-2">
                        <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 11.5px;">
                            <span class="text-muted font-weight-500">টার্গেট পূরণ:</span>
                            <span class="font-weight-bold text-dark">{{ $nextReward->progress_percent ?? 0 }}%</span>
                        </div>
                        <div class="progress reward-progress-track" style="height: 9px; border-radius: 10px; background: #e2e8f0;">
                            <div class="progress-bar reward-progress-fill" 
                                 role="progressbar" 
                                 style="width: {{ $nextReward->progress_percent ?? 0 }}%;" 
                                 aria-valuenow="{{ $nextReward->progress_percent ?? 0 }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle: Motivational Message Banner -->
            <div class="col-lg-4 col-md-6 col-12 border-right-lg">
                <div class="motivational-banner-box p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="motivational-badge-row mb-2">
                            <span class="badge-moti-sparkle">
                                <i class="fas fa-sparkles mr-1 text-warning"></i> খরচ থেকেই উপহার!
                            </span>
                        </div>
                        <div class="motivational-quote-wrap">
                            <i class="fas fa-quote-left quote-icon-bg"></i>
                            <p class="motivational-text mb-0">
                                {{ $motivationalMsg ?? 'আপনি তো প্রতিদিনই কেনাকাটা করছেন বা খরচ করছেন। এবার সেই খরচ থেকেই পুরস্কারের পথে এগিয়ে যান! ইত্তেবা অ্যাপ বা ইত্তেবা’র লোকাল মার্চেন্ট থেকে নিয়মিত কেনাকাটা ও সার্ভিস গ্রহণ করুন, ক্যাশব্যাকের পাশাপাশি পয়েন্ট সংগ্রহ করুন এবং একে এক আনলক করুন তালিকায় উল্লেখিত দারুণসব পুরস্কার।' }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center text-muted mt-3 pt-2 border-top" style="font-size: 12px;">
                        <i class="fas fa-store-alt text-primary mr-2"></i>
                        <span>ইত্তেবা অ্যাপ ও মার্চেন্ট পার্টনারে কেনাকাটা করুন</span>
                    </div>
                </div>
            </div>

            <!-- Right: All Rewards CTA Button / Action Box -->
            <div class="col-lg-3 col-12">
                <div class="all-rewards-cta-box p-3 p-md-4 h-100 d-flex flex-column justify-content-center text-center">
                    <div class="cta-trophy-icon mb-3">
                        <i class="fas fa-gifts"></i>
                    </div>
                    <h5 class="cta-title mb-2">সকল পুরস্কারের তালিকা</h5>
                    <p class="cta-subtitle text-muted mb-3" style="font-size: 12px;">
                        কাস্টমার, পার্টনার ও মার্চেন্টদের সব লেভেলের আকর্ষণীয় উপহারসমূহ দেখুন
                    </p>
                    
                    <a href="{{ route('profile.rewards') }}" class="btn btn-view-all-rewards btn-block shadow-sm">
                        <span>সকল পুরস্কারের তালিকা দেখুন</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>

                    <div class="mt-2 text-muted" style="font-size: 11px;">
                        <i class="fas fa-mobile-alt mr-1 text-primary"></i> মোবাইল অ্যাপ ভিউ সাপোর্টেড
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reward Section Modern App-like Styles */
.reward-section-wrap {
    margin-top: 10px;
}
.reward-header-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 4px 10px rgba(245, 158, 11, 0.25);
}
.reward-header-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
}
.btn-all-rewards-link {
    font-size: 13px;
    font-weight: 600;
    color: #2563eb;
    text-decoration: none !important;
    padding: 6px 14px;
    background: #eff6ff;
    border-radius: 30px;
    transition: all 0.2s ease;
}
.btn-all-rewards-link:hover {
    background: #dbeafe;
    color: #1d4ed8;
    transform: translateX(2px);
}
.reward-banner-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
    overflow: hidden;
}
@media (min-width: 992px) {
    .border-right-lg {
        border-right: 1px solid #f1f5f9;
    }
}
/* Next Reward Box */
.next-reward-box {
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}
.badge-reward-level {
    background: #1e293b;
    color: #f8fafc;
    font-weight: 600;
    font-size: 11px;
    padding: 5px 10px;
    border-radius: 20px;
}
.badge-reward-status {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 11px;
    border-radius: 20px;
}
.status-available {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.status-in-progress {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.status-received {
    background: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.status-running {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.reward-thumb-box {
    width: 65px;
    height: 65px;
    min-width: 65px;
    border-radius: 14px;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}
.reward-thumb-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 10px;
}
.reward-thumb-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fef3c7;
    color: #d97706;
    border-radius: 10px;
    font-size: 24px;
}
.next-reward-name {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
.reward-target-points {
    font-size: 12px;
    color: #334155;
    background: #fef9c3;
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid #fef08a;
}
.reward-metrics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.r-metric-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 6px 10px;
    font-size: 11.5px;
}
.r-metric-label {
    display: block;
    color: #64748b;
    font-size: 11px;
}
.reward-progress-fill {
    background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);
    border-radius: 10px;
    transition: width 0.8s ease-in-out;
}
/* Motivational Box */
.motivational-banner-box {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 60%, #fff7ed 100%);
    position: relative;
    overflow: hidden;
}
.badge-moti-sparkle {
    background: rgba(245, 158, 11, 0.15);
    color: #b45309;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid rgba(245, 158, 11, 0.25);
}
.motivational-quote-wrap {
    position: relative;
    z-index: 1;
}
.quote-icon-bg {
    position: absolute;
    top: -10px;
    left: -5px;
    font-size: 32px;
    color: rgba(245, 158, 11, 0.15);
    z-index: -1;
}
.motivational-text {
    font-size: 13px;
    line-height: 1.6;
    color: #78350f;
    font-weight: 500;
    text-align: justify;
}
/* Right CTA Box */
.all-rewards-cta-box {
    background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
}
.cta-trophy-icon {
    width: 58px;
    height: 58px;
    background: #dcfce7;
    color: #16a34a;
    border-radius: 50%;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    box-shadow: 0 4px 15px rgba(22, 163, 74, 0.2);
}
.cta-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
}
.btn-view-all-rewards {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff !important;
    font-weight: 600;
    font-size: 13px;
    padding: 10px 16px;
    border-radius: 12px;
    border: none;
    transition: all 0.25s ease;
}
.btn-view-all-rewards:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(22, 163, 74, 0.3) !important;
}
@keyframes pulseGlow {
    0% { transform: scale(1); }
    50% { transform: scale(1.04); }
    100% { transform: scale(1); }
}
.animate-pulse {
    animation: pulseGlow 2s infinite ease-in-out;
}
</style>
