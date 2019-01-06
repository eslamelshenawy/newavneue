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
            {!! Form::open(['url' => adminPath().'/campaign_types']) !!}
            <div class="form-group @if($errors->has('en_name')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_name') }}</label>
                {!! Form::text('en_name','',['class' => 'form-control', 'placeholder' => trans('admin.en_name')]) !!}
            </div>
            <div class="form-group @if($errors->has('ar_name')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_name') }}</label>
                {!! Form::text('ar_name','',['class' => 'form-control', 'placeholder' => trans('admin.ar_name')]) !!}
            </div>
            <div class="form-group @if($errors->has('notes')) has-error @endif col-md-12">
                <label>{{ trans('admin.notes') }}</label>
                {!! Form::textarea('notes','',['class' => 'form-control', 'placeholder' => trans('admin.notes'),'rows'=>5]) !!}
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection