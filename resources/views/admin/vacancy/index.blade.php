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
                   href="{{ url(adminPath().'/vacancies/create') }}">{{ trans('admin.add') }}</a>
                <table class="table table-hover table-striped datatable">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.name') }}</th>
                        <th>{{ trans('admin.job_title') }}</th>
                        <th>{{ trans('admin.applications') }}</th>
                        <th>{{ trans('admin.show') }}</th>
                        <th>{{ trans('admin.edit') }}</th>
                        <th>{{ trans('admin.delete') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vacancies as $vacancy)
                        <tr>
                            <td>{{ @$vacancy->{app()->getLocale().'_name'} }}</td>
                            <td>{{ @$vacancy->jobTitle->{app()->getLocale().'_name'} }}</td>
                            <td>{{ @$vacancy->applications->count() }}{{ __('admin.applications') }} <a href="{{ url(adminPath().'/applications/vacancy/'.$vacancy->id) }}" class="btn btn-default">{{ __('admin.show') }}</a></td>
                            <td><a class="btn btn-warning" href="{{ url(adminPath().'/vacancies/'.$vacancy->id) }}">{{ __('admin.show') }}</a></td>
                            <td><a class="btn btn-info" href="{{ url(adminPath().'/vacancies/'.$vacancy->id.'/edit') }}">{{ __('admin.edit') }}</a></td>
                            <td><a data-toggle="modal" data-target="#delete{{ $vacancy->id }}"
                                   class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                        </tr>
                        <div id="delete{{ $vacancy->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ trans('admin.delete') . ' ' . $vacancy->{app()->getLocale().'_name'} }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::open(['method'=>'DELETE','route'=>['vacancies.destroy',$vacancy->id]]) !!}
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