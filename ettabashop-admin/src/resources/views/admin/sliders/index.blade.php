@extends('admin.layouts.layout')
@section('content')
    <section class="content-header">
        <h1>
            হোমপেজ ব্যানার ও অফার স্লাইডার (Promotional Banners)
            <small>ব্যানারের ছবি, লেখা, বাটন, লিংক, অফার বিস্তারিত এবং প্রদর্শনের ক্রম নিয়ন্ত্রণ</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-image text-primary"></i> ব্যানার তালিকা</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('slider.create') }}" class="btn btn-success btn-flat btn-sm">
                                <i class="fa fa-plus"></i> নতুন ব্যানার যোগ করুন
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
                                <th style="width: 70px;">ক্রম</th>
                                <th style="width: 150px;">ব্যানার ছবি</th>
                                <th>শিরোনাম ও বিবরণ</th>
                                <th>বাটন ও লিংক</th>
                                <th style="width: 100px;">স্ট্যাটাস</th>
                                <th style="width: 150px;" class="text-center">অ্যাকশন</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($sliders)
                                @foreach($sliders->sortBy('sort_order') as $slider)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-navy">{{ $slider->sort_order ?? $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            @if(!empty($slider->image))
                                                <img src="{{ asset($slider->image) }}" style="width: 140px; height: 65px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $slider->title_bn ?: $slider->title_en ?: '(শুধু গ্রাফিক ব্যানার)' }}</strong>
                                            @if($slider->description_bn || $slider->description_en)
                                                <div class="text-muted small">{{ $slider->description_bn ?: $slider->description_en }}</div>
                                            @endif
                                            @if(!empty($slider->offer_details))
                                                <span class="label label-info" style="font-size: 10px;"><i class="fa fa-file-text-o"></i> অফার বিস্তারিত যুক্ত আছে</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div><strong>বাটন:</strong> {{ $slider->button_text ?: 'এখনই কেনাকাটা করুন' }}</div>
                                            <div class="small text-muted"><strong>লিংক:</strong> {{ $slider->button_link ?: 'অফার ডিটেইলস পেজ' }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if(!isset($slider->is_active) || $slider->is_active)
                                                <span class="label label-success">সক্রিয়</span>
                                            @else
                                                <span class="label label-default">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('slider.edit', ['slider' => $slider->id]) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('slider.destroy', ['slider' => $slider->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('আপনি কি নিশ্চিত এই ব্যানারটি মুছে ফেলতে চান?');">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
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
