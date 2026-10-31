<!-- Footer Start -->
<footer class="bg-white border-top mt-5 pt-4 pb-3 animate-fade-in" style="position: relative; z-index: 10;">
    <div class="container-fluid px-xl-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 col-md-12 mb-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="mb-3" height="54">
                </a>
                <p class="text-muted mb-3" style="max-width: 350px; font-size: 14px;">
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

            <div class="col-lg-7 col-md-12">
                <div class="row">
                    <div class="col-6 col-md-6 mb-4">
                        <h6 class="font-weight-bold text-dark text-uppercase mb-3" style="letter-spacing: 1px;">Links</h6>
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

                    <div class="col-6 col-md-6 mb-4">
                        <h6 class="font-weight-bold text-dark text-uppercase mb-3" style="letter-spacing: 1px;">
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
                </div>
            </div>
        </div>

        <div class="border-top pt-3 mt-2">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-left">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} <span
                            class="font-weight-bold text-primary">Ettaba Shop</span>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-right mt-2 mt-md-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="20"
                        class="mr-3" style="filter: grayscale(100%); opacity: 0.5;" alt="PayPal">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="15"
                        class="mr-3" style="filter: grayscale(100%); opacity: 0.5;" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20"
                        style="filter: grayscale(100%); opacity: 0.5;" alt="Mastercard">
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
