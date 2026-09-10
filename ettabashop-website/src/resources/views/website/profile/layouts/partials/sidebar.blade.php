<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle"
                 src="{{ isset($profile->profile_image)?asset($profile->profile_image):asset('assets/images/user.png')}}"
                 alt="User profile picture">
        </div>

        <h4 class="profile-username text-center p-2">{{isset($profile->first_name)?$profile->first_name:'' }} {{isset($profile->last_name)?$profile->last_name:'' }}</h4>
        <h4 class="profile-username text-center p-2" style="font-family: 'Times New Roman';font-weight: bold">eID:- {{ Auth::user()->referral_code }}</h4>
        @if(isset($profile->created_at))
            <p class="text-muted text-center">created at {{ date('d M Y',strtotime($profile->created_at)) }}</p>
        @endif

        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <a href="{{route('website.index')}}">
                    <b>eBalance</b> <span class="float-right text-primary">{{ $earnings['totalEarning'] }}</span>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{route('website.index')}}">
                    <b>Total Earned</b> <span
                            class="float-right text-primary">{{ $earnings['totalEarning'] +$earnings['totalWithdraw']  }} </span>
                </a>
            </li>
            <li class="list-group-item">
                <a href="#">
                    <b>Earned Histories</b>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{route('website.index')}}">
                    <b>Total Reward Earned</b> <span class="float-right text-primary">{{ $earnings['totalPoint'] }}</span>
                </a>
            </li>
            <li class="list-group-item">
                <a href="#">
                    <b>Total Reward Used</b>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('profile.teamTree') }}">
                    <b>My Team & Tree</b>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('profile.withdrawRequests') }}">
                    <b>Withdraw Requests</b> <span
                            class="float-right text-primary">{{ $earnings['totalWithdraw'] }}</span>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('profile.withdrawHistories') }}">
                    <b>Withdraw Histories</b> <span
                            class="float-right text-primary">{{ $earnings['totalWithdraw'] }}</span>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('profile.order') }}">
                    <b>Online Orders Histories</b> <span class="float-right text-primary"></span>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('profile.handcash.order') }}">
                    <b>Merchant Cashback Hist.</b> <span class="float-right text-primary"></span>
                </a>
            </li>
            @if(\Illuminate\Support\Facades\Auth::user()->customer_type=='buy_earn')
                <li class="list-group-item">
                    <a href="{{ route('profile.trainings') }}">
                        <b>My Trainings</b> <span class="float-right text-primary"></span>
                    </a>
                </li>
            @endif
            <li class="list-group-item">
                <a href="https://forms.gle/LfUibtvMfJSAsRjP7" target="_blank">
                    <b>Partner Application</b>
                </a>
            </li>
        </ul>


    </div>
    <!-- /.card-body -->
</div>
