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
                @if(!$vacancy_id)
                    <form action="{{ url(adminPath().'/applications') }}" method="post" enctype="multipart/form-data">
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
                            <input type="hidden"  name="old_cv">
                        </div>

                        <div class="form-group col-md-6 @if($errors->has('en_name')) has-error @endif">
                            <label>{{ trans('admin.first_name') }}</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('en_name') }}"
                                   placeholder="{!! trans('admin.first_name') !!}">
                        </div>

                        <div class="form-group col-md-6 @if($errors->has('last_name')) has-error @endif">
                            <label>{{ trans('admin.last_name') }}</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                                   placeholder="{!! trans('admin.last_name') !!}">
                        </div>

                        <div class="form-group col-md-6 @if($errors->has('email')) has-error @endif">
                            <label>{{ trans('admin.email') }}</label>
                            <input name="email" class="form-control" placeholder="{!! trans('admin.email') !!}">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('admin.phone')) has-error @endif">
                            <label>{{ trans('admin.phone') }}</label>
                            <input name="phone" class="form-control" placeholder="{!! trans('admin.phone') !!}">
                        </div>


                        <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                            <label>{{ trans('admin.linkedin') }}</label>
                            <input type="text" name="linkedin" class="form-control">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                            <label>{{ trans('admin.location') }}</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('ar_description')) has-error @endif">
                            <label>{{ trans('admin.website') }}</label>
                            <input type="text" name="website" class="form-control">
                        </div>

                        <div class="form-group col-md-6 @if($errors->has('applied_date')) has-error @endif">
                            <label>{{ trans('admin.applied_date') }}</label>
                            <input type="date" name="applied_date" class="form-control">
                        </div>

                        @endif
                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                        </div>

                    </form>
            </div>
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