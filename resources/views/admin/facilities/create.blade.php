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
            <form action="{{ url(adminPath().'/facilities') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                <label>{{ trans('admin.en_name') }}</label>
                    <input type="text" name="en_name" class="form-control" value="{{ old('en_name') }}" placeholder="{!! trans('admin.en_name') !!}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                    <label>{{ trans('admin.ar_name') }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name') }}" placeholder="{!! trans('admin.ar_name') !!}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                    <label>{{ trans('admin.en_description') }}</label>
                    <textarea name="en_description" class="form-control" placeholder="{!! trans('admin.en_description') !!}" rows="6">{{ old('en_description') }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                    <label>{{ trans('admin.ar_description') }}</label>
                    <textarea name="ar_description" class="form-control" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ old('ar_description') }}</textarea>
                </div>
                <div class="form-group {{ $errors->has("icon") ? 'has-error' : '' }}">
                    {!! Form::label(trans("admin.icons")) !!}
                    <br>
                    <select class="image-picker show-html form-control" name="icon" style="margin-bottom:10px ">
                        @foreach(App\Icon::get() as $icon)
                            <option value="{{ $icon->id }}" data-img-class="first"  data-img-alt="Page 1" data-img-src="{{url('uploads/'. $icon->icon )}}"></option>
                        @endforeach
                    </select>
                </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
            <script src="{{url('js/image-picker.min.js')}}"></script>
            <script src="{{url('js/image-picker.js')}}"></script>

            <script>
                $("select").imagepicker()
            </script>
    @endsection