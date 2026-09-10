@extends('admin.layouts.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 700; color: #333;">Edit Product: {{ $product->name_en }}</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('direct-seller.product.index') }}" class="btn btn-default btn-flat btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" style="margin: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Validation Error!</h4>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('direct-seller.product.update', $product->id) }}" method="POST" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="box-body" style="padding: 25px;">
                            <!-- Name EN -->
                            <div class="form-group">
                                <label for="name_en" class="col-sm-3 control-label">Product Name (English) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="name_en" class="form-control" id="name_en" placeholder="e.g. Premium Basmati Rice" value="{{ old('name_en', $product->name_en) }}" required>
                                </div>
                            </div>

                            <!-- Name BN -->
                            <div class="form-group">
                                <label for="name_bn" class="col-sm-3 control-label">Product Name (Bangla) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="name_bn" class="form-control" id="name_bn" placeholder="যেমন: প্রিমিয়াম বাসমতি চাল" value="{{ old('name_bn', $product->name_bn) }}" required>
                                </div>
                            </div>

                            <!-- Company Rate -->
                            <div class="form-group">
                                <label for="company_rate" class="col-sm-3 control-label">Company Rate (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="company_rate" class="form-control" id="company_rate" placeholder="Wholesale price paid to company" value="{{ old('company_rate', $product->company_rate) }}" required>
                                </div>
                            </div>

                            <!-- Seller Rate -->
                            <div class="form-group">
                                <label for="seller_rate" class="col-sm-3 control-label">Seller Rate (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="seller_rate" class="form-control" id="seller_rate" placeholder="Price sold to customer (Must be >= Company Rate)" value="{{ old('seller_rate', $product->seller_rate) }}" required>
                                </div>
                            </div>

                            <!-- ERP -->
                            <div class="form-group">
                                <label for="erp" class="col-sm-3 control-label">ERP / MRP (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="erp" class="form-control" id="erp" placeholder="Standard retail price" value="{{ old('erp', $product->erp) }}" required>
                                </div>
                            </div>

                            <!-- Refer Commission -->
                            <div class="form-group">
                                <label for="refer_commission" class="col-sm-3 control-label">Refer Commission (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="refer_commission" class="form-control" id="refer_commission" placeholder="Commission paid to placement eID" value="{{ old('refer_commission', $product->refer_commission) }}" required>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="qty" class="col-sm-3 control-label">Quantity <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="qty" class="form-control" id="qty" placeholder="Inventory amount" value="{{ old('qty', $product->qty) }}" required>
                                </div>
                            </div>

                            <hr>

                            <div class="alert alert-warning" style="border-radius: 8px;">
                                <h4><i class="icon fa fa-calculator"></i> Current Calculations:</h4>
                                <ul>
                                    <li><strong>Calculated VAT (5%):</strong> ৳{{ number_format($product->vat, 2) }}</li>
                                    <li><strong>Calculated Reward Points:</strong> {{ number_format($product->reward_points, 2) }}</li>
                                    <li><strong>Calculated Cashback (TCB):</strong> ৳{{ number_format($product->tcb, 2) }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="box-footer text-right" style="background-color: #fcfcfc;">
                            <button type="reset" class="btn btn-default btn-flat">Reset</button>
                            <button type="submit" class="btn btn-info btn-flat">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
