@extends('admin.index')
@section('content')
    <link rel="stylesheet" type="text/css" href="http://gomilad.com/public/css/dropzone.min.css">
    <style>
        #show_image{
            display: none;
        }
    </style>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <form action="{{ url(adminPath().'/properties') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('unit_id') ? 'has-error' : '' }}">
                    {!! Form::label(trans('admin.unit_type')) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" name="unit_id" data-placeholder="{{ trans('admin.unit_type') }}">
                        <option></option>
                        @foreach(\App\UnitType::get() as $row)
                            <option value="{{ $row->id }}">{{ $row->en_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.price') }}</h3>
                    </div>
                    <div class="box-body">

                            <div class=" @if($errors->has('start_price')) has-error @endif">
                                <label> {{ trans('admin.from') }}</label>
                                <input type="number" name="start_price" class="form-control"  value="{{ old('start_price') }}" placeholder="{{ trans('admin.from') }}">
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
                                <label> {{ trans('admin.from') }}</label>
                                <input type="number" name="area_from" class="form-control"  value="{{ old('area_from') }}" placeholder="{{ trans('admin.from') }}">
                            </div>
                            <div class="col-xs-6 @if($errors->has('area_to')) has-error @endif">
                                <label> {{ trans('admin.to') }}</label>
                                <input type="number" name="area_to" class="form-control"  value="{{ old('area_to') }}" placeholder="{{ trans('admin.to') }}">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="form-group {{ $errors->has('facility') ? 'has-error' : '' }}">
                    {!! Form::label(trans('admin.facility')) !!}
                    <br>
                    <select class="select2 form-control" style="width: 100%" multiple name="facility[]" data-placeholder="{{ trans('admin.facilities') }}">
                        <option></option>
                        @foreach(\App\Facility::get() as $facilty)
                            <option value="{{ $facilty->id }}">{{ $facilty->en_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group @if($errors->has('description')) has-error @endif">
                    <label>{{ trans('admin.description') }}</label>
                    <input multiple type="file" class="form-control" name="image[]">
                </div>

                <div class="form-group @if($errors->has('description')) has-error @endif">
                    <label>{{ trans('admin.description') }}</label>
                    <textarea name="description" class="form-control" placeholder="{!! trans('admin.description') !!}" rows="6"></textarea>
                </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection

