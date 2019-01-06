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
                <form action="{{ url(adminPath().'/job_titles/'.$jobTitle->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                        <label>{{ trans('admin.en_name') }}</label>
                        <input type="text" name="en_name" class="form-control" value="{{ $jobTitle->en_name }}" placeholder="{!! trans('admin.en_name') !!}">
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                        <label>{{ trans('admin.ar_name') }}</label>
                        <input type="text" name="ar_name" class="form-control" value="{{ $jobTitle->ar_name }}" placeholder="{!! trans('admin.ar_name') !!}">
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                        <label>{{ trans('admin.en_description') }}</label>
                        <textarea name="en_description" class="form-control" placeholder="{!! trans('admin.en_description') !!}" rows="6">{{ $jobTitle->en_description }}</textarea>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.ar_description') }}</label>
                        <textarea name="ar_description" class="form-control" placeholder="{!! trans('admin.ar_description') !!}" rows="6">{{ $jobTitle->ar_description }}</textarea>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <select name="category_id" class="form-control">
                            @foreach($jobCategories as $jobCategory)
                                <option @if($jobCategory->id === $jobTitle->job_category_id) selected="selected" @endif value="{{ $jobCategory->id }}">{{ $jobCategory->{app()->getLocale().'_name'} }}</option>
                            @endforeach
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
