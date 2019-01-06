@extends('admin.index')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>
            <h4>{{ trans('admin.phase').' ('.App\Phase::find($data->phase_id)->{app()->getLocale().'_name'} }})</h4>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <form action={{url(adminPath().'/properties/'.$data->id)}} method="post" enctype="multipart/form-data"  >
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label> {{ trans("admin.code") }}</label>
                        <input type="text" name="code" class="form-control" value="{{ $data->code }}" placeholder="{{ trans("admin.code") }}">
                    </div>
                    <div class="form-group">
                        <label> {{ trans("admin.en_name") }}</label>
                        <input type="text" name="en_name" class="form-control" value="{{ $data->en_name }}" placeholder="{{ trans("admin.en_name") }}">
                    </div>
                <div class="form-group">
                    <label> {{ trans("admin.ar_name") }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ $data->ar_name }}" placeholder="{{ trans("admin.ar_name") }}">
                </div>


                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.price') }}</h3>
                    </div>
                    <div class="box-body">

                        <div class=" @if($errors->has('start_price')) has-error @endif col-md-6">
                            <label> {{ trans('admin.unit_price') }}</label>
                            <input type="number" name="start_price" class="form-control"  value="{{ $data->start_price }}" placeholder="{{ trans('admin.unit_price') }}">
                        </div>
                        <div class=" @if($errors->has('start_price')) has-error @endif col-md-6">
                            <label> {{ trans('admin.meter_price') }}</label>
                            <input type="number" name="meter_price" class="form-control"  value="{{ $data->meter_price }}" placeholder="{{ trans('admin.meter_price') }}">
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.area') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6 @if($errors->has('area_from')) has-error @endif">
                                <label> {{ trans('admin.clear1') }}</label>
                                <input type="number" name="area_from" class="form-control"  value="{{ $data->area_from }}" placeholder="{{ trans('admin.clear1') }}">
                            </div>
                            <div class="col-xs-6 @if($errors->has('area_to')) has-error @endif">
                                <label> {{ trans('admin.garden') }}</label>
                                <input type="number" name="area_to" class="form-control"  value="{{ $data->area_to }}" placeholder="{{ trans('admin.garden') }}">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="col-md-6 {{ $errors->has("unit_id") ? "has-error" : "" }}">
                    {!! Form::label(trans("admin.usage")) !!}
                    <br>
                    <select class="select2 form-control __unit" name="type" style="width: 100%"  id="unit_type" data-placeholder="{{ trans("admin.unit_type") }}">
                        <option></option>
                        <option value="personal" @if($data->type=="personal") selected @endif >{{ trans('admin.personal') }}</option>
                        <option value="commercial" @if($data->type=="commercial") selected @endif >{{ trans('admin.commercial') }}</option>
                    </select>
                </div>
                <div class="form-group col-md-6 {{ $errors->has("unit_id") ? "has-error" : "" }}">
                    {!! Form::label(trans("admin.unit_type")) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" id="unit_id" name="unit_id" data-placeholder="{{ trans("admin.unit_type") }}">
                       <option value="{{ $data->unit_id }}" selected  >{{ App\UnitType::find($data->unit_id)->{app()->getLocale().'_name'} }}</option>
                    </select>
                </div>
                <div class="form-group @if($errors->has("en__description")) has-error @endif">
                    <label>{{ trans("admin.en_description") }}</label>
                    <textarea name="en_description" class="form-control" placeholder="{!! trans("admin.en_description") !!}" rows="6">{{ $data->en_description }}</textarea>
                </div>
                <div class="form-group @if($errors->has("ar_description")) has-error @endif">
                    <label>{{ trans("admin.ar_description") }}</label>
                    <textarea name="ar_description" class="form-control" placeholder="{!! trans("admin.ar_description") !!}" rows="6">{{ $data->ar_description }}</textarea>
                </div>
                <div class="form-group @if($errors->has("images")) has-error @endif">
                    <label>{{ trans("admin.add").' '.trans("admin.image") }}</label>
                    <input multiple type="file" class="form-control" name="images[]">
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('change', '#unit_type', function () {
            var usage= $(this).val();
            var unit_id=$(this).attr('id')+'_type';
            var _token= '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_unit_types')}}",
                method: 'post',

                data: {usage: usage, _token: _token},
                success: function (data) {
                    console.log('success');
                    $('#unit_id').html(data);
                }
            });
        });
    </script>
    @endsection
