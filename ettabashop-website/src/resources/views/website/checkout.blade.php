@extends('website.layouts.layout')
@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 150px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route('website.index') }}">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Checkout</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Checkout Start -->
    @if(\Illuminate\Support\Facades\Auth::user())
    <div class="container-fluid pt-5" v-if="!orderSuccess">
        <div class="row px-xl-5">
            <div class="col-lg-7">
                <div class="mb-4">
                    <h4 class="font-weight-semi-bold mb-4 text-primary">Ordering Information</h4>
                    <div class="bg-white p-4 rounded shadow-sm border">
                        <div class="row">
                            <div class="p-3" style="line-height: 2; text-align: justify;">
                                <p>
                                    সম্মানীত কাস্টমার,<br>
                                    <strong>ইত্তেবা শপ লিমিটেড</strong> বাংলাদেশ সরকারের RJSC নিবন্ধিত একটি ই-কমার্স প্রতিষ্ঠান, যা সারাদেশে অনলাইন ও লোকাল ডেলিভারি সেবা পরিচালনা করছে।
                                </p>
                                <p>
                                    🚚 <strong>লোকাল পণ্যের বিশেষ সেবা:</strong><br>
                                    কাঁচাবাজার, গ্রোসারি, খাদ্যপণ্য বা দ্রুত নষ্ট হয় এমন পণ্যগুলো সাধারণত আপনার এলাকার অনুমোদিত লোকাল সেলার বা মার্চেন্টের মাধ্যমেই ডেলিভারি করা হয়।
                                </p>
                                <p>
                                    🕒 <strong>ডেলিভারি সময়সীমা:</strong>
                                </p>
                                <ul style="margin-left: 1.5rem;">
                                    <li><strong>লোকাল অর্ডার (স্থানীয় সেলার):</strong> ১২–২৪ ঘন্টা</li>
                                    <li><strong>অন্যান্য অর্ডার (সারা দেশ):</strong> ৩–৭ কার্যদিবস</li>
                                </ul>
                                <p>
                                    👉 ইত্তেবা শপের প্রতিটি পণ্যে রয়েছে নিশ্চিত <strong>ক্যাশব্যাক</strong> ও <strong>রিওয়ার্ড পয়েন্ট</strong>! 🎁 নিয়মিত পণ্য কিনুন এবং প্রতিবার ক্যাশব্যাক সংগ্রহ করুন!
                                </p>
                                <p style="text-align: center; font-weight: bold; margin-top: 1rem;">
                                    ইত্তেবা শপ মানেই — কাস্টমার জিতবেন প্রতিবার!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5" v-if="cartTotal>0">
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Products</h5>
                        <div class="d-flex justify-content-between" v-for="(item, index) in cartItems">
                            <p>@{{ item.name }}( @{{ item.quantity }})</p>
                            <p>@{{ item.price * item.quantity }}৳</p>
                        </div>
                        <hr class="mt-0">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Delivery charge</h6>
                            <h6 class="font-weight-medium">@{{ deliveryCharge }}৳</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total Bill</h5>
                            <h5 class="font-weight-bold">@{{ cartTotal }}৳</h5>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total cash back</h5>
                            <h5 class="font-weight-bold">@{{ tcb }}৳</h5>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Earned point</h5>
                            <h5 class="font-weight-bold">@{{ trp }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-semi-bold m-0">Payment</h4>
                        <div class="position-relative">
                            <i class="fas fa-question-circle text-muted refund-policy-icon" 
                               :class="{'text-danger active-icon': advancePaymentRequired, 'blurred-icon': !advancePaymentRequired}" 
                               style="cursor: pointer; transition: all 0.3s ease; font-size: 1.25rem;"
                               @click="togglePolicyTooltip"
                               @mouseover="showPolicyTooltip = true"
                               @mouseleave="showPolicyTooltip = false">
                            </i>
                            <div v-if="showPolicyTooltip && advancePaymentRequired" 
                                 class="policy-tooltip shadow-lg border p-3 bg-white text-dark rounded position-absolute" 
                                 style="z-index: 1000; width: 280px; right: 0; top: 30px; line-height: 1.5; font-size: 0.85rem; font-weight: normal; border-left: 4px solid #dc3545 !important; text-align: justify;">
                                <strong>গুরুত্বপূর্ণ:</strong> আপনার কার্টে এমন একটি বা একাধিক পণ্য রয়েছে, যার জন্য অগ্রিম অনলাইন পেমেন্ট বাধ্যতামূলক। এই অর্ডারের ক্ষেত্রে- পেমেন্ট সম্পন্ন হওয়ার পর কাস্টমার ইচ্ছা করলেও অর্ডার বাতিল বা রিফান্ড দাবি করতে পারবেন না। তাই অনুগ্রহ করে পেমেন্টের আগে পণ্য/সেবা, মূল্য ও অন্যান্য তথ্য ভালোভাবে যাচাই করে নিশ্চিত হয়ে অর্ডার করুন। অর্ডার নিশ্চিত করার মাধ্যমে আপনি ইত্তেবা’র এই Advance Payment ও Refund Policy-তে সম্মতি প্রদান করছেন বলে বিবেচিত হবেন।
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="custom-control custom-radio mb-3" :class="{'disabled-payment': advancePaymentRequired}">
                            <input type="radio" class="custom-control-input" @click="selectPaymentMethod(1)" :disabled="advancePaymentRequired" :checked="paymentMethodId === 1" name="payment" id="paypal_auth">
                            <label class="custom-control-label" for="paypal_auth" :style="advancePaymentRequired ? 'text-decoration: line-through; cursor: not-allowed;' : ''">Cash on delivery</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" @click="selectPaymentMethod(2)" :checked="paymentMethodId === 2" name="payment" id="eps_payment_auth">
                            <label class="custom-control-label" for="eps_payment_auth">Online Payment</label>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <form v-on:submit.prevent="saveOrder({{ auth()->user()->id}}, '{{auth()->user()->profile->address ?? '' }}')">
                            {{ csrf_field() }}
                            <button class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3 rounded-pill">{{__('buttons.placeOrder')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid pt-5" v-if="!orderSuccess">
        <div class="row px-xl-5">
            <div class="col-lg-7">
                <div class="mb-4">
                    <h4 class="font-weight-semi-bold mb-4 text-primary">Ordering Information</h4>
                    <div class="bg-white p-4 rounded shadow-sm border text-justify">
                        <p>সম্মানীত গ্রাহক, ইত্তেবা শপ থেকে আপনার প্রয়োজনীয় পণ্যগুলো ক্রয় করার জন্য ধন্যবাদ। আপনার অবগতির জন্য জানিয়ে রাখতে চাই যে, ডেলিভারির ক্ষেত্রে পণ্যের ধরণ অনুযায়ী সময় কিছুটা কম-বেশি হতে পারে। আমরা দ্রুততম সময়ে আপনার পণ্য পৌঁছে দিতে সচেষ্ট।</p>
                        <p class="mt-3">আমাদের সেবা সম্পর্কে কোনো অভিযোগ বা পরামর্শ থাকলে support@ettabashop.com এ মেইল করতে পারেন।</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5" v-if="cartTotal>0">
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Products</h5>
                        <div class="d-flex justify-content-between" v-for="(item, index) in cartItems">
                            <p>@{{ item.name }}( @{{ item.quantity }})</p>
                            <p>@{{ item.price * item.quantity }}৳</p>
                        </div>
                        <hr class="mt-0">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Delivery charge</h6>
                            <h6 class="font-weight-medium">@{{ deliveryCharge }}৳</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Net Total</h5>
                            <h5 class="font-weight-bold">@{{ netTotal }}৳</h5>
                        </div>
                    </div>
                </div>
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Personal information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>আপনার নাম</label>
                            <input type="text" v-model="username" class="form-control rounded-pill" placeholder="আপনার নাম">
                        </div>
                        <div class="form-group">
                            <label>মোবাইল নাম্বার</label>
                            <input type="text" v-model="phone" class="form-control rounded-pill" placeholder="আপনার ফোন নাম্বার">
                        </div>
                        <div class="form-group">
                            <label>ডিলেভারি অ্যাড্রেস</label>
                            <input type="text" v-model="address" class="form-control rounded-pill" placeholder="আপনার ঠিকানা">
                        </div>
                    </div>
                </div>
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-semi-bold m-0">Payment</h4>
                        <div class="position-relative">
                            <i class="fas fa-question-circle text-muted refund-policy-icon" 
                               :class="{'text-danger active-icon': advancePaymentRequired, 'blurred-icon': !advancePaymentRequired}" 
                               style="cursor: pointer; transition: all 0.3s ease; font-size: 1.25rem;"
                               @click="togglePolicyTooltip"
                               @mouseover="showPolicyTooltip = true"
                               @mouseleave="showPolicyTooltip = false">
                            </i>
                            <div v-if="showPolicyTooltip && advancePaymentRequired" 
                                 class="policy-tooltip shadow-lg border p-3 bg-white text-dark rounded position-absolute" 
                                 style="z-index: 1000; width: 280px; right: 0; top: 30px; line-height: 1.5; font-size: 0.85rem; font-weight: normal; border-left: 4px solid #dc3545 !important; text-align: justify;">
                                <strong>গুরুত্বপূর্ণ:</strong> আপনার কার্টে এমন একটি বা একাধিক পণ্য রয়েছে, যার জন্য অগ্রিম অনলাইন পেমেন্ট বাধ্যতামূলক। এই অর্ডারের ক্ষেত্রে- পেমেন্ট সম্পন্ন হওয়ার পর কাস্টমার ইচ্ছা করলেও অর্ডার বাতিল বা রিফান্ড দাবি করতে পারবেন না। তাই অনুগ্রহ করে পেমেন্টের আগে পণ্য/সেবা, মূল্য ও অন্যান্য তথ্য ভালোভাবে যাচাই করে নিশ্চিত হয়ে অর্ডার করুন। অর্ডার নিশ্চিত করার মাধ্যমে আপনি ইত্তেবা’র এই Advance Payment ও Refund Policy-তে সম্মতি প্রদান করছেন বলে বিবেচিত হবেন।
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="custom-control custom-radio mb-3" :class="{'disabled-payment': advancePaymentRequired}">
                            <input type="radio" class="custom-control-input" @click="selectPaymentMethod(1)" :disabled="advancePaymentRequired" :checked="paymentMethodId === 1" name="payment" id="paypal_guest">
                            <label class="custom-control-label" for="paypal_guest" :style="advancePaymentRequired ? 'text-decoration: line-through; cursor: not-allowed;' : ''">Cash on delivery</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" @click="selectPaymentMethod(2)" :checked="paymentMethodId === 2" name="payment" id="eps_payment_guest">
                            <label class="custom-control-label" for="eps_payment_guest">Online Payment</label>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <form v-on:submit.prevent="saveAnonymousOrder">
                            {{ csrf_field() }}
                            <button class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3 rounded-pill">{{__('buttons.placeOrder')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Message (Unified) -->
    <div class="container-fluid py-5" v-if="orderSuccess">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center bg-white p-5 rounded-lg shadow-lg border">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h1 class="display-4 text-success font-weight-bold mb-3">Order Successful!</h1>
                <h3 class="mb-4">আপনার অর্ডারটি সফল ভাবে সাবমিট হয়েছে।</h3>
                <p class="text-muted mb-5">আমাদের একজন প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন। ইত্তেবা শপের সাথে থাকার জন্য ধন্যবাদ।</p>
                <div class="d-flex justify-content-center">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill px-5 mr-3 shadow-sm">Back to Home</a>
                    @auth
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5">View My Orders</a>
                    @endauth
                </div>
            </div>
    </div>
    <!-- Checkout End -->

<style>
    .blurred-icon {
        opacity: 0.35;
        filter: blur(0.3px);
        cursor: default !important;
    }
    .active-icon {
        opacity: 1 !important;
        filter: none !important;
        color: #dc3545 !important;
        animation: pulse-icon 1.5s infinite;
    }
    @keyframes pulse-icon {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
    .disabled-payment {
        opacity: 0.55;
        cursor: not-allowed;
    }
</style>
@endsection()
