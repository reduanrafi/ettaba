<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">

            <li class="nav-item"><a class="nav-link " href="#order" data-toggle="tab">Withdraw histories</a></li>

            

        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">

            <div class=" active tab-pane" id="order">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                        <tr>
                            <th>Detail</th>
                            <th>Date</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($withdrawHistories as $history)
                            <tr>

                                <td> {{ $history->note }}</td>
                                <td>{{ date('d M y',strtotime($history->created_at)) }}</td>

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