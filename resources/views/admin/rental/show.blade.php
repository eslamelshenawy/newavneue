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
                <strong>{{ trans('admin.ar_title') }} : </strong>{{ $unit->ar_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_description') }} : </strong>{{ $unit->ar_description }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_title') }} : </strong>{{ $unit->en_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_description') }} : </strong>{{ $unit->en_description }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.type') }} : </strong>{{ __('admin.'.$unit->type) }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.unit_type') }}
                    : </strong>{{ @App\UnitType::find($unit->unit_type_id)->{App::getLocale().'_name'} }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.lead') }}
                    : </strong>{{ @App\Lead::find($unit->Lead_id)->first_name }} {{ @App\Lead::find($unit->lead_id)->last_name }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.phone') }} : </strong>{{ $unit->phone}}
                <br>
                <hr>
            </div>
            <div class="col-md-4">
                <strong>{{ trans('admin.other_phones') }}
                    : </strong>
                @if($unit->other_phones != 'null')
                    @php($phones = json_decode($unit->other_phones))
                    @foreach($phones as $phone)
                        @if(!$loop->last)
                            {{ $phone }} -
                        @else
                            {{ $phone }}
                        @endif
                    @endforeach
                @endif
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.area') }} : </strong>{{ $unit->area}}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.rooms') }} : </strong>{{ $unit->rooms}}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.floor') }} : </strong>{{ $unit->floor}}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.bathrooms') }} : </strong>{{ $unit->bathrooms}}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.delivery_date') }} : </strong>{{ $unit->bathrooms}}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.view') }} : </strong>{{ __('admin.'.$unit->view) }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.finishing') }} : </strong>{{ __('admin.'.$unit->finishing) }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.rent') }} : </strong>{{ $unit->rent}}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.image') }} : </strong><img src="{{ url('uploads/'.$unit->image)}}"
                                                                   width="100px">
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong style="display: block">{{ trans('admin.images') }} : </strong>
                @php($images = \App\RentalImage::where('unit_id',$unit->id)->get())
                @foreach($images as $image)
                    <img src="{{ url('uploads/'.$image->image)}}" width="100px" style="float: left">
                @endforeach
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.project') }}
                    : </strong>{{@App\Project::find($unit->project_id)->{App::getLocale().'_name'} }}
                <br>
                <hr>
            </div>
            <div class="col-md-2">
                <strong>{{ trans('admin.location') }}
                    : </strong>{{ @App\Location::find($unit->location)->{App::getLocale().'_name'} }}
                <br>
                <hr>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <strong>{{ trans('admin.address_ar') }} : </strong>{{ $unit->ar_address }}
                    <br>
                    <hr>
                </div>
                <div class="col-md-6">
                    <strong>{{ trans('admin.address_en') }} : </strong>{{ $unit->en_address }}
                    <br>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="facebook"><span>push to facebook</span></div>
                    <div class="olx"><span>push to OLX</span></div>
                    <div class="aqarmap"><span>push to Aqar Map</span></div>
                    <div class="propertyfinder"><span>push to Property Finder</span></div>


                    <br>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div id="map" style="height: 450px; z-index: 999"></div>
                </div>
            </div>
        </div>
    </div>
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

            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                animation: google.maps.Animation.DROP
            });

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection