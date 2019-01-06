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
            <form action="{{ url(adminPath() . '/contracts/' . $contract->id) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="form-group @if($errors->has('title')) has-error @endif @if(request()->has('lead')) col-md-12 @else col-md-6 @endif">
                    <label>{{ trans('admin.title') }}</label>
                    <input type="text" class="form-control" value="{{ $contract->title }}" name="title" placeholder="{{ __('admin.title') }}">
                </div>

                <div class="form-group @if($errors->has('lead_id')) has-error @endif col-md-6 @if(request()->has('lead')) hidden @endif">
                    <label>{{ trans('admin.lead') }}</label>
                    <select name="lead_id" class="form-control select2" style="width: 100%"
                            data-placeholder="{!! trans('admin.lead') !!}">
                        <option></option>
                        @foreach(@\App\Lead::getAgentLeads() as $lead)
                            <option @if($contract->lead_id == $lead->id) selected @endif value="{{ $lead->id }}">{{ $lead->first_name . ' ' . $lead->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group @if($errors->has('background')) has-error @endif col-md-12">
                    <label>{{ trans('admin.background') }}</label>
                    <input type="file" class="form-control" name="background">
                </div>

                <div class="form-group @if($errors->has('contract')) has-error @endif col-md-12">
                    <label>{{ trans('admin.contract') }}</label>
                    <textarea id="editor1" name="contract" rows="10" cols="80">{{ $contract->contract }}</textarea>
                </div>
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary btn-flat">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
