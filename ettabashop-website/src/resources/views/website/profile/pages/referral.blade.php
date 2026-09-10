<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">

            <li class="nav-item"><a class="nav-link " href="#order" data-toggle="tab">Referral</a></li>

            

        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">

            <div class=" active tab-pane" id="order">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                        <tr>
                            <th>Referral Code</th>
                            <th>Referral Link</th>
                            <th>QR Code</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>

                            <tr>

                                <td>{{ auth()->user()->referral_code }}</td>
                                <td id="referralLink">https://newshop.ettabashop.com/ENC/{{ auth()->user()->unique_id }}</td>
                                <td>
                                    <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=https://newshop.ettabashop.com/ENC/{{ auth()->user()->unique_id }}&choe=UTF-8" title="Referral QR Code" />
                                </td>
                                <td onclick="copyCode()"><span class="btn btn-xs btn-success">Copy Link</span></td>

                            </tr>

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