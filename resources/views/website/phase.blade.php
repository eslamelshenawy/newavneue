@extends('website.index')
@section('content')
    <style>
        .page-banner{
            background: url("{{ url('uploads/'.$phase->promo )}}") no-repeat fixed;
            background-position:50% ;
        }
        .owl-carousel{
            /*display: inline-block;*/
        }
    </style>
    <!-- Page Banner Start-->
    <section class="page-banner padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="text-uppercase">{{ $phase->{app()->getLocale().'_name'} }}</h2>
                    <p class="bottom30">{{ @App\Location::find(@App\Project::find($phase->project_id)->location_id)->{app()->getLocale().'_name'} }}</p>

                </div>
            </div>
        </div>
    </section>
    <!-- Page Banner End -->
    <!-- Property Detail Start -->
    <section id="property" class="padding_top padding_bottom_half">
        <div class="container">
            <div class="row">
                <div class="col-md-8 listing1 property-details">
                    {{--<h2 class="text-uppercase">{{ $phase->{app()->getLocale().'_name'} }}</h2>--}}
                    {{--<p class="bottom30">{{ @App\Location::find(@App\Project::find($phase->project_id)->location_id)->{app()->getLocale().'_name'} }}</p>--}}
                    <div id="property-d-1" class="owl-carousel single">

                        <div class="item" style="height: 500px"><img src="{{ url('uploads/'.$phase->promo) }}" alt="image" /></div>
                        @foreach(@App\Gallery::where('project_id' ,$phase->id)->get() as $img)
                            <div class="item" style="height: 500px"><img src="{{ url('uploads/'.$img->image) }}" alt="image"/></div>
                        @endforeach

                    </div>
                    <div id="property-d-1-2" class="owl-carousel">
                        <div class="item" style="height: 100px"><img src="{{ url('uploads/'.$phase->promo) }}" alt="image" /></div>
                        @foreach(@App\Gallery::where('project_id' ,$phase->id)->get() as $img)
                            <div class="item" style="height: 100px"><img src="{{ url('uploads/'.$img->image) }}" alt="image"/></div>
                        @endforeach

                    </div>
                    <div class="property_meta bg-black bottom40">
                        @foreach(@App\Phase::where('project_id',$phase->id)->get() as $phase)
                            @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                @foreach(@App\Facility::where('id',$fac->facility_id)->get() as $icon)
                                    <span><img src="{{ url('uploads/'.@App\Icon::where('id',$icon->icon)->first()->icon) }}" width="20px">{{ $icon->{app()->getLocale().'_name'} }}</span>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                    <div id="exampleAccordion" data-children=".item">
                        @php($i = 0)
                    @foreach(@App\Property::where('phase_id',$phase->id)->groupBy('unit_id')->get() as $property)
                        <div class="item" >
                        <a  data-toggle="collapse"  data-parent="#exampleAccordion" href="#exampleAccordion{{ $i }}" aria-expanded="false" aria-controls="exampleAccordion2">
                         <h1 style="background: #161616;color: #caa42d;padding: 5px 30px;">{{ @App\UnitType::find($property->unit_id)->{app()->getLocale().'_name'} }}</h1>
                        </a>
                        <div id="exampleAccordion{{ $i }}" class="collapse" role="tabpanel">
                            <p class="mb-3">
                            <ul style="font-size: 16px;padding: 0 45px">
                                <li>{{__('admin.area_from')}} : {{ @App\Property::where('unit_id',$property->unit_id)->min('area_from')  }} {!! __('admin.m2') !!}</li>
                                <li>{{__('admin.area_to')}} : {{ @App\Property::where('unit_id',$property->unit_id)->min('area_to')  }} {!! __('admin.m2') !!}</li>
                                <li>{{__('admin.starting_price')}} : {{ @App\Property::where('unit_id',$property->unit_id)->min('start_price')  }} {{ __('admin.egp') }}</li>
                            </ul>
                            </p>
                        </div>
                        </div>
                        @php($i++)
                    @endforeach
                    </div>
                    <h2 class="text-uppercase">{{ __('admin.property') }} {{ __('admin.description') }} </h2>
                    <p class="bottom30">{{ $phase->{app()->getLocale().'_description'} }}</p>
                    {{--<div class="text-it-p bottom40">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam power nonummy nibh tempor cum soluta nobis eleifend option congue nihil imperdiet doming Lorem ipsum dolor sit amet. consectetuer elit sed diam power nonummy</p>--}}
                    {{--</div>--}}
                    <h2 class="text-uppercase bottom20">Quick Summary</h2>
                    <div class="row property-d-table bottom40">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-striped table-responsive">
                                <tbody>
                                <tr>
                                    <td><b>{{ __('admin.project') }} {{ __('admin.name') }}</b></td>
                                    <td class="text-right">{{ $phase->{app()->getLocale().'_name'} }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.meter_price') }}</b></td>
                                    <td class="text-right">{{ $phase->meter_price }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.down_payment') }}</b></td>
                                    <td class="text-right">{{ @App\Project::find($phase->project_id)->down_payment }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.area') }}</b></td>
                                    <td class="text-right">{{ $phase->area }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.developer') }}</b></td>
                                    <td class="text-right">{{  @App\Developer::find(@App\Project::find($phase->project_id)->developer_id)->{app()->getLocale().'_name'} }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('admin.installment_year') }}</b></td>
                                    <td class="text-right">{{ @App\Project::find($phase->project_id)->installment_year }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    @if(isset($phase->video))
                    <h2 class="text-uppercase bottom20">{{ __('admin.video') }}</h2>
                    <div class="row bottom40">
                        <div class="col-md-12 padding-b-20">
                            <div class="pro-video">
                                <figure class="wpf-demo-gallery">
                                    <iframe id="ytplayer" type="text/html" width="640" height="360"
                                            src="{{@App\Project::find($phase->project_id)->video}}"
                                            frameborder="0"></iframe>
                                </figure>
                            </div>
                        </div>
                    </div>
                    @endif
                    <h2 class="text-uppercase bottom20">{{ __('admin.location') }}</h2>
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
                            <input type="hidden" name="project_id" value="{{ $phase->project_id }}">
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
                                    <label style="color: #000!important">{{ __('admin.name') }}</label>
                                    <input name="first_name" type="text" class="keyword-input col-xs-6" value="{{ old('first_name') }}" required placeholder="{{ __('admin.first_name') }}">
                                    <input name="last_name" type="text" class="keyword-input col-sm-6"  value="{{ old('last_name') }}" required placeholder="{{ __('admin.last_name') }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="single-query">
                                    <label style="color: #000!important">{{ __('admin.email') }}</label>
                                    <input name="email" type="email" class="keyword-input" required value="{{ old('email') }}" placeholder="{{ __('admin.email') }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="single-query bottom-15">
                                    <label style="color: #000!important">{{ __('admin.mobile') }}</label>
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
                            <input type="hidden" name="project_id" value="{{ $phase->project_id }}">

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
            var lat = parseFloat({{ @App\Project::find($phase->project_id)->lat }});
            var lng = parseFloat({{ @App\Project::find($phase->project_id)->lng }});
            var zoom = parseFloat({{ @App\Project::find($phase->project_id)->zoom }});
            var icon ='{{ url('uploads/'.@App\Project::find($phase->project_id)->map_marker) }}';
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: zoom,
                mapTypeId: 'roadmap'
            });



            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                icon:icon,
            });

            google.maps.event.addListener(map, 'zoom_changed', function() {
                $('#zoom').val(map.getZoom())
            });

        }
        $('.collapse').collapse();
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection