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
                <form action="{{ url(adminPath().'/vacancies/'.$vacancy->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="_method" value="PUT" type="hidden">
                    <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                        <label>{{ trans('admin.en_name') }}</label> :
                        <label>{{$vacancy->en_name}}</label>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                        <label>{{ trans('admin.ar_name') }}</label> : <label>{{$vacancy->ar_name}}</label>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                        <label>{{ trans('admin.en_description') }}</label> : <label> {{ $vacancy->en_description }} </label>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.ar_description') }}</label> : <label> {{ $vacancy->ar_description }} </label>
                    </div>
                    <div class="form-group col-md-4 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.job_title') }}</label> : <label> {{ $vacancy->jobTitle->{app()->getLocale().'_name'} }} </label>

                    </div>
                    <div class="form-group col-md-4 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.status') }}</label> : <label> {{ $vacancy->status?__('admin.open'):__('admin.closed') }} </label>

                    </div>
                    <div class="form-group col-md-4 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.type') }}</label> : <label>
                            @if($vacancy->type == 'full_time') {{ __('admin.full_time') }} @endif
                            @if($vacancy->type == 'part_time') {{ __('admin.part_time') }} @endif
                            @if($vacancy->type == 'freelance') {{ __('admin.freelance') }} @endif
                        </label>
                    </div>
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