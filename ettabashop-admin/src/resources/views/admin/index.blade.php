@extends('admin.layouts.layout')
@section('content')

    <section class="content-header">

        {{--<ol class="breadcrumb">--}}
        {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
        {{--<li class="active">Here</li>--}}
        {{--</ol>--}}
    </section>

    <!-- Main content -->
    @if(Auth::user()->type=='admin')
       @include('admin.dashboards.admin')
    @elseif(Auth::user()->type=='store_owner')
        @include('admin.dashboards.shop')
    @elseif(Auth::user()->type=='sales_staff')
        @include('admin.dashboards.sales')
    @elseif(Auth::user()->type=='store_administrator')
        @include('admin.dashboards.handcash')
    @endif
    <!-- /.content -->

@endsection()
@section('extra-script')
    <script>

        function displayMessage(message) {
            $(".response").html("<div class='success'>" + message + "</div>");
            setInterval(function () {
                $(".success").fadeOut();
            }, 1000);
        }
    </script>
@endsection
