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
            {!! Form::open(['url' => adminPath().'/forms','enctype'=>'multipart/form-data']) !!}
            <div class="row">
                <div class="form-group @if($errors->has('type')) has-error @endif col-md-12">
                    <label>{{ trans('admin.type') }}</label>
                    <select class="form-control select2" name="type" id="type"
                            data-placeholder="{{ __('admin.type') }}">
                        <option></option>
                        <option value="project">{{ __('admin.project') }}</option>
                        <option value="event">{{ __('admin.event') }}</option>
                        <option value="campaign">{{ __('admin.campaign') }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group @if($errors->has('ar_title')) has-error @endif col-md-6">
                    <label>{{ trans('admin.ar_title') }}</label>
                    {!! Form::text('ar_title','',['class' => 'form-control', 'placeholder' => trans('admin.ar_title')]) !!}
                </div>
                <div class="form-group @if($errors->has('en_title')) has-error @endif col-md-6">
                    <label>{{ trans('admin.en_title') }}</label>
                    {!! Form::text('en_title','',['class' => 'form-control', 'placeholder' => trans('admin.en_title')]) !!}
                </div>
            </div>

            <div class="row">
                <div class="form-group @if($errors->has('lead_source_id')) has-error @endif col-md-6">
                    <label>{{ trans('admin.lead_source') }}</label>
                    <select class="form-control select2" name="lead_source_id"
                            data-placeholder="{{ __('admin.lead_source') }}">
                        <option></option>
                        @foreach(@\App\LeadSource::get() as $src)
                            <option value="{{ $src->id }}">{{ $src->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if($errors->has('background')) has-error @endif col-md-6">
                    <label>{{ trans('admin.background') }}</label>
                    {!! Form::file('background',['class' => 'form-control', 'placeholder' => trans('admin.background')]) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group @if($errors->has('fields')) has-error @endif col-md-9">
                    <label>{{ trans('admin.fields') }}</label>
                    <select class="form-control select2" name="fields[0]" data-placeholder="{{ __('admin.fields') }}">
                        <option></option>
                        @foreach($fields as $field)
                            <option value="{{ $field }}">{{ __('admin.'.$field) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <br/>
                    <input type="hidden" name="required[0]" value="0">
                    <input type="checkbox" name="required[0]" class="switch-box"
                           data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.required') }}"
                           value="1">
                </div>
                <div class="col-md-1">
                    <br/>
                    <i class="fa fa-plus" href="#" id="addField"
                       style="font-size: 1.8em; cursor: pointer; padding-top: 10px"></i>
                </div>
            </div>

            <span id="Fields"></span>

            <div class="well" style="display: none" id="projects">
                <div class="form-group @if($errors->has('developer_id')) has-error @endif">
                    <label>{{ trans('admin.developer') }}</label>
                    <select class="form-control select2" name="developer_id" id="developer_id"
                            data-placeholder="{{ __('admin.developer') }}" style="width: 100%">
                        <option></option>
                        @foreach(@\App\Developer::get() as $dev)
                            <option value="{{ $dev->id }}">
                                {{ $dev->{app()->getLocale().'_name'} }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <span id="getProjects"></span>
            </div>

            <div class="well" style="display: none" id="events">
                <div class="form-group @if($errors->has('event_id')) has-error @endif">
                    <label>{{ trans('admin.event') }}</label>
                    <select class="form-control select2" name="event_id" id="event_id"
                            data-placeholder="{{ __('admin.event') }}" style="width: 100%">
                        <option></option>
                        @foreach(@\App\Event::get() as $evnet)
                            <option value="{{ $evnet->id }}">
                                {{ $evnet->{app()->getLocale().'_title'} }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="well" style="display: none" id="campaigns">
                <div class="form-group @if($errors->has('campaign_id')) has-error @endif">
                    <label>{{ trans('admin.campaign') }}</label>
                    <select class="form-control select2" name="campaign_id" id="campaign_id"
                            data-placeholder="{{ __('admin.campaign') }}" style="width: 100%">
                        <option></option>
                        @foreach(@\App\Campaign::get() as $evnet)
                            <option value="{{ $evnet->id }}">
                                {{ $evnet->{app()->getLocale().'_title'} }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('change', '#developer_id', function () {
            var id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_form_projects') }}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#getProjects').html(data);
                    $('.select2').select2();
                }
            });
        });

        $(document).on('change', '#type', function () {
            if ($(this).val() == 'project') {
                $('#projects').show(50);
                $('#events').hide(50);
                $('#campaigns').hide(50);
            } else if ($(this).val() == 'event') {
                $('#projects').hide(50);
                $('#campaigns').hide(50);
                $('#events').show(50);
            } else if ($(this).val() == 'campaign') {
                $('#projects').hide(50);
                $('#campaigns').show(50);
                $('#events').hide(50);
            }
        })
    </script>
    <script>
        var i = 1;
        $(document).on('click', '#addField', function () {
            $('#Fields').append('<div class="row" id="field' + i + '">' +
                '<div class="form-group @if($errors->has("fields")) has-error @endif col-md-9">' +
                '<label>{{ trans("admin.fields") }}</label>' +
                '<select class="form-control select2" name="fields[' + i + ']" data-placeholder="{{ __("admin.fields") }}">' +
                '<option></option>' +
                '@foreach($fields as $field)' +
                '<option value="{{ $field }}">{{ __("admin.".$field) }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<br/>' +
                '<input type="hidden" name="required[' + i + ']" value="0">' +
                '<input type="checkbox" name="required[' + i + ']" class="switch-box"' +
                'data-on-text="{{ __("admin.yes") }}"' +
                'data-off-text="{{ __("admin.no") }}" data-label-text="{{ __("admin.required") }}"' +
                'value="1">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<br/>' +
                '<i class="fa fa-minus removeField" count="' + i + '" href="#" style="font-size: 1.8em; cursor: pointer; padding-top: 10px"></i>' +
                '</div>' +
                '</div>');
            $('.select2').select2();
            $(".switch-box").bootstrapSwitch();
            i++;
        });

        $(document).on('click', '.removeField', function () {
            var count = $(this).attr('count');
            $('#field' + count).remove();
        })
    </script>
@endsection
