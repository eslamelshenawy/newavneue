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
            <strong>{{ trans('admin.name') }} : </strong>{{ $industry->name }}
            <br><hr>
            <strong>{{ trans('admin.notes') }} : </strong>{{ $industry->notes }}
        </div>
    </div>
@endsection