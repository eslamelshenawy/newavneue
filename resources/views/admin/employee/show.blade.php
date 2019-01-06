@extends('admin.index')
@section ('styles')
    <link href="https://www.cssscript.com/wp-includes/css/sticky.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ url('dashboard_employee/css/stars.css') }}">

@endsection
@section('content')


     @if(\App\Employee::find(auth()->user()->employee_id)->is_hr == 1)
    @include('admin.employee.hr_nav')
    @endif

<style>
    .vacationstyle{
        text-align:right;
        font-weight: bold;
        height:59px;
    }
    .days{
        text-align: center;
        font-weight: bold;
    }
    </style>
    <style>
        label.btn span {
  font-size: 1.5em ;
}

label input[type="radio"] ~ i.fa.fa-circle-o{
    color: #c8c8c8;    display: inline;
}
label input[type="radio"] ~ i.fa.fa-dot-circle-o{
    display: none;
}
label input[type="radio"]:checked ~ i.fa.fa-circle-o{
    display: none;
}
label input[type="radio"]:checked ~ i.fa.fa-dot-circle-o{
    color: #7AA3CC;    display: inline;
}
label:hover input[type="radio"] ~ i.fa {
color: #7AA3CC;
}

label input[type="checkbox"] ~ i.fa.fa-square-o{
    color: #c8c8c8;    display: inline;
}
label input[type="checkbox"] ~ i.fa.fa-check-square-o{
    display: none;
}
label input[type="checkbox"]:checked ~ i.fa.fa-square-o{
    display: none;
}
label input[type="checkbox"]:checked ~ i.fa.fa-check-square-o{
    color: #7AA3CC;    display: inline;
}
label:hover input[type="checkbox"] ~ i.fa {
color: #7AA3CC;
}

div[data-toggle="buttons"] label.active{
    color: #7AA3CC;
}

div[data-toggle="buttons"] label {
display: inline-block;
padding: 6px 12px;
margin-bottom: 0;
font-size: 14px;
font-weight: normal;
line-height: 2em;
text-align: left;
white-space: nowrap;
vertical-align: top;
cursor: pointer;
background-color: none;
border: 0px solid 
#c8c8c8;
border-radius: 3px;
color: #c8c8c8;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
-o-user-select: none;
user-select: none;
}

div[data-toggle="buttons"] label:hover {
color: #7AA3CC;
}

