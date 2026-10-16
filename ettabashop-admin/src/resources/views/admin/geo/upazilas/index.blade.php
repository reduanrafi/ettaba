@extends('admin.layouts.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-building"></i> উপজেলা / সিটি কর্পোরেশন / সেনানিবাস তালিকা</h3>
                    <div class="pull-right box-tools">
                        <a href="{{ route('geo.upazilas.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> নতুন যোগ করুন</a>
                    </div>
                </div>

                @if(Session::has('message'))
                    <div class="alert alert-success alert-dismissible" style="margin: 15px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-check"></i> {{ Session::get('message') }}
                    </div>
                @endif

                <!-- Filter Bar -->
                <div class="box-body" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <form method="GET" action="{{ route('geo.upazilas.index') }}" class="form-inline">
                        <div class="form-group" style="margin-right: 10px;">
                            <label style="margin-right: 5px;">জেলা:</label>
                            <select name="district_id" class="form-control input-sm">
                                <option value="">-- সকল জেলা --</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->bn_name ?: $d->name }} ({{ $d->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label style="margin-right: 5px;">ধরন:</label>
                            <select name="type" class="form-control input-sm">
                                <option value="">-- সকল ধরন --</option>
                                <option value="upazila" {{ request('type') == 'upazila' ? 'selected' : '' }}>উপজেলা (Upazila)</option>
                                <option value="city_corporation" {{ request('type') == 'city_corporation' ? 'selected' : '' }}>সিটি কর্পোরেশন</option>
                                <option value="cantonment" {{ request('type') == 'cantonment' ? 'selected' : '' }}>সেনানিবাস</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম দিয়ে খুঁজুন..." class="form-control input-sm">
                        </div>

                        <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-filter"></i> ফিল্টার</button>
                        <a href="{{ route('geo.upazilas.index') }}" class="btn btn-sm btn-default">রিসেট</a>
                    </form>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th style="width: 60px;">ID</th>
                                <th>বাংলা নাম</th>
                                <th>ইংরেজি নাম</th>
                                <th>জেলা</th>
                                <th>বিভাগ</th>
                                <th>ধরন (Type)</th>
                                <th>ওয়ার্ড সংখ্যা</th>
                                <th style="width: 170px;">একশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upazilas as $upz)
                                <tr>
                                    <td>{{ $upz->id }}</td>
                                    <td><strong>{{ $upz->bn_name }}</strong></td>
                                    <td>{{ $upz->name }}</td>
                                    <td>{{ $upz->district->bn_name ?? ($upz->district->name ?? '-') }}</td>
                                    <td>{{ $upz->district->division->bn_name ?? ($upz->district->division->name ?? '-') }}</td>
                                    <td>
                                        @if($upz->type === 'city_corporation')
                                            <span class="label label-success">সিটি কর্পোরেশন</span>
                                        @elseif($upz->type === 'cantonment')
                                            <span class="label label-warning">সেনানিবাস</span>
                                        @else
                                            <span class="label label-default">উপজেলা</span>
                                        @endif
                                    </td>
                                    <td>{{ $upz->ward_count ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('geo.unions.index', ['upazila_id' => $upz->id]) }}" class="btn btn-xs btn-info" title="ইউনিয়ন/ওয়ার্ডসমূহ দেখুন">
                                            <i class="fa fa-list"></i> ইউনিয়ন
                                        </a>
                                        <a href="{{ route('geo.upazilas.edit', $upz->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('geo.upazilas.destroy', $upz->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('আপনি কি নিশ্চিত এই উপজেলা ডিলিট করতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">কোনো তথ্য পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="box-footer clearfix">
                    <div class="pull-right">
                        {{ $upazilas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
