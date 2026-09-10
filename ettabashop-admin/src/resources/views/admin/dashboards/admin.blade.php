<section class="content">
    <h1>
        Ettaba shop  dashboard

    </h1>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $orders }}</h3>

                    <p>Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3> {{ $earnedMoneyToday }}</h3>

                    <p>Earned Money today </p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3> {{ $earnedPointsToday }}</h3>

                    <p>Earned point today </p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $shops }}<sup style="font-size: 20px"></sup></h3>

                    <p>Shops</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $customers }}</h3>

                    <p>Active customers </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $products }}</h3>

                    <p>Products</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Main Fund</span>
                    <span class="info-box-number"> {{ $funds['mainFund']+ $funds['phishingFund']}}</span>
                </div>

            </div>
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Net profit</span>
                    <span class="info-box-number"> {{ $funds['mainFund']}}</span>
                </div>

            </div>
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Phising Fund</span>
                    <span class="info-box-number"> {{ $funds['phishingFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Incentive Fund</span>
                    <span class="info-box-number"> {{ $funds['incentiveFund'] }}</span>
                </div>

            </div>

        </div>

        {{--            <div class="col-md-2 col-sm-6 col-xs-12">--}}
        {{--                <div class="info-box">--}}
        {{--                    <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>--}}
        {{--                    <div class="info-box-content">--}}
        {{--                        <span class="info-box-text">Main Fund</span>--}}
        {{--                        <span class="info-box-number"> {{ $funds['mainFund'] }}</span>--}}
        {{--                    </div>--}}

        {{--                </div>--}}

        {{--            </div>--}}

        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Executive Fund</span>
                    <span class="info-box-number"> {{ $funds['executiveFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Daily Fund  </span>
                    <span class="info-box-number"> {{ $funds['dailyFund'] }}</span>
                </div>

            </div>

        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">weeklyFund Fund</span>
                    <span class="info-box-number"> {{ $funds['weeklyFund'] }}</span>
                </div>

            </div>

        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">vendorFund Fund</span>
                    <span class="info-box-number"> {{ $funds['vendorFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">LocalOffice  Fund</span>
                    <span class="info-box-number"> {{ $funds['localOfficeFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Up   Fund</span>
                    <span class="info-box-number"> {{ $funds['upFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">District  Fund</span>
                    <span class="info-box-number"> {{ $funds['districtFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Division  Fund</span>
                    <span class="info-box-number"> {{ $funds['divisionFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Marketing  Fund</span>
                    <span class="info-box-number"> {{ $funds['marketingFund'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total withdraw</span>
                    <span class="info-box-number"> {{ $funds['totalWithdraw'] }}</span>
                </div>

            </div>

        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-1x">৳</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Withdraw Today</span>
                    <span class="info-box-number"> {{ $funds['todayWithdraw'] }}</span>
                </div>

            </div>
        </div>


    </div>
</section>
