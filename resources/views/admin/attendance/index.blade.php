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

            {!! Form::open(['method'=>'POST' , 'action'=> 'AjaxController@dateInterval']) !!}

            <div class='form-group col-md-6' id='start'>
                {!! Form::label('start','From Date:') !!}
                {!! Form::text('start', null,['class'=>'form-control']) !!}
            </div>


            <div class='form-group col-md-6' id='end'>
                {!! Form::label('end','To Date:') !!}
                {!! Form::text('end',null,['class'=>'form-control']) !!}
            </div>


            <div class='text-left col-md-12' id='interval' >
                {!! Form::submit('Set Date Interval',['class'=>'btn btn-warning']) !!}
            </div>

            {!! Form::close() !!}

            <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
               href="{{ url(adminPath().'/attendance/create') }}">{{ trans('admin.add') }}</a>



            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.date') }}</th>
                    <th>{{ trans('admin.name') }}</th>
                    <th>{{ trans('admin.from_to') }}</th>
                    <th>{{ trans('admin.working_hours') }}</th>
                    <th>{{ trans('admin.edit') }}</th>
                    <th>{{ trans('admin.delete') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($attendance as $attend)
                    <tr>
                        <td>
                            @if($attend->date != null)
                                {{@$attend->date}}
                            @else {{date('d-m-Y',strtotime($attend->check_in))}}
                            @endif
                        </td>

                        <td>{{$attend->full_name}}</td>
                        <td>{{ $attend->check_in.' '.$attend->check_out }}</td>
                        <td>{{ $attend->hours }}</td>
                        <td><a class="btn btn-warning"
                               href="{{ url(adminPath().'/attendance/'.$attend->id.'/edit') }}">{{ __('admin.edit') }}</a>
                        </td>
                        <td><a data-toggle="modal" data-target="#delete{{ $attend->id }}"
                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                    </tr>
                    <div id="delete{{ $attend->id }}" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ trans('admin.delete') . ' ' . 'Attendance'}}</p>
                                </div>
                                <div class="modal-footer">
                                    {!! Form::open(['method'=>'DELETE','route'=>['job_categories.destroy',$attend->id]]) !!}
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
    <script>

        $(function(){$("#start").datepicker({
            autoclose:true,
            format:"yyyy-mm-dd",

        }),
            $("#end").datepicker({
                autoclose:true,
                format:"yyyy-mm-dd",

            })
        });
    </script>
@stop