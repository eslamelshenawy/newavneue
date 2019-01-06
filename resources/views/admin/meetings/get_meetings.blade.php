<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ trans('admin.date') }}</th>
        <th>{{ trans('admin.contact') }}</th>
        <th>{{ trans('admin.show') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach(@$meetings as $meeting)
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
                        <strong>{{ trans('admin.lead') }} : </strong>{{ @\App\Lead::find($meeting->lead_id)->first_name . ' ' . @\App\Lead::find($meeting->lead_id)->last_name }}
                        <br><hr>
                        @if($meeting->contact_id > 0)
                            <strong>{{ trans('admin.contact') }} : </strong>{{ @\App\Contact::find($meeting->contact_id)->name }}
                            <br><hr>
                        @endif
                        <strong>{{ trans('admin.agent') }} : </strong>{{ @\App\User::find($meeting->user_id)->name }}
                        <br><hr>
                        <strong>{{ trans('admin.duration') }} : </strong>{{ $meeting->duration }}
                        <br><hr>
                        <strong>{{ trans('admin.date') }} : </strong>{{ date('Y-m-d',$meeting->date) }}
                        <br><hr>
                        <strong>{{ trans('admin.time') }} : </strong>{{ $meeting->time }}
                        <br><hr>
                        <strong>{{ trans('admin.location') }} : </strong>{{ $meeting->location }}
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
                        <strong>{{ trans('admin.probability') }} : </strong>{{ $meeting->probability }}%
                        <br><hr>
                        <strong>{{ trans('admin.description') }} : </strong>{{ $meeting->description }}
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