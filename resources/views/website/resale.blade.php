@extends('website.index')
@section('content')
    <style>
        .page-banner{
            background: url("{{ url('uploads/'.$resale->image )}}") no-repeat fixed;
            background-position:50% ;
        }
        .owl-carousel{
            /*display: inline-block;*/
        }
        @if(app()->getLocale() == 'ar')
        .main-owl .owl-wrapper{
            left: 3000px !important;
        }
        .project-owl .owl-wrapper{
            left: 1140px !important;
        }
        @endif
    </style>
    <!-- Page Banner Start-->
    {{--<section class="page-banner padding">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12 text-center">--}}
                    {{--<h2 class="text-uppercase">{{ $resale->{app()->getLocale().'_name'} }}</h2>--}}
                    {{--<p class="bottom30">{{ @App\Location::find($resale->location_id)->{app()->getLocale().'_name'} }}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}

    <!-- Page Banner End -->
    <!-- Property Detail Start -->
    <section id="property" class="padding_top padding_bottom_half">
        <div class="container">
            <div class="row">
                <div class="col-md-8 listing1 property-details">
                    @if(app()->getLocale() == 'en')
                    <div id="property-d-1" class="owl-carousel single main-owl">
                        <div class="item" style="height: 500px"><img src="{{ url('uploads/'.$resale->image) }}" alt="image" /></div>
                        @foreach(@App\ResalImage::where('unit_id' ,$resale->id)->get() as $img)
                            <div class="item" style="height: 500px"><img src="{{ url('uploads/'.$img->image) }}" alt="image"/></div>
                        @endforeach
                    </div>
                    <div id="property-d-1-2" class="owl-carousel ">
                        <div class="item" style="height: 100px"><img src="{{ url('uploads/'.$resale->image) }}" alt="image" /></div>
                        @foreach(@App\ResalImage::where('unit_id' ,$resale->id)->get() as $img)
                            <div class="item" style="height: 100px"><img src="{{ url('uploads/'.$img->image) }}" alt="image"/></div>
                        @endforeach

                    </div>
                    @elseif(app()->getLocale() == 'ar')
                     <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="{{ url('uploads/'.$resale->image) }}" style="width: 100%" alt="image" />
                        </div>
                         @foreach(@App\ResalImage::where('unit_id' ,$resale->id)->get() as $img)
                            <div class="item">
                                <img src="{{ url('uploads/'.$img->image) }}" alt="image"/>
                            </div>
                        @endforeach
                    </div>
                     <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                      <span class="sr-only">Next</span>
                    </a>
                    </div>
                    @endif
                    <div class="property_meta bg-black bottom40">
                        @foreach(@App\Phase::where('project_id',$resale->id)->get() as $phase)
                            @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                @foreach(@App\Facility::where('id',$fac->facility_id)->get() as $icon)
                                    <span><img src="{{ url('uploads/'.@App\Icon::where('id',$icon->icon)->first()->icon) }}" width="20px">{{ $icon->{app()->getLocale().'_name'} }}</span>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                    <h2 class="text-uppercase arabic-text">{{ __('admin.description') }} {{ __('admin.property') }}</h2>
                    <p class="bottom30">{{ $resale->{app()->getLocale().'_description'} }}</p>
                    {{--<div class="text-it-p bottom40">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam power nonummy nibh tempor cum soluta nobis eleifend option congue nihil imperdiet doming Lorem ipsum dolor sit amet. consectetuer elit sed diam power nonummy</p>--}}
                    {{--</div>--}}
                    <h2 class="text-uppercase bottom20 arabic-text">{{ __('admin.quick_summary') }}</h2>
                    <div class="row property-d-table bottom40">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-striped table-responsive">
                                <tbody>
                                <tr>
                                    <td><b>{{ __('admin.project') }} {{ __('admin.name') }}</b></td>
                                    <td class="text-right">{{ $resale->{app()->getLocale().'_title'} }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.price') }}</b></td>
                                    <td class="text-right">{{ $resale->price }} {{ __('admin.egp') }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.project') }}</b></td>
                                    <td class="text-right">{{ @App\Project::find($resale->project_id)->{app()->getLocale().'_name'} }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.area') }}</b></td>
                                    <td class="text-right">{{ $resale->area }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.finishing') }}</b></td>
                                    <td class="text-right">{{ __('admin.'.$resale->finishing) }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.rooms') }}</b></td>
                                    <td class="text-right">{{ $resale->rooms}}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.bathrooms') }}</b></td>
                                    <td class="text-right">{{ $resale->bathrooms}}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.floor') }}</b></td>
                                    <td class="text-right">{{ $resale->floor}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    @if(isset($resale->youtube_link))
                        <h2 class="text-uppercase bottom20">Video Presentation</h2>
                        <div class="row bottom40">
                            <div class="col-md-12 padding-b-20">
                                <div class="pro-video">
                                    <figure class="wpf-demo-gallery">
                                        <iframe id="ytplayer" type="text/html" width="100%" height="360"
                                                src="{{ $resale->youtube_link }}"
                                                frameborder="0"></iframe>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    @endif
                    <h2 class="text-uppercase bottom20 arabic-text">{{ __('admin.location') }}</h2>
                    <div class="row bottom40">
                        <div class="col-md-12 bottom20">
                            <div class="property-list-map">
                                <div id="map" style="width:100% ;height:430px !important;z-index:20"></div>
                                <div id="property-listing-map" class="multiple-location-map" style="width:100%;height:430px;"></div>
                            </div>
                        </div>
                        <div class="sharethis-inline-share-buttons"></div>

                    </div>



                </div>
                <aside class="col-md-4 col-xs-12">
                    @include('admin.error')
                    @if(@auth('lead')->user())
                        <form action="{{ url('interested_lead') }}" method="post" class="callus" style="margin-bottom: 55px;">
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="project">
                            <input type="hidden" name="agent_id" value="0">
                            <input type="hidden" name="user_id" value="0">
                            <input type="hidden" name="project_id" value="{{ $resale->id }}">
                            <div class="col-sm-12">
                                <div class="single-query bottom15">
                                    <button type="submit" class=" hub-btn hub-btn-website col-xs-12">{{ __('admin.interested') }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="property-query-area clearfix">
                            @include('website/search')
                        </div>
                    @else
                        {{ __('admin.interested_statement') }}
                        <form action="{{ url('interested_lead') }}" method="post" class="callus">
                            {{ csrf_field() }}

                            <div class="col-sm-12">
                                <div class="single-query">
                                    <label class="arabic-text" style="color: #000!important">{{ __('admin.name') }}</label>
                                    <input name="first_name" type="text" class="keyword-input col-xs-6" value="{{ old('first_name') }}" required placeholder="{{ __('admin.first_name') }}">
                                    <input name="last_name" type="text" class="keyword-input col-sm-6"  value="{{ old('last_name') }}" required placeholder="{{ __('admin.last_name') }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="single-query">
                                    <label class="arabic-text" style="color: #000!important">{{ __('admin.email') }}</label>
                                    <input name="email" type="email" class="keyword-input" required value="{{ old('email') }}" placeholder="{{ __('admin.email') }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="single-query bottom-15">
                                    <label class="arabic-text" style="color: #000!important">{{ __('admin.mobile') }}</label>
                                    <input name="phone" type="tel" class="keyword-input" value="{{ old('phone') }}" required placeholder="{{ __('admin.mobile') }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="single-query bottom15">
                                    <button type="submit" class=" hub-btn hub-btn-website col-xs-12">{{ __('admin.interested') }}</button>
                                </div>
                            </div>
                            <input type="hidden" name="type" value="project">
                            <input type="hidden" name="agent_id" value="0">
                            <input type="hidden" name="user_id" value="0">
                            <input type="hidden" name="project_id" value="{{ $resale->id }}">

                        </form>
                    @endif
                    @include('website.featured_projects',['featured',$featured])

                </aside>
            </div>
        </div>
    </section>
    <!-- Property Detail End -->

@endsection
@section('js')
    <script>
        function initAutocomplete() {
            var lat = parseFloat({{ $resale->lat }});
            var lng = parseFloat({{ $resale->lng }});
            var zoom = parseFloat({{ $resale->zoom }});
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: zoom,
                mapTypeId: 'roadmap'
            });



            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            google.maps.event.addListener(map, 'zoom_changed', function() {
                $('#zoom').val(map.getZoom())
            });

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection