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
            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
            
                    <th>{{ trans('admin.id') }}</th>
                    <th>{{ trans('admin.route') }}</th>
                    <th>{{ trans('admin.title') }}</th>
                    <th>{{ trans('admin.type') }}</th>
                    <th>{{ trans('admin.agent') }}</th>
                    <th>{{ trans('admin.date') }}</th>
                    <th>{{ trans('admin.show') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ __('admin.'.$log->route) }}</td>
                        <td>{{ $log->{app()->getLocale().'_title'} }}</td>
                        <td>{{ __('admin.'.$log->type) }}</td>
                        <td>
                            <a href="{{ url(adminPath().'/agent/'.@$log->user_id) }}">{{ @\App\User::find($log->user_id)->name }}</a>
                        </td>
                        <td>{{ $log->created_at }}</td>
                        <td>
                            @if($log->type != 'log')
                            <a href="{{ url(adminPath().'/logs/'.$log->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
@stop