@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                    <div class="box-header with-border" style="padding: 15px;">
                        <h3 class="box-title" style="font-weight: 700;">
                            <i class="fa fa-history text-info"></i> Product Edit History: <span class="text-primary">{{ $product->name_en }}</span>
                        </h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-arrow-left"></i> Back to Products</a>
                        </div>
                    </div>
                    
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered table-striped table-hover text-nowrap" style="margin-bottom: 0;">
                            <thead style="background-color: #f4f6f9;">
                                <tr>
                                    <th>#</th>
                                    <th>Date & Time</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Seller Rate</th>
                                    <th>MRP</th>
                                    <th>ERP</th>
                                    <th>VAT</th>
                                    <th>Cashback</th>
                                    <th>Refer Comm.</th>
                                    <th>TCB</th>
                                    <th>TRP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($histories->count() > 0)
                                    @foreach($histories as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="text-muted"><i class="fa fa-calendar"></i> {{ date('d M Y, h:i A', strtotime($history->created_at)) }}</span></td>
                                            <td><strong>{{ $history->old_name }}</strong></td>
                                            <td style="max-width: 220px; white-space: normal;">
                                                @if(!empty($history->old_description))
                                                    <span title="{{ strip_tags($history->old_description) }}">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($history->old_description), 40) }}
                                                    </span>
                                                    <button type="button" class="btn btn-xs btn-default text-primary" data-toggle="modal" data-target="#descModal{{ $history->id }}">
                                                        <i class="fa fa-eye"></i> View
                                                    </button>
                                                    
                                                    <!-- Modal for full description -->
                                                    <div class="modal fade" id="descModal{{ $history->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content" style="border-radius: 8px;">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title font-weight-bold">Historical Description ({{ date('d M Y', strtotime($history->created_at)) }})</h4>
                                                                </div>
                                                                <div class="modal-body" style="white-space: pre-wrap; font-size: 14px; line-height: 1.6; max-height: 400px; overflow-y: auto;">
                                                                    {{ strip_tags($history->old_description) }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td><span class="label label-primary" style="font-size: 12px;">{{ $history->old_quantity ?? '-' }}</span></td>
                                            <td>৳ {{ number_format(floatval($history->old_rate), 2) }}</td>
                                            <td>৳ {{ number_format(floatval($history->old_mrp), 2) }}</td>
                                            <td><strong>৳ {{ number_format(floatval($history->old_erp), 2) }}</strong></td>
                                            <td>৳ {{ number_format(floatval($history->old_vat), 2) }}</td>
                                            <td>৳ {{ number_format(floatval($history->old_cb), 2) }}</td>
                                            <td>৳ {{ number_format(floatval($history->old_direct_refer_commission), 2) }}</td>
                                            <td>৳ {{ number_format(floatval($history->old_tcb), 2) }}</td>
                                            <td>৳ {{ number_format(floatval($history->old_trp), 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="13" class="text-center" style="padding: 30px; color: #888;">
                                            <i class="fa fa-info-circle fa-2x"></i><br>
                                            No edit history recorded for this product yet.
                                        </td>
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
