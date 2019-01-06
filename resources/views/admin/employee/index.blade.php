@extends('admin.index')
@section('content')

    @include('admin.employee.hr_nav')

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="pull-right ">
                    <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif" href="{{ url(adminPath().'/employees/create/') }}"
                       class="btn btn-success fa fa-user">{{ __('admin.add') }}</a>
                </div>

                <table class="table table-hover table-striped datatable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>national ID</th>
                        <th>EMAIL</th>
                        <th>Phone</th>
                        <th>GENDER</th>
                        <th>Show</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{$employee->id}}</td>
                            <td>{{$employee->en_first_name.' '.$employee->en_middle_name}}</td>
                            <td>{{$employee->national_id}}</td>
                            <td>{{$employee->personal_mail}}</td>
                            <td>{{$employee->phone}}</td>
                            <td>{{$employee->gender}}</td>

                            <td>
                                <a href="{{route('employees.show', $employee->id)}}" class="btn btn-info">
                                    <span class="fa fa-users"></span> Profile </a>
                            </td>

                            <td>
                                <a href="{{route('employees.edit', $employee->id)}}" class="btn btn-warning">
                                    <span class="fa fa-edit"></span> Edit </a>
                            </td>
                            <td><a data-toggle="modal" data-target="#delete{{ $employee->id }}"
                                   class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                        </tr>

                            <div id="delete{{ $employee->id }}" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.employee') }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{  $employee->en_first_name. ' ' .$employee->en_last_name }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            {!! Form::open(['method'=>'DELETE','route'=>['employees.destroy',$employee->id]]) !!}
                                            <button type="button" class="btn btn-default btn-flat"
                                                    data-dismiss="modal">{{ trans('admin.close') }}</button>
                                            <button type="submit"
                                                    class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                                </div>
                            </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@section('js')
    <script>
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': true,
            "order": [[ 0, "desc" ]],
            'autoWidth': true
        })
    </script>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "320px";
            document.getElementById("main").style.marginLeft = "320px";
            document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        var closeNav = function () {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            document.body.style.backgroundColor = "white";
        }
    </script>
    <script>
        $('table[data-form="deleteForm"]').on('click', '.form-delete', function(e){
           e.preventDefault();
            var $form=$(this);
            $('#confirm').modal().on('click', '#delete-btn', function(){
                 $form.submit();
                 });
                 });
        </script>

@endsection
@stop