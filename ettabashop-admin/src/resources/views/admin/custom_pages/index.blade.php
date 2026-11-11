@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-file-text-o text-primary"></i> কাস্টম পেজ ও পলিসি ম্যানেজার (Page Builder)
        <a href="{{ route('admin.customPages.create') }}" class="btn btn-sm btn-success pull-right">
            <i class="fa fa-plus"></i> নতুন পেজ তৈরি করুন
        </a>
    </h1>
</section>

<section class="content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="box box-primary">
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>আইডি</th>
                        <th>শিরোনাম (Title)</th>
                        <th>কাস্টম URL (Slug)</th>
                        <th>স্ট্যাটাস</th>
                        <th>তৈরির তারিখ</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td><strong>{{ $page->title }}</strong></td>
                            <td>
                                <code>/page/{{ $page->slug }}</code>
                            </td>
                            <td>
                                @if($page->is_published)
                                    <span class="label label-success">প্রকাশিত (Published)</span>
                                @else
                                    <span class="label label-default">ড্রাফট</span>
                                @endif
                            </td>
                            <td>{{ $page->created_at ? $page->created_at->format('d M Y') : '' }}</td>
                            <td>
                                <a href="{{ route('admin.customPages.edit', $page->id) }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-edit"></i> এডিট
                                </a>
                                <a href="{{ route('admin.customPages.destroy', $page->id) }}" class="btn btn-xs btn-danger"
                                   onclick="return confirm('আপনি কি নিশ্চিত যে এই পেজটি মুছে ফেলতে চান?');">
                                    <i class="fa fa-trash"></i> ডিলিট
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 25px;">
                                কোনো কাস্টম পেজ পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

