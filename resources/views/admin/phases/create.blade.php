@extends('admin.index')
@section('content')
    <style>
        #show_image {
            display: none;
        }
    </style>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('admin.add').' '.trans('admin.phase').' '.trans('admin.to').' '.$project->{app()->getLocale().'_name'} }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <form action="{{ url(adminPath().'/phases/store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                    <label>{{ trans('admin.en_name') }}</label>
                    <input type="text" name="en_name" class="form-control" value="{{ old('en_name') }}"
                           placeholder="{!! trans('admin.en_name') !!}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                    <label>{{ trans('admin.ar_name') }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name') }}"
                           placeholder="{!! trans('admin.ar_name') !!}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                    <label>{{ trans('admin.en_description') }}</label>
                    <textarea name="en_description" class="form-control"
                              placeholder="{!! trans('admin.en_description') !!}" value="{{ old('en_description') }}"
                              rows="6">{{ old('en_description') }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                    <label>{{ trans('admin.ar_description') }}</label>
                    <textarea name="ar_description" class="form-control"
                              placeholder="{!! trans('admin.ar_description') !!}" value="{{ old('ar_description') }}"
                              rows="6">{{ old('ar_description') }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('meter_price')) has-error @endif">
                    <label>{{ trans('admin.meter_price') }}</label>
                    <input type="number" name="meter_price" class="form-control" value="{{ old('meter_price') }}"
                           placeholder="{!! trans('admin.meter_price') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('area')) has-error @endif">
                    <label>{{ trans('admin.area') }}</label>
                    <input type="number" name="area" class="form-control" value="{{ old('area') }}"
                           placeholder="{!! trans('admin.area') !!}">
                </div>
                <?php
                $arr=[];
                if(count(old('facility'))>0)
                    $arr=old('facility');
                ?>
                <div class="form-group  {{ $errors->has("facility") ? 'has-error' : '' }} col-md-12">
                    {!! Form::label(trans("admin.facility")) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" multiple name="facility[]"
                            data-placeholder="{{ trans("admin.facilities") }}">
                        <option></option>
                        @foreach(App\Facility::get() as $facilty)
                            <option value="{{ $facilty->id }}" @if(in_array($facilty->id,$arr)) selected @endif>{{ $facilty->en_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6 @if($errors->has('meta_keywords')) has-error @endif">
                    {!! Form::label(trans('admin.meta_keywords')) !!}
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" data-role="tagsinput" style="width: 100%">
                </div>
                <div class="form-group col-md-6 @if($errors->has('meta_description')) has-error @endif">
                    {!! Form::label(trans('admin.meta_description')) !!}
                    <textarea class="form-control" name="meta_description" value="{{ old('meta_keywords') }}"
                              rows="1">{{ old('meta_description') }}</textarea>
                </div>

                <div class="form-group  @if($errors->has('delivery_date')) has-error @endif col-md-2">
                    <label>{{ trans('admin.delivery_date') }}</label>
                    <div class="input-group">
                        {!! Form::text('delivery_date','',['class' => 'form-control', 'placeholder' => trans('admin.delivery_date'),'readonly'=>'','id'=>'datepicker']) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="input-group image-preview">
                    <label>{{ trans("admin.logo") }}</label>
                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                    <!-- don\'t give a name === doesn\'t send on POST/GET -->
                    <span class="input-group-btn">
                               <!-- image-preview-clear button -->
                       <button type="button" class="btn btn-default image-preview-clear"
                               style="display:none; margin-top: 25px;">
                           <span class="glyphicon glyphicon-remove"></span> {{ trans("admin.clear") }}
                       </button>
                        <!-- image-preview-input -->
                       <div class="btn btn-default image-preview-input" style="margin-top: 25px;">
                            <span class="glyphicon glyphicon-folder-open"></span>
                            <span class="image-preview-input-title">{{ trans("admin.browse") }}</span>
                          <input type="file" accept="image/png, image/jpeg, image/gif" name="logo"/> <!-- rename it -->
                      </div>
                  </span>
                </div>
                <div class="form-group @if($errors->has('promo')) has-error @endif">
                    <label>{{ trans('admin.promo') }}</label>
                    <input type="file" accept="image/png, image/jpeg, image/gif" name="promo" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
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

