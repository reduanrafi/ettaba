@extends('admin.layouts.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-map-signs"></i> পৌরসভা / ইউনিয়ন / অঞ্চল / ওয়ার্ড তালিকা</h3>
                    <div class="pull-right box-tools">
                        <a href="{{ route('geo.unions.create', ['district_id' => request('district_id')]) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> নতুন যোগ করুন
                        </a>
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
                    <form method="GET" action="{{ route('geo.unions.index') }}" class="form-inline">
                        <div class="form-group" style="margin-right: 10px;">
                            <label style="margin-right: 5px;">জেলা:</label>
                            <select name="district_id" id="filter_district_id" class="form-control input-sm">
                                <option value="">-- সকল জেলা --</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->bn_name ?: $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label style="margin-right: 5px;">উপজেলা/সিটি কর্পোরেশন:</label>
                            <select name="upazila_id" id="filter_upazila_id" class="form-control input-sm">
                                <option value="">-- সকল উপজেলা --</option>
                                @foreach($upazilas as $u)
                                    <option value="{{ $u->id }}" {{ request('upazila_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->bn_name ?: $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label style="margin-right: 5px;">ধরন:</label>
                            <select name="type" class="form-control input-sm">
                                <option value="">-- সকল ধরন --</option>
                                <option value="union" {{ request('type') == 'union' ? 'selected' : '' }}>ইউনিয়ন</option>
                                <option value="municipality" {{ request('type') == 'municipality' ? 'selected' : '' }}>পৌরসভা</option>
                                <option value="zone" {{ request('type') == 'zone' ? 'selected' : '' }}>অঞ্চল / জোন</option>
                                <option value="ward" {{ request('type') == 'ward' ? 'selected' : '' }}>ওয়ার্ড</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম দিয়ে খুঁজুন..." class="form-control input-sm">
                        </div>

                        <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-filter"></i> ফিল্টার</button>
                        <a href="{{ route('geo.unions.index') }}" class="btn btn-sm btn-default">রিসেট</a>
                    </form>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th style="width: 60px;">ID</th>
                                <th>বাংলা নাম</th>
                                <th>ইংরেজি নাম</th>
                                <th>উপজেলা / সিটি কর্পোরেশন</th>
                                <th>জেলা</th>
                                <th>ধরন (Type)</th>
                                <th>ওয়ার্ড সংখ্যা</th>
                                <th style="width: 120px;">একশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unions as $un)
                                <tr>
                                    <td>{{ $un->id }}</td>
                                    <td><strong>{{ $un->bn_name }}</strong></td>
                                    <td>{{ $un->name }}</td>
                                    <td>{{ $un->upazila->bn_name ?? ($un->upazila->name ?? '-') }}</td>
                                    <td>{{ $un->upazila->district->bn_name ?? ($un->upazila->district->name ?? '-') }}</td>
                                    <td>
                                        @if($un->type === 'municipality')
                                            <span class="label label-primary">পৌরসভা</span>
                                        @elseif($un->type === 'zone')
                                            <span class="label label-info">অঞ্চল</span>
                                        @elseif($un->type === 'ward')
                                            <span class="label label-warning">ওয়ার্ড</span>
                                        @else
                                            <span class="label label-default">ইউনিয়ন</span>
                                        @endif
                                    </td>
                                    <td>{{ $un->ward_count ?: 9 }} টি</td>
                                    <td>
                                        <a href="{{ route('geo.unions.edit', $un->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('geo.unions.destroy', $un->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('আপনি কি নিশ্চিত এই ইউনিয়ন ডিলিট করতে চান?');">
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
                        {{ $unions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-script')
<script>
    $('#filter_district_id').on('change', function() {
        let distId = $(this).val();
        let upzSelect = $('#filter_upazila_id');
        upzSelect.html('<option value="">লোড হচ্ছে...</option>');

        if (!distId) {
            upzSelect.html('<option value="">-- সকল উপজেলা --</option>');
            return;
        }

        $.get("{{ url('/private-panel/geo/ajax/upazilas') }}/" + distId, function(data) {
            let options = '<option value="">-- সকল উপজেলা --</option>';
            $.each(data, function(i, item) {
                options += '<option value="' + item.id + '">' + (item.bn_name || item.name) + '</option>';
            });
            upzSelect.html(options);
        });
    });
</script>
@endsection
