@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        পুরস্কার গ্লোবাল সেটিংস ও মেসেজ
        <small>Motivational Message, Terms & How To Earn Points</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li><a href="{{ route('admin.rewards.index') }}">পুরস্কার তালিকা</a></li>
        <li class="active">সেটিংস</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cogs text-primary"></i> হোম ও অল-রিওয়ার্ডস পেইজের মেসেজ কনফিগারেশন</h3>
                </div>

                <div class="col-md-8 col-md-offset-2" id="messageDiv" style="margin-top: 10px;">
                    @if(Session::has('success'))
                        @include('admin.layouts.message.success')
                    @elseif(Session::has('error'))
                        @include('admin.layouts.message.error')
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.rewards.settings.update') }}" class="form-horizontal">
                    @csrf
                    <div class="box-body" style="padding: 25px;">
                        <!-- Motivational Message -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">মোটিভেশনাল মেসেজ (Motivational Message)</label>
                            <div class="col-sm-9">
                                <textarea name="motivational_message" rows="4" class="form-control" placeholder="হোম পেইজ ও রিওয়ার্ডস পেইজের ব্যানারে প্রদর্শিত হবে...">{{ old('motivational_message', $motivationalMsg) }}</textarea>
                                <span class="help-block text-muted">এই মেসেজটি কাস্টমার হোম পেইজের রিওয়ার্ড ব্যানার ও All Rewards পেইজের নিচে প্রদর্শিত হয়।</span>
                            </div>
                        </div>

                        <!-- How To Earn Points List -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">কীভাবে Point অর্জন করবেন? (প্রতি লাইনে একটি)</label>
                            <div class="col-sm-9">
                                <textarea name="how_to_earn_points" rows="5" class="form-control">{{ old('how_to_earn_points', $howToEarnText) }}</textarea>
                                <span class="help-block text-muted">All Rewards পেইজের উৎসাহমূলক অংশে ডানে তালিকা আকারে প্রদর্শিত হবে।</span>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">পুরস্কার সম্পর্কিত নিয়ম ও শর্তাবলি</label>
                            <div class="col-sm-9">
                                <textarea name="terms_and_conditions" rows="5" class="form-control">{{ old('terms_and_conditions', $terms) }}</textarea>
                                <span class="help-block text-muted">কাস্টমার অল-রিওয়ার্ডস পেইজের নিচের বারে ক্লিকে এই নিয়ম ও শর্তাবলি দেখতে পারবেন।</span>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary" style="padding-left: 25px; padding-right: 25px;">
                            <i class="fa fa-save"></i> সেটিংস আপডেট করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
