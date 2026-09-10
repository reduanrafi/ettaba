@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Slider</h3>
                        <div class="pull-right box-tools">
                        <a href="{{ route('slider.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>
                        </div>
                    </div>
                    @if(Session::has('message'))
                        <div class="col-md-6 col-md-offset-2" style="color: red" id="successMessage">
                            <span> {{ Session::get('message') }}</span>
                        </div>
                    @endif
                    <!-- /.box-header -->
                    <!-- form start -->

                        <!-- /.box-body -->
                        <div class="box-footer">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>

                                    <th>Thumb</th>
                                    <th>Title</th>
                                    <th>Created date</th>
                                    <th class="text-center">Action</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                               @if($sliders)
                                   @foreach($sliders as $slider)
                                       <tr>

                                           <td>
                                               <img src="{{ asset($slider->image) }}" width="50" height="50">
                                           </td>
                                           <td>{{  $slider->title }}</td>
                                           <td>{{ date('y-m-d',strtotime($slider->created_at)) }}</td>

                                           {{--<td ><a href="{{ route('subject.config',['subject_id'=>$slider->id]) }}" class=" btn btn-xs btn-success">Config</a></td>--}}
                                           <td class="text-center">
                                               <a href="{{  route('slider.edit',['slider'=>$slider->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>

                                           </td>
                                           <td class="text-center">
                                               <form action="{{  route('slider.destroy',['slider'=>$slider->id]) }}" method="POST">
                                                   {{ method_field('DELETE') }}
                                                   {{ csrf_field() }}
                                                   <button type='submit' class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                               </form>
                                           </td>

                                       </tr>
                                   @endforeach
                               @endif
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