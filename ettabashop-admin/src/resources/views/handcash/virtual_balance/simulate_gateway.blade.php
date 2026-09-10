<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ettaba Shop | Simulated Payment Gateway</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .gateway-wrapper {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .gateway-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 30px 24px;
            text-align: center;
            border-bottom: 4px solid #3b82f6;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .logo-subtext {
            font-size: 13px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .gateway-body {
            padding: 30px 24px;
        }

        .payment-summary {
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #f1f5f9;
            padding: 20px;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            color: #64748b;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
        }

        .summary-label {
            font-weight: 500;
        }

        .summary-val {
            font-weight: 700;
            color: #1e293b;
        }

        .summary-val.total {
            font-size: 20px;
            color: #2563eb;
        }

        .simulation-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 25px;
            color: #b45309;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .simulation-warning i {
            font-size: 16px;
            margin-top: 2px;
        }

        .btn-simulate {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            cursor: pointer;
            margin-bottom: 12px;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            box-sizing: border-box;
        }

        .btn-success {
            background: #10b981;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ef4444;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #94a3b8;
            box-shadow: 0 4px 10px rgba(148, 163, 184, 0.2);
            margin-bottom: 0;
        }

        .btn-cancel:hover {
            background: #64748b;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="gateway-wrapper">
        <div class="gateway-header">
            <div class="logo-text"><i class="fa fa-shield"></i> ETTABA SECURE PAY</div>
            <div class="logo-subtext">Unified Payment Aggregator</div>
        </div>
        <div class="gateway-body">
            <div class="payment-summary">
                <div class="summary-row">
                    <span class="summary-label">Merchant Name</span>
                    <span class="summary-val">{{ $history->user->name }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Transaction ID</span>
                    <span class="summary-val">{{ $history->transaction_id }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Payment Gateway</span>
                    <span class="summary-val">{{ $history->gateway_name }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Base Amount</span>
                    <span class="summary-val">৳ {{ number_format($history->amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Gateway Charge</span>
                    <span class="summary-val">৳ {{ number_format($history->gateway_charge, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Payable Amount</span>
                    <span class="summary-val total">৳ {{ number_format($history->total_paid, 2) }}</span>
                </div>
            </div>

            <div class="simulation-warning">
                <i class="fa fa-info-circle"></i>
                <span>You are currently in the <strong>Sandbox/Simulation mode</strong>. Please select one of the actions below to proceed with the transaction callback.</span>
            </div>

            <form method="POST" action="{{ route('handcash.add_money.gateway.process', ['transaction_id' => $history->transaction_id]) }}">
                @csrf
                <button type="submit" name="action" value="success" class="btn-simulate btn-success">
                    <i class="fa fa-check-circle"></i> Complete Payment (Success)
                </button>
                <button type="submit" name="action" value="fail" class="btn-simulate btn-danger">
                    <i class="fa fa-times-circle"></i> Fail Payment (Failed)
                </button>
                <button type="submit" name="action" value="cancel" class="btn-simulate btn-cancel">
                    <i class="fa fa-ban"></i> Cancel Payment (Cancelled)
                </button>
            </form>
        </div>
    </div>
</body>
</html>
