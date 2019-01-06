@foreach($projects as $row)
    <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->{app()->getLocale().'_name'} }}</td>
        <td>
            <a href="{{ url(adminPath().'/developers/'.$row->developer_id) }}">{{ @App\Developer::find($row->developer_id)->{app()->getLocale().'_name'} }}</a>
        </td>
        <td>{{ @\App\Phase::where('project_id',$row->id)->count() }}</td>
        {{--<td>--}}
        {{--@if($row->featured == 0)--}}
        {{--<a href="{{ url(adminPath().'/project_featured/'.$row->id) }}"><span class="fa fa-star"></span></a>--}}
        {{--@elseif($row->featured == 1)--}}
        {{--<a href="{{ url(adminPath().'/project_un_featured/'.$row->id) }}"><span class="fa fa-star featured"></span></a>--}}
        {{--@endif--}}
        {{--</td>--}}
        <td><a href="{{ url(adminPath().'/projects/'.$row->id) }}"
               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
        <td><a href="{{ url(adminPath().'/projects/'.$row->id.'/edit') }}"
               class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
        <td><a data-toggle="modal" data-target="#delete{{ $row->id }}"
               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
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
                    {!! Form::open(['method'=>'DELETE','route'=>['projects.destroy',$row->id]]) !!}
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