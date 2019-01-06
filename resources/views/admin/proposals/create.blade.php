@extends('admin.index')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            {!! Form::open(['url' => adminPath().'/proposals','enctype' => 'multipart/form-data']) !!}
            <div class="form-group @if($errors->has('unit_type')) has-error @endif">
                <label>{{ trans('admin.unit_type') }}</label>
                <select class="form-control select2" id="unit_type" name="unit_type"
                        data-placeholder="{{ trans('admin.unit_type') }}">
                    <option></option>
                    <option value="resale"
                            @if(request()->type == 'resale') selected @endif>{{ trans('admin.resale') }}</option>
                    <option value="rental"
                            @if(request()->type == 'rental') selected @endif>{{ trans('admin.rental') }}</option>
                    <option value="new_home"
                            @if(request()->type == 'new_home') selected @endif>{{ trans('admin.new_home') }}</option>
                    <option value="land"
                            @if(request()->type == 'land') selected @endif>{{ trans('admin.land') }}</option>
                </select>
            </div>

            <div class="form-group @if($errors->has('personal_commercial')) has-error @endif">
                <label>{{ trans('admin.personal_commercial') }}</label>
                <select class="form-control select2" id="personal_commercial" name="personal_commercial"
                        data-placeholder="{{ trans('admin.personal_commercial') }}" disabled="">
                    <option></option>
                    <option value="personal">{{ trans('admin.personal') }}</option>
                    <option value="commercial">{{ trans('admin.commercial') }}</option>
                </select>
            </div>

            <div class="form-group @if($errors->has('developer')) has-error @endif" id="developer_id">
                <label>{{ trans('admin.developer') }}</label>
                <select class="form-control select2" name="developer_id" id="developer"
                        data-placeholder="{{ trans('admin.developer') }}">
                    <option></option>
                    @foreach(\App\Developer::get() as $developer)
                        <option value="{{ $developer->id }}">{{ $developer->{app()->getLocale().'_name'} }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('project')) has-error @endif" id="project_id">
                <label>{{ trans('admin.project') }}</label>
                <select class="form-control select2" name="project_id" id="project"
                        data-placeholder="{{ trans('admin.developer') }}">
                    <option></option>
                </select>
            </div>

            <span id="phases"></span>

            <div class="form-group @if($errors->has('unit_id')) has-error @endif" id="unit_id">
                <label>{{ trans('admin.unit') }}</label>
                <select class="form-control select2" name="unit_id" id="unit"
                        data-placeholder="{{ trans('admin.unit') }}" style="width: 100%">
                    <option></option>
                </select>
            </div>

            <div class="form-group @if($errors->has('lead_id')) has-error @endif">
                <label>{{ trans('admin.lead') }}</label>
                <select class="form-control select2" name="lead_id" id="lead_id"
                        data-placeholder="{{ trans('admin.lead') }}">
                    <option></option>
                    @foreach(@App\Lead::getAgentLeads() as $lead)
                        <option value="{{ $lead->id }}"
                                @if(old('lead_id') == $lead->id) selected @endif>
                            {{ $lead->first_name . ' ' . $lead->last_name }}
                            -
                            @if($lead->agent_id == auth()->id())
                                {{ __('admin.my_lead') }}
                            @else
                                {{ __('admin.team_lead', ['agent' => @$lead->agent->name]) }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group @if($errors->has('price')) has-error @endif">
                <label>{{ trans('admin.price') }}</label>
                {!! Form::number('price','',['class' => 'form-control', 'placeholder' => trans('admin.price')]) !!}
            </div>

            <div class="form-group @if($errors->has('description')) has-error @endif">
                <label>{{ trans('admin.description') }}</label>
                {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
            </div>

            <div class="form-group @if($errors->has('file')) has-error @endif">
                <label>{{ trans('admin.file') }}</label>
                {!! Form::file('file',['class' => 'form-control', 'placeholder' => trans('admin.file')]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#developer_id').addClass('hidden');
            $('#unit_id').addClass('hidden');
            $('#project_id').addClass('hidden');
        })
    </script>
    <script>
        $(document).on('change', '#developer', function () {
            var dev = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_projects') }}",
                method: 'post',
                dataType: 'html',
                data: {id: dev, _token: _token},
                success: function (data) {
                    $('#project_id').removeClass('hidden')
                    $('#project').html(data);
                }
            });
        })
    </script>
    <script>
        $(document).on('change', '#phase', function () {
            var type = $('#personal_commercial').val();
            var phase = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_phase_units') }}",
                method: 'post',
                dataType: 'html',
                data: {id: phase, type: type, _token: _token},
                success: function (data) {
                    $('#unit').html(data);
                    $('#unit_id').removeClass('hidden');
                }
            });
        })
    </script>
    <script>
        $(document).on('change', '#project', function () {
            var pro = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_phases') }}",
                method: 'post',
                dataType: 'html',
                data: {id: pro, _token: _token},
                success: function (data) {
                    $('#phases').html(data);
                    $('.select2').select2();
                }
            });
        })
    </script>
    <script>
        $(document).on('change', '#unit_type', function () {
            var type = $(this).val();
            $('#personal_commercial').attr('type', type);

            if ($(this).val() == 'new_home') {
                $('#developer_id').removeClass('hidden');
                $('#unit_id').addClass('hidden');
            } else {
                $('#unit_id').removeClass('hidden')
                $('#developer_id').addClass('hidden')
            }
            if ($(this).val() != 'land') {
                $('#personal_commercial').attr('disabled', false);
                $('#unit').html('');
            } else {
                $('#personal_commercial').attr('disabled', true);
                $('#unit').html('@foreach(\App\Land::get() as $land)' +
                    '<option></option>' +
                    '<option value="{{ $land->id }}">' +
                    '{{ $land->{app()->getLocale().'_title'} }}' +
                    '</option>' +
                    '@endforeach');
            }
        });
        $(document).on('change', '#personal_commercial', function () {
            var personal_commercial = $(this).val();
            var type = $(this).attr('type');
            var _token = '{{ csrf_token() }}';
            if (type != 'new_home') {
                $.ajax({
                    url: "{{ url(adminPath().'/get_units') }}",
                    method: 'post',
                    data: {personal_commercial: personal_commercial, type: type, _token: _token},
                    success: function (data) {
                        $('#unit').html(data);
                    }
                });
            }
        });
        @if(request()->has('type'))
        $(document).ready(function () {
            var type = '{{ request()->type }}';
            var personal_commercial = '{{ request()->personal_commercial }}';
            var unit_id = '{{ request()->unit }}';
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_units') }}",
                method: 'post',
                data: {personal_commercial: personal_commercial, unit_id: unit_id, type: type, _token: _token},
                success: function (data) {
                    $('#unit').html(data);
                }
            });
        });
        @endif
    </script>
@endsection