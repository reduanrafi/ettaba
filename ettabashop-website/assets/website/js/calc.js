const app = {

    data() {
        return {
            message: '',
            rootUrl: (() => {
                let url = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || window.location.origin;
                return url.endsWith('/') ? url : url + '/';
            })(),
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
            username: "",
            phone: "",
            address: "",
            dVirtualBalance: 0,
            virtualBalance: 0,
            vbCount: 0,
            advancePaymentRequired: false,
            showPolicyTooltip: false

        }

    },
    methods: {
        addToCart(product_id, product_name, erp, trp, owner_id, tcb, rate) {
            let found = false;

            itemToAdd = {
                'product_id': product_id,
                'owner_id': owner_id,
                'name': product_name,
                'price': erp,
                'trp': trp,
                'tcb': tcb,
                'rate': rate,
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
            this.checkAdvancePayment();

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

            this.netTotal = this.cartTotal;

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
        checkAdvancePayment() {
            if (this.cartItems.length === 0) {
                this.advancePaymentRequired = false;
                return;
            }
            let productIds = this.cartItems.map(item => item.product_id);
            axios.post(this.rootUrl + "api/check-cart-payment-rules", { product_ids: productIds })
                .then(response => {
                    this.advancePaymentRequired = response.data.advance_payment_required;
                    if (this.advancePaymentRequired) {
                        this.paymentMethodId = 2; // Auto-select EPS (Online Payment)
                    }
                })
                .catch(error => {
                    console.error("Error checking advance payment rules:", error);
                });
        },
        togglePolicyTooltip() {
            if (this.advancePaymentRequired) {
                this.showPolicyTooltip = !this.showPolicyTooltip;
            }
        },
        saveOrder(userId, address, total) {
            if (this.paymentMethodId == 0) {
                this.message = 'Please select a payment method';
            } else {
                let order = {
                    'order': {
                        "user_id": userId,
                        "payment_method_id": this.paymentMethodId,
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


                axios.post(this.rootUrl + "orders/save", order)
                    .then(response => {
                        console.log(response);
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            this.message = response.data.message;
                            this.orderSuccess = true;
                            this.clearCart();
                        }
                    }).catch(error => {
                        console.log(error.response);
                        this.message = error.response?.data?.message || 'Error saving order';
                    });
            }


        },
        saveAnonymousOrder() {
            console.log("test")
            console.log(this.username)
            if (this.paymentMethodId === 0) {
                this.message = 'Please select a payment method';
                console.log(this.message)
                return false;
            } else if (this.username == null || this.username === "") {
                this.message = "You must add the user name";
                return false;
            } else if (this.phone == null || this.phone === "") {
                this.message = "Phone number is required";
                return false;
            } else if (this.address == null || this.address === "") {
                this.message = "Address is required";
                return false;
            } else {
                console.log("else working here")
                let order = {
                    'order': {
                        "payment_method_id": this.paymentMethodId,
                        "address_id": 1,
                        "erp_total": this.cartTotal,
                        "net_total": this.netTotal,
                        "rate_total": this.rateTotal,
                        "trp": this.trp,
                        "tcb": this.tcb,
                        "orderItems": this.cartItems,
                        "username": this.username,
                        "phone": this.phone,
                        "address": this.address

                    }

                }

                console.log(order)


                axios.post(this.rootUrl + "save-anonymous-order", order)
                    .then(response => {
                        console.log(response);
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            this.message = response.data.message;
                            this.orderSuccess = true;
                            this.clearCart();
                        }
                    }).catch(error => {
                        console.log(error.response);
                        this.message = error.response?.data?.message || 'Error saving order';
                    });
            }


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

        changeClass() {

            if (this.rating == 0) {
                this.star1 = this.emptyStar;
                this.star2 = this.emptyStar;
                this.star3 = this.emptyStar;
                this.star4 = this.emptyStar;
                this.star5 = this.emptyStar;
            } else if (this.rating == 1) {
                this.star1 = this.filledStar;
                this.star2 = this.emptyStar;
                this.star3 = this.emptyStar;
                this.star4 = this.emptyStar;
                this.star5 = this.emptyStar;
            } else if (this.rating == 2) {
                this.star1 = this.filledStar;
                this.star2 = this.filledStar;
                this.star3 = this.emptyStar;
                this.star4 = this.emptyStar;
                this.star5 = this.emptyStar;
            } else if (this.rating == 3) {
                this.star1 = this.filledStar;
                this.star2 = this.filledStar;
                this.star3 = this.filledStar;
                this.star4 = this.emptyStar;
                this.star5 = this.emptyStar;
            } else if (this.rating == 4) {
                this.star1 = this.filledStar;
                this.star2 = this.filledStar;
                this.star3 = this.filledStar;
                this.star4 = this.filledStar;
                this.star5 = this.emptyStar;
            } else if (this.rating == 5) {
                this.star1 = this.filledStar;
                this.star2 = this.filledStar;
                this.star3 = this.filledStar;
                this.star4 = this.filledStar;
                this.star5 = this.filledStar;
            }
        }
    },
    mounted: function () {

        if (localStorage.getItem('cartItems') != 'undefined' && localStorage.getItem('cartItems') != null) {

            this.cartItems = JSON.parse(localStorage.getItem('cartItems'));
            this.cartTotal = this.Total['erp'];
            this.trp = this.Total['trp'];
            this.tcb = this.Total['tcb'];
            this.rateTotal = this.Total['rate'];
            this.netTotal = this.cartTotal;
        }
        this.checkAdvancePayment();

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