@extends('website.index')
@section('content')
    <style>
        .location{
            cursor: pointer;
            transition: 0.8s all;
            color: #161616;
        }
        .location:hover{
           background: #000;
            color: #fff;
        }
    </style>
    <div id="map" style="height: 650px !important;z-index:20"></div>
    <div>
        <ul id="jstree" style="margin: 20px 0">
        @foreach($locations as $location)
            <span style="padding: 5px 20px;display: inline-block;font-size: 16px" zoom="{{ $location->zoom }}" lat="{{ $location->lat }}" lng="{{ $location->lng }}" class="location">{{ $location->{app()->getLocale().'_name'} }}</span>

            @endforeach
        </ul>
    </div>
@endsection
@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>
        function initAutocomplete() {
            {{--var lat=parseFloat({{ $project->lat }});--}}
            {{--var lng=parseFloat({{ $project->lng }});--}}
            {{--var zoom=parseInt({{ $project->zoom }});--}}
            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat:30 , lng: 30 },
                zoom: 7,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.

            @foreach($projects as $project)
            var lat=parseFloat({{ $project->lat }});
            var lng=parseFloat({{ $project->lng }});
            var icon= '{{ url('uploads/'.$project->map_marker) }}';
            var marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                icon: icon
            });
            google.maps.event.addListener(marker, 'click', function () {
                window.location.href = '{{ url('project/'.slug($project->en_name).'-'.$project->id) }}';
            });
            @endforeach

            $('.location').on('click', function () {
                var lat = parseFloat($(this).attr('lat'));
                var lng = parseFloat($(this).attr('lng'));
                var zoom = parseInt($(this).attr('zoom'));
                $('#lat').val(lat);
                $('#lng').val(lng);
                $('#zoom').val(zoom);
//                marker.setPosition({lat: lat, lng: lng});
                map.setCenter(new google.maps.LatLng(lat, lng));
                map.setZoom(zoom);
            })
        }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>

@endsection
