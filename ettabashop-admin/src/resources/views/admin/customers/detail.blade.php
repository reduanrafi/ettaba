@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="col-md-12 " id="messageDiv">
            @if(Session::has('success'))
                @include('admin.layouts.message.success')
            @elseif(Session::has('error'))
                @include('admin.layouts.message.error')
            @endif
        </div>
        <div class="row">
            @if($customer->profile!=null)
                <div class="col-md-4">

                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{ asset(str_replace('http://localhost:81/ecom/', '', $customer->profile->profile_image)) }}" onerror="this.src='{{ asset('assets/images/nophoto.png') }}'" alt="User profile picture">
                        <h3 class="profile-username text-center">{{ $customer->profile->first_name }} {{ $customer->profile->last_name }}</h3>
                        <p class="text-muted text-center">{{ $customer->unique_id }}</p>

                        <!-- Customer QR Code -->
                        @php
                            // Get base UI from config or fallback, and strip trailing slash if present
                            $baseUrl = rtrim(env('FRONTEND_URL', url('/')), '/');
                            $qrUrl = $baseUrl . '/ENC/' . $customer->unique_id;
                        @endphp
                        <div class="text-center" style="margin-bottom: 20px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code">
                            <br><small style="font-size:10px;"><a href="{{ $qrUrl }}" target="_blank">{{ $qrUrl }}</a></small>
                            <br><small><strong>Print this for Card</strong></small>
                        </div>
                        
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>NID</b> <a class="pull-right">{{ $customer->profile->nid }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Mobile </b> <a class="pull-right">{{ $customer->phone }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Birth Date</b> <a class="pull-right">{{ $customer->profile->date_of_birth }}</a>
                            </li>
                        </ul>

                        <a href="{{ route('blockedUser.checkNID',['nid'=>$customer->profile->nid]) }}" class="btn btn-danger btn-block"><b>Check NID</b></a>

                        <a href="{{ route('customer.changeStatus',['id'=>$customer->id,'status'=>'approve']) }}" class="btn btn-primary btn-block"><b>Approve</b></a>
                        
                        @if($customer->merchant_search_access)
                            <a href="{{ route('customer.changeSearchAccess',['id'=>$customer->id,'status'=>0]) }}" class="btn btn-warning btn-block"><b>Disable Merchant Search Access</b></a>
                        @else
                            <a href="{{ route('customer.changeSearchAccess',['id'=>$customer->id,'status'=>1]) }}" class="btn btn-success btn-block"><b>Enable Merchant Search Access</b></a>
                        @endif

                    </div>

                </div>
                </div>
                <div class="col-md-4">

                    <div class="box box-primary">
                        <div class="box-body box-profile">

                            <img class="img-responsive" src="{{ asset(str_replace('http://localhost:81/ecom/', '', $customer->profile->nid_image)) }}" onerror="this.style.display='none'" alt="User NID picture">

                        </div>

                    </div>
                </div>
                <div class="col-md-4">

                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <form class="form-horizontal" action="{{ route('blockedUser.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="box-body">
                                    <div class="row">




                                        <div class="col-md-8">

                                            <div class="form-group">
                                                <label for="subject-name" class="col-sm-3 control-label">NID</label>
                                                <div class="col-sm-9">
                                                    <input type="text"   class="form-control" id="nid" name="nid" value="{{ $customer->profile->nid }}" placeholder="Name">
                                                    @error('nid')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">

                                            <div class="form-group">
                                                <label for="subject-name" class="col-sm-3 control-label">Phone</label>
                                                <div class="col-sm-9">
                                                    <input type="text"   class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" placeholder="Name">
                                                    @error('phone')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-group">
                                                <label for="subject-name" class="col-sm-3 control-label">Reason</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" name="reason" placeholder="Why are you blocking this person"></textarea>
                                                    @error('name_en')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <div>
                                            <input type="hidden" name="user_id" value="{{$customer->id}}">
                                        </div>
                                        <div class="col-md-8">

                                            <div class="form-group">
                                                <label for="subject-name" class="col-sm-3 control-label"></label>
                                                <div class="col-sm-9">
                                                    <input type="submit" value="Blacklist NID & phone" class="btn btn-danger">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            @else
                <div class="col-md-12">

                    <h1 class="text-center"><a href="">Customer didn't created his profile yet</a></h1>
                </div>
            @endif
        </div>
        <!-- /.row -->
    </section>
@endsection