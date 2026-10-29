@extends('admin.layouts.layout')
@section('content')
    <section class="content-header">
        <h1>ক্যাটাগরি সম্পাদনা (Edit Category)</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-edit text-primary"></i> ক্যাটাগরি আপডেট করুন: {{ $category->name_bn ?: $category->name_en }}</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('category.index') }}" class="btn btn-primary btn-flat btn-sm">
                                <i class="fa fa-list"></i> সকল ক্যাটাগরি দেখুন
                            </a>
                        </div>
                    </div>

                    <form class="form-horizontal" action="{{ route('category.update', ['category' => $category->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {!! method_field('PUT') !!}
                        <input type="hidden" name="sync_sections" value="1">

                        <div class="box-body">
                            @if(Session::has('success'))
                                @include('admin.layouts.message.success')
                            @elseif(Session::has('error'))
                                @include('admin.layouts.message.error')
                            @endif

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ক্যাটাগরির নাম (বাংলা) <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name_bn" value="{{ $category->name_bn ?: $category->name_en }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ক্যাটাগরির নাম (ইংরেজি)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name_en" value="{{ $category->name_en }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">স্লাগ (Slug)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="slug" value="{{ $category->slug }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">প্রদর্শন ক্রম (Serial / Priority)</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="priority" value="{{ $category->priority ?? 0 }}">
                                    <small class="text-muted">ছোট সংখ্যা আগে দেখাবে</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">FontAwesome আইকন ক্লাস ও রঙ</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="icon_class" value="{{ $category->icon_class }}" placeholder="যেমন: fas fa-shopping-cart, fas fa-tshirt">
                                </div>
                                <div class="col-sm-3">
                                    <input type="color" class="form-control" name="icon_color" value="{{ $category->icon_color ?: '#16a34a' }}" style="height: 34px; padding: 2px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">আইকন / ছবি আপলোড (ঐচ্ছিক)</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" onchange="loadFile(event)" name="image" accept="image/*">
                                    @if(!empty($category->image))
                                        <p style="margin-top: 8px;"><img id="output" src="{{ asset($category->image) }}" width="80" style="border-radius: 8px;"/></p>
                                    @else
                                        <p style="margin-top: 8px;"><img id="output" width="80" style="border-radius: 8px;"/></p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">প্যারেন্ট ক্যাটাগরি (ঐচ্ছিক)</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="parent_id">
                                        <option value="">-- মূল ক্যাটাগরি (Main Category) --</option>
                                        @foreach($categories as $parent)
                                            @if($parent->id != $category->id)
                                                <option @if($category->parent_id == $parent->id) selected @endif value="{{ $parent->id }}">
                                                    {{ $parent->name_bn ?: $parent->name_en }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if(isset($homeSections) && count($homeSections) > 0)
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">হোমপেজের কোন সেকশনে এই ক্যাটাগরির পণ্য দেখানো হবে?</label>
                                    <div class="col-sm-8">
                                        <div class="well well-sm" style="background: #f8fafc; margin-bottom: 0;">
                                            @foreach($homeSections as $sec)
                                                <label class="checkbox-inline" style="margin-left: 0; margin-right: 18px; margin-bottom: 6px;">
                                                    <input type="checkbox" name="home_section_ids[]" value="{{ $sec->id }}"
                                                        {{ in_array((int)$category->id, $sec->category_ids_array) ? 'checked' : '' }}>
                                                    <strong>{{ $sec->title_bn }}</strong>
                                                </label>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">একাধিক সেকশন নির্বাচন করা যাবে।</small>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="col-sm-3 control-label">সেটিংস</label>
                                <div class="col-sm-8">
                                    <div class="checkbox">
                                        <label style="margin-right: 20px;">
                                            <input type="checkbox" name="is_active" value="1" {{ (!isset($category->is_active) || $category->is_active) ? 'checked' : '' }}>
                                            <strong>ক্যাটাগরি সক্রিয় (Active)</strong>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="advance_payment_required" value="1" {{ $category->advance_payment_required ? 'checked' : '' }}>
                                            <strong>অগ্রিম পেমেন্ট বাধ্যতামূলক (COD বন্ধ)</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer text-right">
                            <a href="{{ route('category.index') }}" class="btn btn-default">বাতিল</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> আপডেট করুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