div[data-toggle="buttons"] label:active, div[data-toggle="buttons"] label.active {
-webkit-box-shadow: none;
box-shadow: none;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            
            @if(@$employee->photos->where('code', 'profile')->first()->image)
            <img src="{{url('uploads/'.$employee->photos->where('code', 'profile')->first()->image) }}" class="img-square lead_image"
                             alt="{{ __('admin.employee') }}"
                             width="130px"
                             height="130px">
                            </div>
                            @else
                            <img src="{{url('uploads/website_cover_81698172832.jpg')}}" class="img-square lead_image"
                             alt="{{ __('admin.employee') }}"
                             width="130px"
                             height="130px">
                            </div>
                            @endif
                        
                            <div class="col-md-6 head_margin_top">
                                <div class="col-xs-6">
                                    <div class="col-xs-12 l-m-b">

                                         <span class="lead_name_span font-18">
                                             <i class="fa fa-user-o" aria-hidden="true"></i>
                                             <span id="old_first_name">{{ $employee->en_first_name }}</span>
                                             <span id="old_middle_name">{{  $employee->en_middle_name }}</span>
                                             <span id="old_last_name">{{  $employee->en_last_name}}</span>
                                             <a href="#"><i class="" aria-hidden="true"></i></a>
                                            </span>
                    <div class="col-xs-30 l-m-b font-18">
                        <strong> {{ @\App\JobTitle::find($employee->job_title_id)->en_name }}</strong>
                        <a href="#"><i class="" aria-hidden="true"></i></a>
                    </div>
                    @if($kpi_percent==0)
                        <div></div>
                    @else
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-animated progress-bar-striped progress-bar-animated"
                                 role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                 style="width: {{$kpi_percent}}%"><strong>{{$kpi_percent}}% KPI Rating</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xs-6">
                <div class="col-xs-12 l-m-b">
                    <div id="piechart">

                    </div>
                </div>

            </div>
        </div>
    </div><br>







    <div class="container">

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#profile "><strong>Personal Data</strong></a></li>
            <li><a data-toggle="tab" href="#salary"><strong>Salary</strong></a></li>
            <li><a data-toggle="tab" href="#time"><strong>Time</strong></a></li>
            <li><a data-toggle="tab" href="#ER_Contact"><strong>ER Contacts</strong></a></li>
            <li><a data-toggle="tab" href="#Custody"><strong>Custody</strong></a></li>
            <li><a data-toggle="tab" href="#MasterData"><strong>Master Data</strong></a></li>
            <li><a data-toggle="tab" href="#Rating"><strong>KPI Rating</strong></a></li>
        </ul>

        <div class="tab-content">
            <div id="profile" class="tab-pane fade in active">
                <h3>Employee Profile</h3>
                <div class="row ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ trans('admin.more_information') }}</h3>

                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.first_name') }} [EN] :
                                                <span id="en_first_name" class="">
                                                {{ $employee->en_first_name }}
                                            </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="en_first_name"
                                                       id="en_first_name_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="en_first_name"
                                                       id="en_first_name_save"></i>
                                                    <span id="en_first_name_input" class="hidden">
                                                <input type="text" class="update_input"
                                                       value="{{ $employee->en_first_name }}"
                                                       id="en_first_name_update">
                                            </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.middle_name') }} [EN] :
                                            <span id="en_middle_name" class="">
                                                        {{ $employee->en_middle_name }}
                                        </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="en_middle_name"
                                                   id="en_middle_name_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="en_middle_name"
                                                   id="en_middle_name_save"></i>
                                                <span id="en_middle_name_input" class="hidden">
                                            <input type="text" class="update_input"
                                                   value="{{ $employee->en_middle_name }}"
                                                   id="en_middle_name_update">
                                        </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.last_name') }} [EN] :
                                                <span id="en_last_name" class="">
                                                {{ $employee->en_last_name }}
                                            </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="en_last_name"
                                                       id="en_last_name_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="en_last_name"
                                                       id="en_last_name_save"></i>
                                                    <span id="en_last_name_input" class="hidden">
                                                <input type="text" class="update_input"
                                                       value="{{ $employee->en_last_name }}"
                                                       id="en_last_name_update">
                                            </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.ar_first_name') }} :
                                            <span id="ar_first_name" class="">
                                                {{ $employee->ar_first_name }}
                                            </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="ar_first_name"
                                                   id="ar_first_name_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="ar_first_name"
                                                   id="ar_first_name_save"></i>
                                                <span id="ar_first_name_input" class="hidden">
                                                <input type="text" class="update_input"
                                                       value="{{ $employee->ar_first_name }}"
                                                       id="ar_first_name_update">
                                            </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.ar_middle_name') }} :
                                                <span id="ar_middle_name" class="">
                                                {{ $employee->ar_middle_name }}
                                            </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="ar_middle_name"
                                                       id="ar_middle_name_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="ar_middle_name"
                                                       id="ar_middle_name_save"></i>
                                                    <span id="ar_middle_name_input" class="hidden">
                                                <input type="text" class="update_input"
                                                       value="{{ $employee->ar_middle_name }}"
                                                       id="ar_middle_name_update">
                                            </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.ar_last_name') }} :
                                            <span id="ar_last_name" class="">
                                            {{ $employee->ar_last_name }}
                                        </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="ar_last_name"
                                                   id="ar_last_name_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="ar_last_name"
                                                   id="ar_last_name_save"></i>
                                                <span id="ar_last_name_input" class="hidden">
                                            <input type="text" class="update_input"
                                                   value="{{ $employee->ar_last_name }}"
                                                   id="ar_last_name_update">
                                        </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.nationality') }} :
                                                <span id="nationality" class="">
                                                {{ @\App\Country::find($employee->nationality)->name }}
                                            </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="nationality"
                                                       id="nationality_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="nationality"
                                                       id="nationality_save"></i>
                                                    <span id="nationality_input" class="hidden">
                                                <select class="select2 form-control update_input"
                                                        id="nationality_update"
                                                        data-placeholder="{{ __('admin.nationality') }}">
                                                    <option></option>
                                                    @foreach(@\App\Country::get() as $country)
                                                        <option value="{{ $country->id }}"
                                                                @if($employee->nationality == $country->id) selected @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.religion') }} :
                                            <span id="religion" class="">
                                            {{ __('admin.'.$employee->religion) }}
                                        </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)
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
                                                <option @if($employee->religion == 'muslim') selected
                                                        @endif value="muslim">{{ trans('admin.muslim') }}</option>
                                                <option @if($employee->religion == 'christian') selected
                                                        @endif value="christian">{{ trans('admin.christian') }}</option>
                                                <option @if($employee->religion == 'jewish') selected
                                                        @endif value="jewish">{{ trans('admin.jewish') }}</option>
                                                <option @if($employee->religion == 'other') selected
                                                        @endif value="other">{{ trans('admin.other') }}</option>
                                            </select>
                                        </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.country') }} :
                                                <span id="country_id" class="">
                                                {{ @\App\Country::find($employee->country_id)->name }}
                                            </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


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
                                                                @if($employee->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.city') }} :
                                            <span id="city_id" class="">
                                            {{ @\App\City::find($employee->city_id)->name }}
                                        </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


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
                                                @foreach(@\App\City::where('country_id',$employee->country_id)->get() as $city)
                                                    <option value="{{ $city->id }}"
                                                            @if($employee->city_id == $city->id) selected @endif>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.birth_date') }} :
                                                <span id="birth_date" class="">
                                                {{ $employee->birth_date }}
                                            </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="birth_date"
                                                       id="birth_date_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="birth_date"
                                                       id="birth_date_save"></i>
                                                    <span id="birth_date_input" class="hidden">
                                                <input type="text" class="update_input datepicker"
                                                       value="{{ $employee->birth_date }}"
                                                       id="birth_date_update">
                                            </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.job_title') }} :
                                            <span id="job_title_id" class="">
                                            {{ @\App\JobTitle::find($employee->job_title_id)->name }}
                                        </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)


                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="job_title_id"
                                                   id="job_title_id_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="job_title_id"
                                                   id="job_title_id_save"></i>
                                                <span id="job_title_id_input" class="hidden">
                                                                <select class="select2 form-control update_input"
                                                                        id="job_title_id_update"
                                                                        data-placeholder="{{ __('admin.job_title') }}">
                                                                        <option></option>
                                                                    @foreach(@\App\JobTitle::all() as $job_title)
                                                                        <option value="{{ $job_title->id }}"
                                                                                @if($employee->job_title_id == $job_title->id) selected @endif>{{ $job_title->en_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.company') }} :
                                                <span id="company" class="">
                                                                {{ $employee->company }}
                                                                </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)
                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="company"
                                                       id="company_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="company"
                                                       id="company_save"></i>
                                                    <span id="company_input" class="hidden">
                                                                <input type="text" class="update_input"
                                                                       value="{{ $employee->company }}"
                                                                       id="company_update">
                                                                 </span>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.school') }} :
                                            <span id="school" class="">
                                                             {{ $employee->school }}
                                                            </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="school"
                                                   id="school_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="school"
                                                   id="school_save"></i>
                                                <span id="school_input" class="hidden">
                                                            <input type="text" class="update_input"
                                                                   value="{{ $employee->school }}"
                                                                   id="school_update">
                                                            </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6 border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.club') }} :
                                                <span id="club" class="">
                                                                {{ $employee->club }}
                                                                </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="club"
                                                   id="club_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="club"
                                                   id="club_save"></i>
                                                <span id="club_input" class="hidden">
                                                                 <input type="text" class="update_input"
                                                                        value="{{ $employee->club }}"
                                                                        id="club_update">
                                                                   </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.address') }} :
                                            <span id="address" class="">
                                                            {{ $employee->address }}
                                                             </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                            <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                               style="font-size: 1.2em; cursor: pointer" type="address"
                                               id="address_btn"></i>
                                            <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                               style="font-size: 1.2em; cursor: pointer" type="address"
                                               id="address_save"></i>
                                            <span id="address_input" class="hidden">
                                                            <input type="text" class="update_input"
                                                                   value="{{ $employee->address }}"
                                                                   id="address_update">
                                                            </span>
                                                @endif
                                        </div>
                                        <div class="col-md-6  border_right">
                                            <div class="head_margin_top">
                                                {{ trans('admin.facebook') }} :
                                                <span id="facebook" class="">
                                                            <a href="{{ 'https://www.facebook.com/'.$employee->facebook }}"
                                                               target="_blank"><b><i
                                                                            class="fa fa-facebook"
                                                                            aria-hidden="true"></i></b></a>
                                                                </span>
                                                @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                                <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style="font-size: 1.2em; cursor: pointer" type="facebook"
                                                   id="facebook_btn"></i>
                                                <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                   style=" font-size: 1.2em; cursor: pointer" type="facebook"
                                                   id="facebook_save"></i>
                                                <span id="facebook_input" class="hidden">
                                                                <input type="text" class="update_input"
                                                                       value="{{ $employee->facebook }}"
                                                                       id="facebook_update">
                                                                </span>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6 head_margin_top">
                                            {{ trans('admin.notes') }} :
                                            <span id="notes" class="">
                                                            {{ $employee->notes }}
                                                             </span>
                                            @if(auth()->user()->type == 'admin' || App\Employee::find(auth()->user()->employee_id)->is_hr == 1)

                                            <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                               style="font-size: 1.2em; cursor: pointer" type="notes"
                                               id="notes_btn"></i>
                                            <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                               style="font-size: 1.2em; cursor: pointer" type="notes"
                                               id="notes_save"></i>
                                            <span id="notes_input" class="hidden">
                                                            <input type="text" class="update_input"
                                                                   value="{{ $employee->notes }}"
                                                                   id="notes_update">
                                                            </span>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="salary" class="tab-pane fade">
                <h3>Salary</h3>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.salary') }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.basic_salary') }}</th>
                                    <th>{{ trans('admin.gross_salary') }}</th>
                                    <th>{{ trans('admin.net_salary') }}</th>
                                    <th>{{ trans('admin.full_salary') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(App\Salary::where('employee_id',$employee->id)->get() as $salary)
                                    <tr>
                                        <td>{{ @$salary->basic }}</td>
                                        <td>{{ @$salary->gross }}</td>
                                        <td>{{ @$salary->net }}</td>
                                        <td>{{ @$salary->full_salary }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


                <h3>Salary Update</h3>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.salary') }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">


                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>


                                    <th>{{ trans('admin.full_salary') }}</th>
                                    <th>{{ trans('admin.allowances') }}</th>
                                    <th>{{ trans('admin.deductions') }}</th>
                                    <th>{{ trans('admin.details') }}</th>
                                    <th>{{ trans('admin.status') }}</th>
                                    <th>{{ trans('admin.ordered_by') }}</th>
                                    <th>{{ trans('admin.ordered_time') }}</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach(App\SalaryDetail::where('employee_id',$employee->id)->get() as $salary_detail)
                                    <tr>


                                        <td>{{ @$salary_detail->full_salary }}</td>
                                        <td>{{ @$salary_detail->allowances }}</td>
                                        <td>{{ @$salary_detail->deductions }}</td>
                                        <td>{{ @$salary_detail->details }}</td>
                                        <td>{{ @$salary_detail->status }}</td>
                                        <td>{{ @$salary_detail->ordered_by }}</td>
                                        <td>{{ @$salary_detail->ordered_time}}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


            </div>
            <div id="time" class="tab-pane fade">


                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.vacation_request') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="box-body">
                                        <div calss="row">

                                            {!! Form::open(['method'=>'POST' , 'action'=> 'VacationController@requestVacation' ,'id'=>'Vacant']) !!}
                                            <input type="hidden" value="{{ $employee->id }}" name="employee_id"
                                                   id='employee_vac'>

                                            <div class=" text-center col-md-12" id="vacations">
                                                <br>
                                                <button type="button" class="btn btn-success btn-flat"
                                                        id="addVacation">{{ trans('admin.request_vacation') }}</button>
                                            </div>

                                            <div class='text-left col-md-12' id='upvacation'>
                                                {!! Form::submit('Upload Request ',['class'=>'btn btn-primary']) !!}
                                            </div>
                                            {!! Form::close() !!}


                                            <table class="table table-hover table-striped datatable">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('admin.reason') }}</th>
                                                    <th>{{ trans('admin.number_of_days') }}</th>
                                                    <th>{{ trans('admin.start_date') }}</th>
                                                    <th>{{ trans('admin.end_date') }}</th>
                                                    <th>{{ trans('admin.approval') }}</th>
                                                    <th>Vacation payment</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($vacations as $vacation)
                                                    <tr>
                                                        <td>{{ $vacation->reason }}</td>
                                                        <td>{{ $vacation->number_of_days }}</td>
                                                        <td>{{ $vacation->start_date}}</td>
                                                        <td>{{ $vacation->end_date}}</td>

                                                        <td>
                                                            @if($vacation->is_approved=='1'){{ trans('admin.approved') }}
                                                            @elseif($vacation->is_approved=='0') {{ trans('admin.disapproved') }}
                                                            @else
                                                                @if(\App\Employee::find(auth()->user()->employee_id)->is_hr == 1)
                                                                    <a data-toggle="modal"
                                                                       data-target="#approval{{ $vacation->id }}"
                                                                       class="btn btn-info btn-flat">{{ trans('admin.approval') }}</a>
                                                                    <a data-toggle="modal"
                                                                       data-target="#disapproval{{ $vacation->id }}"
                                                                       class="btn btn-danger btn-flat">{{ trans('admin.disapproval') }}</a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{$vacation->vacation_payment}}</td>
                                                    </tr>


                                                    <div id="approval{{ $vacation->id }}" class="modal fade"
                                                         role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">
                                                                        &times;
                                                                    </button>
                                                                    <h4 class="modal-title">{{ trans('admin.approve') . ' ' . trans('admin.vacation') }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>{{ trans('admin.approve') . ' ' . trans('admin.vacation') }}</p>
                                                                    {!! Form::open(['method'=>'POST', 'action'=> 'VacationController@approveVacation']) !!}
                                                                    <input type="hidden" value="{{ $employee->id }}"
                                                                           name="employee_id"
                                                                           id='employee_vacapprove'>
                                                                    <div class="row">
                                                                        <div class="col-xs-6">
                                                                            <div class="btn-group btn-group-vertical"
                                                                                 data-toggle="buttons">
                                                                                <label class="btn active">
                                                                                    <input type="radio"
                                                                                           name='groupOfDefaultRadios'
                                                                                           checked value="1"><i
                                                                                            class="fa fa-circle-o fa-2x"></i><i
                                                                                            class="fa fa-dot-circle-o fa-2x"></i>
                                                                                    <span>Annual vacation</span>
                                                                                </label>

                                                                                <label class="btn">
                                                                                    <input type="radio"
                                                                                           name='groupOfDefaultRadios'
                                                                                           value="2"><i
                                                                                            class="fa fa-circle-o fa-2x"></i><i
                                                                                            class="fa fa-dot-circle-o fa-2x"></i><span>Unscheduled vacation</span>
                                                                                </label>

                                                                                <label class="btn">
                                                                                    <input type="radio"
                                                                                           name='groupOfDefaultRadios'
                                                                                           value="3"><i
                                                                                            class="fa fa-circle-o fa-2x"></i><i
                                                                                            class="fa fa-dot-circle-o fa-2x"></i><span>Free</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="vacationstyle col-xs-6"> Annual
                                                                            vacation
                                                                            :{{$employee->annual_vacations}}</div>
                                                                        <div class="vacationstyle col-xs-6"> Unscheduled
                                                                            vacation:{{$employee->unscheduled_vacation}}</div>
                                                                        <div class="vacationstyle col-xs-6">Employee
                                                                            payment desire
                                                                            :{{$vacation->vacation_payment_desire}}</div>
                                                                        <div class="vacationstyle col-xs-12">Reason
                                                                            :{{$vacation->reason}}</div>


                                                                    </div>

                                                                    <div class="days"> Number of
                                                                        days:{{$vacation->number_of_days}}</div>


                                                                    {{--{{ Form::label('Annual Days', null, ['class' => 'control-label']) }}--}}
                                                                    {{--{!! Form::radio('deduced', $vacation->number_of_days) !!}--}}
                                                                    {{--{{ Form::label('For Free', null, ['class' => 'control-label']) }}--}}
                                                                    {{--{!! Form::radio('free', 0) !!}--}}
                                                                    {{-- {!! Form::close() !!}--}}
                                                                </div>

                                                                <div class="modal-footer">
                                                                    {{-- {!! Form::open(['method'=>'POST', 'action'=> 'VacationController@approveVacation']) !!}--}}
                                                                    <input type="hidden" value="{{ $vacation->id }}"
                                                                           name="vacation_id">
                                                                    <input type="hidden" value="{{ $employee->id }}"
                                                                           name="employee_id"
                                                                           id='employee_vacdisapprove'>

                                                                    <button type="button"
                                                                            class="btn btn-default btn-flat"
                                                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                                    <button type="submit"
                                                                            class="btn btn-info btn-flat">{{ trans('admin.approved') }}</button>

                                                                    {!! Form::close() !!}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div id="disapproval{{ $vacation->id }}" class="modal fade"
                                                         role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">
                                                                        &times;
                                                                    </button>

                                                                    <h4 class="modal-title">{{ trans('admin.disapproved') . ' ' . trans('admin.vacation') }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>{{ trans('admin.disapproved') . ' ' . trans('admin.vacation') }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    {!! Form::open(['method'=>'POST', 'action'=> 'VacationController@disApproveVacation']) !!}

                                                                    <input type="hidden" value="{{ $vacation->id }}"
                                                                           name="vacation_id">
                                                                    <input type="hidden" value="{{ $employee->id }}"
                                                                           name="employee_id" id='employee_vac'>
                                                                    <button type="button"
                                                                            class="btn btn-default btn-flat"
                                                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                                    <button type="submit"
                                                                            class="btn btn-danger btn-flat">{{ trans('admin.disapproved') }}</button>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


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
                <h3 class="box-title">{{ trans('admin.attendance') }}</h3>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.attendance') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-striped datatable">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('admin.status') }}</th>
                                            <th>{{ trans('admin.working_hours') }}</th>
                                            <th>{{ trans('admin.check_in') }}</th>
                                            <th>{{ trans('admin.check_out') }}</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($attends as $attend )
                                            <tr>
                                                <td>{{ @$attend->status}}</td>
                                                <td>{{ @$attend->hours}}</td>
                                                <td>{{ @$attend->check_in}}</td>
                                                <td>{{ @$attend->check_out}}</td>

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
            <div id="ER_Contact" class="tab-pane fade">
                <h3 class="box-title">{{ trans('admin.ER_Contacts') }}</h3>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.ER_Contacts') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-striped datatable">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.phone') }}</th>
                                            <th>{{ trans('admin.relation') }}</th>
                                            <th>{{ trans('admin.mail') }}</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(App\Contact::where('employee_id',$employee->id)->get() as $contact)
                                            <tr>
                                                <td>{{ @$contact->name }}</td>
                                                <td>{{ @$contact->phone }}</td>
                                                <td>{{ @$contact->relation }}</td>
                                                <td>{{ @$contact->mail }}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            {{--<h3 class="box-title">{{ trans('admin.ER_Contacts') }}</h3>--}}
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::open(['method'=>'POST' , 'action'=> 'EmployeeController@addErContact']) !!}
                                    <input type="hidden" value="{{ $employee->id }}" name="employee_id"
                                           id='employee_er'>

                                    <div class=" text-center col-md-12" id="contacts">
                                        <br>
                                        <button type="button" class="btn btn-success btn-flat"
                                                id="addContact">{{ trans('admin.add_contact') }}</button>
                                    </div>

                                    <div class='text-left col-md-12' id='uper'>
                                        {!! Form::submit('upload ER Contact ',['class'=>'btn btn-primary']) !!}
                                    </div>

                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="Custody" class="tab-pane fade">
                <h3 class="box-title">{{ trans('admin.Custody') }}</h3>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.Custody') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-striped datatable">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('admin.id') }}</th>
                                            <th>{{ trans('admin.title') }}</th>
                                            <th>{{ trans('admin.received_in') }}</th>
                                            <th>{{ trans('admin.qr_code') }}</th>
                                            <th>{{ trans('admin.status') }}</th>
                                            <th>{{ trans('admin.delivered') }}</th>
                                            <th>{{ trans('admin.delivered_in') }}</th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(App\Custody::where('employee_id',$employee->id)->get() as $custody)
                                            <tr>
                                                <td>{{ @$custody->id }}</td>
                                                <td>{{ @$custody->title}}</td>
                                                <td>{{@$custody->date}}</td>
                                                <td>{{ @$custody->qr_code}}</td>
                                                <td>{{ @$custody->status}}</td>
                                                <td>
                                                    @if($custody->delivered == '0')
                                                        @if(\App\Employee::find(auth()->user()->employee_id)->is_hr == 1)
                                                            <a data-toggle="modal"
                                                               data-target="#delivered{{ $custody->id }}"
                                                               class="btn btn-danger btn-flat">{{ trans('admin.delivered') }}</a>
                                                        @endif
                                                    @else {{ trans('admin.delivered') . ' ' . trans('admin.custody') }}
                                                    @endif
                                                </td>
                                                <td>{{@$custody->delivered_in}}</td>

                                                </td>
                                            </tr>
                                            <div id="delivered{{ $custody->id }}" class="modal fade"
                                                 role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                            <h4 class="modal-title">{{ trans('admin.delivered') . ' ' . trans('admin.custody') }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{  $custody->title }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            {!! Form::open(['method'=>'POST','action'=> ['CustodiesController@deliverCustody']]) !!}
                                                            <input type="hidden" value="{{ $custody->id }}"
                                                                   name="custody_id">

                                                            <div class='form-group col-md-6' id='allowances'>
                                                                {!! Form::label('delivered_in','Delivered In:') !!}
                                                                {!! Form::date('delivered_in', null,['class'=>'form-control']) !!}
                                                            </div>

                                                            <button type="button"
                                                                    class="btn btn-default btn-flat"
                                                                    data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                            <button type="submit"
                                                                    class="btn btn-danger btn-flat">{{ trans('admin.delivered') }}</button>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(\App\Employee::find(auth()->user()->employee_id)->is_hr == 1||auth()->user()->type == "admin")
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ trans('admin.upload_custody') }}</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        {!! Form::open(['method'=>'POST' , 'action'=> 'EmployeeController@addCustody']) !!}
                                        <input type="hidden" value="{{ $employee->id }}" name="employee_id"
                                               id="empCustody">

                                        <div class='form-group col-md-6' id='Title'>
                                            {!! Form::label('title',' Title:') !!}
                                            {!! Form::text('title',  null,['class'=>'form-control']) !!}
                                        </div>

                                        <div class='form-group col-md-6' id='QrCode'>
                                            {!! Form::label('qr_code',' QR Code:') !!}
                                            {!! Form::text('qr_code',  null,['class'=>'form-control']) !!}
                                        </div>

                                        <div class='form-group col-md-6' id='status'>
                                            {!! Form::label('status','Status:') !!}
                                            {!! Form::select('status', [''=>'Choose Options'] + array('new'=>'new' , 'normal'=>'normal', 'old'=>'old' )  , null,['class'=>'form-control'])  !!}
                                        </div>

                                        <div class='form-group col-md-6' id='date'>
                                            {!! Form::label('date','From Date:') !!}
                                            {!! Form::date('date',  null,['class'=>'form-control'])  !!}
                                        </div>

                                        <div class='text-left col-md-12' id='upcustody'>
                                            {!! Form::submit('upload custody',['class'=>'btn btn-primary']) !!}
                                        </div>

                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div id="MasterData" class="tab-pane fade">
                <h3 class="box-title">{{ trans('admin.Master_Data') }}</h3>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.Master_Data') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-striped datatable">
                                        <thead>
                                        <tr>

                                            <th>{{ trans('admin.image') }}</th>
                                            <th>{{ trans('admin.type') }}</th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(App\Photo::where('employee_id',$employee->id)->get() as $pic)
                                            <tr>

                                                <td><a href="{{ url('uploads/'.@$pic->image) }}">image</a></td>
                                                <td>{{ @$pic->code }}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <h3 class="box-title">{{ trans('admin.Master_Data') }}</h3>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.Master_Data') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::open(['method'=>'POST' , 'action'=> 'EmployeeController@imageCollector','file'=>'true' ,'enctype'=>'multipart/form-data']) !!}
                                    <input type="hidden" value="{{ $employee->id }}" name="employee_id" id="emp">
                                    <div class='form-group col-md-6' id='image'>
                                        {!! Form::label('image',' Image:') !!}
                                        {!! Form::file('image',  null,['class'=>'form-control']) !!}
                                    </div>

                                    <div class='form-group col-md-6 ' id='code'>
                                        {!! Form::label('code','Type:') !!}
                                        {!! Form::select('code', [''=>'Choose Options'] + array('id'=>'id' , 'cetification'=>'cetification', 'Militaty'=>'Military' ,'other'=>'other')  , null,['class'=>'form-control'])  !!}
                                    </div>

                                    <div class='text-left col-md-12' id='up'>
                                        {!! Form::submit('upload master Data ',['class'=>'btn btn-primary']) !!}
                                    </div>

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
             </div>
            </div>
            <div id="Rating" class="tab-pane fade">

                <h1>KPI Rating </h1>
                <h3 class="box-title">{{ trans('admin.rates') }}</h3>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('admin.rates') }}</h3>
                        </div>
                        @if(!$rated_employees )
                            <div class="alert alert-info alert-dismissible fade in" id="message">
                                There is no employeers to rate right now

                            </div>
                        @else
                            <div class="box-body">
                                <div class="row">
                                    <div class="container">
                                        <h2>Rated_employers</h2>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>FullName</th>
                                                <th>Work</th>
                                                <th>Apperance</th>
                                                <th>Target</th>
                                                <th>Ideas</th>
                                                <th>Efficient</th>
                                                <th>Rate</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                        @foreach($rated_employees as $rated_employee)

                                    </div> 
                                    
                                    <tr>
                                        <td> {{$rated_employee['en_first_name']}} {{$rated_employee['en_last_name']}} </td>
                                        <td>
                                            <div class="stars">

                                                <input class="star star-5" id="star-5-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-{{ $rated_employee['id'] }}" value="5">
                                                <label class="star star-5" for="star-5-{{ $rated_employee['id'] }}"
                                                       id="lstar-5"></label>
                                                <input class="star star-4" id="star-4-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-{{ $rated_employee['id'] }}" value="4">
                                                <label class="star star-4" for="star-4-{{ $rated_employee['id'] }}"
                                                       id="lstar-4"></label>
                                                <input class="star star-3" id="star-3-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-{{ $rated_employee['id'] }}" value="3">
                                                <label class="star star-3" for="star-3-{{ $rated_employee['id'] }}"
                                                       id="lstar-3"></label>
                                                <input class="star star-2" id="star-2-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-{{ $rated_employee['id'] }}" value="2">
                                                <label class="star star-2" for="star-2-{{ $rated_employee['id'] }}"
                                                       id="lstar-2"></label>
                                                <input class="star star-1" id="star-1-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-{{ $rated_employee['id'] }}" value="1">
                                                <label class="star star-1" for="star-1-{{ $rated_employee['id'] }}"
                                                       id="lstar-1"></label>

                                            </div>
                                        </td>

                                        <td>
                                            <div class="stars">

                                                <input class="star star-5" id="star-5-1-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-1-{{ $rated_employee['id'] }}" value="5">
                                                <label class="star star-5" for="star-5-1-{{ $rated_employee['id'] }}"
                                                       id="lstar-5"></label>
                                                <input class="star star-4" id="star-4-1-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-1-{{ $rated_employee['id'] }}" value="4">
                                                <label class="star star-4" for="star-4-1-{{ $rated_employee['id'] }}"
                                                       id="lstar-4"></label>
                                                <input class="star star-3" id="star-3-1-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-1-{{ $rated_employee['id'] }}" value="3">
                                                <label class="star star-3" for="star-3-1-{{ $rated_employee['id'] }}"
                                                       id="lstar-3"></label>
                                                <input class="star star-2" id="star-2-1-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-1-{{ $rated_employee['id'] }}" value="2">
                                                <label class="star star-2" for="star-2-1-{{ $rated_employee['id'] }}"
                                                       id="lstar-2"></label>
                                                <input class="star star-1" id="star-1-1-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-1-{{ $rated_employee['id'] }}" value="1">
                                                <label class="star star-1" for="star-1-1-{{ $rated_employee['id'] }}"
                                                       id="lstar-1"></label>

                                            </div>
                                        </td>
                                        <td>
                                            <div class="stars">

                                                <input class="star star-5" id="star-5-2-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-2-{{ $rated_employee['id'] }}" value="5">
                                                <label class="star star-5" for="star-5-2-{{ $rated_employee['id'] }}"
                                                       id="lstar-5"></label>
                                                <input class="star star-4" id="star-4-2-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-2-{{ $rated_employee['id'] }}" value="4">
                                                <label class="star star-4" for="star-4-2-{{ $rated_employee['id'] }}"
                                                       id="lstar-4"></label>
                                                <input class="star star-3" id="star-3-2-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-2-{{ $rated_employee['id'] }}" value="3">
                                                <label class="star star-3" for="star-3-2-{{ $rated_employee['id'] }}"
                                                       id="lstar-3"></label>
                                                <input class="star star-2" id="star-2-2-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-2-{{ $rated_employee['id'] }}" value="2">
                                                <label class="star star-2" for="star-2-2-{{ $rated_employee['id'] }}"
                                                       id="lstar-2"></label>
                                                <input class="star star-1" id="star-1-2-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-2-{{ $rated_employee['id'] }}" value="1">
                                                <label class="star star-1" for="star-1-2-{{ $rated_employee['id'] }}"
                                                       id="lstar-1"></label>

                                            </div>
                                        </td>
                                        <td>
                                            <div class="stars">

                                                <input class="star star-5" id="star-5-3-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-3-{{ $rated_employee['id'] }}" value="5">
                                                <label class="star star-5" for="star-5-3-{{ $rated_employee['id'] }}"
                                                       id="lstar-5"></label>
                                                <input class="star star-4" id="star-4-3-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-3-{{ $rated_employee['id'] }}" value="4">
                                                <label class="star star-4" for="star-4-3-{{ $rated_employee['id'] }}"
                                                       id="lstar-4"></label>
                                                <input class="star star-3" id="star-3-3-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-3-{{ $rated_employee['id'] }}" value="3">
                                                <label class="star star-3" for="star-3-3-{{ $rated_employee['id'] }}"
                                                       id="lstar-3"></label>
                                                <input class="star star-2" id="star-2-3-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-3-{{ $rated_employee['id'] }}" value="2">
                                                <label class="star star-2" for="star-2-3-{{ $rated_employee['id'] }}"
                                                       id="lstar-2"></label>
                                                <input class="star star-1" id="star-1-3-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-3-{{ $rated_employee['id'] }}" value="1">
                                                <label class="star star-1" for="star-1-3-{{ $rated_employee['id'] }}"
                                                       id="lstar-1"></label>

                                            </div>
                                        </td>

                                        <td>
                                            <div class="stars">

                                                <input class="star star-5" id="star-5-4-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-4-{{ $rated_employee['id'] }}" value="5">
                                                <label class="star star-5" for="star-5-4-{{ $rated_employee['id'] }}"
                                                       id="lstar-5"></label>
                                                <input class="star star-4" id="star-4-4-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-4-{{ $rated_employee['id'] }}" value="4">
                                                <label class="star star-4" for="star-4-4-{{ $rated_employee['id'] }}"
                                                       id="lstar-4"></label>
                                                <input class="star star-3" id="star-3-4-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-4-{{ $rated_employee['id'] }}" value="3">
                                                <label class="star star-3" for="star-3-4-{{ $rated_employee['id'] }}"
                                                       id="lstar-3"></label>
                                                <input class="star star-2" id="star-2-4-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-4-{{ $rated_employee['id'] }}" value="2">
                                                <label class="star star-2" for="star-2-4-{{ $rated_employee['id'] }}"
                                                       id="lstar-2"></label>
                                                <input class="star star-1" id="star-1-4-{{ $rated_employee['id'] }}"
                                                       type="radio" name="star-4-{{ $rated_employee['id'] }}" value="1">
                                                <label class="star star-1" for="star-1-4-{{ $rated_employee['id'] }}"
                                                       id="lstar-1"></label>

                                            </div>
                                        </td>
                                        <td id="btn-submit">
                                            <input type="hidden" value="{{$rated_employee['id']}}"
                                                   name="rated_employee_id" id='employee_er'>
                                            <input type="hidden" value="{{$employee_id}}" name="employee_id">
                                            <button type="button" class="btn btn-info rate-submit" value="submit"
                                                    emp="{{$employee_id}}" rated="{{$rated_employee['id']}}">rate
                                            </button>

                                        </td>
                                    </tr>
                                    
                                    @endforeach

                                    </tbody>
                                    </table>
                                    <div class="alert alert-success alert-dismissible fade in" style="display:none"
                                         id="msg"> Rate saved successfully
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
    </div>
    </div>


    </div>
    @endif



