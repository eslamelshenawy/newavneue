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
            <form action={{url(adminPath().'/titles/'.$titles->id)}} method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group @if($errors->has('name')) has-error @endif">
                    <label>{{ trans('admin.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $titles->name }}" placeholder="{!! trans('admin.name') !!}">
                </div>
                <div class="form-group @if($errors->has('description')) has-error @endif">
                    <label>{{ trans('admin.description') }}</label>
                    <textarea name="description" class="form-control" placeholder="{!! trans('admin.description') !!}" rows="5">{{ $titles->description }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
    </div>
@endsection
