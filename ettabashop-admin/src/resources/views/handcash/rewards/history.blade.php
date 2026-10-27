@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        মার্চেন্ট পুরস্কার আবেদন হিস্টোরি
        <small>আপনার আবেদনের স্ট্যাটাস ও পয়েন্ট ব্যবহারের হিসাব</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.handcash') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('handcash.rewards.index') }}">মার্চেন্ট রিওয়ার্ডস</a></li>
        <li class="active">আবেদন হিস্টোরি</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2" id="messageDiv">
            @if(Session::has('success'))
                @include('admin.layouts.message.success')
            @elseif(Session::has('error'))
                @include('admin.layouts.message.error')
            @endif
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua" style="border-radius: 10px;">
                <span class="info-box-icon"><i class="fa fa-diamond"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">বর্তমান পয়েন্ট ব্যালেন্স</span>
                    <span class="info-box-number" style="font-size: 24px;">{{ number_format($userPoints) }} <small style="color: #fff;">Pts</small></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">অ্যাক্টিভ সার্ভিস পয়েন্ট</span>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-green" style="border-radius: 10px;">
                <span class="info-box-icon"><i class="fa fa-gift"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">মোট পুরস্কারে কর্তিত পয়েন্ট</span>
                    <span class="info-box-number" style="font-size: 24px;">{{ number_format($totalUsed) }} <small style="color: #fff;">Pts</small></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">পুরস্কার গ্রহণে ব্যবহৃত</span>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="info-box bg-purple" style="border-radius: 10px;">
                <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">মোট আবেদনের সংখ্যা</span>
                    <span class="info-box-number" style="font-size: 24px;">{{ $applications->count() }} <small style="color: #fff;">টি</small></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">সব সময়ের মোট আবেদন</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list text-primary"></i> আবেদনের তালিকা (Merchant Application History)</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('handcash.rewards.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-trophy"></i> সকল পুরস্কার
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 130px;">Application No</th>
                                <th>পুরস্কারের বিবরণ</th>
                                <th style="width: 130px;">প্রয়োজনীয় পয়েন্ট</th>
                                <th style="width: 140px;">আবেদনের তারিখ</th>
                                <th style="width: 130px;">স্ট্যাটাস</th>
                                <th>অ্যাডমিন নোট ও কালেকশন তথ্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $app)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $app->application_no }}</strong>
                                        <div class="text-muted" style="font-size: 11px;">Merchant App</div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            @if(!empty($app->reward_image))
                                                <img src="{{ config('app.image_url') . $app->reward_image }}" 
                                                     onerror="this.src='{{ asset($app->reward_image) }}'" 
                                                     style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px; margin-right: 10px; border: 1px solid #ddd;">
                                            @else
                                                <div style="width: 44px; height: 44px; background: #fef3c7; color: #d97706; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                    <i class="fa fa-gift fa-lg"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong style="color: #1e3a8a; font-size: 14px;">{{ $app->reward_name }}</strong>
                                                @if($app->reward_title)
                                                    <div class="text-muted" style="font-size: 12px;">{{ $app->reward_title }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="label label-warning" style="font-size: 12px;">
                                            {{ number_format($app->required_points) }} Pts
                                        </span>
                                        @if($app->points_deducted)
                                            <div class="text-success" style="font-size: 11px; margin-top: 3px;">
                                                <i class="fa fa-check"></i> কর্তিত পয়েন্ট
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $app->created_at->format('d M, Y') }}</div>
                                        <small class="text-muted">{{ $app->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($app->status == 'pending')
                                            <span class="label label-warning" style="font-size: 12px; padding: 4px 8px;">
                                                <i class="fa fa-spinner fa-spin"></i> Pending
                                            </span>
                                        @elseif($app->status == 'accepted')
                                            <span class="label label-success" style="font-size: 12px; padding: 4px 8px;">
                                                <i class="fa fa-check-circle"></i> Accepted
                                            </span>
                                        @elseif($app->status == 'received')
                                            <span class="label label-primary" style="font-size: 12px; padding: 4px 8px;">
                                                <i class="fa fa-trophy"></i> Received
                                            </span>
                                        @elseif($app->status == 'rejected')
                                            <span class="label label-danger" style="font-size: 12px; padding: 4px 8px;">
                                                <i class="fa fa-times-circle"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($app->admin_note)
                                            <div style="background: #f8fafc; border-left: 3px solid #3b82f6; padding: 6px 10px; border-radius: 4px; font-size: 12px; margin-bottom: 4px;">
                                                <strong>অ্যাডমিন নোট:</strong> {{ $app->admin_note }}
                                            </div>
                                        @endif

                                        @if($app->collection_date || $app->collection_place)
                                            <div style="background: #f0fdf4; border-left: 3px solid #10b981; padding: 6px 10px; border-radius: 4px; font-size: 12px;">
                                                @if($app->collection_date)
                                                    <div><i class="fa fa-calendar text-success"></i> <strong>তারিখ:</strong> {{ $app->collection_date }} ({{ $app->collection_time }})</div>
                                                @endif
                                                @if($app->collection_place)
                                                    <div><i class="fa fa-map-marker text-danger"></i> <strong>স্থান:</strong> {{ $app->collection_place }}</div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(empty($app->admin_note) && empty($app->collection_date))
                                            <span class="text-muted" style="font-size: 12px;">আবেদন পর্যালোচনায় রয়েছে...</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4" style="padding: 30px;">
                                        <i class="fa fa-inbox fa-3x text-muted" style="margin-bottom: 10px; display: block;"></i>
                                        এখনও কোনো পুরস্কারের আবেদন করা হয়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
