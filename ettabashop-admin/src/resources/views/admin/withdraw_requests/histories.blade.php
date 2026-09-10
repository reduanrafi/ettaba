@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Withdraw requests</h3>

                    </div>
                    <div class="col-md-8 col-md-offset-2" id="messageDiv">
                        @if(Session::has('success'))
                            @include('admin.layouts.message.success')
                        @elseif(Session::has('error'))
                            @include('admin.layouts.message.error')
                        @endif
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <!-- /.box-body -->
                    <div class="box-footer">
                        <table id="withdrwahistoriesTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User name</th>
                                <th>User Phone</th>

                                <th>Request amount</th>
                                <th class="text-center">Request time</th>


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($histories as $wr)
                                <tr>

                                    <td>{{ $wr->id }}</td>
                                    <td>
                                        <a href="{{ route('customer.show',['customer'=>$wr->user->id]) }}">
                                            @if($wr->user->profile!=null)
                                                {{ $wr->user->profile->first_name }} {{ $wr->user->profile->last_name }}
                                            @else
                                                {{ $wr->user->phone }}
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ $wr->user->phone }}</td>

                                    <td>{{ $wr->amount }}</td>
                                    <td class="text-center">{{ date('d M y',strtotime($wr->created_at)) }}</td>



                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection
@section('extra-script')
    <script>

        $(function () {
            $('#withdrwahistoriesTable').DataTable(
                {
                    responsive: true,
                    "order": [[0, "desc"]]
                }
            )
        });
    </script>
@endsection
