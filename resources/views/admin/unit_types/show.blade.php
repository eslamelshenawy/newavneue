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
            <strong>{{ trans('admin.id') }} : </strong>{{ $show->id }}
            <br><hr>
            <strong>{{ trans('admin.en_name') }} : </strong>{{ $show->en_name }}
            <br><hr>
            <strong>{{ trans('admin.ar_name') }} : </strong>{{ $show->ar_name }}
            <br><hr>

            @if($show->usage=='commercial')
                <strong>{{ trans('admin.type') }} : </strong>تجارى--Commercial
                <br><hr>
                @elseif($show->usage=='personal')
                <strong>{{ trans('admin.type') }} : </strong>سكنى--Personal
                <br><hr>
                @endif

        </div>
    </div>
@endsection