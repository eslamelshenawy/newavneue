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
                    <th>{{ trans('admin.name') }}</th>
                    <th>{{ trans('admin.location') }}</th>
                    <th>{{ trans('admin.developer') }}</th>
                    <th>{{ trans('admin.updated_at') }}</th>
                    <th>{{ trans('admin.operation') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\ProjectRequest::get() as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->location }}</td>
                        <td>{{ $row->developer }}</td>
                        <td>{{ date("d-m-Y",$row->portal_updated_at) }}</td>
                        <td><a href="{{ url(adminPath().'/accept/'.$row->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.accept') }}</a>
                        <a data-toggle="modal" data-target="#delete{{ $row->id }}"
                               class="btn btn-danger btn-flat">{{ trans('admin.refuse') }}</a></td>
                    </tr>
                    <div id="delete{{ $row->id }}" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ trans('admin.delete') . ' ' . $row->name }}</p>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ url(adminPath().'/deletepush/'.$row->id) }}"
                                       class="btn btn-danger btn-flat">{{ trans('admin.refuse') }}</a></td>
                                </div>
                            </div>

                        </div>
                    </div>
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