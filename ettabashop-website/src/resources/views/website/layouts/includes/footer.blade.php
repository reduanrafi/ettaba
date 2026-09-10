<!-- Footer Start -->
<footer class="bg-white border-top mt-5 pt-5 pb-4 animate-fade-in" style="padding-bottom: 100px !important;">
    <div class="container-fluid px-xl-5">
        <div class="row px-xl-5">
            <div class="col-lg-4 col-md-12 mb-5">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="mb-4" height="60">
                </a>
                <p class="text-muted mb-4" style="max-width: 350px;">
                    Buy quality products and earn rewards. Ettaba Shop is committed to providing the best shopping
                    experience in Bangladesh.
                </p>
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle mr-2"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle mr-2"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle mr-2"><i
                            class="fab fa-youtube"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle"><i
                            class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h6 class="font-weight-bold text-dark text-uppercase mb-4" style="letter-spacing: 1px;">Links
                        </h6>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-muted mb-2 font-weight-bold text-decoration-none hover-text-primary"
                                href="{{ route('website.index') }}"><i
                                    class="fa fa-angle-right mr-2"></i>{{ __('menu.home') }}</a>
                            <a class="text-muted mb-2 font-weight-bold text-decoration-none hover-text-primary"
                                href="{{ route('website.shop') }}"><i
                                    class="fa fa-angle-right mr-2"></i>{{ __('menu.shop') }}</a>
                            <a class="text-muted mb-2 font-weight-bold text-decoration-none hover-text-primary"
                                href="{{ route('website.contact') }}"><i
                                    class="fa fa-angle-right mr-2"></i>{{ __('menu.contact') }}</a>
                        </div>
                    </div>

                    <div class="col-md-4 mb-5">
                        <h6 class="font-weight-bold text-dark text-uppercase mb-4" style="letter-spacing: 1px;">
                            Information</h6>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-muted mb-2 font-weight-bold text-decoration-none hover-text-primary"
                                href="{{ route('website.about') }}"><i
                                    class="fa fa-angle-right mr-2"></i>{{ __('menu.about') }}</a>
                            <a class="text-muted mb-2 font-weight-bold text-decoration-none hover-text-primary"
                                href="{{ route('website.terms') }}"><i
                                    class="fa fa-angle-right mr-2"></i>{{ __('menu.terms') }}</a>
                            <a class="text-muted mb-2 font-weight-bold text-decoration-none hover-text-primary"
                                href="{{ route('website.privacy') }}"><i
                                    class="fa fa-angle-right mr-2"></i>{{ __('menu.privacy') }}</a>
                        </div>
                    </div>

                    <div class="col-md-4 mb-5">
                        <h6 class="font-weight-bold text-dark text-uppercase mb-4" style="letter-spacing: 1px;">Contact
                            Us</h6>
                        <div class="mb-3">
                            <p class="text-muted small mb-1"><i class="fa fa-map-marker-alt text-primary mr-2"></i>
                                Address</p>
                            <p class="text-dark font-weight-bold small">Mohammadpur-1207, Dhaka, Bangladesh.</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1"><i class="fa fa-envelope text-primary mr-2"></i> Email</p>
                            <p class="text-dark font-weight-bold small">ettabashop.crm@gmail.com</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1"><i class="fa fa-phone-alt text-primary mr-2"></i> Phone</p>
                            <p class="text-dark font-weight-bold small">019 111 222 52</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-top pt-4 mt-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-left">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} <span
                            class="font-weight-bold text-primary">Ettaba Shop</span>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-right mt-3 mt-md-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="20"
                        class="mr-3 grayscale" alt="PayPal">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="15"
                        class="mr-3 grayscale" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20"
                        class="grayscale" alt="Mastercard">
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .grayscale {
        filter: grayscale(100%);
        opacity: 0.5;
        transition: var(--transition);
    }

    .grayscale:hover {
        filter: grayscale(0%);
        opacity: 1;
    }

    .hover-text-primary:hover {
        color: var(--primary) !important;
        padding-left: 5px;
    }

    footer {
        position: relative;
        z-index: 10;
    }
</style>
<!-- Footer End -->