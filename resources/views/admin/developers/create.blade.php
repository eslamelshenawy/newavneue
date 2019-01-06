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
            <form action="{{ url(adminPath().'/developers') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
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
                    <label> {{ trans('admin.en_description') }}</label>
                    <textarea name="en_description" class="form-control" value=""
                              placeholder="{!! trans('admin.en_description') !!}"
                              rows="6">{{ old('en_description') }}</textarea>
                </div>

                        <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                            <label> {{ trans('admin.ar_description') }}</label>
                            <textarea  name="ar_description" class="form-control" value="" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ old('ar_description') }}</textarea>
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('email')) has-error @endif col-md-11">
                            <label>{{ trans('admin.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                   placeholder="{!! trans('admin.email') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('phone')) has-error @endif col-md-11">
                            <label>{{ trans('admin.phone') }}</label>
                            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}"
                                   placeholder="{!! trans('admin.phone') !!}">
                        </div>
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
                                <input type="file" accept="image/png, image/jpeg, image/gif" name="logo"/> <!-- rename it -->
                            </div>
                            </span>
                        </div><!-- /input-group image-preview [TO HERE]-->
                        <div class="input-group image-preview col-md-12">
                            <label>{{ trans('admin.website_cover') }}</label><br>
                            <label>{{ trans('admin.best_image') }} 1900 * 536</label>
                            <input type="file" accept="image/png, image/jpeg, image/gif" name="website_cover"/> <!-- rename it -->

                        </div>
                        <div class="form-group col-md-9 @if($errors->has('phone')) has-error @endif">
                            <label>{{ trans('admin.facebook') }}</label>
                            <input type="text" name="facebook" class="form-control" value="{{ old('facebook') }}"
                                   placeholder="{!! trans('admin.facebook') !!}">
                        </div>
                        <div class="form-group @if($errors->has('payment_method')) has-error @endif col-md-3">
                            <br/>
                            <input type="hidden" name="featured" value="0">
                            <input type="checkbox" name="featured" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                                   data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.featured') }}" value="1">
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-center">
                            <br>
                            <button type="button" class="btn btn-success btn-flat"
                                    id="addContact">{{ trans('admin.add_contact') }}</button>
                        </div>
                        <br>
                        <span id="contacts"></span>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>

@endsection
@section('js')
    <script>
        var i = 1;
        $(document).on('click', '#addEmail', function () {
            $('#otherEmails').append('<span id="removeEmail' + i + '"><div class="form-group col-md-11">' +
                '<label>{{ trans("admin.other_emails") }}</label>' +
                '<input type="email" name="other_emails[]" class="form-control"' +
                'placeholder="{!! trans("admin.other_emails") !!}">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<br/>' +
                '<a class="btn btn-social-icon btn-plus removeEmail" num="' + i + '" style="margin-top: 5px;"><i ' +
                'class="fa fa-minus"></i></a>' +
                '</div></span>');
            i++
        });

        $(document).on('click', '.removeEmail', function () {
            num = $(this).attr('num');
            $('#removeEmail' + num).remove();
        })
    </script>

    <script>
        var x = 1;
        $(document).on('click', '#addPhone', function () {
            $('#otherPhones').append('<span id="removePhone' + x + '"><div class="form-group col-md-11">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<input type="number" name="other_phones[]" class="form-control"' +
                'placeholder="{!! trans("admin.other_phones") !!}">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<br/>' +
                '<a class="btn btn-social-icon btn-plus removePhone" num="' + x + '" style="margin-top: 5px;"><i ' +
                'class="fa fa-minus"></i></a>' +
                '</div></span>');
            x++
        });

        $(document).on('click', '.removePhone', function () {
            num = $(this).attr('num');
            $('#removePhone' + num).remove();
        })
    </script>

    <script>
        var y = 1;
        $(document).on('click', '#addContact', function () {
            $('#contacts').append('<div class="well" style="height: 150px" id="removeContact'+y+'">' +
                '<div class="form-group col-md-4">' +
                '<label>{{ trans("admin.name") }}</label>' +
                '<input type="text" name="contact_name[]" class="form-control"' +
                'placeholder="{{ trans("admin.name") }}">' +
                '</div>' +
                '<div class="form-group col-md-4">' +
                '<label>{{ trans("admin.phone") }}</label>' +
                '<input type="number" name="contact_phone[]" class="form-control" value=""' +
                'placeholder="{!! trans("admin.phone") !!}">' +
                '</div>' +
                '<div class="form-group col-md-4">' +
                '<label>{{ trans("admin.email") }}</label>' +
                '<input type="email" name="contact_email[]" class="form-control"' +
                'placeholder="{!! trans("admin.email") !!}">' +
                '</div>' +
                '<div class="text-center">' +
                '<button type="button" class="btn btn-danger btn-flat removeContact" num="'+y+'">' +
                '{{ trans("admin.remove") }}</button>' +
                '</div>' +
                '</div>');
            x++
        });

        $(document).on('click', '.removeContact', function () {
            num = $(this).attr('num');
            $('#removeContact' + num).remove();
        })
    </script>
@endsection
