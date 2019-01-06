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
            <a class="btn btn-success pull-right" href="{{ url(adminPath().'/out_sub_cats/'.$sub->id.'/edit') }}">{{ trans('admin.edit') }}</a>
            <br>
            <strong>{{ trans('admin.name') }} : </strong>{{ $sub->name }}
            <br><hr>
            <strong>{{ trans('admin.out_cat') }} : </strong>{{ $sub->cat->name }}
            <br><hr>
            <strong>{{ trans('admin.due_date') }} : </strong>{{ $sub->due_date }}
            <br><hr>
            <strong>{{ trans('admin.notes') }} : </strong>{{ $sub->notes }}
        </div>
    </div>
@endsection