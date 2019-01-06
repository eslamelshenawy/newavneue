@extends('website.index')
@section('content')
    <!-- Page Banner Start-->
    <style>
        .page-banner{
            background: url("{{ asset('website_style/images/6.jpg') }}")  no-repeat fixed !important;
        }
    </style>
    <section class="page-banner padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase">{{ __('admin.rental') }}</h1>

                </div>
            </div>
        </div>
    </section>
    <!-- Page Banner End -->



    <!-- Listing Start -->
    <section id="listing1" class="listing1 padding_top">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-9">
                            <h2 class="uppercase">{{ __('admin.property_listings') }}</h2>
                        </div>

                    </div>
                    <div class="row">
                        @foreach($rental as $property)
                            <div class="col-sm-6 not-filter">
                                <div class="property_item heading_space">
                                    <div class="image">
                                        <a href="{{ url('rental/'.slug($property->{app()->getLocale().'_title'}).'-'.$property->id) }}"><img src="{{ url('uploads/'.$property->image) }}" alt="{{ $property->{app()->getLocale().'_name'} }}" class="img-responsive"></a>
                                        <div class="price clearfix">
                                            <span class="tag pull-right">{{ $property->price }} {{ __('admin.egp') }}</span>
                                        </div>
                                        <span class="tag_t">{{ __('admin.resale') }}</span>
                                    </div>
                                    <div class="proerty_content">
                                        <div class="proerty_text">
                                            <h3 class="captlize"><a href="{{ url('rental/'.slug($property->{app()->getLocale().'_title'}).'-'.$property->id) }}">{{ $property->{app()->getLocale().'_title'} }}</a></h3>
                                            <p>{{ @App\Location::find($property->location_id)->{app()->getLocale().'_title'} }}</p>
                                        </div>
                                        @if(!auth()->guard('lead')->guest())

                                            <div class="fav like-btn-box" type="rental"  unit_id="{{ $property->id }}" style="cursor: pointer"><span>
                                                    <i class="icon-like" id="fav{{ $property->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',$property->id)->first()) color: #caa42d; @endif"></i></span></div>

                                        @endif
                                        <div class="property_meta transparent" >
                                            @foreach(@App\UnitFacility::where('unit_id',$property->id)->where('type','rental')->limit(3)->get() as $facility)
                                                @php($f = @\App\Facility::find($facility->facility_id))
                                                @php($icon = @\App\Icon::find($f->icon))
                                                <span class="col-xs-4" style="line-height: 3.5">
                                                <span class="col-sm-2" style="margin: 7px;">
                                                <img src="{{ url('uploads/'.$icon->icon) }}" style="width: 25px">
                                                    </span>
                                                <span class="text-left" style="padding-top: 10px;padding-left:1px;font-size: 11px">
                                                {{ $f->{app()->getLocale().'_name'} }}
                                                    </span>
                                            </span>
                                            @endforeach
                                        </div>

                                        <div class="toggle_share collapse" id="one">
                                            <ul>
                                                <li><a href="#." class="facebook"><i class="icon-facebook-1"></i> Facebook</a></li>
                                                <li><a href="#." class="twitter"><i class="icon-twitter-1"></i> Twitter</a></li>
                                                <li><a href="#." class="vimo"><i class="icon-vimeo3"></i> Vimeo</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach($all_projects as $property)

                            <div class="col-sm-6 hidden to-filter"
                                 price="{{ $property->rent }}"
                                 area="{{ $property->area }}"
                                 location="{{ $property->location }}"
                                 en_name="{{ $property->en_title}}"
                                 ar_name="{{ $property->ar_title }}"
                                 rooms="{{ $property->rooms }}"
                                 barhrooms="{{ $property->bathrooms }}">
                                <div class="property_item heading_space">
                                    <div class="image">
                                        <a href="{{ url('resale/'.slug($property->{app()->getLocale().'_title'}).'-'.$property->id) }}"><img src="{{ url($property->watermarked_image) }}" alt="{{ $property->{app()->getLocale().'_name'} }}" class="img-responsive"></a>
                                        <div class="price clearfix">
                                            <span class="tag pull-right">{{ $property->price }} {{ __('admin.egp') }}</span>
                                        </div>
                                        <span class="tag_t">{{ __('admin.resale') }}</span>
                                    </div>
                                    <div class="proerty_content">
                                        <div class="proerty_text">
                                            <h3 class="captlize"><a href="{{ url('resale/'.$property->id) }}">{{ $property->{app()->getLocale().'_title'} }}</a></h3>
                                            <p>{{ @App\Location::find($property->location_id)->{app()->getLocale().'_name'} }}</p>
                                        </div>
                                        @if(!auth()->guard('lead')->guest())

                                            <div class="fav like-btn-box" type="rental"  unit_id="{{ $property->id }}" style="cursor: pointer"><span>
                                                    <i class="icon-like" id="fav{{ $property->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',$property->id)->first()) color: #caa42d; @endif"></i></span></div>

                                        @endif
                                        <div class="property_meta transparent" >
                                            @foreach(@App\UnitFacility::where('unit_id',$property->id)->where('type','rental')->limit(3)->get() as $facility)
                                                @php($f = @\App\Facility::find($facility->facility_id))
                                                @php($icon = @\App\Icon::find($f->icon))
                                                <span class="col-xs-4" style="line-height: 3.5">
                                                <span class="col-sm-2" style="margin: 7px;">
                                                <img src="{{ url('uploads/'.$icon->icon) }}" style="width: 25px">
                                                    </span>
                                                <span class="text-left" style="padding-top: 10px;padding-left:1px;font-size: 11px">
                                                {{ $f->{app()->getLocale().'_name'} }}
                                                    </span>
                                            </span>
                                            @endforeach
                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="padding_bottom text-center">
                        <ul class="pager">
                            {{ $rental->links() }}
                        </ul>
                    </div>
                </div>
                <aside class="col-md-4 col-xs-12 ">
                    <div class="property-query-area clearfix">
                        <form class="callus" action="{{ url('search') }}" method="post" style="padding: 20px">
                            {{ csrf_field() }}
                            <h2 class="text-uppercase t_white bottom20 text-center">{{ __('admin.filter') }}</h2>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="single-query">
                                        <input type="text" name="keyword" id="keyword" class="keyword-input" placeholder="{{ __('admin.Keyword (e.g. "office")') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="single-query bottom15">
                                        <select name="location" id="location" class="select2" data-placeholder="{{ trans('admin.select_location') }}">
                                            <option></option>
                                            @foreach($search['region'] as $location)
                                                <option value="{{ $location['id'] }}">{{  $location['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="single-query">
                                        <input type="number" name="keyword" id="rooms" class="keyword-input" placeholder="{{ __('admin.rooms') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="single-query">
                                        <input type="number" name="keyword" id="bathrooms" class="keyword-input" placeholder="{{ __('admin.bathrooms') }}">
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <input type="hidden" name="min_price" id="min_price" value="{{ $search['data']['rental_commercial_min_price'] }}">
                                <input type="hidden" name="max_price" id="max_price" value="{{ $search['data']['rental_commercial_max_price'] }}">
                                <input type="hidden" name="min_area" id="min_area" value="{{ $search['data']['rental_commercial_min_area'] }}">
                                <input type="hidden" name="max_area" id="max_area" value="{{ $search['data']['rental_commercial_max_area'] }}">
                                <div class="col-md-12 col-sm-12 col-xs-12 bottom15">
                                    <div class="single-query-slider">
                                        <div class="clearfix top20">
                                            <label class="pull-left" id="price_lable">{{ trans('admin.price') }}</label>
                                            <div class="price text-right">
                                                (
                                                <div class="leftLabel" id="mi_price"></div>
                                                <span> {{ __('admin.egp') }}</span> )
                                                <span>{{ __('admin.to') }} ( <div class="rightLabel" id="ma_price"></div> {{ __('admin.egp') }} )</span>

                                            </div>
                                        </div>
                                        <div id="price_range" data-range_min="{{ $search['data']['rental_commercial_min_price'] }}"
                                             data-range_max="{{ $search['data']['rental_commercial_max_price'] }}"
                                             data-cur_min="{{ $search['data']['rental_commercial_min_price'] }}"
                                             data-cur_max="{{ $search['data']['rental_commercial_max_price'] }}"
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
                                        <div class="clearfix top20">
                                            <label class="pull-left">{{ __('admin.area_range') }}</label>
                                            <div class="price text-right"> (
                                                <div class="leftLabel" id="mi_area"></div>
                                                <span>{!!  __('admin.m2') !!} ) </span>

                                                <span>to ( <div class="rightLabel" id="ma_area"></div> {!!  __('admin.m2') !!} )</span>
                                            </div>
                                        </div>
                                        <div id="area_range" data-range_min="{{ $search['data']['rental_commercial_min_area'] }}"
                                             data-range_max="{{ $search['data']['rental_commercial_max_area'] }}"
                                             data-cur_min="{{ $search['data']['rental_commercial_min_area'] }}"
                                             data-cur_max="{{ $search['data']['rental_commercial_max_area'] }}"
                                             class="nstSlider">
                                            <div class="bar"></div>
                                            <div class="leftGrip"></div>
                                            <div class="rightGrip"></div>
                                        </div>
                                    </div>
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
                                                    <div class="col-md-4 col-sm-6 col-xs-12">
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
                    </div>
                    @include('website.featured_projects',['featured',$featured])
                </aside>
            </div>
        </div>
    </section>
    <!-- Listing end -->
@endsection
@section('js')
    <script>
        $('.select2').select2();
        $(document).on('click change keyup', '#price_range , #area_range ,#location ,#keyword ,#rooms ,#bathrooms', function () {
            $('.not-filter').addClass('hidden');
            $('.pager').addClass('hidden');
            $('#min_price').val($('#mi_price').text());
            $('#max_price').val($('#ma_price').text());
            $('.to-filter').each(function () {
                if(parseInt($(this).attr('price')) >= parseInt($('#mi_price').text()) &&
                    parseInt($(this).attr('price')) <= parseInt($('#ma_price').text()) &&
                    parseInt($(this).attr('area')) >= parseInt($('#mi_area').text())  &&
                    parseInt($(this).attr('area')) <= parseInt($('#ma_area').text())
                ){
                    if (!isNaN(parseInt($('#location').val()))) {
                        if( parseInt($(this).attr('location')) == parseInt($('#location').val())) {
                            if($('#keyword').val()) {
                                if($(this).attr('en_name').toLowerCase().includes($('#keyword').val().toLowerCase())) {
                                    $(this).removeClass('hidden');
                                }else{
                                    $(this).addClass('hidden');
                                }
                            }else{
                                $(this).removeClass('hidden');
                            }

                        }
                        else{

                            $(this).addClass('hidden');
                        }
                    }
                    else{
                        if($('#keyword').val()) {
                            if($(this).attr('en_name').toLowerCase().includes($('#keyword').val().toLowerCase())) {
                                $(this).removeClass('hidden');
                            }else{
                                $(this).addClass('hidden');
                            }
                        }else {
                            $(this).removeClass('hidden');
                        }
                    }
                }
                else{
                    $(this).addClass('hidden');
                }
            });
        });
        $(document).on('click', '#area_range', function () {
            $('#min_area').val($('#mi_area').text());
            $('#max_area').val($('#mi_area').text());
        });
        $('#non_project').hide();
        $('#facility1').show();
        $(document).ready(function () {
            $('#price_range').nstSlider('set_range', parseInt("{{ $search['data']['rental_commercial_min_price'] }}"), parseInt("{{ $search['data']['rental_commercial_max_price'] }}"));
            $('#area_range').nstSlider('set_range', parseInt("{{ $search['data']['rental_commercial_min_area'] }}"), parseInt("{{ $search['data']['rental_commercial_max_area'] }}"));
            $('#price_range').nstSlider('set_position', parseInt("{{ $search['data']['rental_commercial_min_price'] }}"), parseInt("{{ $search['data']['rental_commercial_max_price'] }}"));
            $('#area_range').nstSlider('set_position', parseInt("{{ $search['data']['rental_commercial_min_area'] }}"), parseInt("{{ $search['data']['rental_commercial_max_area'] }}"));
            $('#price_lable').text("{{ trans('admin.price') }}");
            $('#min_price').val($('#mi_price').text());
            $('#max_price').val($('#ma_price').text());
            $('#min_area').val($('#mi_area').text());
            $('#max_area').val($('#ma_area').text());
        });
    </script>

    @if(!auth()->guard("lead")->guest())
        <script>
            $(document).on('click', '.fav', function () {
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
            });
        </script>
    @endif
@endsection
