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
            <strong>{{ trans('admin.leads') }} : </strong>
                    {{ @App\Lead::find($todo->leads)->first_name.' '.@App\Lead::find($todo->leads)->last_name }}

            <br><hr>
            <strong>{{ trans('admin.due_date') }} : </strong>{{ date('Y-m-d.',$todo->due_date) }}
            <br><hr>
            <strong>{{ trans('admin.to_do_type') }} : </strong>{{ trans('admin.'.$todo->to_do_type) }}
            <br><hr>
            <strong>{{ trans('admin.status') }} : </strong>{{ trans('admin.'.$todo->status) }}
            <br><hr>
            <strong>{{ trans('admin.description') }} : </strong>{{ $todo->description }}
        </div>
    </div>
@endsection