@endsection
@section('js')
    <script>
        $(document).on('change', '#country_id', function () {
            var id = $(this).val();
            var location = $('#location').val();
            var unit_type = $('#unit_type').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_cities')}}",
                type: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#cities').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '#up', function () {

            var id = $('#emp').val();
            var image = $('#image').val();
            var code = $('#code').val();
            var _token = '{{csrf_token()}}';
            $.ajax({
                url: "{{url(adminPath().'/image_collector')}}",
                type: 'post',
                dataType: 'json',
                data: {
                    id: id,
                    image: image,
                    code: code,
                    _token: _token
                },
                success: function (data) {

                }
            })
        })

    </script>

    <script>
        $(document).on('click', '#upsal', function () {

            var id = $('#emp').val();
            var allowances = $('#allowances').val();
            var special_increase = $('#special_increase').val();
            var annual_increase = $('#annual_increase').val();
            var basic_salary = $('#basic_salary').val();
            var _token = '{{csrf_token()}}';

            $.ajax({
                url: "{{url(adminPath().'/update-salary-notes')}}",
                type: 'post',
                dataType: 'json',
                data: {
                    id: id,
                    allowances: allowances,
                    special_increase: special_increase,
                    annual_increase: annual_increase,
                    basic_salary: basic_salary,
                    _token: _token
                },
                success: function (data) {
                    alert(yub);
                }
            })
        })


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
        $('.datepicker1').datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
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
            var id = '{{ $employee->id }}';
            $.ajax({
                url: "{{ url(adminPath().'/update_employee')}}",
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.7.3/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.7.3/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor();
    </script>

    <script>
        function openNav() {

            document.getElementById("mySidenav").style.width = "320px";
            document.getElementById("main").style.marginLeft = "320px";
            document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        var closeNav = function () {

            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            document.body.style.backgroundColor = "white";
        }

    </script>


    <script>
        var y = 1;
        $(document).on('click', '#addContact', function () {
            console.log('clicked');
            $('#contacts').append(
                '<div class="well col-md-12" style="" id="removeContact' + y + '">' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.name") }}</label>' +
                '<input type="text" name="contact_name[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.name") }}" required>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.relation") }}</label>' +
                '<input type="text" name="contact_relation[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.relation") }}">' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.phone") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="contact_phone[' + y + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.phone") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][whatsapp]" value="1" class="minimal"' +
                'style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                'style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][sms]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][sms]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][viber]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][viber]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon addContactPhone" count="' + y + '" style="cursor: pointer">' +
                '<a class="fa fa-plus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<span id="otherContactPhones' + y + '"></span>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.email") }}</label>' +
                '<div class="input-group">' +
                '<input type="email" name="contact_email[' + y + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.email") !!}">' +
                '<span class="input-group-addon addContactEmail" count="' + y + '" style="cursor: pointer">' +
                '<a class="fa fa-plus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<span id="otherContactEmails' + y + '"></span>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.job_title") }}</label>' +
                '<select name="contact_title_id[' + y + ']" class="form-control select2"' +
                'data-placeholder="{!! trans("admin.job_title") !!}">' +
                '<option></option>' +
                '@foreach(@\App\Title::all() as $titl)' +
                '<option value="{{ $titl->id }}">{{ $titl->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.nationality") }}</label>' +
                '<select name="contact_nationality[' + y + ']" class="form-control select2"' +
                'data-placeholder="{!! trans("admin.nationality") !!}">' +
                '<option></option>' +
                '@foreach(@\App\Country::all() as $country)' +
                '<option value="{{ $country->id }}">{{ $country->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="text-center col-md-12">' +
                '<button type="button" class="btn btn-danger btn-flat removeContact" num="' + y + '">' +
                '{{ trans("admin.remove") }}</button>' +
                '</div>' +
                '</div>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            y++;
            $('.select2').select2()
        });

        $(document).on('click', '.removeContact', function () {
            var num = $(this).attr('num');
            $('#removeContact' + num).remove();
        })
    </script>

    <script>
        var z = 1;
        $(document).on('click', '.addContactPhone', function () {
            var count = $(this).attr('count');
            $('#otherContactPhones' + count).append(
                '<div class="form-group col-md-6" id="otherContactPhone' + z + '">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="contact_other_phones[' + count + '][' + z + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.other_phones") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][whatsapp]" value="1" class="minimal"' +
                'style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                'style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][sms]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][sms]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][viber]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][viber]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon removeContactPhone" count="' + z + '" style="cursor: pointer">' +
                '<a class="fa fa-minus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            z++
        });

        $(document).on('click', '.removeContactPhone', function () {
            var contactPhone = $(this).attr('count');
            $('#otherContactPhone' + contactPhone).remove();
        })
    </script>
    <script>
        var i = 1;
        $(document).on('click', '.addContactEmail', function () {
            var count = $(this).attr('count');
            $('#otherContactEmails' + count).append('<div class="form-group col-md-6" id="otherContactEmail' + i + '">' +
                '<label>{{ trans("admin.other_emails") }}</label>' +
                '<div class="input-group">' +
                '<input type="email" name="contact_other_emails[' + count + '][' + i + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.other_emails") !!}">' +
                '<span class="input-group-addon removeContactEmail" count="' + i + '" style="cursor: pointer">' +
                '<a class="fa fa-minus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>');
            i++
        });

        $(document).on('click', '.removeContactEmail', function () {
            var contactEmail = $(this).attr('count');
            $('#otherContactEmail' + contactEmail).remove();
        })
    </script>

    <script>
        var y = 1;
        $(document).on('click', '#addVacation', function () {
            console.log('clicked');
            $('#vacations').append(
                '<div class="well col-md-12" style="" id="removeContact' + y + '">' +

                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.number_of_days") }}</label>' +
                '<input type="number" name="number_of_days[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.number_of_days") }}" required>' +
                '</div>' +

                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.reason") }}</label>' +
                '<input type="text" name="reason[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.reason") }}" required>' +
                '</div>' +

                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.start_date") }}</label>' +
                '<input type="text" name="start_date[' + y + ']" class="form-control datepicker"' +
                'placeholder="{{ trans("admin.start_date") }}">' +
                '</div>' +

                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.end_date") }}</label>' +
                '<input type="text" name="end_date[' + y + ']" class="form-control datepicker"' +
                'placeholder="{{ trans("admin.end_date") }}">' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-xs-12">' +
                '<div class="btn-group" data-toggle="buttons">' +
                '<label class="btn active">' +
                '<input type="radio" name="vacation_pay[' + y + ']" checked value="1"><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span>Annual Vacation</span>' +
                '</label>' +
                '<label class="btn">' +
                '<input type="radio" name="vacation_pay[' + y + ']" value="2"><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span>Unscheduled Vacation</span>' +
                '</label>' +
                '<label class="btn">' +
                '<input type="radio" name="vacation_pay[' + y + ']" value="3"><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> Free</span>' +
                '</label>' +
                '</div>' +
                
                 '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.notes") }}</label>' +
                '<input type="text" name="notes[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.notes") }}" required>' +
                '</div>' +

                '<div class="text-center col-md-12">' +
                '<button type="button" class="btn btn-danger btn-flat removeVac" num="' + y + '">' +
                '{{ trans("admin.remove") }}</button>' +
                '</div>' +


                '</div>' +


                '<script>$(".datepicker").datepicker({autoclose: true, format: "mm/dd/yyyy",});<\/script>'
            );
            y++;
        });

        $(document).on('click', '.removeVac', function () {
            var num = $(this).attr('num');
            $('#removeContact' + num).remove();
        })
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'days per mounth'],
                ['Work', {{$rated_work}}],
                ['Efficient', {{$rated_effeciant}}],
                ['Ideas', {{$rated_ideas}}],
                ['Appearance',{{$rated_apperance}}],
                ['target',{{$rated_target}}]
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {'width': 300, 'height': 200};

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
    <script>

        $(document).on('click', '.star', function () {
            var id = $(this).attr('id');
            console.log(id);

            /* input.star:checked ~ label.star:before { 
  content: '\f005';
  color: #FD4;
  transition: all .25s;
} */
        });


    </script>
    {{--<script>--}}
    {{--var filter_show = 0;--}}
    {{--$('.filter-icon').on('click mouseover', function () {--}}
    {{--if (!filter_show) {--}}
    {{--$('.filter').css('right', 0);--}}
    {{--$('.filter-icon').css('right', '500px');--}}
    {{--filter_show = 1;--}}
    {{--} else {--}}
    {{--$('.filter').css('right', '-500px');--}}
    {{--$('.filter-icon').css('right', '0');--}}
    {{--filter_show = 0;--}}
    {{--}--}}
    {{--});--}}
    {{--</script>--}}
    {{--<script>--}}
    {{--var y = 1;--}}
    {{--$(document).on('click', '#addVacation', function () {--}}
    {{--console.log('clicked');--}}
    {{--$('#vacations').append(--}}
    {{--'<div class="well col-md-12" style="" id="removeContact' + y + '">' +--}}

    {{--'<div class="form-group col-md-6">' +--}}
    {{--'<label>{{ trans("admin.en_name") }}</label>' +--}}
    {{--'<input type="text" name="en_name[' + y + ']" class="form-control"' +--}}
    {{--'placeholder="{{ trans("admin.en_name") }}" required>' +--}}
    {{--'</div>' +--}}

    {{--'<div class="form-group col-md-6">' +--}}
    {{--'<label>{{ trans("admin.ar_name") }}</label>' +--}}
    {{--'<input type="text" name="ar_name[' + y + ']" class="form-control"' +--}}
    {{--'placeholder="{{ trans("admin.ar_name") }}" required>' +--}}
    {{--'</div>' +--}}

    {{--'<div class="form-group col-md-6">' +--}}
    {{--'<label>{{ trans("admin.number_of_days") }}</label>' +--}}
    {{--'<input type="number" name="annual_days[' + y + ']" class="form-control"' +--}}
    {{--'placeholder="{{ trans("admin.annual_days") }}" required>' +--}}
    {{--'</div>' +--}}

    {{--'<div class="form-group col-md-6">' +--}}
    {{--'<label>{{ trans("admin.type") }}</label>' +--}}
    {{--'<select class="form-control" name="type[' + y + ']" >' +--}}
    {{--'<option value="occasional">{{ __('admin.occasional') }}</option>' +--}}
    {{--'<option value="annual">{{ __('admin.annual') }}</option>' +--}}
    {{--'</select>' +--}}
    {{--'</div>' +--}}

    {{--'<div class="form-group col-md-6">' +--}}
    {{--'<label>{{ trans("admin.start_date") }}</label>' +--}}
    {{--'<input type="text" name="start_date[' + y + ']" class="form-control datepicker"' +--}}
    {{--'placeholder="{{ trans("admin.start_date") }}">' +--}}
    {{--'</div>' +--}}

    {{--'<div class="form-group col-md-6">' +--}}
    {{--'<label>{{ trans("admin.end_date") }}</label>' +--}}
    {{--'<input type="text" name="end_date[' + y + ']" class="form-control datepicker"' +--}}
    {{--'placeholder="{{ trans("admin.end_date") }}">' +--}}
    {{--'</div>' +--}}

    {{--'<div class="text-center col-md-12">' +--}}
    {{--'<button type="button" class="btn btn-danger btn-flat removeVac" num="' + y + '">' +--}}
    {{--'{{ trans("admin.remove") }}</button>' +--}}
    {{--'</div>' +--}}
    {{--'</div>' +--}}

    {{--'<script>$(".datepicker").datepicker({autoclose: true, format: "mm/dd/yyyy",});<\/script>'--}}
    {{--);--}}
    {{--y++;--}}
    {{--});--}}

    {{--$(document).on('click', '.removeVac', function () {--}}
    {{--var num = $(this).attr('num');--}}
    {{--$('#removeContact' + num).remove();--}}
    {{--})--}}
    {{--</script>--}}
    <script>

        $(document).on('click', ".rate-submit", function (event) {
            event.preventDefault();
            var butt = $(this);
            var id = $(this).attr('emp');
            var rated = $(this).attr('rated');

            var rated_emp1 = $("input:radio[name ='star-" + rated + "']:checked").val();
            var rated_emp2 = $("input:radio[name ='star-1-" + rated + "']:checked").val();
            var rated_emp3 = $("input:radio[name ='star-2-" + rated + "']:checked").val();
            var rated_emp4 = $("input:radio[name ='star-3-" + rated + "']:checked").val();
            var rated_emp5 = $("input:radio[name ='star-4-" + rated + "']:checked").val();
            console.log(id, rated, rated_emp1, rated_emp2, rated_emp3, rated_emp4, rated_emp5);
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: '{!! url(adminPath() . "/rate_employee") !!}',
                method: "POST",
                data: {
                    id: id,
                    _token: _token,
                    rated: rated,
                    rated_work: rated_emp1,
                    rated_apperance: rated_emp2,
                    rated_target: rated_emp3,
                    rated_ideas: rated_emp4,
                    rated_efficient: rated_emp5
                },
                success: function (response) {
                    butt.attr("disabled", true);
                    $("#msg").show();
                    setTimeout(function () {
                        $("#msg").hide();
                    }, 5000);
                    console.log(response);

                },
                error: function (err) {
                    console.log(err)
                }
            });
        });
    </script>


@stop