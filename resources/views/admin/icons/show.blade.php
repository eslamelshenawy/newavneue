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
            <strong>{{ trans('admin.id') }} : </strong>{{ $icon->id }}
            <br><hr>
            <strong>{{ trans('admin.icon') }} : </strong><br>
            <img src="/uploads/{{ $icon->icon }}"  class="img-thumbnail" alt="Cinque Terre" width="304" height="236">

        </div>
    </div>
@endsection