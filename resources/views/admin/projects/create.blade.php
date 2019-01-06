@extends('admin.index')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default-dark/style.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default/style.min.css">
    <style>
        .tree1, .tree1 ul {
            margin: 0;
            padding: 0;
            list-style: none
        }

        .tree1 ul {
            margin-left: 1em;
            position: relative
        }

        .tree1 ul ul {
            margin-left: .5em
        }

        .tree1 ul:before {
            content: "";
            display: block;
            width: 0;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            border-left: 1px solid
        }

        .tree1 li {
            margin: 0;
            padding: 0 1em;
            line-height: 2em;
            color: #cba42d;
            font-weight: 700;
            position: relative
        }

        .tree1 ul li:before {
            content: "";
            display: block;
            width: 10px;
            height: 0;
            border-top: 1px solid;
            margin-top: -1px;
            position: absolute;
            top: 1em;
            left: 0
        }

        .tree1 ul li:last-child:before {
            background: #fff;
            height: auto;
            top: 1em;
            bottom: 0
        }

        .indicator {
            margin-right: 5px;
        }

        .tree1 li a {
            text-decoration: none;
            color: #cba42d;
        }

        .tree1 li button, .tree1 li button:active, .tree1 li button:focus {
            text-decoration: none;
            color: #cba42d;
            border: none;
            background: transparent;
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
            outline: 0;
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
            <form action="{{ url(adminPath().'/projects') }}" method="post" enctype="multipart/form-data">
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
                    <label>{{ trans('admin.en_description') }}</label>
                    <textarea name="en_description" class="form-control"
                              placeholder="{!! trans('admin.en_description') !!}" rows="6">{{ old('en_description') }}</textarea>
                </div>
                <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                    <label>{{ trans('admin.ar_description') }}</label>
                    <textarea name="ar_description" class="form-control"
                              placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ old('ar_description') }}</textarea>
                </div>
                
                <div class="form-group col-md-6 @if($errors->has('developer_pdf')) has-error @endif">
                    <label>{{ trans('admin.developer_pdf') }}</label>
                    <input type="file" multiple name="developer_pdf[]" class="form-control"
                           placeholder="{!! trans('admin.developer_pdf') !!}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('broker_pdf')) has-error @endif">
                    <label>{{ trans('admin.broker_pdf') }}</label>
                    <input type="file" multiple name="broker_pdf[]" class="form-control"
                           placeholder="{!! trans('admin.broker_pdf') !!}">
                </div>
                
                
                <div class="form-group  col-md-4 {{ $errors->has("developer") ? 'has-error' : '' }}">
                    {!! Form::label(trans("admin.developer")) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" name="developer"
                            data-placeholder="{{ trans("admin.developer") }}">
                        <option></option>
                        @foreach(App\Developer::get() as $developer)
                            @if(old('developer')==$developer->id)
                                <option value="{{ $developer->id }}" selected>{{ $developer->{app()->getLocale().'_name'} }}</option>
                            @else
                                <option value="{{ $developer->id }}">{{ $developer->{app()->getLocale().'_name'} }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group  col-md-4 {{ $errors->has("usage") ? 'has-error' : '' }}">
                    {!! Form::label(trans("admin.type")) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" name="project_type"
                            data-placeholder="{{ trans("admin.type") }}">
                        <option></option>
                        <option value="personal">{{ __('admin.personal') }}</option>
                        <option value="commercial">{{ __('admin.commercial') }}</option>
                    </select>
                </div>
                @php($arr=[])
                @if( old('facility') !==null)
                    @php($arr=old('facility'));
                @endif
                <div class="form-group  {{ $errors->has("facility") ? 'has-error' : '' }} col-md-8">
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
                <div class="form-group col-md-4 {{ $errors->has("tags") ? 'has-error' : '' }}">
                    {!! Form::label(trans("admin.tags")) !!}
                    <br>
                    <select multiple class="select2 form-control" style="width: 100%" name="tags[]"
                            data-placeholder="{{ trans("admin.tags") }}">
                        <option></option>
                        <?php
                        $count=0;
                        if(old('tags'))
                            $count=count(old('tags'));
                        $start=0;
                        ?>
                        @foreach(App\Tag::get() as $tag)
                            @if($count>$start)
                                @if(old('tags')[$start]==$tag->id)
                                    <option value="{{ $tag->id }}" selected>{{ $tag->{app()->getLocale().'_name'} }}</option>
                                    <?php $start++ ?>
                                @else
                                    <option value="{{ $tag->id }}">{{ $tag->{app()->getLocale().'_name'} }}</option>
                                @endif
                            @else
                                <option value="{{ $tag->id }}">{{ $tag->{app()->getLocale().'_name'} }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group  col-md-4 @if($errors->has('meter_price')) has-error @endif">
                    <label>{{ trans('admin.meter_price') }}</label>
                    <input type="number" name="meter_price" class="form-control" value="{{ old('meter_price') }}"
                           placeholder="{!! trans('admin.meter_price') !!}" min="0">
                </div>
                <div class="form-group  col-md-4 @if($errors->has('area')) has-error @endif">
                    <label>{{ trans('admin.area') }}</label>
                    <input type="number" name="area" class="form-control" value="{{ old('area') }}" min="0"
                           placeholder="{!! trans('admin.area') !!}">
                </div>
                <div class="form-group  col-md-4 @if($errors->has('area_to')) has-error @endif">
                    <label>{{ trans('admin.area_to') }}</label>
                    <input type="number" name="area_to" class="form-control" value="{{ old('area_to') }}" min="0"
                           placeholder="{!! trans('admin.area_to') !!}">
                </div>
                <div class="form-group  col-md-4 @if($errors->has('down_payment')) has-error @endif">
                    <label>{{ trans('admin.down_payment') }}</label>
                    <input type="number" name="down_payment" class="form-control" value="{{ old('down_payment') }}"
                           placeholder="{!! trans('admin.down_payment') !!}" min="0">
                </div>
                <div class="form-group  col-md-4 @if($errors->has('ins_years')) has-error @endif">
                    <label>{{ trans('admin.ins_years') }}</label>
                    <input type="number" name="ins_years" class="form-control" value="{{ old('ins_years') }}"
                           placeholder="{!! trans('admin.ins_years') !!}" min="0">
                </div>
                <div class="form-group  @if($errors->has('delivery_date')) has-error @endif col-md-2">
                    <label>{{ trans('admin.delivery_date') }}</label>
                    <div class="input-group">
                        {!! Form::text('delivery_date','',['class' => 'form-control', 'placeholder' => trans('admin.delivery_date'),'readonly'=>'','id'=>'datepicker']) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group  col-md-4 @if($errors->has('video')) has-error @endif">
                    <label>{{ trans('admin.video') }}</label>
                    <input type="text" name="video" class="form-control" value="{{ old('video') }}"
                           placeholder="{!! trans('admin.video') !!}" min="0">
                </div>
                <input type="hidden" placeholder="lat" name="lat" id="lat" value="{{ old('lat') }}">
                <input type="hidden" placeholder="lng" name="lng" id="lng" value="{{ old('lat') }}">
                <input type="hidden" placeholder="zoom" name="zoom" id="zoom" value="{{ old('lat') }}">

                <div class="col-md-4">
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
                              <input type="file" accept="image/png, image/jpeg, image/gif" value="" name="logo"/> <!-- rename it -->
                          </div>
                      </span>
                    </div>
                </div>
                <div class="form-group @if($errors->has('facebook')) has-error @endif col-md-4">
                    <label>{{ trans('admin.facebook') }}</label>
                    {!! Form::text('facebook','',['class' => 'form-control', 'placeholder' => trans('admin.facebook')]) !!}
                </div>
                <div class="form-group @if($errors->has('cover')) has-error @endif">
                    <label>{{ trans('admin.cover') }}</label>
                    <input type="file" accept="image/png, image/jpeg, image/gif" name="cover">
                </div>
                <div class="form-group @if($errors->has('website_cover')) has-error @endif">
                    <label>{{ trans('admin.website_cover') }}</label><br>
                    <label> {{ __('admin.best_image') }} 900 * 536</label>
                    <input type="file" accept="image/png, image/jpeg, image/gif" name="website_cover">
                </div>
                <div class="form-group @if($errors->has("gallery")) has-error @endif">
                    <label>{{ trans("admin.gallery") }}</label>
                    <input multiple type="file" accept="image/png, image/jpeg, image/gif" class="form-control" name="gallery[]">
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.meta_keywords')) !!}
                    <input type="text" name="meta_keywords" class="form-control" data-role="tagsinput" value="{{ old('meta_keywords') }}" style="width: 100%">
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label(trans('admin.meta_description')) !!}
                    <textarea class="form-control" row="6" name="meta_description" value="{{ old('meta_description') }}">{{ old('meta_description') }}</textarea>
                </div>

                <div class="form-group col-md-6">
                    <br/>
                    <input type="hidden" name="featured" value="0">
                    <input type="checkbox" name="featured" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.featured') }}" value="1">
                </div>

                <div class="form-group col-md-6">
                    <br/>
                    <input type="hidden" name="show_website" value="0">
                    <input type="checkbox" name="show_website" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.show_website') }}" value="1">
                </div>

                <div class="form-group col-md-6">
                    <br/>
                    <input type="hidden" name="vacation" value="0">
                    <input type="checkbox" name="vacation" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.vacation') }}" value="1">
                </div>
                <div class="form-group col-md-6">
                    <br/>
                    <input type="hidden" name="mobile" value="0">
                    <input type="checkbox" name="mobile" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.mobile') }}" value="1">
                </div>
                <div class="form-group row @if($errors->has('location_id')) has-error @endif">
                    <div class="form-group col-md-6">
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            {!! Form::label(trans('admin.location')) !!}
                            <select class="select2 form-control" id="location" name="location" data-placeholder="{{ trans('admin.location') }}">
                                @foreach(App\Location::all() as $row1)
                                    <option value="{{ $row1->id }}">{{ $row1->{app()->getLocale().'_name'} }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="location_id" id="location_id" >
                    </div>
                    <div class="col-md-6" id="jstree" style="padding:25px">
                        <ul>
                            @foreach($location as $row)
                                <li style="cursor: pointer" id="{{ $row->id }}" class="child" data-title="{{ $row->title }}" lat="{{ $row->lat }}" lng="{{ $row->lng }}" zoom="{{ $row->zoom }}" data-id=" {{ $row->id }}">
                                    <span class="fa fa-thumb-tack"></span> {{ $row->title }}
                                    @if(count($row->childs))
                                        @include('admin.locations.manageChild',['childs' => $row->childs])
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="map" style="height: 500px;z-index:20"></div>
                </div>
                <div class="checkbox col-sm-4">
                    <label>
                        <input type="checkbox" id="main_slider" name="main_slider">
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                        <label for="main_slider">{{ __('admin.add') . ' ' . __('admin.main_slider') }}</label>
                    </label>
                </div>
                <div class="form-group col-sm-4">
                    <label> {{ __('admin.best_image') }} 1900 * 1000</label>
                    <input class="form-control col-sm-8" name="project_slider" type="file" accept="image/jpeg,image/png,image/jpg">
                </div>
                <div class="form-group col-sm-4 @if($errors->has('map_marker')) has-error @endif">
                    <label> {{ __('admin.best_image') }} 20* 32 </label>
                    <input type="file" class="form-control col-sm-8" name="map_marker" accept="image/jpeg,image/png,image/jpg" >
                </div>

                <input type="hidden" name="type" value="project">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>

    </div>




    </div>
@endsection
@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 30.0595581, lng: 31.2233591},
                zoom: 7,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.


            var marker = new google.maps.Marker({
                position: {lat: 30.0595581, lng: 31.2233591},
                map: map,
                draggable: false,
                animation: google.maps.Animation.DROP
            });


            google.maps.event.addListener(map, 'click', function (event) {
                if(marker)
                {
                    marker.setMap(null);
                    var myLatLng = event.latLng;
                }

                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,

                });
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
                console.log(marker.getPosition().lat());
            })

            google.maps.event.addListener(map, 'zoom_changed', function() {
                $('#zoom').val(map.getZoom())
            });

            $("#jstree").on('changed.jstree', function (e, data) {
                var lat1 = parseFloat($('#'+data.selected).attr('lat'));
                var lng1= parseFloat($('#'+data.selected).attr('lng'));
                var zoom = parseInt($('#'+data.selected).attr('zoom'));
                $('#lat').val(lat1);
                $('#lng').val(lng1);
                $('#zoom').val(zoom);
                console.log(lat1);
                marker.setPosition({ lat:lat1,lng:lng1 } );
                map.setCenter(new google.maps.LatLng(lat1,lng1));
                map.setZoom(zoom);


            })

        }

        $("#jstree").on('changed.jstree', function (e, data) {
            $('#location').select2('val',data.selected);
            $('#get_group').text($('#'+data.selected).attr('data-title'));
            $('#get_location').attr('lat',$('#'+data.selected).attr('lat'));
            $('#get_location').attr('lng',$('#'+data.selected).attr('lng'));
            $('#get_location').attr('zoom',$('#'+data.selected).attr('zoom'));
            var id = data.selected;
            $('#parent_id').val(id);
            $('#location_id').val(id);
            $('#del_loc').val(id);
            //marker.setPosition(place.geometry.location);
        }).jstree({
            'core': {
                "themes": {
                    "dots": false,
                    "icons": false
                }
            },
            'plugins': ['types', 'contextmenu', 'wholerow', 'ui']
        });
        var id ="{{ old("location_id") }}";
        $('#jstree').jstree(true).select_node(id);
        $('#location').on('change', function () {
            var id = $(this).val();
            var old = $(this).attr('old');
            $('#jstree').jstree(true).deselect_node(old);
            $('#jstree').jstree(true).select_node(id);
            $(this).attr('old',id);
        })
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>
    <script>
        $('#datepicker').datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
        });
    </script>

@endsection
