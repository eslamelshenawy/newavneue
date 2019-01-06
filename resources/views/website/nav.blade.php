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
                            </li>New Avenue is an Egyptian based Real Estate Consultancy firm. Aiming to always provide our client base with the most beneficial real estate decisions, we ensure a Win-Win relationship. Whether it’s Buying, Selling, or Renting, New Avenue provides the Real Estate market clients with all-round needed real estate services.Our vision is to become the leading Real Estate consultancy firm in Egypt with the finest and most prominent experience for our clients.
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

</header>



