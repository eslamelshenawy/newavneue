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
               href="{{ url(adminPath().'/requests/create') }}">{{ trans('admin.add') }}</a>
            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.id') }}</th>
                    <th>{{ trans('admin.lead') }}</th>
                    <th>{{ trans('admin.unit_type') }}</th>
                    <th>{{ trans('admin.price').' '.trans('admin.from') }}</th>
                    <th>{{ trans('admin.price').' '.trans('admin.to') }}</th>
                    <th>{{ trans('admin.date').' '.trans('admin.start') }}</th>
                    <th>{{ trans('admin.date').' '.trans('admin.end') }}</th>
                    <th>{{ trans('admin.description') }}</th>
                    <th>{{ trans('admin.show') }}</th>
                    <th>{{ trans('admin.edit') }}</th>
                    <th>{{ trans('admin.delete') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($index as $src)
                    <tr>
                        <td>{{ $src->id }}</td>
                        <td>{{ @$src->lead->first_name . ' ' . @$src->lead->last_name }}</td>
                        <td>{{ $src->unit_type }}</td>
                        <td>{{ $src->price_from }}</td>
                        <td>{{ $src->price_to }}</td>
                        <td>{{ Date('Y-m-d',$src->date_from) }}</td>
                        <td>{{ Date('Y-m-d',$src->date_to) }}</td>
                        <td>{{ $src->description }}</td>
                        <td><a href="{{ url(adminPath().'/requests/'.$src->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                        <td><a href="{{ url(adminPath().'/requests/'.$src->id.'/edit') }}"
                               class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
                        <td><a data-toggle="modal" data-target="#delete{{ $src->id }}"
                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
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
                                    {!! Form::open(['method'=>'DELETE','route'=>['requests.destroy',$src->id]]) !!}
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
    {{ $index->links() }}
@endsection

@section('js')
    <script>
        $('.datatable').dataTable({
            
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': true,
            
            'autoWidth': true
        })
    </script>
@stop