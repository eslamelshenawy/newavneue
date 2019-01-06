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
            {!! Form::open(['url' => adminPath().'/tags']) !!}
            <div class="form-group @if($errors->has('name')) has-error @endif">
                <label>{{ trans('admin.en_name') }}</label>
                {!! Form::text('en_name','',['class' => 'form-control', 'placeholder' => trans('admin.en_name')]) !!}
            </div>
            <div class="form-group @if($errors->has('name')) has-error @endif">
                <label>{{ trans('admin.ar_name') }}</label>
                {!! Form::text('ar_name','',['class' => 'form-control', 'placeholder' => trans('admin.ar_name')]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection