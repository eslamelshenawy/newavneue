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
            <form action="{{ url(adminPath().'/leads') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group @if($errors->has('first_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.prefix_name') }}</label>
                    <select name="prefix_name" class="form-control select2"
                            data-placeholder="{!! trans('admin.prefix_name') !!}">
                        <option></option>
                        <option value="mr">{{ trans('admin.mr') }}</option>
                        <option value="mrs">{{ trans('admin.mrs') }}</option>
                        <option value="ms">{{ trans('admin.ms') }}</option>
                    </select>
                </div>
                <div class="form-group @if($errors->has('first_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.first_name') }}</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                           placeholder="{!! trans('admin.first_name') !!}">
                </div>
                <div class="form-group @if($errors->has('last_name')) has-error @endif col-md-4">
                    <label>{{ trans('admin.last_name') }}</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                           placeholder="{!! trans('admin.last_name') !!}">
                </div>



                <div class="form-group @if($errors->has('email')) has-error @endif col-md-11">
                    <label>{{ trans('admin.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{!! trans('admin.email') !!}">
                </div>
                <div class="col-md-1">
                    <br/>
                    <a class="btn btn-social-icon btn-plus" style="margin-top: 5px;" id="addEmail"><i
                                class="fa fa-plus"></i></a>
                </div>
                <span id="otherEmails"></span>
                <div class="form-group @if($errors->has('phone')) has-error @endif col-md-11">
                    <label>{{ trans('admin.phone') }}</label>
                    <div class="input-group">
                        <input type="number" name="phone" class="form-control" value="{{ old('phone') }}"
                               placeholder="{!! trans('admin.phone') !!}">
                        <span class="input-group-addon" style="padding-bottom: 3px">
                            <label class="fa fa-whatsapp" style="color: #34af23;">
                                <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"
                                     style="position: relative;">
                                    <input type="hidden" name="social[whatsapp]" value="0">
                                    <input type="checkbox" name="social[whatsapp]" value="1" class="minimal"
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
                                    <input type="checkbox" name="social[sms]" value="1" class="minimal"
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
                                    <input type="checkbox" name="social[viber]" value="1" class="minimal"
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
                <span id="otherPhones"></span>

                <div class="form-group @if($errors->has('lead_source')) has-error @endif col-md-12">
                    <label for="lead_source">{{ trans('admin.source') }}</label>
                    <select name="lead_source" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.lead_source') }}">
                        <option></option>
                        @foreach(App\LeadSource::get() as $lead)
                            <option value="{{ $lead->id }}">
                                {{ $lead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label>{{ trans('admin.reference') }}</label>
                    <input type="text" name="reference" class="form-control" placeholder="{{ trans('admin.reference') }}">
                </div>

                <div class="form-group col-md-12">
                    <label>{{ trans('admin.notes') }}</label>
                    <textarea name="notes" class="form-control" placeholder="{{ trans('admin.notes') }}"
                              rows="5"></textarea>
                </div>
                @if(auth()->user()->type == 'admin' or App\Role::find(auth()->user()->role_id)->name == "admin")
                <div class="form-group col-md-12">
                    <label>{{ trans('admin.residential_agent') }}</label>
                    <select class="form-control select2" name="agent_id" data-placeholder="{{ __('admin.residential_agent') }}">
                        <option></option>
                        <option value="no_agent">no agent</option>
                        @foreach(@\App\User::where('residential_commercial', 'residential')->get() as $agent)
                            <option value="{{ $agent->id }}" >{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label>{{ trans('admin.commercial_agent') }}</label>
                    <select class="form-control select2" name="commercial_agent_id" data-placeholder="{{ __('admin.commercial_agent') }}">
                        <option></option>
                        @foreach(@\App\User::where('residential_commercial', 'commercial')->get() as $agent)
                            <option value="{{ $agent->id }}" @if($agent->id == auth()->id()) selected @endif>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <!-- image-preview-filename input [CUT FROM HERE]-->
                <div class="col-md-12">
                    <div class="input-group image-preview">
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
                    <br/>
                </div>
                <div class="well col-md-12">
                <div class="col-md-12">
                    <h3 class="box-title">{{ __('admin.add_request') }}</h3>
                </div>
                    <div class="form-group {{ $errors->has('unit_type') ? 'has-error' : '' }} col-md-2">
                        {!! Form::label(trans('admin.buyer_seller')) !!}
                        <select class="select2 form-control" id="unit_type" name="buyer_seller" style="width: 100%"
                                data-placeholder="{{ trans('admin.type') }}">
                            <option></option>
                            <option value="buyer">{{ trans('admin.buyer') }}</option>
                            <option value="seller">{{ trans('admin.seller') }}</option>
                            
                        </select>
                    </div>
                    <div class="form-group {{ $errors->has('request_type') ? 'has-error' : '' }} col-md-2">
                        {!! Form::label(trans('admin.request_type')) !!}
                        <select class="select2 form-control" id="type" name="request_type" style="width: 100%"
                                data-placeholder="{{ trans('admin.request_type') }}">
                            <option></option>
                            <option value="resale">{{ trans('admin.resale') }}</option>
                            <option value="rental">{{ trans('admin.rental') }}</option>
                            <option value="new_home">{{ trans('admin.new_home') }}</option>
                        </select>
                    </div>
                    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }} col-md-2">
                        {!! Form::label(trans('admin.location')) !!}
                        <select class="select2 form-control" id="location" name="request_location" style="width: 100%"
                                data-placeholder="{{ trans('admin.location') }}">
                            <option></option>
                            @foreach(@\App\Location::all() as $location)
                                <option value="{{ $location->id }}">{{ $location->{app()->getLocale().'_name'} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group {{ $errors->has('unit_type') ? 'has-error' : '' }} col-md-2">
                        {!! Form::label(trans('admin.type')) !!}
                        <select class="select2 form-control" id="unit_type" name="request_unit_type" style="width: 100%"
                                data-placeholder="{{ trans('admin.type') }}">
                            <option></option>
                            <option value="commercial">{{ trans('admin.commercial') }}</option>
                            <option value="personal">{{ trans('admin.personal') }}</option>
                            <option value="land">{{ trans('admin.land') }}</option>
                        </select>
                    </div>
    
                    <div class="form-group {{ $errors->has('unit_type_id') ? 'has-error' : '' }} col-md-2">
                        {!! Form::label(trans('admin.unit_type')) !!}
                        <select class="select2 form-control" id="unit_type_id" name="request_unit_type_id" style="width: 100%"
                                data-placeholder="{{ trans('admin.unit_type') }}">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group {{ $errors->has('unit_type_id') ? 'has-error' : '' }} col-md-12">
                        {!! Form::label(trans('admin.project')) !!}
                        <select class="select2 form-control"  name="request_project_id[]" style="width: 100%"
                                data-placeholder="{{ trans('admin.unit_type') }}" multiple>
                            <option></option>
                            @foreach(App\Project::all() as $project)
                                <option value = "{{ $project->id }}"> {{ $project->{app()->getLocale().'_name'} }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="text-center col-md-12">
                    <br>
                    <button type="button" class="btn btn-success btn-flat"
                            id="addContact">{{ trans('admin.add_contact') }}</button>
                </div>
                <br>
                <span id="contacts"></span>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('change', '#unit_type', function () {
           
            var usage = $(this).val();
          
                var _token = '{{ csrf_token() }}';
                $.ajax({
                    url: "{{ url(adminPath().'/get_unit_types')}}",
                    method: 'post',

                    data: {usage: usage, _token: _token},
                    success: function (data) {
                        $('#unit_type_id').html(data);
                    }
                });
           
        });
    </script>
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
            var num = $(this).attr('num');
            $('#removeEmail' + num).remove();
        })
    </script>

    <script>
        var x = 1;
        $(document).on('click', '#addPhone', function () {
            $('#otherPhones').append('<span id="removePhone' + x + '"><div class="form-group col-md-11">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="other_phones[' + x + ']" class="form-control"' +
                'placeholder="{!! trans("admin.other_phones") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">' +
                '<input type="hidden" name="other_socials[' + x + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="other_socials[' + x + '][whatsapp]" value="1" class="minimal" style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">' +
                '<input type="hidden" name="other_socials[' + x + '][sms]" value="0">' +
                '<input type="checkbox" name="other_socials[' + x + '][sms]" value="1" class="minimal" style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">' +
                '<input type="hidden" name="other_socials[' + x + '][viber]" value="0">' +
                '<input type="checkbox" name="other_socials[' + x + '][viber]" value="1" class="minimal" style="position: absolute; opacity: 0;">' +
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
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            x++
        });

        $(document).on('click', '.removePhone', function () {
            var num = $(this).attr('num');
            $('#removePhone' + num).remove();
        })
    </script>

    <script>
        var y = 1;
        $(document).on('click', '#addContact', function () {
            $('#contacts').append('<div class="well col-md-12" style="" id="removeContact' + y + '">' +
                '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.name") }}</label>' +
                '<input type="text" name="contact_name[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.name") }}" required>' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.relation") }}</label>' +
                '<input type="text" name="contact_relation[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.relation") }}">' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.phone") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="contact_phone[' + y + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.phone") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][whatsapp]" value="1" class="minimal"' +
                'style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                'style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][sms]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][sms]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][viber]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][viber]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon addContactPhone" count="' + y + '" style="cursor: pointer">' +
                '<a class="fa fa-plus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<span id="otherContactPhones' + y + '"></span>' +
                '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.email") }}</label>' +
                '<div class="input-group">' +
                '<input type="email" name="contact_email[' + y + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.email") !!}">' +
                '<span class="input-group-addon addContactEmail" count="' + y + '" style="cursor: pointer">' +
                '<a class="fa fa-plus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<span id="otherContactEmails' + y + '"></span>' +
                '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.job_title") }}</label>' +
                '<select name="contact_title_id[' + y + ']" class="form-control select2"' +
                'data-placeholder="{!! trans("admin.job_title") !!}">' +
                '<option></option>' +
                '@foreach(@\App\Title::all() as $title1)' +
                '<option value="{{ $title1->id }}">{{ $title1->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<label>{{ trans("admin.nationality") }}</label>' +
                '<select name="contact_nationality[' + y + ']" class="form-control select2"' +
                'data-placeholder="{!! trans("admin.nationality") !!}">' +
                '<option></option>' +
                '@foreach(@\App\Country::all() as $country)' +
                '<option value="{{ $country->id }}">{{ $country->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="text-center col-md-12">' +
                '<button type="button" class="btn btn-danger btn-flat removeContact" num="' + y + '">' +
                '{{ trans("admin.remove") }}</button>' +
                '</div>' +
                '</div>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            y++;
            $('.select2').select2()
        });

        $(document).on('click', '.removeContact', function () {
            var num = $(this).attr('num');
            $('#removeContact' + num).remove();
        })
    </script>
    <script>
        var z = 1;
        $(document).on('click', '.addContactPhone', function () {
            var count = $(this).attr('count');
            $('#otherContactPhones' + count).append('<div class="form-group col-md-12" id="otherContactPhone' + z + '">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="contact_other_phones[' + count + '][' + z + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.other_phones") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][whatsapp]" value="1" class="minimal"' +
                'style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                'style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][sms]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][sms]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][viber]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][viber]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon removeContactPhone" count="' + z + '" style="cursor: pointer">' +
                '<a class="fa fa-minus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            z++
        });

        $(document).on('click', '.removeContactPhone', function () {
            var contactPhone = $(this).attr('count');
            $('#otherContactPhone' + contactPhone).remove();
        })
    </script>
    <script>
        var i = 1;
        $(document).on('click', '.addContactEmail', function () {
            var count = $(this).attr('count');
            $('#otherContactEmails' + count).append('<div class="form-group col-md-12" id="otherContactEmail' + i + '">' +
                '<label>{{ trans("admin.other_emails") }}</label>' +
                '<div class="input-group">' +
                '<input type="email" name="contact_other_emails[' + count + '][' + i + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.other_emails") !!}">' +
                '<span class="input-group-addon removeContactEmail" count="' + i + '" style="cursor: pointer">' +
                '<a class="fa fa-minus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>');
            i++
        });

        $(document).on('click', '.removeContactEmail', function () {
            var contactEmail = $(this).attr('count');
            $('#otherContactEmail' + contactEmail).remove();
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
        $('.datepicker').datepicker('setDate', new Date(1990, 00, 01));
         $('.select2').select2({
                
                  allowClear: true
                });
        
       
    </script>
@endsection