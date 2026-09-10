<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">
                    Profile
                    @if(isset($profile) && $profile->user->is_approved==1)
                        <i class="text-success fa fa-check"></i>
                    @endif
                </a> </li>

        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">
            <div class="active tab-pane" id="settings">
                @if(Session::has('error'))
                    @include('website.layouts.message.error')
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" method="post" action="{{ route('profile.save') }}"
                      enctype="multipart/form-data">
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
                    @if(auth()->user()->customer_type=='buy_earn')
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Father Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   required
                                   name="father_name"
                                   value="{{ isset($profile->father_name)?$profile->father_name:''}}"
                                   placeholder="Father Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Mother Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   required
                                   name="mother_name"
                                   value="{{ isset($profile->mother_name)?$profile->mother_name:''}}"
                                   placeholder="Mother Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">NID</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   required
                                   name="nid"
                                   value="{{ isset($profile->nid)?$profile->nid:'' }}"
                                   {{ isset($profile->nid) && $profile->nid != '' ? 'readonly' : '' }}
                                   placeholder="National identity number"
                                   required>
                        </div>
                    </div>




                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Date of birth</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control"
                                   required
                                   name="date_of_birth"
                                   value="{{ isset($profile->date_of_birth)?$profile->date_of_birth:'' }}"
                                   {{ isset($profile->date_of_birth) && $profile->date_of_birth != '' ? 'readonly' : '' }}
                                   placeholder="Name">
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Delivery address</label>
                        <div class="col-sm-10">
                                            <textarea class="form-control" name="address"
                                                      required
                                                      placeholder="Address">{{ isset($profile->address)?$profile->address:'' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="subject-name" class="col-sm-2 col-form-label">Profile
                            image</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control"
                                   {{ isset($profile->profile_image)?'':'required' }}
                                   onchange="loadFile(event)"
                                   id="image"
                                   name="profile_image"
                                   placeholder="Post image">
                            <p><img id="output" width="200"/></p>
                        </div>

                    </div>

{{--                    <div class="form-group row">--}}
{{--                        <label for="subject-name" class="col-sm-2 col-form-label">NID--}}
{{--                            image</label>--}}
{{--                        <div class="col-sm-10">--}}
{{--                            @if(isset($profile->nid_image))--}}
{{--                                <img class="profile-user-img img-fluid img-circle"--}}
{{--                                     src="{{ isset($profile->nid_image)?asset($profile->nid_image):asset('assets/images/user.png')}}"--}}
{{--                                     alt="User profile picture">--}}
{{--                            @else--}}

{{--                            <input type="file" class="form-control"--}}
{{--                                   {{ isset($profile->nid_image)?'':'required' }}--}}
{{--                                   onchange="loadNid(event)"--}}
{{--                                   id="nid_image"--}}
{{--                                   name="nid_image"--}}
{{--                                   placeholder="Post image">--}}
{{--                            <p><img id="nid" width="200"/></p>--}}
{{--                            @endif--}}
{{--                        </div>--}}

{{--                    </div>--}}


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
