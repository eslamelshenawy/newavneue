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
               href="{{ url(adminPath().'/lead_sources/create') }}">{{ trans('admin.add') }}</a>
            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.id') }}</th>
                    <th>{{ trans('admin.name') }}</th>
                    <th>{{ trans('admin.show') }}</th>
                    <th>{{ trans('admin.edit') }}</th>
                    <th>{{ trans('admin.delete') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($index as $src)
                    <tr>
                        <td>{{ $src->id }}</td>
                        <td>{{ $src->name }}</td>
                        <td>
                            <a href="{{ url(adminPath().'/lead_sources/'.$src->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                        </td>
                        <td>
                            @if($src->id != 22 and $src->id != 23 and $src->id != 24)
                                <a href="{{ url(adminPath().'/lead_sources/'.$src->id.'/edit') }}"
                                   class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a>
                            @endif
                        </td>
                        <td>
                            @if($src->id != 22 and $src->id != 23 and $src->id != 24)
                                <a data-toggle="modal" data-target="#delete{{ $src->id }}"
                                   class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a>
                            @endif
                        </td>
                    </tr>
                    <div id="delete{{ $src->id }}" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ trans('admin.delete') . ' ' . $src->name }}</p>
                                </div>
                                <div class="modal-footer">
                                    {!! Form::open(['method'=>'DELETE','route'=>['lead_sources.destroy',$src->id]]) !!}
                                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    <button type="submit" class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
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