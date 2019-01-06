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
            <strong>{{ trans('admin.id') }} : </strong>{{ $phase->id }}
            <br><hr>
            <strong>{{ trans('admin.en_name') }} : </strong>{{ $phase->en_name }}
            <br><hr>
            <strong>{{  trans('admin.ar_name') }} : </strong>{{ $phase->ar_name }}
            <br><hr>
            <strong>{{ trans('admin.en_description') }} : </strong>{{ $phase->en_description }}
            <br><hr>
            <strong>{{ trans('admin.ar_description') }} : </strong>{{ $phase->ar_description }}
            <br><hr>
            <strong>{{ trans('admin.meter_price') }} : </strong>{{ $phase->meter_price }}
            <br><hr>
            <strong>{{ trans('admin.area') }} : </strong>{{ $phase->area }}
            <br><hr>
            <strong>{{ trans('admin.area') }} : </strong>{{ date('Y-m-d',$data->delivery_date) }}
            <br><hr>
        </div>
    </div>
@endsection