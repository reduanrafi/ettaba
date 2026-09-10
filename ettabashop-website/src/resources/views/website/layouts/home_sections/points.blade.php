<!-- Points Stats Section -->
<div class="row no-gutters">
    <!-- E-Balance -->
    <div class="col-lg-2 col-md-4 col-6 p-1">
        <div class="d-flex align-items-center bg-white border rounded p-2 h-100 shadow-sm">
            <div class="bg-primary-light rounded-circle p-2 mr-2">
                <i class="fas fa-wallet text-primary small"></i>
            </div>
            <div class="lh-1">
                <p class="small text-muted m-0" style="font-size: 0.65rem;">{{ __("point.eBalance") }}</p>
                <h6 class="font-weight-bold m-0 text-success small">{{ number_format($statistics['totalEarning'] ?? 0, 2) }} <span class="small">TK</span></h6>
            </div>
        </div>
    </div>

    <!-- Pending Earning -->
    <div class="col-lg-2 col-md-4 col-6 p-1">
        <div class="d-flex align-items-center bg-white border rounded p-2 h-100 shadow-sm">
            <div class="bg-primary-light rounded-circle p-2 mr-2">
                <i class="fa fa-clock text-primary small"></i>
            </div>
            <div class="lh-1">
                <p class="small text-muted m-0" style="font-size: 0.65rem;">{{ __("point.pendingEarning") }}</p>
                <h6 class="font-weight-bold m-0 text-warning small">{{ number_format($statistics['pendingEarning'] ?? 0, 2) }} <span class="small">TK</span></h6>
            </div>
        </div>
    </div>

    <!-- Total Point -->
    <div class="col-lg-2 col-md-4 col-6 p-1">
        <div class="d-flex align-items-center bg-white border rounded p-2 h-100 shadow-sm">
            <div class="bg-primary-light rounded-circle p-2 mr-2">
                <i class="fa fa-trophy text-primary small"></i>
            </div>
            <div class="lh-1">
                <p class="small text-muted m-0" style="font-size: 0.65rem;">{{ __("point.totalPoint") }}</p>
                <h6 class="font-weight-bold m-0 text-primary small">{{ number_format($statistics['totalPoint'] ?? 0, 2) }}</h6>
            </div>
        </div>
    </div>

    <!-- Pending Point -->
    <div class="col-lg-2 col-md-4 col-6 p-1">
        <div class="d-flex align-items-center bg-white border rounded p-2 h-100 shadow-sm">
            <div class="bg-primary-light rounded-circle p-2 mr-2">
                <i class="fa fa-hourglass-half text-primary small"></i>
            </div>
            <div class="lh-1">
                <p class="small text-muted m-0" style="font-size: 0.65rem;">{{ __("point.pendingPoint") }}</p>
                <h6 class="font-weight-bold m-0 text-muted small">{{ number_format($statistics['pendingPoint'] ?? 0, 2) }}</h6>
            </div>
        </div>
    </div>

    <!-- Total Earning (Income) -->
    <div class="col-lg-2 col-md-4 col-6 p-1">
        <div class="d-flex align-items-center bg-white border rounded p-2 h-100 shadow-sm">
            <div class="bg-primary-light rounded-circle p-2 mr-2">
                <i class="fa fa-hand-holding-usd text-primary small"></i>
            </div>
            <div class="lh-1">
                <p class="small text-muted m-0" style="font-size: 0.65rem;">{{ __("point.totalEarning") }}</p>
                <h6 class="font-weight-bold m-0 text-info small">{{ number_format(($statistics['totalEarning'] ?? 0) + ($statistics['totalWithdraw'] ?? 0), 2) }} <span class="small">TK</span></h6>
            </div>
        </div>
    </div>

    <!-- Total Withdraw -->
    <div class="col-lg-2 col-md-4 col-6 p-1">
        <div class="d-flex align-items-center bg-white border rounded p-2 h-100 shadow-sm">
            <div class="bg-primary-light rounded-circle p-2 mr-2">
                <i class="fa fa-external-link-alt text-primary small"></i>
            </div>
            <div class="lh-1">
                <p class="small text-muted m-0" style="font-size: 0.65rem;">{{ __("point.totalWithdraw") }}</p>
                <h6 class="font-weight-bold m-0 text-danger small">{{ number_format($statistics['totalWithdraw'] ?? 0, 2) }} <span class="small">TK</span></h6>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-light { background: rgba(99, 102, 241, 0.1); }
    .lh-1 { line-height: 1.1; }
</style>
<!-- Featured End -->
