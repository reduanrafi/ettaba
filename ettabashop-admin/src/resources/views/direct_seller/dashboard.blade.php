@extends('admin.layouts.layout')

@section('content')
<section class="content">
    <style>
        .premium-card {
            position: relative;
            display: block;
            border-radius: 12px !important;
            margin-bottom: 20px;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 140px;
        }
        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
        }
        .premium-card .inner {
            padding: 18px 20px;
        }
        .premium-card .inner h3 {
            font-size: 30px;
            font-weight: 700;
            margin: 0 0 6px 0;
            white-space: nowrap;
            padding: 0;
            letter-spacing: -0.5px;
        }
        .premium-card .inner p {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            opacity: 0.9;
            line-height: 1.3;
        }
        .premium-card .icon {
            position: absolute;
            top: auto;
            bottom: 8px;
            right: 18px;
            z-index: 0;
            font-size: 65px;
            color: rgba(255, 255, 255, 0.16) !important;
            transition: all 0.3s ease;
        }
        .premium-card:hover .icon {
            transform: scale(1.1) rotate(-5px);
            color: rgba(255, 255, 255, 0.28) !important;
        }
        .premium-card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.15);
            padding: 4px 0;
            color: rgba(255, 255, 255, 0.8) !important;
            text-align: center;
            z-index: 10;
            display: block;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            transition: background 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .premium-card-footer:hover {
            background: rgba(0, 0, 0, 0.25);
            color: #ffffff !important;
            text-decoration: none;
        }
        .dashboard-header {
            margin-bottom: 25px;
            padding-left: 5px;
        }
        .dashboard-header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #333333;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .section-divider {
            width: 100%;
            margin: 15px 0 25px 0;
            border-top: 1px solid #e0e0e0;
        }
    </style>

    <div class="row">
        <div class="col-xs-12 dashboard-header">
            <h1>Direct Selling Dashboard</h1>
        </div>
        
        <!-- 1. Direct Selling Balance -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$directSellingBalance, 2, '.', '') }} ৳</h3>
                    <p>e-Balance (Direct Selling)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-wallet"></i>
                </div>
                <a href="{{ route('direct-seller.add_money.create') }}" class="premium-card-footer">Add Money <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 2. Today's Customers -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);">
                <div class="inner">
                    <h3>{{ $todayCustomers }}</h3>
                    <p>Today's Customers</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('direct-seller.order.index') }}" class="premium-card-footer">View Sales <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 3. Today's Net Profit -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$todayNetProfit, 2, '.', '') }} ৳</h3>
                    <p>Today's Net Profit</p>
                </div>
                <div class="icon">
                    <i class="fa fa-line-chart"></i>
                </div>
                <a href="{{ route('direct-seller.order.index') }}" class="premium-card-footer">View Sales <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 4. Today's Sales -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #4568dc 0%, #b06ab3 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$todaySales, 2, '.', '') }} ৳</h3>
                    <p>Today's Total Sales</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="{{ route('direct-seller.order.index') }}" class="premium-card-footer">View Sales <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="section-divider"></div>
        </div>
    </div>

    <div class="row">
        <!-- 5. Total Customers -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="inner">
                    <h3>{{ $totalCustomers }}</h3>
                    <p>Total Customers</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('direct-seller.order.index') }}" class="premium-card-footer">View Sales <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 6. Total Net Profit -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #f12711 0%, #f5af19 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$totalNetProfit, 2, '.', '') }} ৳</h3>
                    <p>Total Net Profit</p>
                </div>
                <div class="icon">
                    <i class="fa fa-line-chart"></i>
                </div>
                <a href="{{ route('direct-seller.order.index') }}" class="premium-card-footer">View Sales <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 7. Total Sales -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #833ab4 0%, #fd1d1d 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$totalSales, 2, '.', '') }} ৳</h3>
                    <p>Total Sales</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="{{ route('direct-seller.order.index') }}" class="premium-card-footer">View Sales <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection
