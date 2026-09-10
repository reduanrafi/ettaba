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
            <h1>Local Merchant Dashboard</h1>
        </div>
        
        <!-- 1. Current e-Balance -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$currentEBalance, 2, '.', '') }}</h3>
                    <p>Current e-Balance</p>
                </div>
                <div class="icon">
                    <i class="ion ion-wallet"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 2. Today Received Customer -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);">
                <div class="inner">
                    <h3>{{ $todayReceivedCustomer }}</h3>
                    <p>Today Received Customer</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 3. Today Merchant Rewards -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$todayMerchantRewards, 2, '.', '') }}</h3>
                    <p>Today Merchant Rewards</p>
                </div>
                <div class="icon">
                    <i class="fa fa-star"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 4. Today Withdrawal -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #4568dc 0%, #b06ab3 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$todayWithdrawal, 2, '.', '') }}</h3>
                    <p>Today Withdrawal</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bank"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="section-divider"></div>
        </div>
    </div>

    <div class="row">
        <!-- 5. Total Received Customer -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="inner">
                    <h3>{{ $totalReceivedCustomer }}</h3>
                    <p>Total Received Customer</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 6. Total Merchant Rewards -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #f12711 0%, #f5af19 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$totalMerchantRewards, 2, '.', '') }}</h3>
                    <p>Total Merchant Rewards</p>
                </div>
                <div class="icon">
                    <i class="fa fa-star"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 7. Total Withdrawal -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #833ab4 0%, #fd1d1d 100%);">
                <div class="inner">
                    <h3>{{ number_format((float)$totalWithdrawal, 2, '.', '') }}</h3>
                    <p>Total Withdrawal</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bank"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 8. Active Counter -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="premium-card" style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);">
                <div class="inner">
                    <h3>{{ $activeCounter }}</h3>
                    <p>Active Counter</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <a href="#" class="premium-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</section>
