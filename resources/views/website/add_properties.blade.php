@extends('website/index')
@section('content')




    <!-- My Properties  -->
    <section id="property" class="padding listing1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul class="f-p-links margin_bottom">
                        <li><a href="{{ url('profile') }}"><i class="icon-icons230"></i>{{ __('admin.profile') }}</a></li>
                        {{--<li><a href="{{ url('my_properties') }}"><i class="icon-icons215"></i> My Properties</a></li>--}}
                        <li><a href="{{ url('add_properties') }}"  class="active"><i class="icon-icons215"></i> {{ __('admin.submit_property') }}</a></li>
                        <li><a href="{{ url('favourite_properties') }}"><i class="icon-icons43"></i> {{ __('admin.favorites') }}</a></li>
                        <li><a href="login.html"><i class="icon-lock-open3"></i>{{ __('admin.logout') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-1 col-md-2"></div>
                <div class="col-sm-10 col-md-8">
                    @include('admin.error')
                    <h2 class="text-uppercase bottom40">{{ __('admin.add_your_prop') }}</h2>
                    <form class="callus clearfix border_radius submit_property" action="{{ url('add_property') }}" method="post" enctype="multipart/form-data">
                       {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">

                                <div class="single-query form-group bottom20 @if($errors->has('ar_title')) has-error @endif">
                                    <label>{{ __('admin.ar_title') }}</label>
                                    <input type="text" name="ar_title" class="keyword-input" value="{{ old('ar_title') }}" placeholder="{{ __('admin.prop_ar_title') }}">
                                </div>
                                <div class="single-query form-group bottom20">
                                    <label>{{ __('admin.prop_ar_desc') }}</label><br>
                                    <textarea class="col-md-12" name="ar_description">{{ old('ar_description') }}</textarea>
                                </div>

                                <div class="single-query bottom20">
                                    <label>{{ __('admin.status') }} </label>
                                    <div class="intro">
                                        <select name="prop_type" id="prop_type">
                                            <option value="sale" class="active">{{ __('admin.for_sale') }}</option>
                                            <option value="rent">{{ __('admin.for_rent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="single-query form-group bottom20 @if($errors->has('en_title')) has-error @endif">
                                    <label>{{ __('admin.en_title') }}</label>
                                    <input type="text"  value="{{ old('en_title') }}" class="keyword-input" name="en_title" placeholder="Enter your property english title">
                                </div>
                                <div class="single-query form-group bottom20">
                                    <label> {{ __('admin.prop_en_desc') }} </label><br>
                                    <textarea class="col-md-12" name="en_description">{{ old('en_description') }}</textarea>
                                </div>

                                <div class="single-query form-group bottom20" id="sale">
                                    <label>{{ __('admin.price') }}</label>
                                    <input type="number" value="{{ old('price') }}" step="1000" class="keyword-input" min="0" name="price" placeholder="{{ __('admin.price') }}">
                                </div>
                                <div class="single-query form-group bottom20 hidden" id="rent">
                                    <label>{{ __('admin.rent') }}</label>
                                    <input type="number"  step="100" class="keyword-input" min="0" name="rent" placeholder="{{ __('admin.rent') }}">
                                </div>
                                <div class="single-query form-group bottom20 hidden" id="compound">
                                    <label>{{ __('admin.compound') }}</label>
                                    <select name="compound">
                                        @foreach(@App\Project::all() as $project)
                                            <option value="{{ $project->id }}">{{ $project->{app()->getLocale().'_name'} }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>


                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="margin40 bottom15">Propertie Photos</h3>


                            <div class="file_uploader bottom20 col-sm-12">
                                <div class="box col-sm-6">
                                    <label class="col-sm-12" style="margin: 0 30px">{{ __('admin.main_image') }}</label>
                                    <input type="file" name="image" style="display: none;" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple />
                                    <label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                                </div>
                                <div class="box col-sm-6">
                                    <label class="col-sm-12" style="margin: 0 30px">{{ __('admin.other_images') }}</label>
                                    <input type="file" name="other_images" style="display: none;" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple />
                                    <label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="bottom15 margin40">Propertie Detail</h3>
                        </div>
                    </div>

                        <div class="row">
                            <div class=" form-group @if($errors->has('type')) has-error @endif col-md-6 bottom20">
                                <label>{{ trans('admin.type') }}</label>
                                <select class="select2 form-control" name="type" id="type" data-placeholder="{{ trans('admin.type') }}">
                                    <option></option>
                                    <option value="personal"
                                            @if("personal" == request()->type || old('type') == 'personal' ) selected @endif>{{ trans('admin.personal') }}</option>
                                    <option value="commercial"
                                            @if("commercial" == request()->type || old('type') == 'commercial') selected @endif>{{ trans('admin.commercial') }}</option>
                                </select>
                            </div>

                            <div class="form-group @if($errors->has('unit_type_id')) has-error @endif col-md-6 bottom20">
                                <label>{{ trans('admin.unit_type') }}</label>
                                <select class="select2 form-control" name="unit_type_id"
                                        data-placeholder="{{ trans('admin.unit_type') }}" id="unit_type">
                                    <option></option>
                                    @foreach(@\App\UnitType::all() as $type)
                                        <option value="{{ $type->id }}"
                                                @if($type->id == request()->type_id || old('unit_type_id') == $type->id) selected @endif>{{ $type->{app()->getLocale().'_name'} }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">

                                <div class="single-query form-group bottom20">
                                    <label>{{ __('admin.size') }}</label>
                                    <div class="intro">
                                        <input type="number" value="{{ old('area') }}" step="1" class="keyword-input" min="0" name="area" placeholder="{{ __('admin.size') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-4">

                                <div class="single-query form-group bottom20">
                                    <label>{{ __('admin.rooms') }}</label>
                                    <div class="intro">
                                        <input type="number" step="1"value="{{ old('rooms') }}" class="keyword-input" min="0" name="rooms" placeholder="{{ __('admin.rent') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-4">

                                <div class="single-query  form-group bottom20">
                                    <label>{{ __('admin.bathrooms') }}</label>
                                    <div class="intro">
                                        <input type="number" step="1" value="{{ old('bathrooms') }}" class="keyword-input" min="0" name="bathrooms" placeholder="{{ __('admin.bathrooms') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('finishing')) has-error @endif">
                                    <label>{{ trans('admin.finishing') }}</label>
                                    <select class="select2 form-control col-sm-12" name="finishing" data-placeholder="{{ trans('admin.finishing') }}">
                                        <option></option>
                                        <option value="finished" @if(old('finishing') == 'finished')selected @endif>{{ trans('admin.finished') }}</option>
                                        <option value="semi_finished" @if(old('finishing') == 'semi_finished')selected @endif>{{ trans('admin.semi_finished') }}</option>
                                        <option value="not_finished" @if(old('finishing') == 'not_finished')selected @endif>{{ trans('admin.not_finished') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('view')) has-error @endif col-md-4">
                                <label>{{ trans('admin.view') }}</label>
                                <select class="select2 form-control" name="view" data-placeholder="{{ trans('admin.view') }}">
                                    <option></option>
                                    <option value="main_street" @if(old('view') == 'main_street')selected @endif>{{ trans('admin.main_street') }}</option>
                                    <option value="bystreet" @if(old('view') == 'bystreet')selected @endif>{{ trans('admin.bystreet') }}</option>
                                    <option value="garden" @if(old('view') == 'garden')selected @endif>{{ trans('admin.garden') }}</option>
                                    <option value="corner" @if(old('view') == 'corner')selected @endif>{{ trans('admin.corner') }}</option>
                                    <option value="sea_or_river" @if(old('view') == 'sea_or_river')selected @endif>{{ trans('admin.sea_or_river') }}</option>
                                </select>
                            </div>
                            <div class="form-group @if($errors->has('project_id')) has-error @endif col-md-4">
                                <label>{{ trans('admin.project') }}</label>
                                <select class="select2 form-control" name="project_id" data-placeholder="{{ trans('admin.project') }}">
                                    <option></option>
                                    @foreach(@\App\Project::all() as $project)
                                        <option value="{{ $project->id }}"
                                                @if($project->id == request()->project) selected @endif>{{ $project->{app()->getLocale().'_name'} }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <h3 class="bottom15 margin40">{{ __('admin.property_features') }}</h3>
                                <div class="search-propertie-filters">
                                    <div class="container-2">
                                        <div class="row">
                                            @foreach($facilities as $facility)
                                            <div class="col-md-4 col-sm-4">
                                                <div class="search-form-group white">
                                                    <input type="checkbox" class="check-box" id="fa-{{ $facility->id }}" name="check-box" value="{{ $facility->id }}" />
                                                    <label for="fa-{{ $facility->id }}">{{ $facility->{app()->getLocale().'_name'} }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <h3 class="bottom15 margin40">Video Presentation</h3>
                                <div class="single-query form-group bottom15">
                                    <label>{{ __('admin.video') }}</label>
                                    <input type="text" class="keyword-input" placeholder="https://vimeo.com">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h3 class="bottom15 margin40">Place on Map</h3>
                                <div class="single-query form-group bottom15">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group @if($errors->has('location')) has-error @endif">
                                                <label>{{ trans('admin.location') }}</label>
                                                <select class="select2 form-control" name="location" id="location"
                                                        data-placeholder="{{ trans('admin.location') }}">
                                                    <option></option>
                                                    @foreach(@\App\Location::all() as $location)
                                                        <option value="{{ $location->id }}" @if($location->id == request()->location) selected
                                                                @endif
                                                                lat="{{ $location->lat }}" lng="{{ $location->lng }}"
                                                                zoom="{{ $location->zoom }}">{{ $location->{app()->getLocale().'_name'} }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group @if($errors->has('en_address')) has-error @endif">
                                                <label>{{ trans('admin.en_address') }}</label>
                                                {!! Form::text('en_address',request()->en_address,['class' => 'form-control', 'placeholder' => trans('admin.en_address'),'id'=>'address']) !!}
                                            </div>

                                            <div class="form-group @if($errors->has('ar_address')) has-error @endif">
                                                <label>{{ trans('admin.ar_address') }}</label>
                                                {!! Form::text('ar_address',request()->ar_address,['class' => 'form-control', 'placeholder' => trans('admin.ar_address')]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="map" style="height: 300px !important; x-index: 999"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="map" style="height: 300px !important;z-index:20"></div>
                            <div class="col-md-4 pull-right">
                                <button type="submit" class="btn-blue hub-btn border_radius margin40">submit property</button>
                            </div>
                        </div>
                        <input id="lat" name="lat" type="hidden"
                               value="@if(request()->has('lat')){{ request()->lat }} @else 30.0595581 @endif">
                        <input id="lng" name="lng" type="hidden"
                               value="@if(request()->has('lng')){{ request()->lng }} @else 31.2233591 @endif">
                        <input id="zoom" name="zoom" type="hidden"
                               value="@if(request()->has('zoom')){{ request()->lng }} @else 7 @endif">
                        <input value="0" name="total" type="hidden">
                        <input value="cash" name="payment_method" type="hidden">
                        <input value="0" name="due_now" type="hidden">
                        <input value="0" name="featured" type="hidden">
                        <input value="{{ auth('lead')->user()->id }}" name="lead_id" type="hidden">
                        <input value="0" name="user_id" type="hidden">
                        <input value=" @if(auth('lead')->user()->phone) {{ auth('lead')->user()->phone }}@endif" name="phone" type="hidden">
                    </form>
                </div>
            </div>

        </div>
    </section>
    <!-- My Properties End -->
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
    <script>
        $(document).on('change','#prop_type',function () {
            if($(this).val() == 'sale'){
                $('#sale').removeClass('hidden');
                $('#rent').addClass('hidden');
            } else if($(this).val() == 'rent'){
                $('#rent').removeClass('hidden');
                $('#sale').addClass('hidden');
            }
        });
        $(document).on('change', '#type', function () {
            var usage = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url('get_unit_types')}}",
                method: 'post',

                data: {usage: usage, _token: _token},
                success: function (data) {
                    $('#unit_type').html(data);
                }
            });
        });
    </script>
@endsection