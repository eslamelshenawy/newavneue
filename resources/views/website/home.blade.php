

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>New Avenue</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/reality-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/bootsnav.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/jquery.fancybox.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/owl.transitions.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/cubeportfolio.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/settings.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/range-Slider.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/search.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/slick/slick.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/slick/slick-theme.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/style.css') }}">
    @if(app()->getLocale() == 'ar')
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/css/style_home.css') }}">
    @endif
    <link rel="stylesheet" href="{{ url('style/select2/dist/css/select2.min.css') }}">
    <link rel="icon" href="{{ url('website_style/images/icon.png')}}">
</head>
<style>
    .dropdown:hover .dropdown-menu {
        display: block;
        position: absolute;
    }
</style>
<body>

<header class="layout_double">
    <div class="topbar dark">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    {{--<p>We are Best in Town With 40 years of Experience.</p>--}}
                </div>
                <div class="col-md-7 text-right">
                    <ul class="breadcrumb_top">
                        <li><a href="{{ url('favourite_properties') }}"><i class="icon-icons43"></i>{{ __('admin.favorites') }}</a></li>
                        <li><a href="{{ url('add_properties') }}"><i class="icon-icons215"></i>{{ __('admin.submit_property') }}</a></li>
                        {{--<li><a href="{{ url('my_properties') }}"><i class="icon-icons215"></i>{{ __('admin.my_prop') }}</a></li>--}}
                        @if(auth('lead')->guest())
                            <li><a href="{{ url('lead_login') }}"><i class="icon-icons179"></i>{{ __('admin.login/register') }}</a></li>
                        @else
                            <li><a href="{{ url('profile') }}">
                                    <i class="icon-icons230"></i>{{ auth()->guard('lead')->user()->first_name }} {{ auth()->guard('lead')->user()->last_name }}
                                </a></li>
                            <li>
                                <a href="{{ url('logout') }}"
                                   onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                    {{ __('admin.logout') }}
                                </a>
                                <form id="logout-form" action="{{ url('logout') }}" method="get" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endif
                        @if(app()->getLocale() == 'ar')
                            <a href="{{ url('language/en') }}" class="">
                                <i class="fa fa-globe" style="color: #000;font-size: 20px;"></i>
                                <span class="label" style="font-size: 0.5em;background: #000">en</span>
                            </a>
                        @else
                            <a href="{{ url('language/ar') }}" class="">
                                <i class="fa fa-globe" style="color: #000;font-size: 20px;"></i>
                                <span class="label" style="font-size: 0.5em;background: #000">عربي</span>
                            </a>
                        @endif
                        <li class="last-icon"><i class="icon-icons215"></i></li>
                        <li style="color: #000;"><i class="fas fa-fire"></i>19212</li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-upper dark">
        <div class="container">
            <div class="row">
                <!--<div class="col-md-3 col-xs-6">-->
                <!--    <div class="logo">-->
                <!--        <a href="{{ url('/') }}"><img alt="" src="{{ url('images/logo.png') }}" width="130px" class="img-responsive"></a>-->
                <!--    </div>-->
                <!--</div>-->
                <!--Info Box-->
                <div class="col-md-12 col-xs-12 right">
                     <div class="logo pull-left">
                        <a href="{{ url('/') }}"><img alt="" src="{{ url('images/logo.png') }}" width="130px" class="img-responsive"></a>
                    </div>
                     <button type="button" class="navbar-toggle collapsed pull-right" data-toggle="collapse" data-target="#navbar-menu" aria-expanded="false" style="margin-top: 10px;">
                        <i class="fa fa-bars" style="color:#fff"></i>
                    </button>
                    @include('website.menu')
                </div>
            </div>
        </div>
    </div>

</header>




