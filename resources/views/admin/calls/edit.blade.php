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
            {!! Form::open(['url' => adminPath().'/calls/'.$call->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('lead_id')) has-error @endif">
                <label>{{ trans('admin.lead') }}</label>
                <select name="lead_id" class="form-control select2" style="width: 100%"
                        data-placeholder="{{ trans('admin.lead') }}">
                    <option></option>
                    @foreach(@App\Lead::getAgentLeads() as $lead)
                        <option value="{{ $lead->id }}"
                                @if(old('lead_id') == $lead->id) selected
                                @elseif(request()->lead == $lead->id) selected @endif>
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

            <div class="form-group @if($errors->has('duration')) has-error @endif">
                <label>{{ trans('admin.duration') }}</label>
                {!! Form::number('duration',$call->duration,['class' => 'form-control', 'placeholder' => trans('admin.duration')]) !!}
            </div>



            <div class="form-group @if($errors->has('date')) has-error @endif">
                <label>{{ trans('admin.date') }}</label>
                <div class="input-group">
                    {!! Form::text('date',date('Y-m-d',$call->date),['class' => 'form-control datepicker', 'placeholder' => trans('admin.date'),'readonly'=>'']) !!}
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>

            <div class="form-group @if($errors->has('probability')) has-error @endif">
                <label>{{ trans('admin.probability') }}</label>
                <div class="input-group">
                    {!! Form::number('probability',$call->probability,['class' => 'form-control', 'placeholder' => trans('admin.probability'),'max'=> 100, 'min'=> 0]) !!}
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                </div>
            </div>

            <div class="form-group @if($errors->has('projects')) has-error @endif">
                <label>{{ trans('admin.projects') }}</label>
                @php($projects = json_decode($call->projects))
                <select multiple class="form-control select2" name="projects[]" style="width: 100%" data-placeholder="{{ trans('admin.projects') }}">
                    <option></option>
                    @foreach(@\App\Project::get() as $project)
                        <option value="{{ $project->id }}" @if(in_array($project->id, $projects)) selected @endif>{{ $project->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('description')) has-error @endif">
                <label>{{ trans('admin.description') }}</label>
                {!! Form::textarea('description',$call->description,['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection