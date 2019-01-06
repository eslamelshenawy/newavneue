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
                <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                   href="{{ url(adminPath().'/vacations/create') }}">{{ trans('admin.add') }}</a>
                <table class="table table-hover table-striped datatable">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.name') }}</th>
                        <th>{{ trans('admin.annual_days') }}</th>
                        <th>Vacation type </th>
                        <th>{{ trans('admin.show') }}</th>
                        <th>{{ trans('admin.edit') }}</th>
                        <th>{{ trans('admin.delete') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vacations as $vacation)
                        <tr>
                            <td>{{ $vacation->{app()->getLocale().'_name'} }}</td>
                            <td>{{ $vacation->number_of_days}}</td>
                            <td>{{$vacation->type}}</td>
                            <td><a class="btn btn-warning" href="{{ url(adminPath().'/vacations/'.$vacation->id) }}">{{ __('admin.show') }}</a></td>
                            <td><a class="btn btn-warning" href="{{ url(adminPath().'/vacations/'.$vacation->id.'/edit') }}">{{ __('admin.edit') }}</a></td>
                            <td><a data-toggle="modal" data-target="#delete{{ $vacation->id }}"
                                   class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                        </tr>
                        <div id="delete{{ $vacation->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ trans('admin.delete') . ' ' . $vacation->{app()->getLocale().'_name'} }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::open(['method'=>'DELETE','route'=>['vacations.destroy',$vacation->id]]) !!}
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                        <button type="submit"
                                                class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                        {!! Form::close() !!}
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
@endsection

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
@stop