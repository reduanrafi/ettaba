<div class="card">
    <div class="card-body">
        
        @if(isset($message) && !empty($message))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Top Section: Partner Info -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <div style="width: 130px; height: 130px; border-radius: 50%; background-color: #4a72c1; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 0 auto; overflow: hidden; border: 3px solid #2854a1; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    @if(isset($activeProfile) && $activeProfile->profile_image)
                        <img src="{{ asset($activeProfile->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Partner Image">
                    @else
                        <span style="font-size: 16px; font-weight: bold;">পার্টনারের<br>ছবি</span>
                    @endif
                </div>
            </div>
            
            <div class="col-md-9">
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #e0e0e0; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <div style="border-bottom: 2px solid #4a72c1; padding-bottom: 12px; margin-bottom: 15px;">
                        <div class="d-block d-md-inline-block">
                            <span style="background-color: #4a72c1; color: white; padding: 6px 20px; border-radius: 20px; font-size: 16px; font-weight: bold; display: inline-block; border: 2px solid #2854a1;">Your Team & Tree</span>
                        </div>
                        <div class="d-block d-md-inline-block float-md-right mt-2 mt-md-0">
                            <span style="font-size: 14px; font-weight: bold; color: #4a72c1; background: #e2e8f4; padding: 6px 15px; border-radius: 5px; display: inline-block;">Refer: <span style="color: #333;">{{ $referrer ? $referrer->name . '-' . $referrer->unique_id : 'N/A' }}</span></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%; font-size: 15px;">
                                <tr>
                                    <td style="padding: 3px 0; color: #555;">Partner Name: <b style="color: #333;">{{ $activeUser->name }}</b></td>
                                </tr>
                                <tr>
                                    <td style="padding: 3px 0; color: #555;">eID/Card No: <b style="color: #333;">{{ $activeUser->unique_id }}</b></td>
                                </tr>
                                <tr>
                                    <td style="padding: 3px 0; color: #555;">Mobile: <b style="color: #333;">{{ $activeUser->phone }}</b></td>
                                </tr>
                                <tr>
                                    <td style="padding: 3px 0; color: #555;">Address: <b style="color: #333;">{{ $activeProfile ? $activeProfile->address : '' }}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4 pt-3" style="border-top: 1px dashed #ccc; font-weight: bold; font-size: 15px;">
                        <div class="col-12 col-md-6 mb-2">
                            <span style="color: #666;">Total Earned:</span> <span style="color: #28a745;">{{ $activeEarnings['totalEarning'] + $activeEarnings['totalWithdraw'] }}</span>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <span style="color: #666;">Total Reward:</span> <span style="color: #17a2b8;">{{ $activeEarnings['totalPoint'] }}</span>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <span style="color: #666;">Total Partners:</span> <span style="color: #4a72c1;">{{ $partners->count() }}</span>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <span style="color: #666;">Total Customers:</span> <span style="color: #e83e8c;">{{ $customers->count() }}</span>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <span style="color: #666;">Total Merchants:</span> <span style="color: #fd7e14;">{{ $merchants->count() }}</span>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <span style="color: #666;">Total Withdraw:</span> <span style="color: #dc3545;">{{ $activeEarnings['totalWithdraw'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Section: Tabs & Search -->
        <div class="row mb-3">
            <div class="col-md-8">
                <ul class="nav nav-pills" id="teamTreeTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="partner-tab" data-toggle="pill" href="#partner" role="tab" style="background-color: #4a72c1; color: white; border-radius: 5px; margin-right: 5px; padding: 8px 25px; border: 1px solid #2854a1;">Partner</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customers-tab" data-toggle="pill" href="#customers" role="tab" style="background-color: #4a72c1; color: white; border-radius: 5px; margin-right: 5px; padding: 8px 25px; border: 1px solid #2854a1;">Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="merchants-tab" data-toggle="pill" href="#merchants" role="tab" style="background-color: #4a72c1; color: white; border-radius: 5px; margin-right: 5px; padding: 8px 25px; border: 1px solid #2854a1;">Merchants</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <form action="{{ route('profile.teamTree') }}" method="GET">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="background-color: #4a72c1; color: white; border: 1px solid #2854a1;">Search :</span>
                        </div>
                        <input type="text" name="uid" class="form-control" placeholder="Enter eID (e.g. 13...)" required style="border: 1px solid #4a72c1;">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" style="background-color: #4a72c1; border: 1px solid #2854a1;"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bottom Section: Tables -->
        <div class="tab-content" id="teamTreeTabsContent">
            <!-- Partners Tab -->
            <div class="tab-pane fade show active" id="partner" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead style="background-color: #f4f4f4;">
                            <tr>
                                <th>LS</th>
                                <th>Partner Name</th>
                                <th>eID</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Total<br>Earned</th>
                                <th>Total<br>Reward</th>
                                <th>Total<br>Partners</th>
                                <th>Total<br>Customers</th>
                                <th>Total<br>Merchants</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($partners as $index => $partner)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('profile.teamTree', ['uid' => $partner->unique_id]) }}">{{ $partner->name }}</a></td>
                                <td>{{ $partner->unique_id }}</td>
                                <td>{{ $partner->phone }}</td>
                                <td>{{ $partner->profile ? $partner->profile->address : '' }}</td>
                                <td>{{ $partner->total_earned }}</td>
                                <td>{{ $partner->total_reward }}</td>
                                <td>{{ $partner->total_partners }}</td>
                                <td>{{ $partner->total_customers }}</td>
                                <td>{{ $partner->total_merchants }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">No Partners Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customers Tab -->
            <div class="tab-pane fade" id="customers" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead style="background-color: #f4f4f4;">
                            <tr>
                                <th>LS</th>
                                <th>Customer Name</th>
                                <th>eID</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Total<br>Earned</th>
                                <th>Total<br>Reward</th>
                                <th>Total<br>Partners</th>
                                <th>Total<br>Customers</th>
                                <th>Total<br>Merchants</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->unique_id }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->profile ? $customer->profile->address : '' }}</td>
                                <td>{{ $customer->total_earned }}</td>
                                <td>{{ $customer->total_reward }}</td>
                                <td>{{ $customer->total_partners }}</td>
                                <td>{{ $customer->total_customers }}</td>
                                <td>{{ $customer->total_merchants }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">No Customers Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Merchants Tab -->
            <div class="tab-pane fade" id="merchants" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead style="background-color: #f4f4f4;">
                            <tr>
                                <th>LS</th>
                                <th>Merchant Name</th>
                                <th>mID</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Total<br>Earned</th>
                                <th>Total<br>Reward</th>
                                <th>Total<br>Partners</th>
                                <th>Total<br>Customers</th>
                                <th>Total<br>Merchants</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($merchants as $index => $merchant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $merchant->name }}</td>
                                <td>{{ $merchant->merchant_id }}</td>
                                <td>{{ $merchant->phone }}</td>
                                <td>{{ $merchant->profile ? $merchant->profile->address : '' }}</td>
                                <td>{{ $merchant->total_earned }}</td>
                                <td>{{ $merchant->total_reward }}</td>
                                <td>{{ $merchant->total_partners }}</td>
                                <td>{{ $merchant->total_customers }}</td>
                                <td>{{ $merchant->total_merchants }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">No Merchants Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .nav-pills .nav-link:not(.active) {
        background-color: transparent !important;
        color: #4a72c1 !important;
    }
    .nav-pills .nav-link.active {
        background-color: #4a72c1 !important;
        color: white !important;
    }
</style>
