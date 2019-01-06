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
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li @if(!session()->has('return_to_suggestions')) class="active" @endif><a href="#main_info" data-toggle="tab"
                                          aria-expanded="false">{{ trans('admin.main_info') }}</a></li>
                    <li @if(session()->has('return_to_suggestions')) class="active" @endif><a href="#suggestions" data-toggle="tab"
                                    aria-expanded="true">{{ trans('admin.suggestions') }}</a></li>
                </ul>
                <div class="tab-content">
                    @php
                        $locationsArray = \App\Http\Controllers\HomeController::getChildren($req->location);
                        $locationsArray[] = $req->location;
                    @endphp
                    <div class="tab-pane @if(!session()->has('return_to_suggestions')) active @endif" id="main_info">
                        <strong>{{ trans('admin.id') }} : </strong>{{ $req->id }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.lead') }}
                            : </strong>{{ @\App\Lead::find($req->lead_id)->first_name . ' ' . @\App\Lead::find($req->lead_id)->last_name }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.price') }} : </strong>{{ $req->price_from }} <i
                                class="fa fa-arrows-h"></i> {{ $req->price_to }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.area') }} : </strong>{{ $req->area_from }} <i
                                class="fa fa-arrows-h"></i> {{ $req->area_to }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.request_type') }} : </strong>{{ trans('admin.'.$req->request_type) }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.type') }} : </strong>{{ trans('admin.'.$req->unit_type) }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.unit_type') }}
                            : </strong>{{ @App\UnitType::find($req->unit_type_id)->{app()->getLocale().'_name'} }}
                        <br>
                        <hr>
                        @if($req->request_type != 'new_home')
                            <strong>{{ trans('admin.rooms') }} : </strong>{{ $req->rooms_from }} <i
                                    class="fa fa-arrows-h"></i> {{ $req->rooms_to }}
                            <br>
                            <hr>
                            <strong>{{ trans('admin.bathrooms') }} : </strong>{{ $req->bathrooms_from }} <i
                                    class="fa fa-arrows-h"></i> {{ $req->bathrooms_to }}
                            <br>
                            <hr>
                        @endif
                        @php
                            $full_location="";
                            $location_id = $req->location;
                            if ($req->location) {
                                while($location_id != '0') {
                                    $location = @\App\Location::find($location_id);
                                    $location_id = $location->parent_id;
                                    $full_location .= $location->{app()->getLocale().'_name'}.' -';
                                }
                            }
                        @endphp
                        <strong>{{ trans('admin.location') }}: </strong>{{ trim($full_location, '-') }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.delivery_date') }}: </strong>{{ $req->date }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.down_payment') }}: </strong>{{ $req->down_payment }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.project') }}: </strong>{{ @App\Project::find($req->project_id)->{app()->getLocale() . '_name'} }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.notes') }} : </strong>{{ $req->notes }}
                        <br>
                        <hr>
                        <div class="col-md-12">
                            <div id="map" style="height: 450px; z-index: 999"></div>
                        </div>
                    </div>
                    <div class="tab-pane @if(session()->has('return_to_suggestions')) active @endif" id="suggestions">
                        @if($req->unit_type != 'land')
                            @if($req->request_type == 'new_home')
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin.id') }}</th>
                                        <th>{{ __('admin.meter_price') }}</th>
                                        <th>{{ __('admin.area') }}</th>
                                        <th>{{ __('admin.title') }}</th>
                                        <th>{{ __('admin.show') }}</th>
                                        <th>{{ __('admin.add') . '/' . __('admin.remove') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@\App\Project::where('type', $req->unit_type)->
                                        whereBetween('meter_price', [$req->price_from, $req->price_to])->
                                        whereBetween('area', [$req->area_from, $req->area_to])->
                                        whereIn('location_id', $locationsArray)->
                                        get() as $row)
                                        @if(@\App\InterestedRequest::where('unit_id', $row->id)->where('request_id', $req->id)->count())
                                            @php($added = 1)
                                        @else
                                            @php($added = 0)
                                        @endif
                                        <tr @if($added) style="background-color: rgba(255, 0, 0, 0.15);" @endif>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ $row->meter_price }}</td>
                                            <td>{{ $row->area }}</td>
                                            <td>{{ $row->{app()->getLocale().'_name'} }}</td>
                                            <td>
                                                <a href="{{ url(adminPath().'/projects/'.$row->id) }}"
                                                   class="btn btn-primary btn-flat">
                                                    {{ trans('admin.show') }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($added)
                                                <a href="{{ url(adminPath() . '/interested-request/' . $row->id . '/' . $req->id) }}"
                                                   class="btn btn-danger btn-flat">
                                                    {{ trans('admin.remove') }}
                                                </a>
                                                @else
                                                    <a href="{{ url(adminPath() . '/interested-request/' . $row->id . '/' . $req->id) }}"
                                                       class="btn btn-success btn-flat">
                                                        {{ trans('admin.add') }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @elseif($req->request_type == 'resale')
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
                                    @foreach(@\App\ResaleUnit::whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                    where('type',$req->unit_type)->
                                    where('unit_type_id',$req->unit_type_id)->
                                    whereBetween('total',[$req->price_from,$req->price_to])->
                                    whereBetween('area',[$req->area_from,$req->area_to])->
                                    whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                    whereIn('location',$locationsArray)->
                                    where('delivery_date',$req->date)->
                                    whereBetween('bathrooms',[$req->bathrooms_from,$req->bathrooms_to])->get()
                                     as $resaleUnit)
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
                                            <td>
                                                <a href="#" class="btn btn-flat btn-primary">
                                                    {{ trans('admin.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @elseif($req->request_type == 'rental')
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
                                    @foreach(@\App\RentalUnit::whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                    where('type',$req->unit_type)->
                                    where('unit_type_id',$req->unit_type_id)->
                                    whereBetween('rent',[$req->price_from,$req->price_to])->
                                    whereBetween('area',[$req->area_from,$req->area_to])->
                                    whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                    whereIn('location',$locationsArray)->
                                    where('delivery_date',$req->date)->
                                    whereBetween('bathrooms',[$req->bathrooms_from,$req->bathrooms_to])->get() as $resaleUnit)
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
                                            <td>
                                                <a href="#" class="btn btn-flat btn-primary">
                                                    {{ trans('admin.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @else
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
                                @foreach(@\App\Land::whereBetween('meter_price', [$req->price_from, $req->price_to])->
                                whereBetween('area', [$req->area_from, $req->area_to])->
                                whereIn('location', $locationsArray)->get() as $land)
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
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ trans('admin.delete') . ' ' . $land->name }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    {!! Form::open(['method'=>'DELETE','route'=>['lands.destroy',$land->id]]) !!}
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
                        @endif
                    </div>
                </div>
            </div>
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
            'autoWidth': true
        })
    </script>
    <script>
        function initAutocomplete() {
            var lat = parseFloat({{ $req->lat }});
            var lng = parseFloat({{ $req->lng }});
            var zoom = parseInt({{ $req->zoom }});
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng },
                zoom: zoom,
                mapTypeId: 'roadmap'
            });

            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                animation: google.maps.Animation.DROP
            });

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@stop