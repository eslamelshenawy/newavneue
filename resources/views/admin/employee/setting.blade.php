@extends('admin.index')
@section('content')


    @include('admin.employee.hr_nav')

    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">Settings</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">


                        <div class="row ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="box-header with-border">


                                        </div>
                                        <div class="box-body">
                                            <div class="row">

                                                <div class="col-md-6 head_margin_top">

                                                    {{ trans('admin.working_days') }} :
                                                    <span id="working_days" class="">
                                                {{$hr_setting->where('name','=','working_days')->pluck('value')->first() }}
                                                            </span>
                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="working_days"
                                                       id="working_days_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="working_days"
                                                       id="working_days_save"></i>
                                                    <span id="working_days_input" class="hidden">
                                                        <select class="select2 form-control update_input" value="{{$hr_setting->where('name','=','working_days')->pluck('value')->first() }}"
                                                                id="working_days_update"
                                                                data-placeholder="{{ __('admin.working_days') }}">
                                                             <option></option>
                                                            @for ($x = 15; $x <= 30; $x++) {
                                                            <option>{{$x}}</option>
                                                            @endfor
                                                        </select>
                                                    </span>
                                                </div>


                                                <div class="col-md-6 head_margin_top">
                                                    {{ trans('admin.working_hours') }} :
                                                    <span id="working_hours" class="">
                                                        {{$hr_setting->where('name','=','working_hours')->pluck('value')->first() }}
                                                        </span>
                                                    <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="working_hours"
                                                       id="working_hours_btn"></i>
                                                    <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                       style="font-size: 1.2em; cursor: pointer" type="working_hours"
                                                       id="working_hours_save"></i>
                                                    <span id="working_hours_input" class="hidden">
                                                        <select class="select2 form-control update_input" value="{{$hr_setting->where('name','=','working_hours')->pluck('value')->first() }}"
                                                                id="working_hours_update"
                                                                data-placeholder="{{ __('admin.working_hours') }}">
                                                             <option></option>
                                                            @for ($x = 1; $x <= 15; $x++) {
                                                            <option>{{$x}}</option>
                                                            @endfor
                                                        </select>
                                                         </span>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.start_work') }} :
                                                        <span id="start_work" class="">
                                                            {{$hr_setting->where('name','=','start_work')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="start_work"
                                                           id="start_work_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="start_work"
                                                           id="start_work_save"></i>
                                                        <span id="start_work_input" class="hidden">
                                                        <input type="time" class="update_input" value="{{$hr_setting->where('name','=','start_work')->pluck('value')->first() }}"
                                                               id="start_work_update">
                                                         </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.end_work') }} :
                                                        <span id="end_work" class="">
                                                            {{$hr_setting->where('name','=','end_work')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="end_work"
                                                           id="endWork_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="end_work"
                                                           id="end_work_save"></i>
                                                        <span id="end_work_input" class="hidden">
                                                        <input type="time" class="update_input" value="{{$hr_setting->where('name','=','end_work')->pluck('value')->first() }}"
                                                               id="end_work_update">
                                                         </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.weekend') }} :
                                                        <span id="weekend" class="">
                                                          {{  $hr_setting->where('name','=','weekend')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="weekend"
                                                           id="weekend_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="weekend"
                                                           id="weekend_save"></i>
                                                        <span id="weekend_input" class="hidden">
                                                        {{--<input type="text" class="update_input"--}}
                                                               {{--id="weekend_update">--}}
                                                            <select multiple class="update_input select2" name="weekend"
                                                                    style="width: 100%"  id="weekend_update" value="{{$hr_setting->where('name','=','weekend')->pluck('value')->first() }}"
                                                                    data-placeholder="{{ trans('admin.weekend') }}">
                                                                    <option></option>
                                                                    <option value="Saturday">Saturday</option>
                                                                    <option value="Sunday">Sunday</option>
                                                                    <option value="Monday">Monday</option>
                                                                    <option value="Tuesday">Tuesday</option>
                                                                    <option value="Wednesday">Wednesday</option>
                                                                    <option value="Thursday">Thursday</option>
                                                                    <option value="Friday">Friday</option>
                                                             </select>

                                                         </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.annual_increase') }} :
                                                        <span id="annual_increase" class="">
                                                            {{$hr_setting->where('name','=','annual_increase')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="annual_increase"
                                                           id="annual_increase_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="annual_increase"
                                                           id="annual_increase_save"></i>
                                                        <span id="annual_increase_input" class="hidden">
                                                        <input type="text" class="update_input" value="{{$hr_setting->where('name','=','annual_increase')->pluck('value')->first() }}"
                                                               id="annual_increase_update">
                                                         </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.special_reward') }} :
                                                        <span id="special_reward" class="">
                                                            {{$hr_setting->where('name','=','special_reward')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="special_reward"
                                                           id="special_reward_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="special_reward"
                                                           id="special_reward_save"></i>
                                                        <span id="special_reward_input" class="hidden">
                                                        <input type="text" class="update_input" value="{{$hr_setting->where('name','=','special_reward')->pluck('value')->first() }}"
                                                               id="special_reward_update">
                                                         </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.annual_vacation') }} :
                                                        <span id="annual_vacation" class="">
                                                            {{$hr_setting->where('name','=','annual_vacation')->pluck('value')->first() }}
                                                    </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="annual_vacation"
                                                           id="annual_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="annual_vacation"
                                                           id="annual_vacation_save"></i>
                                                        <span id="annual_vacation_input" class="hidden">
                                                    <input type="text" class="update_input" value="{{$hr_setting->where('name','=','annual_vacation')->pluck('value')->first() }}"
                                                           id="annual_vacation_update">
                                                    </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.unscheduled_vacation') }} :
                                                        <span id="unscheduled_vacation" class="">
                                                            {{$hr_setting->where('name','=','unscheduled_vacation')->pluck('value')->first() }}
                                                     </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="unscheduled_vacation"
                                                           id="unscheduled_vacation_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer"
                                                           type="unscheduled_vacation"
                                                           id="unscheduled_vacation_save"></i>
                                                        <span id="unscheduled_vacation_input" class="hidden">
                                                         <input type="text" class="update_input" value="{{$hr_setting->where('name','=','unscheduled_vacation')->pluck('value')->first() }}"
                                                                id="unscheduled_vacation_update">
                                                         </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.tax') }} :
                                                        <span id="tax" class="">
                                                            {{$hr_setting->where('name','=','tax')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="tax"
                                                           id="tax_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="tax"
                                                           id="tax_save"></i>
                                                        <span id="tax_input" class="hidden">
                                                    <input type="text" class="update_input" id="tax_update" value="{{$hr_setting->where('name','=','tax')->pluck('value')->first() }}">
                                                    </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.punishment') }} :
                                                        <span id="punishment" class="">
                                                            {{$hr_setting->where('name','=','punishment')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="punishment"
                                                           id="punishment_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="punishment"
                                                           id="punishment_save"></i>
                                                        <span id="punishment_input" class="hidden">
                                                    <input type="number" class="update_input" id="punishment_update" value="{{$hr_setting->where('name','=','punishment')->pluck('value')->first() }}">
                                                    </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 head_margin_top">
                                                    <div class="head_margin_top">
                                                        {{ trans('admin.overtime') }} :
                                                        <span id="overtime" class="">
                                                            {{$hr_setting->where('name','=','overtime')->pluck('value')->first() }}
                                                        </span>
                                                        <i class="fa fa-edit update @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="overtime"
                                                           id="overtime_btn"></i>
                                                        <i class="fa fa-check save hidden @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                                                           style="font-size: 1.2em; cursor: pointer" type="overtime"
                                                           id="overtime_save"></i>
                                                        <span id="overtime_input" class="hidden">
                                                    <input type="text" class="update_input" id="overtime_update" value="{{$hr_setting->where('name','=','overtime')->pluck('value')->first() }}">
                                                    </span>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 head_margin_top">
                                                        <br>
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
                                    <h3 class="box-title">{{ trans('admin.gross_salary') }}</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">


                                    {!! Form::open(['method'=>'POST' , 'action'=> 'EmployeeController@salaryNotes']) !!}

                                    <div class='form-group col-md-12' id='employee'>
                                        {!! Form::label('employee_id',' Choose Employee:') !!}
                                        <select class="select2 form-control update_input" id="gross_update"
                                                data-placeholder="{{ __('admin.employee') }}" name="employee_id">
                                            <option></option>
                                            @foreach(@\App\Employee::get() as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->en_first_name .' '.$employee->en_last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" value="{{ $employee->salary}}" name="basic_salary" id='bsal'>

                                    <div class='form-group col-md-6' id='by'>
                                        {!! Form::label('by','Ordered by:') !!}
                                        {!! Form::text('by', null,['class'=>'form-control']) !!}
                                    </div>

                                    <div class='form-group col-md-6' id='allowances'>
                                        {!! Form::label('allowances','Allowances:') !!}
                                        {!! Form::number('allowances', 0,['class'=>'form-control']) !!}
                                    </div>
                                    <div class='form-group col-md-6' id='date'>
                                        {!! Form::label('date','Date:') !!}
                                        {!! Form::date('date', null,['class'=>'form-control']) !!}
                                    </div>


                                    <div class='form-group col-md-6' id='details'>
                                        {!! Form::label('details','Details:') !!}
                                        {!! Form::text('details', null,['class'=>'form-control']) !!}
                                    </div>


                                    <div class='text-left col-md-12' id='upsal'>
                                        {!! Form::submit('update salary',['class'=>'btn btn-primary']) !!}
                                    </div>

                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="actions">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ trans('admin.deduction') }}</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">


                                    {!! Form::open(['method'=>'POST' , 'action'=> 'EmployeeController@salaryNotes']) !!}

                                    <div class='form-group col-md-12' id='employee'>
                                        {!! Form::label('rated_employee_id',' Choose Employee:') !!}
                                        <select class="select2 form-control update_input" id="nationality_update" name="employee_id"
                                                data-placeholder="{{ __('admin.employee') }}">
                                            <option></option>
                                            @foreach(@\App\Employee::get() as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->en_first_name .' '.$employee->en_last_name}}</option>
                                            @endforeach
                                        </select></div>

                                    <input type="hidden" value="{{ $employee->salary}}" name="basic_salary" id='sal'>

                                    <div class='form-group col-md-6' id='by'>
                                        {!! Form::label('by','Ordered by:') !!}
                                        {!! Form::text('by', null,['class'=>'form-control']) !!}
                                    </div>


                                    <div class='form-group col-md-6' id='ded'>
                                        {!! Form::label('deductions','Deductions:') !!}
                                        {!! Form::number('deductions',0 ,['class'=>'form-control']) !!}
                                    </div>

                                    <div class='form-group col-md-6' id='date'>
                                        {!! Form::label('date','Date:') !!}
                                        {!! Form::date('date', null,['class'=>'form-control']) !!}
                                    </div>

                                    <div class='form-group col-md-6' id='details'>
                                        {!! Form::label('details','Details:') !!}
                                        {!! Form::text('details', null,['class'=>'form-control']) !!}
                                    </div>

                                    <div class='text-left col-md-12' id='upsalary'>
                                        {!! Form::submit('update salary',['class'=>'btn btn-primary']) !!}
                                    </div>

                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ trans('admin.national_vacations') }}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">


                                    <form action="{{ url(adminPath().'/vacations') }}" method="post"
                                          enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                                            <label>{{ trans('admin.en_name') }}</label>
                                            <input type="text" name="en_name" class="form-control"
                                                   value="{{ old('en_name') }}"
                                                   placeholder="{!! trans('admin.en_name') !!}">
                                        </div>

                                        <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                                            <label>{{ trans('admin.ar_name') }}</label>
                                            <input type="text" name="ar_name" class="form-control"
                                                   value="{{ old('ar_name') }}"
                                                   placeholder="{!! trans('admin.ar_name') !!}">
                                        </div>
                                        <div class="form-group col-md-6 @if($errors->has('number_of_days')) has-error @endif">
                                            <label>{{ __('admin.number_of_days') }}</label>
                                            <input type="text" name="number_of_days" class="form-control"
                                                   value="{{ old('number_of_days') }}"
                                                   placeholder="{!! trans('admin.number_of_days') !!}">
                                        </div>
                                        <div class="form-group col-md-6 @if($errors->has('type')) has-error @endif">
                                            <label>{{ __('admin.type') }}</label>
                                            <select class="form-control" name="type">
                                                <option value="occasional">{{ __('admin.occasional') }}</option>
                                                <option value="annual">{{ __('admin.annual') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 @if($errors->has('start_date')) has-error @endif">
                                            <label>{{ trans('admin.start_date') }}</label>
                                            <input type="text" name="start_date" class="form-control datepicker"
                                                   value="{{ old('start_date') }}"
                                                   placeholder="{!! trans('admin.ar_name') !!}">
                                        </div>
                                        <div class="form-group col-md-6 @if($errors->has('end_date')) has-error @endif">
                                            <label>{{ trans('admin.end_date') }}</label>
                                            <input type="text" name="end_date" class="form-control datepicker"
                                                   value="{{ old('end_date') }}"
                                                   placeholder="{!! trans('admin.ar_name') !!}">
                                        </div>


                                        <button type="submit"
                                                class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ trans('admin.national_vacations') }}</h3>

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
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.days') }}</th>
                                            <th>{{ trans('admin.start_date') }}</th>
                                            <th>{{ trans('admin.end_date') }}</th>
                                            <th>{{ trans('admin.edit') }}</th>
                                            <th>{{ trans('admin.delete') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vacations as $vacation)
                                            <tr>
                                                <td>{{ $vacation->{app()->getLocale().'_name'} }}</td>
                                                <td>{{ $vacation->number_of_days }}</td>
                                                <td>{{ $vacation->start_date }}</td>
                                                <td>{{ $vacation->end_date }}</td>
                                                <td><a class="btn btn-warning"
                                                       href="{{ url(adminPath().'/vacations/'.$vacation->id.'/edit') }}">{{ __('admin.edit') }}</a>
                                                </td>
                                                <td><a data-toggle="modal" data-target="#delete{{ $vacation->id }}"
                                                       class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a>
                                                </td>
                                            </tr>
                                            <div id="delete{{ $vacation->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                            <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ trans('admin.delete') . ' ' . $vacation->{app()->getLocale().'_name'} }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            {!! Form::open(['method'=>'DELETE','route'=>['vacations.destroy',$vacation->id]]) !!}
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ trans('admin.attendance') }}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">

                                    <style>
                                        .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
                                        .bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
                                        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
                                    </style>
                                    <div class="col-md-6">


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
@endsection
@section('js')
    <script>
        $('.datepicker').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            viewMode: "years",
            minViewMode: "years",
        });
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
//            console.log(type);
            var value = $('#' + type + '_update').val();
            $.ajax({
                url: "{{ url(adminPath().'/update-settings')}}",
                method: 'post',
                dataType: 'json',
                data: {type: type, value: value, _token: '{{ csrf_token() }}'},
                beforeSend: function () {
                    $('#' + type + '_save').addClass('fa-spin');
                },
                success: function (data) {
                    $('#' + type + '_save_working_days').removeClass('fa-spin');
                    $('#' + type + '_btn').removeClass('hidden');
                    $('#' + type).html(data.value);
                    $('#newNote').val('');
                    $(this).addClass('hidden');
                    $('#' + type).removeClass('hidden');
                    $('#' + type + '_input').addClass('hidden');
                    $('#' + type + '_save').addClass('hidden');
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                    $('#' + type + '_input').removeClass('hidden');
                    $('#' + type + '_save').removeClass('hidden');
                }
            })
        })
    </script>





@stop









