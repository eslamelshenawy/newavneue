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
            {!! Form::open(['url' => adminPath().'/out_sub_cats/'.$sub->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('name')) has-error @endif">
                <label>{{ trans('admin.name') }}</label>
                {!! Form::text('name', $sub->name, ['class' => 'form-control', 'placeholder' => trans('admin.name')]) !!}
            </div>

            <div class="form-group @if($errors->has('out_cat_id')) has-error @endif">
                <label>{{ trans('admin.out_cat') }}</label>
                <select class="form-control select2" name="out_cat_id" data-placeholder="{{ __('admin.out_cat') }}">
                    <option></option>
                    @foreach($cats as $cat)
                        <option @if($cat->id == $sub->out_cat_id) selected
                                @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('due_date')) has-error @endif">
                <label>{{ trans('admin.due_date') }}</label>
                {!! Form::number('due_date', $sub->due_date, ['class' => 'form-control', 'placeholder' => trans('admin.due_date'), 'min' => 1, 'max' => 31]) !!}
            </div>

            <div class="form-group @if($errors->has('notes')) has-error @endif">
                <label>{{ trans('admin.notes') }}</label>
                {!! Form::textarea('notes', $sub->notes ,['class' => 'form-control', 'placeholder' => trans('admin.notes'),'rows'=>5]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection