@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        {{ $pageTitle ?? 'পুরস্কার তালিকা' }}
        <small>পুরস্কার তালিকা ও পয়েন্ট কনফিগারেশন</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li class="active">পুরস্কার তালিকা</li>
    </ol>
</section>

<section class="content">
    @php
        $currentType = $currentType ?? 'customer';
    @endphp
    <!-- Type Filter Tabs -->
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="{{ route('admin.rewards.index', 'customer') }}" 
                   class="btn {{ $currentType == 'customer' ? 'btn-primary' : 'btn-default' }}">
                    <i class="fa fa-users"></i> কাস্টমার পুরস্কার (Customer)
                </a>
                <a href="{{ route('admin.rewards.index', 'partner') }}" 
                   class="btn {{ $currentType == 'partner' ? 'btn-primary' : 'btn-default' }}">
                    <i class="fa fa-handshake-o"></i> পার্টনার পুরস্কার (Partner)
                </a>
                <a href="{{ route('admin.rewards.index', 'merchant') }}" 
                   class="btn {{ $currentType == 'merchant' ? 'btn-primary' : 'btn-default' }}">
                    <i class="fa fa-shopping-bag"></i> মার্চেন্ট পুরস্কার (Merchant)
                </a>
            </div>

            <a href="{{ route('admin.rewards.create', $currentType) }}" class="btn btn-success pull-right">
                <i class="fa fa-plus-circle"></i> নতুন পুরস্কার যুক্ত করুন
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-trophy text-yellow"></i> 
                        {{ ucfirst($currentType) }} Rewards List (মোট: {{ $rewards->count() }})
                    </h3>
                </div>

                <div class="col-md-8 col-md-offset-2" id="messageDiv" style="margin-top: 10px;">
                    @if(Session::has('success'))
                        @include('admin.layouts.message.success')
                    @elseif(Session::has('error'))
                        @include('admin.layouts.message.error')
                    @endif
                </div>

                <div class="box-body table-responsive">
                    <table id="example2" class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 50px;">ক্রমিক</th>
                                <th style="width: 70px;">ছবি</th>
                                <th>পুরস্কার টাইটেল</th>
                                <th>পুরস্কার নাম (উপহার)</th>
                                <th>প্রয়োজনীয় পয়েন্ট</th>
                                <th>অর্ডার</th>
                                <th>স্ট্যাটাস</th>
                                <th style="width: 170px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rewards as $reward)
                                <tr style="{{ $reward->is_visible ? '' : 'background-color: #fcf8e3; opacity: 0.8;' }}">
                                    <td class="text-center font-weight-bold">
                                        <span class="badge bg-navy">#{{ $reward->level_no }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($reward->image))
                                            <img src="{{ asset($reward->image) }}" alt="Reward" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; color: #999;">
                                                <i class="fa fa-gift fa-2x"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $reward->title }}</strong>
                                        @if($reward->subtitle)
                                            <br><small class="text-muted">{{ $reward->subtitle }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-primary font-weight-bold">{{ $reward->reward_name }}</span>
                                    </td>
                                    <td>
                                        <span class="label label-warning" style="font-size: 13px;">
                                            <i class="fa fa-diamond"></i> {{ number_format($reward->required_points) }} Pts
                                        </span>
                                    </td>
                                    <td>{{ $reward->sort_order }}</td>
                                    <td>
                                        @if($reward->is_visible)
                                            <span class="label label-success"><i class="fa fa-check"></i> দৃশ্যমান</span>
                                        @else
                                            <span class="label label-default"><i class="fa fa-eye-slash"></i> লুকানো (Hidden)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.rewards.toggleHide', $reward->id) }}" 
                                           class="btn btn-xs {{ $reward->is_visible ? 'btn-warning' : 'btn-info' }}"
                                           title="{{ $reward->is_visible ? 'পুরস্কারটি হাইড করুন' : 'পুরস্কারটি শো করুন' }}">
                                            <i class="fa {{ $reward->is_visible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            {{ $reward->is_visible ? 'Hide' : 'Show' }}
                                        </a>

                                        <a href="{{ route('admin.rewards.edit', $reward->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <a href="{{ route('admin.rewards.destroy', $reward->id) }}" 
                                           class="btn btn-xs btn-danger" 
                                           onclick="return confirm('আপনি কি নিশ্চিত এই পুরস্কারটি মুছে ফেলতে চান?');">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        কোনো পুরস্কার পাওয়া যায়নি। নতুন পুরস্কার যোগ করতে উপরের বাটনে ক্লিক করুন।
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
