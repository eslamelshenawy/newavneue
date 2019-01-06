<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ trans('admin.date') }}</th>
        <th>{{ trans('admin.contact') }}</th>
        <th>{{ trans('admin.show') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach(@$calls as $call)
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
                        <strong>{{ trans('admin.probability') }} : </strong>{{ $call->probability }}%
                        <br><hr>
                        <strong>{{ trans('admin.phone') }} : </strong>{{ $call->phone }}
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