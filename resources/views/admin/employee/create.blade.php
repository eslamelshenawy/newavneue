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

                <h1>Create Employee</h1>

                {!! Form::open(['method'=>'POST' , 'action'=> 'EmployeeController@store','file'=>'true' ,'enctype'=>'multipart/form-data']) !!}

                <div class = 'form-group col-md-4'>
                    {!! Form::label('en_first_name','First Name:') !!}
                    {!! Form::text('en_first_name', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('en_middle_name','Middle Name:') !!}
                    {!! Form::text('en_middle_name', null,['class'=>'form-control']) !!}
                </div>
                <div class = 'form-group col-md-4'>
                    {!! Form::label('en_last_name','Last Name:') !!}
                    {!! Form::text('en_last_name', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('ar_first_name','Arabic First Name:') !!}
                    {!! Form::text('ar_first_name', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('ar_middle_name','Arabic Middle Name:') !!}
                    {!! Form::text('ar_middle_name', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('ar_last_name','Arabic Last Name:') !!}
                    {!! Form::text('ar_last_name', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('national_id','National ID:') !!}
                    {!! Form::text('national_id', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('profile_photo','Profile Photo:') !!}
                    {!! Form::file('profile_photo',  null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('salary','Salary:') !!}
                    {!! Form::number('salary', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4 id="gender"'>
                    {!! Form::label('gender','Gender:') !!}
                    {!! Form::select('gender', [''=>'Choose Options'] + array('female'=>'female' , 'male'=>'male')  , null,['class'=>'form-control'])  !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('marital_status','marital_status:') !!}
                    {!! Form::select('marital_status', [''=>'Choose Options'] + array('widowed'=>'widowed','divorced'=>'divorced', 'married'=>'married' , 'engaged'=>'engaged' , 'single'=>'single')  , null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4 id="military"'>
                    {!! Form::label('military_status','military_status:') !!}
                    {!! Form::select('military_status', [''=>'Choose Options'] +array('female'=>'female' ,'fullfilled'=>'fullfilled', 'postponed'=>'postponed' , 'exempted'=>'exempted' ), null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('phone','Phone:') !!}
                    {!! Form::text('phone', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('personal_mail','Email:') !!}
                    {!! Form::email('personal_mail', null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('company_mail','Company Mail:') !!}
                    {!! Form::email('company_mail', null,['class'=>'form-control']) !!}
                </div>


                <div class = 'form-group col-md-6'>
                    {!! Form::label('job_category_id','Department:') !!}
                    {!! Form::select('job_category_id',[''=>'Choose Options'] + $categories ,null,['class'=>'form-control']) !!}
                </div>

                <div class = 'form-group col-md-6 '>
                    {!! Form::label('job_title_id','Job:') !!}
                    {!! Form::select('job_title_id', [''=>'Choose Options'] + $job_titles ,null,['class'=>'form-control']) !!}

                </div>

                <div class = 'form-group col-md-4'>
                    {!! Form::label('password','Password:') !!}
                    {!! Form::password('password',['class'=>'form-control']) !!}
                </div>



                <div class="form-group @if($errors->has('role_id')) has-error @endif col-md-4" id="roles">
                    <label>{{ trans('admin.role') }}</label>
                    <select name="role_id" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.role') }}">
                        <option></option>
                        @foreach(App\Role::get() as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if($errors->has('type')) has-error @endif col-md-4">
                    <label>{{ trans('admin.type') }}</label>
                    <br/>
                    <input type="hidden" name="type" value="agent">
                    <input type="checkbox" id="agentType" name="type" class="switch-box"
                           data-on-text="{{ __('admin.yes') }}"
                           data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.admin') }}" value="admin">
                </div>




                <div class=" text-center col-md-12" id="contacts">
                    <br>
                    <button type="button" class="btn btn-success btn-flat"
                            id="addContact">{{ trans('admin.add_contact') }}</button>
                </div>



                <div class = 'text-left col-md-12'>
                    {!! Form::submit('create Employee',['class'=>'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}




            </div>


        </div>
    </div>

@section('js')


    <script>
        var y = 1;
        $(document).on('click', '#addContact', function () {
            console.log('clicked');
            $('#contacts').append(
                '<div class="well col-md-12" style="" id="removeContact' + y + '">' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.name") }}</label>' +
                '<input type="text" name="contact_name[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.name") }}" required>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.relation") }}</label>' +
                '<input type="text" name="contact_relation[' + y + ']" class="form-control"' +
                'placeholder="{{ trans("admin.relation") }}">' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.phone") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="contact_phone[' + y + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.phone") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][whatsapp]" value="1" class="minimal"' +
                'style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                'style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][sms]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][sms]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_social[' + y + '][viber]" value="0">' +
                '<input type="checkbox" name="contact_social[' + y + '][viber]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon addContactPhone" count="' + y + '" style="cursor: pointer">' +
                '<a class="fa fa-plus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<span id="otherContactPhones' + y + '"></span>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.email") }}</label>' +
                '<div class="input-group">' +
                '<input type="email" name="contact_email[' + y + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.email") !!}">' +
                '<span class="input-group-addon addContactEmail" count="' + y + '" style="cursor: pointer">' +
                '<a class="fa fa-plus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '<span id="otherContactEmails' + y + '"></span>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.job_title") }}</label>' +
                '<select name="contact_title_id[' + y + ']" class="form-control select2"' +
                'data-placeholder="{!! trans("admin.job_title") !!}">' +
                '<option></option>' +
                '@foreach(@\App\Title::all() as $titl)' +
                '<option value="{{ $titl->id }}">{{ $titl->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<label>{{ trans("admin.nationality") }}</label>' +
                '<select name="contact_nationality[' + y + ']" class="form-control select2"' +
                'data-placeholder="{!! trans("admin.nationality") !!}">' +
                '<option></option>' +
                '@foreach(@\App\Country::all() as $country)' +
                '<option value="{{ $country->id }}">{{ $country->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="text-center col-md-12">' +
                '<button type="button" class="btn btn-danger btn-flat removeContact" num="' + y + '">' +
                '{{ trans("admin.remove") }}</button>' +
                '</div>' +
                '</div>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            y++;
            $('.select2').select2()
        });

        $(document).on('click', '.removeContact', function () {
            var num = $(this).attr('num');
            $('#removeContact' + num).remove();
        })
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
    <script>
        var z = 1;
        $(document).on('click', '.addContactPhone', function () {
            var count = $(this).attr('count');
            $('#otherContactPhones' + count).append('<div class="form-group col-md-6" id="otherContactPhone' + z + '">' +
                '<label>{{ trans("admin.other_phones") }}</label>' +
                '<div class="input-group">' +
                '<input type="number" name="contact_other_phones[' + count + '][' + z + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.other_phones") !!}">' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-whatsapp" style="color: #34af23;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][whatsapp]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][whatsapp]" value="1" class="minimal"' +
                'style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                'style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="fa fa-comments" style="color: #3b5998;">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][sms]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][sms]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon" style="padding-bottom: 3px">' +
                '<label class="" style="color: #3b5998;">' +
                '<img src="{{ url("viber.png") }}" height="18px">' +
                '<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false"' +
                'style="position: relative;">' +
                '<input type="hidden" name="contact_other_socials[' + count + '][' + z + '][viber]" value="0">' +
                '<input type="checkbox" name="contact_other_socials[' + count + '][' + z + '][viber]" value="1" class="minimal"' +
                ' style="position: absolute; opacity: 0;">' +
                '<ins class="iCheck-helper"' +
                ' style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>' +
                '</div>' +
                '</label>' +
                '</span>' +
                '<span class="input-group-addon removeContactPhone" count="' + z + '" style="cursor: pointer">' +
                '<a class="fa fa-minus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            z++
        });

        $(document).on('click', '.removeContactPhone', function () {
            var contactPhone = $(this).attr('count');
            $('#otherContactPhone' + contactPhone).remove();
        })
    </script>
    <script>
        var i = 1;
        $(document).on('click', '.addContactEmail', function () {
            var count = $(this).attr('count');
            $('#otherContactEmails' + count).append('<div class="form-group col-md-6" id="otherContactEmail' + i + '">' +
                '<label>{{ trans("admin.other_emails") }}</label>' +
                '<div class="input-group">' +
                '<input type="email" name="contact_other_emails[' + count + '][' + i + ']" class="form-control" value=""' +
                'placeholder="{!! trans("admin.other_emails") !!}">' +
                '<span class="input-group-addon removeContactEmail" count="' + i + '" style="cursor: pointer">' +
                '<a class="fa fa-minus" style="margin-top: 5px;"></a>' +
                '</span>' +
                '</div>' +
                '</div>');
            i++
        });

        $(document).on('click', '.removeContactEmail', function () {
            var contactEmail = $(this).attr('count');
            $('#otherContactEmail' + contactEmail).remove();
        })
    </script>
    <script>
        $(document).on('change', '#country_id', function () {
            var id = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_cities')}}",
                type: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                success: function (data) {
                    $('#cities').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>
    <script>
        $(document).on('click',)

    </script>

    <script>
        $('.datepicker').datepicker('setDate', new Date(1990, 00, 01));
    </script>

@endsection
@stop