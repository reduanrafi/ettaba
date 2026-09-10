<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @yield('extra-meta')
    <title>{{ config('app.name', 'Ettaba shop') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/admin')}}/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('assets/admin')}}/bower_components/Ionicons/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/admin')}}/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/admin')}}/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/dist/css/skins/skin-blue.min.css">

    @yield('extra-style')

    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="">
<div class="wrapper">
    <!-- Main Header -->
    @include('admin.layouts.includes.header')
    <!-- Left side column. contains the logo and sidebar -->
    @include('admin.layouts.includes.navigation')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- All Dynamic Content Placed Here -->
        @section('content') @show
    </div>
    <!-- /.content-wrapper -->
    <!-- Main Footer -->
{{--    <footer class="main-footer">--}}
{{--        <!-- To the right -->--}}
{{--        <div class="pull-right hidden-xs">--}}
{{--            Anything you want--}}
{{--        </div>--}}
{{--        <!-- Default to the left -->--}}
{{--        <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.--}}
{{--    </footer>--}}
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{asset('assets/admin')}}/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('assets/admin')}}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="{{asset('assets/admin')}}/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/admin')}}/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin')}}/dist/js/adminlte.min.js"></script>
@yield('extra-script')
<script>
    $(function () {
        $('#example2').DataTable(
            {
                responsive: true,

            }
        )
    });

    $("#messageDiv").show().delay(10000).queue(function(n) {
        $(this).hide(); n();
    });
    var loadFile = function(event) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>

</body>
</html>
