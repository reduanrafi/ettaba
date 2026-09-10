@extends('admin.layouts.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 700; color: #333;">Add New Direct Selling Product</h3>
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

                    <form action="{{ route('direct-seller.product.store') }}" method="POST" class="form-horizontal">
                        @csrf
                        <div class="box-body" style="padding: 25px;">
                            <!-- Name EN -->
                            <div class="form-group">
                                <label for="name_en" class="col-sm-3 control-label">Product Name (English) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="name_en" class="form-control" id="name_en" placeholder="e.g. Premium Basmati Rice" value="{{ old('name_en') }}" required>
                                </div>
                            </div>

                            <!-- Name BN -->
                            <div class="form-group">
                                <label for="name_bn" class="col-sm-3 control-label">Product Name (Bangla) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="name_bn" class="form-control" id="name_bn" placeholder="যেমন: প্রিমিয়াম বাসমতি চাল" value="{{ old('name_bn') }}" required>
                                </div>
                            </div>

                            <!-- Company Rate -->
                            <div class="form-group">
                                <label for="company_rate" class="col-sm-3 control-label">Company Rate (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="company_rate" class="form-control" id="company_rate" placeholder="Wholesale price paid to company" value="{{ old('company_rate') }}" required>
                                </div>
                            </div>

                            <!-- Seller Rate -->
                            <div class="form-group">
                                <label for="seller_rate" class="col-sm-3 control-label">Seller Rate (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="seller_rate" class="form-control" id="seller_rate" placeholder="Price sold to customer (Must be >= Company Rate)" value="{{ old('seller_rate') }}" required>
                                </div>
                            </div>

                            <!-- ERP -->
                            <div class="form-group">
                                <label for="erp" class="col-sm-3 control-label">ERP / MRP (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="erp" class="form-control" id="erp" placeholder="Standard retail price" value="{{ old('erp') }}" required>
                                </div>
                            </div>

                            <!-- Refer Commission -->
                            <div class="form-group">
                                <label for="refer_commission" class="col-sm-3 control-label">Refer Commission (৳) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" name="refer_commission" class="form-control" id="refer_commission" placeholder="Commission paid to placement eID" value="{{ old('refer_commission') }}" required>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="qty" class="col-sm-3 control-label">Quantity <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="qty" class="form-control" id="qty" placeholder="Inventory amount" value="{{ old('qty') }}" required>
                                </div>
                            </div>

                            <hr>

                            <div class="alert alert-warning" style="border-radius: 8px;">
                                <h4><i class="icon fa fa-calculator"></i> Automatic Calculations:</h4>
                                <p>By saving this product, the system will automatically calculate the following properties:</p>
                                <ul>
                                    <li><strong>VAT (5%):</strong> Paid on the Company Rate.</li>
                                    <li><strong>Reward Points (TRP equivalent):</strong> Calculated as `(Company Rate - Refer Commission - VAT) / 25`.</li>
                                    <li><strong>Total Cashback (TCB):</strong> Instantly credited to the customer as `2 * Reward Points`.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="box-footer text-right" style="background-color: #fcfcfc;">
                            <button type="reset" class="btn btn-default btn-flat">Reset</button>
                            <button type="submit" class="btn btn-info btn-flat">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
