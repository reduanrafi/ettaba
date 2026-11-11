@extends('admin.layouts.layout')

@section('extra-style')
<style>
    .seller-addmoney-container {
        padding: 20px 10px;
        background: #f8fafc;
    }
    .seller-addmoney-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 25px;
        overflow: hidden;
    }
    .seller-addmoney-header {
        background: linear-gradient(135deg, #166534 0%, #15803d 100%);
        color: #ffffff;
        padding: 16px 20px;
        font-weight: 700;
        font-size: 17px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .balance-badge-box {
        background: #f0fdf4;
        border: 2px solid #86efac;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .balance-badge-val {
        font-size: 26px;
        font-weight: 800;
        color: #15803d;
    }
    .amount-btn-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }
    .amount-btn {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        color: #1e293b;
        padding: 10px 20px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .amount-btn:hover, .amount-btn.active {
        border-color: #16a34a;
        background: #f0fdf4;
        color: #15803d;
    }
    .gateway-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .gateway-option-card:hover {
        border-color: #86efac;
        background: #fafffc;
    }
    .gateway-option-card.selected {
        border-color: #22c55e;
        background: #f0fdf4;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        <i class="fa fa-plus-square text-green"></i> Add Money — Seller e-Balance
    </h1>
</section>

<section class="content seller-addmoney-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-info-circle"></i> {{ session('warning') }}
        </div>
    @endif

    <div class="row">
        {{-- Left Column: Add Money Form --}}
        <div class="col-md-6">
            <div class="seller-addmoney-card">
                <div class="seller-addmoney-header">
                    <span><i class="fa fa-credit-card"></i> ব্যালেন্স রিচার্জ ফরম</span>
                    <span class="badge bg-green">Online Payment (EPS / MFS / Card)</span>
                </div>
                <div class="box-body" style="padding: 20px;">
                    <!-- Balance Display Box -->
                    <div class="balance-badge-box">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                আপনার বর্তমান সেলার ব্যালেন্স (Seller e-Balance)
                            </div>
                            <div class="balance-badge-val">৳ {{ number_format($currentEbalance, 2) }}</div>
                        </div>
                        <div>
                            <span class="label label-success" style="font-size: 12px; padding: 6px 12px; border-radius: 6px;">
                                <i class="fa fa-shield"></i> সিকিউরড ওয়ালেট
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('seller.addMoney.initiate') }}" method="POST" id="sellerAddMoneyForm">
                        @csrf
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 14px; color: #1e293b;">
                                রিচার্জের পরিমাণ (টাকা) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="amount" id="customAmountInput" class="form-control input-lg"
                                   placeholder="পরিমাণ লিখুন (যেমন: ৫০০)" min="1" step="any" required
                                   style="font-size: 18px; font-weight: 700; border-radius: 8px;">
                        </div>

                        <!-- Quick Presets -->
                        <div style="font-size: 12.5px; font-weight: 700; color: #64748b; margin-bottom: 8px;">
                            সহজে বাছাই করুন:
                        </div>
                        <div class="amount-btn-grid">
                            <button type="button" class="amount-btn" onclick="setSellerAmount(300)">৳ ৩০০</button>
                            <button type="button" class="amount-btn" onclick="setSellerAmount(500)">৳ ৫০০</button>
                            <button type="button" class="amount-btn" onclick="setSellerAmount(1000)">৳ ১,০০০</button>
                            <button type="button" class="amount-btn" onclick="setSellerAmount(2000)">৳ ২,০০০</button>
                            <button type="button" class="amount-btn" onclick="setSellerAmount(5000)">৳ ৫,০০০</button>
                        </div>

                        <!-- Payment Gateways -->
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 14px; color: #1e293b; margin-bottom: 10px;">
                                পেমেন্ট মেথড বাছাই করুন <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="gateway_code" id="selectedGatewayCode" value="{{ $gateways->first()->code ?? 'mfs' }}">

                            @forelse($gateways as $idx => $gw)
                                <div class="gateway-option-card {{ $idx === 0 ? 'selected' : '' }}" onclick="selectSellerGateway('{{ $gw->code }}', this)">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <i class="fa fa-dot-circle-o text-success" style="font-size: 18px;"></i>
                                        <div>
                                            <strong style="font-size: 15px; color: #0f172a;">{{ $gw->name }}</strong>
                                            <div style="font-size: 12px; color: #64748b;">
                                                চার্জ: {{ floatval($gw->charge_percent) }}% ({{ $gw->charge_percent > 0 ? 'গেgateway ফি প্রযোজ্য' : 'ফ্রি' }})
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-blue" style="font-size: 11px;">Select</span>
                                </div>
                            @empty
                                <div class="gateway-option-card selected" onclick="selectSellerGateway('mfs', this)">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <i class="fa fa-dot-circle-o text-success" style="font-size: 18px;"></i>
                                        <div>
                                            <strong style="font-size: 15px; color: #0f172a;">Bkash / Nagad / Rocket (MFS)</strong>
                                            <div style="font-size: 12px; color: #64748b;">EPS Instant Secure Gateway</div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="callout callout-info" style="font-size: 12px; margin-top: 15px; border-radius: 6px;">
                            <i class="fa fa-info-circle"></i> সেলফ-ডেলিভারি অর্ডারে কাস্টমারকে পণ্য সরবরাহের পর অর্ডার সম্পন্ন করতে আপনার Seller e-Balance প্রয়োজন হবে।
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block" style="font-weight: 800; border-radius: 8px; margin-top: 15px;">
                            <i class="fa fa-arrow-right"></i> পেমেন্টে এগিয়ে যান
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Transaction History --}}
        <div class="col-md-6">
            <div class="seller-addmoney-card">
                <div class="seller-addmoney-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);">
                    <span><i class="fa fa-history"></i> রিচার্জ হিস্ট্রি (Add Money History)</span>
                    <span class="badge bg-blue">{{ count($histories) }} রেকর্ড</span>
                </div>
                <div class="box-body table-responsive" style="padding: 10px;">
                    <table class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th>তারিখ</th>
                                <th>ট্রানজেকশন আইডি</th>
                                <th>গেটওয়ে</th>
                                <th>রিচার্জ</th>
                                <th>পরিশোধ</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $h)
                                <tr>
                                    <td>{{ $h->created_at ? $h->created_at->format('d M Y, h:i A') : '' }}</td>
                                    <td><code>{{ $h->transaction_id }}</code></td>
                                    <td>{{ $h->gateway_name ?: 'Gateway' }}</td>
                                    <td><strong class="text-green">৳{{ number_format($h->amount, 2) }}</strong></td>
                                    <td>৳{{ number_format($h->total_paid, 2) }}</td>
                                    <td>
                                        @if($h->status === 'success')
                                            <span class="label label-success">সফল (Success)</span>
                                        @elseif($h->status === 'pending')
                                            <span class="label label-warning">অপেক্ষমান</span>
                                        @elseif($h->status === 'failed')
                                            <span class="label label-danger">ব্যর্থ</span>
                                        @elseif($h->status === 'cancelled')
                                            <span class="label label-default">বাতিল</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding: 30px;">
                                        কোনো ব্যালেন্স রিচার্জ রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function setSellerAmount(val) {
        document.getElementById('customAmountInput').value = val;
    }

    function selectSellerGateway(code, el) {
        document.getElementById('selectedGatewayCode').value = code;
        var cards = document.querySelectorAll('.gateway-option-card');
        cards.forEach(function(card) {
            card.classList.remove('selected');
        });
        el.classList.add('selected');
    }
</script>
@endsection

