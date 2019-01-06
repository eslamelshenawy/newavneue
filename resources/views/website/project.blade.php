@extends('website.index')
@section('content')
<style>
    .page-banner{
        background: url("{{ url('uploads/'.$project->website_cover )}}") no-repeat fixed;
        background-position:50% ;
    }


</style>
<!-- Page Banner Start-->
<section class="page-banner padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center" >
                <img src="{{ url('uploads/'.$project->logo) }}" style="width: 70px;height: 70px;border-radius: 70px;">
                <h2 class="text-uppercase">{{ $project->{app()->getLocale().'_name'} }}</h2>
                <p class="">{{ @App\Location::find($project->location_id)->{app()->getLocale().'_name'} }}</p>
            </div>
            @php($developer = @App\Developer::where('id',$project->developer_id)->first())
            <a href="{{ url('developer/'.slug($developer->{app()->getLocale().'_name'}).'-'.$developer->id) }}">
                <img class="developer-logo"  src="{{ url('uploads/'.$developer->logo) }}">
            </a>
        </div>
    </div>
</section>

<!-- Page Banner End -->
<!-- Property Detail Start -->
<section id="property" class="padding_top padding_bottom_half">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-xs-12 listing1 property-details">
                {{--<h2 class="text-uppercase">{{ $project->{app()->getLocale().'_name'} }}</h2>--}}
                {{--<p class="bottom30">{{ @App\Location::find($project->location_id)->{app()->getLocale().'_name'} }}</p>--}}
                @if(app()->getLocale() == 'en')
                <div id="property-d-1" class="owl-carousel single main-owl">

                    <div class="item"><img src="{{ url('uploads/'.$project->cover) }}" alt="image" /></div>
                    @foreach(@App\Gallery::where('project_id' ,$project->id)->get() as $img)
                    <div class="item"><img src="{{ url('uploads/'.$img->image) }}" alt="image"/></div>
                    @endforeach
                </div>
                <div id="property-d-1-2" class="owl-carousel">
                    <div class="item" style="height: 100px"><img src="{{ url('uploads/'.$project->cover) }}" alt="image" /></div>
                    @foreach(@App\Gallery::where('project_id' ,$project->id)->get() as $img)
                    <div class="item" style="height: 100px"><img src="{{ url('uploads/'.$img->image) }}" alt="image"/></div>
                    @endforeach

                </div>
                @elseif(app()->getLocale() == 'ar')
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="{{ url('uploads/'.$project->cover) }}" alt="image" />
                        </div>
                        @foreach(@App\Gallery::where('project_id' ,$project->id)->get() as $img)
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
                <div class="property_meta bg-white bottom40 row">
                    @foreach(@App\UnitFacility::where('unit_id',$project->id)->where('type','project')->get() as $facility)
                    @php($f = @\App\Facility::find($facility->facility_id))
                    @php($icon = @\App\Icon::find($f->icon))
                    <span class="col-xs-3 pull-left" style="line-height: 3.5">
                                                <span class="col-sm-2 pull-left" style="margin: 8px 10px 0 10px;">
                                                <img src="{{ url('uploads/'.$icon->icon) }}" style="width: 25px">
                                                    </span>
                                                <span class="text-left" style="padding-top: 20px;display:block;padding-left:1px;font-size: 11px">
                                                {{ $f->{app()->getLocale().'_name'} }}
                                                    </span>
                                            </span>
                    @endforeach
                </div>
                <h2 class="text-uppercase arabic-text">{{ __('admin.description') }} {{ __('admin.property') }}</h2>
                <p class="bottom30 arabic-text">{{ $project->{app()->getLocale().'_description'} }}</p>
                {{--<div class="text-it-p bottom40">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam power nonummy nibh tempor cum soluta nobis eleifend option congue nihil imperdiet doming Lorem ipsum dolor sit amet. consectetuer elit sed diam power nonummy</p>--}}
                    {{--</div>--}}

                <h2 class="text-uppercase bottom20 arabic-text">{{ __('admin.quick_summary') }}</h2>
                <div class="row property-d-table bottom40">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped table-responsive">
                            <tbody>
                            <tr>
                                <td><b>{{ __('admin.developer') }}</b></td>
                                <td class="text-right">{{ @App\Developer::where('id',$project->developer_id)->first()->{app()->getLocale().'_name'} }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ __('admin.project') }} {{ __('admin.name') }}</b></td>
                                <td class="text-right">{{ $project->{app()->getLocale().'_name'} }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ __('admin.area') }}</b></td>
                                <td class="text-right">{{ $project->area }} @if($project->area_to != 0) {{ __('admin.to').' '.$project->area_to }} @endif</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped table-responsive">
                            <tbody>
                            <tr>
                                <td><b>{{ __('admin.down_payment') }}</b></td>
                                <td class="text-right">{{ $project->down_payment }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ __('admin.installment_year') }}</b></td>
                                <td class="text-right">{{ $project->installment_year }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ __('admin.maintenance_fees') }}</b></td>
                                <td class="text-right">{{ $project->maintenance_fees }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                @if(@App\Phase::where('project_id',$project->id)->count())
                <h2 class="text-uppercase">{{ __('admin.phases') }}</h2>

                <div class="row bottom40">
                    @foreach(@App\Phase::where('project_id',$project->id)->get() as $phase)
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="pro-img">
                            <figure class="wpf-demo-gallery">
                                <a href="{{ url('phase/'.$phase->id) }}">
                                    <img src="{{ url('uploads/'.$phase->promo) }}" alt="image"/>
                                    <span class="phase_name">{{ $phase->{app()->getLocale().'_name'} }}</span>
                                </a>
                            </figure>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                @if(isset($project->video))
                <h2 class="text-uppercase bottom20 arabic-text">{{ __('admin.Video Presentation') }}</h2>
                <div class="row bottom40">
                    <div class="col-md-12 padding-b-20">
                        <div class="pro-video">
                            <figure class="wpf-demo-gallery">
                                <iframe id="ytplayer" type="text/html" width="100%" height="360"
                                        src="{{ $project->video }}"
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


                <div class="row">
                    <div class="col-sm-10">
                        <h2 class="text-uppercase top20 arabic-text">{{ __('admin.similar_projects') }}</h2>
                        <p class="bottom30">{{ __('admin.similar_sub') }}</p>
                    </div>
                    <div class="col-sm-12"><div id="two-col-slider" class="owl-carousel project-owl">
                        @if(count($tags)>0)
                            @foreach($tags as $item)
                            @if(@App\Project::where('id',$item->project_id)->where('show_website',1)->get())
                            @php($project_taged = @App\Project::find($item->project_id))

                            <div class="item">
                                <div class="property_item heading_space">
                                    <div class="image" style="height: 200px;">
                                        <a href="{{ url('project/'.slug(@$project_taged->en_name).'-'.@$project_taged->id) }}"><img src="{{ url('uploads/'.@$project_taged->website_cover) }}" style="height: 200px" alt="latest property" height="120px" class="img-responsive"></a>
                                        <div class="price clearfix">
                                            <span class="tag pull-right">{{ @$project_taged->meter_price }} {{ __('admin.per_meter') }}</span>
                                        </div>
                                        {{--<span class="tag_t">For Sale</span>--}}
                                        {{--<span class="tag_l">Featured</span>--}}
                                    </div>
                                    <div class="proerty_content">
                                        <div class="proerty_text">
                                            <h3 class="captlize"><a href="#.">{{ @$project_taged->{app()->getLocale().'_name'} }}</a></h3>
                                            <p>{{ @App\Location::find(@$project_taged->location_id)->{app()->getLocale().'_name'} }}</p>
                                        </div>
                                        @if(!auth()->guard('lead')->guest())

                                        <div class="fav like-btn-box" type="resale"  unit_id="{{ @$unit->id }}" style="cursor: pointer"><span>
                                                    <i class="icon-like" id="fav{{ @$unit->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',@$unit->id)->first()) color: #caa42d; @endif"></i></span></div>

                                        @endif
                                        <div class="property_meta transparent">
                                            @foreach(@App\UnitFacility::where('unit_id',@$project_taged->id)->get() as $fac)
                                            @foreach(@App\Facility::where('id',$fac->facility_id)->get() as $icon)
                                            <span class="col-sm-4 pull-right"><img src="{{ url('uploads/'.@App\Icon::where('id',$icon->icon)->first()->icon) }}" width="20px">&nbsp;{{ $icon->{app()->getLocale().'_name'} }}</span>
                                            @endforeach
                                            @endforeach
                                        </div>


                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        @endif    
                        </div></div>
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
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
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
                <p class="arabic-text">
                    {{ __('admin.interested_statement') }}
                </p>
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
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

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
    $('.slick').slick({
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    });
</script>

<script>
    function initAutocomplete() {
        var lat = parseFloat({{ $project->lat }});
        var lng = parseFloat({{ $project->lng }});
        var zoom = parseFloat({{ $project->zoom }});
        var icon= '{{ url('uploads/'.$project->map_marker) }}';
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: lat, lng: lng},
            zoom: zoom,
            mapTypeId: 'roadmap',
            icon:icon,
        });



        var marker = new google.maps.Marker({
            position: {lat: lat, lng: lng},
            map: map,
            animation: google.maps.Animation.DROP,
            icon:icon,
        });

        google.maps.event.addListener(map, 'zoom_changed', function() {
            $('#zoom').val(map.getZoom())
        });

    }

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
        async defer></script>
@endsection