<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">

            <li class="nav-item"><a class="nav-link " href="#order" data-toggle="tab">Orders</a></li>

            

        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">

            <div class=" active tab-pane" id="order">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                        <tr>
                            <th>Training title</th>
                            <th>Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        @for($i=0;$i<3;$i++)
                            <tr>

                                <td>Call of Duty IV</td>
                                <td><span class="badge badge-success">Done</span></td>

                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>



            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div><!-- /.card-body -->
</div>