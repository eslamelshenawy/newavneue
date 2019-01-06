@extends('admin.index')

@section('content')
    @include('admin.employee.hr_nav')

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <form action="{{ url(adminPath().'/vacancies') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                        <label>{{ trans('admin.en_name') }}</label>
                        <input type="text" name="en_name" class="form-control" value="{{ old('en_name') }}" placeholder="{!! trans('admin.en_name') !!}">
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                        <label>{{ trans('admin.ar_name') }}</label>
                        <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name') }}" placeholder="{!! trans('admin.ar_name') !!}">
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                        <label>{{ trans('admin.en_description') }}</label>
                        <textarea name="en_description" class="form-control" placeholder="{!! trans('admin.en_description') !!}" rows="6">{{ old('en_description') }}</textarea>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.ar_description') }}</label>
                        <textarea name="ar_description" class="form-control" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ old('ar_description') }}</textarea>
                    </div>
                    <div class="form-group col-md-4 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.job_title') }}</label>
                        <select name="job_title_id" class="form-control">
                            @foreach($jobTitles as $jobTitle)
                                <option value="{{ $jobTitle->id }}">{{ $jobTitle->{app()->getLocale().'_name'} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.status') }}</label>
                        <select name="status" class="form-control">
                            <option value="0">{{ __('admin.open') }}</option>
                            <option value="1">{{ __('admin.closed') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.type') }}</label>
                        <select name="type" class="form-control">
                            <option value="full_time">{{ __('admin.full_time') }}</option>
                            <option value="part_time">{{ __('admin.part_time') }}</option>
                            <option value="freelance">{{ __('admin.freelance') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "320px";
            document.getElementById("main").style.marginLeft = "320px";
            document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        var closeNav = function () {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            document.body.style.backgroundColor = "white";
        }
    </script>
@endsection