<!--Slider-->
<div class="big-slider rev_slider_wrapper hidden-md hidden-sm hidden-xs">
    <div id="rev_eight" class="rev_slider"  data-version="5.0">
        <ul>
            <!-- SLIDE  -->
            @foreach(@App\Project::where('on_slider',1)->get() as $slider)
                <li data-transition="fade">
                    <!-- MAIN IMAGE -->
                    <img src="{{ url('uploads/'.$slider->website_slider) }}" alt="" data-bgposition="center center" data-bgfit="cover">

                    <h1 class="tp-caption tp-resizeme uppercase" style="color: #fff;"
                        data-x="center" data-hoffset="0"
                        data-y="275"
                        data-transform_idle="o:1;"
                        data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                        data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                        data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                        data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                        data-start="500"
                        data-splitin="none"
                        data-splitout="none"
                        style="z-index: 6; color: #fff;font-size: 56px">{{ $slider->{app()->getLocale().'_name'} }}
                    </h1>
    
                   <img src="{{ url('uploads/'.$slider->logo) }}" width="200px" alt="{{ $slider->{app()->getLocale().'_name'} }}"
                     class="tp-caption tp-resizeme uppercase"
                     data-x="center" data-hoffset="0"
                     data-y="100"
                     data-transform_idle="o:1;"
                     data-transform_in="x:[100%];z:0;rX:90deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                     data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                     data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                     data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                     data-start="500"
                     data-splitin="none"
                     data-splitout="none">
                    <p class="tp-caption  tp-resizeme"
                       data-x="center" data-hoffset="0"
                       data-y="320"
                       data-transform_idle="o:1;"
                       data-transform_in="opacity:0;s:2000;e:Power3.easeInOut;"
                       data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                       data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                       data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                       data-start="800" style="color: #e6e6e6; font-size: 20px;margin-top: 10px;">{{ @App\Location::find($slider->location_id)->{app()->getLocale().'_name'} }}
                    </p>
                    <img src="{{ url('uploads/'.$slider->image) }}" alt="" data-bgposition="center center" data-bgfit="cover">
                    <div class="slider-caption tp-caption tp-resizeme"
                         data-x="['right','right','center','center']" data-hoffset="['0','0','0','0']"
                         data-y="['center','center','center','center']" data-voffset="['0','0','0','0']"
                         data-responsive_offset="on"
                         data-visibility="['on','on','off','off']"
                         data-start="1500"
                         data-transition="fade"
                         data-transform_idle="o:1;"
                         data-transform_in="x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power3.easeInOut;"
                         data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                         data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                    >

                    </div>
                    <div class="tp-caption  tp-resizeme"
                         data-x="center" data-hoffset="0"
                         data-y="400"
                         data-transform_idle="o:1;"
                         data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                         data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                         data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                         data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                         data-splitin="none"
                         data-splitout="none"
                         data-start="800" style="color: #fff;font-size: 36px;">
                         <span style="display:block">{{ $slider->down_payment }}% {{ __('admin.down_payment')}}</span> 
                            <!--<br><span style="display:block">&</span><br>-->
                         <span style="display:block">{{ __('admin.installment_year') }} {{ $slider->installment_year }} {{ __('admin.years') }}</span>

                    </div>
                    <div class="tp-caption tp-static-layer"
                         id="slide-37-layer-2"
                         data-x="center" data-hoffset="0"
                         data-y="500" data-voffset="560"
                         data-whitespace="nowrap"
                         data-visibility="['on','on','on','on']"
                         data-transform_in="x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power3.easeInOut;"
                         data-start="500"
                         data-basealign="slide"
                         data-startslide="0"
                         data-endslide="5"
                         style="z-index: 5;"><a href="{{ url('project/'.slug($slider->en_name).'-'.$slider->id) }}" class="btn-white border_radius uppercase">{{ __('admin.more_details') }}</a></div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<!--Slider ends-->

