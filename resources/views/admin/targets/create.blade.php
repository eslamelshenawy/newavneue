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
            {!! Form::open(['url' => adminPath().'/targets']) !!}
            <div class="form-group @if($errors->has('agent_type_id')) has-error @endif">
                <label>{{ trans('admin.agent_type') }}</label>
                <select class="form-control select2"  name="agent_type_id" data-placeholder="{{ trans('admin.agent_type') }}">
                    <option></option>
                    @foreach(@App\AgentType::get() as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group @if($errors->has('calls')) has-error @endif">
                <label>{{ trans('admin.calls') }}</label>
                {!! Form::number('calls','',['class' => 'form-control', 'placeholder' => trans('admin.calls'),'min'=>0]) !!}
            </div>
            <div class="form-group @if($errors->has('meetings')) has-error @endif">
                <label>{{ trans('admin.meetings') }}</label>
                {!! Form::number('meetings','',['class' => 'form-control', 'placeholder' => trans('admin.meetings'),'min'=>0]) !!}
            </div>
            <div class="form-group @if($errors->has('money')) has-error @endif">
                <label>{{ trans('admin.money') }}</label>
                {!! Form::number('money','',['class' => 'form-control', 'placeholder' => trans('admin.money'),'min'=>0]) !!}
            </div>
            <div class="form-group @if($errors->has('month')) has-error @endif">
                <label>{{ trans('admin.month') }}</label>
                {!! Form::number('month','',['class' => 'form-control', 'placeholder' => trans('admin.month'),'min'=>1,'max'=>12]) !!}
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