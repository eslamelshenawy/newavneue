@extends('admin.index')

@section('content')

    @include('admin.employee.hr_nav')



    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.gross_salary') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">


                {!! Form::open(['method'=>'put' , 'action'=> ['SalariesController@update',$salary->employee_id],'file'=>'true' ,'enctype'=>'multipart/form-data']) !!}


                <div class='form-group col-md-6' id='by'>
                    {!! Form::label('by','Ordered by:') !!}
                    {!! Form::text('by', null,['class'=>'form-control']) !!}
                </div>

                <div class='form-group col-md-6' id='allowances'>
                    {!! Form::label('allowances','Allowances:') !!}
                    {!! Form::number('allowances', 0,['class'=>'form-control']) !!}
                </div>
                <div class='form-group col-md-6' id='date'>
                    {!! Form::label('date','Date:') !!}
                    {!! Form::date('date', null,['class'=>'form-control']) !!}
                </div>


                <div class='form-group col-md-6' id='details'>
                    {!! Form::label('details','Details:') !!}
                    {!! Form::text('details', null,['class'=>'form-control']) !!}
                </div>


                <div class='text-left col-md-12' id='upsal'>
                    {!! Form::submit('update salary',['class'=>'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <div class="col-md-6" id="actions">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.deduction') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">


                {!! Form::open(['method'=>'put' , 'action'=> ['SalariesController@update',$salary->employee_id],'file'=>'true' ,'enctype'=>'multipart/form-data']) !!}


                <div class='form-group col-md-6' id='by'>
                    {!! Form::label('by','Ordered by:') !!}
                    {!! Form::text('by', null,['class'=>'form-control']) !!}
                </div>


                <div class='form-group col-md-6' id='ded'>
                    {!! Form::label('deductions','Deductions:') !!}
                    {!! Form::number('deductions',0 ,['class'=>'form-control']) !!}
                </div>

                <div class='form-group col-md-6' id='date'>
                    {!! Form::label('date','Date:') !!}
                    {!! Form::date('date', null,['class'=>'form-control']) !!}
                </div>

                <div class='form-group col-md-6' id='details'>
                    {!! Form::label('details','Details:') !!}
                    {!! Form::text('details', null,['class'=>'form-control']) !!}
                </div>

                <div class='text-left col-md-12' id='upsalary'>
                    {!! Form::submit('update salary',['class'=>'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}

            </div>
        </div>
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

