@extends('website.layouts.layout')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-lg shadow-sm border" style="border-radius: 14px !important;">
        <div class="d-flex align-items-center">
            <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 44px; height: 44px; background: #dcfce7; color: #15803d; font-size: 20px;">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h5 class="font-weight-bold mb-0" style="color: #0f172a;">নোটিশ ও নোটিফিকেশন তালিকা</h5>
                <small class="text-muted">ইত্তেবা শপের সকল প্রকাশিত পাবলিক নোটিশ ও আপডেট</small>
            </div>
        </div>
        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 font-weight-bold">
            <i class="fas fa-arrow-left mr-1"></i> হোমপেজ
        </a>
    </div>

    <!-- Personal Notifications (if logged in and any exist) -->
    @auth
        @if(isset($userNotifications) && $userNotifications->count() > 0)
            <div class="mb-4">
                <h6 class="font-weight-bold mb-2" style="color: #15803d;">
                    <i class="fas fa-user-check mr-1"></i> আপনার ব্যক্তিগত নোটিফিকেশন
                </h6>
                @foreach($userNotifications as $uNote)
                    <div class="bg-white p-3 mb-2 border shadow-sm d-flex align-items-start justify-content-between" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
                        <div>
                            <h6 class="font-weight-bold mb-1" style="color: #0f172a; font-size: 14px;">{{ $uNote->title }}</h6>
                            <p class="mb-1 text-muted" style="font-size: 13px;">{{ $uNote->message }}</p>
                            <small class="text-muted" style="font-size: 11px;">
                                <i class="far fa-clock mr-1"></i> {{ $uNote->created_at ? $uNote->created_at->diffForHumans() : '' }}
                            </small>
                        </div>
                        @if(!empty($uNote->link))
                            <a href="{{ $uNote->link }}" class="btn btn-sm btn-success rounded-pill px-3 ml-2">দেখুন</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    @endauth

    <!-- Public Notices List -->
    <div>
        <h6 class="font-weight-bold mb-3" style="color: #dc2626;">
            <i class="fas fa-bullhorn mr-1"></i> সকল পাবলিক নোটিশ ও ঘোষণা
        </h6>

        @if(isset($notices) && $notices->count() > 0)
            @foreach($notices as $notice)
                @php
                    $isRead = in_array((int)$notice->id, $readIds ?? []);
                @endphp
                <a href="{{ route('website.notices.show', $notice->id) }}" class="text-decoration-none d-block mb-3">
                    <div class="bg-white p-3 border shadow-sm transition-all notice-card-item"
                         style="border-radius: 14px; border-left: 4px solid {{ $isRead ? '#94a3b8' : '#dc2626' }} !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center pr-2">
                                @if(!empty($notice->image))
                                    <img src="{{ config('app.image_url') . $notice->image }}"
                                         onerror="this.src='{{ asset($notice->image) }}'"
                                         style="width: 58px; height: 58px; object-fit: cover; border-radius: 10px; margin-right: 12px; border: 1px solid #e2e8f0;">
                                @else
                                    <div style="width: 48px; height: 48px; min-width: 48px; border-radius: 10px; background: {{ $isRead ? '#f1f5f9' : '#fef2f2' }}; color: {{ $isRead ? '#64748b' : '#dc2626' }}; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 12px;">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="d-flex align-items-center flex-wrap mb-1">
                                        @if(!$isRead)
                                            <span class="badge badge-danger mr-2 px-2 py-1" style="font-size: 10px; border-radius: 20px;">নতুন</span>
                                        @endif
                                        <h6 class="font-weight-bold mb-0" style="color: #0f172a; font-size: 15px;">{{ $notice->title }}</h6>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 13px; line-height: 1.4;">
                                        {{ $notice->summary ?: \Illuminate\Support\Str::limit(strip_tags($notice->content), 110) }}
                                    </p>
                                    <small class="text-muted" style="font-size: 11px;">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $notice->published_at ? $notice->published_at->format('d M Y, h:i A') : ($notice->created_at ? $notice->created_at->format('d M Y') : '') }}
                                    </small>
                                </div>
                            </div>
                            <div class="ml-2 text-success font-weight-bold d-flex align-items-center" style="white-space: nowrap; font-size: 13px;">
                                <span class="d-none d-sm-inline mr-1">বিস্তারিত</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach

            @if(method_exists($notices, 'links'))
                <div class="mt-4 d-flex justify-content-center">
                    {{ $notices->links() }}
                </div>
            @endif
        @else
            <div class="bg-white p-5 text-center border shadow-sm" style="border-radius: 16px;">
                <i class="far fa-bell-slash fa-3x text-muted mb-3"></i>
                <h6 class="font-weight-bold text-dark">বর্তমানে কোনো নতুন পাবলিক নোটিশ নেই</h6>
                <p class="text-muted small mb-0">নতুন কোনো অফার বা ঘোষণা প্রকাশিত হলে এখানে দেখতে পাবেন।</p>
            </div>
        @endif
    </div>
</div>

<style>
    .notice-card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
</style>
@endsection

