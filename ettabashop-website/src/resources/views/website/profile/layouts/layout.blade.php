<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @yield('facebook')
    @yield('extra-meta')
    <title>{{ config('app.name', '') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{--    <link rel="stylesheet" href="{{asset('assets/website/')}}">--}}
<!-- Font Awesome -->

    <link rel="apple-touch-icon" href="{{ asset('assets/website/img/apple-icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo/favicon.ico')}}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/website/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/website/css/style.css') }}" rel="stylesheet">
    <!-- Google Font -->
    @yield('extra-style')
</head>

<body class="">
<div id="app">
{{--    @include('website.layouts.includes.navigation')--}}


<!-- All Dynamic Content Placed Here -->
    @section('content') @show


    @include('website.layouts.includes.footer')
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/website/lib/easing/easing.min.js')}}"></script>
<script src="{{ asset('assets/website/lib/owlcarousel/owl.carousel.min.js')}}"></script>

<!-- Contact Javascript File -->
<script src="{{ asset('assets/website/mail/jqBootstrapValidation.min.js')}}"></script>
<script src="{{ asset('assets/website/mail/contact.js')}}"></script>

<!-- Template Javascript -->
<script src="{{ asset('assets/website/js/main.js')}}"></script>
<script src="https://unpkg.com/vue@next"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>

{{--<script>--}}
{{--    const app = {--}}

{{--        data() {--}}
{{--            return {--}}

{{--            }--}}

{{--        },--}}
{{--        methods: {--}}

{{--        },--}}
{{--        mounted: function () {--}}


{{--        },--}}
{{--        computed: {--}}

{{--        },--}}

{{--    };--}}

{{--    Vue.createApp(app).mount('#app')--}}
{{--</script>--}}
@yield('extra-script')

<div class="modal fade" id="virtualBalanceModal"  tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add virtual balance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('profile.virtualBalanceSave')}}" method="post" enctype="multipart/form-data">
                    @csrf

                        <div class="form-group">
                            <label for="amount">পরিমান</label>
                            <input type="text" name="amount" class="form-control" id="amount"
                                   placeholder="কত টাকা পাঠিয়েছেন লিখুন">
                        </div>
                        <div class="form-group">
                            <label for="transaction_code">বিকাশ অথবা নগদ ট্রান্সেকশন আইডি</label>
                            <input type="text" name="transaction_code" class="form-control"
                                   id="transaction_code" placeholder="TrxID:BHK1T....">
                        </div>

                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::user()->id }}">
                        <input type="hidden" name="status" value="incoming">

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
