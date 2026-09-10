<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">

            <li class="nav-item"><a class="nav-link " href="#order" data-toggle="tab">Your current e-balance {{ $earnings['totalEarning'] }} Tk</a></li>


        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="col-md-8 col-md-offset-2" id="messageDiv">
            @if(Session::has('success'))
                @include('website.layouts.message.success')
            @elseif(Session::has('error'))
                @include('website.layouts.message.error')
            @endif
        </div>
        <div class="tab-content">
            <form class="form-horizontal" method="post" action="{{ route('profile.withdrawRequestSave') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Withdraw amount</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               required
                               name="amount"
                               placeholder="টাকার পরিমান ইংরেজিতে লিখুন">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label"> Bkash  personal</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               required
                               name="phone"
                               placeholder="নাম্বার ইংরেজিতে লিখুন">
                    </div>
                </div>
{{--                <div class="form-group row">--}}
{{--                    <label for="inputName" class="col-sm-2 col-form-label">Bank account information</label>--}}
{{--                    <div class="col-sm-10">--}}
{{--                        <input type="text" class="form-control"--}}
{{--                               required--}}
{{--                               name="bank_account_number"--}}
{{--                               placeholder="Account name and number">--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="tab-content">

            <div class=" active tab-pane" id="order">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                        <tr>
                            <th class="text-center">Request ID</th>
                            <th class="text-center">Request amount</th>
                            <th class="text-center">Request time</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Note</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($withdrawRequests as $wr)
                        <tr>

                            <td class="text-center">{{ $wr->id }}</td>
                            <td class="text-center">{{ $wr->amount }}</td>
                            <td class="text-center">{{ date('d M y',strtotime($wr->created_at)) }}</td>

                            <td class="text-center"><span class="text-success">{{ ucfirst($wr->status) }}</span></td>
                            <td class="text-center"><span class=" ">{{ $wr->note }}</span></td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div><!-- /.card-body -->
</div>
@section('extra-script')
    <script>
        function copyCode() {
            /* Get the text field */
            var copyText = document.getElementById("referralLink");
            console.log(copyText.innerText);
            /* Select the text field */
            // copyText.select();
            // copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.innerText);

            /* Alert the copied text */
            alert("Copied the referralLink: " + copyText.innerText);
        }
    </script>
@endsection
