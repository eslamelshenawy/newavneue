@extends('admin.index')
@section('content')
    <style>
        #show_image{
            display: none;
        }
    </style>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <form action="{{ url(adminPath().'/unit_types') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group @if($errors->has('en_name')) has-error @endif col-md-4">
                <label>{{ trans('admin.en_name') }}</label>
                    <input type="text" name="en_name" class="form-control" value="{{ old('en_name') }}" placeholder="{!! trans('admin.en_name') !!}">
                </div>
                <div class="form-group @if($errors->has('ar_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.ar_name') }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name') }}" placeholder="{!! trans('admin.ar_name') !!}">
                </div>
                <div class="form-group @if($errors->has('usage')) has-error @endif col-md-4">
                    <label>{{ trans('admin.type') }}</label>
                    <select name="usage" class="form-control select2" data-placeholder="{{ trans('admin.usage') }}">
                        <option></option>
                        <option value="commercial">{{ trans('admin.commercial') }}</option>
                        <option value="personal">{{ trans('admin.personal') }}</option>
                    </select>
                </div>
                <div class="form-group @if($errors->has('usage')) has-error @endif col-md-12">
                    <label>{{ trans('admin.description') }}</label>

                    <textarea class="form-control" name="description"></textarea>
                </div>

            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
