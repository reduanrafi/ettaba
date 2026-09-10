@extends('website.layouts.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #f1f3f9);">
                
                {{-- Dynamic Color Header and Icon --}}
                @if($status === 'success')
                    <div class="text-center py-5" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                        <div class="icon-container mb-3 animate__animated animate__zoomIn">
                            <i class="fas fa-check-circle text-white" style="font-size: 80px; filter: drop-shadow(0px 4px 10px rgba(0, 0, 0, 0.15));"></i>
                        </div>
                        <h2 class="text-white font-weight-bold mb-0">Payment Successful!</h2>
                    </div>
                @elseif($status === 'cancelled')
                    <div class="text-center py-5" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
                        <div class="icon-container mb-3 animate__animated animate__zoomIn">
                            <i class="fas fa-exclamation-triangle text-white" style="font-size: 80px; filter: drop-shadow(0px 4px 10px rgba(0, 0, 0, 0.15));"></i>
                        </div>
                        <h2 class="text-white font-weight-bold mb-0">Payment Cancelled</h2>
                    </div>
                @else
                    <div class="text-center py-5" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
                        <div class="icon-container mb-3 animate__animated animate__zoomIn">
                            <i class="fas fa-times-circle text-white" style="font-size: 80px; filter: drop-shadow(0px 4px 10px rgba(0, 0, 0, 0.15));"></i>
                        </div>
                        <h2 class="text-white font-weight-bold mb-0">Payment Failed</h2>
                    </div>
                @endif

                {{-- Card Body --}}
                <div class="card-body p-4 text-center">
                    <p class="lead text-muted mb-4">{{ $message }}</p>

                    @if(isset($order))
                        <div class="bg-white rounded p-4 mb-4 border text-left shadow-sm">
                            <h5 class="font-weight-bold mb-3 border-bottom pb-2 text-primary">Order Summary</h5>
                            <div class="row mb-2">
                                <div class="col-6 text-muted">Order ID:</div>
                                <div class="col-6 font-weight-bold text-right">{{ $order->unique_order_id ?? ('ESL' . $order->id) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 text-muted">Amount Paid:</div>
                                <div class="col-6 font-weight-bold text-right text-success">{{ number_format($order->net_total, 2) }} ৳</div>
                            </div>
                            @if($order->transaction_id)
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">Transaction ID:</div>
                                    <div class="col-6 text-right text-truncate" style="font-size: 0.9rem;" title="{{ $order->transaction_id }}">
                                        <code>{{ $order->transaction_id }}</code>
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-6 text-muted">Payment Status:</div>
                                <div class="col-6 text-right">
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : ($order->payment_status === 'failed' ? 'badge-danger' : 'badge-warning') }} px-3 py-2 rounded-pill text-uppercase">
                                        {{ $order->payment_status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill px-5 mr-2 shadow-sm" style="transition: all 0.3s ease;">
                            Back to Home
                        </a>
                        @auth
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5" style="transition: all 0.3s ease;">
                                View Orders
                            </a>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .icon-container {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }
@if($status === 'success')
<script>
    localStorage.removeItem('cartItems');
</script>
@endif
@endsection
