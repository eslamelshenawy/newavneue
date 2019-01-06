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
            <form action={{url(adminPath().'/facilities/'.$data->id)}} method="post" enctype="multipart/form-data"  >
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                    <label>{{ trans('admin.en_name') }}</label>
                    <input type="text" name="en_name" class="form-control" value="{{ $data->en_name }}" placeholder="{!! trans('admin.en_name') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                    <label>{{ trans('admin.ar_name') }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ $data->ar_name }}" placeholder="{!! trans('admin.ar_name') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                    <label>{{ trans('admin.en_description') }}</label>
                    <textarea name="en_description" class="form-control" placeholder="{!! trans('admin.en_description') !!}" rows="6">{{ $data->en_description }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                    <label>{{ trans('admin.ar_description') }}</label>
                    <textarea name="description" class="form-control" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ $data->ar_description }}</textarea>
                </div>
                <div class="form-group {{ $errors->has("icon") ? 'has-error' : '' }}">
                    {!! Form::label(trans("admin.icons")) !!}
                    <br>
                    <select class="image-picker show-html form-control" name="icon" style="margin-bottom:10px ">
                        @foreach(App\Icon::get() as $icon)
                            <option value="{{ $icon->id }}" @if($data->icon==$icon->id) selected @endif data-img-class="first"  data-img-alt="Page 1" data-img-src="{{url('uploads/'. $icon->icon )}}">{{ $icon->{app()->getLocale().'_name'} }}</option>
                        @endforeach
                    </select>
                </div>
                <br/>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
    </div>
@endsection
        @section('js')
            <script src="{{url('js/image-picker.min.js')}}"></script>
            <script src="{{url('js/image-picker.js')}}"></script>
            <script>
                $('#close-preview').on('click',function () {
                    $('#oldImage').hide(200);
                })

                $('#imageInput').on('change',function () {
                    $('#oldImage').hide(200);
                })
                $("select").imagepicker()
            </script>
@endsection

