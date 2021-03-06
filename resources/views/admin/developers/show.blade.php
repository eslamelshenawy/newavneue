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

            <strong>{{ trans('admin.id') }} : </strong>{{ $developer->id }}
            <br><hr>
            <strong>{{ trans('admin.en_name') }} : </strong>{{ $developer->en_name }}
            <br><hr>
            <strong>{{ trans('admin.ar_name') }} : </strong>{{ $developer->ar_name }}
            <br><hr>
            <strong>{{ trans('admin.phone') }} : </strong>{{ $developer->phone }}
            <br><hr>
            <strong>{{ trans('admin.email') }} : </strong>{{ $developer->email }}
            <br><hr>
            <strong>{{ trans('admin.en_description') }} : </strong>{{ $developer->en_description }}
            <br><hr>
            <strong>{{ trans('admin.ar_description') }} : </strong>{{ $developer->ar_description }}
            <br><hr>

            <strong>{{ trans('admin.logo') }} : </strong><br>
            <img src="/uploads/{{ $developer->logo }}"  class="img-thumbnail" alt="Cinque Terre" width="304" height="236">
        </div>
        </div>
    </div>
@endsection