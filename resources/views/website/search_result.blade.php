@extends('website.index')
@section('content')
    <link rel="stylesheet" href="{{ url('style/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('website_style/css/range-Slider.min.css') }}">
    <section id="listing_layout" class="listing1 maplisting">
        <div class="container-fluid">
            <div class="row">
                <div class="ol-md-6 col-sm-6 col-xs-12 listing_map">
                    <div id="banner-map">
                        <div class="row property-list-area property-list-map-area">
                            <div class="property-list-map">
                                <div id="map" class="multiple-location-map" style="width:100%; top:0; bottom:0;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ol-md-6 col-sm-6 col-xs-12 pull-right" @if(app()->getLocale()=='ar') style="float:right !important; text-align: right" @endif>
                    <div class="property-query-area top20">
                        <div class="row">
                            @include('website.search')
                        </div>

                        </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="text-uppercase bottom20">Properties listings</h2>
                        </div>
                        {{--{{ dd(request()->type) }}--}}
                        @foreach($data as $row)
                        <div class="col-sm-6">
                            <div class="property_item heading_space">
                                <div class="property_head text-center">
                                    <h3 class="captlize">{{ $row['title'] }}</h3>
                                    <p>{{ $row['location'] }}</p>
                                </div>
                                <div class="image" style="height: 200px">
                                    {{--{{ dd($row) }}--}}
                                    @php($src = 'uploads/')
                                    @if (request()->type == 'project')
                                        @php($src.=$row['cover'])
                                    @else
                                        @php($src.=$row['home_image'])
                                    @endif
                                    <a href="{{ url(request()->type.'/'.slug($row['title']).'-'.$row['id']) }}"> <img style="height: 200px" src="{{ url($src)}}

                                     " alt="latest property" class="img-responsive"></a>
                                    <div class="price clearfix">
                                        @if($type1=='rental')
                                        <span class="tag">{{ trans('admin.for_rent') }}</span>
                                        @elseif($type1=='resale')
                                            <span class="tag">{{ trans('admin.for_sale') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="proerty_content">
                                    <div class="property_meta">
                                        <span><i class="icon-select-an-objecto-tool"></i>{{ $row['area'] }}</span>
                                        @if($type1!='project')
                                        <span><i class="icon-bed"></i>{{ $row['rooms'] }} Bedrooms</span>
                                        <span><i class="icon-safety-shower"></i>{{ $row['bathrooms'] }} Bedrooms</span>
                                        @endif
                                    </div>
                                    <div class="proerty_text">
                                        <p>{{ \Illuminate\Support\Str::words($row['description'],25) }}</p>
                                    </div>
                                    <div class="favroute clearfix">
                                        <p class="pull-md-left">{{ $row['price'] }} EG</p>
                                        <ul class="pull-right">
                                            @if(!auth()->guard('lead')->guest())

                                                <li class="fav" type="project"  unit_id="{{ $row['id'] }}" style="cursor: pointer;"><span>
                                                    <i class="icon-like" id="fav{{ $row['id'] }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',$row['id'])->first()) color: #caa42d; @endif" ></i></span></li>

                                            @endif
                                            <li><a href="#three" class="share_expender" data-toggle="collapse"><i class="icon-share3"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="toggle_share collapse" id="three">
                                        <ul>
                                            <li><a href='javascript:void(0)' class="facebook"><i class="icon-facebook-1"></i> Facebook</a></li>
                                            <li><a href="javascript:void(0)" class="twitter"><i class="icon-twitter-1"></i> Twitter</a></li>
                                            <li><a href="javascript:void(0)" class="vimo"><i class="icon-vimeo3"></i> Vimeo</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
        </div>
    </section>
{{--{{ dd($data) }}--}}
@endsection
@section('js')
    @if(!auth()->guard("lead")->guest())
        <script>
            $(document).on('click', '.fav', function () {
                alert('sheno');
                var unit_id = $(this).attr('unit_id');
                var type = $(this).attr('type');
                var lead = '{{ auth()->guard("lead")->user()->id }}';
                var _token = '{{ csrf_token() }}';
                $.ajax({
                    url: "{{ url('favorite')}}",
                    method: 'get',
                    dataType: 'json',
                    data: {unit_id: unit_id,type : type,lead:lead, _token: _token},
                    success: function (data) {
                        if (data.response == 'add') {
                            $('#fav'+unit_id).css('color','#caa42d');
                        }else{
                            $('#fav'+unit_id).css('color','#161616');
                        }
                    },
                    error: function() {
                        alert('{{ __('admin.error') }}')
                    }
                })
            })
        </script>
    @endif
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
                    @foreach($data as $row)
            var lat=parseFloat({{ $row['lat'] }});
            var lng=parseFloat({{ $row['lng'] }});
            var zoom=parseInt({{ $row['zoom'] }});

            var marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
            });
            @endforeach
        }
        $(document).ready(function () {
            $('.checkbox').attr('name', 'facility[]');
        });
    </script>
    <script src="{{  url('website_style/js/jquery-2.1.4.js')}}"></script>
    <script src="{{  url('website_style/js/bootstrap.min.js')}}"></script>
    <script src="{{  url('website_style/js/jquery.appear.js')}}"></script>
    <script src="{{  url('website_style/js/jquery-countTo.js') }}"></script>
    <script src="{{  url('website_style/js/bootsnav.js') }}"></script>
    <script src="{{  url('website_style/js/masonry.pkgd.min.js') }}"></script>
    <script src="{{  url('website_style/js/jquery.parallax-1.1.3.js') }}"></script>
    <script src="{{  url('website_style/js/jquery.cubeportfolio.min.js') }}"></script>
    <script src="{{  url('website_style/js/range-Slider.min.js') }}"></script>
    <script src="{{  url('website_style/js/owl.carousel.min.js') }}"></script>
    <script src="{{  url('website_style/js/selectbox-0.2.min.js') }}"></script>
    <script src="{{  url('website_style/js/zelect.js') }}"></script>
    <script src="{{  url('website_style/js/jquery.fancybox.js') }}"></script>
    <script src="{{  url('website_style/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{  url('website_style/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{  url('website_style/js/revolution.extension.actions.min.js') }}"></script>
    <script src="{{  url('website_style/js/revolution.extension.layeranimation.min.js') }}"></script>
    <script src="{{  url('website_style/js/revolution.extension.navigation.min.js') }}"></script>
    <script src="{{  url('style/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{  url('website_style/js/revolution.extension.parallax.min.js') }}"></script>
    <script src="{{  url('website_style/js/revolution.extension.slideanims.min.js') }}"></script>
    <script src="{{  url('website_style/js/revolution.extension.video.min.js') }}"></script>
    <script src="{{  url('website_style/js/custom.js') }}"></script>
    <script src="{{  url('website_style/js/functions.js') }}"></script>
    <script src="{{  url('website_style/js/load.js') }}"></script>
    <script src="{{  url('website_style/css/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).on('click', '#price_range', function () {
            $('#min_price').val($('#mi_price').text());
            $('#max_price').val($('#ma_price').text());
        })
        $(document).on('click', '#area_range', function () {
            $('#min_area').val($('#mi_area').text());
            $('#max_area').val($('#ma_area').text());
        })

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
        $('.select2').select2();
    </script>

    @endsection