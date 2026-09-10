@extends('website.layouts.layout')
@section('facebook')
{{--    <meta property="og:title" content="{{ $product->name_en}}"/>--}}
    {{--    <meta property="og:image" content="{{'http://mahedipublications.com/'.$product->featured_image }}"/>--}}
    {{--    <meta property="og:description" content="{{ $product->description_en }}"/>--}}
@endsection
@section('content')
    <!-- Shop Detail Start -->

    <div class="row border-top px-xl-5">
        <div class="col-lg-2">
            <img src="{{ asset('assets/images/logo/logo.png') }}" width="150" height="100">
        </div>

        <div class="col-lg-10">
            @include('website.layouts.includes.navigation')

        </div>

    </div>
    <div class="container py-5">
        <div class="row">

            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ isset($profile->profile_image)?$profile->profile_image: asset('assets/images/user.png')}}" alt="User profile picture">
                        </div>

                        <h4 class="profile-username text-center p-2">{{isset($profile->first_name)?$profile->first_name:'' }} {{isset($profile->last_name)?$profile->last_name:'' }}</h4>

{{--                        <p class="text-muted text-center">Designation</p>--}}

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>E-balance</b> <a class="float-right">1,322</a>
                            </li>
                            <li class="list-group-item">
                                <b>Earnings</b> <a class="float-right">543</a>
                            </li>
                            <li class="list-group-item">
                                <b>Withdraws</b> <a class="float-right">13,287</a>
                            </li>
                        </ul>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->


            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
                            <li class="nav-item"><a class="nav-link " href="#order" data-toggle="tab">Orders</a>
                            <li class="nav-item"><a class="nav-link" href="#address" data-toggle="tab">others</a>
                            </li>
                            </li>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                @if(Session::has('error'))
                               @include('website.layouts.message.error')
                                @endif
                                <form class="form-horizontal" method="post" action="{{ route('profile.save') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                   required
                                                   name="first_name"
                                                   value="{{ isset($profile->first_name)?$profile->first_name:''}}"
                                                   placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Last Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                   required
                                                   name="last_name"
                                                   value="{{ isset($profile->last_name)?$profile->last_name:'' }}"
                                                   placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">NID</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                   required
                                                   name="nid"
                                                   value="{{ isset($profile->nid)?$profile->nid:'' }}"
                                                   placeholder="National identity number"
                                                   required>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Address</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="address"
                                                      required
                                                      placeholder="Address">{{ isset($profile->address)?$profile->address:'' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Date of birth</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control"
                                                   required
                                                   name="date_of_birth"
                                                   value="{{ isset($profile->date_of_birth)?$profile->date_of_birth:'' }}"
                                                   placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="subject-name" class="col-sm-2 col-form-label">Profile
                                            image</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control"
                                                   required
                                                   onchange="loadFile(event)"
                                                   id="image"
                                                   name="profile_image"
                                                   placeholder="Post image">
                                            <p><img id="output" width="200"/></p>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="order">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Item</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @for($i=0;$i<10;$i++)
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                                <td>Call of Duty IV</td>
                                                <td><span class="badge badge-success">Shipped</span></td>
                                                <td>
                                                    <div class="sparkbar">120</div>
                                                </td>
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="address">
                                <form class="form-horizontal">

                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Shipping address</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="address" rows="5"
                                                      cols="10"> </textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- Shop Detail End -->
@endsection()
@section('extra-script')
    <script>
        var loadFile = function (event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endsection()

