@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        মার্চেন্ট পুরস্কার ও অফার (Merchant Rewards)
        <small>হ্যান্ড ক্যাশ ক্যাশব্যাক সেলের মাধ্যমে অর্জিত পয়েন্ট ও পুরস্কার</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.handcash') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li class="active">মার্চেন্ট রিওয়ার্ডস</li>
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

    <!-- Merchant Point Summary Banner -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid bg-aqua-gradient" style="border-radius: 12px; overflow: hidden;">
                <div class="box-body" style="padding: 25px;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <span class="badge" style="background: rgba(255,255,255,0.2); font-size: 13px;">
                                <i class="fa fa-trophy text-yellow"></i> মার্চেন্ট রিওয়ার্ড ব্যালেন্স
                            </span>
                            <h2 style="font-size: 32px; font-weight: bold; margin-top: 10px; margin-bottom: 5px;">
                                {{ number_format($userPoints) }} <small style="color: #fff; font-size: 18px;">Points</small>
                            </h2>
                            <p style="color: rgba(255,255,255,0.9); font-size: 14px;">
                                আপনার ক্যাশব্যাক বিক্রয় ও সার্ভিস পয়েন্টের উপর ভিত্তি করে নির্ধারিত আকর্ষণীয় উপহারসমূহ
                            </p>

                            @if(isset($nextReward))
                                <div style="background: rgba(255,255,255,0.15); border-radius: 10px; padding: 12px; margin-top: 15px; max-width: 500px;">
                                    <div class="d-flex justify-content-between" style="font-size: 13px; font-weight: bold; margin-bottom: 6px;">
                                        <span>পরবর্তী লক্ষ্য: {{ $nextReward->reward_name }}</span>
                                        <span class="pull-right">বাকি: {{ number_format($nextReward->needed_points) }} Pts</span>
                                    </div>
                                    <div class="progress progress-xs" style="background: rgba(255,255,255,0.3); border-radius: 8px;">
                                        <div class="progress-bar progress-bar-yellow" style="width: {{ $nextReward->progress_percent }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4 text-center">
                            @if(isset($nextReward))
                                <div style="background: rgba(255,255,255,0.12); padding: 15px; border-radius: 12px; display: inline-block;">
                                    @if(!empty($nextReward->image))
                                        <img src="{{ config('app.image_url') . $nextReward->image }}" 
                                             onerror="this.src='{{ asset($nextReward->image) }}'" 
                                             style="max-height: 80px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); margin-bottom: 8px;">
                                    @endif
                                    <div style="font-size: 15px; font-weight: bold;">{{ $nextReward->reward_name }}</div>
                                    <div class="text-yellow" style="font-weight: bold;">{{ number_format($nextReward->required_points) }} Points</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Merchant Rewards List -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list text-primary"></i> মার্চেন্ট পুরস্কারের তালিকা</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('handcash.rewards.history') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-history"></i> আবেদন হিস্টোরি
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 60px;">লেভেল</th>
                                <th style="width: 80px;">ছবি</th>
                                <th>পুরস্কারের নাম</th>
                                <th>টাইটেল ও বিবরণ</th>
                                <th>প্রয়োজনীয় পয়েন্ট</th>
                                <th>স্ট্যাটাস</th>
                                <th style="width: 140px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rewards as $reward)
                                <tr>
                                    <td class="text-center font-weight-bold">
                                        <span class="badge bg-navy">#{{ $reward->level_no }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($reward->image))
                                            <img src="{{ config('app.image_url') . $reward->image }}" 
                                                 onerror="this.src='{{ asset($reward->image) }}'" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #fef3c7; color: #d97706; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-gift fa-2x"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong style="font-size: 15px; color: #1e3a8a;">{{ $reward->reward_name }}</strong>
                                    </td>
                                    <td>
                                        <div><strong>{{ $reward->title }}</strong></div>
                                        @if($reward->subtitle)
                                            <small class="text-muted">{{ $reward->subtitle }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="label label-warning" style="font-size: 13px;">
                                            <i class="fa fa-diamond"></i> {{ number_format($reward->required_points) }} Pts
                                        </span>
                                    </td>
                                    <td>
                                        @if($reward->user_status == 'received' || $reward->user_status == 'accepted')
                                            <span class="label label-success"><i class="fa fa-check-circle"></i> Received</span>
                                        @elseif($reward->user_status == 'in_progress')
                                            <span class="label label-warning"><i class="fa fa-spinner fa-spin"></i> In Progress</span>
                                        @elseif($reward->user_status == 'available')
                                            <span class="label label-success"><i class="fa fa-gift"></i> Available</span>
                                        @elseif($reward->user_status == 'running')
                                            <span class="label label-info"><i class="fa fa-bolt"></i> চলমান ({{ $reward->progress_percent }}%)</span>
                                        @else
                                            <span class="label label-default"><i class="fa fa-lock"></i> Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('handcash.rewards.show', $reward->id) }}" class="btn btn-sm btn-primary btn-block">
                                            <i class="fa fa-eye"></i> বিস্তারিত দেখুন
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        বর্তমানে কোনো মার্চেন্ট পুরস্কার সক্রিয় নেই।
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
