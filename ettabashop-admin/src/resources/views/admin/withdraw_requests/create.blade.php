@extends('admin.layouts.layout')
@section('content')

    <!-- BEGIN Portlet PORTLET-->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" id="messageDiv">
                @if(Session::has('success'))
                    @include('admin.layouts.message.success')
                @elseif(Session::has('error'))
                    @include('admin.layouts.message.error')
                @endif
            </div>
            <div class="col-md-12">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Make withdraw request</h3>
                        <div class="pull-right box-tools">
                            <a href="{{ route('mywithdraws') }} "
                               class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-mail-forward"></i> View All</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <div class="box-body">
                    <form class="form-horizontal" method="post" action="{{ route('withdraw.save') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Amount</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       required
                                       name="amount"
                                       placeholder="Withdraw amount">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label"> Bkash  number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       required
                                       name="phone"
                                       placeholder="0170....">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Bank account information</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       required
                                       name="bank_account_number"
                                       placeholder="Account name and number">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->
        {{--        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>--}}
        {{--        <script>tinymce.init({ selector:'textarea' });</script>--}}
        <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace( 'editor' );
        </script>
    </section>
    <!-- END Portlet PORTLET-->

@endsection

