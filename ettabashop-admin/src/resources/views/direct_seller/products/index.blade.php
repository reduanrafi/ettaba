@extends('admin.layouts.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 700; color: #333;">My Products</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('direct-seller.product.create') }}" class="btn btn-primary btn-flat pull-right btn-sm">
                                <i class="fa fa-plus"></i> Add Product
                            </a>
                        </div>
                    </div>

                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible" style="margin: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible" style="margin: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <div class="box-body table-responsive">
                        <table id="directProductDataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr style="background-color: #f4f4f4;">
                                    <th>ID</th>
                                    <th>Name (English)</th>
                                    <th>Name (Bangla)</th>
                                    <th>Company Rate</th>
                                    <th>Seller Rate</th>
                                    <th>ERP</th>
                                    <th>Refer Commission</th>
                                    <th>Qty Available</th>
                                    <th>VAT (5%)</th>
                                    <th>Points</th>
                                    <th>Cashback (TCB)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td><strong>{{ $product->name_en }}</strong></td>
                                        <td>{{ $product->name_bn }}</td>
                                        <td>৳{{ number_format($product->company_rate, 2) }}</td>
                                        <td>৳{{ number_format($product->seller_rate, 2) }}</td>
                                        <td>৳{{ number_format($product->erp, 2) }}</td>
                                        <td>৳{{ number_format($product->refer_commission, 2) }}</td>
                                        <td>
                                            @if($product->qty <= 5)
                                                <span class="label label-danger">{{ $product->qty }}</span>
                                            @else
                                                <span class="label label-success">{{ $product->qty }}</span>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($product->vat, 2) }}</td>
                                        <td><span class="badge bg-purple">{{ number_format($product->reward_points, 2) }}</span></td>
                                        <td><span class="badge bg-olive">৳{{ number_format($product->tcb, 2) }}</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('direct-seller.product.edit', $product->id) }}" class="btn btn-xs btn-primary btn-flat">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('direct-seller.product.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger btn-flat">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-script')
    <script>
        $(function () {
            $('#directProductDataTable').DataTable({
                responsive: true,
                "order": [[0, "desc"]],
                stateSave: true
            });
        });
    </script>
@endsection
