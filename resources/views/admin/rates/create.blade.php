@extends('admin.index')

@section('content')
    @include('admin.employee.hr_nav')


<h3 class="box-title">{{ trans('admin.rating') }}</h3>
<div class="col-md-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('admin.rating') }}</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">

                    {!! Form::open(['method'=>'POST' , 'action'=> 'RatesController@store']) !!}
                    {{--<input type="hidden" value="{{ $employee->id }}" name="rated_employee_id">--}}
                        <div class='form-group col-md-6' id='usRate'>
                            {!! Form::label('rated_employee_id',' Rated Person:') !!}
                            {!! Form::select('rated_employee_id',[''=>'Choose Options'] +$employee,  null,['class'=>'form-control']) !!}
                        </div>

                    <div class="form-group col-md-6 @if($errors->has('employees')) has-error @endif">
                        <label>{{ trans('admin.rate_person') }}</label>
                        <select multiple class="form-control select2" name="employee_id[]"
                                style="width: 100%"
                                data-placeholder="{{ trans('admin.rate_person') }}">
                            <option></option>
                            @foreach(@\App\Employee::where ('id','!=',$employee->id)->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->en_first_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class='text-left col-md-12' id='Ratting'>
                        {!! Form::submit('Ratting',['class'=>'btn btn-info']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
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
    @stop