<!--Slider-->
<div class="rev_slider_wrapper hidden-lg">
    <div id="rev_slider_full" class="rev_slider"  data-version="5.0">
        <ul>
            <!-- SLIDE  -->
            @foreach(@App\Project::where('on_slider',1)->get() as $slider)
                <li data-transition="fade">
                    <!-- MAIN IMAGE -->
                    <img src="{{ url('uploads/'.$slider->website_slider) }}" alt="" data-bgposition="center center" data-bgfit="cover">

                    <h1 class="tp-caption tp-resizeme uppercase" style="color: #fff;font-size: 40px"
                        data-x="center" data-hoffset="0"
                        data-y="300"
                        data-transform_idle="o:1;"
                        data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                        data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                        data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                        data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                        data-start="500"
                        data-splitin="none"
                        data-splitout="none"
                        style="z-index: 6; color: #fff;font-size: 56px">{{ $slider->{app()->getLocale().'_name'} }}
                    </h1>

                    <img src="{{ url('uploads/'.$slider->logo) }}" width="100px" alt="{{ $slider->{app()->getLocale().'_name'} }}"
                         class="tp-caption tp-resizeme uppercase"
                         data-x="center" data-hoffset="0"
                         data-y="0"
                         data-transform_idle="o:1;"
                         data-transform_in="x:[100%];z:0;rX:90deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                         data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                         data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                         data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                         data-start="500"
                         data-splitin="none"
                         data-splitout="none">
                    <p class="tp-caption  tp-resizeme"
                       data-x="center" data-hoffset="0"
                       data-y="350"
                       data-transform_idle="o:1;"
                       data-transform_in="opacity:0;s:2000;e:Power3.easeInOut;"
                       data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                       data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                       data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                       data-start="800" style="color: #e6e6e6; font-size: 20px;margin-top: 10px;">{{ @App\Location::find($slider->location_id)->{app()->getLocale().'_name'} }}
                    </p>
                    <img src="{{ url('uploads/'.$slider->image) }}" alt="" data-bgposition="center center" data-bgfit="cover">
                    <div class="slider-caption tp-caption tp-resizeme"
                         data-x="['right','right','center','center']" data-hoffset="['0','0','0','0']"
                         data-y="450"
                         data-responsive_offset="on"
                         data-visibility="['on','on','off','off']"
                         data-start="1500"
                         data-transition="fade"
                         data-transform_idle="o:1;"
                         data-transform_in="x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power3.easeInOut;"
                         data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                         data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                    >

                    </div>
                    <div class="tp-caption  tp-resizeme"
                         data-x="center" data-hoffset="0"
                         data-y="400"
                         data-transform_idle="o:1;"
                         data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                         data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                         data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                         data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                         data-splitin="none"
                         data-splitout="none"
                         data-start="800" style="color: #fff;font-size: 36px;">
                        <spna  style="background: #000;padding: 10px;font-size: 40px">{{ $slider->down_payment }}% {{ __('admin.down_payment')}}</spna>
                        <span  style="background: #000;padding: 10px;font-size: 40px">{{ $slider->installment_year }} {{ __('admin.installment') }}
                            @if($slider->installment_year > 1 && app()->getLocale() == 'en'){{ __('admin.years')}}
                            @elseif($slider->installment_year <= 1 && app()->getLocale() == 'en'){{ __('admin.year')}}
                            @elseif($slider->installment_year <= 10 && app()->getLocale() == 'ar'){{ __('admin.years')}}
                            @elseif($slider->installment_year > 10 && app()->getLocale() == 'ar'){{ __('admin.year')}}</span>
                        @endif


                    </div>
                    <div class="tp-caption tp-static-layer"
                         id="slide-37-layer-2"
                         data-x="center" data-hoffset="0"
                         data-y="500" data-voffset="560"
                         data-whitespace="nowrap"
                         data-visibility="['on','on','on','on']"
                         data-transform_in="x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power3.easeInOut;"
                         data-start="500"
                         data-basealign="slide"
                         data-startslide="0"
                         data-endslide="5"
                         style="z-index: 5;"><a href="{{ url('project/'.slug($slider->en_name).'-'.$slider->id) }}" class="btn-white border_radius uppercase">{{ __('admin.more_details') }}</a></div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<!--Slider ends-->



