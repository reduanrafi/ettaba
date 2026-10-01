@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product History: {{ $product->name_en }}</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('product.index') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-arrow-left"></i> Back to Products</a>
                        </div>
                    </div>
                    
                    <div class="box-body table table-bordered table-hover table-responsive no-padding ">
                        <table class="table table-bordered table-hover table-responsive no-padding">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Seller Rate</th>
                                    <th>MRP</th>
                                    <th>ERP</th>
                                    <th>Cashback</th>
                                    <th>Refer Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($histories->count() > 0)
                                    @foreach($histories as $history)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i A', strtotime($history->created_at)) }}</td>
                                            <td>{{ $history->old_name }}</td>
                                            <td>{{ $history->old_rate }}</td>
                                            <td>{{ $history->old_mrp }}</td>
                                            <td>{{ $history->old_erp }}</td>
                                            <td>{{ $history->old_cb }}</td>
                                            <td>{{ $history->old_direct_refer_commission }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No history available for this product.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
