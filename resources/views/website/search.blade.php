<form class="callus" action="{{ url('search') }}" method="get" style="padding: 20px">
    {{ csrf_field() }}
    <h2 class="text-uppercase t_white bottom20 text-center">{{ __('admin.search_title') }}</h2>
    <div class="row">
        <div class="col-sm-12">
            <div class="single-query bottom15">
                <input type="text" name="keyword" class="keyword-input" placeholder="Keyword (e.g. 'office')">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="single-query bottom15">
                <select name="type" class="select2" id="property_type"
                        data-placeholder="{{ trans('admin.select_type') }}">
                    <option></option>
                    <option value="project" selected>{{ trans('admin.project') }}</option>
                    <option value="resale" >{{ trans('admin.resale') }}</option>
                    <option value="rental">{{ trans('admin.rental') }}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="single-query bottom15">
                <select name="location" class="select2" data-placeholder="{{ trans('admin.select_location') }}">
                    <option></option>
                    @foreach($search['region'] as $location)
                        <option value="{{ $location['id'] }}">{{  $location['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="single-query bottom15">
                <select class="select2" id="unit_type" multiple name="unit_type[]" data-placeholder="select unit type">
                    <option></option>
                    @foreach($search['unit_type'] as $unit_type)
                        <option value="{{ $unit_type['id'] }}">{{ $unit_type['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="non_project">
            <div class="search-2">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="single-query bottom15">
                        <select class="select2" name="rooms"
                                data-placeholder="{{ trans('admin.select_number_of_rooms') }}">
                            <option></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="single-query bottom15">
                        <select class="select2" name="bathrooms"
                                data-placeholder="{{ trans('admin.select_number_of_bathrooms') }}">
                            <option></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <input type="hidden" name="min_price" id="min_price" value="{{ $search['data']['project_min_price'] }}">
        <input type="hidden" name="max_price" id="max_price" value="{{ $search['data']['project_max_price'] }}">
        <input type="hidden" name="min_area" id="min_area" value="{{ $search['data']['project_min_area'] }}">
        <input type="hidden" name="max_area" id="max_area" value="{{ $search['data']['project_max_area'] }}">
        <div class="col-md-12 col-sm-12 col-xs-12 bottom15">
            <div class="single-query-slider">
                <div class="clearfix top20 right-direction">
                    <label class="pull-left" id="price_lable">{{ trans('admin.price') }}</label>
                    <div class="price text-right">
                        (
                        <div class="leftLabel" id="mi_price"></div>
                        <span> {{ __('admin.egp') }}</span> )
                        <span>{{ __('admin.to') }} ( <div class="rightLabel" id="ma_price"></div> {{ __('admin.egp') }} )</span>

                    </div>
                </div>
                <div id="price_range" data-range_min="{{ $search['data']['project_min_price'] }}"
                     data-range_max="{{ $search['data']['project_max_price'] }}"
                     data-cur_min="{{ $search['data']['project_min_price'] }}"
                     data-cur_max="{{ $search['data']['project_max_price'] }}"
                     class="nstSlider">
                    <div class="bar"></div>
                    <div class="leftGrip"></div>
                    <div class="rightGrip"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 bottom15">
            <div class="single-query-slider">
                <div class="clearfix top20 right-direction">
                    <label class="pull-left">{{ __('admin.area_range') }}</label>
                    <div class="price text-right"> (
                        <div class="leftLabel" id="mi_area"></div>
                        <span>{!!  __('admin.m2') !!} ) </span>

                        <span>{{ __('admin.to') }} ( <div class="rightLabel" id="ma_area"></div> {!!  __('admin.m2') !!} )</span>
                    </div>
                </div>
                <div id="area_range" data-range_min="{{ $search['data']['project_min_area'] }}"
                     data-range_max="{{ $search['data']['project_max_area'] }}"
                     data-cur_min="{{ $search['data']['project_min_area'] }}"
                     data-cur_max="{{ $search['data']['project_max_area'] }}"
                     class="nstSlider">
                    <div class="bar"></div>
                    <div class="leftGrip"></div>
                    <div class="rightGrip"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-4 col-xs-4 text-right form-group top30 pull-right">
            <button type="submit" class="border_radius btn-yellow text-uppercase">{{ __('admin.search') }}</button>
        </div>
    </div>
    <div class="row" id="facility1">
        <div class="col-sm-12">
            <div class="group-button-search">
                <a data-toggle="collapse" href=".search-propertie-filters" class="more-filter">
                    <i class="fa fa-plus text-1" aria-hidden="true"></i><i class="fa fa-minus text-2 hide"
                                                                           aria-hidden="true"></i>
                    <div class="text-1">{{ __('admin.Show more search options') }}</div>
                    <div class="text-2 hide">{{ __('admin.less more search options') }}</div>
                </a>
            </div>
            <div class="search-propertie-filters collapse">
                <div class="container-2">
                    <div class="row">
                        @foreach($search['facilities'] as $facility)
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="search-form-group white bottom10">
                                    <input type="checkbox" class="checkbox" value="{{ $facility['id'] }}"
                                           name="check-box"/>
                                    <span>{{ $facility['name'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
    <script>

        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat:25 , lng: 30 },
                zoom: 6,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.
            @if(isset($data))
            @foreach($data as $row)
            var icon= '{{ url('uploads/'.@$row['marker']) }}';
            var lat=parseFloat({{ $row['lat'] }});
            var lng=parseFloat({{ $row['lng'] }});
            var zoom=parseInt({{ $row['zoom'] }});

            var marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                icon:icon,
            });
            @endforeach
            @endif
        }
    </script>
    <script>

        $('.select2').select2();
        $(document).on('click', '#price_range', function () {
            $('#min_price').val($('#mi_price').text());
            $('#max_price').val($('#ma_price').text());
        })
        $(document).on('click', '#area_range', function () {
            $('#min_area').val($('#mi_area').text());
            $('#max_area').val($('#ma_area').text());
        })
        $('#non_project').hide();
        $(document).on('change', '#property_type', function () {
            if ($(this).val() == "project") {
                $('#non_project').hide();
                $('#facility1').show();
                $('#price_range').nstSlider('set_range', parseInt("{{ $search['data']['project_min_price'] }}"), parseInt("{{ $search['data']['project_max_price'] }}"));
                $('#area_range').nstSlider('set_range', parseInt("{{ $search['data']['project_min_area'] }}"), parseInt("{{ $search['data']['project_max_area'] }}"));
                $('#price_range').nstSlider('set_position', parseInt("{{ $search['data']['project_min_price'] }}"), parseInt("{{ $search['data']['project_max_price'] }}"));
                $('#area_range').nstSlider('set_position', parseInt("{{ $search['data']['project_min_area'] }}"), parseInt("{{ $search['data']['project_max_area'] }}"));
                $('#price_lable').text("{{ trans('admin.price') }}");
                $('#min_price').val($('#mi_price').text());
                $('#max_price').val($('#ma_price').text());
                $('#min_area').val($('#mi_area').text());
                $('#max_area').val($('#ma_area').text());
            }
            else if ($(this).val() == "resale") {
                $('#non_project').show();
                $('#facility1').hide();
                $('#price_range').nstSlider('set_range', parseInt("{{ $search['data']['resale_min_price'] }}"), parseInt("{{ $search['data']['resale_max_price'] }}"));
                $('#area_range').nstSlider('set_range', parseInt("{{ $search['data']['resale_min_area'] }}"), parseInt("{{ $search['data']['resale_max_area'] }}"));
                $('#price_range').nstSlider('set_position', parseInt("{{ $search['data']['resale_min_price'] }}"), parseInt("{{ $search['data']['resale_max_price'] }}"));
                $('#area_range').nstSlider('set_position', parseInt("{{ $search['data']['resale_min_area'] }}"), parseInt("{{ $search['data']['resale_max_area'] }}"));
                $('#price_lable').text("{{ trans('admin.price') }}");
                $('#min_price').val($('#mi_price').text());
                $('#max_price').val($('#ma_price').text());
                $('#min_area').val($('#mi_area').text());
                $('#max_area').val($('#ma_area').text());
            }
            else if ($(this).val() == "rental") {
                $('#non_project').show();
                $('#facility1').hide();
                $('#price_range').nstSlider('set_range', parseInt("{{ $search['data']['rental_min_price'] }}"), parseInt("{{ $search['data']['rental_max_price'] }}"));
                $('#area_range').nstSlider('set_range', parseInt("{{ $search['data']['rental_min_area'] }}"), parseInt("{{ $search['data']['rental_max_area'] }}"));
                $('#price_range').nstSlider('set_position', parseInt("{{ $search['data']['rental_min_price'] }}"), parseInt("{{ $search['data']['rental_max_price'] }}"));
                $('#area_range').nstSlider('set_position', parseInt("{{ $search['data']['rental_min_area'] }}"), parseInt("{{ $search['data']['rental_max_area'] }}"));
                $('#price_lable').text("{{ trans('admin.rent') }}");
                $('#min_price').val($('#mi_price').text());
                $('#max_price').val($('#ma_price').text());
                $('#min_area').val($('#mi_area').text());
                $('#max_area').val($('#ma_area').text());
            }
        })
    </script>
    <script>
        $(document).ready(function () {
            $('.checkbox').attr('name', 'facility[]');
        });
    </script>

    @endsection