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
        <th>{{ trans('admin.agent') }}</th>
        <th>{{ trans('admin.name') }}</th>
        <th>{{ trans('admin.email') }}</th>
        <th>{{ trans('admin.phone') }}</th>
        <th>{{ trans('admin.source') }}</th>
        <th>{{ trans('admin.show') }}</th>
        <th>{{ trans('admin.edit') }}</th>
        <th>{{ trans('admin.delete') }}</th>
        <th>{{ trans('admin.switch') }}</th>
    </tr>
    </thead>
    <tbody id="teamData">
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
            <td>{{ @App\User::find($lead->agent_id)->name }}</td>
            <td>{{ $lead->first_name . ' ' . $lead->last_name }}</td>
            <td>
                <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
            </td>
            <td>{{ $lead->phone }}</td>
            <td>{{ @App\LeadSource::find($lead->lead_source_id)->name }}</td>
            <td><a href="{{ url(adminPath() . '/leads/' . $lead->id) }}" class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
            <td><a href="{{ url(adminPath() . '/leads/' . $lead->id . '/edit') }}" class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
            <td><a data-toggle="modal" data-target="#delete{{ $lead->id }}" class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
            <td><a data-toggle="modal" data-target="#switch{{ $lead->id }}" class="btn btn-success btn-flat">{{ trans('admin.switch') }}</a></td>
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