<!-- Property Search area Start -->
<form class="callus container" action="{{ url('search') }}" method="get" style="padding: 20px">
    {{ csrf_field() }}
    <h2 class="text-uppercase t_white bottom20 text-center">{{ __('admin.search_title') }}</h2>
    <div class="row">
        <div class="col-sm-4">
            <div class="single-query bottom15">
                <input type="text" name="keyword" class="keyword-input" style="margin: 0;" placeholder="{{ __('admin.Keyword (e.g. "office")') }}">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="single-query bottom15">
                <select name="location" class="select2" data-placeholder="{{ trans('admin.select_location') }}">
                    <option></option>
                    @foreach($search['region'] as $location)
                        <option value="{{ $location['id'] }}">{{  $location['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="single-query bottom15">
                <select class="select2" id="unit_type" multiple name="unit_type[]" data-placeholder="{{ __('admin.select unit type') }}" style="height: 48px;">
                    <option></option>
                    @foreach($search['unit_type'] as $unit_type)
                        <option value="{{ $unit_type['id'] }}">{{ $unit_type['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-2" style="margin-top: 30px">
            <div class="single-query bottom15">
                <select name="type" class="select2" id="property_type"
                        data-placeholder="{{ trans('admin.select_type') }}">
                    <option></option>
                    <option value="project" selected>{{ trans('admin.project') }}</option>
                    <option value="resale"  >{{ trans('admin.resale') }}</option>
                    <option value="rental">{{ trans('admin.rental') }}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="col-sm-6">
                <input type="hidden" name="min_price" id="min_price" value="{{ $search['data']['project_min_price'] }}">
                <input type="hidden" name="max_price" id="max_price" value="{{ $search['data']['project_max_price'] }}">
                <input type="hidden" name="min_area" id="min_area" value="{{ $search['data']['project_min_area'] }}">
                <input type="hidden" name="max_area" id="max_area" value="{{ $search['data']['project_max_area'] }}">
                <div class="col-md-12 col-sm-12 col-xs-12 bottom15" >
                    <div class="single-query-slider">
                        <div class="clearfix top20">
                            <label class="@if(app()->getLocale()=='en') pull-right @else pull-left @endif" id="price_lable">{{ trans('admin.price') }}</label>
                            <div class="price text-right @if(app()->getLocale()=='en') pull-left @else pull-right @endif">
                                (
                                <div class="leftLabel" id="mi_price"></div>
                                <span> EG</span> )
                                <span>to ( <div class="rightLabel" id="ma_price"></div> EG )</span>

                            </div>
                        </div>
                        <div id="price_range" data-range_min="{{ $search['data']['project_min_price'] }}"
                             data-range_max="{{ $search['data']['project_max_price'] }}"
                             data-cur_min="{{ $search['data']['project_min_price'] }}"
                             data-cur_max="{{ $search['data']['project_max_price'] }}"
                             class="nstSlider animating_css_class">
                            <div class="bar"></div>
                            <div class="leftGrip"></div>
                            <div class="rightGrip"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="col-md-12 col-sm-12 col-xs-12 bottom15">
                    <div class="single-query-slider">
                        <div class="clearfix top20">
                            <label class="pull-left">area Range:</label>
                            <div class="price text-right"> (
                                <div class="leftLabel" id="mi_area"></div>
                                <span>m<sup>2</sup> ) </span>

                                <span>to ( <div class="rightLabel" id="ma_area"></div> m<sup>2</sup> )</span>
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

            </div>
        </div>

    </div>
    <div id="non_project">
        <div class="search-2">
            <div class="col-md-4 col-sm-4 col-xs-4">
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
            <div class="col-md-4 col-sm-4 col-xs-4">
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

    <div class="row" id="">

        <div class="col-sm-12">
            <div class="group-button-search " style="float: left;">
                <a data-toggle="collapse" href=".search-propertie-filters" class="more-filter ">
                    <i class="fa fa-plus text-1" aria-hidden="true"></i><i class="fa fa-minus text-2 hide"
                                                                           aria-hidden="true"></i>
                    <div class="text-1">{{ __('admin.Show more search options') }}</div>
                    <div class="text-2 hide">{{ __('admin.less more search options') }}</div>
                </a>

            </div>
            <div class="col-md-8 col-sm-8 col-xs-12 text-right facility1 form-group top30 pull-right">
                <button type="submit" class="border_radius btn-yellow text-uppercase">{{ __('admin.search') }}</button>
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
<!-- Property Search area End -->


<!-- Latest Property -->
<section id="property" class="padding index2 bg_light">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2 class="uppercase">{{ __('admin.featured_projects') }}</h2>
               
            </div>
        </div>
        <div class="row">
            @foreach(@App\Project::where('show_website',1)->orderby('featured_priority')->limit('9')->get() as $project)
                <div class="col-sm-6 col-md-4 feature-home">
                <div class="property_item heading_space home-project" style="min-height:240px;">
                    <div class="project-details">
                        {{--{{ $project->down_payment }}% {{ __('admin.down_payment')}} & {{ $project->installment_year }}--}}
                        @if(isset($project->down_payment))
                            <div style="margin-top: 30px">
                                <bold>{{ __('admin.down_payment') }}</bold>
                                <h4> {{ @$project->down_payment }}%</h4>
                            </div>
                        @endif
                        @if(isset($project->installment_year))
                            <div style="margin-top: 10px">
                                <bold>{{ __('admin.installment') }}</bold>
                                <h4>{{ @$project->installment_year }} {{ __('admin.years') }}</h4>
                            </div>
                        @endif
                        @if(isset($project->delivery_date))
                            <div style="margin-top: 10px">
                                <bold>{{ __('admin.delivery_date') }}</bold>
                                <h4>{{ @$project->delivery_date }}</h4>
                            </div>
                        @endif
                        <a style="border: 1px solid #000 ;border-radius: 5px;padding: 0 5px;font-size: 14px;margin-top: 20px;display: block;width: 100px;margin: 20px auto;" href="{{ url('project/'.slug($project->{app()->getLocale().'_name'}).'-'.$project->id) }}">{{ __('admin.more_details') }}</a>
                    </div>
                    <div class="image">

                            <div style="background: url({{ url('uploads/'.$project->logo) }}) #fff;
                                    width: 70px;
                                    height: 70px;
                                    position: absolute;
                                    background-size: contain;
                                    float: right;
                                    right: 0;z-index: 1;"></div><img src="{{ url('uploads/'.$project->cover) }}" alt="latest property" class="img-responsive">
                        <div class="price clearfix">
                            <span class="tag pull-right">{{ $project->{app()->getLocale().'_name'} }}</span>
                        </div>
                    </div>
                    <h4 class="text-right" style="margin: 10px 10px 0 0">{{ $project->meter_price }} {{ __('admin.EGP') }} {{ __('admin.meter_price') }}</h4>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Latest Property Ends -->
<section id="" class="padding col-md-10 col-md-offset-1">
    <h2 class="uppercase text-center" style="padding:0 0 30px 0;color: black">{{ __('admin.featured_resale') }}</h2>
    <section class="center slider col-xs-12">
        @foreach(@App\ResaleUnit::where('featured',1)->orderby('priority')->get() as $unit)
            <a href="{{ url('resale/'.slug($unit->{app()->getLocale().'_title'}).'-'.$unit->id) }}">
                <div class="resale-unit" >
                    <div class="col-sm-6" style="background: url({{ url($unit->watermarked_image) }});background-position: 50%;background-size:cover;height: 450px;padding: 0;margin-bottom: 20px; ">
                        <div class="resale-data" class="col-sm-12 bottom-20">
                            <h3 class="margin" style="padding: 4px 8px;font-size: 18px">{{ $unit->{app()->getLocale().'_title'} }}</h3>
                            <h5 class="margin" style="padding: 0 8px;width: 100%">{{ @App\Location::find($unit->location)->{app()->getLocale().'_name'} }} <span class="pull-right" style="text-align: right">{{ $unit->price}} {{ __('admin.egp') }}</span></h5>
                            <span class="col-xs-3 col-xs-offset-4" style="color:#fff;text-align: center;width: 100%">
                                @if(isset($unit->rooms))
                                    <span class="pull-left resale-home"><i class="fas fa-bed"></i> {{ $unit->rooms }}</span>
                                @endif
                                @if(isset($unit->bathrooms))
                                    <span class="pull-left resale-home"><i class="fas fa-bath"></i> {{ $unit->bathrooms }}</span>
                                @endif
                            </span>

                        </div>
                    </div>

                </div>
            </a>
        @endforeach
    </section>

</section>
<!--Partners-->

<section id="logos">
    <div class="container partner2 padding">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2 class="uppercase">{{ __('admin.our_partners') }}</h2>
                <p class="heading_space"></p>
            </div>
        </div>
        <div class="row">
            <div id="partners" class="owl-carousel" style="overflow: visible;">
                @foreach(@App\Developer::where('featured',1)->get() as $developer)
                    <div class="item">
                        <a href="{{ url('developer/'.slug($developer->{app()->getLocale().'_name'}).'-'.$developer->id) }}">
                            <img src="{{ url('uploads/'.$developer->logo)}}" alt="Featured Partner" style="width: 100px;height: 100px;border-radius: 50%" data-toggle="tooltip" data-placement="right" title="{{ $developer->{app()->getLocale().'_name'} }}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!--Partners Ends-->

@php
    $header =  $_SERVER['HTTP_USER_AGENT'];
@endphp
@if(stripos($header,'Android') !== false)
    <div class="android-dev" style="position: fixed;bottom: 0;background: #fff;width: 100%;float:right;padding: 20px;z-index: 1000">
        <a href="{{ \App\Setting::first()->play_store }}" style="width: 80%;float: left" >For better experience download our app<img src="{{ url('website_style/images/play-store-icon.png') }}" width="30px"></a>
        <div style="width: 20%;border-left:1px solid #000;float: left" class="dapp-close-btn"><i class="fas fa-times pull-right " style="text-align: center;font-size: 22px"></i></div>
    </div>


@elseif(stripos($header,'iPhone') !== false){
    <div class="android-dev" style="position: fixed;bottom: 0;background: #fff;width: 100%;float:right;padding: 20px;z-index: 1000">
        <a href="{{ \App\Setting::first()->apple_store }}" style="width: 80%;float: left" >For better experience download our app<img src="{{ url('website_style/images/apple.png') }}" width="30px"></a>
        <div style="width: 20%;border-left:1px solid #000;float: left" class="dapp-close-btn"><i class="fas fa-times pull-right " style="text-align: center;font-size: 22px"></i></div>
    </div>
@endif

<!--Footer-->
<footer class="footer_third" >
    <div class="container padding_top about-background2">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="footer_panel bottom30">
                    <a href="{{ url('/') }}" class="logo bottom30"><img src="{{ url('website_style/images/logo.png')}}" width="200px" alt="logo"></a>
                    @if(app()->getLocale() == 'en')
                        <p class="bottom15">{{ @App\Setting::first()->about_hub }}
                        </p>
                    @else
                        <p class="bottom15">{{ @App\Setting::first()->ar_about_hub }}
                        </p>
                    @endif

                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="footer_panel bottom30">
                    <h4 class="bottom30 heading">{{ __('admin.latest_news') }}</h4>
                    @foreach(@App\Event::limit(3)->get() as $news)
                        <div class="media bottom30">
                            <div class="media-body">
                                <a href="{{ url('event/'.$news->id) }}">{{ $news->{app()->getLocale().'_title'} }}</a>
                                <span><i class="icon-clock5"></i>{{ date('M d Y',strtotime($news->created_at)) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="footer_panel bottom30">
                    <h4 class="bottom30 heading">{{ __('admin.Subscribe') }}</h4>
                    <p>{{ __('admin.subscribe') }}</p>
                    <form class="top30" method="post" action="{{ url('newsletter') }}">
                        {{ csrf_field() }}
                        <input class="search" name="email" placeholder="{{ __('admin.email_enter') }}" type="text">
                        <button class="button_s" href="#">
                            <i class="icon-mail-envelope-open"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!--CopyRight-->
        <div class="copyright_simple">
            <div class="row">
                <div class="col-md-6 col-sm-5 top20 bottom20">
                    <p>Copyright &copy; 2017 <span>PropertzCrm</span>. All rights reserved.</p>
                </div>
                <div class="col-md-6 col-sm-7 text-right top15 bottom10">
                    <ul class="social_share">
                        @foreach(@App\HubSocial::all() as $social)
                            <li><a href="{{ $social->link }}" class="facebook"><img src="{{ url('uploads/'.$social->web_icon) }}" style="height: 30px"></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>




<script src="{{  asset('website_style/js/jquery-2.1.4.js')}}"></script>
<script src="{{  asset('website_style/js/bootstrap.min.js')}}"></script>
<script src="{{  asset('website_style/js/jquery.appear.js')}}"></script>
<script src="{{  asset('website_style/js/jquery-countTo.js') }}"></script>
<script src="{{  asset('website_style/js/bootsnav.js') }}"></script>
<script src="{{  asset('website_style/js/masonry.pkgd.min.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.parallax-1.1.3.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.cubeportfolio.min.js') }}"></script>
<script src="{{  asset('website_style/js/range-Slider.min.js') }}"></script>
<script src="{{  asset('website_style/js/owl.carousel.min.js') }}"></script>
<script src="{{  asset('website_style/js/selectbox-0.2.min.js') }}"></script>
<script src="{{  asset('website_style/js/zelect.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.fancybox.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.actions.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.layeranimation.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.navigation.min.js') }}"></script>
<script src="{{  asset('style/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.parallax.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.slideanims.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.video.min.js') }}"></script>
<script src="{{  asset('website_style/js/custom.js') }}"></script>
<script src="{{  asset('website_style/js/functions.js') }}"></script>
<script src="{{  asset('website_style/css/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>
@yield('js')
<script>
    $('.dapp-close-btn').on('click',function () {
        $('.android-dev').addClass('hidden');
    });
    $('.dapp-close-btn').on('click',function () {
        $('.ios-dev').addClass('hidden');
    });
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
        $('.checkbox').attr('name', 'facilities[]');
    });
</script>
<script>
    $(".center").slick({
        dots: false,
        infinite: true,
        centerMode: true,
        autoplay:true,
        slidesToShow: 3,
        centerPadding: '-120px',

        arrows:true,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1000,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '20px',
                    slidesToShow: 1
                }
            }
            ,
            {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '50px',
                    slidesToShow: 1,
                    variableWidth: true,
                    autoplay:true,
                }
            }
        ]
    });
    $(".center2").slick({
        dots: false,
        infinite: true,
        autoplay:true,
        slidesToShow: 1,
        centerMode: true,
        centerPadding: '20px',
        variableWidth: true,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '200px',
                    slidesToShow: 1,
                    variableWidth: true,
                    variableHeight: true,
                    autoplay:true,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '50px',
                    slidesToShow: 1,
                    variableWidth: true,
                    variableHeight: true,
                    autoplay:true,
                }
            }
        ]
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
<script>
    var show = true;
    $('.more-filter').on('click',function () {

        if(show==true)
        {
            $('.social').addClass('hidden');
            show=false;
        }

        else
        {
            show=true;
            $('.social').removeClass('hidden');
        }


    });


    $(document).ready(function () {
        $('.checkbox').attr('name','facility[]')
    })
</script>
@yield('js')
</body>
</html>

