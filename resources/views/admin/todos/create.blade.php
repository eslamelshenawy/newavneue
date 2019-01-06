@extends('admin.index')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            {!! Form::open(['url' => adminPath().'/todos']) !!}
            <div class="form-group @if($errors->has('leads')) has-error @endif">
                <label>{{ trans('admin.leads') }}</label>
                <select class="form-control select2" name="leads" data-placeholder="{{ trans('admin.leads') }}" style="width: 100%">
                    <option></option>
                    @foreach(@App\Lead::getAgentLeads() as $lead)
                        <option value="{{ $lead->id }}"
                                @if(old('lead_id') == $lead->id) selected @endif>
                            {{ $lead->first_name . ' ' . $lead->last_name }}
                            -
                            @if($lead->agent_id == auth()->id())
                                {{ __('admin.my_lead') }}
                            @else
                                {{ __('admin.team_lead', ['agent' => @$lead->agent->name]) }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group @if($errors->has('leads')) has-error @endif">
                <label>{{ trans('admin.to_do_type') }}</label>
                <select class="form-control select2" name="to_do_type" data-placeholder="{{ trans('admin.to_do_type') }}" style="width: 100%">
                    <option></option>
                    <option value="call">{{ trans('admin.call') }}</option>
                    <option value="meeting">{{ trans('admin.meeting') }}</option>
                    <option value="other">{{ trans('admin.other') }}</option>
                </select>
            </div>

            <div class="form-group @if($errors->has('due_date')) has-error @endif">
                <label>{{ trans('admin.due_date') }}</label>
                <div class="input-group">
                        <input type="text" readonly name="due_date" @if(request()->has('date')) value="{{ request()->date }}" @else value="{{ old('due_date') }}" @endif class="form-control pull-right datepicker" placeholder="{{ trans('admin.due_date') }}">
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

            <div class="form-group @if($errors->has('description')) has-error @endif">
                <label>{{ trans('admin.description') }}</label>
                {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection