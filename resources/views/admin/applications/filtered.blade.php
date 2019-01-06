<div class="tab-content" id="apps">
    <div id="under_review" class="tab-pane fade in active">
        <table class="table table-hover table-striped datatable">
            <thead>
            <tr>
                <th>{{ trans('admin.name') }}</th>
                <th>{{ trans('admin.category') }}</th>
                <th>{{ trans('admin.status') }}</th>
                <th>{{ trans('admin.linkedin') }}</th>
                <th>{{ trans('admin.cv') }}</th>
                <th>{{ trans('admin.show') }}</th>
                <th>{{ trans('admin.edit') }}</th>
                <th>{{ trans('admin.delete') }}</th>
            </tr>
            </thead>
            <tbody >
            @foreach($applications as $application)
                <tr>
                    <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                    <td>{{ @$application->job_category->{app()->getLocale().'_name'} }} - {{ @$application->job_title->{app()->getLocale().'_name'} }}</td>
                    <td>{{ $application->acceptness }}</td>
                    <td><a href="{{ $application->linkedin }}">Linked In</a></td>
                    <td><a href="{{ url('uploads/'.$application->cv) }}">CV</a></td>
                    <td><a href="{{ url(adminPath().'/applications/'.$application->id) }}" class="btn btn-default">{{ __('admin.show') }}</a></td>
                    <td><a class="btn btn-warning" href="{{ url(adminPath().'/applications/'.$application->id.'/edit') }}">{{ __('admin.edit') }}</a></td>
                    <td><a data-toggle="modal" data-target="#delete{{ $application->id }}"
                           class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                </tr>
                <div id="delete{{ $application->id }}" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('admin.delete') . ' ' . $application->{app()->getLocale().'_name'} }}</p>
                            </div>
                            <div class="modal-footer">
                                {!! Form::open(['method'=>'DELETE','route'=>['applications.destroy',$application->id]]) !!}
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