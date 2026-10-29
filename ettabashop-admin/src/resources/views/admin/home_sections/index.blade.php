@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        হোমপেজ পণ্য সেকশন নিয়ন্ত্রণ (Homepage Product Sections)
        <small>পণ্য সেকশন যোগ, লুকানো, ক্রম পরিবর্তন এবং কোন সেকশনে কোন ক্যাটাগরির পণ্য দেখানো হবে তা নির্ধারণ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li class="active">হোমপেজ পণ্য সেকশন</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1" id="messageDiv">
            @if(Session::has('success'))
                @include('admin.layouts.message.success')
            @elseif(Session::has('error'))
                @include('admin.layouts.message.error')
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Sections Table (8 cols) -->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list-ol text-primary"></i> হোমপেজ সেকশনসমূহ (মোট: {{ $sections->count() }} টি)</h3>
                    <div class="pull-right">
                        <a href="{{ route('product.index') }}" class="btn btn-xs btn-info"><i class="fa fa-star"></i> ফিচার পণ্য নির্বাচন ({{ $featuredCount }} টি নির্বাচিত)</a>
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 85px;">সিরিয়াল</th>
                                <th>সেকশনের নাম ও ধরন</th>
                                <th>নির্ধারিত ক্যাটাগরিসমূহ / নিয়ম</th>
                                <th style="width: 75px;">পণ্য সংখ্যা</th>
                                <th style="width: 95px;">স্ট্যাটাস</th>
                                <th style="width: 145px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                                @php
                                    $catIds = $section->category_ids_array;
                                    $matchedCats = $categories->whereIn('id', $catIds);
                                @endphp
                                <tr style="{{ $section->is_visible ? '' : 'background-color: #fef2f2; opacity: 0.75;' }}">
                                    <td class="text-center">
                                        <span class="badge bg-navy">{{ $section->sort_order }}</span>
                                        <div style="margin-top: 4px;">
                                            <a href="{{ route('admin.homeSections.moveOrder', ['id' => $section->id, 'dir' => 'up']) }}" class="btn btn-xs btn-default" title="উপরে নিন">
                                                <i class="fa fa-arrow-up"></i>
                                            </a>
                                            <a href="{{ route('admin.homeSections.moveOrder', ['id' => $section->id, 'dir' => 'down']) }}" class="btn btn-xs btn-default" title="নিচে নিন">
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <span style="width: 32px; height: 32px; border-radius: 6px; background: #f0fdf4; color: {{ $section->icon_color ?: '#16a34a' }}; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; border: 1px solid #bbf7d0;">
                                                <i class="{{ $section->icon ?: 'fas fa-bolt' }}"></i>
                                            </span>
                                            <div>
                                                <strong style="font-size: 14px;">{{ $section->title_bn }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    @if($section->section_type === 'auto_cashback')
                                                        <span class="text-success"><i class="fa fa-magic"></i> স্বয়ংক্রিয় (সর্বোচ্চ ক্যাশব্যাক)</span>
                                                    @elseif($section->section_type === 'auto_point')
                                                        <span class="text-warning"><i class="fa fa-magic"></i> স্বয়ংক্রিয় (সর্বোচ্চ পয়েন্ট)</span>
                                                    @elseif($section->section_type === 'featured')
                                                        <span class="text-info"><i class="fa fa-star"></i> এডমিন নির্বাচিত ফিচার পণ্য</span>
                                                    @elseif($section->section_type === 'all')
                                                        <span class="text-primary"><i class="fa fa-th"></i> সকল ক্যাটাগরির পণ্য</span>
                                                    @else
                                                        <span><i class="fa fa-folder-open-o"></i> ক্যাটাগরি ভিত্তিক</span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($section->section_type === 'auto_cashback')
                                            <span class="label label-success">সর্বোচ্চ ক্যাশব্যাক অনুযায়ী অটোমেটিক (সমান হলে বেশি অর্ডার আগে)</span>
                                        @elseif($section->section_type === 'auto_point')
                                            <span class="label label-warning">সর্বোচ্চ পয়েন্ট অনুযায়ী অটোমেটিক (সমান হলে বেশি অর্ডার আগে)</span>
                                        @elseif($section->section_type === 'featured')
                                            <span class="label label-info">পণ্য তালিকা থেকে Featured নির্বাচিত পণ্যসমূহ</span>
                                            @if($matchedCats->count() > 0)
                                                <div style="margin-top: 4px;">
                                                    @foreach($matchedCats as $c)
                                                        <span class="label label-default" style="margin: 1px; display: inline-block;">{{ $c->name_bn ?: $c->name_en }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @elseif($section->section_type === 'all')
                                            @if($matchedCats->count() > 0)
                                                @foreach($matchedCats as $c)
                                                    <span class="label label-primary" style="margin: 2px; display: inline-block;">{{ $c->name_bn ?: $c->name_en }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">সকল সক্রিয় ক্যাটাগরির পণ্য</span>
                                            @endif
                                        @else
                                            @if($matchedCats->count() > 0)
                                                @foreach($matchedCats as $c)
                                                    <span class="label label-primary" style="margin: 2px; display: inline-block;">{{ $c->name_bn ?: $c->name_en }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-danger small"><i class="fa fa-info-circle"></i> কোনো ক্যাটাগরি নির্বাচন করা হয়নি (Edit বাটনে ক্লিক করে ক্যাটাগরি দিন)</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $section->product_limit }} টি</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($section->is_visible)
                                            <span class="label label-success"><i class="fa fa-eye"></i> দৃশ্যমান</span>
                                        @else
                                            <span class="label label-default"><i class="fa fa-eye-slash"></i> লুকানো</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.homeSections.toggleVisibility', $section->id) }}"
                                           class="btn btn-xs {{ $section->is_visible ? 'btn-warning' : 'btn-info' }}">
                                            <i class="fa {{ $section->is_visible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            {{ $section->is_visible ? 'Hide' : 'Show' }}
                                        </a>

                                        <button type="button" class="btn btn-xs btn-primary"
                                                onclick='openEditSection(@json($section), @json($catIds))'>
                                            <i class="fa fa-edit"></i> Edit
                                        </button>

                                        <a href="{{ route('admin.homeSections.destroy', $section->id) }}"
                                           class="btn btn-xs btn-danger"
                                           onclick="return confirm('আপনি কি নিশ্চিত এই সেকশনটি মুছে ফেলতে চান?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">কোনো সেকশন পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add / Edit Section Form (4 cols) -->
        <div class="col-md-4">
            <!-- Add New Section Box -->
            <div class="box box-success" id="addSectionBox">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-circle text-success"></i> নতুন পণ্য সেকশন যোগ করুন</h3>
                </div>
                <form method="POST" action="{{ route('admin.homeSections.store') }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>সেকশনের নাম (বাংলা) <span class="text-danger">*</span></label>
                            <input type="text" name="title_bn" class="form-control" placeholder="যেমন: স্পেশাল ডিল / লোকাল পণ্য" required>
                        </div>

                        <div class="form-group">
                            <label>সেকশনের ধরন (Section Type)</label>
                            <select name="section_type" class="form-control" onchange="toggleCategorySelect(this.value, 'add_cat_box')">
                                <option value="category">ক্যাটাগরি ভিত্তিক পণ্য (Category Wise)</option>
                                <option value="featured">ফিচার পণ্য (Admin Featured)</option>
                                <option value="auto_cashback">অধিক ক্যাশব্যাকের পণ্য (Auto Highest Cashback)</option>
                                <option value="auto_point">অধিক পয়েন্টের পণ্য (Auto Highest Point)</option>
                                <option value="all">সকল পণ্য (All Products)</option>
                            </select>
                        </div>

                        <div class="form-group" id="add_cat_box">
                            <label>কোন কোন ক্যাটাগরির পণ্য এই সেকশনে শো করবে?</label>
                            <div style="max-height: 180px; overflow-y: auto; border: 1px solid #d2d6de; padding: 8px; border-radius: 4px; background: #f8fafc;">
                                @foreach($categories as $cat)
                                    <div class="checkbox" style="margin-top: 2px; margin-bottom: 4px;">
                                        <label>
                                            <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}">
                                            {{ $cat->name_bn ?: $cat->name_en }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>হোমপেজে পণ্য সংখ্যা</label>
                                    <input type="number" name="product_limit" class="form-control" value="4" min="1" max="50">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>সিরিয়াল (Order)</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ ($sections->max('sort_order') ?? 0) + 1 }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label>আইকন ক্লাস</label>
                                    <input type="text" name="icon" class="form-control" value="fas fa-bolt">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>আইকন রঙ</label>
                                    <input type="color" name="icon_color" class="form-control" value="#16a34a" style="height: 34px; padding: 2px;">
                                </div>
                            </div>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_visible" value="1" checked>
                                <strong>হোমপেজে প্রদর্শন করুন</strong>
                            </label>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> সেকশন যুক্ত করুন</button>
                    </div>
                </form>
            </div>

            <!-- Edit Section Box -->
            <div class="box box-warning" id="editSectionBox" style="display: none;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit text-warning"></i> পণ্য সেকশন সম্পাদনা</h3>
                    <button type="button" class="btn btn-default btn-xs pull-right" onclick="closeEditSection()"><i class="fa fa-times"></i></button>
                </div>
                <form id="editSectionForm" method="POST" action="">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>সেকশনের নাম (বাংলা) <span class="text-danger">*</span></label>
                            <input type="text" name="title_bn" id="edit_sec_title_bn" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>সেকশনের ধরন (Section Type)</label>
                            <select name="section_type" id="edit_sec_type" class="form-control" onchange="toggleCategorySelect(this.value, 'edit_cat_box')">
                                <option value="category">ক্যাটাগরি ভিত্তিক পণ্য (Category Wise)</option>
                                <option value="featured">ফিচার পণ্য (Admin Featured)</option>
                                <option value="auto_cashback">অধিক ক্যাশব্যাকের পণ্য (Auto Highest Cashback)</option>
                                <option value="auto_point">অধিক পয়েন্টের পণ্য (Auto Highest Point)</option>
                                <option value="all">সকল পণ্য (All Products)</option>
                            </select>
                        </div>

                        <div class="form-group" id="edit_cat_box">
                            <label>কোন কোন ক্যাটাগরির পণ্য এই সেকশনে শো করবে?</label>
                            <div style="max-height: 180px; overflow-y: auto; border: 1px solid #d2d6de; padding: 8px; border-radius: 4px; background: #f8fafc;">
                                @foreach($categories as $cat)
                                    <div class="checkbox" style="margin-top: 2px; margin-bottom: 4px;">
                                        <label>
                                            <input type="checkbox" name="category_ids[]" class="edit-cat-checkbox" value="{{ $cat->id }}">
                                            {{ $cat->name_bn ?: $cat->name_en }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>হোমপেজে পণ্য সংখ্যা</label>
                                    <input type="number" name="product_limit" id="edit_sec_limit" class="form-control" min="1" max="50">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>সিরিয়াল (Order)</label>
                                    <input type="number" name="sort_order" id="edit_sec_order" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label>আইকন ক্লাস</label>
                                    <input type="text" name="icon" id="edit_sec_icon" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>আইকন রঙ</label>
                                    <input type="color" name="icon_color" id="edit_sec_color" class="form-control" style="height: 34px; padding: 2px;">
                                </div>
                            </div>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_visible" id="edit_sec_visible" value="1">
                                <strong>হোমপেজে প্রদর্শন করুন</strong>
                            </label>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="button" class="btn btn-default" onclick="closeEditSection()">বাতিল</button>
                        <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> আপডেট করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-script')
<script>
    function toggleCategorySelect(type, boxId) {
        var box = document.getElementById(boxId);
        if (type === 'auto_cashback' || type === 'auto_point') {
            box.style.display = 'none';
        } else {
            box.style.display = 'block';
        }
    }

    function openEditSection(section, catIds) {
        document.getElementById('editSectionForm').action = "{{ url('private-panel/home-sections/update') }}/" + section.id;
        document.getElementById('edit_sec_title_bn').value = section.title_bn || '';
        document.getElementById('edit_sec_type').value = section.section_type || 'category';
        document.getElementById('edit_sec_limit').value = section.product_limit || 4;
        document.getElementById('edit_sec_order').value = section.sort_order || 1;
        document.getElementById('edit_sec_icon').value = section.icon || 'fas fa-bolt';
        document.getElementById('edit_sec_color').value = section.icon_color || '#16a34a';
        document.getElementById('edit_sec_visible').checked = !!section.is_visible;

        var checkboxes = document.querySelectorAll('.edit-cat-checkbox');
        checkboxes.forEach(function(cb) {
            cb.checked = catIds.indexOf(parseInt(cb.value)) !== -1;
        });

        toggleCategorySelect(section.section_type, 'edit_cat_box');

        document.getElementById('addSectionBox').style.display = 'none';
        document.getElementById('editSectionBox').style.display = 'block';
        window.scrollTo({ top: 120, behavior: 'smooth' });
    }

    function closeEditSection() {
        document.getElementById('editSectionBox').style.display = 'none';
        document.getElementById('addSectionBox').style.display = 'block';
    }
</script>
@endsection

