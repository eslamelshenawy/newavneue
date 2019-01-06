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
            <form action={{url(adminPath().'/leads/'.$data->id)}} method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group @if($errors->has('first_name')) has-error @endif col-md-12">
                    <label>{{ trans('admin.prefix_name') }}</label>
                    <select name="prefix_name" class="form-control select2"
                            data-placeholder="{!! trans('admin.prefix_name') !!}">
                        <option></option>
                        <option value="mr"
                                @if($data->prefix_name == 'mr') selected @endif>{{ trans('admin.mr') }}</option>
                        <option value="mrs"
                                @if($data->prefix_name == 'mrs') selected @endif>{{ trans('admin.mrs') }}</option>
                        <option value="ms"
                                @if($data->prefix_name == 'ms') selected @endif>{{ trans('admin.ms') }}</option>
                    </select>
                </div>
                <div class="form-group @if($errors->has('first_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.first_name') }}</label>
                    <input type="text" name="first_name" class="form-control" value="{{ $data->first_name }}"
                           placeholder="{!! trans('admin.first_name') !!}">
                </div>
                <div class="form-group @if($errors->has('middle_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.middle_name') }}</label>
                    <input type="text" name="middle_name" class="form-control" value="{{ $data->middle_name }}"
                           placeholder="{!! trans('admin.middle_name') !!}">
                </div>
                <div class="form-group @if($errors->has('last_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.last_name') }}</label>
                    <input type="text" name="last_name" class="form-control" value="{{ $data->last_name }}"
                           placeholder="{!! trans('admin.last_name') !!}">
                </div>
                <div class="form-group @if($errors->has('ar_first_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.ar_first_name') }}</label>
                    <input type="text" name="ar_first_name" class="form-control" value="{{ $data->ar_first_name }}"
                           placeholder="{!! trans('admin.ar_first_name') !!}">
                </div>
                <div class="form-group @if($errors->has('ar_middle_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.ar_middle_name') }}</label>
                    <input type="text" name="ar_middle_name" class="form-control" value="{{ $data->ar_middle_name }}"
                           placeholder="{!! trans('admin.ar_middle_name') !!}">
                </div>
                <div class="form-group @if($errors->has('ar_last_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.ar_last_name') }}</label>
                    <input type="text" name="ar_last_name" class="form-control" value="{{ $data->ar_last_name }}"
                           placeholder="{!! trans('admin.ar_last_name') !!}">
                </div>
                <div class="form-group @if($errors->has('title_id')) has-error @endif col-md-12">
                    <label>{{ trans('admin.job_title') }}</label>
                    <select name="title_id" class="form-control select2"
                            data-placeholder="{!! trans('admin.job_title') !!}">
                        <option></option>
                        @foreach(@\App\Title::all() as $title)
                            <option value="{{ $title->id }}"
                                    @if($title->id == $data->title_id) selected @endif>{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if($errors->has('id_number')) has-error @endif col-md-12">
                    <label>{{ trans('admin.id_number') }}</label>
                    <input type="number" value="{{ $data->id_number }}" class="form-control" name="id_number" placeholder="{{ __('admin.id_number') }}">
                </div>
                <div class="form-group @if($errors->has('nationality')) has-error @endif col-md-12">
                    <label>{{ trans('admin.nationality') }}</label>
                    <select name="nationality" class="form-control select2"
                            data-placeholder="{!! trans('admin.nationality') !!}">
                        <option></option>
                        @foreach(@\App\Country::all() as $country)
                            <option value="{{ $country->id }}"
                                    @if($country->id == $data->nationality) selected @endif>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if($errors->has('religion')) has-error @endif col-md-12">
                    <label>{{ trans('admin.religion') }}</label>
                    <select name="religion" class="form-control select2"
                            data-placeholder="{!! trans('admin.religion') !!}">
                        <option></option>
                        <option value="muslim"
                                @if($data->id == 'muslim') selected @endif>{{ trans('admin.muslim') }}</option>
                        <option value="christian"
                                @if($data->id == 'christian') selected @endif>{{ trans('admin.christian') }}</option>
                        <option value="jewish"
                                @if($data->id == 'jewish') selected @endif>{{ trans('admin.jewish') }}</option>
                        <option value="other"
                                @if($data->id == 'other') selected @endif>{{ trans('admin.other') }}</option>
                    </select>
                </div>
                <div class="form-group @if($errors->has('birth_date')) has-error @endif col-md-12">
                    <label>{{ trans('admin.birth_date') }}</label>
                    <div class="input-group">
                        {!! Form::text('birth_date',date('Y-m-d',$data->birth_date),['class' => 'form-control datepicker', 'placeholder' => trans('admin.birth_date'),'readonly'=>'']) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group @if($errors->has('email')) has-error @endif col-md-11">
                    <label>{{ trans('admin.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ $data->email }}"
                           placeholder="{!! trans('admin.email') !!}">
                </div>
                <div class="col-md-1">
                    <br/>
                    <a class="btn btn-social-icon btn-plus" style="margin-top: 5px;" id="addEmail"><i
                                class="fa fa-plus"></i></a>
                </div>
                <span id="otherEmails">
                    @php
                        $oldEmails = json_decode($data->other_emails);
                        $x = 1;
                    @endphp
                    @if($oldEmails != null)
                        @foreach($oldEmails as $emails)
                            <div id="removeOldEmail{{ $x }}">
                        <div class="form-group col-md-11">
                        <label>{{ trans('admin.other_emails') }}</label>
                        <input type="email" name="other_emails[]" class="form-control" value="{{ $emails}}"
                               placeholder="{!! trans('admin.other_emails') !!}">
                        </div>
                        <div class="col-md-1">
                        <br/>
                        <a class="btn btn-social-icon btn-plus removeOldEmail" style="margin-top: 5px;"
                           count="{{ $x }}"><i
                                    class="fa fa-minus"></i></a>
                        </div>
                        </div>
                            @php
                                $x++
                            @endphp
                        @endforeach
                    @endif
                </span>

                @php
                    $social = json_decode($data->social);
                @endphp
                <div class="form-group @if($errors->has('phone')) has-error @endif col-md-11">
                    <label>{{ trans('admin.phone') }}</label>
                    <div class="input-group">
                        <input type="number" name="phone" class="form-control" value="{{ $data->phone }}"
                               placeholder="{!! trans('admin.phone') !!}">
                        <span class="input-group-addon" style="padding-bottom: 3px">
                            <label class="fa fa-whatsapp" style="color: #34af23;">
                                <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"
                                     style="position: relative;">
                                    <input type="hidden" name="social[whatsapp]" value="0">
                                    <input type="checkbox" name="social[whatsapp]" @if(@$social->whatsapp == 1) checked
                                           @endif value="1" class="minimal"
                                           style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                </div>
                            </label>
                        </span>
                        <span class="input-group-addon" style="padding-bottom: 3px">
                            <label class="fa fa-comments" style="color: #3b5998;">
                                <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"
                                     style="position: relative;">
                                    <input type="hidden" name="social[sms]" value="0">
                                    <input type="checkbox" name="social[sms]" @if(@$social->sms == 1) checked
                                           @endif value="1" class="minimal"
                                           style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                </div>
                            </label>
                        </span>
                        <span class="input-group-addon" style="padding-bottom: 3px">
                            <label class="" style="color: #3b5998;">
                                <img src="{{ url('viber.png') }}" height="18px">
                                <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"
                                     style="position: relative;">
                                    <input type="hidden" name="social[viber]" value="0">
                                    <input type="checkbox" name="social[viber]" value="1"
                                           @if(@$social->viber == 1) checked @endif class="minimal"
                                           style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                </div>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-1">
                    <br/>
                    <a class="btn btn-social-icon btn-plus" style="margin-top: 5px;" id="addPhone"><i
                                class="fa fa-plus"></i></a>
                </div>

                <span id="otherPhones">
                    @php
                        $oldPhones = json_decode($data->other_phones);
                        $y = 1;
                    @endphp
                    @if($oldPhones != null)
                        @foreach($oldPhones as $phones)
                            @foreach($phones as $phone => $socials)
                                <div id="removeOldPhone{{ $y }}">
                                    <div class="form-group @if($errors->has('phone')) has-error @endif col-md-11">
                                    <label>{{ trans('admin.other_phones') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="other_phones[o{{ $y }}]" class="form-control"
                                               value="{{ $phone }}"
                                               placeholder="{!! trans('admin.other_phones') !!}">
                                        <span class="input-group-addon" style="padding-bottom: 3px">
                                            <label class="fa fa-whatsapp" style="color: #34af23;">
                                                <div class="icheckbox_minimal-blue" aria-checked="false"
                                                     aria-disabled="false"
                                                     style="position: relative;">
                                                    <input type="hidden" name="other_socials[o{{ $y }}][whatsapp]" value="0">
                                                    <input type="checkbox" name="other_socials[o{{ $y }}][whatsapp]"
                                                           @if($socials->whatsapp == 1) checked
                                                           @endif value="1" class="minimal"
                                                           style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper"
                                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                </div>
                                            </label>
                                        </span>
                                        <span class="input-group-addon" style="padding-bottom: 3px">
                                            <label class="fa fa-comments" style="color: #3b5998;">
                                                <div class="icheckbox_minimal-blue" aria-checked="false"
                                                     aria-disabled="false"
                                                     style="position: relative;">
                                                    <input type="hidden" name="other_socials[o{{ $y }}][sms]" value="0">
                                                    <input type="checkbox" name="other_socials[o{{ $y }}][sms]"
                                                           @if($socials->sms == 1) checked
                                                           @endif value="1" class="minimal"
                                                           style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper"
                                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                </div>
                                            </label>
                                        </span>
                                        <span class="input-group-addon" style="padding-bottom: 3px">
                                            <label class="" style="color: #3b5998;">
                                                <img src="{{ url('viber.png') }}" height="18px">
                                                <div class="icheckbox_minimal-blue" aria-checked="false"
                                                     aria-disabled="false"
                                                     style="position: relative;">
                                                    <input type="hidden" name="other_socials[o{{ $y }}][viber]" value="0">
                                                    <input type="checkbox" name="other_socials[o{{ $y }}][viber]" value="1"
                                                           @if($socials->viber == 1) checked @endif class="minimal"
                                                           style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper"
                                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                </div>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                    <div class="col-md-1">
                                    <br/>
                                    <a class="btn btn-social-icon btn-plus removeOldPhone" style="margin-top: 5px;"
                                       count="{{ $y }}"><i
                                                class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                @php
                                    $y++
                                @endphp
                            @endforeach
                        @endforeach
                    @endif
                </span>

                <div class="form-group @if($errors->has('country')) has-error @endif col-md-12">
                    <label>{{ trans('admin.country') }}</label>
                    <select name="country_id" class="form-control select2" id="country_id" style="width: 100%"
                            data-placeholder="{{ trans('admin.country') }}">
                        <option></option>
                        @foreach(App\Country::get() as $country)
                            <option value="{{ $country->id }}" @if($country->id == $data->country_id) selected @endif>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <span id="cities">
                    @if($data->country_id > 0)
                        <div class="form-group @if($errors->has('city_id')) has-error @endif col-md-12">
                            <label>{{ trans('admin.city') }}</label>
                            <select name="city_id" class="form-control select2" id="city_id" style="width: 100%"
                                    data-placeholder="{{ trans('admin.city') }}">
                                <option></option>
                                @foreach(@\App\City::where('country_id', $data->country_id) as $city)
                                    <option value="{{ $city->id }}" @if($data->city_id == $city->id) selected @endif>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </span>
                <div class="form-group @if($errors->has('address')) has-error @endif col-md-12">
                    <label> {{ trans('admin.address') }}</label>
                    <input type="text" name="address" class="form-control" value="{{ $data->address }}"
                           placeholder="{!! trans('admin.address') !!}">
                </div>
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.industry') }}</label>
                    <select name="industry_id" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.industry') }}">
                        <option></option>
                        @foreach(App\Industry::get() as $ind)
                            <option value="{{ $ind->id }}" @if($ind->id == $data->industry_id) selected @endif>
                                {{ $ind->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.company') }}</label>
                    <input name="company" class="form-control" placeholder="{{ trans('admin.company') }}"
                           value="{{ $data->company }}">
                </div>
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.school') }}</label>
                    <input name="school" class="form-control" placeholder="{{ trans('admin.school') }}"
                           value="{{ $data->school }}">
                </div>
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.club') }}</label>
                    <input type="text" name="club" class="form-control" placeholder="{!! trans('admin.club') !!}"
                           value="{{ $data->club }}">
                </div>
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.facebook') }}</label>
                    <input name="facebook" class="form-control" placeholder="{{ trans('admin.facebook') }}"
                           value="{{ $data->facebook }}">
                </div>
                <div class="form-group @if($errors->has('lead_source')) has-error @endif col-md-12">
                    <label>{{ trans('admin.source') }}</label>
                    <select name="lead_source"  class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.lead_source') }}">
                        <option></option>
                        @foreach(App\LeadSource::get() as $lead)
                            <option value="{{ $lead->id }}" @if($data->lead_source_id == $lead->id) selected @endif>
                                {{ $lead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.notes') }}</label>
                    <textarea name="notes" class="form-control" placeholder="{{ trans('admin.notes') }}"
                              rows="5">{{ $data->notes }}</textarea>
                </div>
                <!-- image-preview-filename input [CUT FROM HERE]-->
                <div class="input-group image-preview col-md-12">
                    <label>{{ trans('admin.image') }}</label>
                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                    <!-- don't give a name === doesn't send on POST/GET -->
                    <span class="input-group-btn">
                    <!-- image-preview-clear button -->
                    <button type="button" class="btn btn-default image-preview-clear"
                            style="display:none; margin-top: 25px;">
                        <span class="glyphicon glyphicon-remove"></span> {{ trans('admin.clear') }}
                    </button>
                        <!-- image-preview-input -->
                    <div class="btn btn-default image-preview-input" style="margin-top: 25px;">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span class="image-preview-input-title">{{ trans('admin.browse') }}</span>
                        <input type="file" accept="image/png, image/jpeg, image/gif" name="image"/> <!-- rename it -->
                    </div>
                </span>
                </div><!-- /input-group image-preview [TO HERE]-->
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
                '<div class="input-group">' +
                '<input type="number" name="other_phones['+ x +']" class="form-control"' +
                'placeholder="{!! trans("admin.other_phones") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">' +
                '<input type="hidden" name="other_socials['+ x +'][whatsapp]" value="0">' +
                '<input type="checkbox" name="other_socials['+ x +'][whatsapp]" value="1" class="minimal" style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">' +
                '<input type="hidden" name="other_socials['+ x +'][sms]" value="0">' +
                '<input type="checkbox" name="other_socials['+ x +'][sms]" value="1" class="minimal" style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">' +
                '<input type="hidden" name="other_socials['+ x +'][viber]" value="0">' +
                '<input type="checkbox" name="other_socials['+ x +'][viber]" value="1" class="minimal" style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<br/>' +
                '<a class="btn btn-social-icon btn-plus removePhone" num="' + x + '" style="margin-top: 5px;"><i ' +
                'class="fa fa-minus"></i></a>' +
                '</div></span>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            })
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            })
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            })
            x++
        });

        $(document).on('click', '.removePhone', function () {
            num = $(this).attr('num');
            $('#removePhone' + num).remove();
        })
    </script>

    <script>
        $(document).on('change', '#country_id', function () {
            var id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_cities')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#cities').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '.removeOldEmail', function () {
            var oldEmail = $(this).attr('count');
            $('#removeOldEmail' + oldEmail).remove();
        })
    </script>
    <script>
        $(document).on('click', '.removeOldPhone', function () {
            var oldPhone = $(this).attr('count');
            $('#removeOldPhone' + oldPhone).remove();
        })
    </script>
@endsection