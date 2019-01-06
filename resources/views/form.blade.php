<!DOCTYPE html>
<html lang="en">
@php($fields = json_decode($form->fields))
<head>
    <title>{{ $title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('style/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .bg {
            background: url("{{ url('uploads/'.$form->background) }}");
            height: 100%;
            width: 100%;
            background-size: cover;
            position: absolute;
            /*background-attachment: fixed;*/
        }

        .form-group {
            padding: 30px 30px 0px 30px;
        }

        .form-control {
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        label {
            color: #fff;
        }

        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
            background-color: rgba(0, 0, 0, 0.3) !important;
            color: #fff !important;
        }
    </style>
</head>
<body>
<div class="bg">
    <div class="container">
        <div class="row" style="margin: 25px 0px 25px 0px">
            <img src="{{ url('uploads/logo.png') }}" height="100" class="pull-left">
            @if($form->type == 'project')
                <img src="{{ url('uploads/' . @$form->project->logo) }}" height="100" class="pull-right">
            @elseif($form->type == 'event')
                <img src="{{ url('uploads/' . @$form->event->logo) }}" height="100" class="pull-right">
            @elseif($form->type == 'campaign')
                <img src="{{ url('uploads/' . @$form->campaign->logo) }}" height="100" class="pull-right">
            @endif
        </div>
        <div class="row" style="background: rgba(0, 0, 0, 0.3); height: auto; border-radius: 10px">
            <form action="{{ url('form-lead') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="prefix">{{ __('admin.prefix_name') }}: <span style="color: red;">*</span></label>
                    <select class="form-control" required name="prefix_name">
                        <option disabled selected>{{ __('admin.prefix_name') }}</option>
                        <option value="mr">{{ __('admin.mr') }}</option>
                        <option value="mrs">{{ __('admin.mrs') }}</option>
                        <option value="ms">{{ __('admin.ms') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="first_name">{{ __('admin.first_name') }}: <span style="color: red;">*</span></label>
                    <input type="text" required name="first_name" class="form-control" id="first_name"
                           placeholder="{{ __('admin.first_name') }}">
                </div>

                <div class="form-group">
                    <label for="last_name">{{ __('admin.last_name') }}: <span style="color: red;">*</span></label>
                    <input type="text" required name="last_name" class="form-control" id="last_name"
                           placeholder="{{ __('admin.last_name') }}">
                </div>

                <div class="form-group">
                    <label for="Phone">{{ __('admin.phone') }}: <span style="color: red;">*</span></label>
                    <input type="text" required name="phone" class="form-control" id="Phone"
                           placeholder="{{ __('admin.phone') }}">
                </div>
                @foreach($fields as $field => $k)
                    @if($field == 'image')
                        <div class="form-group">
                            <label for="{{ $field }}">{{ __('admin.'.$field) }}: @if($k)<span style="color: red;">*</span>@endif</label>
                            <input type="file" @if($k) required @endif name="{{ $field }}" class="form-control" id="{{ $field }}"
                                   placeholder="{{ __('admin.'.$field) }}">
                        </div>
                    @elseif($field == 'email')
                        <div class="form-group">
                            <label for="{{ $field }}">{{ __('admin.'.$field) }}: @if($k)<span style="color: red;">*</span>@endif</label>
                            <input type="email" @if($k) required @endif name="{{ $field }}" class="form-control" id="{{ $field }}"
                                   placeholder="{{ __('admin.'.$field) }}">
                        </div>
                    @elseif($field == 'religion')
                        <div class="form-group">
                            <label for="{{ $field }}">{{ __('admin.'.$field) }}: @if($k)<span style="color: red;">*</span>@endif</label>
                            <select class="form-control" @if($k) required @endif name="{{ $field }}">
                                <option disabled selected>{{ $field }}</option>
                                <option value="muslim">{{ __('admin.muslim') }}</option>
                                <option value="christian">{{ __('admin.christian') }}</option>
                                <option value="jewish">{{ __('admin.jewish') }}</option>
                                <option value="other">{{ __('admin.other') }}</option>
                            </select>
                        </div>
                    @elseif($field == 'birth_date')
                        <div class="form-group">
                            <label for="{{ $field }}">{{ __('admin.'.$field) }}: @if($k)<span style="color: red;">*</span>@endif</label>
                            <input type="text" @if($k) required @endif readonly name="{{ $field }}" class="form-control datepicker" id="{{ $field }}"
                                   placeholder="{{ __('admin.'.$field) }}">
                        </div>
                    @else
                        <div class="form-group">
                            <label for="{{ $field }}">{{ __('admin.'.$field) }}: @if($k)<span style="color: red;">*</span>@endif</label>
                            <input type="text" @if($k) required @endif name="{{ $field }}" class="form-control" id="{{ $field }}"
                                   placeholder="{{ __('admin.'.$field) }}">
                        </div>
                    @endif
                @endforeach
                <input type="hidden" value="{{ $id }}" name="form_id">
                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        {{ __('admin.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ url('style/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    </script>
</footer>
</html>