@extends('admin.index')

@section('content')
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
               href="{{ url(adminPath().'/leads/create') }}">{{ trans('admin.add') }}</a>
            @if(checkRole('export_excel') or @auth()->user()->type == 'admin')
                <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                   style="margin: 0 7px" href="{{ url(adminPath().'/xlsrequest') }}">Import Leads</a>
            @endif
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="@if(!request()->has('team')) active @endif"><a href="#active_leads" data-toggle="tab"
                                                                              aria-expanded="false">{{ trans('admin.my_leads') }}</a></li>
                    @if(@auth()->user()->type == 'admin')
                        <li class=""><a href="#individual_leads" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.individual_leads') }}</a></li>
                    @endif
                    @if(auth()->user()->type == 'admin' or @\App\Group::where('team_leader_id', auth()->id())->count())
                        <li class="@if(request()->has('team')) active @endif"><a href="#team_leads" data-toggle="tab"
                                                                                 aria-expanded="true">{{ trans('admin.team_leads') }}</a></li>
                    @endif
                    <li class=""><a href="#hot_leads" data-toggle="tab"
                                    aria-expanded="true">{{ trans('admin.hot') . ' ' . trans('admin.leads') }}</a></li>
                    <li class=""><a href="#fav_leads" data-toggle="tab"
                                    aria-expanded="true">{{ trans('admin.favorite') . ' ' . trans('admin.leads') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane @if(!request()->has('team')) active @endif" style="min-height: 650px;" id="active_leads">
                        <button type="button" class="btn btn-info btn-flat" data-toggle="collapse" data-target="#filter" style="margin: 10px 0">{{ __('admin.filter') }}</button>
                        <div id="filter" class="collapse">
                            <div class="form-group col-md-6">
                                <label>{{ trans('admin.from') }}</label>
                                <input type="text" readonly id="dateFrom" class="form-control datepicker" placeholder="{{ trans('admin.from') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ trans('admin.to') }}</label>
                                <input type="text" readonly id="dateTo" class="form-control datepicker" placeholder="{{ trans('admin.to') }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ trans('admin.call_status') }}</label>
                                <select class="form-control select2" id="callStatus" data-placeholder="{{ trans('admin.call_status') }}" style="width: 100%">
                                    <option></option>
                                    @foreach(@\App\CallStatus::get() as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ trans('admin.meeting_status') }}</label>
                                <select class="form-control select2" id="meetingStatus" data-placeholder="{{ trans('admin.meeting_status') }}" style="width: 100%">
                                    <option></option>
                                    @foreach(@\App\MeetingStatus::get() as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>{{ trans('admin.location') }}</label>
                                <select class="form-control select2" id="location" data-placeholder="{{ trans('admin.location') }}" style="width: 100%">
                                    <option></option>
                                    @foreach(@\App\Location::get() as $location)
                                        <option value="{{ $location->id }}">{{ $location->{app()->getLocale() . '_name'} }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <button class="btn btn-success btn-flat" id="filterLeads">
                                    {{ __('admin.get') }}
                                    <i class="fa fa-spinner fa-spin hidden" id="Spinner"></i>
                                </button>
                            </div>
                        </div>
                         <a data-toggle="modal" data-target="#switchLead"
                               class="btn btn-success btn-flat switchLeadModal" >{{ trans('admin.switch') }}</a>
                            <div class="col-md-12">
                            <table class="table datatable" >
                                <thead>
                                <tr>
                                     <th>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="checkAll">
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        </th>
                                    <th>{{ trans('admin.id') }}</th>
                                    <th>{{ trans('admin.personal') .' '.trans('admin.status') }}</th>
                                    <th>{{  trans('admin.scommercial') .' '.trans('admin.status') }}</th>
                                    <th>{{  trans('admin.seen') }}</th>
                                    <th>{{  trans('admin.probability') }}</th>
                                    <th>{{ trans('admin.name') }}</th>
                                    <th>{{ trans('admin.phone') }}</th>
                                    <th>{{ trans('admin.source') }}</th>
                                    <th>{{ trans('admin.agent') }}</th>
                                    <th>{{ trans('admin.type') }}</th>
                                    <th>{{ trans('admin.favorite') }}</th>
                                    <th>{{ trans('admin.hot') }}</th>
                                    <th>{{ trans('admin.option') }}</th>
                                    @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                                        <th>{{ trans('admin.switch') }}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody id="myLeads"></tbody>
                            </table>
                        </div>


                    </div>
                    @if(auth()->user()->type == 'admin')
                        <div class="tab-pane" style="min-height: 650px;" id="individual_leads">
                            <a data-toggle="modal" data-target="#switchLead"
                               class="btn btn-success btn-flat switchLeadModal">{{ trans('admin.switch') }}</a>
                            <table class="table table-hover table-striped datatable1" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="checkAll">
                                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                            </label>
                                        </div>
                                    </th>
                                    <th>{{ trans('admin.id') }}</th>
                                    <th>{{ trans('admin.name') }}</th>
                                    <th>{{ trans('admin.email') }}</th>
                                    <th>{{ trans('admin.phone') }}</th>
                                    <th>{{ trans('admin.source') }}</th>
                                    <th>{{ trans('admin.option') }}</th>
                                @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                                    <th>{{ trans('admin.switch') }}</th>
                                @endif
                                </thead>
                            </table>

                        </div>
                    @endif
                    @if(auth()->user()->type == 'admin' or @\App\Group::where('team_leader_id', auth()->id())->count())
                        <div class="tab-pane @if(request()->has('team')) active @endif" style="min-height: 650px;" id="team_leads">
                            <button type="button" class="btn btn-info btn-flat" data-toggle="collapse" data-target="#Tfilter" style="margin: 10px 0">{{ __('admin.filter') }}</button>
                            <div id="Tfilter" class="collapse">
                                <form>
                                    <div class="form-group col-md-6">
                                        <label>{{ trans('admin.from') }}</label>
                                        <input type="text" readonly id="TdateFrom" name="date_from" class="form-control datepicker" placeholder="{{ trans('admin.from') }}" value="{{ request()->date_from }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ trans('admin.to') }}</label>
                                        <input type="text" readonly id="TdateTo" name="date_to" class="form-control datepicker" placeholder="{{ trans('admin.to') }}" value="{{ request()->date_to }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>{{ trans('admin.call_status') }}</label>
                                        <select class="form-control select2" id="TcallStatus" name="call_status" data-placeholder="{{ trans('admin.call_status') }}" style="width: 100%">
                                            <option></option>
                                            @foreach(@\App\CallStatus::get() as $status)
                                                <option value="{{ $status->id }}" @if(request()->call_status == $status->id) selected @endif>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ trans('admin.meeting_status') }}</label>
                                        <select class="form-control select2" id="TmeetingStatus" name="meeting_status" data-placeholder="{{ trans('admin.meeting_status') }}" style="width: 100%">
                                            <option></option>
                                            @foreach(@\App\MeetingStatus::get() as $status)
                                                <option value="{{ $status->id }}" @if(request()->meeting_status == $status->id) selected @endif>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>{{ trans('admin.location') }}</label>
                                        <select class="form-control select2" id="Tlocation" name="location" data-placeholder="{{ trans('admin.location') }}" style="width: 100%">
                                            <option></option>
                                            @foreach(@\App\Location::get() as $location)
                                                <option value="{{ $location->id }}" @if(request()->location == $location->id) selected @endif>{{ $location->{app()->getLocale() . '_name'} }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>{{ trans('admin.groups') }}</label>
                                        <select class="form-control select2" id="Groups" name="group_id" style="width: 100%" data-placeholder="{{ __('admin.groups') }}">
                                            <option value="0">{{ __('admin.all') }}</option>
                                            @foreach($groups as $group)
                                                <option value="{{ @$group->id }}" @if(request()->group_id == $group->id) selected @endif>{{ @$group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>{{ trans('admin.agent') }}</label>
                                        <select class="form-control select2" id="teamAgent" name="agent_id" style="width: 100%" data-placeholder="{{ __('admin.agent') }}">
                                            <option value="0">{{ __('admin.all') }}</option>
                                            @foreach($Agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="team" value="1">
                                    <div class="form-group col-md-12">
                                        <button class="btn btn-success btn-flat" id="">
                                            {{ __('admin.get') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                             <a data-toggle="modal" data-target="#switchLead"
                               class="btn btn-success btn-flat switchLeadModal" >{{ trans('admin.switch') }}</a>
                            <input type="text" id="searchTeam" placeholder="{{ __('admin.search') }}" class="form-control">
                            <div id="teamData">
                                <table class="table table-hover table-striped datatableTeam" style="width: 100%" id="teamDataTable">
                                    <thead>
                                    <tr>
                                         <th>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="checkAll">
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        </th>
                                        <th>{{ trans('admin.id') }}</th>
                                        <th>{{ trans('admin.personal') .' '.trans('admin.status') }}</th>
                                        <th>{{ trans('admin.scommerical') .' ' .trans('admin.status') }}</th>
                                        <th>{{ trans('admin.personal') }} {{ trans('admin.agent') }}</th>
                                        <th>{{ trans('admin.scommercial') }} {{ trans('admin.agent') }}</th>
                                        <th>{{ trans('admin.name') }}</th>
                                        <th>{{ trans('admin.email') }}</th>
                                        <th>{{ trans('admin.phone') }}</th>
                                        <th>{{ trans('admin.source') }}</th>
                                        <th>{{ trans('admin.option') }}</th>
                                        <th>{{ trans('admin.switch') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="">
                                    @php($commercialAgents = @\App\User::where('residential_commercial', 'commercial')->pluck('id')->toArray())
                                    @php($residentialAgents = @\App\User::where('residential_commercial', 'residential')->pluck('id')->toArray())
                                    @foreach($teams as $lead)
                                        <tr>
                                            <td class="checkbox">
                                                <label>
                                                    <input class="switch" name="checked_leads[]" type="checkbox"
                                                           value={{ $lead->id }}>
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </td>
                                            <td>{{ $lead->id }}</td>
                                            <td>
                                                <i class="fa fa-circle" aria-hidden="true" style="@if(DB::table('lead_actions')->whereIn('user_id', $residentialAgents)->where('lead_id',$lead->id)->count() > 0) color:green;@else color:red @endif"></i>
                                            </td>
                                            <td>
                                                <i class="fa fa-circle" aria-hidden="true" style="@if(DB::table('lead_actions')->whereIn('user_id', $commercialAgents)->where('lead_id',$lead->id)->count() > 0) color:green;@else color:red @endif"></i>
                                            </td>
                                            <td>{{ @App\User::find($lead->agent_id)->name }}</td>
                                            <td>{{ @App\User::find($lead->commercial_agent_id)->name }}</td>
                                            <td>{{ $lead->first_name . ' ' . $lead->last_name }}</td>
                                            <td>
                                                <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                            </td>
                                            <td>{{ $lead->phone }}</td>
                                            <td>{{ @App\LeadSource::find($lead->lead_source_id)->name }}</td>
                                            <td>
                                            <select class="form-control"  onchange="if(this.value=='del'){$('#delete{{ $lead->id }}').modal();} else{location = this.value;}">
                                                <option value="#" disabled selected >Options</option>
                                                <option value="{{ url(adminPath() . '/leads/' . $lead->id) }}"> {{ trans('admin.show') }} </option>
                                                <option value="{{ url(adminPath() . '/leads/' . $lead->id . '/edit') }} ">{{  trans('admin.edit') }}</option>
                                                <option value="del" class="delete" data-toggle="modal" data-target='#delete{{ $lead->id }}' class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</option>
                                            </select>
                                            </td>
                                            <td><a data-toggle="modal" data-target="#switch{{ $lead->id }}" class="btn btn-success btn-flat">{{ trans('admin.switch') }}</a>

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
                                                        <button type="button" class="btn btn-default btn-flat"
                                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                        <a class="btn btn-danger btn-flat" href="{{ url(adminPath() . '/delete-lead/' . $lead->id) }}">{{ trans('admin.delete') }}</a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div id="switch{{ $lead->id }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">{{ trans('admin.switch') . ' ' . trans('admin.lead') }}</h4>
                                                    </div>
                                                    <form action="{{ url(adminPath() . '/switch_leads') }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <select class="select2" name="agent_id"
                                                                    data-placeholder="{{ __('admin.agent') }}" style="width: 100%">
                                                                <option></option>
                                                                @foreach(@App\User::get() as $agent)
                                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" value="{{ $lead->id }}" name="leads[]">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default btn-flat"
                                                                    data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                            <button type="submit"
                                                                    class="btn btn-success btn-flat">{{ trans('admin.switch') }}</button>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                    </tbody>
                                </table>
                                <span id="teamLinks">
                                    {{ $teams->links() }}
                                </span>
                            </div>
                        </div>
                    @endif
                    <div class="tab-pane" style="min-height: 650px;" id="hot_leads">
                        <table class="table table-hover table-striped datatable2" style="width: 100%">
                            <thead>
                            <tr>
                                <th>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="checkAll">
                                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                            </label>
                                        </div>
                                    </th>
                                <th>{{ trans('admin.id') }}</th>
                                <th>{{ trans('admin.name') }}</th>
                                <th>{{ trans('admin.email') }}</th>
                                <th>{{ trans('admin.phone') }}</th>
                                <th>{{ trans('admin.source') }}</th>
                                <th>{{ trans('admin.option') }}</th>
                                @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                                    <th>{{ trans('admin.switch') }}</th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" style="min-height: 650px;" id="fav_leads">
                        <table class="table table-hover table-striped datatable3" style="width: 100%">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.id') }}</th>
                                <th>{{ trans('admin.name') }}</th>
                                <th>{{ trans('admin.email') }}</th>
                                <th>{{ trans('admin.phone') }}</th>
                                <th>{{ trans('admin.source') }}</th>
                                <th>{{ trans('admin.option') }}</th>
                                @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                                    <th>{{ trans('admin.switch') }}</th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="switchLead" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{{ trans('admin.switch')  }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            {!! Form::open(['method'=>'post','url'=>adminPath().'/switch_leads']) !!}
                                            <label>{{ __('admin.personal'). ' ' . __('admin.agent') }}</label>
                                            <select class="select2" name="agent_id"
                                                    data-placeholder="{{ __('admin.agent') }}" style="width: 100%">
                                                <option></option>
                                                @foreach(@\App\User::where('residential_commercial','residential')->get() as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                            <label>{{ __('admin.commercial'). ' ' . __('admin.agent') }}</label>
                                              <select class="select2" name="commercial_agent_id"
                                                    data-placeholder="{{ __('admin.agent') }}" style="width: 100%">
                                                <option></option>
                                                @foreach(@\App\User::where('residential_commercial','commercial')->get() as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="getLeads"></span>
                                        </div>

                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-default btn-flat"
                                                    data-dismiss="modal">{{ trans('admin.close') }}</button>
                                            <button type="submit"
                                                    class="btn btn-success btn-flat">{{ trans('admin.switch') }}</button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
@endsection

@section('js')
    <script>
    //     $(document).on('change', '#teamAgent', function () {
    //         var id = $(this).val();
    //         var _token = '{{ csrf_token() }}';
    //         $.ajax({
    //             url: '{{ url(adminPath() . '/filter_team_leads') }}',
    //             dataType: 'html',
    //             data: {_token: _token, id: id},
    //             type: 'post',
    //             success: function (data) {
    //                 $('#teamData').html(data);
    //             }
    //         })
    //     })
    </script>
    <script>
        setTimeout(function(){
            $('.datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print all',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print selected',
                        exportOptions: {
                            selected: true,
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                select: true,
                pagingType: "full_numbers",
                order: [[ 4, 'asc' ]],

                processing: true,
                createdRow: function ( row, data, index ) {
                if (data.seen==0){
                    $('td', row).addClass('bg-danger');
                }
                },
                serverSide: true,
                ajax: '{{ url(adminPath().'/leads_ajax') }}',
                drawCallback: function ( settings ) {

                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                api.column(4, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        if(group==0){

                            $(rows).eq( i ).before(
                                '<tr class="group table-dark"><td colspan="10">'+'{{ trans('admin.unseen')}}'+'</td></tr>'
                            );
                        }else if(group==1){
                            $(rows).eq( i ).before(
                                '<tr class="group table-dark"><td colspan="10">'+'{{ trans('admin.seen')}}'+'</td></tr>'
                            );
                        }else if(group==2){
                            $(rows).eq( i ).before(
                                '<tr class="group table-dark"><td colspan="10">'+'{{ trans('admin.seenWithAction')}}'+'</td></tr>'
                            );
                        }
                        last = group;
                    }
                });
                },
                columns: [
                    {data: 'checkbox'},
                    {data: 'id'},
                    {data: 'personal_status'},
                    {data: 'commercial_status'},
                    {data: 'seen'},
                    {data: 'probability'},
                    {data: 'name'},
                    //{data: 'email'},
                    {data: 'phone'},
                    {data: 'source'},
                    {data: 'agent'},
                    {data: 'type'},
                    {data: 'fav', searchable: false, sortable: false},
                    {data: 'hot', searchable: false, sortable: false},
                    {data: 'option', searchable: false, sortable: false},
                    /*               {data: 'show', searchable: false, sortable: false},
                    {data: 'edit', searchable: false, sortable: false},
                    @if(checkRole('hard_delete_leads') or checkRole('soft_delete_leads') or auth()->user()->type == 'admin')
                    {data: 'delete', searchable: false, sortable: false},
                    @endif
                    */
                    @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                    {data: 'switch', searchable: false, sortable: false},
                    @endif
                ],
                columnDefs: [
                { "targets": 4 ,
                  "createdCell": function (td, cellData, rowData, row, col) {
                        if($(td).html()==0){
                            $(td).html('<i class="fa fa-circle" aria-hidden="true" style="color: ' + 'red;' + '"></i>');
                        }else if($(td).html()==1){
                            $(td).html('<i class="fa fa-circle" aria-hidden="true" style="color: ' + 'orange;' + '"></i>');
                        }else{
                            $(td).html('<i class="fa fa-circle" aria-hidden="true" style="color: ' + 'green;' + '"></i>');
                        }

                    },
                }
                ],
            });
            $('.datatable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print all'
                    },
                    {
                        extend: 'print',
                        text: 'Print selected',
                        exportOptions: {
                            modifier: {
                                selected: true
                            }
                        }
                    }
                ],
                select: true,
                pagingType: "full_numbers",
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                ajax: '{{ url(adminPath().'/leads_ind_ajax') }}',
                columns: [
                    {data: 'checkbox'},
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'phone'},
                    {data: 'source'},
                    {data: 'option', searchable: false, sortable: false},
                    @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                    {data: 'switch', searchable: false, sortable: false},
                    @endif
                ]
            });
            $('.datatable2').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print all'
                    },
                    {
                        extend: 'print',
                        text: 'Print selected',
                        exportOptions: {
                            modifier: {
                                selected: true
                            }
                        }
                    }

            ],
            select: true,
            pagingType: "full_numbers",
            order: [[0, 'desc']],
            processing: true,
            serverSide: true,
            ajax: '{{ url(adminPath().'/leads_hot_ajax') }}',
            columns: [
                {data: 'checkbox'},
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'phone'},
                {data: 'source'},
                {data: 'option', searchable: false, sortable: false},
                @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                    {data: 'switch', searchable: false, sortable: false},
                @endif
            ]
        });
        $('.datatable3').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print all'
                },
                {
                    extend: 'print',
                    text: 'Print selected',
                    exportOptions: {
                        modifier: {
                            selected: true
                        }
                    }
                }
                ],
                select: true,
                pagingType: "full_numbers",
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                ajax: '{{ url(adminPath().'/leads_fav_ajax') }}',
                columns: [
                     {data: 'checkbox'},
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'phone'},
                    {data: 'source'},
                    {data: 'option', searchable: false, sortable: false},
                    @if(checkRole('switch_leads') or auth()->user()->type == 'admin')
                        {data: 'switch', searchable: false, sortable: false},
                    @endif
                ]
            });
            $('.datatableTeam').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print all'
                    },
                    {
                        extend: 'print',
                        text: 'Print selected',
                        exportOptions: {
                            modifier: {
                                selected: true
                            }
                        }
                    }
                ],
                select: true,
                paging: false,
                order: [[0, 'desc']],

            });

        }, 100);
    </script>
    <script>
        $(document).on('change', '#checkAll', function () {
            if ($("#checkAll").is(':checked')) {
                $('.switch').prop('checked', true);
            } else {
                $('.switch').prop('checked', false);
            }
        });
    </script>
    <script>
        $(document).on('click', '.switchLeadModal', function () {
            $('#getLeads').html('');
            $('.switch').each(function () {
                if ($(this).is(':checked')) {
                    $('#getLeads').append(
                        '<input type="hidden" name="leads[]" value="' + $(this).val() + '">'
                    )
                }
            });
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
                beforeSend: function () {
                    $('#Fav' + id).addClass('animated flip');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#Fav' + id).removeClass('animated flip');
                    }, 1000);
                    if (data.status == 1) {
                        $('#Fav' + id).css('color', '#caa42d');
                    } else {
                        $('#Fav' + id).css('color', '#161616');
                    }
                },
                error: function () {
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
                beforeSend: function () {
                    $('#Hot' + id).addClass('animated flip');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#Hot' + id).removeClass('animated flip');
                    }, 1000);
                    if (data.status == 1) {
                        $('#Hot' + id).css('color', '#dd4b39');
                    } else {
                        $('#Hot' + id).css('color', '#161616');
                    }
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                }
            })
        })
    </script>
    <script>
        $(document).on('click','#filterLeads',function() {
            var location = $('#location').val();
            var meetingStatus = $('#meetingStatus').val();
            var callStatus = $('#callStatus').val();
            var dateTo = $('#dateTo').val();
            var dateFrom = $('#dateFrom').val();
            var _token = '{{ csrf_token() }}';
            var agent_id = '{{ auth()->id() }}';
            $.ajax({
                url: '{{ url(adminPath() . '/filter_leads') }}',
                dataType: 'html',
                data: {
                    _token: _token,
                    location: location,
                    meeting_status: meetingStatus,
                    call_status: callStatus,
                    date_to: dateTo,
                    date_from: dateFrom,
                    agent_id: agent_id
                },
                type: 'post',
                success: function (data) {
                    $('#myLeads').html(data);
                    $('#Spinner').addClass('hidden');
                },
                beforeSend: function() {
                    $('#Spinner').removeClass('hidden');
                },
                error: function() {
                    alert("{{ __('admin.error') }}");
                    $('#Spinner').addClass('hidden');
                }
            });
        });
    </script>

    <script>
        $(document).on('click','#TfilterLeads',function() {
            var location = $('#Tlocation').val();
            var meetingStatus = $('#TmeetingStatus').val();
            var callStatus = $('#TcallStatus').val();
            var dateTo = $('#TdateTo').val();
            var dateFrom = $('#TdateFrom').val();
            var _token = '{{ csrf_token() }}';
            var agent_id = $('#teamAgent').val();
            var group_id = $('#Group').val();
            var type = 'team';
            $.ajax({
                url: '{{ url(adminPath() . '/filter_leads') }}',
                dataType: 'html',
                data: {
                    _token: _token,
                    location: location,
                    meeting_status: meetingStatus,
                    call_status: callStatus,
                    date_to: dateTo,
                    date_from: dateFrom,
                    agent_id: agent_id,
                    type: type,
                    group_id: group_id
                },
                type: 'post',
                success: function (data) {
                    $('#teamData').html(data);
                    $('#Tspinner').addClass('hidden');
                },
                beforeSend: function() {
                    $('#Tspinner').removeClass('hidden');
                },
                error: function() {
                    alert("{{ __('admin.error') }}");
                    $('#Tspinner').addClass('hidden');
                }
            });
        });
    </script>
    <script>
        $(document).on('keyup', '#searchTeam', function() {
            var q = $(this).val();
            var _token = '{{ csrf_token() }}';
            var agents = '{{ json_encode($agent_ids) }}';
            $.ajax({
                url: "{{ url(adminPath() . '/search-team') }}",
                type: "post",
                dataType: "html",
                data: {_token: _token, q: q, agents: agents},
                success: function(data) {
                    $('#teamDataTable').html(data);
                    $('#teamLinks').remove()
                }
            })
        });
    </script>
    <script>
        $(document).on('change', '#Groups', function() {
            var group_id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: '{{ url(adminPath() . '/get_group_agents') }}',
                type: 'post',
                dataType: 'html',
                data: {_token: _token, group_id: group_id},
                success: function(data) {
                    $('#teamAgent').html(data);
                },
                error: function() {
                    alert('{{ __('admin.error') }}')
                }
            })
        });
    </script>
@stop
