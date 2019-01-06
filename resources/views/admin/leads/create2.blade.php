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
            <form action="{{ url(adminPath().'/leads/upload/excel') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group @if($errors->has('lead_source')) has-error @endif">
                    <label>{{ trans('admin.source') }}</label>
                    <select name="lead_source"  class="form-control select2" style="width: 100%" data-placeholder="{{ trans('admin.lead_source') }}">
                        <option></option>
                        @foreach(App\LeadSource::get() as $lead)
                            <option value="{{ $lead->id }}" @if(old('lead_source')==$lead->id) selected @endif>
                                {{ $lead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- image-preview-filename input [CUT FROM HERE]-->
                <br>
                <div class="form-group @if($errors->has('xls')) has-error @endif">
                    <label>{{ trans('admin.xls') }}</label>
                    <input type="file" name="xls" accept="xls" class="form-control" placeholder="{!! trans('admin.xls') !!}">
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection