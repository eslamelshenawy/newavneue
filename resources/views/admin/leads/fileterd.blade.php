@foreach($leads as $lead)
    <tr>
        <td>{{ $lead->id }}</td>
        <td><i class="fa fa-circle" aria-hidden="true" style="@if(DB::table('lead_actions')->where('user_id',$lead->id)->count() > 0) color:green;@else color:red @endif"></i></td>
        <td>{{ $lead->first_name . ' ' . $lead->last_name }}</td>
        <td><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
        <td>{{ $lead->phone }}</td>

        <td>{{ @\App\LeadSource::find($lead->lead_source_id)->name }}</td>
        <td>{{ @$lead->agent->name }}</td>
        <td>  @if (@App\Request::where('lead_id', $lead->id)->where('unit_type', 'personal')->where('unit_type', 'commercial')->count() > 0)
                @php($leadType = __('admin.personal') . ' - ' . __('admin.commercial'))
            @elseif(@App\Request::where('lead_id', $lead->id)->where('unit_type', 'personal')->where('unit_type', '!=', 'commercial')->count() > 0)
                @php($leadType = __('admin.personal'))
            @elseif(@App\Request::where('lead_id', $lead->id)->where('unit_type', '!=', 'personal')->where('unit_type','commercial')->count() > 0)
                @php($leadType = __('admin.commercial'))
            @else
                @php($leadType = __('admin.personal'))
            @endif
            {{ $leadType }}
        </td>
        <td>
            @if ($lead->favorite)
                @php($fcolor = 'color: #caa42d')
            @else
                @php($fcolor = '')
            @endif

            <i class="fa fa-star Fav" id="Fav{{ $lead->id }}" count="{{ $lead->id }}" style="{{ $fcolor }}"></i>
        </td>
        <td>
            @if ($lead->hot)
                @php($color = 'color: #dd4b39')
            @else
                @php($color = '')
            @endif
            <i class="fa fa-fire Hot" id="Hot{{ $lead->id }}" count="{{ $lead->id }}" style="'{{$color }}"></i>
        </td>
        <td>
            <a href="{{ url(adminPath() . '/leads/' . $lead->id) }}" class="btn btn-primary btn-flat">
                {{ __('admin.show') }}
            </a>
        </td>
        <td>
            <a href="{{ url(adminPath() . '/leads/' . $lead->id . '/edit') }}" class="btn btn-warning btn-flat">
                {{ __('admin.edit') }}
            </a>
        </td>
        <td>
            <a data-toggle="modal" data-target="#delete{{ $lead->id }}"
               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a>
        </td>
        <td>
            <a data-toggle="modal" data-target="#switch{{ $lead->id }}"
               class="btn btn-success btn-flat">{{ trans('admin.switch') }}</a>
        </td>
    </tr>
    <div id="delete{{ $lead->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{  trans('admin.delete') . ' ' . trans('admin.lead') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('admin.delete') . ' ' . $lead->name }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat"
                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                    <a class="btn btn-danger btn-flat"
                       href="{{ url(adminPath() . '/delete-lead/' . $lead->id) }}">{{ trans('admin.delete') }}</a>
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
                            @foreach (@\App\User::get() as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>';
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