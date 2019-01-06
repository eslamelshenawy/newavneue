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
                <form action="{{ url(adminPath().'/applications/'.$application->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input value="put" name="_method" type="hidden">
                    <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                        <label>{{ trans('admin.first_name') }}</label> : <label> {{ $application->first_name }} {{ $application->last_name }}</label>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                        <label>{{ trans('admin.last_name') }}</label> : <label>{{ $application->email }}</label>

                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.phone') }}</label> : <label> {{ $application->phone }} </label>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.cv') }}</label> : <a href="{{ url('uploads/'.$application->cv) }}"> CV </a>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.linkedin') }}</label>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.website') }}</label> : <a href="{{ url($application->website) }}"> {{ __('admin.website') }}</a>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.location') }}</label> : <label>{{ url('uploads/'.$application->location) }}</label>
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.status') }}</label>
                        @if($application->acceptness == 'under_review'){{ __('admin.under_review') }}@endif
                        @if($application->acceptness == 'shortlisted'){{ __('admin.shortlisted') }}@endif
                        @if($application->acceptness == 'accepted'){{ __('admin.accepted') }}@endif
                        @if($application->acceptness == 'proposed'){{ __('admin.proposed') }}@endif
                        @if($application->acceptness == 'rejected'){{ __('admin.rejected') }}@endif
                    </div>
                    <input name="vacancy_id" type="hidden" value="{{ $application->vacancy_id }}">
                </form>
            </div>
        </div>
        @if($application->acceptness == 'proposed')
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('admin.proposal') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    @php $url =''; @endphp
                    @if(isset($proposal->id))
                        @php $url = adminPath().'/applications/proposed/'.$proposal->id @endphp
                    @else
                        @php $url = adminPath().'/applications/proposed/' @endphp
                    @endif
                    <form action="{{ url($url) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if(isset($proposal->id))
                            <input name="_method" type="hidden" value="PUT">
                        @endif
                        <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                            <label>{{ trans('admin.salary') }}</label>
                            <input type="text" name="salary" class="form-control" value="{{ @$proposal->salary}}" placeholder="{!! trans('admin.salary') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                            <label>{{ trans('admin.days_off') }}</label>
                            <input type="text" name="days_off" class="form-control" value="{{ @$proposal->days_off}}" placeholder="{!! trans('admin.days_off') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                            <label>{{ trans('admin.notes') }}</label>
                            <textarea type="text" name="notes" class="form-control" placeholder="{!! trans('admin.notes') !!}">{{ @$proposal->description}}</textarea>
                        </div>
                        <input type="hidden" value="{{ $application->id }}" name="application_id">
                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                    </form>
                </div>
            </div>
    </div>
    @endif
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