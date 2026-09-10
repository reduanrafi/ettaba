@extends('admin.layouts.layout')

@section('extra-style')
<style>
    /* Premium Aesthetic Styling */
    .add-money-container {
        padding: 20px;
        background: #f4f6f9;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .premium-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
        margin-bottom: 25px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .premium-card:hover {
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.12);
    }

    .premium-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        padding: 18px 24px;
        font-weight: 600;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: none;
    }

    .premium-card-body {
        padding: 25px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Preset Amount Buttons */
    .amount-btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 25px;
    }

    .amount-btn {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        color: #334155;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        min-width: 90px;
        text-align: center;
    }

    .amount-btn:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        transform: translateY(-2px);
    }

    .amount-btn:active {
        transform: translateY(0);
    }

    /* Gateway Selection Cards */
    .gateway-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .gateway-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        text-align: center;
    }

    .gateway-card:hover {
        border-color: #93c5fd;
        transform: translateY(-3px);
    }

    .gateway-card.selected {
        border-color: #3b82f6;
        background: #eff6ff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);
    }

    .gateway-card.selected::after {
        content: "\f00c";
        font-family: "FontAwesome";
        position: absolute;
        top: 10px;
        right: 12px;
        color: #3b82f6;
        font-size: 16px;
    }

    .gateway-icon {
        font-size: 28px;
        color: #475569;
        margin-bottom: 10px;
    }

    .gateway-card.selected .gateway-icon {
        color: #3b82f6;
    }

    .gateway-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .gateway-subtitle {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 8px;
        min-height: 16px;
    }

    .gateway-charge-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .gateway-card.selected .gateway-charge-badge {
        background: #3b82f6;
        color: #ffffff;
    }

    /* Help Icon & Custom Tooltip */
    .help-icon-wrapper {
        position: relative;
        display: inline-block;
        cursor: help;
        margin-left: 8px;
    }

    .help-icon {
        color: #94a3b8;
        font-size: 16px;
        transition: color 0.2s ease;
    }

    .help-icon-wrapper:hover .help-icon {
        color: #ef4444;
    }

    .custom-tooltip {
        visibility: hidden;
        width: 320px;
        background-color: #1e293b;
        color: #f8fafc;
        text-align: left;
        border-radius: 8px;
        padding: 14px;
        position: absolute;
        z-index: 100;
        bottom: 125%;
        left: 50%;
        margin-left: -160px;
        opacity: 0;
        transition: opacity 0.3s;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        font-size: 12px;
        line-height: 1.5;
        border: 1px solid #ef4444;
    }

    .custom-tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #1e293b transparent transparent transparent;
    }

    .help-icon-wrapper:hover .custom-tooltip {
        visibility: visible;
        opacity: 1;
    }

    /* Calculation Row (The Purple-ish Box Section) */
    .calculation-box {
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        border: 2px dashed #c084fc;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .calc-item {
        text-align: center;
        flex: 1;
        min-width: 150px;
    }

    .calc-label {
        font-size: 14px;
        font-weight: 700;
        color: #6b21a8;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .calc-value-container {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e9d5ff;
        padding: 12px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .calc-value {
        font-size: 24px;
        font-weight: 800;
        color: #7e22ce;
    }

    .calc-operator {
        font-size: 28px;
        font-weight: 700;
        color: #a855f7;
        user-select: none;
    }

    /* Footer Action Buttons */
    .action-btn-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
    }

    .reset-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #475569;
        padding: 12px 18px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .reset-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .pay-now-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        border-radius: 8px;
        color: #ffffff;
        padding: 12px 35px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
    }

    .pay-now-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
    }

    .pay-now-btn:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    /* History Table badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }

    .status-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-failed {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-cancelled {
        background-color: #ffedd5;
        color: #9a3412;
    }

    .status-pending {
        background-color: #f1f5f9;
        color: #475569;
    }
</style>
@endsection

@section('content')
<div class="add-money-container">
    <div class="row">
        <!-- Add Money Form Card -->
        <div class="col-md-12">
            <div class="premium-card">
                <div class="premium-card-header">
                    <span>Add Money (Merchant Balance)</span>
                    <span class="label label-primary" style="font-size: 14px; padding: 6px 12px; border-radius: 4px;">
                        Current E-Balance: ৳ {{ number_format($user->virtual_balance, 2) }}
                    </span>
                </div>
                <div class="premium-card-body">
                    <!-- Session messages -->
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-check-circle"></i> {{ Session::get('success') }}
                        </div>
                    @endif
                    @if(Session::has('warning'))
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-exclamation-triangle"></i> {{ Session::get('warning') }}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-times-circle"></i> {{ Session::get('error') }}
                        </div>
                    @endif

                    <form id="addMoneyForm" method="POST" action="{{ route('handcash.add_money.initiate') }}">
                        @csrf
                        <input type="hidden" name="amount" id="hiddenAmount" value="0">
                        <input type="hidden" name="gateway_code" id="hiddenGatewayCode" value="">

                        <!-- 1. Select Amount -->
                        <div class="section-title">1. Select Amount</div>
                        <div class="amount-btn-group">
                            <button type="button" class="amount-btn" data-value="100">৳100</button>
                            <button type="button" class="amount-btn" data-value="500">৳500</button>
                            <button type="button" class="amount-btn" data-value="2000">৳2,000</button>
                            <button type="button" class="amount-btn" data-value="5000">৳5,000</button>
                            <button type="button" class="amount-btn" data-value="20000">৳20,000</button>
                        </div>

                        <!-- 2. Payment Gateway Selection -->
                        <div class="section-title">
                            2. Select Payment Gateway & Charges
                            <div class="help-icon-wrapper">
                                <i class="fa fa-question-circle help-icon"></i>
                                <div class="custom-tooltip">
                                    আপনি যে Payment Gateway নির্বাচন করবেন, অবশ্যই সেই Gateway-এর মাধ্যমে-ই পেমেন্ট সম্পন্ন করুন। এক Gateway নির্বাচন করে অন্য Gateway দিয়ে পেমেন্ট করলে Transaction ব্যর্থ হতে পারে বা অর্থ সাময়িকভাবে আটকে যেতে পারে। এ ক্ষেত্রে, নিরাপত্তার স্বার্থে আপনার Merchant Account সাময়িকভাবে Suspend হতে পারে এবং Merchant Panel-এর কার্যক্রম সাময়িক বা স্থায়ীভাবে বন্ধ হয়ে যেতে পারে!
                                </div>
                            </div>
                        </div>

                        <div class="gateway-group">
                            @foreach($gateways as $gateway)
                                <div class="gateway-card" data-code="{{ $gateway->code }}" data-rate="{{ $gateway->charge_percent }}">
                                    <div class="gateway-icon">
                                        @if($gateway->code == 'mfs')
                                            <i class="fa fa-mobile"></i>
                                        @elseif($gateway->code == 'card')
                                            <i class="fa fa-credit-card"></i>
                                        @elseif($gateway->code == 'eps')
                                            <i class="fa fa-university"></i>
                                        @else
                                            <i class="fa fa-globe"></i>
                                        @endif
                                    </div>
                                    <div class="gateway-title">{{ $gateway->name }}</div>
                                    <div class="gateway-subtitle">
                                        @if($gateway->code == 'mfs')
                                            bKash, Nagad, Rocket, Upay
                                        @elseif($gateway->code == 'card')
                                            Visa / MasterCard
                                        @elseif($gateway->code == 'eps')
                                            Internet Banking / Cards
                                        @elseif($gateway->code == 'amex')
                                            Amex Credit Card
                                        @else
                                            Online Payment
                                        @endif
                                    </div>
                                    <div class="gateway-charge-badge">Charge: {{ $gateway->charge_percent }}%</div>
                                </div>
                            @endforeach
                        </div>

                        <!-- 3. Payment Process (Violet Box) -->
                        <div class="calculation-box">
                            <div class="calc-item">
                                <div class="calc-label">Merchant Balance</div>
                                <div class="calc-value-container">
                                    <span class="calc-value">৳ <span id="displayMerchantBalance">0</span></span>
                                </div>
                            </div>
                            <div class="calc-operator">+</div>
                            <div class="calc-item">
                                <div class="calc-label">Gateway Charges</div>
                                <div class="calc-value-container">
                                    <span class="calc-value">৳ <span id="displayGatewayCharges">0</span></span>
                                </div>
                            </div>
                            <div class="calc-operator">=</div>
                            <div class="calc-item">
                                <div class="calc-label">Total Amount</div>
                                <div class="calc-value-container">
                                    <span class="calc-value">৳ <span id="displayTotalAmount">0</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Reset & Pay Now Buttons -->
                        <div class="action-btn-row">
                            <button type="button" class="reset-btn" id="resetBtn" title="Reset All Selections">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                            <button type="submit" class="pay-now-btn" id="payNowBtn" disabled>
                                Pay Now <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Balance Add History -->
        <div class="col-md-12">
            <div class="premium-card">
                <div class="premium-card-header">
                    <span>Balance Add History</span>
                </div>
                <div class="premium-card-body table-responsive">
                    <table class="table table-bordered table-striped" id="historyTable">
                        <thead>
                            <tr class="bg-gray">
                                <th>Transaction ID</th>
                                <th>Date & Time</th>
                                <th>Base Amount</th>
                                <th>Gateway</th>
                                <th>Gateway Charge</th>
                                <th>Total Paid</th>
                                <th>Status</th>
                                <th>Payment Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                                <tr>
                                    <td><strong>{{ $history->transaction_id }}</strong></td>
                                    <td>{{ $history->created_at->format('d M Y h:i A') }}</td>
                                    <td>৳ {{ number_format($history->amount, 2) }}</td>
                                    <td>{{ $history->gateway_name }}</td>
                                    <td>৳ {{ number_format($history->gateway_charge, 2) }}</td>
                                    <td>৳ {{ number_format($history->total_paid, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $history->status }}">
                                            {{ ucfirst($history->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $history->payment_reference ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
    $(document).ready(function() {
        // Init DataTables for history
        if ($('#historyTable tbody tr').length > 1 || !$('#historyTable tbody tr td').hasClass('text-muted')) {
            $('#historyTable').DataTable({
                "order": [[1, "desc"]],
                "responsive": true
            });
        }

        let selectedAmount = 0;
        let selectedGatewayCode = '';
        let selectedGatewayRate = 0.0;

        // Amount Selection (Additive)
        $('.amount-btn').on('click', function() {
            let val = parseInt($(this).data('value'));
            selectedAmount += val;
            updateCalculations();
        });

        // Gateway Selection
        $('.gateway-card').on('click', function() {
            $('.gateway-card').removeClass('selected');
            $(this).addClass('selected');

            selectedGatewayCode = $(this).data('code');
            selectedGatewayRate = parseFloat($(this).data('rate'));

            updateCalculations();
        });

        // Reset
        $('#resetBtn').on('click', function() {
            selectedAmount = 0;
            selectedGatewayCode = '';
            selectedGatewayRate = 0.0;

            $('.gateway-card').removeClass('selected');
            updateCalculations();
        });

        // Update Calculation Boxes and hidden inputs
        function updateCalculations() {
            // Update inputs
            $('#hiddenAmount').val(selectedAmount);
            $('#hiddenGatewayCode').val(selectedGatewayCode);

            // Compute charges
            let charges = 0;
            if (selectedGatewayCode && selectedAmount > 0) {
                charges = (selectedAmount * selectedGatewayRate) / 100.0;
            }
            let total = selectedAmount + charges;

            // Update UI text
            $('#displayMerchantBalance').text(selectedAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#displayGatewayCharges').text(charges.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#displayTotalAmount').text(total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

            // Enabled/Disabled state for Pay Now button
            if (selectedAmount > 0 && selectedGatewayCode) {
                $('#payNowBtn').prop('disabled', false);
            } else {
                $('#payNowBtn').prop('disabled', true);
            }
        }

        // Prevent double submit on form submission
        $('#addMoneyForm').on('submit', function() {
            $('#payNowBtn').prop('disabled', true);
            $('#payNowBtn').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        });
    });
</script>
@endsection
