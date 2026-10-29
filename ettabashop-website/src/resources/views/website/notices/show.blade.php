@extends('website.layouts.layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <!-- Top Nav -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="{{ route('website.notices.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> সকল নোটিশ তালিকা
                </a>
                <a href="{{ url('/') }}" class="btn btn-sm btn-light border rounded-pill px-3 text-dark font-weight-bold">
                    <i class="fas fa-home mr-1 text-success"></i> হোমপেজ
                </a>
            </div>

            <!-- Notice Detail Card -->
            <div class="bg-white border shadow-sm overflow-hidden mb-4" style="border-radius: 16px;">
                <div class="p-3 px-4 border-bottom d-flex align-items-center justify-content-between" style="background: #f0fdf4;">
                    <span class="badge badge-success px-3 py-1" style="border-radius: 20px; font-size: 12px; background: #16a34a;">
                        <i class="fas fa-bullhorn mr-1"></i> অফিশিয়াল নোটিশ
                    </span>
                    <small class="text-muted font-weight-bold">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ $notice->published_at ? $notice->published_at->format('d M Y, h:i A') : ($notice->created_at ? $notice->created_at->format('d M Y') : '') }}
                    </small>
                </div>

                @if(!empty($notice->image))
                    <div class="text-center bg-light border-bottom">
                        <img src="{{ config('app.image_url') . $notice->image }}"
                             onerror="this.src='{{ asset($notice->image) }}'"
                             alt="{{ $notice->title }}"
                             class="img-fluid"
                             style="max-height: 380px; width: 100%; object-fit: cover;">
                    </div>
                @endif

                <div class="p-4">
                    <h4 class="font-weight-bold mb-3" style="color: #0f172a; line-height: 1.4;">{{ $notice->title }}</h4>

                    @if($notice->summary)
                        <div class="p-3 mb-3 rounded" style="background: #fef2f2; border-left: 4px solid #dc2626; color: #7f1d1d; font-size: 14px; font-weight: 600;">
                            {{ $notice->summary }}
                        </div>
                    @endif

                    <div style="font-size: 15px; line-height: 1.8; color: #334155; white-space: pre-line;">{!! nl2br(e($notice->content)) !!}</div>
                </div>
            </div>

            <!-- Other Recent Notices -->
            @if(isset($recentNotices) && $recentNotices->count() > 0)
                <div class="bg-white p-3 border shadow-sm" style="border-radius: 14px;">
                    <h6 class="font-weight-bold mb-3" style="color: #15803d;">অন্যান্য সাম্প্রতিক নোটিশ</h6>
                    @foreach($recentNotices as $rNotice)
                        <a href="{{ route('website.notices.show', $rNotice->id) }}" class="d-flex align-items-center justify-content-between py-2 border-bottom text-decoration-none text-dark">
                            <span style="font-size: 13.5px; font-weight: 600;">{{ $rNotice->title }}</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

