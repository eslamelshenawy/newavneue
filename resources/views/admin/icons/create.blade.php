@extends('admin.index')
@section('content')
    <style>
        #show_image {
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
            <form action="{{ url(adminPath().'/icons') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="">
                    <label>{{ trans('admin.icons') }}</label>
                    <input class="form-control" name="icon[]" type="file" multiple>
                </div>
                <br/>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
