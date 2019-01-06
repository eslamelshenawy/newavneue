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
            <form action={{url(adminPath().'/settings')}} method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group col-md-6 @if($errors->has('title')) has-error @endif">
                    <label>{{ trans('admin.title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $settings->title }}"
                           placeholder="{!! trans('admin.title') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('admin_path')) has-error @endif">
                    <label>{{ trans('admin.admin_path') }}</label>
                    <input type="text" name="admin_path" class="form-control" value="{{ $settings->admin_path }}"
                           placeholder="{!! trans('admin.admin_path') !!}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('lead_source')) has-error @endif">
                    <label>{{ trans('admin.source') }}</label>
                    <select name="theme" class="form-control select2" id="theme" style="width: 100%"
                            data-placeholder="{{ trans('admin.lead_source') }}">
                        <option></option>
                        @foreach($themes as $theme)
                            <option @if($theme == $settings->theme) selected @endif value="{{ $theme}}">
                                {{ $theme }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="lat" id="lat" value="{{ $settings->lat }}">
                <input type="hidden" name="lng" id="lng" value="{{ $settings->lng }}">
                <input type="hidden" name="zoom" id="zoom" value="{{ $settings->zoom }}">
                <div class="form-group col-md-6 @if($errors->has('email')) has-error @endif">
                    <label>{{ trans('admin.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ $settings->email }}"
                           placeholder="{!! trans('admin.email') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('address')) has-error @endif">
                    <label>{{ trans('admin.address').' '.trans('admin.in_english') }}</label>
                    <textarea name="address" rows="6" class="form-control"
                              value="{{ $settings->address }}">{{ $settings->address }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_address')) has-error @endif">
                    <label>{{ trans('admin.address').' '.trans('admin.in_arabic') }}</label>
                    <textarea name="ar_address" rows="6" class="form-control"
                              value="{{ $settings->ar_address }}">{{ $settings->ar_address }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('about_us')) has-error @endif">
                    <label>{{ trans('admin.about_us').' '.trans('admin.in_english') }}</label>
                    <textarea name="about_us" rows="6" class="form-control"
                              value="{{ $settings->about_hub }}">{{ $settings->about_hub }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_about_us')) has-error @endif">
                    <label>{{ trans('admin.about_us').' '.trans('admin.in_arabic') }}</label>
                    <textarea name="ar_about_us" rows="6" class="form-control"
                              value="{{ $settings->ar_about_hub }}">{{ $settings->ar_about_hub }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('get_in_touch')) has-error @endif">
                    <label>{{ trans('admin.get_in_touch').' '.trans('admin.in_english') }}</label>
                    <textarea name="get_in_touch" rows="6" class="form-control"
                              value="{{ $settings->get_in_touch }}">{{ $settings->get_in_touch }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_get_in_touch')) has-error @endif">
                    <label>{{ trans('admin.get_in_touch').' '.trans('admin.in_arabic') }}</label>
                    <textarea name="ar_get_in_touch" rows="6" class="form-control"
                              value="{{ $settings->ar_get_in_touch }}">{{ $settings->ar_get_in_touch }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('mission')) has-error @endif">
                    <label>{{ trans('admin.mission').' '.trans('admin.in_english') }}</label>
                    <textarea name="mission" rows="6" class="form-control"
                              value="{{ $settings->mission }}">{{ $settings->mission }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_mission')) has-error @endif">
                    <label>{{ trans('admin.mission').' '.trans('admin.in_arabic') }}</label>
                    <textarea name="ar_mission" rows="6" class="form-control"
                              value="{{ $settings->ar_mission }}">{{ $settings->ar_mission }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('vision')) has-error @endif">
                    <label>{{ trans('admin.vision').' '.trans('admin.in_english') }}</label>
                    <textarea name="vision" rows="6" class="form-control"
                              value="{{ $settings->vision }}">{{ $settings->vision }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_vision')) has-error @endif">
                    <label>{{ trans('admin.vision').' '.trans('admin.in_arabic') }}</label>
                    <textarea name="ar_vision" rows="6" class="form-control"
                              value="{{ $settings->ar_vision }}">{{ $settings->ar_vision }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('play_store')) has-error @endif">
                    <label>{{ trans('admin.play_store') }}</label>
                    <input type="text" name="play_store" class="form-control" value="{{ $settings->play_store }}"
                           placeholder="{!! trans('admin.play_store') !!}">
                </div>
                <div class="form-group col-md-6 @if($errors->has('apple_store')) has-error @endif">
                    <label>{{ trans('admin.apple_store') }}</label>
                    <input type="text" name="apple_store" class="form-control" value="{{ $settings->apple_store }}"
                           placeholder="{!! trans('admin.apple_store') !!}">
                </div>
                
                <div class="form-group col-md-12 @if($errors->has('mail_provider')) has-error @endif">
                    <label>{{ trans('admin.mail_provider') }}</label>
                    <select class="form-control select2" name="mail_provider"
                            data-placeholder="{{ __('admin.mail_provider') }}">
                        <option></option>
                        <option @if($settings->mail_provider == 'gmail') selected @endif value="gmail">{{ __('admin.gmail') }}</option>
                        <option @if($settings->mail_provider == 'cpanel') selected @endif value="cpanel">{{ __('admin.cpanel') }}</option>
                        <option @if($settings->mail_provider == 'zoho') selected @endif value="zoho" disabled>{{ __('admin.zoho') }}</option>
                    </select>
                </div>
                <div class="form-group col-md-3 @if($errors->has('leads_mail')) has-error @endif">
                    <label>{{ trans('admin.leads_mail') }}</label>
                    <input type="text" name="leads_mail" class="form-control" value="{{ $settings->leads_mail }}"
                           placeholder="{!! trans('admin.leads_mail') !!}">
                </div>
                <div class="form-group col-md-3 @if($errors->has('lead_mail_password')) has-error @endif">
                    <label>{{ trans('admin.lead_mail_password') }}</label>
                    <input type="text" name="lead_mail_password" class="form-control" value="{{ $settings->lead_mail_password }}"
                           placeholder="{!! trans('admin.lead_mail_password') !!}">
                </div>
                
                <div class="col-md-12">
                    <div class="input-group image-preview">
                        <label>{{ trans('admin.logo') }}</label>
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
                        <input type="file" id="imageInput" accept="image/png, image/jpeg, image/gif" name="logo"/>
                        <!-- rename it -->
                    </div>
                </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="input-group image-preview">
                        <label>{{ trans('admin.watermark') }}</label>
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
                        <input type="file" id="" accept="image/png, image/jpeg, image/gif" name="watermark"/>
                        <!-- rename it -->
                    </div>
                </span>
                    </div>
                </div>

                @foreach(@\App\HubPhone::get() as $phone)
                    <div class="form-group @if($errors->has('phone')) has-error @endif col-md-6"
                         id="PhoneOld{{ $phone->id }}">
                        <label>{{ trans('admin.phone') }}</label>
                        <div class="input-group">
                            {!! Form::number('phone[]',$phone->phone,['class' => 'form-control', 'placeholder' => trans('admin.phone')]) !!}
                            @if($loop->first)
                                <span style="cursor: pointer" class="input-group-addon" id="addPhone"><i
                                            class="fa fa-plus"></i></span>
                            @else
                                <span style="cursor: pointer" class="input-group-addon removePhone"
                                      pid="Old{{ $phone->id }}"><i
                                            class="fa fa-minus"></i></span>
                            @endif
                        </div>
                    </div>
                @endforeach
                @foreach(@\App\HubSocial::all() as $social)
                    <input type="hidden" value="{{ $social->id }}" name="social_id[]">
                    <div class="col-md-12" id="DivOld{{ $social->id }}">
                        <div class="form-group @if($errors->has('social_mobile_icon')) has-error @endif col-md-4"
                             id="socialOldMob{{ $social->id }}">
                            <label>{{ trans('admin.social_mobile_icon') }}</label>
                            <div class="">
                                {!! Form::file('social_mobile_icon[]',['class' => 'form-control']) !!}
                                <input type="hidden" value="{{ $social->web_icon }}" name="old_mobile_icon[]">
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('social')) has-error @endif col-md-4"
                             id="socialOldWeb{{ $social->id }}">
                            <label>{{ trans('admin.social_web_icon') }}</label>
                            <div class="">
                                {!! Form::file('social_web_icon[]',['class' => 'form-control']) !!}
                                <input type="hidden" value="{{ $social->web_icon }}" name="old_web_icon[]">
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('social')) has-error @endif col-md-4"
                             id="socialOld{{ $social->id }}">
                            <label>{{ trans('admin.social_url') }}</label>
                            <div class="input-group">
                                {!! Form::text('social_url[]',$social->link,['class' => 'form-control', 'required' => '' , 'placeholder' => trans('admin.social_url')]) !!}
                                @if($loop->first)
                                    <span style="cursor: pointer" class="input-group-addon" id="addSocial"><i
                                                class="fa fa-plus"></i></span>
                                @else
                                    <span style="cursor: pointer" class="input-group-addon removeSocial"
                                          sid="Old{{ $social->id }}"><i
                                                class="fa fa-minus removeSocial"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <span id="otherPhones"></span>

                <div class="col-md-12">
                    <div id="map" style="height: 500px;z-index:20"></div>
                </div>
                <br/>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('change', '#theme', function () {
            var color = $(this).val();
            var lastClass = $('#themeColor').attr('theme');
            $('#themeColor').removeClass(lastClass);
            $('#themeColor').attr('theme', color);
            $('#themeColor').addClass(color);
        })
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>

    <script>

        function initAutocomplete() {

            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 30.0595581, lng: 31.2233591},
                zoom: 7,
                mapTypeId: 'roadmap'
            });

            var marker = new google.maps.Marker({
                position: {lat: 30.0595581, lng: 31.2233591},
                map: map,
                draggable: false,
                animation: google.maps.Animation.DROP
            });


            google.maps.event.addListener(map, 'click', function (event) {
                if (marker) {
                    marker.setMap(null);
                    var myLatLng = event.latLng;
                }

                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,

                });
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
            });

            google.maps.event.addListener(map, 'zoom_changed', function () {
                $('#zoom').val(map.getZoom())
            });

            $('#location').on('change', function () {
                var id = $(this).val();
                var old = $(this).attr('old');
                $('#jstree').jstree(true).deselect_node(old);
                $('#jstree').jstree(true).select_node(id);
                $(this).attr('old', id);
            })
            place();

            function place() {
                var lat2 = parseFloat({{ $settings->lat }});
                var lng2 = parseFloat({{ $settings->lng }});
                var zoom2 = parseInt({{ $settings->zoom }});
                $('#lat').val(lat2);
                $('#lng').val(lng2);
                $('#zoom').val(zoom2);
                marker.setPosition({lat: lat2, lng: lng2});
                map.setCenter(new google.maps.LatLng(lat2, lng2));
                map.setZoom(zoom2);

            }
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
    <script>
        var i = 1;
        var j = 1;
        $(document).on('click', '#addPhone', function () {
            $('#otherPhones').append('<div class="form-group col-md-6" id="Phone' + i + '">' +
                '<label>{{ trans("admin.phone") }}</label>' +
                '<div class="input-group">' +
                '<input name="phone[]" class="form-control" placeholder="{{ trans("admin.phone") }}" type="number" value="">' +
                '<span style="cursor: pointer" class="removePhone input-group-addon" pid="' + i + '"><i' +
                ' class="fa fa-minus"></i></span>' +
                '</div>' +
                '</div>')
        });
        var i = 0;
        $(document).on('click', '#addSocial', function () {
            $('#otherPhones').append('<div class="col-md-12" id="Div' + i + '">' +
                '<div class="form-group @if($errors->has("social_mobile_icon")) has-error @endif col-md-4"' +
                ' id="socialOldMob{{ $social->id }}">' +
                '<label>{{ trans("admin.social_mobile_icon") }}</label>' +
                '<div class="">' +
                '{!! Form::file("social_mobile_icon[]",["class" => "form-control"]) !!}' +
                '</div>' +
                '</div>' +
                '<div class="form-group @if($errors->has("social")) has-error @endif col-md-4"' +
                ' id="socialOldWeb{{ $social->id }}">' +
                '<label>{{ trans("admin.social_web_icon") }}</label>' +
                '<div class="">' +
                '{!! Form::file("social_web_icon[]",["class" => "form-control"]) !!}' +
                '</div>' +
                '</div>' +
                '<div class="form-group @if($errors->has("social")) has-error @endif col-md-4"' +
                ' id="socialOld{{ $social->id }}">' +
                '<label>{{ trans("admin.social_url") }}</label>' +
                '<div class="input-group">' +
                '{!! Form::text("social_url[]",$social->phone,["class" => "form-control", "placeholder" => trans("admin.social_url")]) !!}' +
                '<span style="cursor: pointer" class="input-group-addon removeSocial"' +
                ' sid="' + i + '"><i' +
                ' class="fa fa-minus removSocial"></i></span>' +
                '</div>' +
                '</div>' +
                '</div>');
            i++;
        });
        $(document).on('click', '.removePhone', function () {
            var id = $(this).attr('pid');
            $('#Phone' + id).remove();
        });
        $(document).on('click', '.removeSocial', function () {
            var id = $(this).attr('sid');
            $('#Div' + id).remove();
        })
    </script>


@endsection