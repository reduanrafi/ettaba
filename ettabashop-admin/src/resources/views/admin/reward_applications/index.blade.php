@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        পুরস্কারের আবেদনসমূহ (Reward Applications)
        <small>নতুন জমা হওয়া আবেদন ও যাচাইকরণ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li class="active">আবেদন তালিকা</li>
    </ol>
</section>

<section class="content">
    <!-- Filter Bar -->
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-7">
            <div class="btn-group">
                <a href="{{ route('admin.rewardApplications.index', ['account_type' => '']) }}" 
                   class="btn {{ $currentFilter == 'all' || empty($currentFilter) ? 'btn-primary' : 'btn-default' }}">
                    সকল আবেদন
                </a>
                <a href="{{ route('admin.rewardApplications.index', ['account_type' => 'customer']) }}" 
                   class="btn {{ $currentFilter == 'customer' ? 'btn-primary' : 'btn-default' }}">
                    কাস্টমার
                </a>
                <a href="{{ route('admin.rewardApplications.index', ['account_type' => 'partner']) }}" 
                   class="btn {{ $currentFilter == 'partner' ? 'btn-primary' : 'btn-default' }}">
                    পার্টনার
                </a>
                <a href="{{ route('admin.rewardApplications.index', ['account_type' => 'merchant']) }}" 
                   class="btn {{ $currentFilter == 'merchant' ? 'btn-primary' : 'btn-default' }}">
                    মার্চেন্ট
                </a>
            </div>
        </div>

        <div class="col-md-5">
            <form method="GET" action="{{ route('admin.rewardApplications.index') }}" class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="আইডি, নাম, ফোন বা পুরস্কার লিখে খুঁজুন...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i> খুঁজুন</button>
                </span>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-clock-o text-yellow"></i> 
                        অপেক্ষমান আবেদনসমূহ (Pending Requests: {{ $applications->total() }})
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.rewardApplications.history') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-history"></i> পূর্বের আবেদনের হিস্টোরি
                        </a>
                    </div>
                </div>

                <div class="col-md-8 col-md-offset-2" id="messageDiv" style="margin-top: 10px;">
                    @if(Session::has('success'))
                        @include('admin.layouts.message.success')
                    @elseif(Session::has('error'))
                        @include('admin.layouts.message.error')
                    @endif
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th>Application ID</th>
                                <th>আবেদনকারী</th>
                                <th>অ্যাকাউন্ট টাইপ</th>
                                <th>মোবাইল নম্বর</th>
                                <th>পুরস্কারের নাম</th>
                                <th>প্রয়োজনীয় পয়েন্ট</th>
                                <th>আবেদনের তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th style="width: 140px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $app)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $app->application_no }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $app->user_name ?: optional($app->user)->name }}</strong>
                                        <br><small class="text-muted">UID: #{{ $app->user_id }}</small>
                                    </td>
                                    <td>
                                        @if($app->account_type == 'partner')
                                            <span class="label label-primary">পার্টনার</span>
                                        @elseif($app->account_type == 'merchant')
                                            <span class="label label-warning">মার্চেন্ট</span>
                                        @else
                                            <span class="label label-info">কাস্টমার</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa fa-phone text-muted"></i> {{ $app->user_phone ?: optional($app->user)->phone }}
                                    </td>
                                    <td>
                                        <strong>{{ $app->reward_name }}</strong>
                                        @if($app->reward_title)
                                            <br><small class="text-muted">{{ $app->reward_title }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="label label-warning" style="font-size: 13px;">
                                            {{ number_format($app->required_points) }} Pts
                                        </span>
                                    </td>
                                    <td>
                                        {{ $app->created_at->format('d M, Y') }}
                                        <br><small class="text-muted">{{ $app->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="label label-warning">
                                            <i class="fa fa-spinner fa-spin"></i> Pending
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.rewardApplications.show', $app->id) }}" class="btn btn-sm btn-primary btn-block">
                                            <i class="fa fa-eye"></i> যাচাই ও সিদ্ধান্ত
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        বর্তমানে কোনো অপেক্ষমান পুরস্কারের আবেদন নেই।
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
