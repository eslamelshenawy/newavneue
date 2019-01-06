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
            {!! Form::open(['url' => adminPath().'/companies']) !!}
            <div class="form-group @if($errors->has('name')) has-error @endif">
                <label>{{ trans('admin.name') }}</label>
                {!! Form::text('name','',['class' => 'form-control', 'placeholder' => trans('admin.name')]) !!}
            </div>
            <div class="form-group @if($errors->has('industry_id')) has-error @endif">
                <label>{{ trans('admin.notes') }}</label>
                <select class="form-control select2" name="industry_id" data-placeholder="{{ trans('admin.industry') }}">
                    <option></option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group @if($errors->has('email')) has-error @endif">
                <label>{{ trans('admin.email') }}</label>
                {!! Form::text('email','',['class' => 'form-control', 'placeholder' => trans('admin.email')]) !!}
            </div>
            <div class="form-group @if($errors->has('phone')) has-error @endif">
                <label>{{ trans('admin.phone') }}</label>
                {!! Form::text('phone','',['class' => 'form-control', 'placeholder' => trans('admin.phone')]) !!}
            </div>
            <div class="form-group @if($errors->has('notes')) has-error @endif">
                <label>{{ trans('admin.notes') }}</label>
                {!! Form::textarea('notes','',['class' => 'form-control', 'placeholder' => trans('admin.notes'),'rows'=>5]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection