@extends('admin.index')

@section('content')
    <div class="col-md-9">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                {!! Form::open(['url' => adminPath().'/calls']) !!}
                <div class="form-group @if($errors->has('lead_id')) has-error @endif @if(request()->has('lead')) hidden @endif">
                    <label>{{ trans('admin.lead') }}</label>
                    <select name="lead_id" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.lead') }}" id="lead_id">
                        <option></option>
                        @foreach(@App\Lead::getAgentLeads() as $lead)
                            <option value="{{ $lead->id }}"
                                    @if(old('lead_id') == $lead->id) selected
                                    @elseif(request()->lead == $lead->id) selected @endif>
                                {{ $lead->first_name . ' ' . $lead->last_name }}
                                -
                                @if($lead->agent_id == auth()->id())
                                    {{ __('admin.my_lead') }}
                                @else
                                    {{ __('admin.team_lead', ['agent' => @$lead->agent->name]) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <span id="contacts">
                    @if(request()->has('lead'))
                        <div class="form-group @if($errors->has('contact_id')) has-error @endif">
                            <label>{{ trans('admin.contact') }}</label>
                            <select name="contact_id" class="form-control select2" id="Contact_id" style="width: 100%"
                                    data-placeholder="{{ trans('admin.contact') }}">
                                <option value="0">{{ trans('admin.lead') }}</option>
                                @foreach(@\App\Contact::where('lead_id',request()->lead)->get() as $contact)
                                    <option value="{{ $contact->id }}">
                                        {{ $contact->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <span id="getPhones">
                        <div class="form-group">
                            <label>{{ trans('admin.phone') }}</label>
                            <select name="phone" class="form-control select2" id="phone" style="width: 100%"
                                    data-placeholder="{{ trans('admin.phone') }}">
                                <option value="{{ @$lead->phone }}">{{ @$lead->phone }}</option>
                                @if(@$lead->other_phones != null)
                                    @php($allPhones = json_decode(@$lead->other_phones))
                                    @foreach($allPhones as $phones)
                                        @foreach($phones as $phone => $v)
                                            <option value="{{ $phone }}">
                                        {{ $phone }}
                                    </option>
                                        @endforeach
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        </span>
                    @endif
                </span>
                
                <div class="form-group @if($errors->has('call_status_id')) has-error @endif">
                    <label>{{ trans('admin.call_status') }}</label>
                    <select class="form-control select2" name="call_status_id" id="callStatus" data-placeholder="{{ __('admin.call_status') }}">
                        <option></option>
                        @foreach(@\App\CallStatus::get() as $status)
                            <option value="{{ $status->id }}" next="{{ $status->has_next_action }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group @if($errors->has('duration')) has-error @endif">
                    <label>{{ trans('admin.duration') }}</label>
                    {!! Form::number('duration','',['class' => 'form-control', 'placeholder' => trans('admin.duration')]) !!}
                </div>


                <div class="form-group @if($errors->has('date')) has-error @endif">
                    <label>{{ trans('admin.date') }}</label>
                    <div class="input-group">
                        {!! Form::text('date','',['class' => 'form-control datepicker', 'placeholder' => trans('admin.date'),'readonly'=>'']) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group @if($errors->has('probability')) has-error @endif">
                    <label>{{ trans('admin.probability') }}</label>
                    <select class="form-control select2" name="probability" data-placeholder="{{ __('admin.probability') }}">
                        <option></option>
                        <option value="highest">{{ __('admin.highest') }}</option>
                        <option value="high">{{ __('admin.high') }}</option>
                        <option value="normal">{{ __('admin.normal') }}</option>
                        <option value="low">{{ __('admin.low') }}</option>
                        <option value="lowest">{{ __('admin.lowest') }}</option>
                    </select>
                </div>

                <div class="form-group @if($errors->has('projects')) has-error @endif">
                    <label>{{ trans('admin.projects') }}</label>
                    <select multiple class="form-control select2" name="projects[]" style="width: 100%"
                            data-placeholder="{{ trans('admin.projects') }}">
                        <option></option>
                        @foreach(@\App\Project::get() as $project)
                            <option value="{{ $project->id }}">{{ $project->{app()->getLocale().'_name'} }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group @if($errors->has('description')) has-error @endif">
                    <label>{{ trans('admin.description') }}</label>
                    {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-success btn-flat"
                            id="addAction">{{ trans('admin.next_action') }}</button>
                    <button type="button" class="btn btn-danger btn-flat hidden"
                            id="removeAction">{{ trans('admin.remove') . ' ' . trans('admin.next_action') }}</button>
                </div>
                <br/>
                <span id="nextAction"></span>
                <span id="goldenQuestions"></span>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.all_calls') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
            <span id="getCalls">
                @if(request()->has('lead'))
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.date') }}</th>
                            <th>{{ trans('admin.contact') }}</th>
                            <th>{{ trans('admin.show') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@\App\Call::where('lead_id',request()->lead)->latest()->get() as $call)
                            <tr>
                            <td>{{ date('Y-m-d',$call->date) }}</td>
                            <td>
                                @if($call->contact_id > 0)
                                    {{ @\App\Contact::find($call->contact_id)->name }}
                                @else
                                    {{ @\App\Lead::find($call->lead_id)->first_name . ' ' . @\App\Lead::find($call->lead_id)->last_name }}
                                @endif
                            </td>
                            <td><a data-toggle="modal" data-target="#getCall{{ $call->id }}"
                                   class="btn btn-primary btn-flat"> {{ trans('admin.show') }} </a> </td>
                        </tr>
                            <div id="getCall{{ $call->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.call') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <strong>{{ trans('admin.lead') }}
                                            : </strong>{{ @\App\Lead::find($call->lead_id)->first_name . ' ' . @\App\Lead::find($call->lead_id)->last_name }}
                                        <br><hr>
                                        @if($call->contact_id > 0)
                                            <strong>{{ trans('admin.contact') }}
                                                : </strong>{{ @\App\Contact::find($call->contact_id)->name }}
                                            <br>
                                            <hr>
                                        @endif
                                        <strong>{{ trans('admin.agent') }}
                                            : </strong>{{ @\App\User::find($call->user_id)->name }}
                                        <br><hr>
                                        <strong>{{ trans('admin.duration') }} : </strong>{{ $call->duration }}
                                        <br><hr>
                                        <strong>{{ trans('admin.date') }} : </strong>{{ date('Y-m-d',$call->date) }}
                                        <br><hr>
                                        @php($projects = json_decode($call->projects))
                                        <strong>{{ trans('admin.projects') }} : </strong>
                                        @foreach($projects as $project)
                                            @if(!$loop->last)
                                                {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }} -
                                            @else
                                                {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }}
                                            @endif
                                        @endforeach
                                        <br><hr>
                                        <strong>{{ trans('admin.probability') }} : </strong>{{ $call->probability }}%
                                        <br><hr>
                                        <strong>{{ trans('admin.phone') }} : </strong>{{ $call->phone }}
                                        <br><hr>
                                        <strong>{{ trans('admin.description') }} : </strong>{{ $call->description }}
                                        <br><hr>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </span>
            </div>
        </div>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.all_meetings') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
            <span id="getMeetings">
                @if(request()->has('lead'))
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.date') }}</th>
                            <th>{{ trans('admin.contact') }}</th>
                            <th>{{ trans('admin.show') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@\App\Meeting::where('lead_id',request()->lead)->latest()->get() as $meeting)
                            <tr>
                            <td>{{ date('Y-m-d',$meeting->date) }}</td>
                            <td>
                                @if($meeting->contact_id > 0)
                                    {{ @\App\Contact::find($meeting->contact_id)->name }}
                                @else
                                    {{ @\App\Lead::find($meeting->lead_id)->first_name . ' ' . @\App\Lead::find($meeting->lead_id)->last_name }}
                                @endif
                            </td>
                            <td><a data-toggle="modal" data-target="#getMeeting{{ $meeting->id }}"
                                   class="btn btn-primary btn-flat"> {{ trans('admin.show') }} </a> </td>
                        </tr>
                            <div id="getMeeting{{ $meeting->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.meeting') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <strong>{{ trans('admin.lead') }}
                                            : </strong>{{ @\App\Lead::find($meeting->lead_id)->first_name . ' ' . @\App\Lead::find($meeting->lead_id)->last_name }}
                                        <br><hr>
                                        @if($meeting->contact_id > 0)
                                            <strong>{{ trans('admin.contact') }}
                                                : </strong>{{ @\App\Contact::find($meeting->contact_id)->name }}
                                            <br>
                                            <hr>
                                        @endif
                                        <strong>{{ trans('admin.agent') }}
                                            : </strong>{{ @\App\User::find($meeting->user_id)->name }}
                                        <br><hr>
                                            <strong>{{ trans('admin.duration') }} : </strong>{{ $meeting->duration }}
                                        <br><hr>
                                            <strong>{{ trans('admin.date') }}
                                                : </strong>{{ date('Y-m-d',$meeting->date) }}
                                        <br><hr>
                                            <strong>{{ trans('admin.time') }} : </strong>{{ $meeting->time }}
                                        <br><hr>
                                            <strong>{{ trans('admin.location') }} : </strong>{{ $meeting->location }}
                                        <br><hr>
                                            <strong>{{ trans('admin.probability') }}
                                                : </strong>{{ $meeting->probability }}%
                                        <br><hr>
                                        @php($projects = json_decode($meeting->projects))
                                        <strong>{{ trans('admin.projects') }} : </strong>
                                        @foreach($projects as $project)
                                            @if(!$loop->last)
                                                {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }} -
                                            @else
                                                {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }}
                                            @endif
                                        @endforeach
                                        <br><hr>
                                            <strong>{{ trans('admin.description') }}
                                                : </strong>{{ $meeting->description }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </span>
            </div>
        </div>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.all_requests') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
            <span id="getRequests">
                @if(request()->has('lead'))
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.delivery_date') }}</th>
                            <th>{{ trans('admin.show') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@\App\Request::where('lead_id',request()->lead)->latest()->get() as $req)
                            <tr>
                            <td>
                                {{ $req->date }}
                            </td>
                            <td><a data-toggle="modal" data-target="#getReq{{ $req->id }}"
                                   class="btn btn-primary btn-flat"> {{ trans('admin.show') }} </a> </td>
                        </tr>
                            <div id="getReq{{ $req->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.request') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <strong>{{ trans('admin.id') }} : </strong>{{ $req->id }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.lead') }}
                                            : </strong>{{ @\App\Lead::find($req->lead_id)->first_name . ' ' . @\App\Lead::find($req->lead_id)->last_name }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.price') }} : </strong>{{ $req->price_from }} <i
                                                class="fa fa-arrows-h"></i> {{ $req->price_to }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.area') }} : </strong>{{ $req->area_from }} <i
                                                class="fa fa-arrows-h"></i> {{ $req->area_to }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.location') }}
                                            : </strong>{{ @\App\Location::find($req->location)->{app()->getLocale().'_name'} }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.delivery_date') }}: </strong>{{ $req->date }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.down_payment') }}: </strong>{{ $req->down_payment }}
                                        <br>
                                        <hr>
                                        <strong>{{ trans('admin.notes') }} : </strong>{{ $req->notes }}
                                        <br>
                                        <hr>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </span>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('change', '#lead_id', function () {
            var id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_calls_contacts')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#contacts').html(data);
                    $('.select2').select2();
                }
            });
            $.ajax({
                url: "{{ url(adminPath().'/get_calls')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getCalls').html(data);
                }
            });

            $.ajax({
                url: "{{ url(adminPath().'/get_meetings')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getMeetings').html(data);
                }
            });

            $.ajax({
                url: "{{ url(adminPath().'/get_requests')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getRequests').html(data);
                }
            })
        })
    </script>
    <script>
        $(document).on('change', '#Contact_id', function () {
            var contact_id = $(this).val();
            var lead_id = $('#lead_id').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_phones')}}",
                method: 'post',
                dataType: 'html',
                data: {contact_id: contact_id, _token: _token, lead: lead_id},
                success: function (data) {
                    $('#getPhones').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '#addAction', function () {
            $('#nextAction').html('<div class="well" id="action">' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.to_do_type") }}</label>' +
                '<select class="form-control select2" name="to_do_type" data-placeholder="{{ trans("admin.to_do_type") }}" style="width: 100%">' +
                '<option></option>' +
                '<option value="call">{{ trans("admin.call") }}</option>' +
                '<option value="meeting">{{ trans("admin.meeting") }}</option>' +
                '<option value="other">{{ trans("admin.other") }}</option>' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.due_date") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::text("to_do_due_date","",["class" => "form-control datepicker", "placeholder" => trans("admin.due_date"),"readonly"=>""]) !!}' +
                '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>{{ trans("admin.description") }}</label>' +
                '{!! Form::textarea("to_do_description","",["class" => "form-control", "placeholder" => trans("admin.description"),"rows"=>5]) !!}' +
                '</div>' +
                '</div>')
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $(this).addClass('hidden');
            $('#removeAction').removeClass('hidden');
        });

        $(document).on('click', '#removeAction', function () {
            $('#action').remove();
            $(this).addClass('hidden');
            $('#addAction').removeClass('hidden');
        })

    </script>
    <script>
        $(document).on('click', '#addGoldenQuestion', function () {
            $('#goldenQuestions').html('<div id="questions" class="well">' +
                '<div class="form-group">' +
                '{!! Form::label(trans("admin.location")) !!}' +
                '<select class="select2 form-control" id="location" name="req_location" style="width: 100%"' +
                'data-placeholder="{{ trans("admin.location") }}" required>' +
                '<option></option>' +
                '@foreach(@\App\Location::all() as $location)' +
                '<option value="{{ $location->id }}">{{ $location->{app()->getLocale()."_name"} }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">' +
                '<label> {{ trans("admin.price_from") }}</label>' +
                '<input type="number" required name="req_price_from" class="form-control" value="{{ old("price_from") }}"' +
                'placeholder="{{ trans("admin.from") }}">' +
                '</div>' +
                '<div class="form-group col-xs-6">' +
                '<label> {{ trans("admin.price_to") }}</label>' +
                '<input type="number" required name="req_price_to" class="form-control" value="{{ old("price_to") }}"' +
                'placeholder="{{ trans("admin.to") }}">' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">' +
                '<label> {{ trans("admin.area_from") }}</label>' +
                '<input type="number" required name="req_area_from" class="form-control" value="{{ old("area_from") }}"' +
                'placeholder="{{ trans("admin.from") }}">' +
                '</div>' +
                '<div class="form-group col-xs-6">' +
                '<label> {{ trans("admin.area_to") }}</label>' +
                '<input type="number" required name="req_area_to" class="form-control" value="{{ old("area_to") }}"' +
                'placeholder="{{ trans("admin.to") }}">' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>{{ trans("admin.delivery_date") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::text("req_date","",["class" => "form-control", "placeholder" => trans("admin.delivery_date"),"readonly"=>"","id"=>"datepicker","required"=>""]) !!}' +
                '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>{{ trans("admin.down_payment") }}</label>' +
                '<input type="number" required class="form-control" name="req_down_payment"' +
                'placeholder="{{ trans("admin.down_payment") }}">' +
                '</div>' +
                '<div class="form-group">' +
                '<label> {{ trans("admin.notes") }}</label>' +
                '<textarea name="req_notes" class="form-control" value="{{ old("notes") }}"' +
                'placeholder="{!! trans("admin.notes") !!}" rows="6"></textarea>' +
                '</div>' +
                '</div>')
            $('.select2').select2();
            $('#datepicker').datepicker({
                autoclose: true,
                format: " yyyy",
                viewMode: "years",
                minViewMode: "years",
            });
            $(this).addClass('hidden');
            $('#removeGoldenQuestion').removeClass('hidden');
        });

        $(document).on('click', '#removeGoldenQuestion', function () {
            $('#questions').remove();
            $(this).addClass('hidden');
            $('#addGoldenQuestion').removeClass('hidden');
        })

    </script>
    <script>
        $(document).on('change', '#callStatus', function() {
            var action = $('option:selected', this).attr('next');
            if (action == 1) {
                $('#nextAction').html('<div class="well" id="action">' +
                    '<div class="form-group col-md-6">' +
                    '<label>{{ trans("admin.to_do_type") }}</label>' +
                    '<select class="form-control select2" name="to_do_type" data-placeholder="{{ trans("admin.to_do_type") }}" style="width: 100%">' +
                    '<option></option>' +
                    '<option value="call">{{ trans("admin.call") }}</option>' +
                    '<option value="meeting">{{ trans("admin.meeting") }}</option>' +
                    '<option value="other">{{ trans("admin.other") }}</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<label>{{ trans("admin.due_date") }}</label>' +
                    '<div class="input-group">' +
                    '{!! Form::text("to_do_due_date","",["class" => "form-control datepicker", "placeholder" => trans("admin.due_date"),"readonly"=>""]) !!}' +
                    '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>{{ trans("admin.description") }}</label>' +
                    '{!! Form::textarea("to_do_description","",["class" => "form-control", "placeholder" => trans("admin.description"),"rows"=>5]) !!}' +
                    '</div>' +
                    '</div>')
                $('.select2').select2();
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd'
                });
                $('#addAction').addClass('hidden');
                $('#removeAction').removeClass('hidden');
            } else {
                $('#action').remove();
                $('#removeAction').addClass('hidden');
                $('#addAction').removeClass('hidden');
            }
        })
    </script>
@endsection