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

                <h1>Edit Employee</h1>

                {!! Form::open(['method'=>'put' , 'action'=> ['EmployeeController@update',$employee->id],'file'=>'true' ,'enctype'=>'multipart/form-data']) !!}
                {{--{!! Form::open(['url' => adminPath().'/employees' .$employee->id , 'method'=>'put','file'=>'true' ,'enctype'=>'multipart/form-data']) !!}--}}
                {{--{!! Form::open(['method'=>'PUT' , 'action'=> ['EmployeeController@update', $employee->id],'file'=>'true' ,'enctype'=>'multipart/form-data']) !!}--}}
                <div class = 'form-group col-md-4'>
                    {!! Form::label('en_first_name','First Name:') !!}
                    {!! Form::text('en_first_name', $employee->en_first_name ,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('en_middle_name','Middle Name:') !!}
                    {!! Form::text('en_middle_name', $employee->en_middle_name,['class'=>'form-control']) !!}
                </div>
                <div class = 'form-group col-md-4'>
                    {!! Form::label('en_last_name','Last Name:') !!}
                    {!! Form::text('en_last_name', $employee->en_last_name,['class'=>'form-control']) !!}
                </div>
                <div class = 'form-group col-md-4'>
                    {!! Form::label('ar_first_name','First Name:') !!}
                    {!! Form::text('ar_first_name', $employee->ar_first_name,['class'=>'form-control']) !!}
                </div>
                <div class = 'form-group col-md-4'>
                    {!! Form::label('ar_middle_name','Middle Name:') !!}
                    {!! Form::text('ar_middle_name', $employee->ar_middle_name,['class'=>'form-control']) !!}
                </div>
                <div class = 'form-group col-md-4'>
                    {!! Form::label('ar_last_name','Last Name:') !!}
                    {!! Form::text('ar_last_name', $employee->ar_last_name,['class'=>'form-control']) !!}
                </div>


                <div class = 'form-group col-md-4'>
                    {!! Form::label('national_id','National ID:') !!}
                    {!! Form::text('national_id', $employee->national_id,['class'=>'form-control']) !!}
                </div>


                <div class = 'form-group col-md-4'>
                    {!! Form::label('profile_photo','Profile Image:') !!}
                    {!! Form::file('profile_photo',  null,['class'=>'form-control']) !!}
                </div>


                <div class = 'form-group col-md-4'>
                    {!! Form::label('salary','Salary:') !!}
                    {!! Form::text('salary', $employee->salary,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4 id="gender"'>
                    {!! Form::label('gender','Gender:') !!}
                    {!! Form::select('gender', [''=>'Choose Options'] + array(1=>'female' , 0=>'male')  , null,['class'=>'form-control'])  !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('marital_status','marital_status:') !!}
                    {!! Form::select('marital_status',  [''=>'Choose Options'] + array(4=>'widowed',3=>'divorced', 2=>'married' , 1=>'engaged' , 0=>'single')  , null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4 id="military"'>
                    {!! Form::label('military_status','military_status:') !!}
                    {!! Form::select('military_status',  [''=>'Choose Options'] +array(3=>'female' ,2=>'fullfilled', 1=>'postponed' , 0=>'exempted' ), null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('phone','Phone:') !!}
                    {!! Form::text('phone', $employee->phone,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('personal_mail','Email:') !!}
                    {!! Form::email('personal_mail', $employee->personal_mail,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('company_mail','Company Mail:') !!}
                    {!! Form::email('company_mail', $employee->company_mail,['class'=>'form-control']) !!}
                </div>


                <div class = 'form-group col-md-6'>
                    {!! Form::label('job_category_id','Department:') !!}
                    {!! Form::select('job_category_id',[''=>'Choose Options'] + $categories ,null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-6'>
                    {!! Form::label('job_title_id','Job:') !!}
                    {!! Form::select('job_title_id',  [''=>'Choose Options'] + $job_titles ,null,['class'=>'form-control']) !!}
                </div>



                <div class = 'form-group col-md-3'>
                    {!! Form::submit('Update Employee',['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>
    {{--@endsection--}}
@section('js')
    <script>


        $('document').on('change','.gender',function () {
            if($(this).val() == 'female'){
                $('#military').hide();
            }else{
                $('#military').show();
            }
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
@stop