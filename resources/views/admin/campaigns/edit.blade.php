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
            {!! Form::open(['url' => adminPath().'/campaigns/'.$edit->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('en_name')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_name') }}</label>
                {!! Form::text('en_name',$edit->en_name,['class' => 'form-control', 'placeholder' => trans('admin.en_name')]) !!}
            </div>
            <div class="form-group @if($errors->has('ar_name')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_name') }}</label>
                {!! Form::text('ar_name',$edit->ar_name,['class' => 'form-control', 'placeholder' => trans('admin.ar_name')]) !!}
            </div>

            <div class="form-group @if($errors->has('campaign_type_id')) has-error @endif col-md-6">
                <label>{{ trans('admin.campaign_type') }}</label>
                <select class="select2 form-control" name="campaign_type_id" data-placeholder="{{ __('admin.campaign_type') }}">
                    <option></option>
                    @foreach(@\App\CampaignType::all() as $type)
                        <option value="{{ $type->id }}" @if($edit->campaign_type_id == $type->id) selected @endif>{{ $type->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group @if($errors->has('project_id')) has-error @endif col-md-6">
                <label>{{ trans('admin.project') }}</label>
                <select class="select2 form-control" name="project_id" data-placeholder="{{ __('admin.project') }}">
                    <option></option>
                    @foreach(@\App\Project::all() as $project)
                        <option value="{{ $project->id }}" @if($edit->project_id == $project->id) selected @endif>{{ $project->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('start_date')) has-error @endif col-md-6">
                <label>{{ trans('admin.start_date') }}</label>
                {!! Form::text('start_date',date('Y-m-d',$edit->start_date),['class' => 'form-control datepicker', 'placeholder' => trans('admin.start_date'),'readonly'=>'']) !!}
            </div>
            <div class="form-group @if($errors->has('end_date')) has-error @endif col-md-6">
                <label>{{ trans('admin.end_date') }}</label>
                {!! Form::text('end_date',date('Y-m-d',$edit->end_date),['class' => 'form-control datepicker', 'placeholder' => trans('admin.end_date'),'readonly'=>'']) !!}
            </div>

            <div class="form-group @if($errors->has('budget')) has-error @endif col-md-12">
                <label>{{ trans('admin.budget') }}</label>
                {!! Form::number('budget',$edit->budget,['class' => 'form-control', 'placeholder' => trans('admin.budget')]) !!}
            </div>

            <div class="form-group @if($errors->has('description')) has-error @endif col-md-12">
                <label>{{ trans('admin.description') }}</label>
                {!! Form::textarea('description',$edit->description,['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection