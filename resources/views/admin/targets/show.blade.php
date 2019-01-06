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
            <strong>{{ trans('admin.agent_type') }} : </strong>{{ @App\AgentType::find($target->agent_type_id)->name }}
            <br><hr>
            <strong>{{ trans('admin.month') }} : </strong>{{ $target->month }}
            <br><hr>
            <strong>{{ trans('admin.calls') }} : </strong>{{ $target->calls }}
            <br><hr>
            <strong>{{ trans('admin.meetings') }} : </strong>{{ $target->meetings }}
            <br><hr>
            <strong>{{ trans('admin.money') }} : </strong>{{ $target->money }}
            <br><hr>
            <strong>{{ trans('admin.notes') }} : </strong>{{ $target->notes }}
        </div>
    </div>
@endsection