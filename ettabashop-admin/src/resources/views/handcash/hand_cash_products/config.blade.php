@extends('handcash.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Subject configuration</h3>
                        {{--<div class="pull-right box-tools">--}}
                        {{--<a href="{{ route('exam.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>--}}
                        {{--</div>--}}
                        @if(Session::has('message'))

                                <span class="col-md-offset-2"> {{ Session::get('message') }}</span>

                        @endif
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" action="{{ route('subject.set-config') }}" method="post">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                @if ($errors->any())
                                    <div class="col-md-6 col-md-offset-2">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <span>{{ $error }}</span>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="col-md-4 ">

                                    <div class="form-group">
                                        <label for="subject-name" class="col-sm-3 control-label">Subject Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ $config->name }}" class="form-control" id="subject-name" name="subject_name" placeholder="Enter Subject Name">
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-3 ">

                                        <div class="form-group">
                                            <label for="subject-name" class="col-sm-3 control-label">Questions</label>
                                            <div class="col-sm-9">
                                                <input type="number" value="{{ $config->num_of_questions }}" min="0" class="form-control" id="subject-name" name="num_of_question" placeholder="Set number of questions">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 ">

                                        <div class="form-group">
                                            <label for="subject-name" class="col-sm-3 control-label">Time</label>
                                            <div class="col-sm-9">
                                                <input type="number" value="{{ $config->total_time }}" min="0" class="form-control" id="subject-name" name="total_time" placeholder="Set number of questions">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{ $config->id }}">
                                    <input type="hidden" name="subject_id" value="{{ $config->sub }}">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info">Set</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /.box-body -->
                    {{--</form>--}}
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection
