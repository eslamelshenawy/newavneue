@extends('admin.index')

@section('content')
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

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

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
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

    {!! Form::open(['url' => adminPath().'/resale_units/'.$unit->id , 'method'=>'put', 'enctype'=>'multipart/form-data']) !!}
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('admin.unit_info') }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('ar_title')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_title') }}</label>
                {!! Form::text('ar_title',$unit->ar_title,['class' => 'form-control', 'placeholder' => trans('admin.ar_title')]) !!}
            </div>

            <div class="form-group @if($errors->has('en_title')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_title') }}</label>
                {!! Form::text('en_title',$unit->en_title,['class' => 'form-control', 'placeholder' => trans('admin.en_title')]) !!}
            </div>

            <div class="form-group @if($errors->has('ar_description')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_description') }}</label>
                {!! Form::textarea('ar_description',$unit->ar_description,['class' => 'form-control', 'placeholder' => trans('admin.ar_description'),'rows'=>5]) !!}
            </div>

            <div class="form-group @if($errors->has('en_description')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_description') }}</label>
                {!! Form::textarea('en_description',$unit->en_description,['class' => 'form-control', 'placeholder' => trans('admin.en_description'),'rows'=>5]) !!}
            </div>
            <div class="form-group @if($errors->has('ar_notes')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_notes') }}</label>
                {!! Form::textarea('ar_notes',$unit->ar_notes,['class' => 'form-control', 'placeholder' => trans('admin.ar_notes'),'rows'=>5]) !!}
            </div>

            <div class="form-group @if($errors->has('en_notes')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_notes') }}</label>
                {!! Form::textarea('en_notes',$unit->en_notes,['class' => 'form-control', 'placeholder' => trans('admin.en_notes'),'rows'=>5]) !!}
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('admin.lead_info') }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('lead_id')) has-error @endif col-md-6">
                <label>{{ trans('admin.lead') }}</label>
                <select class="select2 form-control" name="lead_id" data-placeholder="{{ trans('admin.lead') }}">
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
            <div class="form-group @if($errors->has('phone')) has-error @endif col-md-6">
                <label>{{ trans('admin.phone') }}</label>
                <div class="input-group">
                    {!! Form::number('phone',$unit->phone,['class' => 'form-control', 'placeholder' => trans('admin.phone')]) !!}
                    <span style="cursor: pointer" class="input-group-addon" id="addPhone"><i
                                class="fa fa-plus"></i></span>
                </div>
            </div>
            @php($otherPhones = json_decode($unit->other_phones))
            <span id="otherPhones">
                @foreach($otherPhones as $phone)
                    @php($i = rand(0,999))
                    <div class="form-group @if($errors->has('other_phones')) has-error @endif col-md-4"
                         id="otherPhone{{ $i }}">
                        <label>{{ trans('admin.other_phones') }}</label>
                        <div class="input-group">
                            {!! Form::number('other_phones[]',$phone,['class' => 'form-control', 'placeholder' => trans('admin.other_phones')]) !!}
                            <span style="cursor: pointer" class="input-group-addon removePhone" num="{{ $i }}"><i
                                        class="fa fa-minus"></i></span>
                        </div>
                    </div>
                @endforeach
            </span>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('admin.unit_description') }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('type')) has-error @endif col-md-2">
                <label>{{ trans('admin.type') }}</label>
                <select class="select2 form-control" name="type" id="type" data-placeholder="{{ trans('admin.type') }}">
                    <option></option>
                    <option value="personal"
                            @if($unit->type == 'personal') selected @endif>{{ trans('admin.personal') }}</option>
                    <option value="commercial"
                            @if($unit->type == 'commercial') selected @endif>{{ trans('admin.commercial') }}</option>
                </select>
            </div>

            <div class="form-group @if($errors->has('unit_type_id')) has-error @endif col-md-2">
                <label>{{ trans('admin.unit_type') }}</label>
                <select class="select2 form-control" name="unit_type_id"
                        data-placeholder="{{ trans('admin.unit_type') }}" id="unit_type">
                    <option></option>
                    @foreach(@\App\UnitType::where('usage', $unit->type)->get() as $unitType)
                        <option value="{{ $unitType->id }}"
                                @if($unit->unit_type_id == $unitType->id) selected @endif>{{ $unitType->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('project_id')) has-error @endif col-md-2">
                <label>{{ trans('admin.project') }}</label>
                <select class="select2 form-control" name="project_id" data-placeholder="{{ trans('admin.project') }}">
                    <option></option>
                    @foreach(@\App\Project::all() as $project)
                        <option value="{{ $unit->id }}"
                                @if($project->project_id == $unit->id) selected @endif>{{ $project->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('broker_id')) has-error @endif col-md-2">
                <label>{{ trans('admin.broker') }}</label>
                <select class="select2 form-control" name="broker_id" data-placeholder="{{ trans('admin.broker') }}">
                    <option></option>
                </select>
            </div>
            <div class="form-group @if($errors->has('original_price')) has-error @endif col-md-2">
                <label>{{ trans('admin.original_price') }}</label>
                {!! Form::number('original_price',$unit->original_price,['class' => 'form-control', 'placeholder' => trans('admin.original_price')]) !!}
            </div>

            <div class="form-group @if($errors->has('payed')) has-error @endif col-md-2">
                <label>{{ trans('admin.payed') }}</label>
                {!! Form::number('payed',$unit->payed,['class' => 'form-control', 'placeholder' => trans('admin.payed')]) !!}
            </div>

            <div class="form-group @if($errors->has('rest')) has-error @endif col-md-2">
                <label>{{ trans('admin.rest') }}</label>
                {!! Form::number('rest',$unit->rest,['class' => 'form-control', 'placeholder' => trans('admin.rest')]) !!}
            </div>

            <div class="form-group @if($errors->has('total')) has-error @endif col-md-2">
                <label>{{ trans('admin.total') }}</label>
                {!! Form::number('total',$unit->total,['class' => 'form-control', 'placeholder' => trans('admin.total')]) !!}
            </div>

            <div class="form-group @if($errors->has('due_now')) has-error @endif col-md-2">
                <label>{{ trans('admin.due_now') }}</label>
                {!! Form::number('due_now',$unit->due_now,['class' => 'form-control', 'placeholder' => trans('admin.due_now')]) !!}
            </div>

            <div class="form-group @if($errors->has('delivery_date')) has-error @endif col-md-2">
                <label>{{ trans('admin.delivery_date') }}</label>
                <div class="input-group">
                    {!! Form::text('delivery_date',$unit->delivery_date,['class' => 'form-control', 'placeholder' => trans('admin.delivery_date'),'readonly'=>'','id'=>'datepicker']) !!}
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            <div class="form-group @if($errors->has('area')) has-error @endif col-md-2">
                <label>{{ trans('admin.area') }}</label>
                {!! Form::number('area',$unit->area,['class' => 'form-control', 'placeholder' => trans('admin.area')]) !!}
            </div>

            <div class="form-group @if($errors->has('price')) has-error @endif col-md-2">
                <label>{{ trans('admin.price') }}</label>
                {!! Form::number('price',$unit->price,['class' => 'form-control', 'placeholder' => trans('admin.price')]) !!}
            </div>

            <div class="form-group @if($errors->has('rooms')) has-error @endif col-md-2">
                <label>{{ trans('admin.rooms') }}</label>
                {!! Form::number('rooms',$unit->rooms,['class' => 'form-control', 'placeholder' => trans('admin.rooms')]) !!}
            </div>

            <div class="form-group @if($errors->has('bathrooms')) has-error @endif col-md-2">
                <label>{{ trans('admin.bathrooms') }}</label>
                {!! Form::number('bathrooms',$unit->bathrooms,['class' => 'form-control', 'placeholder' => trans('admin.bathrooms')]) !!}
            </div>

            <div class="form-group @if($errors->has('floors')) has-error @endif col-md-2">
                <label>{{ trans('admin.floors') }}</label>
                {!! Form::number('floors',$unit->floors,['class' => 'form-control', 'placeholder' => trans('admin.floors')]) !!}
            </div>


            <div class="form-group @if($errors->has('finishing')) has-error @endif col-md-2">
                <label>{{ trans('admin.finishing') }}</label>
                <select class="select2 form-control" name="finishing" data-placeholder="{{ trans('admin.finishing') }}">
                    <option></option>
                    <option value="finished"
                            @if($unit->finishing == 'finished') selected @endif>{{ trans('admin.finished') }}</option>
                    <option value="semi_finished"
                            @if($unit->finishing == 'semi_finished') selected @endif>{{ trans('admin.semi_finished') }}</option>
                    <option value="not_finished"
                            @if($unit->finishing == 'not_finished') selected @endif>{{ trans('admin.not_finished') }}</option>
                </select>
            </div>
            <div class="form-group @if($errors->has('image')) has-error @endif col-md-2">
                <label>{{ trans('admin.image') }}</label>
                {!! Form::file('image',['class' => 'form-control', 'placeholder' => trans('admin.image'), 'accept' => 'image/*']) !!}
            </div>

            <div class="form-group @if($errors->has('other_images')) has-error @endif col-md-2">
                <label>{{ trans('admin.other_images') }}</label>
                {!! Form::file('other_images[]',['class' => 'form-control', 'placeholder' => trans('admin.other_images'), 'multiple'=>'']) !!}
            </div>
            <div class="form-group @if($errors->has('view')) has-error @endif col-md-3">
                <label>{{ trans('admin.view') }}</label>
                <select class="select2 form-control" name="view" data-placeholder="{{ trans('admin.view') }}">
                    <option></option>
                    <option value="main_street"
                            @if($unit->view == 'main_street') selected @endif>{{ trans('admin.main_street') }}</option>
                    <option value="bystreet"
                            @if($unit->view == 'bystreet') selected @endif>{{ trans('admin.bystreet') }}</option>
                    <option value="garden"
                            @if($unit->view == 'garden') selected @endif>{{ trans('admin.garden') }}</option>
                    <option value="corner"
                            @if($unit->view == 'corner') selected @endif>{{ trans('admin.corner') }}</option>
                    <option value="sea_or_river"
                            @if($unit->view == 'sea_or_river') selected @endif>{{ trans('admin.sea_or_river') }}</option>
                </select>
            </div>



            <div class="form-group @if($errors->has('youtube_link')) has-error @endif col-md-3">
                <label>{{ trans('admin.youtube_link') }}</label>
                {!! Form::text('youtube_link',$unit->youtube_link,['class' => 'form-control', 'placeholder' => trans('admin.youtube_link')]) !!}
            </div>


            <div class="form-group @if($errors->has('payment_method')) has-error @endif col-md-3">
                <label>{{ trans('admin.payment_method') }}</label>
                <select class="select2 form-control" name="payment_method"
                        data-placeholder="{{ trans('admin.payment_method') }}">
                    <option></option>
                    <option value="cash"
                            @if($unit->payment_method == 'cash') selected @endif>{{ trans('admin.cash') }}</option>
                    <option value="installment"
                            @if($unit->payment_method == 'installment') selected @endif>{{ trans('admin.installment') }}</option>
                    <option value="cash_or_installment"
                            @if($unit->payment_method == 'cash_or_installment') selected @endif>{{ trans('admin.cash_or_installment') }}</option>
                </select>
            </div>

            <div class="form-group @if($errors->has('payment_method')) has-error @endif col-md-3">
                <br/>
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                       data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.featured') }}" value="1" @if($unit->featured == 1) checked @endif>
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
                @php($images = App\ResalImage::where('unit_id',$unit->id)->get())
                @foreach($images as $image)
                    <div class="img-container">
                        <div id="{{ $image->id }}Div">
                            <img src="{{url('uploads/'.$image->image)  }}" alt="Avatar" class="image" id="delete-img">
                            <div class="img-overlay" id="{{ $image->id }}">
                                <i class="glyphicon glyphicon-trash delete-icon"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('location')) has-error @endif col-md-12">
                        <label>{{ trans('admin.location') }}</label>
                        <select class="select2 form-control" name="location" id="location"
                                data-placeholder="{{ trans('admin.location') }}">
                            <option></option>
                            @foreach(@\App\Location::all() as $location)
                                <option value="{{ $location->id }}" lat="{{ $location->lat }}" lng="{{ $location->lng }}"
                                        zoom="{{ $location->zoom }}"
                                        @if($location->id == $unit->location) selected @endif>{{ $location->{app()->getLocale().'_name'} }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group @if($errors->has('en_address')) has-error @endif col-md-12">
                        <label>{{ trans('admin.en_address') }}</label>
                        {!! Form::text('en_address',$unit->en_address,['class' => 'form-control', 'placeholder' => trans('admin.en_address'),'id'=>'address']) !!}
                    </div>

                    <div class="form-group @if($errors->has('ar_address')) has-error @endif col-md-12">
                        <label>{{ trans('admin.ar_address') }}</label>
                        {!! Form::text('ar_address',$unit->ar_address,['class' => 'form-control', 'placeholder' => trans('admin.ar_address')]) !!}
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="map" style="height: 450px; x-index: 999"></div>
                </div>
            </div>
            <input id="lat" name="lat" type="hidden" value="{{ $unit->lat }}">
            <input id="lng" name="lng" type="hidden" value="{{ $unit->lng }}">
            <input id="zoom" name="zoom" type="hidden" value="{{ $unit->zoom }}">

            <div class="col-md-12">
                <br/>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('js')
    <script>
        $('.img-overlay').on('click',function () {
            var id = $(this).attr('id');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/delete_resale_image')}}",
                method: 'post',
                dataType: 'json',
                data: {id: id, _token: _token},
                beforeSend: function () {
                    //
                },
                success: function (data) {
                    $('#'+id+'Div').remove();
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

            var input = document.getElementById('address');
            var searchBox = new google.maps.places.SearchBox(input);

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
    <script>
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
    <script>
        var i = 1;
        $(document).on('click', '#addPhone', function () {
            $('#otherPhones').append('<div class="form-group col-md-4" id="otherPhone' + i + '">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::number("other_phones[]","",["class" => "form-control", "placeholder" => trans("admin.other_phones")]) !!}' +
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
@endsection