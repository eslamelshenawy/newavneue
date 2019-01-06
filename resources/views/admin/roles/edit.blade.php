@extends('admin.index')
<style>
    .bootstrap-switch {
        width: 194px !important;
    }
    .bootstrap-switch-container {
        width: 239px !important;
    }
    .bootstrap-switch-label{
        width: 150px !important;
    }

</style>
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
            {!! Form::open(['url' => adminPath().'/roles/'.$role->id , 'method'=>'put']) !!}
            <div class="form-group @if($errors->has('name')) has-error @endif">
                <label>{{ trans('admin.name') }}</label>
                {!! Form::text('name',$role->name,['class' => 'form-control', 'placeholder' => trans('admin.name')]) !!}
            </div>
            <div class="form-group @if($errors->has('roles')) has-error @endif">
                @php($arr = json_decode($role->roles))
                
                @foreach($roles as $k => $role)
                    @if(is_array($role))
                        <div>
                            <strong>{{ __('admin.'.$k) }}:</strong>
                            <br/>
                            <br/>
                        </div>
                        @foreach($role as $r)
                            <input type="hidden" name="roles[{{ $r }}]" value="0">
                            <input type="checkbox" @if(@$arr->$r) checked @endif name="roles[{{ $r }}]" class="switch-box"
                                   data-on-text="{{ __('admin.yes') }}"
                                   data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.'.$r) }}"
                                   value="1">
                        @endforeach
                        <hr/>
                    @else
                        <input type="hidden" name="roles[{{ $role }}]" value="0">
                        <input type="checkbox" @if(@$arr->$r) checked @endif name="roles[{{ $role }}]" class="switch-box"
                               data-on-text="{{ __('admin.yes') }}"
                               data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.'.$role) }}"
                               value="1">
                    @endif
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection