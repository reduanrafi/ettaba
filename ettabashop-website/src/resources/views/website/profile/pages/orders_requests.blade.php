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
                            <th>Order ID</th>
                            <th>products</th>

                            <th>Status</th>
                            <th class="text-center">Time</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{!! $order->product_name !!}</td>

                                @if($order->status=='completed')
                                <td><span class="badge badge-success">{{ $order->status }}</span></td>
                                @else
                                <td><span class="badge badge-danger">{{ $order->status }}</span></td>
                                @endif


                                <td class="text-center">{{ date('d M y',strtotime($order->created_at)) }}</td>

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