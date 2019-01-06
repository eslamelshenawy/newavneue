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
            {!! Form::open(['url' => adminPath().'/lands','enctype'=>'multipart/form-data']) !!}
            <div class="form-group @if($errors->has('ar_title')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_title') }}</label>
                {!! Form::text('ar_title','',['class' => 'form-control', 'placeholder' => trans('admin.ar_title')]) !!}
            </div>
            <div class="form-group @if($errors->has('en_title')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_title') }}</label>
                {!! Form::text('en_title','',['class' => 'form-control', 'placeholder' => trans('admin.en_title')]) !!}
            </div>

            <div class="form-group @if($errors->has('ar_description')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_description') }}</label>
                {!! Form::textarea('ar_description','',['class' => 'form-control', 'placeholder' => trans('admin.ar_description'),'rows'=>5]) !!}
            </div>
            <div class="form-group @if($errors->has('en_description')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_description') }}</label>
                {!! Form::textarea('en_description','',['class' => 'form-control', 'placeholder' => trans('admin.en_description'),'rows'=>5]) !!}
            </div>

            <div class="form-group @if($errors->has('image')) has-error @endif col-md-6">
                <label>{{ trans('admin.image') }}</label>
                {!! Form::file('image',['class' => 'form-control', 'placeholder' => trans('admin.image')]) !!}
            </div>
            <div class="form-group @if($errors->has('other_images')) has-error @endif col-md-6">
                <label>{{ trans('admin.other_images') }}</label>
                {!! Form::file('other_images[]',['class' => 'form-control', 'placeholder' => trans('admin.other_images'),'multiple'=>'']) !!}
            </div>

            <div class="form-group @if($errors->has('area')) has-error @endif col-md-6">
                <label>{{ trans('admin.area') }}</label>
                {!! Form::number('area','',['class' => 'form-control', 'placeholder' => trans('admin.area'), 'min' => 0]) !!}
            </div>
            <div class="form-group @if($errors->has('meter_price')) has-error @endif col-md-6">
                <label>{{ trans('admin.meter_price') }}</label>
                {!! Form::number('meter_price','',['class' => 'form-control', 'placeholder' => trans('admin.meter_price'), 'min' => 0]) !!}
            </div>

            <div class="form-group @if($errors->has('lead')) has-error @endif col-md-6">
                <label>{{ trans('admin.lead') }}</label>
                <select class="form-control select2" name="lead_id" data-placeholder="{{ __('admin.lead') }}">
                    <option></option>
                    @foreach(@App\Lead::getAgentLeads() as $lead)
                        <option value="{{ $lead->id }}"
                                @if(old('lead_id') == $lead->id) selected @endif>
                            {{ $lead->first_name . ' ' . $lead->last_name }}
                            -
                            @if($lead->agent_id == auth()->id())
                                {{ __('admin.my_lead') }}
                            @else
                                {{ __('admin.team_lead', ['agent' => @$lead->agent->name]) }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group @if($errors->has('location')) has-error @endif col-md-6">
                <label>{{ trans('admin.location') }}</label>
                <select class="form-control select2" name="location" data-placeholder="{{ __('admin.location') }}" id="location">
                    <option></option>
                    @foreach(@\App\Location::get() as $location)
                        <option value="{{ $location->id }}" lat="{{ $location->lat }}" lng="{{ $location->lng }}" zoom="{{ $location->zoom }}">{{ $location->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <div id="map" style="height: 450px; x-index: 999"></div>
            </div>
            <input id="lat" name="lat" type="hidden"
                   value="30.0595581">
            <input id="lng" name="lng" type="hidden"
                   value="31.2233591">
            <input id="zoom" name="zoom" type="hidden"
                   value="7">
            <div class="col-md-12">
                <br/>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: @if(request()->has('lat')){{ request()->lat }} @else 30.0595581 @endif,
                    lng: @if(request()->has('lng')){{ request()->lng }} @else 31.2233591 @endif},
                zoom: @if(request()->has('zoom')){{ request()->lng }} @else 7 @endif,
                mapTypeId: 'roadmap'
            });

            var input = document.getElementById('address');
            var searchBox = new google.maps.places.SearchBox(input);

            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });


            var marker = new google.maps.Marker({
                position: {
                    lat: @if(request()->has('lat')){{ request()->lat }} @else 30.0595581 @endif,
                    lng: @if(request()->has('lng')){{ request()->lng }} @else 31.2233591 @endif},
                map: map,
                draggable: true,
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

            $('#location').on('change', function () {
                var lat = parseFloat($('option:selected', this).attr('lat'));
                var lng = parseFloat($('option:selected', this).attr('lng'));
                var zoom = parseInt($('option:selected', this).attr('zoom'));
                $('#lat').val(lat);
                $('#lng').val(lng);
                $('#zoom').val(zoom);
                marker.setPosition({lat: lat, lng: lng});
                map.setCenter(new google.maps.LatLng(lat, lng));
                map.setZoom(zoom);
            })
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection