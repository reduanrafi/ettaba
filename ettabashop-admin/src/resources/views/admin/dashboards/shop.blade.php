<style>
    .seller-dash-wrap {
        padding: 10px 5px 25px;
    }
    .seller-dash-heading {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 16px 0;
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
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        border: 1px solid #172554;
        border-radius: 12px;
        padding: 14px 10px;
        margin-bottom: 14px;
        color: #ffffff !important;
        text-decoration: none !important;
        box-shadow: 0 4px 8px rgba(30, 58, 138, 0.15);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        min-height: 88px;
    }
    .seller-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(30, 58, 138, 0.25);
        color: #ffffff !important;
    }
    .seller-metric-title {
        font-size: 13px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 5px;
        line-height: 1.25;
    }
    .seller-metric-val {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
    }
</style>

<section class="content seller-dash-wrap">
    @php
        $unseenSellerOrders = 0;
        try {
            $unseenSellerOrders = \App\Http\Controllers\Shopper\SellerOrderController::getUnseenOrderCountForSeller(\Illuminate\Support\Facades\Auth::id());
        } catch (\Throwable $e) {
            $unseenSellerOrders = 0;
        }

        // Seller Reward resolution (Pages 3 & 7 spec)
        $sellerPoints = floatval($totalPoint ?? 0);
        $rewardService = app(\App\Services\RewardService::class);
        $sellerNextReward = null;
        try {
            $sellerNextReward = $rewardService->getNextRewardForUser(Auth::user());
        } catch (\Throwable $e) {
            $sellerNextReward = null;
        }

        $targetPoints = isset($sellerNextReward) && $sellerNextReward->required_points > 0 ? floatval($sellerNextReward->required_points) : 1000;
        $neededPoints = max(0, $targetPoints - $sellerPoints);
        $progressPercent = $targetPoints > 0 ? min(100, round(($sellerPoints / $targetPoints) * 100, 1)) : 0;
        $rewardName = isset($sellerNextReward) && !empty($sellerNextReward->reward_name) ? $sellerNextReward->reward_name : 'একটি আকর্ষণীয় মগ';
        $rewardImg = isset($sellerNextReward) && !empty($sellerNextReward->image) ? $sellerNextReward->image : null;
    @endphp

    <div class="seller-dash-heading">
        <span><i class="fa fa-dashboard text-primary"></i> Seller Dashboard</span>
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('seller.orders.index') }}" class="btn btn-success btn-sm" style="font-weight: 700; border-radius: 6px;">
                <i class="fa fa-shopping-bag"></i> My Orders
            </a>
            @if($unseenSellerOrders > 0)
                <span class="badge bg-red" style="font-size: 13px; padding: 5px 10px; border-radius: 12px;">{{ $unseenSellerOrders }} New</span>
            @endif
        </div>
    </div>

    {{-- Row 1: Balance & Reward --}}
    <div class="row seller-dash-row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ !empty($isSelfDelivery) ? route('seller.addMoney.index') : route('mywithdraws') }}" class="seller-metric-card">
                <div class="seller-metric-title">Seller e-Balance</div>
                <div class="seller-metric-val">৳{{ number_format((float) ($eBalance ?? 0), 2) }}</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
            <a href="{{ route('seller.orders.index') }}" class="seller-metric-card" style="{{ (!empty($isSelfDelivery) && ($pendingBalance ?? 0) < 0) ? 'background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);' : '' }}">
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

    {{-- Seller Next Reward Card (Page 3 spec) --}}
    <div class="box box-solid" style="border-radius: 12px; border: 1px solid #fed7aa; background: #fff; margin-bottom: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <div class="box-body" style="padding: 12px 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #fee2e2; padding-bottom: 6px; margin-bottom: 10px;">
                <div>
                    <span style="font-size: 15px; font-weight: 800; color: #dc2626;"><i class="fa fa-gift"></i> আপনার পরবর্তী পুরস্কার</span>
                    <span style="font-size: 13.5px; font-weight: 700; color: #166534; margin-left: 8px;">পাবেন: {{ $rewardName }}</span>
                </div>
                <a href="#" style="font-size: 12px; font-weight: 600; color: #15803d; text-decoration: none;">
                    সকল পুরস্কার দেখুন &rarr;
                </a>
            </div>

            <div class="row text-center" style="margin-bottom: 8px;">
                <div class="col-xs-4" style="border-right: 1px solid #f1f5f9;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">মোট পয়েন্ট</div>
                    <div style="font-size: 14px; font-weight: 800; color: #dc2626;">{{ number_format($sellerPoints, 2) }}</div>
                </div>
                <div class="col-xs-4" style="border-right: 1px solid #f1f5f9;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">পরবর্তী পুরস্কার পেতে</div>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ number_format($neededPoints, 2) }} <small style="font-size: 10px;">পয়েন্ট বাকি</small></div>
                </div>
                <div class="col-xs-4">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">পরবর্তী পুরস্কার পাবেন</div>
                    <div style="font-size: 14px; font-weight: 800; color: #15803d;">{{ number_format($targetPoints, 0) }} পয়েন্ট-এ</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress" style="height: 7px; border-radius: 6px; background: #e2e8f0; margin-bottom: 6px;">
                <div class="progress-bar" style="width: {{ max(4, $progressPercent) }}%; background: linear-gradient(90deg, #dc2626 0%, #ea580c 100%);"></div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 10.5px; font-weight: 700; color: #64748b; margin-bottom: 8px;">
                <span class="text-danger">{{ number_format($sellerPoints, 2) }}</span>
                <span>{{ number_format($targetPoints, 0) }}</span>
            </div>

            <!-- Motivational Message -->
            <div style="background: #f8fafc; border-radius: 6px; padding: 6px 10px; border: 1px solid #e2e8f0; display: flex; align-items: center;">
                <i class="fa fa-line-chart text-success" style="font-size: 16px; margin-right: 8px;"></i>
                <span style="font-size: 12px; font-weight: 700; color: #1e293b;">যত বেশি পয়েন্ট ও বিক্রি, তত বড় পুরস্কার!</span>
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
