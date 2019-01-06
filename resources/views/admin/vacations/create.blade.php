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
                    <form action="{{ url(adminPath().'/vacations') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                            <label>{{ trans('admin.en_name') }}</label>
                            <input type="text" name="en_name" class="form-control" value="{{ old('en_name') }}" placeholder="{!! trans('admin.en_name') !!}">
                        </div>

                        <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                            <label>{{ trans('admin.ar_name') }}</label>
                            <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name') }}" placeholder="{!! trans('admin.ar_name') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('annual_days')) has-error @endif">
                            <label>{{ __('admin.annual_days') }}</label>
                            <input type="text" name="annual_days" class="form-control" value="{{ old('annual_days') }}" placeholder="{!! trans('admin.ar_name') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('annual_days')) has-error @endif">
                            <label>{{ __('admin.type') }}</label>
                            <select class="form-control" name="type">
                                <option value="occasional">{{ __('admin.occasional') }}</option>
                                <option value="annual">{{ __('admin.annual') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('start_date')) has-error @endif">
                            <label>{{ trans('admin.start_date') }}</label>
                            <input type="text" name="start_date" class="form-control datepicker" value="{{ old('start_date') }}" placeholder="{!! trans('admin.ar_name') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('end_date')) has-error @endif">
                            <label>{{ trans('admin.end_date') }}</label>
                            <input type="text" name="end_date" class="form-control datepicker" value="{{ old('end_date') }}" placeholder="{!! trans('admin.ar_name') !!}">
                        </div>


                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                    </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.datepicker').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            viewMode: "years",
            minViewMode: "years",
        });
    </script>
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