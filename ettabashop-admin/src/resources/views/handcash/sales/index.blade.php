@extends('admin.layouts.layout')
@section('content')
    <section class="invoice" id="app">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="search-container" style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <div style="flex-grow: 1; position: relative;">
                        <input class="form-control input-lg" v-model="eid" placeholder="eID দিয়ে কাস্টমার সার্চ করুন" style="border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: none; padding-left: 15px;">
                    </div>
                    <button @click="getUserProfile" class="btn btn-info btn-lg" style="border-radius: 8px; background-color: #00bcd4; border-color: #00bcd4; font-weight: 600; padding: 0 25px; white-space: nowrap;">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
        <!-- Error Message Row -->
        <div class="row" v-if="errorMessage">
            <div class="col-xs-12">
                <div class="alert alert-warning" style="white-space: pre-line; border-radius: 8px;">
                    @{{ errorMessage }}
                </div>
            </div>
        </div>
        <!-- info row -->
        <div class="row" v-if="userId > 0">
            <div class="col-xs-12">
                <div class="customer-card">
                    <div class="customer-avatar-wrapper">
                        <img class="customer-avatar" :src="profileImage" alt="Customer profile picture"
                            onerror="this.onerror=null; this.src='{{ asset('assets/images/user.png') }}'">
                    </div>
                    <div class="customer-info">
                        <h4 class="customer-name">@{{ username }}</h4>
                        <div class="customer-balance-row">
                            <span>Customer Balance:</span>
                            <span class="customer-balance-badge">৳ @{{ eBalance }}</span>
                        </div>
                        <p class="customer-address" v-if="address"><i class="fa fa-map-marker"></i> @{{ address }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <br>
        <!-- Product Search (Hidden for new system) -->
        <div class="row" v-if="false">
            <div class="col-xs-9">
                <div class="form-group">
                    <input class="form-control" placeholder="শর্ট নেম অথবা প্রডাক্ট কোড লিখে সার্চ করোন"
                        v-model="searchQuery" @input="fetchSuggestions" placeholder="Search for an item">
                    <ul v-if="suggestions.length">
                        <li v-for="item in suggestions" :key="item.id" @click="addToCart(item)">
                            @{{ item.name }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">

                    <button type="submit" class="btn btn-success">Search</button>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- Table row (Hidden for new system) -->
        <div class="row" v-if="false">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>

                            <th>Trp</th>
                            <th>unite price</th>
                            <th>Quantity</th>



                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody v-if="cartItems.length>0">
                        <tr v-for="(item, index) in cartItems">
                            <td class="align-middle"> @{{ item.name }}</td>

                            @if(app()->getLocale() == 'en')

                                <td class="align-middle">@{{ item.trp }}</td>
                                <td class="align-middle">@{{ item.price}}</td>
                            @else
                                <td class="align-middle">@{{ item.price}}</td>
                                <td class="align-middle">@{{ item.trp}}</td>
                            @endif
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    {{-- <div class="input-group-btn">--}}
                                        {{-- <button class="btn btn-sm btn-primary btn-minus">--}}
                                            {{-- <i class="fa fa-minus"></i>--}}
                                            {{-- </button>--}}
                                        {{-- </div>--}}
                                    <input type="number" disabled min="1" v-model="item.quantity"
                                        class="form-control form-control-sm bg-secondary text-center">
                                    {{-- <div class="input-group-btn">--}}
                                        {{-- <button class="btn btn-sm btn-primary btn-plus" @click="addToCart(item)">--}}

                                            {{-- <i class="fa fa-plus"></i>--}}
                                            {{-- </button>--}}
                                        {{-- </div>--}}
                                </div>
                            </td>
                            <td class="align-middle">@{{ item.price * item.quantity}}</td>
                            <td class="align-middle"><button @click="removeItem(index)" class="btn btn-sm btn-primary"><i
                                        class="fa fa-times"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row" v-if="false">
            <div class="col-xs-6">
                <div class="form-group">
                    <button type="button" @click="saveOrder(userId,'No address required')" class="btn btn-success">V-Balance
                        & Cash</button>
                    <button type="button" @click="saveOrder(userId,'No address required')"
                        class="btn btn-success">Cash</button>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <h2>Total: @{{ cartTotal }}</h2>
                </div>
            </div>
            <!-- /.col -->
        </div>

        <!-- /.row -->

        <!-- New Direct Pay flow -->
        <div class="row" v-if="userId > 0">
            <div class="col-xs-12">
                <div class="payment-card">
                    <div class="payment-card-header">
                        <div class="merchant-info-group">
                            <div class="merchant-wallet-icon">
                                <i class="fa fa-wallet"></i>
                            </div>
                            <div class="merchant-balance-details">
                                <span class="merchant-balance-label">Merchant Balance</span>
                                <span class="merchant-balance-amount">৳ @{{ sellerBalance }}</span>
                            </div>
                        </div>
                        <a href="{{ route('handcash.virtual_balance.create') }}" class="add-money-btn">
                            <i class="fa fa-plus-circle"></i> Add Money
                        </a>
                    </div>
                    <div class="payment-card-body">
                        <h4 class="payment-title">Enter Amount (টাকার পরিমাণ)</h4>
                        <div class="amount-input-wrapper">
                            <input type="number" v-model="cashbackAmount" class="form-control amount-input" placeholder="৳ 0.00">
                        </div>
                        <button class="btn confirm-btn" @click="directPay">
                            <i class="fa fa-check-circle"></i> Confirm Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- this row will not appear when printing -->
        {{-- <div class="row no-print">--}}
            {{-- <div class="col-xs-12">--}}
                {{-- <a onclick="javascript:window.print();" class="btn btn-default"><i class="fa fa-print"></i>
                    Print</a>--}}

                {{-- </div>--}}
            {{-- </div>--}}
    </section>
@endsection
@section('extra-style')
    <style>
        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 5px;
            border: 1px solid #ddd;
        }

        li {
            padding: 8px;
            cursor: pointer;
        }

        li:hover {
            background-color: #f0f0f0;
        }

        /* Card Container */
        .payment-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
            margin-bottom: 25px;
            overflow: hidden;
        }

        /* Header with Merchant Balance and Add Money */
        .payment-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #fafbfc;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: nowrap; /* Force one line on mobile */
            gap: 12px;
        }

        .merchant-info-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .merchant-wallet-icon {
            background: #e0f2fe;
            color: #0284c7;
            padding: 8px;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .merchant-balance-details {
            display: flex;
            flex-direction: column;
        }

        .merchant-balance-label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1.2;
        }

        .merchant-balance-amount {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
            white-space: nowrap;
        }

        .add-money-btn {
            font-weight: 600;
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 13px;
            box-shadow: 0 2px 4px rgba(0, 188, 212, 0.2);
            background-color: #00bcd4;
            border: none;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
            cursor: pointer;
        }

        .add-money-btn:hover, .add-money-btn:focus {
            background-color: #00acc1;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 188, 212, 0.3);
            transform: translateY(-1px);
        }

        .add-money-btn:active {
            transform: translateY(0);
        }

        /* Card Body */
        .payment-card-body {
            padding: 30px 20px;
            text-align: center;
            background: #ffffff;
        }

        .payment-title {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 20px;
        }

        .amount-input-wrapper {
            position: relative;
            max-width: 280px;
            margin: 0 auto;
        }

        .amount-input {
            width: 100% !important;
            font-size: 28px !important;
            font-weight: 700 !important;
            text-align: center;
            border: 2px solid #cbd5e1 !important;
            border-radius: 10px !important;
            padding: 10px 15px !important;
            color: #1e293b !important;
            background-color: #f8fafc !important;
            transition: all 0.2s ease !important;
            height: auto !important;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02) !important;
        }

        .amount-input:focus {
            border-color: #00bcd4 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15) !important;
            outline: none !important;
        }

        /* Chrome, Safari, Edge, Opera: remove spinner arrows */
        .amount-input::-webkit-outer-spin-button,
        .amount-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox: remove spinner arrows */
        .amount-input[type=number] {
            -moz-appearance: textfield;
        }

        .confirm-btn {
            width: 100%;
            max-width: 280px;
            font-size: 16px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            border-radius: 30px !important;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2) !important;
            transition: all 0.2s ease !important;
            cursor: pointer;
            margin-top: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .confirm-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3) !important;
            transform: translateY(-1px);
        }

        .confirm-btn:active {
            transform: translateY(0);
        }

        /* Customer Card */
        .customer-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .customer-avatar-wrapper {
            flex-shrink: 0;
        }

        .customer-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #e2e8f0;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .customer-info {
            flex-grow: 1;
            text-align: left;
        }

        .customer-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 6px 0;
        }

        .customer-balance-row {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .customer-balance-badge {
            background: #dcfce7;
            color: #15803d;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
        }

        .customer-address {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        @media (max-width: 480px) {
            .payment-card-header {
                padding: 12px 15px;
            }
            .merchant-balance-amount {
                font-size: 16px;
            }
            .add-money-btn {
                padding: 5px 10px;
                font-size: 11px;
            }
            .customer-card {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .customer-avatar {
                width: 70px;
                height: 70px;
            }
            .customer-info {
                text-align: center;
            }
            .customer-balance-row {
                justify-content: center;
            }
        }
    </style>
@endsection
@section('extra-script')
    {{--
    <script src="https://adminlte.io/themes/AdminLTE/bower_components/fastclick/lib/fastclick.js"></script>--}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>

    <script>
        const app = {

            data() {
                return {
                    message: '',
                    rootUrl: '{{ url("/") }}/',
                    frontendUrl: '{{ rtrim(env("FRONTEND_URL", "https://ettabashop.com"), "/") }}/',
                    cartItems: [],
                    cartTotal: 0,
                    netTotal: 0,
                    trp: 0,
                    tcb: 0,
                    rateTotal: 0,
                    //cartTotalBn:this.changeNumberToBangla(this.cartTotal),
                    deliveryCharge: 10,
                    paymentMethodId: 0,
                    emptyStar: "far fa-star",
                    filledStar: "fas fa-star",
                    star1: "far fa-star",
                    star2: "far fa-star",
                    star3: "far fa-star",
                    star4: "far fa-star",
                    star5: "far fa-star",
                    rating: 0,
                    ratingText: "",
                    orderSuccess: false,

                    customerId: 0,
                    userId: 0,
                    username: "",
                    phone: "",
                    address: "",
                    dVirtualBalance: 0,
                    virtualBalance: 0,
                    vbCount: 0,
                    eBalance: 0,
                    sellerBalance: {{ auth()->user()->virtual_balance ?? 0 }},

                    eid: "",
                    profileImage: "{{ asset('assets/images/user.png') }}",

                    searchQuery: '',
                    suggestions: [],
                    cashbackAmount: null,
                    errorMessage: null
                }

            },
            watch: {
                eid(val) {
                    if (val && val.includes('ENC/')) {
                        let parts = val.split('ENC/');
                        if (parts[1]) {
                            this.eid = parts[1].trim();
                            this.getUserProfile();
                        }
                    } else if (val && val.includes('.ettabashop.com/')) {
                        // generic fallback
                        let p = val.split('/');
                        let last = p[p.length - 1];
                        if (last) {
                            this.eid = last.trim();
                            this.getUserProfile();
                        }
                    }
                }
            },
            methods: {

                fetchSuggestions() {
                    if (this.searchQuery.length < 2) {
                        this.suggestions = [];
                        return;
                    }

                    axios.get(this.rootUrl + `hand-cash-product`, { params: { query: this.searchQuery } })
                        .then(response => {
                            this.suggestions = response.data.products;
                            console.log(this.suggestions)
                        })
                        .catch(error => {
                            console.error("Error fetching suggestions:", error);
                        });
                },
                getUserProfile() {
                    this.errorMessage = null;
                    axios.get(this.rootUrl + "hand-cash-customer?eid=" + this.eid)
                        .then(response => {


                            console.log("status " + response.status);
                            console.log(response);
                            if (response.data.status === 'notFound' || response.data.status === 'notAuthorized') {
                                if (response.data.message) {
                                    this.errorMessage = response.data.message;
                                }
                                this.clearUserData();
                            }
                            else {
                                this.bindUserProfileData(response.data);
                            }
                        }).catch(error => console.log(error.response));

                    console.log(this.eid);
                },
                bindUserProfileData(user) {
                    this.username = user.firstName + ' ' + user.lastName
                    this.userId = user.userId
                    this.phone = user.phone
                    this.address = user.address
                    this.profileImage = this.frontendUrl + user.avatar
                    this.eBalance = user.eBalance
                },
                clearUserData() {
                    this.username = ""
                    this.customerId = 0
                    this.userId = 0
                    this.phone = ""
                    this.address = ""
                    this.profileImage = "{{ asset('assets/images/user.png') }}"
                    this.eBalance = 0
                },
                clickWorking() {
                    console.log("Click working")
                },
                addToCart(item) {

                    let found = false;
                    console.log(item)
                    console.log(item.name)
                    itemToAdd = {
                        'product_id': item.id,
                        'owner_id': item.owner_id,
                        'name': item.name,
                        'price': item.erp_en,
                        'trp': item.trp_en,
                        'tcb': item.tcb_en,
                        'rate': item.rate_en,
                        'quantity': 1
                    };
                    console.log(itemToAdd)

                    // Add the item or increase qty

                    let itemInCart = this.cartItems.filter(item => item.product_id === itemToAdd.product_id);

                    let isItemInCart = itemInCart.length > 0;

                    if (isItemInCart === false) {
                        this.cartItems.push(itemToAdd);
                    } else {
                        itemInCart[0].quantity += itemToAdd.quantity;
                    }

                    localStorage.setItem('cartItems', JSON.stringify(this.cartItems));

                    this.setTotal(this.calculateTotal())


                },

                removeItem(index) {

                    this.cartItems.splice(index, 1);

                    localStorage.setItem('cartItems', JSON.stringify(this.cartItems));

                    this.setTotal(this.calculateTotal())

                },
                clearCart() {
                    this.cartItems = [];
                    localStorage.clear();
                    this.cartTotal = 0;

                },
                calculateTotal() {
                    let items = JSON.parse(localStorage.getItem('cartItems'));
                    let total = [];

                    total['erp'] = 0;
                    total['trp'] = 0;
                    total['tcb'] = 0;
                    total['rate'] = 0;

                    items.forEach(item => {

                        total['erp'] += item.price * item.quantity;

                        total['trp'] += item.trp * item.quantity;

                        total['tcb'] += item.tcb * item.quantity;

                        total['rate'] += item.rate * item.quantity;

                    });

                    return total;
                },

                setTotal(total) {
                    this.cartTotal = total['erp'];

                    this.trp = total['trp'];

                    this.tcb = total['tcb'];

                    this.rateTotal = total['rate'];

                    this.netTotal = this.cartTotal - this.tcb;

                },

                calculateTotalTrp() {
                    let items = JSON.parse(localStorage.getItem('cartItems'));
                    let totalTrp = 0;
                    items.forEach(item => {
                        totalTrp += item.trp * item.quantity;
                    });

                    return totalTrp;
                },
                calculateVDeductedNetTotal(total, virtualBalance, trp) {

                    if (this.vbCount == 0) {

                        this.dVirtualBalance = trp * 20;
                        this.virtualBalance = virtualBalance - this.dVirtualBalance;
                        if (this.virtualBalance < 0) {
                            this.virtualBalance = this.dVirtualBalance = virtualBalance;
                        }

                        this.netTotal = this.netTotal - this.dVirtualBalance;

                        this.vbCount++;
                        return this.netTotal;

                    }
                },
                selectPaymentMethod(pmId) {
                    this.paymentMethodId = pmId;
                    console.log(this.paymentMethodId)
                },
                saveOrder(userId, address, total) {
                    let order = {
                        'order': {
                            "user_id": userId,
                            "payment_method_id": 1,
                            "address_id": 1,
                            "erp_total": this.cartTotal,
                            "net_total": this.netTotal,
                            "rate_total": this.rateTotal,
                            "trp": this.trp,
                            "tcb": this.tcb,
                            "orderItems": this.cartItems,
                            "virtual_balance": this.dVirtualBalance
                        }

                    }
                    console.log(order)
                    axios.post(this.rootUrl + "hand-cash-order-save", order)
                        .then(response => {

                            console.log(response);
                            this.message = response.data.message

                        }).catch(error => console.log(error.response));
                    this.orderSuccess = true;
                    //this.clearCart()
                },
                directPay() {
                    if (!this.cashbackAmount || this.cashbackAmount <= 0) {
                        alert('Please enter a valid amount.');
                        return;
                    }
                    axios.post(this.rootUrl + "hand-cash-direct-pay", {
                        user_id: this.userId,
                        amount: this.cashbackAmount
                    })
                        .then(response => {
                            alert(response.data.message);
                            if (response.data.status === 'ok') {
                                this.sellerBalance = (parseFloat(this.sellerBalance) - parseFloat(this.cashbackAmount)).toFixed(2);
                                this.clearUserData();
                                this.cashbackAmount = null;
                                this.eid = '';
                            }
                        }).catch(error => {
                            console.log(error);
                            alert('Something went wrong!');
                        });
                },

                timeSince: function (date) {

                    if (date != undefined) {

                        var seconds = Math.floor((new Date() - this.sqlToJS(date)) / 1000);

                        ////console.log(date)
                        var interval = Math.floor(seconds / 31536000);

                        if (interval > 1) {
                            return interval + " years";
                        }
                        interval = Math.floor(seconds / 2592000);
                        if (interval > 1) {
                            return interval + " months";
                        }
                        interval = Math.floor(seconds / 86400);
                        if (interval > 1) {
                            return interval + " days";
                        }
                        if (interval == 1) {

                            return interval + " day";
                        }
                        interval = Math.floor(seconds / 3600);
                        if (interval > 1) {
                            return interval + " hours";
                        }
                        interval = Math.floor(seconds / 60);
                        if (interval > 1) {
                            return interval + " minutes";
                        }
                        return Math.floor(seconds) + " seconds";
                    }

                },

                changeNumberToBangla(n) {
                    console.log(n)
                    const toBn = n => n.replace(/\d/g, d => "০১২৩৪৫৬৭৮৯"[d]);

                    return toBn(n)
                },
                ratingUpdate(rating) {
                    this.rating = rating;
                    this.changeClass(rating)

                },
                saveRating() {

                    console.log(this.rating)
                    console.log(this.ratingText)
                },


            },
            mounted: function () {
                let currentUrl = window.location.href;
                if (currentUrl.includes('ENC/')) {
                    let parts = currentUrl.split('ENC/');
                    if (parts[1]) {
                        this.eid = 'ENC/' + parts[1].trim();
                    }
                }

                if (localStorage.getItem('cartItems') != 'undefined' && localStorage.getItem('cartItems') != null) {

                    this.cartItems = JSON.parse(localStorage.getItem('cartItems'));
                    this.cartTotal = this.Total['erp'];
                    this.trp = this.Total['trp'];
                    this.tcb = this.Total['tcb'];
                    this.rateTotal = this.Total['rate'];
                    this.netTotal = this.cartTotal - this.tcb;
                }

            },
            computed: {
                Total() {
                    console.log(localStorage.getItem('cartItems'))
                    if (localStorage.getItem('cartItems') != 'undefined' && localStorage.getItem('cartItems') != null) {
                        let items = JSON.parse(localStorage.getItem('cartItems'));

                        let total = [];

                        total['erp'] = 0;
                        total['trp'] = 0;
                        total['tcb'] = 0;
                        total['rate'] = 0;

                        items.forEach(item => {
                            total['erp'] += item.price * item.quantity;
                            total['trp'] += item.trp * item.quantity;
                            total['tcb'] += item.tcb * item.quantity;
                            total['rate'] += item.rate * item.quantity;
                        });
                        if (items.length > 0) {
                            total['erp'] += this.deliveryCharge;

                        }
                        return total;
                    }

                },

            },

        };

        Vue.createApp(app).mount('#app')
    </script>
@endsection