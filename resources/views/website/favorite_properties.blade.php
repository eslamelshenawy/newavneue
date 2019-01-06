@extends('website/index')
@section('content')
    <!-- Favorite Properties  -->
    <section id="property" class="padding_top listing1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul class="f-p-links margin_bottom">
                        <li><a href="{{ url('profile') }}" ><i class="icon-icons230"></i>{{ __('admin.profile') }}</a></li>
                        {{--<li><a href="{{ url('my_properties') }}"><i class="icon-icons215"></i> My Properties</a></li>--}}
                        <li><a href="{{ url('add_properties') }}"><i class="icon-icons215"></i> {{ __('admin.submit_property') }}</a></li>
                        <li><a href="{{ url('favourite_properties') }}" class="active"><i class="icon-icons43"></i> {{ __('admin.favorites') }}</a></li>
                        <li><a href="login.html"><i class="icon-lock-open3"></i>{{ __('admin.logout') }}</a></li>
                    </ul>
                    <h2 class="text-uppercase">{{ __('admin.My favorite Properties') }}</h2>
                    {{--<p class="heading_space">We have Properties in these Areas View a list of Featured Properties.</p>--}}
                </div>
            </div>
            <div class="row">
                @foreach($favorites as $favorite)
                    @php(@$unit = '')
                    @if($favorite->type == 'project')
                        @php(@$unit = @App\Project::find($favorite->unit_id))
                        <div class="col-sm-4">
                            <div class="property_item heading_space">
                                <div class="image">
                                    <a href="{{ url('project/'.slug(@$unit->{app()->getLocale().'_name'}).'-'.@$unit->id) }}"><img src="{{ url('uploads/'.@$unit->cover) }}" alt="{{ @$unit->{app()->getLocale().'_name'} }}" class="img-responsive"></a>
                                    <div class="price clearfix">
                                        <span class="tag pull-right">{{ $unit->meter_price }} {{ __('admin.per_meter') }}</span>
                                    </div>
                                    <span class="tag_t">{{ __('admin.new_homes') }}</span>
                                </div>
                                <div class="proerty_content">
                                    <div class="proerty_text">
                                        <h3 class="captlize"><a href="#.">{{ @$unit->{app()->getLocale().'_name'} }}</a></h3>
                                        <p>{{ @App\Location::find(@$unit->location_id)->{app()->getLocale().'_name'} }}</p>
                                    </div>
                                    @if(!auth()->guard('lead')->guest())
                                        <div class="fav like-btn-box" type="project"  unit_id="{{ @$unit->id }}" style="cursor: pointer;"><span>
                                                    <i class="icon-like" id="fav{{ @$unit->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',@$unit->id)->first()) color: #caa42d; @endif" ></i></span></div>

                                    @endif
                                    <div class="property_meta transparent">

                                        @foreach(@App\Phase::where('project_id',@$unit->id)->get() as $phase)
                                            @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                                @php($facility = @App\Facility::find($fac->id))
                                                <span class="col-sm-4"><img src="{{url('uploads/'.@App\Icon::find($facility->icon)->icon )}}" width="25px">
                                                    {{ @App\Facility::find($fac->id)->{app()->getLocale().'_name'} }}
                                                    </span>
                                            @endforeach
                                        @endforeach
                                    </div>


                                </div>
                            </div>
                        </div>
                    @elseif($favorite->type == 'resale')
                        @php(@$unit = @App\ResaleUnit::find($favorite->unit_id))
                        <div class="col-sm-4">
                            <div class="property_item heading_space">
                                <div class="image">
                                    <a href="{{ url('resale/'.@$unit->id) }}"><img src="{{ url(@$unit->watermarked_image) }}" alt="{{ @$unit->{app()->getLocale().'_name'} }}" class="img-responsive"></a>
                                    <div class="price clearfix">
                                        <span class="tag pull-right">{{ @$unit->price }} {{ __('admin.egp') }}</span>
                                    </div>
                                    <span class="tag_t">{{ __('admin.resale') }}</span>
                                </div>
                                <div class="proerty_content">
                                    <div class="proerty_text">
                                        <h3 class="captlize"><a href="{{ url('resale/'.@$unit->id) }}">{{ @$unit->{app()->getLocale().'_title'} }}</a></h3>
                                        <p>{{ @App\Location::find(@$unit->location_id)->{app()->getLocale().'_name'} }}</p>
                                    </div>
                                    @if(!auth()->guard('lead')->guest())

                                        <div class="fav like-btn-box" type="resale"  unit_id="{{ @$unit->id }}" style="cursor: pointer"><span>
                                                    <i class="icon-like" id="fav{{ @$unit->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',@$unit->id)->first()) color: #caa42d; @endif"></i></span></div>

                                    @endif
                                    <div class="property_meta transparent">

                                        @foreach(@App\Phase::where('project_id',@$unit->id)->get() as $phase)
                                            @foreach(@App\Phase_Facilities::where('phase_id',$phase->id)->get() as $fac)
                                                @php($facility = @App\Facility::find($fac->id))
                                                <span class="col-sm-4"><img src="{{url('uploads/'.@App\Icon::find($facility->icon)->icon )}}" width="25px">
                                                    {{ @App\Facility::find($fac->id)->{app()->getLocale().'_name'} }}
                                                    </span>

                                            @endforeach
                                        @endforeach
                                    </div>


                                </div>
                            </div>
                        </div>
                    @elseif($favorite->type == 'rental')
                        @php(@$unit = @App\RentalUnit::find($favorite->unit_id))
                        <div class="col-sm-4">
                            <div class="property_item heading_space">
                                <div class="image">
                                    <a href="{{ url('rental/'.@$unit->id) }}"><img src="{{ url('uploads/'.@$unit->image) }}" alt="{{ @$unit->{app()->getLocale().'_title'} }}" class="img-responsive"></a>
                                    <div class="price clearfix">
                                        <span class="tag pull-right">{{@$unit->rent }} {{ __('admin.egp') }}</span>
                                    </div>
                                    <span class="tag_t">{{ __('admin.rental') }}</span>
                                </div>
                                <div class="proerty_content">
                                    <div class="proerty_text">
                                        <h3 class="captlize"><a href="#.">{{ @$unit->{app()->getLocale().'_title'} }}</a></h3>
                                        <p>{{ @App\Location::find(@$unit->location_id)->{app()->getLocale().'_name'} }}</p>
                                    </div>
                                    @if(!auth()->guard('lead')->guest())

                                        <div class="fav like-btn-box" type="rental"  unit_id="{{ @$unit->id }}" style="cursor: pointer"><span>
                                                    <i class="icon-like" id="fav{{ @$unit->id }}" style="@if(@App\Favorite::where('lead_id',@auth()->guard('lead')->user()->id)->where('unit_id',@$unit->id)->first()) color: #caa42d; @endif"></i></span></div>

                                    @endif
                                    <div class="property_meta transparent">

                                       
                                    </div>

                                </div>
                            </div>

                        </div>
                    @endif

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
            });
        </script>
    @endif
@endsection
