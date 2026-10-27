@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        {{ $reward->reward_name }}
        <small>মার্চেন্ট পুরস্কার বিস্তারিত বিবরণ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.handcash') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('handcash.rewards.index') }}">মার্চেন্ট রিওয়ার্ডস</a></li>
        <li class="active">{{ $reward->reward_name }}</li>
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
        <div class="col-md-8 col-md-offset-2">
            <!-- Main Reward Box -->
            <div class="box box-primary" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
                <div class="box-header with-border" style="background: #f8fafc;">
                    <div class="d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="badge bg-navy" style="font-size: 13px; padding: 6px 14px; border-radius: 20px;">
                            <i class="fa fa-trophy text-yellow"></i> Reward Level #{{ $reward->level_no }}
                        </span>
                        <a href="{{ route('handcash.rewards.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> তালিকা ফিরে যান
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 25px;">
                    <!-- Reward Image & Name -->
                    <div class="text-center" style="margin-bottom: 25px;">
                        <div style="width: 130px; height: 130px; margin: 0 auto 15px; border-radius: 16px; border: 2px solid #e2e8f0; padding: 6px; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                            @if(!empty($reward->image))
                                <img src="{{ config('app.image_url') . $reward->image }}" 
                                     onerror="this.src='{{ asset($reward->image) }}'" 
                                     style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 12px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #fef3c7; color: #d97706; border-radius: 12px;">
                                    <i class="fa fa-gift fa-3x"></i>
                                </div>
                            @endif
                        </div>

                        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 4px; color: #1e293b;">
                            {{ $reward->reward_name }}
                        </h2>
                        @if($reward->subtitle)
                            <p class="text-muted" style="font-size: 14px; margin-bottom: 8px;">{{ $reward->subtitle }}</p>
                        @endif

                        <div style="display: inline-block; background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 14px;">
                            <i class="fa fa-diamond"></i> প্রয়োজনীয় পয়েন্ট: {{ number_format($reward->required_points) }} Points
                        </div>

                        @if($reward->description)
                            <div style="max-width: 600px; margin: 15px auto 0; font-size: 13.5px; line-height: 1.6; color: #475569; background: #f8fafc; padding: 12px 16px; border-radius: 10px; text-align: left;">
                                {{ $reward->description }}
                            </div>
                        @endif
                    </div>

                    <!-- Progress Statistics Bar -->
                    <div style="background: #f1f5f9; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                        <h4 style="font-size: 15px; font-weight: bold; margin-top: 0; margin-bottom: 15px; color: #334155;">
                            <i class="fa fa-line-chart text-primary"></i> আপনার পয়েন্ট অগ্রগতি (Merchant Progress)
                        </h4>

                        <div class="row text-center" style="margin-bottom: 15px;">
                            <div class="col-xs-4">
                                <div style="background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <small class="text-muted" style="display: block;">আপনার Point</small>
                                    <strong class="text-primary" style="font-size: 16px;">{{ number_format($userPoints) }}</strong>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div style="background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <small class="text-muted" style="display: block;">প্রয়োজন</small>
                                    <strong class="text-warning" style="font-size: 16px;">{{ number_format($reward->required_points) }}</strong>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div style="background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <small class="text-muted" style="display: block;">বাকি পয়েন্ট</small>
                                    <strong class="text-danger" style="font-size: 16px;">{{ number_format($reward->needed_points) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; margin-bottom: 5px;">
                                <span>অগ্রগতি লক্ষ্য:</span>
                                <span>{{ $reward->progress_percent }}% সম্পন্ন</span>
                            </div>
                            <div class="progress progress-sm" style="background: #cbd5e1; border-radius: 6px; height: 12px; margin-bottom: 0;">
                                <div class="progress-bar progress-bar-success" style="width: {{ $reward->progress_percent }}%; border-radius: 6px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Information & How-To Earn -->
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-6" style="margin-bottom: 15px;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; height: 100%;">
                                <h4 style="font-size: 14px; font-weight: bold; margin-top: 0; color: #1e3a8a;">
                                    <i class="fa fa-info-circle text-info"></i> {{ $reward->info_title ?: 'পুরস্কার সম্পর্কিত তথ্য:' }}
                                </h4>
                                <ul style="padding-left: 18px; margin-bottom: 0; font-size: 13px; line-height: 1.8; color: #475569;">
                                    @php
                                        $infoList = $reward->info_items_array;
                                    @endphp
                                    @if(!empty($infoList) && count($infoList) > 0)
                                        @foreach($infoList as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    @else
                                        <li>আসল ব্র্যান্ডেড ও শতভাগ মানসম্মত মার্চেন্ট উপহার</li>
                                        <li>ইত্তেবা অফিস থেকে সরাসরি ডেলিভারি ও হ্যান্ডওভার</li>
                                        <li>অফিসিয়াল সার্ভিস ও ব্র্যান্ড ওয়ারেন্টি প্রযোজ্য</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6" style="margin-bottom: 15px;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; height: 100%;">
                                <h4 style="font-size: 14px; font-weight: bold; margin-top: 0; color: #b45309;">
                                    <i class="fa fa-coins text-warning"></i> {{ $reward->howto_title ?: 'কীভাবে পয়েন্ট অর্জন করবেন?' }}
                                </h4>
                                <ul style="padding-left: 18px; margin-bottom: 0; font-size: 13px; line-height: 1.8; color: #475569;">
                                    @php
                                        $howtoList = $reward->howto_items_array;
                                    @endphp
                                    @if(!empty($howtoList) && count($howtoList) > 0)
                                        @foreach($howtoList as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    @else
                                        <li>মার্চেন্ট শপে নিয়মিত ক্যাশব্যাক সেল প্রদান করুন</li>
                                        <li>গ্রাহকদের মানসম্মত পণ্য ও সেবা দিয়ে সেল বাড়ান</li>
                                        <li>বিশেষ মার্চেন্ট মেগা ক্যাম্পেইনে অংশগ্রহণ করুন</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Warning if Incomplete -->
                    @if(!$completeness['is_complete'])
                        <div class="alert alert-warning" style="border-radius: 8px;">
                            <i class="fa fa-exclamation-triangle"></i> <strong>প্রোফাইল আপডেট প্রয়োজন:</strong>
                            পুরস্কারের আবেদনের জন্য আপনার এনআইডি ও প্রোফাইল সম্পূর্ণ থাকা বাধ্যতামূলক।
                            <br>ঘাটতি: <strong>{{ implode(', ', $completeness['missing']) }}</strong>
                        </div>
                    @endif

                    <!-- Action Apply Button -->
                    <div class="text-center" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                        @if($reward->can_apply)
                            @if($completeness['is_complete'])
                                <form method="POST" action="{{ route('handcash.rewards.apply') }}" onsubmit="return confirm('আপনি কি নিশ্চিত এই মার্চেন্ট পুরস্কারের জন্য আবেদন করতে চান?');">
                                    @csrf
                                    <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                    <button type="submit" class="btn btn-success btn-lg" style="padding: 12px 35px; border-radius: 25px; font-weight: bold; font-size: 16px;">
                                        <i class="fa fa-paper-plane"></i> এই পুরস্কারের জন্য আবেদন করুন
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-default btn-lg" disabled style="padding: 12px 35px; border-radius: 25px;">
                                    <i class="fa fa-lock"></i> আবেদন করতে প্রোফাইল আপডেট করুন
                                </button>
                            @endif
                        @elseif($reward->user_status === 'in_progress')
                            <button type="button" class="btn btn-warning btn-lg" disabled style="padding: 12px 35px; border-radius: 25px;">
                                <i class="fa fa-spinner fa-spin"></i> আপনার আবেদন প্রক্রিয়াধীন রয়েছে
                            </button>
                            <p class="text-muted" style="margin-top: 10px; font-size: 13px;">এডমিন আবেদনটি যাচাই-বাছাই করছেন।</p>
                        @elseif($reward->user_status === 'accepted' || $reward->user_status === 'received')
                            <button type="button" class="btn btn-success btn-lg" disabled style="padding: 12px 35px; border-radius: 25px;">
                                <i class="fa fa-check-circle"></i> পুরস্কারটি গ্রহণ সম্পন্ন হয়েছে
                            </button>
                        @else
                            <button type="button" class="btn btn-default btn-lg" disabled style="padding: 12px 35px; border-radius: 25px;">
                                <i class="fa fa-lock"></i> পয়েন্ট অপর্যাপ্ত (লকড)
                            </button>
                            <p class="text-muted" style="margin-top: 10px; font-size: 13px;">
                                আবেদন করতে আরও <strong>{{ number_format($reward->needed_points) }}</strong> পয়েন্ট প্রয়োজন।
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
