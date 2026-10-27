@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        অনলাইন ক্যাটাগরি ব্যবস্থাপনা (Online Categories)
        <small>কাস্টমার হোম পেইজের ক্যাটাগরি আইকন ও ক্রম নিয়ন্ত্রণ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li class="active">অনলাইন ক্যাটাগরি</li>
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
        <!-- Categories Table (8 columns) -->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-th-large text-primary"></i> ক্যাটাগরি তালিকা (মোট: {{ $categories->count() }})</h3>
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 60px;">ক্রমিক</th>
                                <th style="width: 70px;">আইকন</th>
                                <th>ক্যাটাগরির নাম</th>
                                <th style="width: 80px;">অর্ডার</th>
                                <th style="width: 100px;">স্ট্যাটাস</th>
                                <th style="width: 150px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr style="{{ $cat->is_visible ? '' : 'background-color: #fcf8e3; opacity: 0.8;' }}">
                                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <div style="width: 40px; height: 40px; border-radius: 8px; background: #f0fdf4; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; border: 1px solid #bbf7d0;">
                                            <i class="{{ $cat->icon ?: 'fas fa-th' }}"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="font-size: 14px;">{{ $cat->name }}</strong>
                                        <br><small class="text-muted">Class: {{ $cat->icon }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-navy">{{ $cat->sort_order }}</span>
                                    </td>
                                    <td>
                                        @if($cat->is_visible)
                                            <span class="label label-success"><i class="fa fa-check"></i> দৃশ্যমান</span>
                                        @else
                                            <span class="label label-default"><i class="fa fa-eye-slash"></i> লুকানো (Hidden)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.onlineCategories.toggleHide', $cat->id) }}" 
                                           class="btn btn-xs {{ $cat->is_visible ? 'btn-warning' : 'btn-info' }}"
                                           title="{{ $cat->is_visible ? 'হাইড করুন' : 'শো করুন' }}">
                                            <i class="fa {{ $cat->is_visible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            {{ $cat->is_visible ? 'Hide' : 'Show' }}
                                        </a>

                                        <button type="button" 
                                                class="btn btn-xs btn-primary" 
                                                onclick="editCategory('{{ $cat->id }}', '{{ addslashes($cat->name) }}', '{{ $cat->icon }}', '{{ $cat->sort_order }}', '{{ $cat->is_visible }}')">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>

                                        <a href="{{ route('admin.onlineCategories.destroy', $cat->id) }}" 
                                           class="btn btn-xs btn-danger" 
                                           onclick="return confirm('আপনি কি নিশ্চিত এই ক্যাটাগরিটি মুছে ফেলতে চান?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">কোনো ক্যাটাগরি পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add / Edit Form (4 columns) -->
        <div class="col-md-4">
            <!-- Add New Form -->
            <div class="box box-success" id="addCategoryBox">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-circle text-success"></i> নতুন ক্যাটাগরি যুক্ত করুন</h3>
                </div>
                <form method="POST" action="{{ route('admin.onlineCategories.store') }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>ক্যাটাগরির নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: গ্রোসারি" required>
                        </div>

                        <div class="form-group">
                            <label>FontAwesome আইকন ক্লাস</label>
                            <input type="text" name="icon" class="form-control" placeholder="যেমন: fas fa-shopping-basket" value="fas fa-shopping-basket">
                            <small class="text-muted">FontAwesome আইকন কোড (উদাঃ fas fa-fish, fas fa-carrot)</small>
                        </div>

                        <div class="form-group">
                            <label>প্রদর্শন ক্রম (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ ($categories->max('sort_order') ?? 0) + 1 }}">
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_visible" value="1" checked> 
                                <strong>হোম পেইজে প্রদর্শন করুন</strong>
                            </label>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>

            <!-- Edit Form (Hidden by default, shown when clicking edit) -->
            <div class="box box-warning" id="editCategoryBox" style="display: none;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit text-warning"></i> ক্যাটাগরি সম্পাদনা</h3>
                    <button type="button" class="btn btn-default btn-xs pull-right" onclick="cancelEdit()"><i class="fa fa-times"></i></button>
                </div>
                <form id="editCategoryForm" method="POST" action="">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>ক্যাটাগরির নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>FontAwesome আইকন ক্লাস</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>প্রদর্শন ক্রম (Sort Order)</label>
                            <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_visible" id="edit_is_visible" value="1"> 
                                <strong>হোম পেইজে প্রদর্শন করুন</strong>
                            </label>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="button" class="btn btn-default" onclick="cancelEdit()">বাতিল</button>
                        <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> আপডেট করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@section('extra-script')
<script>
    function editCategory(id, name, icon, sort_order, is_visible) {
        var updateUrl = "{{ url('private-panel/online-categories/update') }}/" + id;
        document.getElementById('editCategoryForm').action = updateUrl;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_sort_order').value = sort_order;
        document.getElementById('edit_is_visible').checked = (is_visible == 1 || is_visible == '1');

        document.getElementById('addCategoryBox').style.display = 'none';
        document.getElementById('editCategoryBox').style.display = 'block';
        window.scrollTo({ top: 150, behavior: 'smooth' });
    }

    function cancelEdit() {
        document.getElementById('editCategoryBox').style.display = 'none';
        document.getElementById('addCategoryBox').style.display = 'block';
    }
</script>
@endsection
@endsection
