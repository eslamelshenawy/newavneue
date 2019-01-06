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
            {!! Form::open(['url' => adminPath().'/professions/'.$profession->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('name')) has-error @endif">
                <label>{{ trans('admin.name') }}</label>
                {!! Form::text('name', $profession->name ,['class' => 'form-control', 'placeholder' => trans('admin.name')]) !!}
            </div>
            <div class="form-group @if($errors->has('notes')) has-error @endif">
                <label>{{ trans('admin.notes') }}</label>
                {!! Form::textarea('notes', $profession->notes ,['class' => 'form-control', 'placeholder' => trans('admin.notes'),'rows'=>5]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection