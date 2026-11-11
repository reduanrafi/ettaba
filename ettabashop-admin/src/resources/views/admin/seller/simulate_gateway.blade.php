@extends('admin.layouts.layout')

@section('content')
<section class="content-header">
    <h1><i class="fa fa-desktop text-blue"></i> EPS Gateway Simulation (Demo Environment)</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box box-primary" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div class="box-header with-border bg-blue">
                    <h3 class="box-title"><i class="fa fa-credit-card"></i> Seller e-Balance Recharge Portal</h3>
                </div>
                <div class="box-body" style="padding: 25px;">
                    <div class="well text-center" style="background: #f8fafc;">
                        <h4 style="margin: 0 0 10px; color: #475569;">ট্রানজেকশন বিবরণ</h4>
                        <div style="font-size: 26px; font-weight: 800; color: #16a34a; margin-bottom: 5px;">
                            ৳ {{ number_format($history->total_paid, 2) }}
                        </div>
                        <p class="text-muted" style="margin-bottom: 0;">
                            মূল রিচার্জ: ৳ {{ number_format($history->amount, 2) }} | গেটওয়ে ফি: ৳ {{ number_format($history->gateway_charge, 2) }}
                        </p>
                        <p style="margin-top: 5px;">
                            <code>Trx ID: {{ $history->transaction_id }}</code>
                        </p>
                    </div>

                    <p class="text-center text-muted" style="font-size: 13px;">
                        এটি একটি টেস্ট পেমেন্ট সিমুলেশন। নিচের যেকোনো বাটন চাপ দিয়ে লেনদেন সম্পন্ন করতে পারেন:
                    </p>

                    <form action="{{ route('seller.addMoney.gateway.process', $history->transaction_id) }}" method="POST">
                        @csrf
                        <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                            <button type="submit" name="action" value="success" class="btn btn-success btn-lg" style="font-weight: 700; flex: 1;">
                                <i class="fa fa-check-circle"></i> সফল পেমেন্ট (Success)
                            </button>
                            <button type="submit" name="action" value="fail" class="btn btn-danger btn-lg" style="font-weight: 700; flex: 1;">
                                <i class="fa fa-times-circle"></i> ব্যর্থ (Fail)
                            </button>
                            <button type="submit" name="action" value="cancel" class="btn btn-default btn-lg" style="flex: 1;">
                                বাতিল (Cancel)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

