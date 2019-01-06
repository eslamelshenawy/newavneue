@extends('admin.index')

@section('content')

    <form action="{{ url(adminPath().'/rental_units/'.$unit->id) }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="put">
        {{ csrf_field() }}
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group col-md-6 {{ $errors->has('ar_title') ? 'has-error' : '' }}">
                    {!! Form::label(trans('admin.ar_title')) !!}
                    <input class="form-control" name="ar_title" value="{{ $unit->ar_title }}">
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.en_title')) !!}
                    <input class="form-control" name="en_title" value="{{ $unit->en_title }}">
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.ar_description')) !!}
                    <textarea class="form-control" name="ar_description">{{ $unit->ar_description }}  </textarea>
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.en_description')) !!}
                    <textarea class="form-control" name="en_description">{{ $unit->en_description }} </textarea>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.unit_data') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group col-md-4">
                    {!! Form::label(trans('admin.lead')) !!}
                    <select class="form-control select2" data-placeholder="{{ trans('admin.lead') }}" name="lead_id">
                        <option></option>
                        @foreach(@App\Lead::getAgentLeads() as $lead)
                            <option value="{{ $lead->id }}"
                                    @if($unit->lead_id == $lead->id) selected @endif>
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
                <div class="form-group @if($errors->has('phone')) has-error @endif col-md-4">
                    <label>{{ trans('admin.phone') }}</label>
                    <div class="input-group">
                        {!! Form::text('phone',$unit->phone,['class' => 'form-control', 'placeholder' => trans('admin.phone')]) !!}
                        <span style="cursor: pointer" class="input-group-addon" id="addPhone"><i
                                    class="fa fa-plus"></i></span>
                    </div>
                </div>
                @php($otherPhones = json_decode($unit->other_phones))
                <span id="otherPhones">
                @if(count($otherPhones)>0)
                        @foreach($otherPhones as $phone)
                            @php($i = rand(0,999))
                            <div class="form-group @if($errors->has('other_phones')) has-error @endif col-md-4"
                                 id="otherPhone{{ $i }}">
                        <label>{{ trans('admin.other_phones') }}</label>
                        <div class="input-group">
                            {!! Form::text('other_phones[]',$phone,['class' => 'form-control', 'placeholder' => trans('admin.other_phones')]) !!}
                            <span style="cursor: pointer" class="input-group-addon removePhone" num="{{ $i }}"><i
                                        class="fa fa-minus"></i></span>
                        </div>
                    </div>
                        @endforeach
                    @endif
            </span>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.unit_data') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.usage')) !!}
                    <select class="form-control select2" data-placeholder="{{ trans('admin.type') }}" id="type"
                            name="usage">
                        <option></option>
                        <option value="commercial"
                                @if($unit->type == "commercial") selected @endif>{{ trans('admin.commercial') }}</option>
                        <option value="personal"
                                @if($unit->type == "personal") selected @endif>{{ trans('admin.personal') }}</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.type')) !!}
                    <select class="form-control select2" id="unit_type" name="type_id">
                        @foreach(@App\UnitType::where('usage',$unit->type)->get() as $type)
                            <option></option>
                            <option value="{{ $type->id }}" @if($unit->unit_type_id == $type->id ) selected @endif>
                                {{ $type->{app()->getLocale().'_name'} }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.area')) !!}
                    <input class="form-control" name="area" type="number" min="0" value="{{ $unit->area }}">
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.rooms')) !!}
                    <input class="form-control" name="rooms" type="number" min="0" value="{{ $unit->rooms }}">
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.floor')) !!}
                    <input class="form-control" name="floor" type="number" value="{{ $unit->floor }}">
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.bathrooms')) !!}
                    <input class="form-control" name="bathrooms" type="number" value="{{ $unit->bathrooms }}">
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.delivery_date')) !!}
                    <input class="form-control " id="datepicker" name="delivery_date" type="text" readonly
                           value="{{ $unit->delivery_date }}">
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.view')) !!}
                    <select class="form-control select2" data-placeholder="{{ trans('admin.view') }}" name="view">
                        <option></option>
                        <option value="main_street"
                                @if($unit->view == "main_street") selected @endif >{{ trans('admin.main_street') }}</option>
                        <option value="bystreet"
                                @if($unit->view == "bystreet") selected @endif>{{ trans('admin.bystreet') }}</option>
                        <option value="garden"
                                @if($unit->view == "garden") selected @endif>{{ trans('admin.garden') }}</option>
                        <option value="corner"
                                @if($unit->view == "corner") selected @endif>{{ trans('admin.corner') }}</option>
                        <option value="sea_or_river"
                                @if($unit->view == "sea_or_river") selected @endif>{{ trans('admin.sea_or_river') }}</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.finishing')) !!}
                    <select class="form-control select2" data-placeholder="{{ trans('finishing') }}" name="finishing">
                        <option></option>
                        <option value="finished"
                                @if($unit->finishing == "finished") selected @endif>{{ trans('admin.finished') }}</option>
                        <option value="semi_finished"
                                @if($unit->finishing == "semi_finished") selected @endif>{{ trans('admin.semi_finished') }}</option>
                        <option value="not_finished"
                                @if($unit->finishing == "not_finished") selected @endif>{{ trans('admin.not_finished') }}</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    {!! Form::label(trans('admin.rent')) !!}
                    <input class="form-control" name="rent" type="number" min="0" value="{{ $unit->rent }}">
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label(trans('admin.image')) !!}
                    <input type="file" class="form-control " name="image">
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label(trans('admin.other_images')) !!}
                    <input type="file" multiple class="form-control" name="images[]">
                    @php($images = \App\RentalImage::where('unit_id',$unit->id)->get())
                    @if(count($images)>0)
                        @foreach($images as $image)
                            <div class="img-container">
                                <div id="{{ $image->id }}Div">
                                    <img src="{{url('uploads/'.$image->image)  }}" alt="Avatar" class="image"
                                         id="delete-img">
                                    <div class="img-overlay" id="{{ $image->id }}">
                                        <i class="glyphicon glyphicon-trash delete-icon"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label(trans('admin.project')) !!}
                    <select class="select2 form-control" name="project_id" data-placeholder="{{ __('admin.project') }}">
                        <option></option>
                        @foreach(\App\Project::all() as $project)
                            <option value="{{ $project->id }}"
                                    @if($project->id == $unit->project_id) selected @endif> {{ $project->{app()->getLocale().'_name'}  }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    {!! Form::label(trans('admin.meta_keywords')) !!}
                    <input type="text" value="{{ $unit->meta_keywords }}" name="meta_keywords" class="form-control"
                           data-role="tagsinput" style="width: 100%">
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label(trans('admin.meta_description')) !!}
                    <textarea class="form-control" name="meta_description"
                              rows="1">{{ $unit->meta_description }}</textarea>
                </div>
                <div class="form-group  {{ $errors->has("facility") ? 'has-error' : '' }} col-md-8">
                    {!! Form::label(trans("admin.facility")) !!}
                    @php($arr = $facilities)
                    <br>
                    <select class="select2 form-control" style="width: 100%" multiple name="facility[]"
                            data-placeholder="{{ trans("admin.facilities") }}">
                        <option></option>
                        @foreach(App\Facility::get() as $facilty)
                            <option value="{{ $facilty->id }}" @if(in_array($facilty->id,$arr)) selected @endif>{{ $facilty->en_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">

                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('location')) has-error @endif col-md-12">
                            <label>{{ trans('admin.location') }}</label>
                            <select class="select2 form-control" name="location" id="location"
                                    data-placeholder="{{ trans('admin.location') }}">
                                <option></option>
                                @foreach(@\App\Location::all() as $location)
                                    <option value="{{ $location->id }}" lat="{{ $location->lat }}"
                                            lng="{{ $location->lng }}" zoom="{{ $location->zoom }}"
                                            @if($unit->location == $location->id) selected @endif>{{ $location->{app()->getLocale().'_name'} }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label(trans('admin.address_en')) !!}
                            <input id="address_en" class="form-control" type="text" name="en_address"
                                   value="{{ $unit->en_address }}">
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label(trans('admin.address_ar')) !!}
                            <input id="address" class="form-control" type="text" name="ar_address"
                                   value="{{ $unit->ar_address }}">
                        </div>

                    </div>
                    <div class="col-md-8">

                        <div id="map"></div>

                    </div>
                </div>


                <input type="hidden" name="lat" value="{{$unit->lat}}" id="lang">
                <input type="hidden" name="lng" value="{{$unit->lng}}" id="lat">
                <input type="hidden" name="zoom" value="{{$unit->zoom}}" id="zoom">
                <button type="submit"
                        class="btn btn-primary btn-flat col-md-1 pull-right">{{ trans('admin.submit') }}</button>
            </div>
        </div>
    </form>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 300px;
        }

        /* Optional: Makes the sample page fill the window. */

        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }
    </style>
