@extends('admin.index')

@section('content')
    @php
        if($show->agent_id > 0) {
            $agent = @App\User::find($show->agent_id);
        }
        if($show->commercial_agent_id){
            $commercial_agent = @App\User::find($show->commercial_agent_id);
        }
    @endphp
    <div id="mail" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 90%">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.email') }}</h4>
                </div>
                <div class="modal-body text-center" id="mailBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat"
                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                </div>
            </div>

        </div>
    </div>
    <div id="compose" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 90%">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.email') }}</h4>
                </div>
                <form action="{{url(adminPath().'/mail_post')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body text-center">

                        {{ csrf_field() }}
                        <input type="hidden" name="lead_id" value="{{ $show->email }}">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject"
                                   placeholder="{{ __('admin.subject') }}">
                        </div>
                        <br/>
                        <br/>
                        <div class="col-md-12">
                            <textarea name="message" id="editor"></textarea>
                        </div>
                        <br/>
                        <br/>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <br/>
                            <button type="button" class="btn btn-default btn-flat"
                                    data-dismiss="modal">{{ trans('admin.close') }}</button>
                            <button type="submit" class="btn btn-success btn-flat">{{ trans('admin.send') }}</button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
    <div id="addFile" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                </div>
                <form action="{{ url(adminPath().'/add_doc') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $show->id }}" name="lead_id">
                        <input class="form-control" type="text" name="title" placeholder="{{ __('admin.title') }}">
                        <br/>
                        <input class="form-control" type="file" name="file" placeholder="{{ __('admin.file') }}">
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-default btn-flat"
                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                        <button type="submit" class="btn btn-success btn-flat">{{ trans('admin.add') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            @if(file_exists(url('uploads/'.$show->image)))
                <img src="{{ url('uploads/'.$show->image) }}" class="img-circle lead_image" alt="{{ __('admin.lead') }}"
                     width="130px"
                     height="130px">
            @else
            <div style="border-radius: 50px; border:3px solid #666;height: 80px;">
                <h3 style="text-align: center;">{{ $show->first_name[0] }}.{{  $show->last_name[0] }}.</h3>
            </div>
            @endif
        </div>
        <div class="col-md-6 head_margin_top">
            <div class="col-xs-6">
                <div class="col-xs-12 l-m-b">
                <span class="lead_name_span font-18">
                    <i class="fa fa-user-o" aria-hidden="true"></i>
                    <span id="old_first_name">{{ $show->first_name }}</span>
                    <span id="old_middle_name">{{  $show->middle_name }}</span>
                    <span id="old_last_name">{{  $show->last_name}}</span>
                    <a href="#"><i class="" aria-hidden="true"></i></a>
                </span>
                </div>
                <div class="col-xs-12 font-18">@if($show->email)@ <a href="mailto:{{ $show->email }}">{{ $show->email }}</a>@endif</div>
            </div>
            <div class="col-xs-6">
                @if(@\App\Title::find($contact->title_id)->name)
                <div class="col-xs-12 l-m-b font-18">
                    {{ trans('admin.job_title') }}:{{ @\App\Title::find($contact->title_id)->name }}
                    <a href="#"><i class="" aria-hidden="true"></i></a>
                </div>
                @endif
                @if($show->phone)
                <div class="col-xs-12 font-18">
                    {{ trans('admin.phone') }} :{{ $show->phone }}
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-2 lead_address font-18">
            @if($show->address)
            <div class="">
                <div class="l-m-b font-18">
                    {{ trans('admin.address') }} :{{ $show->address }}<a href="#"><i class=""
                                                                                     aria-hidden="true"></i></a>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-3">
            @if(isset($agent))
                <div class="col-md-12">
                    <a href="{{ url(adminPath().'/agent/'.$agent->id) }}" >
                        <img src="{{ url('uploads/'.$agent->image) }}" class="img-circle lead_image"
                             alt="{{ __('admin.lead') }}" width="70px"
                             height="70px" style="z-index: 4;position: relative;">
                        <span class="agent-name">{{ $agent->name }}</span>

                    </a>
                    @php $action = DB::table('lead_actions')->where('lead_id',$show->id)->where('user_id',$agent->id)->first(); @endphp<br>
                    <div class="col-xs-12">
                        <i class="fa fa-circle" aria-hidden="true" style="@if(DB::table('lead_actions')->where('user_id',$show->id)->count() > 0) color:green;@else color:red @endif"></i>
                        <span>{{ @$action->type }}   @if($action){{ date('d-M-y h:i:s',@$action->time) }}@else no action yet @endif  </span>
                        <span class="pull-right"  data-toggle="modal" data-target="#resAction"> {{ __('admin.all') }} </span>
                    </div>
                    <div class="modal fade" id="resAction" tabindex="-1" role="dialog" aria-labelledby="resActionLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('admin.actions') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @foreach(DB::table('lead_actions')->where('lead_id',$show->id)->where('user_id',$agent->id)->get() as $action)
                                        <div class="row">
                                            <span class="col-xs-6 text-center">{{ $action->type }}</span>   <span class="col-xs-6 text-center">{{ date('d-M-y h:i:s',@$action->time) }}</span>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                    @if(isset($commercial_agent))
                        <div class="col-md-12">
                            <a href="{{ url(adminPath().'/agent/'.$commercial_agent->id) }}">
                                <img src="{{ url('uploads/'.$commercial_agent->image) }}" class="img-circle lead_image"
                                     alt="{{ __('admin.lead') }}" width="70px"
                                     height="70px" style="z-index: 4;position: relative;">
                                <span class="agent-name">{{ $commercial_agent->name }}</span>
                            </a>
                            @php $action = DB::table('lead_actions')->where('lead_id',$show->id)->where('user_id',$commercial_agent)->first(); @endphp<br>
                            <i class="fa fa-circle" aria-hidden="true" style="@if(DB::table('lead_actions')->where('user_id',$show->id)->count() > 0) color:green;@else color:red @endif"></i>
                            <span>{{ @$action->type }} @if($action){{ date('d-M-y h:i:s',@$action->time) }}@else no action yet @endif </span>
                            <span class="pull-right"  data-toggle="modal" data-target="#comAction"> {{ __('admin.all') }} </span>
                        </div>
                        <div class="modal fade" id="comAction" tabindex="-1" role="dialog" aria-labelledby="comActionLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ __('admin.actions') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach(DB::table('lead_actions')->where('lead_id',$show->id)->where('user_id',$commercial_agent->id)->get() as $action)
                                            <div class="row">
                                                <span class="col-xs-6 text-center">{{ $action->type }}</span>   <span class="col-xs-6 text-center">{{ date('d-M-y h:i:s',@$action->time) }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
        </div>

    <div class="row head_margin_top" style="margin-top: 160px;">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.more_information') }}</h3>
                        <a href="#" class="pull-right" style="color: rgb(200, 166, 48)" data-toggle="modal"
                           data-target="#sendCIL">
                            {{ trans('admin.send_cil') }}</a>
                        <div id="sendCIL" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.send_cil') }}</h4>
                                    </div>
                                    {!! Form::open(['method'=>'post','url'=>adminPath().'/send_cil']) !!}
                                    <div class="modal-body">
                                        <input name="lead_id" type="hidden" value="{{ $show->id }}">
                                        <div class="col-md-6">
                                            <select required count="0" class="form-control select2 cilDeveloper"
                                                    style="width: 100%"
                                                    name="developers[]"
                                                    data-placeholder="{{ trans('admin.developer') }}">
                                                <option></option>
                                                @foreach(@\App\Developer::all() as $developer)
                                                    <option value="{{ $developer->id }}">{{ $developer->{app()->getLocale().'_name'} }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <select count="0" class="form-control select2" id="cilProject0"
                                                    style="width: 100%"
                                                    name="projects[]"
                                                    data-placeholder="{{ trans('admin.project') }}">
                                                <option></option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <a type="button" class="fa fa-plus"
                                               style="font-size: 1.7em; cursor: pointer"
                                               id="CILBtn"></a>
                                        </div>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <span id="addCIL"></span>
                                        <div class="col-md-12">
                                            <select style="width: 100%" class="form-control select2" name="file"
                                                    data-placeholder="{{ __('admin.file') }}">
                                                <option></option>
                                                @foreach(@\App\LeadDocument::where('lead_id',$show->id)->get() as $doc)
                                                    <option value="{{ $doc->id }}">{{ $doc->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <br/>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">{{ trans('admin.send') }}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.first_name') }} [EN] :
                                    <span id="first_name" class="">
                                    {{ $show->first_name }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="first_name"
                                       id="first_name_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="first_name"
                                       id="first_name_save"></i>
                                    <span id="first_name_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->first_name }}"
                                           id="first_name_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.middle_name') }} [EN] :
                                <span id="middle_name" class="">
                                {{ $show->middle_name }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="middle_name"
                                   id="middle_name_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="middle_name"
                                   id="middle_name_save"></i>
                                <span id="middle_name_input" class="hidden">
                                <input type="text" class="update_input" value="{{ $show->middle_name }}"
                                       id="middle_name_update">
                            </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.last_name') }} [EN] :
                                    <span id="last_name" class="">
                                    {{ $show->last_name }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="last_name"
                                       id="last_name_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="last_name"
                                       id="last_name_save"></i>
                                    <span id="last_name_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->last_name }}"
                                           id="last_name_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.ar_first_name') }} :
                                <span id="ar_first_name" class="">
                                    {{ $show->ar_first_name }}
                                </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="ar_first_name"
                                   id="ar_first_name_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="ar_first_name"
                                   id="ar_first_name_save"></i>
                                <span id="ar_first_name_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->ar_first_name }}"
                                           id="ar_first_name_update">
                                </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.ar_middle_name') }} :
                                    <span id="ar_middle_name" class="">
                                    {{ $show->ar_middle_name }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="ar_middle_name"
                                       id="ar_middle_name_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="ar_middle_name"
                                       id="ar_middle_name_save"></i>
                                    <span id="ar_middle_name_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->ar_middle_name }}"
                                           id="ar_middle_name_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.ar_last_name') }} :
                                <span id="ar_last_name" class="">
                                {{ $show->ar_last_name }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="ar_last_name"
                                   id="ar_last_name_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="ar_last_name"
                                   id="ar_last_name_save"></i>
                                <span id="ar_last_name_input" class="hidden">
                                <input type="text" class="update_input" value="{{ $show->ar_last_name }}"
                                       id="ar_last_name_update">
                            </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.nationality') }} :
                                    <span id="nationality" class="">
                                    {{ @\App\Country::find($show->nationality)->name }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="nationality"
                                       id="nationality_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="nationality"
                                       id="nationality_save"></i>
                                    <span id="nationality_input" class="hidden">
                                    <select class="select2 form-control update_input" id="nationality_update"
                                            data-placeholder="{{ __('admin.nationality') }}">
                                        <option></option>
                                        @foreach(@\App\Country::get() as $country)
                                            <option value="{{ $country->id }}"
                                                    @if($show->nationality == $country->id) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.religion') }} :
                                <span id="religion" class="">
                                {{ __('admin.'.$show->religion) }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="religion"
                                   id="religion_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="religion"
                                   id="religion_save"></i>
                                <span id="religion_input" class="hidden">
                                <select class="select2 form-control update_input" id="religion_update"
                                        data-placeholder="{{ __('admin.religion') }}">
                                    <option></option>
                                    <option @if($show->religion == 'muslim') selected
                                            @endif value="muslim">{{ trans('admin.muslim') }}</option>
                                    <option @if($show->religion == 'christian') selected
                                            @endif value="christian">{{ trans('admin.christian') }}</option>
                                    <option @if($show->religion == 'jewish') selected
                                            @endif value="jewish">{{ trans('admin.jewish') }}</option>
                                    <option @if($show->religion == 'other') selected
                                            @endif value="other">{{ trans('admin.other') }}</option>
                                </select>
                            </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.country') }} :
                                    <span id="country_id" class="">
                                    {{ @\App\Country::find($show->country_id)->name }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="country_id"
                                       id="country_id_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="country_id"
                                       id="country_id_save"></i>
                                    <span id="country_id_input" class="hidden">
                                    <select class="select2 form-control update_input" id="country_id_update"
                                            data-placeholder="{{ __('admin.country') }}">
                                        <option></option>
                                        @foreach(@\App\Country::get() as $country)
                                            <option value="{{ $country->id }}"
                                                    @if($show->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.city') }} :
                                <span id="city_id" class="">
                                {{ @\App\City::find($show->city_id)->name }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="city_id"
                                   id="city_id_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="city_id"
                                   id="city_id_save"></i>
                                <span id="city_id_input" class="hidden">
                                <select class="select2 form-control update_input" id="city_id_update"
                                        data-placeholder="{{ __('admin.city') }}">
                                    <option></option>
                                    @foreach(@\App\City::where('country_id',$show->country_id)->get() as $city)
                                        <option value="{{ $city->id }}"
                                                @if($show->city_id == $city->id) selected @endif>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.birth_date') }} :
                                    <span id="birth_date" class="">
                                    {{ date('Y/m/d',$show->birth_date) }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="birth_date"
                                       id="birth_date_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="birth_date"
                                       id="birth_date_save"></i>
                                    <span id="birth_date_input" class="hidden">
                                    <input type="text" class="update_input datepicker1"
                                           value="{{ date('Y/m/d',$show->birth_date) }}"
                                           id="birth_date_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.industry') }} :
                                <span id="industry_id" class="">
                                {{ @\App\Industry::find($show->industry_id)->name }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="industry_id"
                                   id="industry_id_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="industry_id"
                                   id="industry_id_save"></i>
                                <span id="industry_id_input" class="hidden">
                                <select class="select2 form-control update_input" id="industry_id_update"
                                        data-placeholder="{{ __('admin.industry') }}">
                                    <option></option>
                                    @foreach(@\App\Industry::all() as $industry)
                                        <option value="{{ $industry->id }}"
                                                @if($show->industry_id == $industry->id) selected @endif>{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.company') }} :
                                    <span id="company" class="">
                                    {{ $show->company }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="company"
                                       id="company_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="company"
                                       id="company_save"></i>
                                    <span id="company_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->company }}"
                                           id="company_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.school') }} :
                                <span id="school" class="">
                                {{ $show->school }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="school"
                                   id="school_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="school"
                                   id="school_save"></i>
                                <span id="school_input" class="hidden">
                                <input type="text" class="update_input" value="{{ $show->school }}"
                                       id="school_update">
                            </span>
                            </div>
                            <div class="col-md-6 border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.club') }} :
                                    <span id="club" class="">
                                    {{ $show->club }}
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="club"
                                       id="club_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="club"
                                       id="club_save"></i>
                                    <span id="club_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->club }}"
                                           id="club_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.id_number') }} :
                                <span id="id_number" class="">
                                {{ $show->id_number }}
                            </span>
                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="id_number"
                                   id="id_number_btn"></i>
                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                   style="font-size: 1.2em; cursor: pointer" type="id_number"
                                   id="id_number_save"></i>
                                <span id="id_number_input" class="hidden">
                                <input type="text" class="update_input" value="{{ $show->id_number }}"
                                       id="id_number_update">
                            </span>
                            </div>
                            <div class="col-md-6  border_right">
                                <div class="head_margin_top">
                                    {{ trans('admin.facebook') }} :
                                    <span id="facebook" class="">
                                    <a href="{{ 'https://www.facebook.com/'.$show->facebook }}"
                                       target="_blank"><b><i
                                                    class="fa fa-facebook" aria-hidden="true"></i></b></a>
                                </span>
                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="facebook"
                                       id="facebook_btn"></i>
                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                       style="font-size: 1.2em; cursor: pointer" type="facebook"
                                       id="facebook_save"></i>
                                    <span id="facebook_input" class="hidden">
                                    <input type="text" class="update_input" value="{{ $show->facebook }}"
                                           id="facebook_update">
                                </span>
                                </div>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.notes') }}
                                :
                                <span id="newComment">{{ $show->notes }}</span>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.reference') }}
                                :
                                <span id="newComment">{{ $show->reference }}</span>
                            </div>
                            <div class="col-md-6 head_margin_top">
                                {{ trans('admin.lead_source') }}
                                :
                                <span id="newComment">{{ @\App\LeadSource::find($show->lead_source_id)->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="actions">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.actions') }}</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="accordion" class="panel-group">

                            <div class="panel panel-default">
                                <div id='headingCall' class="actionTab panel-title">
                                <label>{{ trans('admin.add_call') }}<button type="button" class="btn btnCol" data-toggle="collapse" data-target="#add_call" aria-expanded="true" aria-controls="add_call">+</button></label>
                                </div>
                                <div class="collapse" id="add_call" aria-labelledby="headingCall" data-parent="#accordion">
                                    {!! Form::open(['url' => adminPath().'/calls']) !!}
                                    <input type="hidden" name="lead_id" value="{{ $show->id }}">
                                    <div class="form-group cc-selector @if($errors->has('phone_in_out')) has-error @endif">
                                        <label>{{ trans('admin.phone_in_out') }}</label>
                                        <br><br>
                                        <input checked="checked" sty id="inGoing" type="radio" name="phone_in_out" value="in" />
                                        <label class="drinkcard-cc inGoing" for="inGoing"></label>
                                        <input id="outGoing" type="radio" name="phone_in_out" value="out" />
                                        <label class="drinkcard-cc outGoing" for="outGoing"></label>
                                    </div>
                                    <div class="form-group @if($errors->has('contact_id')) has-error @endif" id="contact">
                                        <label>{{ trans('admin.contact') }}</label>
                                        <select name="contact_id" class="form-control select2" id="contact_id" style="width: 100%"
                                                data-placeholder="{{ trans('admin.contact') }}">
                                            <option value="0">{{ trans('admin.lead') }}</option>
                                            @foreach(@\App\Contact::where('lead_id', $show->id)->get() as $contact)
                                                <option value="{{ $contact->id }}">
                                                    {{ $contact->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                                        <label>{{ trans('admin.phone') }}</label>
                                        <select name="phone" class="form-control select2" id="phone" style="width: 100%" data-placeholder="{{ trans('admin.phone') }}">
                                        <option value="{{ @$show->phone }}">{{ @$show->phone }}</option>
                                            @if(@$lead->other_phones != null)
                                                @php($allPhones = json_decode(@$lead->other_phones))
                                                @foreach($allPhones as $phones)
                                                    @foreach($phones as $phone => $v)
                                                        <option value="{{ $phone }}">
                                                    {{ $phone }}
                                                </option>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group @if($errors->has('call_status_id')) has-error @endif">
                                        <label>{{ trans('admin.call_status') }}</label>
                                        <select class="form-control select2" name="call_status_id" id="callStatus" data-placeholder="{{ __('admin.call_status') }}" style="width: 100%">
                                            <option></option>
                                            @foreach(@\App\CallStatus::get() as $status)
                                                <option value="{{ $status->id }}" next="{{ $status->has_next_action }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <span id="nextAction"></span>

                                    <div class="form-group @if($errors->has('duration')) has-error @endif">
                                        <label>{{ trans('admin.duration') }}</label>
                                        {!! Form::number('duration','',['class' => 'form-control', 'placeholder' => trans('admin.duration')]) !!}
                                    </div>


                                    <div class="form-group @if($errors->has('date')) has-error @endif">
                                        <label>{{ trans('admin.date') }}</label>
                                        <div class="input-group">
                                            {!! Form::text('date','',['class' => 'form-control datepicker1', 'placeholder' => trans('admin.date'),'readonly'=>'']) !!}
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('probability')) has-error @endif">
                                        <label>{{ trans('admin.probability') }}</label>
                                        <select class="form-control select2" name="probability" style="width: 100%" data-placeholder="{{ __('admin.probability') }}">
                                            <option></option>
                                            <option value="highest">{{ __('admin.highest') }}</option>
                                            <option value="high">{{ __('admin.high') }}</option>
                                            <option value="normal">{{ __('admin.normal') }}</option>
                                            <option value="low">{{ __('admin.low') }}</option>
                                            <option value="lowest">{{ __('admin.lowest') }}</option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>{{ trans('admin.budget') }}</label>
                                        <div class="input-group">
                                            {!! Form::number('budget','',['class' => 'form-control', 'placeholder' => trans('admin.budget')]) !!}
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="form-group @if($errors->has('projects')) has-error @endif">
                                        <label>{{ trans('admin.projects') }}</label>
                                        <select multiple class="form-control select2" name="projects[]" style="width: 100%"
                                                data-placeholder="{{ trans('admin.projects') }}">
                                            <option></option>
                                            @foreach(@\App\Project::get() as $project)
                                                <option value="{{ $project->id }}">{{ $project->{app()->getLocale().'_name'} }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group @if($errors->has('description')) has-error @endif">
                                        <label>{{ trans('admin.description') }}</label>
                                        {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-success btn-flat"
                                                id="addAction">{{ trans('admin.next_action') }}</button>
                                        <button type="button" class="btn btn-danger btn-flat hidden"
                                                id="removeAction">{{ trans('admin.remove') . ' ' . trans('admin.next_action') }}</button>
                                    </div>
                                    <br/>
                                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="panel panel-default">
                            <div id='headingMeet' class="actionTab panel-title">
                                     <label>{{ trans('admin.add_meeting') }}<button type="button" class="btn btnCol" data-toggle="collapse" data-target="#add_meeting" aria-expanded="false" aria-controls="add_meeting">+</button></label>
                            </div>
                                <div class="collapse" id="add_meeting" aria-labelledby="headingMeet" data-parent="#accordion">
                                    {!! Form::open(['url' => adminPath().'/meetings']) !!}
                                    <input type="hidden" name="lead_id" value="{{ $show->id }}">

                                    <div class="form-group @if($errors->has('contact_id')) has-error @endif" id="contact">
                                        <label>{{ trans('admin.contact') }}</label>
                                        <select name="contact_id" class="form-control select2" id="contact_id" style="width: 100%"
                                                data-placeholder="{{ trans('admin.contact') }}">
                                            <option value="0">{{ trans('admin.lead') }}</option>
                                            @foreach(@\App\Contact::where('lead_id', $show->id)->get() as $contact)
                                                <option value="{{ $contact->id }}">
                                                    {{ $contact->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group @if($errors->has('meeting_status_id')) has-error @endif">
                                        <label>{{ trans('admin.meeting_status') }}</label>
                                        <select class="form-control select2" name="meeting_status_id" id="meetingStatus" data-placeholder="{{ __('admin.meeting_status') }}" style="width: 100%">
                                            <option></option>
                                            @foreach(@\App\MeetingStatus::get() as $status)
                                                <option value="{{ $status->id }}" next="{{ $status->has_next_action }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <span id="MnextAction"></span>

                                    <div class="form-group @if($errors->has('duration')) has-error @endif">
                                        <label>{{ trans('admin.duration') }}</label>
                                        {!! Form::number('duration','',['class' => 'form-control', 'placeholder' => trans('admin.duration')]) !!}
                                    </div>


                                    <div class="form-group @if($errors->has('date')) has-error @endif">
                                        <label>{{ trans('admin.date') }}</label>
                                        <div class="input-group">
                                            {!! Form::text('date','',['class' => 'form-control datepicker', 'placeholder' => trans('admin.date'),'readonly'=>'']) !!}
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group @if($errors->has('time')) has-error @endif">
                                        <label>{{ trans('admin.time') }}</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            {!! Form::text('time','',['class' => 'form-control timepicker', 'placeholder' => trans('admin.time'),'readonly'=>'']) !!}
                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group @if($errors->has('location')) has-error @endif">
                                        <label>{{ trans('admin.location') }}</label>
                                        <div class="input-group">
                                            {!! Form::text('location','',['class' => 'form-control', 'placeholder' => trans('admin.location')]) !!}
                                            <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group @if($errors->has('probability')) has-error @endif">
                                        <label>{{ trans('admin.probability') }}</label>
                                        <select class="form-control select2" name="probability" style="width: 100%" data-placeholder="{{ __('admin.probability') }}">
                                            <option></option>
                                            <option value="highest">{{ __('admin.highest') }}</option>
                                            <option value="high">{{ __('admin.high') }}</option>
                                            <option value="normal">{{ __('admin.normal') }}</option>
                                            <option value="low">{{ __('admin.low') }}</option>
                                            <option value="lowest">{{ __('admin.lowest') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ trans('admin.budget') }}</label>
                                        <div class="input-group">
                                            {!! Form::number('budget','',['class' => 'form-control', 'placeholder' => trans('admin.budget')]) !!}
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group @if($errors->has('projects')) has-error @endif">
                                        <label>{{ trans('admin.projects') }}</label>
                                        <select multiple class="form-control select2" name="projects[]" style="width: 100%"
                                                data-placeholder="{{ trans('admin.projects') }}">
                                            <option></option>
                                            @foreach(@\App\Project::get() as $project)
                                                <option value="{{ $project->id }}">{{ $project->{app()->getLocale().'_name'} }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group @if($errors->has('description')) has-error @endif">
                                        <label>{{ trans('admin.description') }}</label>
                                        {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-success btn-flat"
                                                id="MaddAction">{{ trans('admin.next_action') }}</button>
                                        <button type="button" class="btn btn-danger btn-flat hidden"
                                                id="MremoveAction">{{ trans('admin.remove') . ' ' . trans('admin.next_action') }}</button>
                                    </div>
                                    <br/>
                                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="panel panel-default">
                            <div id='headingRequest' class="actionTab panel-title">
                                     <label>{{ trans('admin.add_request') }}<button type="button" class="btn btnCol" data-toggle="collapse" data-target="#add_request" aria-expanded="false" aria-controls="add_request">+</button></label>
                            </div>
                                <div class="collapse" id="add_request" aria-labelledby="headingRequest" data-parent="#accordion">
                                    <div class="">
                                        <form action="{{ url(adminPath().'/requests') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $show->id }}" name="lead">
                                            
                                                
                                            <div class="form-group {{ $errors->has('unit_type') ? 'has-error' : '' }} col-md-12">
                                                {!! Form::label(trans('admin.buyer_seller')) !!}
                                                <select class="select2 form-control"  id="buyer_seller" name="buyer_seller" style="width: 100%"
                                                        data-placeholder="{{ trans('admin.type') }}">
                                                    <option></option>
                                                    <option value="buyer">{{ trans('admin.buyer') }}</option>
                                                    <option value="seller">{{ trans('admin.seller') }}</option>

                                                </select>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }} col-md-3">
                                                    {!! Form::label(trans('admin.location')) !!}
                                                    <select class="select2 form-control" id="location" name="location"
                                                            style="width: 100%"
                                                            data-placeholder="{{ trans('admin.location') }}">
                                                        <option></option>
                                                        @foreach(@\App\Location::all() as $location)
                                                            <option value="{{ $location->id }}">{{ $location->{app()->getLocale().'_name'} }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group {{ $errors->has('unit_type') ? 'has-error' : '' }} col-md-3">
                                                    {!! Form::label(trans('admin.type')) !!}
                                                    <select class="select2 form-control" id="unit_type" name="unit_type"
                                                            style="width: 100%"
                                                            data-placeholder="{{ trans('admin.type') }}">
                                                        <option></option>
                                                        <option value="commercial">{{ trans('admin.commercial') }}</option>
                                                        <option value="personal">{{ trans('admin.personal') }}</option>
                                                        <option value="land">{{ trans('admin.land') }}</option>
                                                    </select>
                                                </div>

                                                <div class="form-group {{ $errors->has('unit_type_id') ? 'has-error' : '' }} col-md-3">
                                                    {!! Form::label(trans('admin.unit_type')) !!}
                                                    <select class="select2 form-control" id="unit_type_id"
                                                            name="unit_type_id"
                                                            style="width: 100%"
                                                            data-placeholder="{{ trans('admin.unit_type') }}">
                                                        <option></option>
                                                    </select>
                                                </div>

                                                <div class="form-group {{ $errors->has('request_type') ? 'has-error' : '' }} col-md-3">
                                                    {!! Form::label(trans('admin.request_type')) !!}
                                                    <select class="select2 form-control" id="request_type"
                                                            name="request_type"
                                                            style="width: 100%"
                                                            data-placeholder="{{ trans('admin.request_type') }}">
                                                        <option></option>
                                                        <option value="resale">{{ trans('admin.resale') }}</option>
                                                        <option value="rental">{{ trans('admin.rental') }}</option>
                                                        <option value="new_home">{{ trans('admin.new_home') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="resale_rental" class="hidden">
                                                <div class="form-group col-md-6 @if($errors->has('rooms_from')) has-error @endif">
                                                    <label> {{ trans('admin.rooms_from') }}</label>
                                                    <input type="number" name="rooms_from" id="rooms_from"
                                                           class="form-control"
                                                           value="{{ old('rooms_from') }}"
                                                           placeholder="{{ trans('admin.from') }}">
                                                </div>
                                                <div class="form-group col-md-6 @if($errors->has('rooms_to')) has-error @endif">
                                                    <label> {{ trans('admin.rooms_to') }}</label>
                                                    <input type="number" name="rooms_to" id="rooms_to"
                                                           class="form-control"
                                                           value="{{ old('rooms_to') }}"
                                                           placeholder="{{ trans('admin.to') }}">
                                                </div>

                                                <div class="form-group col-md-6 @if($errors->has('bathrooms_from')) has-error @endif">
                                                    <label> {{ trans('admin.bathrooms_from') }}</label>
                                                    <input type="number" name="bathrooms_from" id="bathrooms_from"
                                                           class="form-control"
                                                           value="{{ old('bathrooms_from') }}"
                                                           placeholder="{{ trans('admin.from') }}">
                                                </div>
                                                <div class="form-group col-md-6 @if($errors->has('bathrooms_to')) has-error @endif">
                                                    <label> {{ trans('admin.bathrooms_to') }}</label>
                                                    <input type="number" name="bathrooms_to" id="bathrooms_to"
                                                           class="form-control"
                                                           value="{{ old('bathrooms_to') }}"
                                                           placeholder="{{ trans('admin.to') }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 @if($errors->has('price_from')) has-error @endif">
                                                <label> {{ trans('admin.price_from') }}</label>
                                                <input type="number" name="price_from" id="price_from" class="form-control"
                                                       value="{{ old('price_from') }}"
                                                       placeholder="{{ trans('admin.from') }}">
                                            </div>
                                            <div class="form-group col-md-6 @if($errors->has('price_to')) has-error @endif">
                                                <label> {{ trans('admin.price_to') }}</label>
                                                <input type="number" name="price_to" id="price_to" class="form-control"
                                                       value="{{ old('price_to') }}"
                                                       placeholder="{{ trans('admin.to') }}">
                                            </div>

                                            <div class="form-group col-md-6 @if($errors->has('area_from')) has-error @endif">
                                                <label> {{ trans('admin.area_from') }}</label>
                                                <input type="number" name="area_from" id="area_from" class="form-control"
                                                       value="{{ old('area_from') }}"
                                                       placeholder="{{ trans('admin.from') }}">
                                            </div>
                                            <div class="form-group col-md-6 @if($errors->has('area_to')) has-error @endif">
                                                <label> {{ trans('admin.area_to') }}</label>
                                                <input type="number" name="area_to" id="area_to" class="form-control"
                                                       value="{{ old('area_to') }}"
                                                       placeholder="{{ trans('admin.to') }}">
                                            </div>
                                            {{--<div class="form-group col-md-6 @if($errors->has('price_from')) has-error @endif">--}}
                                            {{--<label> {{ trans('admin.price_from') }}</label>--}}
                                            {{--<input type="number" name="price_from" id="price_from"--}}
                                            {{--class="form-control"--}}
                                            {{--value="{{ old('price_from') }}"--}}
                                            {{--placeholder="{{ trans('admin.from') }}">--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group col-md-6 @if($errors->has('price_to')) has-error @endif">--}}
                                            {{--<label> {{ trans('admin.price_to') }}</label>--}}
                                            {{--<input type="number" name="price_to" id="price_to" class="form-control"--}}
                                            {{--value="{{ old('price_to') }}"--}}
                                            {{--placeholder="{{ trans('admin.to') }}">--}}
                                            {{--</div>--}}

                                            <div class="form-group @if($errors->has('date')) has-error @endif col-md-12">
                                                <label>{{ trans('admin.delivery_date') }}</label>
                                                <div class="input-group">
                                                    {!! Form::text('date','',['class' => 'form-control datepicker', 'placeholder' => trans('admin.delivery_date'),'readonly'=>'','id'=>'date']) !!}
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>

                                            <div class="form-group @if($errors->has('down_payment')) has-error @endif col-md-12">
                                                <label>{{ trans('admin.down_payment') }}</label>
                                                <input type="number" class="form-control" name="down_payment"
                                                       placeholder="{{ trans('admin.down_payment') }}">
                                            </div>

                                            <div class="form-group @if($errors->has('notes')) has-error @endif col-md-12">
                                                <label> {{ trans('admin.notes') }}</label>
                                                <textarea name="notes" class="form-control" value="{{ old('notes') }}"
                                                          placeholder="{!! trans('admin.notes') !!}"
                                                          rows="6"></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-success btn-flat"
                                                        id="getSuggestions">{{ trans('admin.suggestions') }}</button>
                                                <button type="submit"
                                                        class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                                            </div>
                                        </form>
                                        <span id="get_suggestions"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="beforeAction">
            <div class="box">
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#contacts" data-toggle="tab" class="padding-tap"
                                                  aria-expanded="true">{{ trans('admin.contacts') }}</a></li>
                            <li class=""><a href="#requests" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.requests') }}</a></li>
                            <li class=""><a href="#new_home" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.new') }} {{ trans('admin.home') }}</a>
                            </li>
                            <li class=""><a href="#rental" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.rental') }}</a></li>
                            <li class=""><a href="#resale" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.resale') }}</a></li>
                            <li class=""><a href="#notes" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.notes') }}</a></li>
                            <li class=""><a href="#docs" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.documents') }}</a></li>
                            <li class=""><a href="#suggestions" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.suggestions') }}</a></li>
                            <li class=""><a href="#fav" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.favourite') }}</a></li>
                            <li class=""><a href="#massage" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.massage') }}</a></li>
                            <li class=""><a href="#cils" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.cils') }}</a></li>
                            <li class=""><a href="#interests" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.interests') }}</a></li>
                            <li class=""><a href="#contracts" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.contracts') }}</a></li>
                            <li class=""><a href="#voice_notes" data-toggle="tab" class="padding-tap"
                                            aria-expanded="true">{{ trans('admin.voice_notes') }}</a></li>


                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="contacts">
                                <a data-toggle="modal" data-target="#addContact"
                                   class="btn btn-primary btn-flat fa fa-user hidden"> {{ trans('admin.add_contact') }} </a>
                                <div id="addContact" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.contact') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(['url' => adminPath().'/contacts']) !!}
                                                <div class="form-group @if($errors->has('name')) has-error @endif">
                                                    <label>{{ trans('admin.name') }}</label>
                                                    {!! Form::text('name','',['class' => 'form-control', 'placeholder' => trans('admin.name')]) !!}
                                                </div>
                                                <div class="form-group @if($errors->has('description')) has-error @endif">
                                                    <label>{{ trans('admin.description') }}</label>
                                                    {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
                                                </div>
                                                <button type="submit"
                                                        class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-flat"
                                                        data-dismiss="modal">{{ trans('admin.close') }}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.id') }}</th>
                                        <th>{{ trans('admin.name') }}</th>
                                        <th>{{ trans('admin.relation') }}</th>
                                        <th>{{ trans('admin.email') }}</th>
                                        <th>{{ trans('admin.phone') }}</th>
                                        <th>{{ trans('admin.show') }}</th>
                                        <th>{{ trans('admin.delete') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@\App\Contact::where('lead_id',$show->id)->get() as $contact)
                                        <tr>
                                            <td>{{ $contact->id }}</td>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->relation }}</td>
                                            <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                            <td>{{ $contact->phone }}</td>
                                            <td><a data-toggle="modal" data-target="#show{{ $contact->id }}"
                                                   class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                                            <td><a data-toggle="modal" data-target="#delete{{ $contact->id }}"
                                                   class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                                        </tr>
                                    </tbody>
                                    <div id="show{{ $contact->id }}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.contact') }}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-md-6">
                                                        <strong>{{ trans('admin.name') }}: </strong>{{ $contact->name }}
                                                        <br>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ trans('admin.relation') }}
                                                            : </strong>{{ $contact->relation }}
                                                        <br>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ trans('admin.phone') }}
                                                            : </strong>{{ $contact->phone }}
                                                        @php($contacSocials = json_decode($contact->social));
                                                        @if(@$contacSocials->whatsapp == 1)
                                                            <i class="fa fa-whatsapp" style="color: #34af23;"></i>
                                                        @endif
                                                        @if(@$contacSocials->viber == 1)
                                                            <img src="{{ url('viber.png') }}" height="18px">
                                                        @endif
                                                        @if(@$contacSocials->sms == 1)
                                                            <i class="fa fa-comments" style="color: #3b5998;"></i>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ trans('admin.email') }} : </strong><a
                                                                href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                                        <br>
                                                        <hr>
                                                    </div>
                                                    @php($contactEmails = json_decode($contact->other_emails))
                                                    @if($contactEmails != null)
                                                        @foreach($contactEmails as $emails)
                                                            <div class="col-md-12">
                                                                <strong>{{ trans('admin.other_emails') }} : </strong><a
                                                                        href="mailto:{{ $emails }}">{{ $emails }}</a>
                                                                <br>
                                                                <hr>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    @php($contactPhones = json_decode($contact->other_phones))
                                                    @if($contactPhones != null)
                                                        @foreach($contactPhones as $phones)
                                                            @foreach($phones as $phone => $social)
                                                                <div class="col-md-12">
                                                                    <strong>{{ trans('admin.other_phones') }}
                                                                        : </strong>{{ $phone }}
                                                                    <div class="pull-right">
                                                                        @if($social->whatsapp == 1)
                                                                            <i class="fa fa-whatsapp"
                                                                               style="color: #34af23;"></i>
                                                                        @endif
                                                                        @if($social->viber == 1)
                                                                            <img src="{{ url('viber.png') }}"
                                                                                 height="18px">
                                                                        @endif
                                                                        @if($social->sms == 1)
                                                                            <i class="fa fa-comments"
                                                                               style="color: #3b5998;"></i>
                                                                        @endif
                                                                    </div>
                                                                    <br>
                                                                    <hr>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    @endif
                                                    <div class="col-md-12">
                                                        <strong>{{ trans('admin.job_title') }}
                                                            : </strong>{{ @\App\Title::find($contact->title_id)->name }}
                                                        <br>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <strong>{{ trans('admin.nationality') }}
                                                            : </strong>{{ @\App\Country::find($contact->nationality)->name }}
                                                        <br>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div id="delete{{ $contact->id }}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.contact') }}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ trans('admin.delete') . ' ' . $contact->name }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    {!! Form::open(['method'=>'DELETE','route'=>['contacts.destroy',$contact->id]]) !!}
                                                    <button type="button" class="btn btn-default btn-flat"
                                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                    <button type="submit"
                                                            class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                </table>
                            </div>
                            <div class="tab-pane" id="requests">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.id') }}</th>
                                        <th>{{ trans('admin.lead') }}</th>
                                        <th>{{ trans('admin.unit_type') }}</th>
                                        <th>{{ trans('admin.price').' '.trans('admin.from') }}</th>
                                        <th>{{ trans('admin.price').' '.trans('admin.to') }}</th>
                                        <th>{{ trans('admin.delivery_date') }}</th>
                                        <th>{{ trans('admin.show') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(App\Request::where('lead_id',$show->id)->get() as $req)
                                        <tr>
                                            <td>{{ $req->id }}</td>
                                            <td>{{ @\App\Lead::find($req->lead_id)->first_name }}</td>
                                            <td>{{ @\App\UnitType::find($req->unit_type_id)->{app()->getLocale().'_name'} }}</td>
                                            <td>{{ $req->price_from }}</td>
                                            <td>{{ $req->price_to }}</td>
                                            <td>{{ $req->date }}</td>
                                            <td><a href="{{ url(adminPath().'/requests/'.$req->id) }}"
                                                   class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                            <div class="tab-pane" id="new_home">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.title') }}</th>
                                        <th>{{ trans('admin.start_price') }}</th>
                                        <th>{{ trans('admin.area') }}</th>
                                        <th>{{ trans('admin.project') }}</th>
                                        <th>{{ trans('admin.phase') }}</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($newHomes = @App\Property::where('lead_id',$show->id)->get())
                                    @foreach($newHomes as $newHome)
                                        @php($phase = @\App\Phase::find($newHome->phase_id))
                                        @php($project = @\App\Project::find($phase->project_id))
                                        <tr>
                                            <td>{{ $newHome->{app()->getLocale().'_name'} }}</td>
                                            <td>{{ $newHome->start_price }}</td>
                                            <td>{{ $newHome->area_from }} <i
                                                        class="fa fa-arrows-h"></i> {{ $newHome->area_to }} </td>
                                            <td>{{ $project->{app()->getLocale().'_name'} }}</td>
                                            <td>{{ $phase->{app()->getLocale().'_name'} }}</td>
                                            @php($phase = @App\Phase::find($newHome->phase_id))
                                            @php($project = @App\Project::find($phase->project_id))
                                            <td><a class="btn btn-primary btn-flat "
                                                   href="{{ url(adminPath().'/resale_units/create') }}?lead_id={{$show->id}}&ar_name={{ $newHome->ar_name }}&en_name={{ $newHome->en_name }}&ar_description{{ $newHome->ar_description }}&en_description{{ $newHome->en_description }}&unit_price={{ $newHome->start_price  }}&broker={{ $newHome->user_id }}&project={{ $project->id  }}&area={{ $newHome->area_from}}&type={{ $newHome->type}}&type_id={{ $newHome->unit_id}}&lng={{ $project->lng}}&lat={{ $project->lat}}&zoom={{ $project->zoom}}&location={{ $newHome->location_id }}}&ar_address={{ $newHome->ar_address }}&en_address={{ $newHome->en_address }}">
                                                    {{ trans('admin.convert_to') }} {{ trans('admin.resale') }}</a></td>
                                            <td><a class="btn btn-primary btn-flat "
                                                   href="{{ url(adminPath().'rental_units') }}?lead_id={{$show->id}}&ar_name={{ $newHome->ar_name }}&en_name={{ $newHome->en_name }}&ar_description{{ $newHome->ar_description }}&en_description{{ $newHome->en_description }}&unit_price={{ $newHome->start_price  }}&broker={{ $newHome->user_id }}&project={{ $project->id  }}&area={{ $newHome->area_from}}&type={{ $newHome->type}}&type_id={{ $newHome->unit_id}}&lng={{ $project->lng}}&lat={{ $project->lat}}&zoom={{ $project->zoom}}&location={{ $newHome->location_id }}&ar_address={{ $newHome->ar_address }}&en_address={{ $newHome->en_address }}">
                                                    {{ trans('admin.convert_to') }} {{ trans('admin.rental') }}</a></td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                            <div class="tab-pane" id="rental">

                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.property') }}</th>
                                        <th>{{ trans('admin.title') }}</th>
                                        <th>{{ trans('admin.status') }}</th>
                                        <th>{{ trans('admin.location') }}</th>
                                        <th>{{ trans('admin.rent') }}</th>
                                        <th>{{ trans('admin.rooms') }}</th>
                                        <th>{{ trans('admin.bathrooms') }}</th>
                                        <th>{{ trans('admin.area') }}</th>
                                        <th>{{ trans('admin.delivery_date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($rental = @App\RentalUnit::where('lead_id',$show->id)->get())
                                    @foreach($rental as $resaleUnit)
                                        <tr>
                                            <td><img src="{{ url('/uploads/'.$resaleUnit->image) }}" width="50px"></td>
                                            <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                            <td>{{ $resaleUnit->availability }}</td>
                                            <td>{{ @App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                            <td>{{ $resaleUnit->rent }}</td>
                                            <td>{{ $resaleUnit->rooms }}</td>
                                            <td>{{ $resaleUnit->bathrooms }}</td>
                                            <td>{{ $resaleUnit->area }}</td>
                                            <td>{{ $resaleUnit->delivery_date }}</td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                            <div class="tab-pane" id="resale">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.property') }}</th>
                                        <th>{{ trans('admin.title') }}</th>
                                        <th>{{ trans('admin.status') }}</th>
                                        <th>{{ trans('admin.location') }}</th>
                                        <th>{{ trans('admin.price') }}</th>
                                        <th>{{ trans('admin.rooms') }}</th>
                                        <th>{{ trans('admin.bathrooms') }}</th>
                                        <th>{{ trans('admin.area') }}</th>
                                        <th>{{ trans('admin.delivery_date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($resale = @App\ResaleUnit::where('lead_id',$show->id)->get())
                                    @foreach($resale as $resaleUnit)
                                        <tr>
                                            <td><img src="{{ url('uploads/'.$resaleUnit->image) }}" width="75 px"></td>
                                            <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                            <td>{{ trans('admin.'.$resaleUnit->availability) }}</td>
                                            <td>{{ @\App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                            <td>{{ $resaleUnit->total }}</td>
                                            <td>{{ $resaleUnit->rooms }}</td>
                                            <td>{{ $resaleUnit->bathrooms }}</td>
                                            <td>{{ $resaleUnit->area }}</td>
                                            <td>{{ $resaleUnit->delivery_date }}</td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                            <div class="tab-pane" id="notes">
                                <div id="allNotes">
                                    @foreach(@\App\LeadNote::where('lead_id',$show->id)->orderBy('id')->get() as $note)
                                        <div class="well col-md-12">
                                            <div class="col-md-2 text-center">
                                                <img height="50" width="50"
                                                     style="border-radius: 50px; border: 2px solid #caa42d"
                                                     src="{{ url('uploads/'.@\App\User::find($note->user_id)->image) }}">
                                                <br/>
                                                <br/>
                                                <span style="color: gray">{{ $note->created_at }}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <p>
                                                    {{ $note->note }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-12">
                                    <textarea class="form-control" id="newNote"
                                              placeholder="{{ __('admin.notes') }}"></textarea>
                                    <br/>
                                    <button type="button" class="btn btn-flat btn-success"
                                            id="noteBTN">{{ __('admin.add') }}</button>
                                </div>
                            </div>
                            <div class="tab-pane" id="docs">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin.title') }}</th>
                                        <th>{{ __('admin.file') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@\App\LeadDocument::where('lead_id',$show->id)->get() as $doc)
                                        <tr>
                                            <td>{{ $doc->title }}</td>
                                            <td><a target="_blank" href="{{ url('uploads/'.$doc->file) }}"
                                                   class="fa fa-file"></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <a data-toggle="modal" data-target="#addFile"
                                       class="btn btn-flat btn-primary">{{ __('admin.add') }}</a>
                                </div>
                            </div>
                            <div class="tab-pane" id="suggestions">
                                @php($requests = \App\Request::where('lead_id',$show->id)->get())
                                @foreach($requests as $req)
                                    @php($locationsArray = \App\Http\Controllers\HomeController::getChildren($req->location))
                                    @php($locationsArray[] = $req->location)
                                    @if($req->unit_type != 'land')
                                        @if($req->request_type == 'new_home')
                                            <div class="well">
                                                <strong>{{ __('admin.request') }} :</strong> #{{ $req->id }}
                                                <strong>{{ __('admin.type') }} :</strong>
                                                #{{ __('admin.'.$req->request_type) }}
                                                <br/>
                                                <br/>
                                                <table class="table table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ trans('admin.id') }}</th>
                                                        <th>{{ trans('admin.meter_price') }}</th>
                                                        <th>{{ trans('admin.area') }}</th>
                                                        <th>{{ trans('admin.title') }}</th>
                                                        <th>{{ trans('admin.show') }}</th>
                                                        <th>{{ trans('admin.edit') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach(@App\Project::where('type', $req->unit_type)->
                                                            whereBetween('meter_price', [$req->price_from, $req->price_to])->
                                                            whereBetween('area', [$req->area_from, $req->area_to])->
                                                            whereIn('location_id', $locationsArray)->
                                                            get() as $row)
                                                        <tr>
                                                            <td>{{ $row->id }}</td>
                                                            <td>{{ $row->meter_price }}</td>
                                                            <td>{{ $row->area }}</td>
                                                            <td>{{ $row->{app()->getLocale().'_name'} }}</td>
                                                            <td><a href="{{ url(adminPath().'/projects/'.$row->id) }}"
                                                                   class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ url(adminPath().'/projects/'.$row->id.'/edit') }}"
                                                                   class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @elseif($req->request_type == 'resale')
                                            <div class="well">
                                                <strong>{{ __('admin.request') }} :</strong> #{{ $req->id }}
                                                <strong>{{ __('admin.type') }} :</strong>
                                                #{{ __('admin.'.$req->request_type) }}
                                                <br/>
                                                <br/>
                                                <table class="table table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ trans('admin.property') }}</th>
                                                        <th>{{ trans('admin.title') }}</th>
                                                        <th>{{ trans('admin.status') }}</th>
                                                        <th>{{ trans('admin.location') }}</th>
                                                        <th>{{ trans('admin.price') }}</th>
                                                        <th>{{ trans('admin.rooms') }}</th>
                                                        <th>{{ trans('admin.bathrooms') }}</th>
                                                        <th>{{ trans('admin.area') }}</th>
                                                        <th>{{ trans('admin.delivery_date') }}</th>
                                                        <th>{{ trans('admin.show') }}</th>
                                                        <th>{{ trans('admin.send') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach(@\App\ResaleUnit::whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                                    where('type',$req->unit_type)->
                                                    where('unit_type_id',$req->unit_type_id)->
                                                    whereBetween('total',[$req->price_from,$req->price_to])->
                                                    whereBetween('area',[$req->area_from,$req->area_to])->
                                                    whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                                    whereIn('location',$locationsArray)->
                                                    where('delivery_date',$req->date)->
                                                    whereBetween('bathrooms',[$req->bathrooms_from,$req->bathrooms_to])->get()
                                                     as $resaleUnit)
                                                        <tr>
                                                            <td><img src="{{ url('uploads/'.$resaleUnit->image) }}"
                                                                     width="75 px"></td>
                                                            <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                                            <td>{{ trans('admin.'.$resaleUnit->availability) }}</td>
                                                            <td>{{ @\App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                                            <td>{{ $resaleUnit->total }}</td>
                                                            <td>{{ $resaleUnit->rooms }}</td>
                                                            <td>{{ $resaleUnit->bathrooms }}</td>
                                                            <td>{{ $resaleUnit->area }}</td>
                                                            <td>{{ $resaleUnit->delivery_date }}</td>
                                                            <td>
                                                                <a href="{{ url(adminPath().'/resale_units/'.$resaleUnit->id) }}"
                                                                   class="btn btn-flat btn-primary">{{ trans('admin.show') }}</a>
                                                            <td><a data-toggle="modal"
                                                                   data-target="#sendResale{{ $resaleUnit->id }}"
                                                                   class="btn btn-flat btn-success">{{ trans('admin.send') }}</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <div id="sendResale{{ $resaleUnit->id }}" class="modal fade"
                                                         role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">&times;
                                                                    </button>
                                                                    <h4 class="modal-title">{{ trans('admin.send') . ' ' . trans('admin.unit') }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {!! Form::open(['method'=>'post','url'=>adminPath().'/send_unit']) !!}
                                                                    {{ csrf_field() }}
                                                                    <select class="select2" name="lang"
                                                                            data-placeholder="{{ __('admin.language') }}"
                                                                            style="width: 100%">
                                                                        <option></option>
                                                                        <option value="ar">{{ __('admin.arabic') }}</option>
                                                                        <option value="en">{{ __('admin.english') }}</option>
                                                                    </select>
                                                                    <input type="hidden" value="{{ $resaleUnit->id }}"
                                                                           name="unit_id">
                                                                    <input type="hidden" value="resale" name="type">
                                                                    <input type="hidden" value="{{ $show->email }}"
                                                                           name="lead">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-default btn-flat"
                                                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                                    <button type="submit"
                                                                            class="btn btn-success btn-flat">{{ trans('admin.send') }}</button>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @elseif($req->request_type == 'rental')
                                            <div class="well">
                                                <strong>{{ __('admin.request') }} :</strong> #{{ $req->id }}
                                                <strong>{{ __('admin.type') }} :</strong>
                                                #{{ __('admin.'.$req->request_type) }}
                                                <br/>
                                                <br/>
                                                <table class="table table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ trans('admin.property') }}</th>
                                                        <th>{{ trans('admin.title') }}</th>
                                                        <th>{{ trans('admin.status') }}</th>
                                                        <th>{{ trans('admin.location') }}</th>
                                                        <th>{{ trans('admin.rent') }}</th>
                                                        <th>{{ trans('admin.rooms') }}</th>
                                                        <th>{{ trans('admin.bathrooms') }}</th>
                                                        <th>{{ trans('admin.area') }}</th>
                                                        <th>{{ trans('admin.delivery_date') }}</th>
                                                        <th>{{ trans('admin.show') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach(@\App\RentalUnit::whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                                    where('type',$req->unit_type)->
                                                    where('unit_type_id',$req->unit_type_id)->
                                                    whereBetween('rent',[$req->price_from,$req->price_to])->
                                                    whereBetween('area',[$req->area_from,$req->area_to])->
                                                    whereBetween('rooms',[$req->rooms_from,$req->rooms_to])->
                                                    whereIn('location',$locationsArray)->
                                                    where('delivery_date',$req->date)->
                                                    whereBetween('bathrooms',[$req->bathrooms_from,$req->bathrooms_to])->get() as $resaleUnit)
                                                        <tr>
                                                            <td><img src="{{ url('/uploads/'.$resaleUnit->image) }}"
                                                                     width="50px"></td>
                                                            <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                                            <td>{{ $resaleUnit->availability }}</td>
                                                            <td>{{ @App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                                            <td>{{ $resaleUnit->rent }}</td>
                                                            <td>{{ $resaleUnit->rooms }}</td>
                                                            <td>{{ $resaleUnit->bathrooms }}</td>
                                                            <td>{{ $resaleUnit->area }}</td>
                                                            <td>{{ $resaleUnit->delivery_date }}</td>
                                                            <td>
                                                                <a href="{{ url(adminPath().'/rental_units/'.$resaleUnit->id) }}"
                                                                   class="btn btn-flat btn-primary">{{ trans('admin.show') }}</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @endif
                                    @else
                                        <div class="well">
                                            <strong>{{ __('admin.request') }} :</strong> #{{ $req->id }}
                                            <strong>{{ __('admin.type') }} :</strong>
                                            #{{ __('admin.'.$req->request_type) }}
                                            <br/>
                                            <br/>
                                            <table class="table table-hover table-striped">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('admin.id') }}</th>
                                                    <th>{{ trans('admin.title') }}</th>
                                                    <th>{{ trans('admin.lead') }}</th>
                                                    <th>{{ trans('admin.location') }}</th>
                                                    <th>{{ trans('admin.show') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(@\App\Land::whereBetween('meter_price', [$req->price_from, $req->price_to])->
                                                whereBetween('area', [$req->area_from, $req->area_to])->
                                                whereIn('location', $locationsArray)->get() as $land)
                                                    <tr>
                                                        <td>{{ $land->id }}</td>
                                                        <td>{{ $land->{app()->getLocale().'_title'} }}</td>
                                                        <td>{{ @App\Lead::find($land->lead_id)->first_name }}</td>
                                                        <td>
                                                            {{ @App\Location::find($land->location)->{app()->getLocale().'_name'} }}
                                                        </td>
                                                        <td><a href="{{ url(adminPath().'/lands/'.$land->id) }}"
                                                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                                                        </td>
                                                    </tr>
                                                    <div id="delete{{ $land->id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">
                                                                        &times;
                                                                    </button>
                                                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>{{ trans('admin.delete') . ' ' . $land->name }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    {!! Form::open(['method'=>'DELETE','route'=>['lands.destroy',$land->id]]) !!}
                                                                    <button type="button"
                                                                            class="btn btn-default btn-flat"
                                                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                                    <button type="submit"
                                                                            class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="tab-pane" id="fav">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.property') }}</th>
                                        <th>{{ trans('admin.title') }}</th>
                                        <th>{{ trans('admin.type') }}</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($fav = @App\Favorite::where('lead_id',$show->id)->get())
                                    @foreach($fav as $favor)
                                        <tr>
                                            @php($unit = null)
                                            @if($favor->type == 'project')
                                                @php($unit = @App\Project::find($favor->unit_id))
                                                <td><img src="{{ url('upload'.$unit->cover) }}"></td>
                                                <td>
                                                    <a href="{{ url(adminPath().'/projects/'.$unit->id) }}">{{ $unit->{app()->getLocale().'_name'} }}</a>
                                                </td>
                                                <td>{{ __('admin.project') }}</td>
                                            @elseif($favor->type == 'resale')
                                                @php($unit = @App\ResaleUnit::find($favor->unit_id))
                                                @if($unit != null)
                                                    <td><img src="{{ url('upload/'.@$unit->image) }}"></td>
                                                    <td>
                                                        <a href="{{ url(adminPath().'/resale/'.@$unit->id) }}">{{ @$unit->{app()->getLocale().'_title'} }}</a>
                                                    </td>
                                                    <td>{{ __('admin.resale') }}</td>
                                                @endif
                                            @elseif($favor->type == 'rental')
                                                @if($unit != null)
                                                    <td><img src="{{ url('upload'.$unit->image) }}"></td>
                                                    <td>
                                                        <a href="{{ url(adminPath().'/resale/'.$unit->id) }}">{{ $unit->{app()->getLocale().'_title'} }}</a>
                                                    </td>
                                                    <td>{{ __('admin.rental') }}</td>
                                                @endif
                                                @php($unit = @App\RentalUnit::find($favor->unit_id))
                                            @endif

                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                            <div class="tab-pane" id="massage">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.massage') }}</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($fav = @App\Massage::where('lead_id',$show->id)->get())
                                    @foreach($fav as $favor)
                                        <tr>

                                            <td>{{ $favor->massage }}</td>

                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>

                            </div>
                            <div class="tab-pane" id="cils">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.developer') }}</th>
                                        <th>{{ trans('admin.status') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@App\Cil::where('lead_id',$show->id)->get() as $cil)
                                        <tr>
                                            <td>{{ @\App\Developer::find($cil->developer_id)->{app()->getLocale().'_name'} }}</td>
                                            <td>
                                                <span id="cil{{ $cil->id }}">
                                                    @if($cil->status == 'pending')
                                                        {{ __('admin.pending') }}
                                                        <i class="fa fa-check CILAction" type="confirmed"
                                                           cid="{{ $cil->id }}"
                                                           style="cursor:pointer; color: green; font-size: 1.5em"></i>
                                                        <i class="fa fa-remove CILAction" type="rejected"
                                                           cid="{{ $cil->id }}"
                                                           style="cursor:pointer; color: red; font-size: 1.5em"></i>
                                                    @elseif($cil->status == 'confirmed')
                                                        {{ __('admin.confirmed') }}
                                                    @elseif($cil->status == 'rejected')
                                                        {{ __('admin.rejected') }}
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>

                            </div>
                            <div class="tab-pane" id="interests">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('admin.type') }}</th>
                                        <th>{{ trans('admin.name') }}</th>
                                        <th>{{ trans('admin.date') }}</th>
                                        <th>{{ trans('admin.time') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@App\Interested::where('lead_id',$show->id)->get() as $interest)
                                        <tr>
                                            @if($interest->type == 'project')
                                                <td>{{ __('admin.project') }}</td>
                                                <td>{{ @App\Project::find($interest->unit_id)->{app()->getLocale().'_name'} }}</td>
                                            @elseif($interest->type == 'resale')
                                                <td>{{ __('admin.resale') }}</td>
                                                <td>{{ @App\ResaleUnit::find($interest->unit_id)->{app()->getLocale().'_title'} }}</td>
                                            @elseif($interest->type == 'rental')
                                                <td>{{ __('admin.rental') }}</td>
                                                <td>{{ @App\ResaleUnit::find($interest->unit_id)->{app()->getLocale().'_title'} }}</td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($interest->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($interest->created_at)->format('H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>

                            </div>
                            <div class="tab-pane" id="contracts">
                                <a href="{{ url(adminPath().'/contracts/create?lead='.$show->id) }}"
                                   class="btn btn-success btn-flat">
                                    {{ __('admin.create') }}
                                </a>
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin.agent') }}</th>
                                        <th>{{ __('admin.date') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        <th>{{ __('admin.url') }}</th>
                                        <th>{{ __('admin.show') }}</th>
                                        <th>{{ __('admin.edit') }}</th>
                                        <th>{{ __('admin.delete') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@App\Contract::where('lead_id',$show->id)->get() as $contract)
                                        <tr>
                                            <td>{{ @$contract->user->name }}</td>
                                            <td>{{ @$contract->created_at }}</td>
                                            <td>
                                                @if($contract->status)
                                                    {{ __('admin.confirmed') }}
                                                @else
                                                    {{ __('admin.pending') }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('/contracts/' . $contract->url) }}"
                                                   class="btn btn-primary btn-flat" target="_blank">
                                                    {{ __('admin.url') }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ url(adminPath() . '/contracts/' . $contract->id) }}"
                                                   class="btn btn-success btn-flat">
                                                    {{ __('admin.show') }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ url(adminPath() . '/contracts/' . $contract->id . '/edit') }}"
                                                   class="btn btn-warning btn-flat">
                                                    {{ __('admin.edit') }}
                                                </a>
                                            </td>
                                            <td>
                                                <a data-toggle="modal" data-target="#deleteContract{{ $contract->id }}" class="btn btn-danger btn-flat">
                                                    {{ __('admin.delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <div id="deleteContract{{ $contract->id }}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.contract') }}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ trans('admin.delete') }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-flat"
                                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                    <a class="btn btn-danger btn-flat" href="{{ url(adminPath() . '/delete-contracts/' . $contract->id) }}">{{ trans('admin.delete') }}</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                </table>

                            </div>
                            <div class="tab-pane" id="voice_notes">
                                <table class="table table-hover table-striped datatable">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin.note') }}</th>
                                        <th>{{ __('admin.agent') }}</th>
                                        <th>{{ __('admin.date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(@\App\VoiceNote::where('lead_id',$show->id)->get() as $voice)
                                            <tr>
                                                <td>
                                                    <audio controls>
                                                        <source src="{{ url('uploads/' . $voice->note) }}" type="audio/mpeg">
                                                    </audio>    
                                                </td>
                                                <td>{{ @$voice->agent->name }}</td>
                                                <td>{{ @$voice->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.activity') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active tab-button"><a href="#calls" data-toggle="tab" class="padding-tap"
                                                                 aria-expanded="true">{{ trans('admin.calls') }}</a>
                                </li>
                                <li class=""><a href="#meetings" data-toggle="tab" class="padding-tap"
                                                aria-expanded="true">{{ trans('admin.meetings') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="calls">
                                    <table class="table table-hover table-striped datatable">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('admin.id') }}</th>
                                            <th>{{ trans('admin.contact') }}</th>
                                            <th>{{ trans('admin.duration') }}</th>
                                            <th>{{ trans('admin.probability') }}</th>
                                            <th>{{ trans('admin.budget') }}</th>
                                            <th>{{ trans('admin.date') }}</th>
                                            <th>{{ trans('admin.delete') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(@\App\Call::where('lead_id',$show->id)->get() as $call)
                                            <tr data-toggle="tooltip" data-placement="left"
                                                title="{{ $call->description }}">
                                                <td><a class="black-color"
                                                       href="{{ url(adminPath().'/calls/'.$call->id) }}">{{ $call->id }}</a>
                                                </td>
                                                <td>
                                                    <a class="black-color"
                                                       href="{{ url(adminPath().'/calls/'.$call->id) }}">
                                                        @if($call->contact_id == 0)
                                                            {{ $show->first_name . ' ' . $show->last_name }}
                                                        @else
                                                            {{ @\App\Contact::find($call->contact_id)->name }}
                                                        @endif
                                                    </a>
                                                </td>
                                                <td><a class="black-color"
                                                       href="{{ url(adminPath().'/calls/'.$call->id) }}">{{ $call->duration }}</a>
                                                </td>
                                                <td>
                                                    <a class="black-color"
                                                       href="{{ url(adminPath().'/calls/'.$call->id) }}">{{ __('admin.' . $call->probability) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="black-color"
                                                       href="{{ url(adminPath().'/calls/'.$call->id) }}">{{ $call->budget }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ date('Y-m-d',$call->date) }}
                                                </td>
                                                <td><a data-toggle="modal" data-target="#delete{{ $call->id }}"><i
                                                                class="fa fa-trash-o trash-table"
                                                                aria-hidden="true"></i></a></td>

                                            </tr>
                                        </tbody>
                                        <div id="delete{{ $call->id }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;
                                                        </button>
                                                        <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.call') }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ trans('admin.delete') . ' #' . $call->id }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        {!! Form::open(['method'=>'DELETE','route'=>['calls.destroy',$call->id]]) !!}
                                                        <button type="button" class="btn btn-default btn-flat"
                                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                        <button type="submit"
                                                                class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="tab-pane" id="meetings">
                                    <table class="table table-hover table-striped datatable">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('admin.id') }}</th>
                                            <th>{{ trans('admin.contact') }}</th>
                                            <th>{{ trans('admin.duration') }}</th>
                                            <th>{{ trans('admin.probability') }}</th>
                                            <th>{{ trans('admin.budget') }}</th>
                                            <th>{{ trans('admin.date') }}</th>
                                            <th>{{ trans('admin.show') }}</th>
                                            <th>{{ trans('admin.delete') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(@\App\Meeting::where('lead_id',$show->id)->get() as $meeting)
                                            <tr data-toggle="tooltip" data-placement="left"
                                                title="{{ $meeting->description }}">
                                                <td>{{ $meeting->id }}</td>
                                                <td>
                                                    @if($meeting->contact_id == 0)
                                                        {{ $show->first_name . ' ' . $show->last_name }}
                                                    @else
                                                        {{ @\App\Contact::find($meeting->contact_id)->name }}
                                                    @endif
                                                </td>
                                                <td>{{ $meeting->duration }}</td>
                                                <td>{{ __('admin.' . $meeting->probability) }}</td>
                                                <td>{{ $meeting->budget }}</td>
                                                <td>{{ date('Y-m-d',$meeting->date) }}</td>
                                                <td><a href="{{ url(adminPath().'/meetings/'.$meeting->id) }}"
                                                       class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                                                </td>
                                                <td><a data-toggle="modal" data-target="#delete{{ $meeting->id }}"
                                                       class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <div id="delete{{ $meeting->id }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;
                                                        </button>
                                                        <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.call') }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ trans('admin.delete') . ' #' . $meeting->id }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        {!! Form::open(['method'=>'DELETE','route'=>['meetings.destroy',$meeting->id]]) !!}
                                                        <button type="button" class="btn btn-default btn-flat"
                                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                        <button type="submit"
                                                                class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.history') }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.id') }}</th>
                                    <th>{{ trans('admin.title') }}</th>
                                    <th>{{ trans('admin.type') }}</th>
                                    <th>{{ trans('admin.agent') }}</th>
                                    <th>{{ trans('admin.date') }}</th>
                                    <th>{{ trans('admin.show') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(App\Log::where('route','leads')->where('route_id',$show->id)->get() as $log)
                                    <tr>
                                        <td>{{ @$log->id }}</td>
                                        <td>{{ @$log->{app()->getLocale().'_title'} }}</td>
                                        <td>{{ __('admin.'.$log->type) }}</td>
                                        <td>
                                            <a href="{{ url(adminPath().'/agent/'.$log->user_id) }}">{{ @\App\User::find($log->user_id)->name }}</a>
                                        </td>
                                        <td>{{ $log->created_at }}</td>
                                        <td>
                                            @if($log->type != 'log')
                                                <a href="{{ url(adminPath().'/logs/'.$log->id) }}"
                                                   class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.email') }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button data-toggle="modal" data-target="#compose" type="button"
                                    class="btn btn-danger btn-flat">{{ __('admin.compose') }}</button>
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>{{ __('admin.subject') }}</th>
                                    <th>{{ __('admin.from') }}</th>
                                    <th>{{ __('admin.date') }}</th>
                                    <th>{{ __('admin.show') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($messages = Home::lead_inbox($show->email))
                                @foreach($messages as $message)
                                    <tr id="tr{{ @$message->msgno }}"
                                        @if(!@$message->seen) style="background: rgba(193, 66, 66, 0.37)" @endif>
                                        <td>{{ @$message->subject }}</td>
                                        <td>{{ @$message->from }}</td>
                                        <td>{{ @$message->date }}</td>
                                        <td>
                                            <button type="button" class="getMail btn btn-primary btn-flat"
                                                    msgno="{{ @$message->msgno }}">{{ __('admin.show') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('style')
<!--//new_sec_added datepicker ..-->
<link href="{{ url('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
<style type="text/css">
        .cc-selector input{
            margin:0px;padding:0px;
            -webkit-appearance:none;
               -moz-appearance:none;
                    appearance:none;
                    clear:both;
            
        }
        .cc-selector label{
                                clear:both;
        }
        .cc-selector-2 input{
            position:absolute;
            z-index:999;
        }

        .inGoing{background-image:url({{ url('icon/inCall.png') }});}
        .outGoing{background-image:url({{ url('icon/outCall.png') }});
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
            }

        .cc-selector-2 input:active +.drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
        .cc-selector-2 input:checked +.drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
            -webkit-filter: none;
               -moz-filter: none;
                  
                    filter: none;
        }
        .drinkcard-cc{
            cursor:pointer;
            background-size:contain;
            background-repeat:no-repeat;
            display:inline-block;
            width:40px;height:40px;
            -webkit-transition: all 100ms ease-in;
               -moz-transition: all 100ms ease-in;
                    transition: all 100ms ease-in;
            -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
               -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
                    filter: brightness(1.8) grayscale(1) opacity(.7);
        }
        .drinkcard-cc:hover{
            -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
               -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
                    filter: brightness(1.2) grayscale(.5) opacity(.9);
        }

        /* Extras */
        a:visited{color:#888}
        a{color:#444;text-decoration:none;}
        p{margin-bottom:.3em;}
        * { font-family:monospace; }
        .cc-selector-2 input{ margin: 5px 0 0 12px; }
        .cc-selector-2 label{ margin-left: 7px; }
        span.cc{ color:#6d84b4 }
    /* btn select div */
    .btnCol{
        border-radius: 60px !important;
        background-color:gainsboro;
        height:20px !important;
        width: 20px;
        text-align: center;
        padding: 0px !important;
        font-weight: bold;
        margin-left: 10px;
        margin-top: -3px;
    }
    .actionTab{
        background: #999 !important;
        padding: 8px 20px 5px !important;
        margin-bottom: 5px;
    }
    .panel-default{
        border: none;
    }
    .panel-default form{
        padding: 30px 40px 30px 30px;
    }
    #addPhoneBtn{
    margin-top: 4px;
    position: absolute;
    z-index: 1000000000;
    float: right;
    right: 25px;
    display: none;
    }
    #addPhoneBtn button{
    height: 30px;
    font-size: 14;
    font-weight: bold;
    }
    .oCallSel{
        width: 80px;
        height: 80px;
        border: 1px solid #333;
        padding: 10px;
        margin: 10px;
        float: left;
    }
    .form-group{
        clear: both;
    }
    .ui-selected{
        border: 4px solid #373;
    }
</style>
@endsection
@section('js')
<!--//new_sec_added datepicker ..-->
    <script type="text/javascript" src="{{url('js/bootstrap-datetimepicker.min.js')}}" charset="UTF-8"></script>
<script>
    $('.datepicker1').datetimepicker({
    //new_sec_added datepicker ..
    language:  'en',
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 0,
    forceParse: 1,
    });
    $(document).ready(function() {
        $('#gCallSel').selectable();  
    });
    function show_error(name,t){
        if(t=='i'){
            $('input[name='+name+']').parent().addClass('has-error');
                $('html, body').animate({
                    scrollTop: $('input[name='+name+']').offset().top
                }, 100);
        }else if(t=='ip'){
            $('input[name='+name+']').parent().addClass('has-error');
            $('input[name='+name+']').parent().parent().addClass('has-error');
            $('html, body').animate({
                    scrollTop: $('input[name='+name+']').offset().top
                }, 100);
        }
        else{
            $('select[name='+name+']').parent().addClass('has-error');
                $('html, body').animate({
                    scrollTop: $('select[name='+name+']').offset().top
                }, 100);
        }
        
    }
    function hide_error(name,t){
        if(t=='i'){
        $('input[name='+name+']').parent().removeClass('has-error');
        }else if(t=='ip'){
            $('input[name='+name+']').parent().removeClass('has-error');
            $('input[name='+name+']').parent().parent().removeClass('has-error');
        }else{
            $('select[name='+name+']').parent().removeClass('has-error');
        }
    }
    $('select[name=call_status_id]').next().focusout(function(){
        if($('select[name=call_status_id]').val()==''){
            show_error('call_status_id','s');
        }else{
            hide_error('call_status_id','s');
        }
    });
    $('input[name=duration]').focusout(function(){
        if($('input[name=duration]').val()==''){
            show_error('duration','i');
        }else{
            hide_error('duration','i');
        }
        if($('select[name=call_status_id]').val()==''){
            show_error('call_status_id','s');
        }else{
            hide_error('call_status_id','s');
        }

    });
    $('input[name=date]').focusout(function(){
        if($('input[name=date]').val()==''){
            show_error('date','ip');
        }else{
            hide_error('date','ip');
        }
        if($('input[name=duration]').val()==''){
            show_error('duration','i');
        }else{
            hide_error('duration','i');
        }
        if($('select[name=call_status_id]').val()==''){
            show_error('call_status_id','s');
        }else{
            hide_error('call_status_id','s');
        }
    });
    $('select[name=probability]').next().focusout(function(){
        if($('select[name=probability]').val()==''){
            show_error('probability','s');
        }else{
            hide_error('probability','s');
        }
        if($('input[name=date]').val()==''){
            show_error('date','ip');
        }else{
            hide_error('date','ip');
        }
        if($('input[name=duration]').val()==''){
            show_error('duration','i');
        }else{
            hide_error('duration','i');
        }
        if($('select[name=call_status_id]').val()==''){
            show_error('call_status_id','s');
        }else{
            hide_error('call_status_id','s');
        }
    });
    $('input[name=budget]').focusout(function(){
        if($('input[name=budget]').val()==''){
            show_error('budget','ip');
        }else{
            hide_error('budget','ip');
        }
        if($('select[name=probability]').val()==''){
            show_error('probability','s');
        }else{
            hide_error('probability','s');
        }
        if($('input[name=date]').val()==''){
            show_error('date','ip');
        }else{
            hide_error('date','ip');
        }
        if($('input[name=duration]').val()==''){
            show_error('duration','i');
        }else{
            hide_error('duration','i');
        }
        if($('select[name=call_status_id]').val()==''){
            show_error('call_status_id','s');
        }else{
            hide_error('call_status_id','s');
        }
    });
    $('select[name="projects[]"]').next().focusout(function(){
        if($('select[name="projects[]"]').length == 0){
            show_error('"projects[]"','');
        }else{
            hide_error('"projects[]"','s');
        }
        if($('input[name=budget]').val()==''){
            show_error('budget','ip');
        }else{
            hide_error('budget','ip');
        }
        if($('select[name=probability]').val()==''){
            show_error('probability','s');
        }else{
            hide_error('probability','s');
        }
        if($('input[name=date]').val()==''){
            show_error('date','ip');
        }else{
            hide_error('date','ip');
        }
        if($('input[name=duration]').val()==''){
            show_error('duration','i');
        }else{
            hide_error('duration','i');
        }
        if($('select[name=call_status_id]').val()==''){
            show_error('call_status_id','s');
        }else{
            hide_error('call_status_id','s');
        }
    });
</script>
<script>
    $('#phone .select2-search__field').change(function(){
        if($('input.select2-search__field').val()!=''){
             $('#addPhoneBtn button').removeAttr('disable');
             $('#addPhoneBtn button').removeClass('btn-primary');

        }else{
             $('#addPhoneBtn button').attr('disable');
             $('#addPhoneBtn button').addClass('btn-primary');
        }
    });

        $('#phone').next().click(function(){
            if($('#phone').next().hasClass('select2-container--open')){
                $('#addPhoneBtn').css('display','block');
            }else{
                $('#addPhoneBtn').css('display','none');
            }
        });
        $('#phone').next().focusout(function(){
            if($('#phone').next().hasClass('select2-container--open')){
            $('#addPhoneBtn').css('display','block');
            }else{
                $('#addPhoneBtn').css('display','none');
            }
        });

</script>
      <script >
          $('.btnCol').click(function(){
           if($(this).html()=='+'){
               $(this).html('-');
           }else if($(this).html()=='-'){
               $(this).html('+');
           }
        });
      </script>  
    <script>
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': false,
            'autoWidth': true,
            "pagingType": 'simple',

        })
    </script>
    <script>
        $(document).on('change', '#Contact_id', function () {
            var contact_id = $(this).val();
            var lead_id = $('#lead_id').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_phones')}}",
                method: 'post',
                dataType: 'html',
                data: {contact_id: contact_id, _token: _token, lead: lead_id},
                success: function (data) {
                    $('#getPhones').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>
    <script>
        $('.datepicker').datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
        });
    </script>
    <script>
        $(document).on('change', '#unit_type', function () {
            var usage = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_unit_types')}}",
                method: 'post',

                data: {usage: usage, _token: _token},
                success: function (data) {
                    $('#unit_type_id').html(data);
                }
            });
        });
    </script>
    <script>
        $(document).on('change', '#request_type', function () {
            var reqType = $(this).val();
            if (reqType == 'new_home') {
                $('#resale_rental').addClass('hidden');
            } else {
                $('#resale_rental').removeClass('hidden');
            }
        })
    </script>
    <script>
        $(document).on('click', '#noteBTN', function () {
            var lead_id = '{{ $show->id }}';
            var user_id = '{{ auth()->user()->id }}';
            var note = $('#newNote').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/add_note')}}",
                method: 'post',
                dataType: 'html',
                data: {lead_id: lead_id, user_id: user_id, note: note, _token: _token},
                beforeSend: function () {

                },
                success: function (data) {
                    $('#newNote').val('');
                    $('#allNotes').append(data);
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '#getSuggestions', function () {
            var location = $('#location').val();
            var unit_type = $('#unit_type').val();
            console.log(unit_type);
            var unit_type_id = $('#unit_type_id').val();
            var request_type = $('#request_type').val();
            var rooms_from = $('#rooms_from').val();
            var rooms_to = $('#rooms_to').val();
            var bathrooms_from = $('#bathrooms_from').val();
            var bathrooms_to = $('#bathrooms_to').val();
            var price_from = $('#price_from').val();
            var price_to = $('#price_to').val();
            var area_from = $('#area_from').val();
            var area_to = $('#area_to').val();
            var date = $('#date').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_suggestions')}}",
                method: 'post',
                data: {
                    location: location,
                    unit_type: unit_type,
                    unit_type_id: unit_type_id,
                    request_type: request_type,
                    rooms_from: rooms_from,
                    rooms_to: rooms_to,
                    bathrooms_from: bathrooms_from,
                    bathrooms_to: bathrooms_to,
                    price_from: price_from,
                    price_to: price_to,
                    area_from: area_from,
                    area_to: area_to,
                    date: date,
                    _token: _token
                },
                success: function (data) {
                   
                    $('#get_suggestions').html(data);
                }
            });
        })
    </script>
    <script>
        var i = 1;
        $(document).on('click', '#CILBtn', function () {
            $('#addCIL').append('<div id="cil' + i + '">' +
                '<div class="col-md-6">' +
                '<select required count="' + i + '" class="form-control select2 cilDeveloper" style="width: 100%" ' +
                'name="developers[]" ' +
                ' data-placeholder="{{ trans("admin.developer") }}">' +
                '<option></option>' +
                '@foreach(@\App\Developer::all() as $developer)' +
                '<option value="{{ $developer->id }}">{{ $developer->{app()->getLocale()."_name"} }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="col-md-5">' +
                '<select count="' + i + '" class="form-control select2" style="width: 100%" ' +
                'name="projects[]" ' +
                'data-placeholder="{{ trans("admin.project") }}" id="cilProject' + i + '">' +
                '<option></option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<a type="button" class="fa fa-minus removeCIL" count="' + i + '" style="font-size: 1.7em; cursor: pointer" ' +
                '></a>' +
                '</div>' +
                '<br/>' +
                '<br/>' +
                '<br/>' +
                '</div>');
            $('.select2').select2();
            i++;
        });

        $(document).on('click', '.removeCIL', function () {
            var count = $(this).attr('count');
            $('#cil' + count).remove();
        });

        $(document).on('change', '.cilDeveloper', function () {
            var count = $(this).attr('count');
            var id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_projects')}}",
                method: 'post',
                data: {
                    count: count,
                    id: id,
                    _token: _token
                },
                success: function (data) {
                    $('#cilProject' + count).html(data);
                }
            });
        });
    </script>
    {{-- Ajax Update --}}
    <script>
        $(document).on('click', '.update', function () {
            var type = $(this).attr('type');
            $(this).addClass('hidden');
            $('#' + type).addClass('hidden');
            $('#' + type + '_input').removeClass('hidden');
            $('#' + type + '_save').removeClass('hidden');
        });
        $(document).on('click', '.save', function () {
            var type = $(this).attr('type');
            var value = $('#' + type + '_update').val();
            var id = '{{ $show->id }}';
            $.ajax({
                url: "{{ url(adminPath().'/update_lead')}}",
                method: 'post',
                dataType: 'json',
                data: {type: type, value: value, id: id, _token: '{{ csrf_token() }}'},
                beforeSend: function () {
                    $('#' + type + '_save').addClass('fa-spin');
                },
                success: function (data) {
                    $('#' + type + '_save').removeClass('fa-spin');
                    $('#' + type + '_btn').removeClass('hidden');
                    $('#' + type).html(data.value);
                    $('#newNote').val('');
                    $(this).addClass('hidden');
                    $('#' + type).removeClass('hidden');
                    $('#' + type + '_input').addClass('hidden');
                    $('#' + type + '_save').addClass('hidden');
                    if (data.type == 'first_name') {
                        $('#old_first_name').html(data.value);
                    }
                    if (data.type == 'middle_name') {
                        $('#old_middle_name').html(data.value);
                    }
                    if (data.type == 'last_name') {
                        $('#old_last_name').html(data.value);
                    }
                    if (data.type == 'country_id') {
                        $('#city_id_update').html(data.new_cities);
                    }
                },
                error: function () {
                    alert('{{ __('admin.errer') }}');
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '.getMail', function () {
            var id = $(this).attr('msgno');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_mail')}}/" + id,
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                beforeSend: function () {
                    $('#mailBody').html('<i class="fa fa-circle-o-notch fa-spin fa-2x"></i>');
                    $('#mail').modal('show');
                },
                success: function (data) {
                    $('#mailBody').html(data);
                    $('#tr' + id).css('background', '#fff')
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '.CILAction', function () {
            var cid = $(this).attr('cid');
            var type = $(this).attr('type');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/cil_change_status')}}/" + cid,
                method: 'post',
                dataType: 'json',
                data: {type: type, _token: _token},
                beforeSend: function () {
                    $('#cil' + cid).html('<i class="fa fa-spinner fa-spin"></i>')
                },
                success: function (data) {
                    if (data.status == 'confirmed') {
                        $('#cil' + data.id).html('{{ __('admin.confirmed') }}')
                    } else if (data.status == 'rejected') {
                        $('#cil' + data.id).html('{{ __('admin.rejected') }}')
                    } else {
                        alert('{{ __('admin.error') }}')
                    }
                },
                error: function () {
                    $('#cil' + cid).html('')
                    alert('{{ __('admin.error') }}')
                }
            })
        })
    </script>
    <script>
        CKEDITOR.replace('message');
    </script>

    <script>
        $(document).on('click', '#addAction', function () {
            $('#nextAction').html('<div class="well" id="action">' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.to_do_type") }}</label>' +
                '<select class="form-control select2" name="to_do_type" data-placeholder="{{ trans("admin.to_do_type") }}" style="width: 100%">' +
                '<option></option>' +
                '<option value="call">{{ trans("admin.call") }}</option>' +
                '<option value="meeting">{{ trans("admin.meeting") }}</option>' +
                '<option value="other">{{ trans("admin.other") }}</option>' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.due_date") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::text("to_do_due_date","",["class" => "form-control datepicker", "placeholder" => trans("admin.due_date"),"readonly"=>""]) !!}' +
                '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>{{ trans("admin.description") }}</label>' +
                '{!! Form::textarea("to_do_description","",["class" => "form-control", "placeholder" => trans("admin.description"),"rows"=>5]) !!}' +
                '</div>' +
                '</div>')
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $(this).addClass('hidden');
            $('#removeAction').removeClass('hidden');
        });

        $(document).on('click', '#removeAction', function () {
            $('#action').remove();
            $(this).addClass('hidden');
            $('#addAction').removeClass('hidden');
        })

    </script>
    <script>
        $(document).on('change', '#callStatus', function() {
            var action = $('option:selected', this).attr('next');
            if (action == 1) {
                $('#nextAction').html('<div class="well" id="action">' +
                    '<div class="form-group col-md-6">' +
                    '<label>{{ trans("admin.to_do_type") }}</label>' +
                    '<select class="form-control select2" name="to_do_type" data-placeholder="{{ trans("admin.to_do_type") }}" style="width: 100%">' +
                    '<option></option>' +
                    '<option value="call">{{ trans("admin.call") }}</option>' +
                    '<option value="meeting">{{ trans("admin.meeting") }}</option>' +
                    '<option value="other">{{ trans("admin.other") }}</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<label>{{ trans("admin.due_date") }}</label>' +
                    '<div class="input-group">' +
                    '{!! Form::text("to_do_due_date","",["class" => "form-control datepicker", "placeholder" => trans("admin.due_date"),"readonly"=>""]) !!}' +
                    '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>{{ trans("admin.description") }}</label>' +
                    '{!! Form::textarea("to_do_description","",["class" => "form-control", "placeholder" => trans("admin.description"),"rows"=>5]) !!}' +
                    '</div>' +
                    '</div>')
                $('.select2').select2();
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd'
                });
                $('#addAction').addClass('hidden');
                $('#removeAction').removeClass('hidden');
            } else {
                $('#action').remove();
                $('#removeAction').addClass('hidden');
                $('#addAction').removeClass('hidden');
            }
        })
    </script>
    <script>
        $(document).on('change', '#lead_id', function () {
            var id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_calls_contacts')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#contacts').html(data);
                    $('.select2').select2();
                }
            });
            $.ajax({
                url: "{{ url(adminPath().'/get_calls')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getCalls').html(data);
                }
            });

            $.ajax({
                url: "{{ url(adminPath().'/get_meetings')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getMeetings').html(data);
                }
            });

            $.ajax({
                url: "{{ url(adminPath().'/get_requests')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getRequests').html(data);
                }
            })
        })
    </script>
    <script>
        $(document).on('change', '#Contact_id', function () {
            var contact_id = $(this).val();
            var lead_id = $('#lead_id').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_phones')}}",
                method: 'post',
                dataType: 'html',
                data: {contact_id: contact_id, _token: _token, lead: lead_id},
                success: function (data) {
                    $('#getPhones').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>

    <script>
        $(document).on('click', '#MaddAction', function () {
            $('#MnextAction').html('<div class="well" id="action">' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.to_do_type") }}</label>' +
                '<select class="form-control select2" name="to_do_type" data-placeholder="{{ trans("admin.to_do_type") }}" style="width: 100%">' +
                '<option></option>' +
                '<option value="call">{{ trans("admin.call") }}</option>' +
                '<option value="meeting">{{ trans("admin.meeting") }}</option>' +
                '<option value="other">{{ trans("admin.other") }}</option>' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.due_date") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::text("to_do_due_date","",["class" => "form-control datepicker", "placeholder" => trans("admin.due_date"),"readonly"=>""]) !!}' +
                '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>{{ trans("admin.description") }}</label>' +
                '{!! Form::textarea("to_do_description","",["class" => "form-control", "placeholder" => trans("admin.description"),"rows"=>5]) !!}' +
                '</div>' +
                '</div>')
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $(this).addClass('hidden');
            $('#MremoveAction').removeClass('hidden');
        });

        $(document).on('click', '#MremoveAction', function () {
            $('#Maction').remove();
            $(this).addClass('hidden');
            $('#MaddAction').removeClass('hidden');
        })

    </script>
    <script>
        $(document).on('change', '#meetingStatus', function() {
            var action = $('option:selected', this).attr('next');
            if (action == 1) {
                $('#MnextAction').html('<div class="well" id="Maction">' +
                    '<div class="form-group col-md-6">' +
                    '<label>{{ trans("admin.to_do_type") }}</label>' +
                    '<select class="form-control select2" name="to_do_type" data-placeholder="{{ trans("admin.to_do_type") }}" style="width: 100%">' +
                    '<option></option>' +
                    '<option value="call">{{ trans("admin.call") }}</option>' +
                    '<option value="meeting">{{ trans("admin.meeting") }}</option>' +
                    '<option value="other">{{ trans("admin.other") }}</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<label>{{ trans("admin.due_date") }}</label>' +
                    '<div class="input-group">' +
                    '{!! Form::text("to_do_due_date","",["class" => "form-control datepicker", "placeholder" => trans("admin.due_date"),"readonly"=>""]) !!}' +
                    '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>{{ trans("admin.description") }}</label>' +
                    '{!! Form::textarea("to_do_description","",["class" => "form-control", "placeholder" => trans("admin.description"),"rows"=>5]) !!}' +
                    '</div>' +
                    '</div>')
                $('.select2').select2();
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd'
                });
                $('#MaddAction').addClass('hidden');
                $('#MremoveAction').removeClass('hidden');
            } else {
                $('#Maction').remove();
                $('#MremoveAction').addClass('hidden');
                $('#MaddAction').removeClass('hidden');
            }
        })
    </script>
@stop