<!-- jQuery -->
<script src="{{asset('assets/admin/vendor/jquery/jquery.min.js')}}"></script>

<!-- jQuery 3 -->
<script src="{{asset('Admin')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('assets/admin/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>
{{-- extra script--}}
@yield('extra-script')
<!-- Custom Theme JavaScript -->
<script>
    $(function() {
        // setTimeout() function will be fired after page is loaded
        // it will wait for 5 sec. and then will fire
        // $("#successMessage").hide() function
        setTimeout(function() {
            $("#successMessage").hide('blind', {}, 500)
        }, 5000);
    });
</script>

</body>

</html>