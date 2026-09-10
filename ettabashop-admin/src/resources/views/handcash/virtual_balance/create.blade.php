@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Money (Virtual Balance)</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="POST" action="{{ route('handcash.virtual_balance.store') }}">
                        @csrf
                        <div class="box-body">
                            @if(Session::has('success'))
                                <div class="alert alert-success">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="amount">Amount (টাকার পরিমাণ)</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" required min="1">
                            </div>
                            <div class="form-group">
                                <label for="transaction_code">Transaction ID (ট্রানজেকশন আইডি)</label>
                                <input type="text" class="form-control" id="transaction_code" name="transaction_code" placeholder="Enter Bkash/Nagad/Bank Transaction ID" required>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">My Requests History</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Trx ID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                    <tr>
                                        <td>{{ $req->created_at->format('d M Y h:i A') }}</td>
                                        <td>৳ {{ $req->amount }}</td>
                                        <td>{{ $req->transaction_code }}</td>
                                        <td>
                                            @if($req->is_completed == 1 && $req->is_accepted == 1)
                                                <span class="label label-success">Accepted</span>
                                            @elseif($req->is_rejected == 1)
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
