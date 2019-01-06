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

                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.category') }}</label>
                        <select calss="form-control select2"    name="job_category" id="job_category" class="form-control col-md-6">

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->{app()->getLocale().'_name'} }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('job_titles')) has-error @endif">
                        <label>{{ trans('admin.job_title') }}</label>
                        <select calss="form-control select2" name="job_titles" id="job_titles" class="form-control col-md-6" >
                            <option></option>
                        </select>
                    </div>



                    <div class="form-group col-md-6 @if($errors->has('vacancy_id')) has-error @endif">
                        <label>{{ trans('admin.vacancy') }}</label>
                        <select calss="form-control select2" name="vacancy_id" id="vacancy_id" class="form-control col-md-6">
                            <option></option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.cv') }}</label>
                        <input type="file" name="cv" class="form-control" accept="application/pdf , application/msword">
                        <input type="hidden" value="{{ $application->cv }}" name="old_cv">
                    </div>

                    <input value="put" name="_method" type="hidden">
                    <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                        <label>{{ trans('admin.first_name') }}</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $application->first_name }}" placeholder="{!! trans('admin.en_name') !!}">
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('ar_name')) has-error @endif">
                        <label>{{ trans('admin.last_name') }}</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $application->last_name }}" placeholder="{!! trans('admin.ar_name') !!}">
                    </div>

                    <div class="form-group col-md-6 @if($errors->has('en_description')) has-error @endif">
                        <label>{{ trans('admin.email') }}</label>
                        <input name="email" class="form-control" placeholder="{!! trans('admin.email') !!}" value="{{ $application->email }}">
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.phone') }}</label>
                        <input name="phone" class="form-control" placeholder="{!! trans('admin.phone') !!}" value="{{ $application->phone }}">
                    </div>


                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.linkedin') }}</label>
                        <input type="text" name="linkedin" class="form-control" value="{{ $application->linkedin }}">
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.location') }}</label>
                        <input type="text" name="location" class="form-control" value="{{ $application->location }}">
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                        <label>{{ trans('admin.website') }}</label>
                        <input type="text" name="website" class="form-control" value="{{ $application->website }}">
                    </div>
                    <div class="form-group col-md-6 @if($errors->has('status')) has-error @endif">
                        <label>{{ trans('admin.status') }}</label>
                        <select name="acceptness" class="form-control">
                            <option @if($application->acceptness == 'under_review') selected="selected" @endif value="under_review">{{ __('admin.under_review') }}</option>
                            <option @if($application->acceptness == 'shortlisted') selected @endif value="shortlisted">{{ __('admin.shortlisted') }}</option>
                            <option @if($application->acceptness == 'accepted') selected @endif value="accepted">{{ __('admin.accepted') }}</option>
                            <option @if($application->acceptness == 'proposed') selected @endif value="proposed">{{ __('admin.proposed') }}</option>
                            <option @if($application->acceptness == 'rejected') selected @endif value="rejected">{{ __('admin.rejected') }}</option>
                        </select>
                    </div>

                    <input name="vacancy_id" type="hidden" value="{{ $application->vacancy_id }}">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
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
                            <textarea name="notes" class="form-control" placeholder="{!! trans('admin.notes') !!}">{{ @$proposal->description}}</textarea>
                        </div>
                        <input type="hidden" value="{{ $application->id }}" name="application_id">
                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                    </form>
                    <div class="pull-right">
                        <span> {{ __('admin.application_agreed') }} </span><br>
                        <a href="{{ url(adminPath().'/employees/create/'.$application->id) }}" class="btn btn-info">{{ __('admin.add_employee') }}</a>
                    </div>
                </div>

                @endif
            </div>
    </div>
@endsection
@section('js')

    <script>

        $(document).ready(function(){
            $(document).on('change','#job_category',function(){
                var id = $(this).val()
                $.ajax({
                    url: '{{ url(adminPath() . '/change-category') }}',
                    type:'get',
                    dataType:'html',
                    data:{id:id},
                    success:function(data){
                        $('#job_titles').html(data)
                    }
                })
            });

            $(document).on('change','#job_titles',function () {
                var id = $(this).val()
                $.ajax({
                    url: '{{url(adminPath() .'/change-title' )}}',
                    type: 'get',
                    dataType: 'html',
                    data:{id:id},
                    success:function(data){
                        $('#vacancy_id').html(data)
                    }
                })
            });
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