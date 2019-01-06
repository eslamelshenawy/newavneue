@extends('admin.index')
@section('content')
    <div id="addEvent" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="newDate"></h4>
                </div>
                <form action="{{ url(adminPath().'/tasks') }}" method="post">
                    <div class="modal-body">

                        {{ csrf_field() }}
                        <div class="form-group @if($errors->has('agent_id')) has-error @endif">
                            <label>{{ trans('admin.agent') }}</label>
                            <select name="agent_id" class="form-control select2" style="width: 100%"
                                    data-placeholder="{{ trans('admin.agent') }}">
                                <option></option>
                                @foreach(App\User::get() as $lead)
                                    <option value="{{ $lead->id }}">
                                        {{ $lead->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group @if($errors->has('leads')) has-error @endif">
                            <label>{{ trans('admin.leads') }}</label>
                            <select class="form-control select2" name="leads[]" multiple
                                    data-placeholder="{{ trans('admin.leads') }}" style="width: 100%">
                                <option></option>
                                @foreach(@App\Lead::get() as $lead)
                                    <option value="{{ $lead->id }}">{{ $lead->first_name.' '.$lead->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="due_date" id="dueDate">
                        <!-- /.input group -->
                        <div class="form-group @if($errors->has('task_type')) has-error @endif">
                            <label>{{ trans('admin.task_type') }}</label>
                            <select name="task_type" class="form-control select2" style="width: 100%"
                                    data-placeholder="{{ trans('admin.task_type') }}">
                                <option></option>
                                <option value="call">{{ trans('admin.call') }}</option>
                                <option value="meeting">{{ trans('admin.meeting') }}</option>
                                <option value="others">{{ trans('admin.others') }}</option>
                            </select>
                        </div>
                        <div class="form-group @if($errors->has('description')) has-error @endif">
                            <label> {{ trans('admin.description') }}</label>
                            <textarea name="description" class="form-control"
                                      placeholder="{!! trans('admin.description') !!}"
                                      rows="6">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat"
                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                        <button type="submit" class="btn btn-success btn-flat">
                            {{ trans('admin.submit') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <link rel="stylesheet" href="{{ url("style/fullcalendar/dist/fullcalendar.min.css") }}">
    <link rel="stylesheet" href="{{ url("style/fullcalendar/dist/fullcalendar.print.min.css") }}" media="print">
    <!-- Small boxes (Stat box) -->
    <div class="row">

        <div class="col-md-6">
            <div class="col-lg-6 col-sm-12">
                <!-- small box -->
                <div class="small-box gold-box">
                    <div class="inner">
                        <p>{{ trans('admin.saleD') }}</p>
                        <h3>{{ $salesD }}</h3>

                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>

                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <!-- small box -->
                <div class="small-box blue-box">
                    <div class="inner">
                        <p>{{ trans('admin.saleM') }}</p>
                        <h3>{{ $salesM }}</h3>

                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>

                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <!-- small box -->
                <div class="small-box pink-box">
                    <div class="inner">
                        <p>{{ trans('admin.dash_lead') }}</p>
                        <h3>{{ $leadsD }}</h3>

                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>

                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <!-- small box -->
                <div class="small-box green-box">
                    <div class="inner">
                        <p>{{ trans('admin.customer_number') }}</p>
                        <h3>{{ $leads }}</h3>

                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-money">
                        </i> {{ trans('admin.in_progress') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <span class="dash-deals pull-right">{{ $deals }}{{ trans('admin.EGP') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(auth()->user()->type=='admin' or count(\App\Group::where('team_leader_id',auth()->user()->id)->get())>0)
            <div class="col-md-6 col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-users"></i> {{ trans('admin.team_calendar') }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div id="calendar1"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        @endif
        <div class="@if(auth()->user()->type=='admin' or count(\App\Group::where('team_leader_id',auth()->user()->id)->get())>0) col-md-6 col-sm-12 @else col-md-12 col-sm-12 @endif">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-users"></i> {{ trans('admin.your_calendar') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <div id="calendar2"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.box-body-->
    <div class="box">
        <div class="box-header with-border">
            <i class="fa fa-bar-chart-o"></i>

            <h3 class="box-title">Bar Chart</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div id="chartdiv"></div>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all"/>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <script>
        var chart = AmCharts.makeChart("chartdiv",
            {
                "type": "serial",
                "theme": "light",
                "dataProvider": [
                        @foreach ($chart as $data)
                        @php($arr = explode('||', $data))
                    {
                        "name": "{{ \App\User::find($arr[1])->name }}",
                        "points": {{ $arr[0] }},
                        "color": "#caa42d",
                        "bullet": "{{ url('uploads/'.\App\User::find($arr[1])->image) }}"
                    },
                    @endforeach
                ],
                "valueAxes": [{
                    "maximum": {{ $max }},
                    "minimum": 0,
                    "axisAlpha": 0,
                    "dashLength": 4,
                    "position": "left"
                }],

                "startDuration": 1,
                "graphs": [{
                    "balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
                    "bulletOffset": 10,
                    "bulletSize": 52,
                    "colorField": "color",
                    "cornerRadiusTop": 8,
                    "customBulletField": "bullet",
                    "fillAlphas": 0.8,
                    "lineAlpha": 0,
                    "type": "column",
                    "valueField": "points"
                }],
                "marginTop": 0,
                "marginRight": 0,
                "marginLeft": 0,
                "marginBottom": 0,
                "autoMargins": false,
                "categoryField": "name",
                "categoryAxis": {
                    "axisAlpha": 0,
                    "gridAlpha": 0,
                    "inside": true,
                    "tickLength": 0
                },
                "export": {
                    "enabled": true
                }
            });
    </script>
    <script src={{ url("style/fullcalendar/dist/fullcalendar.min.js") }}></script>
    <script>
        $(function () {
            /* initialize the external events
             -----------------------------------------------------------------*/
            function init_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    })

                })
            }

            init_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()
            @if(auth()->user()->type=='admin' or count(\App\Group::where('team_leader_id',auth()->user()->id)->get())>0)
            $('#calendar1').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                //Random default events
                @if(auth()->user()->type=='admin')
                events: [
                        @foreach(@App\Task::get() as $row)
                    {
                        title: '{{ trans("admin.task")."/".@App\User::find($row->agent_id)->name }}',
                        start: '{{ date("Y/m/d",$row->due_date) }}',
                        url: '{{ url(adminPath().'/tasks/'.$row->id) }}',
                        allDay: true,
                        backgroundColor: 'green', //Primary (light-blue)
                        borderColor: '#3c8dbc' //Primary (light-blue)
                    },
                    @endforeach
                ],
                @elseif(count($groups = @\App\Group::where('team_leader_id',auth()->user()->id)->get()) > 0)
                events: [
                        @foreach($groups as $group)
                        @php($users = Home::myTeam($group->id))
                        @foreach($users as $user)
                        @foreach(@App\Task::where('agent_id',$user)->get() as $row)
                    {
                        title: '{{ trans("admin.task")."/".@App\User::find($user)->name }}',
                        start: '{{ date("Y/m/d",$row->due_date) }}',
                        url: '{{ url(adminPath().'/tasks/'.$row->id) }}',
                        allDay: true,
                        backgroundColor: 'green', //Primary (light-blue)
                        borderColor: '#3c8dbc' //Primary (light-blue)
                    },
                    @endforeach
                    @endforeach
                    @endforeach
                ],
                @endif
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject')

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject)

                    // assign it the date that was reported
                    copiedEventObject.start = date
                    copiedEventObject.allDay = allDay
                    copiedEventObject.backgroundColor = $(this).css('background-color')
                    copiedEventObject.borderColor = $(this).css('border-color')

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove()
                    }

                },
                dayClick: function (date, allDay, jsEvent, view) {
                    var end = new Date(date.format('YYYY-M-D')),
                        now = new Date(),
                        diff = new Date(end - now),
                        days = Math.ceil(diff / 1000 / 60 / 60 / 24);
                    if (days >= 0) {
                        var task = '{{ url(adminPath().'/tasks/create?date=') }}' + date.format('YYYY-M-D');
                        var todo = '{{ url(adminPath().'/todos/create?date=') }}' + date.format('YYYY-M-D');
                        $('#task').attr('href', task);
                        $('#todo').attr('href', todo);
                        $('#addEvent').modal('show');
                        $('#newDate').html(date.format('YYYY-M-D'));
                        $('#dueDate').val(date.format('YYYY-M-D'));
                    }
                }
            })
            @endif
            $('#calendar2').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                //Random default events
                events: [
                        @foreach(@App\ToDo::where('user_id',auth()->user()->id)->get() as $row)
                    {
                        title: '{{ trans("admin.todo")."/".@App\User::find($row->user_id)->name }}',
                        start: '{{ date("Y/m/d",$row->due_date) }}',
                        url: '{{ url(adminPath().'/todos/'.$row->id) }}',
                        allDay: true,
                        backgroundColor: '#3c8dbc', //Primary (light-blue)
                        borderColor: '#3c8dbc' //Primary (light-blue)
                    },
                        @endforeach
                        @foreach(@App\Task::where('agent_id',auth()->user()->id)->orWhere('agent_id',auth()->user()->id)->get() as $row)
                    {
                        title: '{{ trans("admin.task")."/".@App\User::find($row->agent_id)->name }}',
                        start: '{{ date("Y/m/d",$row->due_date) }}',
                        url: '{{ url(adminPath().'/tasks/'.$row->id) }}',
                        allDay: true,
                        backgroundColor: 'green', //Primary (light-blue)
                        borderColor: '#3c8dbc' //Primary (light-blue)
                    },
                    @endforeach
                ],
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject')

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject)

                    // assign it the date that was reported
                    copiedEventObject.start = date
                    copiedEventObject.allDay = allDay
                    copiedEventObject.backgroundColor = $(this).css('background-color')
                    copiedEventObject.borderColor = $(this).css('border-color')

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove()
                    }

                },
                dayClick: function (date, allDay, jsEvent, view) {
                    var end = new Date(date.format('YYYY-M-D')),
                        now = new Date(),
                        diff = new Date(end - now),
                        days = Math.ceil(diff / 1000 / 60 / 60 / 24);
                    if (days >= 0) {
                        var task = '{{ url(adminPath().'/tasks/create?date=') }}' + date.format('YYYY-M-D');
                        var todo = '{{ url(adminPath().'/todos/create?date=') }}' + date.format('YYYY-M-D');
                        $('#task').attr('href', task);
                        $('#todo').attr('href', todo);
                        $('#addEvent').modal('show');
                        $('#newDate').html(date.format('YYYY-M-D'));
                        $('#dueDate').val(date.format('YYYY-M-D'));
                    }
                }
            })


            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            //Color chooser button
            var colorChooser = $('#color-chooser-btn')
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                //Save color
                currColor = $(this).css('color')
                //Add color effect to button
                $('#add-new-event').css({'background-color': currColor, 'border-color': currColor})
            })
            $('#add-new-event').click(function (e) {
                e.preventDefault()
                //Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                //Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color': currColor,
                    'color': '#fff'
                }).addClass('external-event')
                event.html(val)
                $('#external-events').prepend(event)

                //Add draggable funtionality
                init_events(event)

                //Remove event from text input
                $('#new-event').val('')
            })

        })


    </script>

@endsection