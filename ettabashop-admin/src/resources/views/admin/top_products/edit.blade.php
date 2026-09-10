@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manage Shops</h3>
                        {{--<div class="pull-right box-tools">--}}
                        {{--<a href="{{ route('exam.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" action="{{ route('customers') }}" method="post">
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
                                @if(Session::has('message'))
                                    <div class="col-md-6 col-md-offset-2" id="successMessage">
                                        <span> {{ Session::get('message') }}</span>
                                    </div>
                                @endif
                                <div class="col-md-6 col-md-offset-2">

                                    <div class="form-group">
                                        <label for="exam-name" class="col-sm-3 control-label">User email</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" readonly id="exam-name" name="exam_name" value="{{$user->email}}" placeholder="Enter Examination Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exam-name" class="col-sm-3 control-label">Select type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="type">
                                                <option @if($user->type=='admin')selected @endif value="admin">Admin</option>
                                                <option @if($user->type=='author')selected @endif value="author">Author</option>
                                                <option @if($user->type=='editor')selected @endif value="editor">Editor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection