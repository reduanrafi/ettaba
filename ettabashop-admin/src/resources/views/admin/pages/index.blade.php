@extends('admin.layouts.layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pages</h3>
                        <div class="pull-right box-tools">
                        <a href="{{ route('page.create') }}" class="btn btn-block btn-primary btn-flat pull-right btn-sm"><i class="fa fa-plus"></i> Add item</a>
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
                                    <th>Page Title</th>
                                    <th>Thumb</th>
                                    <th>Created date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                               @if($pages)
                                   @foreach($pages as $page)
                                       <tr>
                                           <td>{{ $page->title }}</td>
                                           <td>
                                               <img src="http://localhost/sd/assets/admin/images/pages/{{ $page->image }}" width="50" height="50">
                                           </td>
                                           <td>{{ date('y-m-d',strtotime($page->created_at)) }}</td>
                                           <td class="text-center">
                                               @if($page->is_featured==1)
                                                   <span class="text-green">Featured</span>
                                               @else
                                                   <span >Not Featured</span>
                                               @endif
                                           </td>
                                           {{--<td ><a href="{{ route('subject.config',['subject_id'=>$page->id]) }}" class=" btn btn-xs btn-success">Config</a></td>--}}
                                           <td>
                                               <a href="{{ route('page.edit',['id'=>$page->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
{{--                                               <a href="{{ route('page.isFeatured',['id'=>$page->id]) }}" class="btn btn-xs btn-warning"> Featured</a>--}}

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