@endsection
@section('js')
    <script>
        var i = 1;
        $(document).on('click', '#addPhone', function () {
            $('#otherPhones').append('<div class="form-group col-md-4" id="otherPhone' + i + '">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::text("other_phones[]","",["class" => "form-control", "placeholder" => trans("admin.other_phones")]) !!}' +
                '<span style="cursor: pointer" class="removePhone input-group-addon" num="' + i + '"><i' +
                ' class="fa fa-minus"></i></span>' +
                '</div>' +
                '</div>');
            i++;
        });

        $(document).on('click', '.removePhone', function () {
            var num = $(this).attr('num');
            $('#otherPhone' + num).remove();
        })
    </script>
    <script>
        $('.img-overlay').on('click', function () {
            var id = $(this).attr('id');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/delete_rental_image')}}",
                method: 'post',
                dataType: 'json',
                data: {id: id, _token: _token},
                beforeSend: function () {
                    //
                },
                success: function (data) {
                    $('#' + id + 'Div').remove();
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                }
            });
        });
    </script>
    <script>
        $('#datepicker').datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
        });
    </script>
    <script>

        function initAutocomplete() {
            var lat = parseFloat({{ $unit->lat }});
            var lng = parseFloat({{ $unit->lng }});
            var zoom = parseInt({{ $unit->zoom }});
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: zoom,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.

            var input2 = document.getElementById('address_en');
            var searchBox = new google.maps.places.SearchBox(input2);
//            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });


            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                draggable: false,
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
                    $('#lang').val(place.geometry.location.lng());

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
                if (marker) {
                    marker.setMap(null);
                    var myLatLng = event.latLng;
                }

                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,

                });
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
            })

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

        $(document).on('change', '#type', function () {
            var usage = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_unit_types')}}",
                method: 'post',

                data: {usage: usage, _token: _token},
                success: function (data) {

                    $('#unit_type').html(data);
                }
            });
        });

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection