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
            <form action={{url(adminPath().'/phases/'.$data->id)}} method="post" enctype="multipart/form-data"  >
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
                    <textarea name="ar_description" class="form-control" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ $data->ar_description }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('meter_price')) has-error @endif">
                    <label>{{ trans('admin.meter_price') }}</label>
                    <input type="number" name="meter_price" class="form-control" value="{{ $data->meter_price }}"
                           placeholder="{!! trans('admin.meter_price') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('area')) has-error @endif">
                    <label>{{ trans('admin.area') }}</label>
                    <input type="number" name="area" class="form-control" value="{{ $data->area }}"
                           placeholder="{!! trans('admin.area') !!}">
                </div>
                <div class="form-group  @if($errors->has('delivery_date')) has-error @endif col-md-2">
                    <label>{{ trans('admin.delivery_date') }}</label>
                    <div class="input-group">
                       <input name="delivery_date" class="form-control" value="{{ $data->delivery_date }}" placeholder ="{{ trans('admin.delivery_date') }}" readonly="" id="datepicker" >
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group col-md-6  {{ $errors->has("facility") ? 'has-error' : '' }}">
                    {!! Form::label(trans("admin.facility")) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" multiple name="facility[]"
                            data-placeholder="{{ trans("admin.facilities") }}">
                        <option></option>
                        @foreach(App\Facility::get() as $facilty)
                            <option value="{{ $facilty->id }}" @if(count(App\Phase_Facilities::where('facility_id',$facilty->id)->where('phase_id',$data->id)->get()) > 0) selected @endif >
                                {{ $facilty->en_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.meta_keywords')) !!}
                    <input type="text" name="meta_keywords" value="{{ $facilty->meta_keywords }}" class="form-control" data-role="tagsinput" style="width: 100%">
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.meta_description')) !!}
                    <textarea class="form-control" name="meta_description" rows="1">{{ $facilty->meta_description }}</textarea>
                </div>

                <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                </div>
            </form>
    </div>
    </div>
@endsection
@section('js')
    <script>
        $('#datepicker').datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
        });
    </script>
@endsection