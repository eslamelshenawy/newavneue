@extends('admin.index')
<style>
    .progress-bar {
        width: 0;
        animation: progress 1.5s ease-in-out forwards;

    .title {
        opacity: 0;
        animation: show 0.35s forwards ease-in-out 0.5s;
    }

    }

    @keyframes progress {
        from {
            width: 0;
        }
        to {
            width: 100%;
        }
    }

    @keyframes show {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
@section('content')
    <div id="addEvent" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="newDate"></h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat"
                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                    <a id="todo" href="#" class="btn btn-success btn-flat">{{ trans('admin.todo') }}</a>
                    <a id="task" href="#" class="btn btn-warning btn-flat">{{ trans('admin.task') }}</a>
                </div>
            </div>

        </div>
    </div>
    <link rel="stylesheet" href="{{ url("style/fullcalendar/dist/fullcalendar.min.css") }}">
    <link rel="stylesheet" href="{{ url("style/fullcalendar/dist/fullcalendar.print.min.css") }}" media="print">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-sm-12">
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
        <div class="col-lg-3 col-sm-12">
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
        <div class="col-lg-3 col-sm-12">
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
        <div class="col-lg-3 col-sm-12">
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
    <div class="row">
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-calendar"></i> {{ trans('admin.your_calendar') }}</h3>
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
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-line-chart"></i> {{ trans('admin.target') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                @php
                    $target = @\App\Target::where('agent_type_id', auth()->user()->agent_type_id)->orderBy('id', 'desc')->first();
                    if ($target == null) {
                        $calls = 0;
                        $meetings = 0;
                        $leads = 0;
                        $money = 0;
                        $target = new stdClass();
                        $target->calls = 0;
                        $target->meetings = 0;
                        $target->leads = 0;
                        $target->money = 0;
                        $target->month = 0;
                    }
                    $calls = @\App\Call::where('user_id',auth()->user()->id)->
                    where('created_at','>=', $target->month.'-01 00:00:00')->
                    where('created_at','<=', $target->month.'-31 23:59:59')->
                    count();
                    
                    if ($target->calls != 0) 
                        $callsPercent = $calls * 100 / $target->calls;
                    else
                        $callsPercent = 0;
                        
                    $meetings = @\App\Meeting::where('user_id',auth()->user()->id)->
                    where('created_at','>=', $target->month.'-01 00:00:00')->
                    where('created_at','<=', $target->month.'-31 23:59:59')->
                    count();
                    
                    if ($target->meetings != 0)
                        $meetingsPercent = $meetings * 100 / $target->meetings;
                    else 
                        $meetingsPercent = 0;
                        
                    $leads = @\App\Lead::where('agent_id',auth()->user()->id)->
                    where('created_at','>=', $target->month.'-01 00:00:00')->
                    where('created_at','<=', $target->month.'-31 23:59:59')->
                    count();

                    if ($target->leads != 0)
                        $leadsPercent = $leads * 100 / $target->leads;
                    else
                        $leadsPercent = 0;
                        
                    $money = @\App\ClosedDeal::where('agent_id',auth()->user()->id)->
                    where('created_at','>=', $target->month.'-01 00:00:00')->
                    where('created_at','<=', $target->month.'-31 23:59:59')->
                    sum('price');

                    if ($target->money != 0)
                        $moneyPercent = $money * 100 / $target->money;
                    else
                        $moneyPercent = 0;
                    
                @endphp
                <div class="box-body no-padding">
                    <br/>
                    <div style="width: 100%; padding: 15px;">
                        <h4 style="font-weight: bold"><i class="fa fa-phone"></i> {{ __('admin.calls') }}
                            <span style="color: #caa42d;">{{ $calls }}/<sub
                                        style="color: gray;">{{ $target->calls }}</sub></span>
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                 aria-valuenow="{{ $callsPercent }}" aria-valuemin="0" aria-valuemax="100"
                                 style="max-width: {{ $callsPercent }}%">
                                <span class="title">{{ $callsPercent }}%</span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div style="width: 100%; padding: 15px;">
                        <h4 style="font-weight: bold"><i class="fa fa-handshake-o"></i> {{ __('admin.meetings') }}
                            <span style="color: #caa42d;">{{ $meetings}}/<sub
                                        style="color: gray;">{{ $target->meetings }}</sub></span>
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                 aria-valuenow="{{ $meetingsPercent }}" aria-valuemin="0" aria-valuemax="100"
                                 style="max-width: {{ $meetingsPercent }}%">
                                <span class="title">{{ $meetingsPercent }}%</span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div style="width: 100%; padding: 15px;">
                        <h4 style="font-weight: bold"><i class="fa fa-users"></i> {{ __('admin.leads') }}
                            <span style="color: #caa42d;">{{ $leads}}/<sub
                                        style="color: gray;">{{ $target->leads }}</sub></span>
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                 aria-valuenow="{{ $leadsPercent }}" aria-valuemin="0" aria-valuemax="100"
                                 style="max-width: {{ $leadsPercent }}%">
                                <span class="title">{{ $leadsPercent }}%</span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div style="width: 100%; padding: 15px;">
                        <h4 style="font-weight: bold"><i class="fa fa-money"></i> {{ __('admin.money') }}
                            <span style="color: #caa42d;">{{ $money}}/<sub
                                        style="color: gray;">{{ $target->money }}</sub></span>
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                 aria-valuenow="{{ $moneyPercent }}" aria-valuemin="0" aria-valuemax="100"
                                 style="max-width: {{ $moneyPercent }}%">
                                <span class="title">{{ $moneyPercent }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-users"></i> {{ trans('admin.my_leads') }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table datatable" style="width: 95%;">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.id') }}</th>
                        <th>{{ trans('admin.name') }}</th>
                        <th>{{ trans('admin.email') }}</th>
                        <th>{{ trans('admin.phone') }}</th>
                        <th>{{ trans('admin.source') }}</th>
                        <th>{{ trans('admin.agent') }}</th>
                        <th>{{ trans('admin.favorite') }}</th>
                        <th>{{ trans('admin.hot') }}</th>
                        <th>{{ trans('admin.show') }}</th>
                        <th>{{ trans('admin.edit') }}</th>
                        <th>{{ trans('admin.delete') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Lead::where('agent_id', auth()->user()->id)->get() as $lead)
                        <tr>
                            <td>{{ $lead->id }}</td>
                            <td>{{ $lead->first_name .' '.$lead->last_name }}</td>
                            <td><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
                            <td>{{ $lead->phone }}</td>
                            <td>{{ @\App\LeadSource::find($lead->lead_source_id)->name }}</td>
                            <td>{{ @\App\User::find($lead->agent_id)->name }}</td>
                            <td>
                                <i class="fa fa-star Fav" id="Fav{{ $lead->id }}" count="{{ $lead->id }}"
                                   style="@if($lead->favorite) color: #caa42d; @endif"></i>
                            </td>
                            <td>
                                <i class="fa fa-fire Hot" id="Hot{{ $lead->id }}" count="{{ $lead->id }}"
                                   style="@if($lead->hot) color: #dd4b39; @endif"></i>
                            </td>
                            <td><a href="{{ url(adminPath().'/leads/'.$lead->id) }}"
                                   class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                            <td><a href="{{ url(adminPath().'/leads/'.$lead->id.'/edit') }}"
                                   class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
                            <td><a data-toggle="modal" data-target="#delete{{ $lead->id }}"
                                   class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                        </tr>
                        <div id="delete{{ $lead->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ trans('admin.delete') . ' ' . $lead->name }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::open(['method'=>'DELETE','route'=>['leads.destroy',$lead->id]]) !!}
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
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.box-body-->

@endsection
@section('js')
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
    <script type="text/javascript">
        $(document).on('click', '.Fav', function () {
            var id = $(this).attr('count');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/fav_lead')}}",
                method: 'post',
                dataType: 'json',
                data: {id: id, _token: _token},
                beforeSend: function(){
                    $('#Fav'+id).addClass('animated flip');
                },
                success: function (data) {
                    setTimeout(function(){
                        $('#Fav'+id).removeClass('animated flip');
                    }, 1000);
                    if (data.status == 1) {
                        $('#Fav'+id).css('color','#caa42d');
                    }else{
                        $('#Fav'+id).css('color','#161616');
                    }
                },
                error: function() {
                    alert('{{ __('admin.error') }}')
                }
            })
        })
    </script>
    <script type="text/javascript">
        $(document).on('click', '.Hot', function () {
            var id = $(this).attr('count');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/hot_lead')}}",
                method: 'post',
                dataType: 'json',
                data: {id: id, _token: _token},
                beforeSend: function(){
                    $('#Hot'+id).addClass('animated flip');
                },
                success: function (data) {
                    setTimeout(function(){
                        $('#Hot'+id).removeClass('animated flip');
                    }, 1000);
                    if (data.status == 1) {
                        $('#Hot'+id).css('color','#dd4b39');
                    }else{
                        $('#Hot'+id).css('color','#161616');
                    }
                },
                error: function() {
                    alert('{{ __('admin.error') }}')
                }
            })
        })
    </script>
    <script>
        $('.datatable').DataTable();
    </script>

@endsection
