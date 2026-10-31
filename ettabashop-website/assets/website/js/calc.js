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
            deliveryCharge: 0,
            detailQty: 1,
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
        changeDetailQty(delta) {
            let next = parseInt(this.detailQty || 1, 10) + parseInt(delta || 0, 10);
            if (isNaN(next) || next < 1) next = 1;
            this.detailQty = next;
            let input = document.getElementById('detailQtyInput');
            if (input) input.value = next;
        },

        addToCart(product_id, product_name, erp, trp, owner_id, tcb, rate, qty = 1) {
            let addQty = parseInt(qty || 1, 10);
            if (isNaN(addQty) || addQty < 1) {
                let inputEl = document.getElementById('detailQtyInput');
                addQty = inputEl ? Math.max(1, parseInt(inputEl.value || 1, 10)) : 1;
            }

            let itemToAdd = {
                'product_id': String(product_id),
                'owner_id': owner_id,
                'name': product_name,
                'price': parseFloat(erp || 0),
                'trp': parseFloat(trp || 0),
                'tcb': parseFloat(tcb || 0),
                'rate': parseFloat(rate || 0),
                'quantity': addQty
            };

            let itemInCart = this.cartItems.filter(item => String(item.product_id) === String(itemToAdd.product_id));
            let isItemInCart = itemInCart.length > 0;

            if (isItemInCart === false) {
                this.cartItems.push(itemToAdd);
            } else {
                itemInCart[0].quantity = parseInt(itemInCart[0].quantity || 0, 10) + itemToAdd.quantity;
            }

            localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
            this.setTotal(this.calculateTotal());
            this.checkAdvancePayment();
        },

        decrementItem(index) {
            if (!this.cartItems[index]) return;
            let currentQty = parseInt(this.cartItems[index].quantity || 1, 10);
            if (currentQty > 1) {
                this.cartItems[index].quantity = currentQty - 1;
                localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
                this.setTotal(this.calculateTotal());
                this.checkAdvancePayment();
            } else {
                this.removeItem(index);
            }
        },

        incrementItem(index) {
            if (!this.cartItems[index]) return;
            let currentQty = parseInt(this.cartItems[index].quantity || 1, 10);
            this.cartItems[index].quantity = currentQty + 1;
            localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
            this.setTotal(this.calculateTotal());
        },

        removeItem(index) {
            this.cartItems.splice(index, 1);
            localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
            this.setTotal(this.calculateTotal());
            this.checkAdvancePayment();
        },

        clearCart() {
            this.cartItems = [];
            localStorage.removeItem('cartItems');
            this.cartTotal = 0;
            this.netTotal = 0;
            this.trp = 0;
            this.tcb = 0;
            this.rateTotal = 0;
        },

        calculateTotal() {
            let raw = localStorage.getItem('cartItems');
            let items = (raw && raw !== 'undefined') ? JSON.parse(raw) : [];
            let total = [];

            total['erp'] = 0;
            total['trp'] = 0;
            total['tcb'] = 0;
            total['rate'] = 0;

            items.forEach(item => {
                let q = parseInt(item.quantity || 1, 10);
                total['erp'] += parseFloat(item.price || 0) * q;
                total['trp'] += parseFloat(item.trp || 0) * q;
                total['tcb'] += parseFloat(item.tcb || 0) * q;
                total['rate'] += parseFloat(item.rate || 0) * q;
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
            let raw = localStorage.getItem('cartItems');
            let items = (raw && raw !== 'undefined') ? JSON.parse(raw) : [];
            let totalTrp = 0;
            items.forEach(item => {
                totalTrp += parseFloat(item.trp || 0) * parseInt(item.quantity || 1, 10);
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
                };

                axios.post(this.rootUrl + "orders/save", order)
                    .then(response => {
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            this.message = response.data.message;
                            this.orderSuccess = true;
                            this.clearCart();
                        }
                    }).catch(error => {
                        this.message = error.response?.data?.message || 'Error saving order';
                    });
            }
        },

        saveAnonymousOrder() {
            if (this.paymentMethodId === 0) {
                this.message = 'Please select a payment method';
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
                };

                axios.post(this.rootUrl + "save-anonymous-order", order)
                    .then(response => {
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            this.message = response.data.message;
                            this.orderSuccess = true;
                            this.clearCart();
                        }
                    }).catch(error => {
                        this.message = error.response?.data?.message || 'Error saving order';
                    });
            }
        },

        timeSince: function (date) {
            if (date != undefined) {
                var seconds = Math.floor((new Date() - this.sqlToJS(date)) / 1000);
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
            const toBn = n => String(n).replace(/\d/g, d => "০১২৩৪৫৬৭৮৯"[d]);
            return toBn(n);
        },

        ratingUpdate(rating) {
            this.rating = rating;
            this.changeClass(rating);
            let ratingInput = document.getElementById('reviewRatingInput');
            if (ratingInput) ratingInput.value = rating;
        },

        saveRating() {
            let form = document.getElementById('productReviewForm');
            if (form) {
                form.submit();
            }
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

        let existingRatingEl = document.getElementById('reviewRatingInput');
        if (existingRatingEl && parseInt(existingRatingEl.value || 0, 10) > 0) {
            this.ratingUpdate(parseInt(existingRatingEl.value, 10));
        }
    },
    computed: {
        Total() {
            if (localStorage.getItem('cartItems') != 'undefined' && localStorage.getItem('cartItems') != null) {
                let items = JSON.parse(localStorage.getItem('cartItems'));

                let total = [];

                total['erp'] = 0;
                total['trp'] = 0;
                total['tcb'] = 0;
                total['rate'] = 0;

                items.forEach(item => {
                    let q = parseInt(item.quantity || 1, 10);
                    total['erp'] += parseFloat(item.price || 0) * q;
                    total['trp'] += parseFloat(item.trp || 0) * q;
                    total['tcb'] += parseFloat(item.tcb || 0) * q;
                    total['rate'] += parseFloat(item.rate || 0) * q;
                });

                return total;
            }
        },
    },
};

Vue.createApp(app).mount('#app');
