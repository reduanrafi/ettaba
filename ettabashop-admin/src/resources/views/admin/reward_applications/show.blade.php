@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        আবেদন বিস্তারিত ও পর্যালোচনা
        <small>Application: {{ $application->application_no }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('admin.rewardApplications.index') }}">আবেদন তালিকা</a></li>
        <li class="active">{{ $application->application_no }}</li>
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

    <div class="row">
        <!-- Left Column: User Profile & Reward Snapshot -->
        <div class="col-md-5">
            <!-- User Information Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user text-primary"></i> আবেদনকারীর প্রোফাইল তথ্য</h3>
                </div>
                <div class="box-body box-profile">
                    @php
                        $userProfile = optional($application->user)->profile;
                        $avatarUrl = !empty($userProfile->profile_image) ? asset($userProfile->profile_image) : asset('assets/images/user.png');
                    @endphp
                    <div class="text-center" style="margin-bottom: 15px;">
                        <img src="{{ $avatarUrl }}" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 90px; height: 90px; object-fit: cover;">
                        <h4 class="profile-username text-center" style="margin-top: 10px; margin-bottom: 2px;">
                            {{ $application->user_name ?: optional($application->user)->name }}
                        </h4>
                        <p class="text-muted text-center" style="font-size: 13px;">
                            {{ ucfirst($application->account_type) }} Account | UID: #{{ $application->user_id }}
                        </p>
                    </div>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>মোবাইল নম্বর:</b> 
                            <a class="pull-right" href="tel:{{ $application->user_phone }}">{{ $application->user_phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>eID (রেফারেল কোড):</b> 
                            <span class="pull-right text-navy font-weight-bold">{{ optional($application->user)->referral_code ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>বর্তমান পয়েন্ট ব্যালেন্স:</b> 
                            <span class="pull-right badge bg-green" style="font-size: 13px;">
                                {{ number_format($currentUserPoints) }} Pts
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>জাতীয় পরিচয়পত্র (NID):</b> 
                            <span class="pull-right">
                                @if(!empty($userProfile->nid))
                                    <span class="text-success"><i class="fa fa-check-circle"></i> {{ $userProfile->nid }}</span>
                                @else
                                    <span class="text-danger"><i class="fa fa-times-circle"></i> যুক্ত নেই</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>পিতার নাম:</b> 
                            <span class="pull-right text-muted">{{ $userProfile->father_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>মাতার নাম:</b> 
                            <span class="pull-right text-muted">{{ $userProfile->mother_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>ঠিকানা:</b> 
                            <span class="pull-right text-muted text-right" style="max-width: 65%;">
                                {{ $userProfile->address ?? 'N/A' }}
                            </span>
                        </li>
                    </ul>

                    @if(!$profileCheck['is_complete'])
                        <div class="alert alert-warning" style="margin-top: 10px; font-size: 12px;">
                            <i class="fa fa-exclamation-triangle"></i> <strong>প্রোফাইল সতর্কতা:</strong>
                            <br>নিম্নোক্ত তথ্য অসম্পূর্ণ: {{ implode(', ', $profileCheck['missing']) }}
                        </div>
                    @else
                        <div class="alert alert-success" style="margin-top: 10px; font-size: 12px; padding: 8px;">
                            <i class="fa fa-check-circle"></i> কাস্টমারের এনআইডি ও প্রয়োজনীয় প্রোফাইল তথ্য সম্পূর্ণ রয়েছে।
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reward Snapshot Box -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-gift text-info"></i> আবেদনকৃত পুরস্কারের তথ্য</h3>
                </div>
                <div class="box-body">
                    <div class="text-center" style="margin-bottom: 12px;">
                        @if(!empty($application->reward_image))
                            <img src="{{ asset($application->reward_image) }}" alt="Reward" style="max-height: 120px; border-radius: 8px; border: 1px solid #ddd;">
                        @endif
                        <h4 style="margin-top: 10px; color: #1e3a8a; font-weight: bold;">{{ $application->reward_name }}</h4>
                        <p class="text-muted">{{ $application->reward_title }}</p>
                    </div>

                    <table class="table table-condensed">
                        <tr>
                            <th>প্রয়োজনীয় পয়েন্ট:</th>
                            <td class="text-right">
                                <span class="label label-warning" style="font-size: 13px;">
                                    {{ number_format($application->required_points) }} Pts
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>পয়েন্ট কাটার স্ট্যাটাস:</th>
                            <td class="text-right">
                                @if($application->points_deducted)
                                    <span class="badge bg-red"><i class="fa fa-check"></i> -{{ number_format($application->points_deducted_amount) }} কাটা হয়েছে</span>
                                @else
                                    <span class="badge bg-gray">কাটা হয়নি</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>আবেদনের তারিখ:</th>
                            <td class="text-right">{{ $application->created_at->format('d M, Y - h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Admin Decision Form -->
        <div class="col-md-7">
            <!-- Current Status Box -->
            <div class="box box-solid {{ $application->status == 'pending' ? 'box-warning' : ($application->status == 'accepted' ? 'box-success' : ($application->status == 'received' ? 'box-info' : 'box-danger')) }}">
                <div class="box-header">
                    <h3 class="box-title">
                        বর্তমান স্ট্যাটাস: 
                        @if($application->status == 'pending')
                            <span class="label label-warning" style="font-size: 15px;"><i class="fa fa-clock-o"></i> Application Pending</span>
                        @elseif($application->status == 'accepted')
                            <span class="label label-success" style="font-size: 15px;"><i class="fa fa-check"></i> Application Accepted</span>
                        @elseif($application->status == 'received')
                            <span class="label label-info" style="font-size: 15px;"><i class="fa fa-trophy"></i> Reward Received / Handed Over</span>
                        @elseif($application->status == 'rejected')
                            <span class="label label-danger" style="font-size: 15px;"><i class="fa fa-times"></i> Application Rejected</span>
                        @endif
                    </h3>
                </div>

                <div class="box-body">
                    @if($application->status == 'pending')
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info-circle"></i> পয়েন্ট কাটার নিয়ম:</h4>
                            আবেদনটি <strong>Accept</strong> করার সাথে সাথে স্বয়ংক্রিয়ভাবে কাস্টমারের মোট পয়েন্ট ব্যালেন্স থেকে 
                            <strong>{{ number_format($application->required_points) }} পয়েন্ট</strong> কর্তন হয়ে যাবে এবং পয়েন্ট হিস্টোরিতে রেকর্ড যুক্ত হবে।
                            যদি <strong>Reject</strong> করা হয় তবে কোনো পয়েন্ট কাটা হবে না।
                        </div>

                        <!-- Accept Application Form -->
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title font-weight-bold">
                                    <i class="fa fa-check-circle"></i> আবেদন অনুমোদন করুন (Accept Application)
                                </h4>
                            </div>
                            <div class="panel-body">
                                <form method="POST" action="{{ route('admin.rewardApplications.accept', $application->id) }}" onsubmit="return confirm('আপনি কি নিশ্চিত আবেদনটি অনুমোদন করবেন? অনুমোদন করলে কাস্টমারের একাউন্ট থেকে পয়েন্ট কেটে নেওয়া হবে।');">
                                    @csrf
                                    
                                    @php
                                        $defaultNote = "আপনার {$application->reward_title} ({$application->reward_name}) পুরস্কারের আবেদন গ্রহণ করা হয়েছে। অনুগ্রহ করে নির্ধারিত তারিখ ও সময়ে উপস্থিত হয়ে আপনার পুরস্কার গ্রহণ করুন।";
                                    @endphp

                                    <div class="form-group">
                                        <label>এডমিন নোট (Customer Note) <span class="text-danger">*</span></label>
                                        <textarea name="admin_note" rows="3" class="form-control" required>{{ old('admin_note', $defaultNote) }}</textarea>
                                        <small class="text-muted">এই নোটটি কাস্টমার তার নোটিফিকেশন ও রিওয়ার্ড ডিটেইলসে দেখতে পারবেন।</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>পুরস্কার গ্রহণের তারিখ:</label>
                                                <input type="text" name="collection_date" class="form-control" value="{{ old('collection_date', date('d M, Y', strtotime('+7 days'))) }}" placeholder="উদাঃ ২০ সেপ্টেম্বর ২০২৬">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>পুরস্কার গ্রহণের সময়:</label>
                                                <input type="text" name="collection_time" class="form-control" value="{{ old('collection_time', 'সকাল ১০:০০ টা') }}" placeholder="উদাঃ সকাল ১০টা">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>পুরস্কার গ্রহণের স্থান:</label>
                                                <input type="text" name="collection_place" class="form-control" value="{{ old('collection_place', 'Ettaba Shop অফিস, কিশোরগঞ্জ') }}" placeholder="উদাঃ Ettaba Shop অফিস">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right" style="margin-top: 10px;">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fa fa-check"></i> আবেদন Accept ও পয়েন্ট কর্তন সম্পন্ন করুন
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Reject Application Form -->
                        <div class="panel panel-danger" style="margin-top: 20px;">
                            <div class="panel-heading">
                                <h4 class="panel-title font-weight-bold">
                                    <i class="fa fa-times-circle"></i> আবেদন বাতিল করুন (Reject Application)
                                </h4>
                            </div>
                            <div class="panel-body">
                                <form method="POST" action="{{ route('admin.rewardApplications.reject', $application->id) }}" onsubmit="return confirm('আপনি কি নিশ্চিত এই আবেদনটি বাতিল করতে চান?');">
                                    @csrf
                                    <div class="form-group">
                                        <label>বাতিলের কারণ ও নোট <span class="text-danger">*</span></label>
                                        <textarea name="admin_note" rows="2" class="form-control" placeholder="বাতিল করার সুনির্দিষ্ট কারণ লিখুন..." required></textarea>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-times"></i> আবেদন বাতিল (Reject) করুন
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @else
                        <!-- Already Processed View (Accepted / Received / Rejected) -->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h4><strong>কার্যক্রম তথ্য ও সিদ্ধান্ত:</strong></h4>
                                <table class="table table-bordered" style="margin-top: 15px;">
                                    <tr>
                                        <th style="width: 30%;">প্রসেসিং স্ট্যাটাস:</th>
                                        <td>
                                            @if($application->status == 'accepted')
                                                <span class="label label-success">Accepted (অনুমোদিত)</span>
                                            @elseif($application->status == 'received')
                                                <span class="label label-info">Received (পুরস্কার প্রদান সম্পন্ন)</span>
                                            @elseif($application->status == 'rejected')
                                                <span class="label label-danger">Rejected (বাতিলকৃত)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>পয়েন্ট কর্তন:</th>
                                        <td>
                                            @if($application->points_deducted)
                                                <span class="text-danger font-weight-bold">
                                                    -{{ number_format($application->points_deducted_amount) }} Points
                                                </span> (কাস্টমারের পয়েন্ট সফলভাবে কর্তন করা হয়েছে)
                                            @else
                                                <span class="text-muted">কোনো পয়েন্ট কাটা হয়নি</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>এডমিন নোট:</th>
                                        <td>{{ $application->admin_note ?? 'N/A' }}</td>
                                    </tr>
                                    @if($application->collection_date || $application->collection_place)
                                        <tr>
                                            <th>পুরস্কার গ্রহণের তারিখ ও সময়:</th>
                                            <td>{{ $application->collection_date }} ({{ $application->collection_time }})</td>
                                        </tr>
                                        <tr>
                                            <th>পুরস্কার গ্রহণের স্থান:</th>
                                            <td>{{ $application->collection_place }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>কার্যক্রম সম্পাদনকারী:</th>
                                        <td>{{ optional($application->processor)->name ?? 'Admin' }}</td>
                                    </tr>
                                    <tr>
                                        <th>কার্যক্রমের সময়:</th>
                                        <td>{{ optional($application->processed_at)->format('d M, Y - h:i A') ?? $application->updated_at->format('d M, Y - h:i A') }}</td>
                                    </tr>
                                </table>

                                @if($application->status == 'accepted')
                                    <!-- Mark Received Button -->
                                    <div style="background: #e0f2fe; padding: 15px; border-radius: 8px; margin-top: 20px;">
                                        <h5 class="text-navy font-weight-bold"><i class="fa fa-hand-holding"></i> পুরস্কার হস্তান্তর সম্পন্ন করুন:</h5>
                                        <p class="text-muted" style="font-size: 12.5px;">কাস্টমার সরাসরি অফিসে এসে পুরস্কার গ্রহণ করার পর নিচের বাটনে ক্লিক করে স্ট্যাটাস পরিবর্তন করুন।</p>
                                        <form method="POST" action="{{ route('admin.rewardApplications.markReceived', $application->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-block" onclick="return confirm('আপনি কি নিশ্চিত পুরস্কার হস্তান্তর সম্পন্ন হয়েছে?');">
                                                <i class="fa fa-check-circle"></i> পুরস্কার গ্রহণ সম্পন্ন হিসেবে চিহ্নিত করুন (Mark as Received)
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Update Details Form (Section 8.3: Edit Notes) -->
                                    <div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px;">
                                        <h5><strong>এডমিন নোট বা গ্রহণের তথ্য সংশোধন করুন:</strong></h5>
                                        <form method="POST" action="{{ route('admin.rewardApplications.updateDetails', $application->id) }}">
                                            @csrf
                                            <div class="form-group">
                                                <label>এডমিন নোট:</label>
                                                <textarea name="admin_note" rows="2" class="form-control">{{ old('admin_note', $application->admin_note) }}</textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>তারিখ:</label>
                                                    <input type="text" name="collection_date" class="form-control input-sm" value="{{ old('collection_date', $application->collection_date) }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>সময়:</label>
                                                    <input type="text" name="collection_time" class="form-control input-sm" value="{{ old('collection_time', $application->collection_time) }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>স্থান:</label>
                                                    <input type="text" name="collection_place" class="form-control input-sm" value="{{ old('collection_place', $application->collection_place) }}">
                                                </div>
                                            </div>
                                            <div class="text-right" style="margin-top: 10px;">
                                                <button type="submit" class="btn btn-default btn-sm">
                                                    <i class="fa fa-pencil"></i> নোট ও তথ্য আপডেট করুন
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="box-footer">
                    <a href="{{ route('admin.rewardApplications.index') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> আবেদন তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
