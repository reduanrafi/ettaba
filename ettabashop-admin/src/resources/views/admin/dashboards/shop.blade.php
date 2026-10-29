<style>
    .seller-dash-wrap {
        padding: 10px 5px 25px;
    }
    .seller-dash-heading {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 18px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .seller-dash-row {
        margin-bottom: 6px;
    }
    .seller-metric-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: linear-gradient(135deg, #4472c4 0%, #2f5597 100%);
        border: 2px solid #1f3864;
        border-radius: 14px;
        padding: 16px 10px;
        margin-bottom: 14px;
        color: #ffffff !important;
        text-decoration: none !important;
        box-shadow: 0 4px 10px rgba(31, 56, 100, 0.18);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        min-height: 92px;
    }
    .seller-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(31, 56, 100, 0.28);
        color: #ffffff !important;
    }
    .seller-metric-title {
        font-size: 13.5px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 6px;
        line-height: 1.25;
    }
    .seller-metric-val {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
    }
</style>

<section class="content seller-dash-wrap">
    <div class="seller-dash-heading">
        <span><i class="fa fa-dashboard text-primary"></i> Online Seller Dashboard</span>
        <a href="{{ route('seller.orders.index') }}" class="btn btn-success btn-sm">
            <i class="fa fa-shopping-bag"></i> অর্ডার পরিচালনা করুন (My Orders)
        </a>
    </div>

    {{-- Row 1: Balance & Reward --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('mywithdraws') }}" class="seller-metric-card">
                <div class="seller-metric-title">Seller e-Balance</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($eBalance ?? 0), 2) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Pending Balance</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($pendingBalance ?? 0), 2) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Total Reward</div>
                <div class="seller-metric-val">{{ number_format((float) ($totalPoint ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Pending Reward</div>
                <div class="seller-metric-val">{{ number_format((float) ($pendingPoint ?? 0), 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Row 2: Today's Overview --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Today's Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($todayOrders ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Today's Delivered</div>
                <div class="seller-metric-val">{{ number_format((int) ($todayDelivered ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Today's Earned</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($earnedMoneyToday ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Today's Sale</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($todaySale ?? 0), 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Row 3: Earned Breakdown (Weekly, Monthly, Yearly, Total) --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Weekly Earned</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($weeklyEarned ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Monthly Earned</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($monthlyEarned ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Yearly Earned</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($yearlyEarned ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Total Earned</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($totalEarned ?? 0), 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Row 4: Orders Breakdown (Weekly, Monthly, Yearly, Total) --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Weekly Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($weeklyOrders ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Monthly Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($monthlyOrders ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Yearly Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($yearlyOrders ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Total Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($totalOrders ?? 0)) }}</div>
            </a>
        </div>
    </div>

    {{-- Row 5: Delivered Breakdown (Weekly, Monthly, Yearly, Total) --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Weekly Delivered</div>
                <div class="seller-metric-val">{{ number_format((int) ($weeklyDelivered ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Monthly Delivered</div>
                <div class="seller-metric-val">{{ number_format((int) ($monthlyDelivered ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Yearly Delivered</div>
                <div class="seller-metric-val">{{ number_format((int) ($yearlyDelivered ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Total Delivered</div>
                <div class="seller-metric-val">{{ number_format((int) ($totalDelivered ?? 0)) }}</div>
            </a>
        </div>
    </div>

    {{-- Row 6: Sale Breakdown (Weekly, Monthly, Yearly, Total ERP of Delivered Orders) --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Weekly Sale</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($weeklySale ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Monthly Sale</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($monthlySale ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Yearly Sale</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($yearlySale ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="seller-metric-card">
                <div class="seller-metric-title">Total Sale</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($totalSale ?? 0), 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Row 7: Withdrawals & Cancel Orders --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('mywithdraws') }}" class="seller-metric-card">
                <div class="seller-metric-title">Today's Withdrawal</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($todayWithdrawal ?? 0), 2) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('mywithdraws') }}" class="seller-metric-card">
                <div class="seller-metric-title">Total Withdrawal</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($totalWithdraw ?? 0), 2) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'canceled']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Today's Cancel Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($todayCancelOrders ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index', ['status' => 'canceled']) }}" class="seller-metric-card">
                <div class="seller-metric-title">Total Cancel Orders</div>
                <div class="seller-metric-val">{{ number_format((int) ($totalCancelOrders ?? 0)) }}</div>
            </a>
        </div>
    </div>

    {{-- Row 8: Product & Inventory Metrics --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('product.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Total Products</div>
                <div class="seller-metric-val">{{ number_format((int) ($totalProducts ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('product.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Stock Short (&lt;10)</div>
                <div class="seller-metric-val">{{ number_format((int) ($stockShort ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('product.index') }}" class="seller-metric-card">
                <div class="seller-metric-title">Stock Out</div>
                <div class="seller-metric-val">{{ number_format((int) ($stockOut ?? 0)) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('product.deleted') }}" class="seller-metric-card">
                <div class="seller-metric-title">Hide Products</div>
                <div class="seller-metric-val">{{ number_format((int) ($hideProducts ?? 0)) }}</div>
            </a>
        </div>
    </div>
</section>
