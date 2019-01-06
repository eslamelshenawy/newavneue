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
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_name') }} : </strong>{{ $show->ar_name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_name') }} : </strong>{{ $show->en_name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.notes') }} : </strong>{{ $show->notes }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.facebook') }} : </strong><a
                        href="https://www.facebook.com/{{ $show->facebook }}"
                        target="_blank">{{ $show->facebook }}</a>
                <br>
                <hr>
            </div>
        </div>
    </div>
@endsection