@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        পাবলিক নোটিশ ও নোটিফিকেশন (Public Notices & Notifications)
        <small>লগইন করা ও লগইন না করা—সকল ভিজিটরের জন্য পাবলিক নোটিশ তৈরি, সম্পাদনা ও প্রকাশ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.admin') }}"><i class="fa fa-dashboard"></i> ড্যাশবোর্ড</a></li>
        <li class="active">পাবলিক নোটিশ</li>
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
        <!-- Notices List (7 cols) -->
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bell text-primary"></i> প্রকাশিত ও খসড়া নোটিশ তালিকা (মোট: {{ $notices->count() }} টি)</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>নোটিশের শিরোনাম ও সংক্ষিপ্ত বিবরণ</th>
                                <th style="width: 110px;">প্রকাশের সময়</th>
                                <th style="width: 90px;">স্ট্যাটাস</th>
                                <th style="width: 150px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notices as $notice)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong style="font-size: 14px; color: #0f172a;">{{ $notice->title }}</strong>
                                        @if($notice->summary)
                                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit($notice->summary, 90) }}</div>
                                        @else
                                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($notice->content), 90) }}</div>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $notice->published_at ? $notice->published_at->format('d M Y, h:i A') : $notice->created_at->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        @if($notice->is_published)
                                            <span class="label label-success">প্রকাশিত</span>
                                        @else
                                            <span class="label label-default">অপ্রকাশিত</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.notices.togglePublish', $notice->id) }}"
                                           class="btn btn-xs {{ $notice->is_published ? 'btn-warning' : 'btn-info' }}">
                                            <i class="fa {{ $notice->is_published ? 'fa-eye-slash' : 'fa-bullhorn' }}"></i>
                                            {{ $notice->is_published ? 'Unpublish' : 'Publish' }}
                                        </a>

                                        <button type="button" class="btn btn-xs btn-primary"
                                                onclick='openEditNotice(@json($notice))'>
                                            <i class="fa fa-edit"></i> Edit
                                        </button>

                                        <a href="{{ route('admin.notices.destroy', $notice->id) }}"
                                           class="btn btn-xs btn-danger"
                                           onclick="return confirm('আপনি কি নিশ্চিত এই নোটিশটি মুছে ফেলতে চান?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">কোনো পাবলিক নোটিশ পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create / Edit Notice Form (5 cols) -->
        <div class="col-md-5">
            <!-- Create Form -->
            <div class="box box-success" id="addNoticeBox">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-circle text-success"></i> নতুন নোটিশ তৈরি ও প্রকাশ করুন</h3>
                </div>
                <form method="POST" action="{{ route('admin.notices.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>নোটিশের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: ঈদুল আযহা উপলক্ষে বিশেষ ক্যাশব্যাক অফার!" required>
                        </div>

                        <div class="form-group">
                            <label>সংক্ষিপ্ত সারসংক্ষেপ (তালিকায় দেখানোর জন্য - ঐচ্ছিক)</label>
                            <input type="text" name="summary" class="form-control" placeholder="সংক্ষেপে এক লাইনে বিষয়বস্তু">
                        </div>

                        <div class="form-group">
                            <label>বিস্তারিত বিবরণ <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="6" placeholder="নোটিশের সম্পূর্ণ বিস্তারিত এখানে লিখুন..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label>নোটিশের ছবি / ব্যানার (ঐচ্ছিক)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_published" value="1" checked>
                                <strong>এখনই ওয়েবসাইটে প্রকাশ করুন (স্বয়ংক্রিয়ভাবে নোটিফিকেশন কাউন্ট আপডেট হবে)</strong>
                            </label>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success"><i class="fa fa-bullhorn"></i> নোটিশ প্রকাশ করুন</button>
                    </div>
                </form>
            </div>

            <!-- Edit Form -->
            <div class="box box-warning" id="editNoticeBox" style="display: none;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit text-warning"></i> নোটিশ সম্পাদনা করুন</h3>
                    <button type="button" class="btn btn-default btn-xs pull-right" onclick="closeEditNotice()"><i class="fa fa-times"></i></button>
                </div>
                <form id="editNoticeForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>নোটিশের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_notice_title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>সংক্ষিপ্ত সারসংক্ষেপ</label>
                            <input type="text" name="summary" id="edit_notice_summary" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>বিস্তারিত বিবরণ <span class="text-danger">*</span></label>
                            <textarea name="content" id="edit_notice_content" class="form-control" rows="6" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>নতুন ছবি আপলোড (ঐচ্ছিক)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_published" id="edit_notice_published" value="1">
                                <strong>ওয়েবসাইটে প্রকাশিত রাখুন</strong>
                            </label>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="button" class="btn btn-default" onclick="closeEditNotice()">বাতিল</button>
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
    function openEditNotice(notice) {
        document.getElementById('editNoticeForm').action = "{{ url('private-panel/notices/update') }}/" + notice.id;
        document.getElementById('edit_notice_title').value = notice.title || '';
        document.getElementById('edit_notice_summary').value = notice.summary || '';
        document.getElementById('edit_notice_content').value = notice.content || '';
        document.getElementById('edit_notice_published').checked = !!notice.is_published;

        document.getElementById('addNoticeBox').style.display = 'none';
        document.getElementById('editNoticeBox').style.display = 'block';
        window.scrollTo({ top: 120, behavior: 'smooth' });
    }

    function closeEditNotice() {
        document.getElementById('editNoticeBox').style.display = 'none';
        document.getElementById('addNoticeBox').style.display = 'block';
    }
</script>
@endsection

