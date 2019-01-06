@extends('website.index')
@section('content')
    @if(isset($developer->website_cover))
    <style>
        .page-banner{
            background: url({{ url("uploads/".$developer->website_cover) }});
            background-size: cover !important;
            background-attachment: fixed;
            background-position: center center;
        }
    </style>
    @endif
    <!-- Page Banner Start-->
    <section class="page-banner padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="{{ url('uploads/'.$developer->logo) }}" style="width: 70px;height: 70px;border-radius: 70px">
                    <h1 class="text-uppercase">{{ $developer->{app()->getLocale().'_name'} }}</h1>
                    {{--<ol class="breadcrumb text-center">--}}
                        {{--<li><a href="#">Home</a></li>--}}
                        {{--<li><a href="#">Pages</a></li>--}}
                        {{--<li class="active">Favorite properties</li>--}}
                    {{--</ol>--}}
                </div>
            </div>
        </div>
    </section>
    <!-- Page Banner End -->



    <!-- Favorite Properties  -->
    <section id="property" class="padding_top listing1">
        <div class="container">
           <div class="row">
               <p class="text-center">{{ $developer->{app()->getLocale().'_description'} }}</p>
               @foreach(@App\Project::where('developer_id',$developer->id)->get() as $project)
                <div class="col-sm-4">
                    <div class="property_item heading_space">
                        <div class="image" style="height: 200px">
                            <a href="{{ url('project/'.$project->id) }}"><img style="height: 200px" src="{{ url('uploads/'.$project->cover) }}" alt="latest property" class="img-responsive"></a>
                            <div class="price clearfix">
                                <span class="tag pull-right">{{ $project->meter_price }} {{__('admin.per_meter')}}</span>
                            </div>
                            <span class="tag_l">{{ __('admin.developer') }}</span>
                        </div>
                        <div class="proerty_content">
                            <div class="proerty_text">
                                <h3 class="captlize"><a href="#.">{{ $project->{app()->getLocale().'_name'} }}</a></h3>
                                <p>{{ @App\Location::find($project->location_id)->{app()->getLocale().'_name'} }}</p>
                            </div>
                            <div class="property_meta transparent">
                                @foreach(@App\Phase::where('project_id',$project->id)->get() as $phase)
                                    @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                        @foreach(@App\Facility::where('id',$fac->facility_id)->get() as $icon)
                                            <span><img src="{{ url('uploads/'.@App\Icon::where('id',$icon->icon)->first()->icon) }}" width="20px">{{ $icon->{app()->getLocale().'_name'} }}</span>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </div>

                            <div class="favroute clearfix">
                                <p class="pull-md-left"><i class="icon-calendar2"></i>
                                    @php($now = \Carbon\Carbon::now())
                                    @if($now->diffInMonths( $project->created_at)>0){{ $now->diffInMonths( $project->created_at).' '.__('admin.month') }}
                                    @endif
                                    @if($now->diffInDays( $project->created_at)>=10)
                                        {{ $now->diffInDays( $project->created_at).' '.__('admin.days') }}
                                    @elseif($now->diffInDays( $project->created_at)>0 && $now->diffInDays( $project->created_at)<10)
                                        {{ $now->diffInDays( $project->created_at).' '.__('admin.day') }}
                                    @elseif($now->diffInDays( $project->created_at)==0)
                                        {{ __('admin.today') }}
                                    @endif </p>
                                <ul class="pull-right">
                                    @if(!auth()->guard('lead')->guest())

                                        <li class="fav" type="project"  unit_id="{{ $project->id }}" style="cursor: pointer;"><span>
                                                    <i class="icon-like" id="fav{{ $project->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',$project->id)->first()) color: #caa42d; @endif" ></i></span></li>

                                    @endif
                                    <li><a href="#one" class="share_expender" data-toggle="collapse"><i class="icon-share3"></i></a></li>
                                </ul>
                            </div>
                            <div class="toggle_share collapse" id="proj{{ $project->id }}">
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
               {{--@foreach(@App\ResaleUnit::where('developer_id',$developer->id)->get() as $unit)--}}
           @foreach(@App\Project::where('developer_id',$developer->id)->get() as $project_dev )
               @foreach(@App\RentalUnit::where('project_id',$project_dev->id)->get() as $unit)
                       <div class="col-sm-4">
                       <div class="property_item heading_space">
                           <div class="image" style="height: 200px">
                               <a href="{{ url('rental/'.$unit->id) }}"><img style="height: 200px" src="{{ url('uploads/'.$unit->cover) }}" alt="latest property" class="img-responsive"></a>
                               <div class="price clearfix">
                                   <span class="tag pull-right">{{ $unit->rent }} {{ __('admin.egp') }} {{__('admin.per_month')}}</span>
                               </div>
                               <span class="tag_l">{{ __('admin.rental') }}</span>
                           </div>
                           <div class="proerty_content">
                               <div class="proerty_text">
                                   <h3 class="captlize"><a href="#.">{{ $unit->{app()->getLocale().'_title'} }}</a></h3>
                                   <p>{{ @App\Location::find($unit->location)->{app()->getLocale().'_name'} }}</p>
                               </div>
                               <div class="property_meta transparent">
                                   @foreach(@App\Phase::where('project_id',$project->id)->get() as $phase)
                                       @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                           @foreach(@App\Facility::where('id',$fac->facility_id)->get() as $icon)
                                               <span><img src="{{ url('uploads/'.@App\Icon::where('id',$icon->icon)->first()->icon) }}" width="20px">{{ $icon->{app()->getLocale().'_name'} }}</span>
                                           @endforeach
                                       @endforeach
                                   @endforeach
                               </div>

                               <div class="favroute clearfix">
                                   <p class="pull-md-left"><i class="icon-calendar2"></i> @php($now = \Carbon\Carbon::now())
                                       @if($now->diffInMonths( $unit->created_at)>0){{ $now->diffInMonths( $unit->created_at).' '.__('admin.month') }}
                                       @endif
                                       @if($now->diffInDays( $unit->created_at)>=10)
                                           {{ $now->diffInDays( $unit->created_at).' '.__('admin.days') }}
                                       @elseif($now->diffInDays( $unit->created_at)>0 && $now->diffInDays( $unit->created_at)<10)
                                           {{ $now->diffInDays( $unit->created_at).' '.__('admin.day') }}
                                       @elseif($now->diffInDays( $unit->created_at)==0)
                                           {{ __('admin.today') }}
                                       @endif </p>
                                   <ul class="pull-right">
                                       @if(!auth()->guard('lead')->guest())

                                           <li class="fav" type="rental"  unit_id="{{ $unit->id }}" style="cursor: pointer;"><span>
                                                    <i class="icon-like" id="fav{{ $unit->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',$unit->id)->where('type','rental')->first()) color: #caa42d; @endif" ></i></span></li>

                                       @endif
                                       <li><a href="#one" class="share_expender" data-toggle="collapse"><i class="icon-share3"></i></a></li>
                                   </ul>
                               </div>
                               <div class="toggle_share collapse" id="proj{{ $project->id }}">
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
               @foreach(@App\ResaleUnit::where('project_id',$project_dev->id)->get() as $unit)
                       <div class="col-sm-4">
                       <div class="property_item heading_space">
                           <div class="image" style="height: 200px">
                               <a href="{{ url('resale/'.$unit->id) }}"><img style="height: 200px" src="{{ url('uploads/'.$unit->cover) }}" alt="latest property" class="img-responsive"></a>
                               <div class="price clearfix">
                                   <span class="tag pull-right">{{ $unit->price }} {{ __('admin.egp') }}</span>
                               </div>
                               <span class="tag_l">{{ __('admin.rental') }}</span>
                           </div>
                           <div class="proerty_content">
                               <div class="proerty_text">
                                   <h3 class="captlize"><a href="#.">{{ $unit->{app()->getLocale().'_title'} }}</a></h3>
                                   <p>{{ @App\Location::find($unit->location)->{app()->getLocale().'_name'} }}</p>
                               </div>
                               <div class="property_meta transparent">
                                   @foreach(@App\Phase::where('project_id',$project->id)->get() as $phase)
                                       @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                           @foreach(@App\Facility::where('id',$fac->facility_id)->get() as $icon)
                                               <span><img src="{{ url('uploads/'.@App\Icon::where('id',$icon->icon)->first()->icon) }}" width="20px">{{ $icon->{app()->getLocale().'_name'} }}</span>
                                           @endforeach
                                       @endforeach
                                   @endforeach
                               </div>

                               <div class="favroute clearfix">
                                   <p class="pull-md-left"><i class="icon-calendar2"></i> @php($now = \Carbon\Carbon::now())
                                       @if($now->diffInMonths( $unit->created_at)>0){{ $now->diffInMonths( $unit->created_at).' '.__('admin.month') }}
                                       @endif
                                       @if($now->diffInDays( $unit->created_at)>=10)
                                           {{ $now->diffInDays( $unit->created_at).' '.__('admin.days') }}
                                       @elseif($now->diffInDays( $unit->created_at)>0 && $now->diffInDays( $unit->created_at)<10)
                                           {{ $now->diffInDays( $unit->created_at).' '.__('admin.day') }}
                                       @elseif($now->diffInDays( $unit->created_at)==0)
                                           {{ __('admin.today') }}
                                       @endif </p>
                                   <ul class="pull-right">
                                       @if(!auth()->guard('lead')->guest())

                                           <li class="fav" type="resale"  unit_id="{{ $unit->id }}" style="cursor: pointer;"><span>
                                                    <i class="icon-like" id="fav{{ $unit->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',$unit->id)->where('type','resale')->first()) color: #caa42d; @endif" ></i></span></li>

                                       @endif
                                       <li><a href="#one" class="share_expender" data-toggle="collapse"><i class="icon-share3"></i></a></li>
                                   </ul>
                               </div>
                               <div class="toggle_share collapse" id="proj{{ $project->id }}">
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
           @endforeach

            </div>

        </div>
    </section>
    <!-- Favorite Properties End -->
@endsection
@section('js')
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
            })
        </script>
    @endif
@endsection