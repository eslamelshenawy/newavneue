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
            <form action="{{ url(adminPath().'/developers/'.$developer->id) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                    <label>{{ trans('admin.en_name') }}</label>
                    <input type="text" name="en_name" class="form-control" value="{{ $developer->en_name }}" placeholder="{!! trans('admin.en_name') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                    <label>{{ trans('admin.ar_name') }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ $developer->ar_name  }}" placeholder="{!! trans('admin.ar_name') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                    <label> {{ trans('admin.en_description') }}</label>
                    <textarea  name="en_description" class="form-control" value="{{ $developer->en_description }}" placeholder="{!! trans('admin.en_description') !!}" rows="6">{{ $developer->en_description }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                    <label> {{ trans('admin.ar_description') }}</label>
                    <textarea  name="ar_description" class="form-control" value="{{ $developer->ar_description }}" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ $developer->ar_description }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('email')) has-error @endif col-md-11">
                    <label>{{ trans('admin.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ $developer->email }}"
                           placeholder="{!! trans('admin.email') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('phone')) has-error @endif col-md-11">
                    <label>{{ trans('admin.phone') }}</label>
                    <input type="number" name="phone" class="form-control" value="{{ $developer->phone }}"
                           placeholder="{!! trans('admin.phone') !!}">
                </div>
                <div class="form-group col-md-9 @if($errors->has('phone')) has-error @endif">
                    <label>{{ trans('admin.facebook') }}</label>
                    <input type="text" name="facebook" class="form-control" value="{{ $developer->facebook }}"
                           placeholder="{!! trans('admin.facebook') !!}">
                </div>

                <div class="form-group @if($errors->has('payment_method')) has-error @endif col-md-3">
                    <br/>
                    <input type="hidden" name="featured" value="0">
                    <input type="checkbox" name="featured" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.featured') }}" @if($developer->featured == 1) checked @endif value="1">
                </div>
                <div class="clearfix"></div>

                <div class="input-group image-preview col-md-12">
                    <label>{{ trans('admin.logo') }}</label>
                    <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
                    <span class="input-group-btn">
                    <!-- image-preview-clear button -->
                    <button type="button" class="btn btn-default image-preview-clear" style="display:none; margin-top: 25px;">
                        <span class="glyphicon glyphicon-remove"></span> {{ trans('admin.clear') }}
                    </button>
                        <!-- image-preview-input -->
                    <div class="btn btn-default image-preview-input" style="margin-top: 25px;">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span class="image-preview-input-title">{{ trans('admin.browse') }}</span>

                        <input type="file" id="imageInput" accept="image/png, image/jpeg, image/gif" name="image"/> <!-- rename it -->
                    </div>
                </span>

                </div><!-- /input-group image-preview [TO HERE]-->
                @if(isset($developer->website_cover))
                    <img src="{{ url('uploads/'.$developer->website_cover) }}" width="200px">
                @endif
                <div class="input-group image-preview col-md-12">
                    <label>{{ trans('admin.website_cover') }}</label><br>
                    <label>{{ trans('admin.best_image') }} 1900 * 536</label>
                    <input type="file" accept="image/png, image/jpeg, image/gif" name="website_cover"/> <!-- rename it -->
                    <input type="hidden" value="{{$developer->website_cover}}" name="old_website_cover">
                </div>
                <div class="popover fade bottom in" role="tooltip" id="oldImage" style="top: 480px; left: 532px; display: block;">
                    <div class="arrow" style="left: 50%;"></div>
                    <h3 class="popover-title">
                        <strong>Preview</strong>
                        <button type="button" id="close-preview" style="font-size: initial;" class="close pull-right">x</button>
                    </h3>
                    <div class="popover-content">
                        <img id="dynamic" src="{{ url('uploads/'.$developer->logo) }}" style="width: 250px; height: 200px;">
                    </div>
                </div>
                <br/>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#close-preview').on('click',function () {
            $('#oldImage').hide(200);
        })

        $('#imageInput').on('change',function () {
            $('#oldImage').hide(200);
        })
    </script>
@endsection
