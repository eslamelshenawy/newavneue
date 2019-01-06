<div class="col-md-12">
    @if($type == 'new_home')
        <table class="table table-hover table-striped datatable">
            <thead>
            <tr>
                <th>{{ trans('admin.id') }}</th>
                <th>{{ trans('admin.meter_price') }}</th>
                <th>{{ trans('admin.area') }}</th>
                <th>{{ trans('admin.title') }}</th>
                <th>{{ trans('admin.show') }}</th>
                <th>{{ trans('admin.edit') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->meter_price }}</td>
                    <td>{{ $row->area }}</td>
                    <td>{{ $row->{app()->getLocale().'_name'} }}</td>
                    <td><a href="{{ url(adminPath().'/projects/'.$row->id) }}"
                           class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                    <td><a href="{{ url(adminPath().'/projects/'.$row->id.'/edit') }}"
                           class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
                </tr>
            </tbody>
            @endforeach
        </table>
    @elseif($type == 'resale')
        <table class="table table-hover table-striped datatable">
            <thead>
            <tr>
                <th>{{ trans('admin.property') }}</th>
                <th>{{ trans('admin.title') }}</th>
                <th>{{ trans('admin.status') }}</th>
                <th>{{ trans('admin.location') }}</th>
                <th>{{ trans('admin.price') }}</th>
                <th>{{ trans('admin.rooms') }}</th>
                <th>{{ trans('admin.bathrooms') }}</th>
                <th>{{ trans('admin.area') }}</th>
                <th>{{ trans('admin.delivery_date') }}</th>
                <th>{{ trans('admin.show') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $resaleUnit)
                <tr>
                    <td><img src="{{ url('uploads/'.$resaleUnit->image) }}" width="75 px"></td>
                    <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                    <td>{{ trans('admin.'.$resaleUnit->availability) }}</td>
                    <td>{{ @\App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                    <td>{{ $resaleUnit->total }}</td>
                    <td>{{ $resaleUnit->rooms }}</td>
                    <td>{{ $resaleUnit->bathrooms }}</td>
                    <td>{{ $resaleUnit->area }}</td>
                    <td>{{ $resaleUnit->delivery_date }}</td>
                    <td><a href="{{ url(adminPath().'/resale_units/'.$resaleUnit->id) }}"
                           class="btn btn-flat btn-primary">{{ trans('admin.show') }}</a>
                    </td>
                </tr>
            </tbody>
            @endforeach
        </table>
    @elseif($type == 'rental')
        <table class="table table-hover table-striped datatable">
            <thead>
            <tr>
                <th>{{ trans('admin.property') }}</th>
                <th>{{ trans('admin.title') }}</th>
                <th>{{ trans('admin.status') }}</th>
                <th>{{ trans('admin.location') }}</th>
                <th>{{ trans('admin.rent') }}</th>
                <th>{{ trans('admin.rooms') }}</th>
                <th>{{ trans('admin.bathrooms') }}</th>
                <th>{{ trans('admin.area') }}</th>
                <th>{{ trans('admin.delivery_date') }}</th>
                <th>{{ trans('admin.show') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $resaleUnit)
                <tr>
                    <td><img src="{{ url('/uploads/'.$resaleUnit->image) }}" width="50px"></td>
                    <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                    <td>{{ $resaleUnit->availability }}</td>
                    <td>{{ @App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                    <td>{{ $resaleUnit->rent }}</td>
                    <td>{{ $resaleUnit->rooms }}</td>
                    <td>{{ $resaleUnit->bathrooms }}</td>
                    <td>{{ $resaleUnit->area }}</td>
                    <td>{{ $resaleUnit->delivery_date }}</td>
                    <td><a href="{{ url(adminPath().'/rental_units/'.$resaleUnit->id) }}"
                           class="btn btn-flat btn-primary">{{ trans('admin.show') }}</a>
                    </td>
                </tr>
            </tbody>
            @endforeach
        </table>
    @elseif($type == 'lands')
        <table class="table table-hover table-striped datatable">
            <thead>
            <tr>
                <th>{{ trans('admin.id') }}</th>
                <th>{{ trans('admin.title') }}</th>
                <th>{{ trans('admin.lead') }}</th>
                <th>{{ trans('admin.location') }}</th>
                <th>{{ trans('admin.show') }}</th>
                <th>{{ trans('admin.edit') }}</th>
                <th>{{ trans('admin.delete') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $land)
                <tr>
                    <td>{{ $land->id }}</td>
                    <td>{{ $land->{app()->getLocale().'_title'} }}</td>
                    <td>{{ @App\Lead::find($land->lead_id)->first_name }}</td>
                    <td>
                        {{ @App\Location::find($land->location)->{app()->getLocale().'_name'} }}
                    </td>
                    <td><a href="{{ url(adminPath().'/lands/'.$land->id) }}"
                           class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                    <td><a href="{{ url(adminPath().'/lands/'.$land->id.'/edit') }}"
                           class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
                    <td><a data-toggle="modal" data-target="#delete{{ $land->id }}"
                           class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                </tr>
                <div id="delete{{ $land->id }}" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('admin.delete') . ' ' . $land->name }}</p>
                            </div>
                            <div class="modal-footer">
                                {!! Form::open(['method'=>'DELETE','route'=>['lands.destroy',$land->id]]) !!}
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
    @endif
</div>