@extends('admin.index')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default-dark/style.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default/style.min.css">

    <div class="box">
        <div class="box-header with-border">

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="container">
                <h3>{{ trans('admin.all').' '.trans('admin.location') }}</h3>


                <div class="col-md-6" id="jstree">
                    <ul>
                        @foreach($locations as $row)

                            <li style="cursor: pointer" id="{{ $row->id }}" arabic="{{ $row->ar_name }}" english="{{ $row->en_name }}" lat="{{ $row->lat }}" lng="{{ $row->lng }}" zoom="{{ $row->zoom }}" class="child" data-title="{{ $row->title }}" data-id=" {{ $row->id }}">
                                <span class="fa fa-thumb-tack"></span> {{ $row->{app()->getLocale().'_name'} }}
                                @if(count($row->childs))
                                    @include('admin.locations.manageChild',['childs' => $row->childs])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6 well">

                    <form action="{{ url(adminPath().'/location') }}" method="post">
                        {{ csrf_field() }}
                        <input id="lat" name="lat" type="hidden">
                        <input id="lng" name="lng" type="hidden">
                        <input id="zoom" name="zoom" type="hidden">
                        <input type="hidden" name="parent_id" id="parent_id" value="0">
                        <div class="row">
                            <div class="col-md-2"><b> {{ trans('admin.move').' '.trans('admin.to') }}</b> </div>
                        <div class="form-group col-md-7 {{ $errors->has("parent") ? 'has-error' : '' }}">
                            <select id="move_parent" name="move_parent" class="select2 form-control" style="width: 100%"
                                    data-placeholder="{{ trans("admin.parent") }}">
                                <option value="0">{{ trans('admin.main') }}</option>
                                @foreach(App\Location::get() as $location)
                                    <option class="locationOption" value="{{ $location->id }}">{{ $location->{app()->getLocale().'_name'} }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="form-group col-md-3">
                                    <button type="submit" name="store" value="move" class="btn btn-primary btn-flat">{{ trans('admin.move') }}</button>
                        </div>
                        </div>
                    <div class="form-group {{ $errors->has('en_name') ? 'has-error' : '' }}">
                       <label>{{ trans('admin.english_name') }}</label>
                        <input name="en_name" id="en_name" value="{{ old('en_name') }}" class="form-control" placeholder="{{ trans('admin.english_name') }}">
                    </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label>{{ trans('admin.arabic_name') }}</label>
                            <input name="ar_name" id="ar_name" value="{{ old('ar_name') }}" class="form-control" placeholder="{{ trans('admin.arabic_name') }}">
                        </div>


                        <div class="form-group">
                        <button type="submit" name="store" value="add" class="btn btn-primary btn-flat">{{ trans('admin.add') }}</button>
                        <button type="submit" name="store" value="edit" class="btn btn-primary btn-flat">{{ trans('admin.edit') }}</button>
                            <a data-toggle="modal" data-target="#delete"
                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a>

                    </div>
                    </form>
                    <div id="delete" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ trans('admin.delete') . ' '}}<span id="dd"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <form method='post' action={{ url(adminPath().'/location/destroy1') }}>
                                    {{ csrf_field() }}
                                            <input type="hidden" value="" name="del_loc" id="del_loc">
                                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('admin.close') }}</button>
                                        <button type="submit" class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        <div style="display: none" id="get_location" lat="" lng="" zoom=""></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-md-push-6">
                    <div id="map" style="height: 500px;z-index:20"></div>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 30.0595581, lng: 31.2233591},
                zoom: 7,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('en_name');
            var searchBox = new google.maps.places.SearchBox(input);
//            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });


            var marker = new google.maps.Marker({
                position: {lat: 30.0595581, lng: 31.2233591},
                map: map,
                draggable: false,
                animation: google.maps.Animation.DROP
            });


            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {

                    $('#lat').val(place.geometry.location.lat());
                    $('#lng').val(place.geometry.location.lng());

                    marker.setPosition(place.geometry.location);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });

            google.maps.event.addListener(map, 'click', function (event) {
                if(marker)
                {
                    marker.setMap(null);
                    var myLatLng = event.latLng;
                }

                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,

                });
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
                console.log(marker.getPosition().lat());
            })


            google.maps.event.addListener(map, 'zoom_changed', function() {
                $('#zoom').val(map.getZoom())
            });

            $("#jstree").on('changed.jstree', function (e, data) {
                var id = data.selected;
                var lat1 = parseFloat($('#'+data.selected).attr('lat'));
                var lng1= parseFloat($('#'+data.selected).attr('lng'));
                var zoom = parseInt($('#'+data.selected).attr('zoom'));
                $('#lat').val(lat1);
                $('#lng').val(lng1);
                $('#zoom').val(zoom);
                marker.setPosition({ lat:lat1,lng:lng1 } );
                map.setCenter(new google.maps.LatLng(lat1,lng1));
                map.setZoom(zoom);

            })

        }

        $("#jstree").on('changed.jstree', function (e, data) {
            console.log($('#'+data.selected).attr('lat'));
            $('#get_group').text($('#'+data.selected).attr('data-title'));
            $('#get_location').attr('lat',$('#'+data.selected).attr('lat'));
            $('#get_location').attr('lng',$('#'+data.selected).attr('lng'));
            $('#ar_name').val($('#'+data.selected).attr('arabic'));
            $('#en_name').val($('#'+data.selected).attr('english'));
            var id = data.selected;
            $('#parent_id').val(id);
            $('#edit_parent_id').val(id);
            $('#dd').text($('#'+data.selected).attr('data-title'));
            $('#del_loc').val(id);
            $('.locationOption').prop('disabled',false);
            $('#move_parent').select2("destroy").select2();
            $("#move_parent option[value=" + id + "]").prop('disabled',true);
            //marker.setPosition(place.geometry.location);
        }).jstree({
            'core': {
                "themes": {
                    "dots": false,
                    "icons": false
                }
            },
            'plugins': ['types', 'contextmenu', 'wholerow', 'ui']
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>

@endsection
