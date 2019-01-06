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
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_title') }} : </strong>{{ $land->ar_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_title') }} : </strong>{{ $land->en_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_description') }} : </strong>{{ $land->ar_description }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_description') }} : </strong>{{ $land->en_description }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.area') }} : </strong>{{ $land->area }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.meter_price') }} : </strong>{{ $land->meter_price }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.lead') }} : </strong>{{ @App\Lead::find($land->lead_id)->first_name . ' ' . @App\Lead::find($land->lead_id)->last_name }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.location') }} : </strong>{{ @App\Location::find($land->location)->{app()->getLocale().'_name'} }}
                <br/>
                <hr/>
            </div>

            <div class="col-md-6">
                <div id="map" style="height: 450px; x-index: 999"></div>
            </div>
            
            <div class="col-md-6">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="{{ url('uploads/'.$land->image) }}" alt=""
                                 style="width:100%;">
                        </div>
                        @foreach(@\App\LandImage::where('land_id',$land->id)->get() as $img)
                            <div class="item">
                                <img src="{{ url('uploads/'.$img->image) }}" alt=""
                                     style="width:100%;">
                            </div>
                        @endforeach
                    </div>

                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="fa fa-angle-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="fa fa-angle-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function initAutocomplete() {
            var lat = parseFloat({{ $land->lat }});
            var lng = parseFloat({{ $land->lng }});
            var zoom = parseInt({{ $land->zoom }});
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: lat,
                    lng: lng},
                zoom: zoom,
                mapTypeId: 'roadmap'
            });

            var input = document.getElementById('address');
            var searchBox = new google.maps.places.SearchBox(input);

            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });


            var marker = new google.maps.Marker({
                position: {
                    lat: lat,
                    lng: lng},
                map: map,
                // draggable: true,
                animation: google.maps.Animation.DROP
            });


            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {

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

            google.maps.event.addListener(marker, 'drag', function () {
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
            });

            google.maps.event.addListener(map, 'zoom_changed', function () {
                $('#zoom').val(map.getZoom())
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection