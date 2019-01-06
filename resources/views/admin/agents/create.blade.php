@extends('admin.index')
@section('content')
    <style>
        #show_image {
            display: none;
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
            <form action="{{ url(adminPath().'/agent') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group @if($errors->has('name')) has-error @endif col-md-12">
                    <label>{{ trans('admin.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           placeholder="{!! trans('admin.name') !!}">
                </div>
                <div class="form-group @if($errors->has('email')) has-error @endif col-md-12">
                    <label>{{ trans('admin.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{!! trans('admin.email') !!}">
                </div>
                <div class="form-group @if($errors->has('password')) has-error @endif col-md-12">
                    <label>{{ trans('admin.password') }}</label>
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}"
                           placeholder="{!! trans('admin.password') !!}">
                </div>
                <div class="form-group @if($errors->has('email_password')) has-error @endif col-md-12">
                    <label>{{ trans('admin.email_password') }}</label>
                    <input type="password" name="email_password" class="form-control"
                           value="{{ old('email_password') }}"
                           placeholder="{!! trans('admin.email_password') !!}">
                    <span style="color: red">{{ __('admin.please_enable_less_secure_in_gmail') }}</span> <a
                            href="https://myaccount.google.com/lesssecureapps"
                            target="_blank">{{ __('admin.click_here') }}</a>
                </div>
                <div class="form-group @if($errors->has('phone')) has-error @endif col-md-12">
                    <label>{{ trans('admin.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                           placeholder="{!! trans('admin.phone') !!}">
                </div>

                <div class="form-group @if($errors->has('agent_source')) has-error @endif col-md-12">
                    <label>{{ trans('admin.agent_type') }}</label>
                    <select name="agent_source" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.agent_type') }}">
                        <option></option>
                        @foreach(App\AgentType::get() as $lead)
                            <option value="{{ $lead->id }}">
                                {{ $lead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group @if($errors->has('residential_commercial')) has-error @endif col-md-12">
                    <label>{{ trans('admin.residential_commercial') }}</label>
                    <select class="form-control select2" name="residential_commercial" style="width: 100%"
                            data-placeholder="{{ __('admin.residential_commercial') }}">
                        <option></option>
                        <option value="residential">{{ __('admin.personal') }}</option>
                        <option value="commercial">{{ __('admin.commercial') }}</option>
                    </select>
                </div>

                <div class="form-group @if($errors->has('type')) has-error @endif col-md-2">
                    <label>{{ trans('admin.type') }}</label>
                    <br/>
                    <input type="hidden" name="type" value="agent">
                    <input type="checkbox" id="agentType" name="type" class="switch-box"
                           data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.admin') }}" value="admin">
                </div>

                <div class="form-group @if($errors->has('role_id')) has-error @endif col-md-10" id="roles">
                    <label>{{ trans('admin.role') }}</label>
                    <select name="role_id" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.role') }}">
                        <option></option>
                        @foreach(App\Role::get() as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                </div>
                <div class="col-md-12">
                    <br>
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

