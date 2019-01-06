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
            {!! Form::open(['url' => adminPath().'/proposals/'.$proposal->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('unit_type')) has-error @endif">
                <label>{{ trans('admin.unit_type') }}</label>
                <select class="form-control select2" id="unit_type" name="unit_type"
                        data-placeholder="{{ trans('admin.unit_type') }}">
                    <option></option>
                    <option value="resale" @if($proposal->unit_type == 'resale') selected @endif>{{ trans('admin.resale') }}</option>
                    <option value="rental" @if($proposal->unit_type == 'rental') selected @endif>{{ trans('admin.rental') }}</option>
                    <option value="new_home" @if($proposal->unit_type == 'new_home') selected @endif>{{ trans('admin.new_home') }}</option>
                </select>
            </div>

            <div class="form-group @if($errors->has('unit_id')) has-error @endif">
                <label>{{ trans('admin.unit') }}</label>
                <select class="form-control select2" name="unit_id" id="unit"
                        data-placeholder="{{ trans('admin.unit') }}">
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
                                @if($proposal->lead_id == $lead->id) selected  @endif>
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

            <div class="form-group @if($errors->has('description')) has-error @endif">
                <label>{{ trans('admin.description') }}</label>
                {!! Form::textarea('description',$proposal->description,['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
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
            var type = '{{ $proposal->unit_type }}';
            var unit_id = '{{ $proposal->unit_id }}';
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_units') }}",
                method: 'post',
                data: {type: type, unit_id: unit_id, _token: _token},
                success: function (data) {
                    $('#unit').html(data);
                }
            });
        })
        $(document).on('change', '#unit_type', function () {
            var type = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_units') }}",
                method: 'post',
                data: {type: type, _token: _token},
                success: function (data) {
                    $('#unit').html(data);
                }
            });
        })
    </script>
@endsection