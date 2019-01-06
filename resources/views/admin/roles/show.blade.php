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
            <strong>{{ trans('admin.name') }} : </strong>{{ $role->name }}
            <br><hr>
            <strong>{{ trans('admin.roles') }} : </strong>
            <br/>
            <hr/>
            @php($roles = json_decode($role->roles))
            @foreach($roles as $k => $v)
                <div class="col-md-1">
                    {{ __('admin.'.$k) }} @if($v) <i class="fa fa-check"></i> @else <i class="fa fa-remove"></i> @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection