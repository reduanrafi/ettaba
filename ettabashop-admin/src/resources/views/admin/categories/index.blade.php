@extends('admin.layouts.layout')
@section('content')
    <section class="content-header">
        <h1>
            ক্যাটাগরি ব্যবস্থাপনা (Category Management)
            <small>হোমপেজ ক্যাটাগরির নাম, আইকন, ক্রম, সক্রিয়তা ও সেকশন নির্ধারণ</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-th-large text-primary"></i> সকল ক্যাটাগরি তালিকা</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('admin.homeSections.index') }}" class="btn btn-info btn-flat btn-sm" style="margin-right: 6px;">
                                <i class="fa fa-sliders"></i> হোমপেজ পণ্য সেকশন নিয়ন্ত্রণ
                            </a>
                            <a href="{{ route('category.create') }}" class="btn btn-success btn-flat btn-sm">
                                <i class="fa fa-plus"></i> নতুন ক্যাটাগরি যোগ করুন
                            </a>
                        </div>
                    </div>

                    <div class="col-md-8 col-md-offset-2" id="messageDiv">
                        @if(Session::has('success'))
                            @include('admin.layouts.message.success')
                        @elseif(Session::has('error'))
                            @include('admin.layouts.message.error')
                        @endif
                    </div>

                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 70px;">ক্রম (Order)</th>
                                <th style="width: 80px;">আইকন / ছবি</th>
                                <th>ক্যাটাগরির নাম (বাংলা ও ইংরেজি)</th>
                                <th>হোমপেজের কোন সেকশনে দেখানো হবে</th>
                                <th style="width: 110px;">সক্রিয় স্ট্যাটাস</th>
                                <th style="width: 110px;">দৃশ্যমানতা</th>
                                <th style="width: 160px;">অ্যাকশন</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($categories)
                                @foreach($categories as $category)
                                    @php
                                        $assignedSections = [];
                                        if(isset($homeSections)) {
                                            foreach($homeSections as $sec) {
                                                if(in_array((int)$category->id, $sec->category_ids_array)) {
                                                    $assignedSections[] = $sec->title_bn;
                                                }
                                            }
                                        }
                                        $isActive = isset($category->is_active) ? $category->is_active : 1;
                                    @endphp
                                    <tr style="{{ $category->is_deleted ? 'opacity: 0.6; background: #fef2f2;' : '' }}">
                                        <td class="text-center">
                                            <span class="badge bg-navy">{{ $category->priority ?? $loop->iteration }}</span>
                                            <div style="margin-top: 4px;">
                                                <a href="{{ route('category.moveOrder', ['id' => $category->id, 'dir' => 'up']) }}" class="btn btn-xs btn-default" title="সামনে/উপরে নিন">
                                                    <i class="fa fa-arrow-up"></i>
                                                </a>
                                                <a href="{{ route('category.moveOrder', ['id' => $category->id, 'dir' => 'down']) }}" class="btn btn-xs btn-default" title="পেছনে/নিচে নিন">
                                                    <i class="fa fa-arrow-down"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if(!empty($category->icon))
                                                <img src="{{ asset($category->icon) }}" width="42" height="42" style="object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; padding: 3px;">
                                            @elseif(!empty($category->image))
                                                <img src="{{ asset($category->image) }}" width="42" height="42" style="object-fit: cover; border-radius: 8px;">
                                            @elseif(!empty($category->icon_class))
                                                <div style="width: 42px; height: 42px; border-radius: 8px; background: #f0fdf4; color: {{ $category->icon_color ?: '#16a34a' }}; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid #bbf7d0;">
                                                    <i class="{{ $category->icon_class }}"></i>
                                                </div>
                                            @else
                                                <div style="width: 42px; height: 42px; border-radius: 8px; background: #f1f5f9; color: #64748b; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">
                                                    <i class="fa fa-th-large"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong style="font-size: 15px; color: #0f172a;">{{ $category->name_bn ?: $category->name_en }}</strong>
                                            @if($category->name_en && $category->name_bn != $category->name_en)
                                                <div class="text-muted small">{{ $category->name_en }}</div>
                                            @endif
                                            <div class="text-muted" style="font-size: 11px;">Slug: <code>{{ $category->slug }}</code></div>
                                        </td>
                                        <td>
                                            @if(count($assignedSections) > 0)
                                                @foreach($assignedSections as $secName)
                                                    <span class="label label-info" style="display: inline-block; margin: 2px; font-size: 11px;">{{ $secName }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">সকল পণ্য সেকশন</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('category.toggleActive', $category->id) }}" class="btn btn-xs {{ $isActive ? 'btn-success' : 'btn-danger' }}">
                                                <i class="fa {{ $isActive ? 'fa-check-circle' : 'fa-ban' }}"></i>
                                                {{ $isActive ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('category.toggleHide', ['id' => $category->id, 'hide' => ($category->is_deleted == 0 ? 1 : 0)]) }}"
                                               class="btn btn-xs {{ $category->is_deleted == 0 ? 'btn-info' : 'btn-warning' }}">
                                                <i class="fa {{ $category->is_deleted == 0 ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                                {{ $category->is_deleted == 0 ? 'দেখানো হচ্ছে (Show)' : 'লুকানো (Hidden)' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('category.edit', ['category' => $category->id]) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i> সম্পাদনা (Edit)
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
