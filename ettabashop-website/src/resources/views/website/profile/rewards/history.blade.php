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
                পুরস্কার আবেদনের হিস্টোরি
            </span>
        </div>

        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('profile.rewards') }}" class="topbar-btn" style="background:#eff6ff; color:#2563eb;">
                <i class="fas fa-trophy"></i>
                <span>সকল পুরস্কার</span>
            </a>
            <a href="{{ route('user.profile') }}" class="topbar-btn" style="background:#f1f5f9; color:#334155;">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">ড্যাশবোর্ড</span>
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-5 py-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h3 class="font-weight-bold text-dark mb-1" style="font-size: 22px;">
                <i class="fas fa-history text-primary mr-2"></i> পুরস্কার আবেদনের হিস্টোরি (Reward Application History)
            </h3>
            <p class="text-muted mb-0" style="font-size: 13px;">
                আপনার পূর্বের ও বর্তমান আবেদনসমূহ, অ্যাডমিন নোট এবং পুরস্কার গ্রহণের বিস্তারিত তথ্য
            </p>
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

    <!-- Metric Summary Row (Total Rewards Applied, Used Points, Available Points) -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="dash-stat-card">
                <div class="stat-top-row">
                    <span class="stat-title">বর্তমান পয়েন্ট ব্যালেন্স</span>
                    <div class="stat-icon-wrap icon-amber">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($userPoints) }} <small style="font-size: 13px;">Pts</small></div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="dash-stat-card">
                <div class="stat-top-row">
                    <span class="stat-title">মোট পুরস্কারের ব্যয়িত পয়েন্ট</span>
                    <div class="stat-icon-wrap icon-emerald">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalUsedPoints) }} <small style="font-size: 13px;">Pts</small></div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <div class="dash-stat-card">
                <div class="stat-top-row">
                    <span class="stat-title">মোট আবেদন সংখ্যা</span>
                    <div class="stat-icon-wrap icon-indigo">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $applications->count() }} <small style="font-size: 13px;">টি</small></div>
            </div>
        </div>
    </div>

    <!-- Application History Cards / Table (Section 9) -->
    <div class="card shadow-sm border-0" style="border-radius: 18px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size: 16px;">
                <i class="fas fa-list-alt text-primary mr-2"></i> আবেদনের তালিকা (Total Reward Used)
            </h5>
        </div>

        <div class="card-body p-0">
            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="vertical-align: middle;">
                        <thead style="background: #f8fafc; font-size: 12.5px; color: #475569;">
                            <tr>
                                <th class="pl-4">Application ID</th>
                                <th>পুরস্কারের নাম</th>
                                <th>প্রয়োজনীয় পয়েন্ট</th>
                                <th>আবেদনের তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th>অ্যাডমিনের নোট ও গ্রহণের তথ্য</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13.5px;">
                            @foreach($applications as $app)
                                <tr>
                                    <td class="pl-4">
                                        <strong class="text-primary">{{ $app->application_no }}</strong>
                                        <div class="text-muted" style="font-size: 11px;">
                                            {{ ucfirst($app->account_type) }} Account
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(!empty($app->reward_image))
                                                <img src="{{ rtrim(preg_replace('#/storage/?$#', '', config('app.image_url')), '/') . '/' . ltrim($app->reward_image, '/') }}" 
                                                     onerror="this.onerror=null; this.src='{{ asset($app->reward_image) }}';" 
                                                     alt="Reward" 
                                                     style="width: 42px; height: 42px; object-fit: cover; border-radius: 8px; margin-right: 10px; border: 1px solid #e2e8f0;">
                                            @else
                                                <div style="width: 42px; height: 42px; background: #fef3c7; color: #d97706; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                    <i class="fas fa-gift"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong class="text-dark">{{ $app->reward_name }}</strong>
                                                <div class="text-muted" style="font-size: 11.5px;">{{ $app->reward_title }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                                            {{ number_format($app->required_points) }} Pts
                                        </span>
                                        @if($app->points_deducted)
                                            <div class="text-success" style="font-size: 11px; margin-top: 2px;">
                                                <i class="fas fa-check"></i> কর্তন সম্পন্ন
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $app->created_at->format('d M, Y') }}</div>
                                        <small class="text-muted">{{ $app->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($app->status == 'pending')
                                            <span class="badge badge-warning px-3 py-2" style="border-radius: 20px; font-size: 12px; background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
                                                <i class="fas fa-spinner fa-spin mr-1"></i> Application Pending
                                            </span>
                                        @elseif($app->status == 'accepted')
                                            <span class="badge badge-success px-3 py-2" style="border-radius: 20px; font-size: 12px; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                                                <i class="fas fa-check-circle mr-1"></i> Accepted
                                            </span>
                                        @elseif($app->status == 'received')
                                            <span class="badge badge-info px-3 py-2" style="border-radius: 20px; font-size: 12px; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                                <i class="fas fa-trophy mr-1"></i> Received
                                            </span>
                                        @elseif($app->status == 'rejected')
                                            <span class="badge badge-danger px-3 py-2" style="border-radius: 20px; font-size: 12px; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;">
                                                <i class="fas fa-times-circle mr-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td style="max-width: 320px;">
                                        @if($app->admin_note)
                                            <div class="p-2 mb-1" style="background: #f8fafc; border-radius: 8px; font-size: 12px; border-left: 3px solid #3b82f6;">
                                                <strong>অ্যাডমিন নোট:</strong> {{ $app->admin_note }}
                                            </div>
                                        @endif

                                        @if($app->collection_date || $app->collection_place)
                                            <div class="p-2" style="background: #f0fdf4; border-radius: 8px; font-size: 11.5px; border-left: 3px solid #10b981;">
                                                <div><i class="fas fa-calendar-alt text-success mr-1"></i> <strong>তারিখ:</strong> {{ $app->collection_date }} ({{ $app->collection_time }})</div>
                                                <div><i class="fas fa-map-marker-alt text-danger mr-1"></i> <strong>স্থান:</strong> {{ $app->collection_place }}</div>
                                            </div>
                                        @endif

                                        @if(empty($app->admin_note) && empty($app->collection_date))
                                            <span class="text-muted" style="font-size: 12px;">যাচাই প্রক্রিয়াধীন...</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                    <h5>এখনও কোনো পুরস্কারের আবেদন করা হয়নি</h5>
                    <p class="mb-3">পয়েন্ট সংগ্রহ করে আকর্ষণীয় সব পুরস্কারের জন্য আবেদন করুন।</p>
                    <a href="{{ route('profile.rewards') }}" class="btn btn-primary btn-sm px-4" style="border-radius: 20px;">
                        <i class="fas fa-trophy mr-1"></i> পুরস্কার তালিকা দেখুন
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
