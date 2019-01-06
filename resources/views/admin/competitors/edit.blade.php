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
            {!! Form::open(['url' => adminPath().'/competitors/'.$edit->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('ar_name')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_name') }}</label>
                {!! Form::text('ar_name',$edit->ar_name,['class' => 'form-control', 'placeholder' => trans('admin.ar_name')]) !!}
            </div>
            <div class="form-group @if($errors->has('en_name')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_name') }}</label>
                {!! Form::text('en_name',$edit->en_name,['class' => 'form-control', 'placeholder' => trans('admin.en_name')]) !!}
            </div>
            <div class="form-group @if($errors->has('facebook')) has-error @endif col-md-9">
                <label>{{ trans('admin.facebook') }}</label>
                {!! Form::text('facebook',$edit->facebook,['class' => 'form-control', 'placeholder' => trans('admin.facebook')]) !!}
            </div>
            <div class="form-group @if($errors->has('payment_method')) has-error @endif col-md-3">
                <br/>
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                       data-off-text="{{ __('admin.no') }}" @if($edit->featured == 1) checked @endif data-label-text="{{ __('admin.featured') }}" value="1">
            </div>
            <div class="clearfix"></div>
            <div class="form-group @if($errors->has('notes')) has-error @endif col-md-12">
                <label>{{ trans('admin.notes') }}</label>
                {!! Form::textarea('notes',$edit->notes,['class' => 'form-control', 'placeholder' => trans('admin.notes'),'rows'=>5]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection