@extends('admin.layouts.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 700; color: #333;">Confirm Direct Sale</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('direct-seller.order.index') }}" class="btn btn-default btn-flat btn-sm">
                                <i class="fa fa-list"></i> Sales History
                            </a>
                        </div>
                    </div>

                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible" style="margin: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                            {{ Session::get('error') }}
                        </div>
                    @endif

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

                    <!-- Current Balance Info Box -->
                    <div class="box-body" style="padding-top: 15px; padding-bottom: 0;">
                        <div class="callout callout-info" style="border-radius: 8px; margin-bottom: 10px;">
                            <h4>Current e-Balance (Direct Selling)</h4>
                            <p style="font-size: 20px; font-weight: 700;">৳ {{ number_format(Auth::user()->direct_selling_balance, 2) }}</p>
                        </div>
                    </div>

                    <form action="{{ route('direct-seller.order.store') }}" method="POST" class="form-horizontal" id="confirmSaleForm">
                        @csrf
                        <div class="box-body" style="padding: 25px;">
                            
                            <!-- Step 1: Customer Search -->
                            <div class="form-group">
                                <label for="customer_search" class="col-sm-3 control-label">Search Customer <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="customer_search" placeholder="Enter registered phone number or eID (unique ID)" value="{{ old('customer_phone') }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-warning btn-flat" type="button" id="btnVerifyCustomer">
                                                <i class="fa fa-search"></i> Verify
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block text-muted" style="margin-top: 5px;">Verify the customer to automatically fill details and distribute points.</span>
                                    <div id="searchResponse" style="margin-top: 10px; display: none;"></div>
                                </div>
                            </div>

                            <!-- Customer Phone (hidden field or populated by search) -->
                            <input type="hidden" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}">

                            <!-- Customer Name -->
                            <div class="form-group">
                                <label for="customer_name" class="col-sm-3 control-label">Customer Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="customer_name" class="form-control" id="customer_name" placeholder="Customer Name" value="{{ old('customer_name') }}">
                                </div>
                            </div>

                            <!-- Customer Address -->
                            <div class="form-group">
                                <label for="customer_address" class="col-sm-3 control-label">Customer Address</label>
                                <div class="col-sm-9">
                                    <textarea name="customer_address" class="form-control" id="customer_address" rows="3" placeholder="Customer Delivery Address">{{ old('customer_address') }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <!-- Product Selection -->
                            <div class="form-group">
                                <label for="product_id" class="col-sm-3 control-label">Product <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="product_id" id="product_id" required>
                                        <option value="">Select a Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-qty="{{ $product->qty }}" data-erp="{{ $product->erp }}" data-company-rate="{{ $product->company_rate }}">
                                                {{ $product->name_en }} ({{ $product->name_bn }}) - ERP: ৳{{ $product->erp }} | Available Qty: {{ $product->qty }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="qty" class="col-sm-3 control-label">Quantity <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="qty" class="form-control" id="qty" placeholder="Quantity" value="{{ old('qty', 1) }}" min="1" required>
                                    <span class="help-block text-danger" id="qtyError" style="display: none;">Quantity exceeds available stock.</span>
                                </div>
                            </div>

                            <!-- Total Summary Box -->
                            <div class="form-group" id="summaryGroup" style="display: none;">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class="well well-sm" style="border-radius: 8px; margin-bottom: 0; background-color: #fcfcfc;">
                                        <h5 style="margin-top: 0; font-weight: bold; color: #333;">Order Calculation Summary:</h5>
                                        <table class="table table-condensed" style="margin-bottom: 0;">
                                            <tr>
                                                <td>Unit ERP:</td>
                                                <td class="text-right" id="lblUnitERP">৳0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Order Qty:</td>
                                                <td class="text-right" id="lblQty">1</td>
                                            </tr>
                                            <tr style="font-weight: 700; font-size: 15px; border-top: 2px solid #ddd;">
                                                <td>Total ERP (Balance Deducted):</td>
                                                <td class="text-right text-red" id="lblTotalERP">৳0.00</td>
                                            </tr>
                                            <tr style="font-weight: 600; color: #00a65a;">
                                                <td>Expected Retail Profit (Refunded to Earnings):</td>
                                                <td class="text-right" id="lblExpectedProfit">৳0.00</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer text-right" style="background-color: #fcfcfc;">
                            <button type="reset" class="btn btn-default btn-flat">Reset</button>
                            <button type="submit" class="btn btn-info btn-flat" id="btnSubmitOrder" disabled>Confirm Sale</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-script')
    <script>
        $(document).ready(function () {
            
            // Verify Customer Action
            $('#btnVerifyCustomer').on('click', function () {
                var searchVal = $('#customer_search').val().trim();
                if (searchVal === '') {
                    alert('Please enter a phone number or eID to search.');
                    return;
                }

                $('#searchResponse').removeClass().addClass('alert alert-info').html('<i class="fa fa-spinner fa-spin"></i> Searching...').show();
                $('#customer_phone').val('');
                $('#customer_name').val('');
                $('#customer_address').val('');

                $.ajax({
                    url: '{{ route("direct-seller.order.search_customer") }}',
                    type: 'GET',
                    data: { search: searchVal },
                    success: function (res) {
                        if (res.status === 'success') {
                            $('#searchResponse').removeClass().addClass('alert alert-success').html('<i class="icon fa fa-check"></i> ' + res.message);
                            $('#customer_phone').val(res.phone);
                            $('#customer_name').val(res.name);
                            $('#customer_address').val(res.address);
                            $('#btnSubmitOrder').prop('disabled', false);
                        } else {
                            $('#searchResponse').removeClass().addClass('alert alert-warning').html('<i class="icon fa fa-warning"></i> ' + res.message);
                            // Set customer phone as search value if it looks like a phone number to allow checkout for unregistered customers
                            $('#customer_phone').val(searchVal);
                            $('#btnSubmitOrder').prop('disabled', false);
                        }
                    },
                    error: function () {
                        $('#searchResponse').removeClass().addClass('alert alert-danger').html('<i class="icon fa fa-ban"></i> Connection error. Try again.');
                    }
                });
            });

            // Calculate Order Summary
            function calculateSummary() {
                var selected = $('#product_id option:selected');
                var qty = parseInt($('#qty').val());

                if (selected.val() === '' || isNaN(qty) || qty <= 0) {
                    $('#summaryGroup').hide();
                    return;
                }

                var erp = parseFloat(selected.data('erp'));
                var companyRate = parseFloat(selected.data('company-rate'));
                var stock = parseInt(selected.data('qty'));

                // Validate quantity
                if (qty > stock) {
                    $('#qtyError').show();
                    $('#btnSubmitOrder').prop('disabled', true);
                } else {
                    $('#qtyError').hide();
                    if ($('#customer_phone').val() !== '') {
                        $('#btnSubmitOrder').prop('disabled', false);
                    }
                }

                var totalErp = erp * qty;
                var totalCompanyRate = companyRate * qty;
                var retailProfit = totalErp - totalCompanyRate;

                $('#lblUnitERP').text('৳' + erp.toFixed(2));
                $('#lblQty').text(qty);
                $('#lblTotalERP').text('৳' + totalErp.toFixed(2));
                $('#lblExpectedProfit').text('৳' + retailProfit.toFixed(2));
                $('#summaryGroup').show();
            }

            $('#product_id').on('change', calculateSummary);
            $('#qty').on('input', calculateSummary);

            // Double check validation before submit
            $('#confirmSaleForm').on('submit', function (e) {
                var phone = $('#customer_phone').val();
                if (phone === '') {
                    e.preventDefault();
                    alert('Please enter and verify the Customer Phone number first.');
                    return false;
                }

                var selected = $('#product_id option:selected');
                var qty = parseInt($('#qty').val());
                var stock = parseInt(selected.data('qty'));
                if (qty > stock) {
                    e.preventDefault();
                    alert('Quantity exceeds available stock.');
                    return false;
                }
            });
        });
    </script>
@endsection
