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
            <form action={{url(adminPath().'/meeting_statuses/' . $edit->id)}} method="post" enctype="multipart/form-data"  >
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group @if($errors->has('name')) has-error @endif">
                    <label>{{ trans('admin.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $edit->name }}" placeholder="{!! trans('admin.name') !!}">
                </div>
                
                <div class="form-group @if($errors->has('has_next_action')) has-error @endif">
                    <label> {{ trans('admin.has_next_action') }}</label>
                    <input type="hidden" name="has_next_action" value="0">
                    <input type="checkbox" name="has_next_action" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.has_next_action') }}" @if($edit->has_next_action) checked @endif value="1">
                </div>
                
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
    </div>
@endsection
