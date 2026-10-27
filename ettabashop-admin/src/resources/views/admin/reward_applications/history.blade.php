@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        পুরস্কার আবেদন হিস্টোরি (Application History)
        <small>অনুমোদিত, বাতিল ও হস্তান্তরিত আবেদনসমূহ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('admin.rewardApplications.index') }}">আবেদন তালিকা</a></li>
        <li class="active">হিস্টোরি</li>
    </ol>
</section>

<section class="content">
    <!-- Filter Bar -->
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-8">
            <div class="btn-group">
                <a href="{{ route('admin.rewardApplications.history', ['status' => '']) }}" 
                   class="btn {{ $currentStatus == 'all' || empty($currentStatus) ? 'btn-primary' : 'btn-default' }}">
                    সব হিস্টোরি
                </a>
                <a href="{{ route('admin.rewardApplications.history', ['status' => 'accepted']) }}" 
                   class="btn {{ $currentStatus == 'accepted' ? 'btn-success' : 'btn-default' }}">
                    অনুমোদিত (Accepted)
                </a>
                <a href="{{ route('admin.rewardApplications.history', ['status' => 'received']) }}" 
                   class="btn {{ $currentStatus == 'received' ? 'btn-info' : 'btn-default' }}">
                    হস্তান্তরিত (Received)
                </a>
                <a href="{{ route('admin.rewardApplications.history', ['status' => 'rejected']) }}" 
                   class="btn {{ $currentStatus == 'rejected' ? 'btn-danger' : 'btn-default' }}">
                    বাতিল (Rejected)
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <form method="GET" action="{{ route('admin.rewardApplications.history') }}" class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="আইডি, নাম বা ফোন...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-history text-primary"></i> 
                        আবেদন হিস্টোরি তালিকা (মোট: {{ $applications->total() }})
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.rewardApplications.index') }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-clock-o"></i> পেন্ডিং আবেদন দেখুন
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th>Application ID</th>
                                <th>আবেদনকারী</th>
                                <th>পুরস্কার</th>
                                <th>পয়েন্ট কর্তন</th>
                                <th>আবেদনের তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th>এডমিন নোট ও গ্রহণের স্থান</th>
                                <th style="width: 100px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $app)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $app->application_no }}</strong>
                                        <br><small class="text-muted">{{ ucfirst($app->account_type) }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $app->user_name ?: optional($app->user)->name }}</strong>
                                        <br><i class="fa fa-phone text-muted"></i> {{ $app->user_phone ?: optional($app->user)->phone }}
                                    </td>
                                    <td>
                                        <strong>{{ $app->reward_name }}</strong>
                                        <br><span class="label label-warning">{{ number_format($app->required_points) }} Pts</span>
                                    </td>
                                    <td>
                                        @if($app->points_deducted)
                                            <span class="text-danger font-weight-bold">
                                                -{{ number_format($app->points_deducted_amount) }} Pts
                                            </span>
                                            <br><small class="text-success"><i class="fa fa-check"></i> কর্তন সম্পন্ন</small>
                                        @else
                                            <span class="text-muted">কর্তন হয়নি</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $app->created_at->format('d M, Y') }}
                                        <br><small class="text-muted">{{ $app->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($app->status == 'received')
                                            <span class="label label-info"><i class="fa fa-check-circle"></i> Received</span>
                                        @elseif($app->status == 'accepted')
                                            <span class="label label-success"><i class="fa fa-check"></i> Accepted</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="label label-danger"><i class="fa fa-times"></i> Rejected</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 250px;">
                                        @if($app->admin_note)
                                            <div style="font-size: 12px; color: #333;"><strong>নোট:</strong> {{ Str::limit($app->admin_note, 80) }}</div>
                                        @endif
                                        @if($app->collection_date || $app->collection_place)
                                            <div style="font-size: 11px; color: #666; margin-top: 3px;">
                                                <i class="fa fa-map-marker text-danger"></i> {{ $app->collection_place ?? 'ইত্তেবা অফিস' }}
                                                @if($app->collection_date) | {{ $app->collection_date }} @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.rewardApplications.show', $app->id) }}" class="btn btn-xs btn-default btn-block">
                                            <i class="fa fa-eye"></i> বিস্তারিত
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        কোনো পূর্ববর্তী আবেদন হিস্টোরি পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($applications->hasPages())
                    <div class="box-footer clearfix">
                        {{ $applications->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
