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
            <div class="col-md-6">
                <strong>{{ trans('admin.agent') }} : </strong>{{ \App\User::find($log->user_id)->name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.title') }} : </strong>{{ $log->{app()->getLocale().'_title'} }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.type') }} : </strong>{{ __('admin.'.$log->type) }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.route') }} : </strong>
                @if($log->type != 'delete')
                    <a href="{{ url(adminPath().'/'.$log->route.'/'.$log->route_id) }}">{{ __('admin.'.$log->route) }}</a>
                @else
                    {{ __('admin.'.$log->route) }}
                @endif
                <br>
                <hr>
            </div>
            <div class="col-md-12">
                @php($old = json_decode($log->old_data))
                @php($new = json_decode($log->new_data))
                @if($log->type == 'update')
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center; width: 30%">{{ __('admin.title') }}</th>
                            <th style="text-align: center; width: 30%">{{ __('admin.old') }}</th>
                            <th style="text-align: center; width: 10%"></th>
                            <th style="text-align: center; width: 30%">{{ __('admin.new') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($old as $k => $v)
                            @if($old->$k !== $new->$k)
                                @if($k != 'created_at'
                                and $k != 'updated_at'
                                and $k != 'user_id'
                                and $k != 'id'
                                and $k != 'social'
                                and $k != 'password'
                                and $k != 'status'
                                and !strpos($k,'_id')
                                and !strpos($k,'ther_')
                                )
                                    <tr>
                                        <th style="text-align: center">{{ __('admin.'.$k) }}</th>
                                        <td style="text-align: center">
                                            @if(strpos($k,'date') and isset($old->$k))
                                                {{ @date('Y-m-d', $old->$k) }}
                                            @else
                                                {{ $old->$k }}
                                            @endif
                                        </td>
                                        <td style="text-align: center"><i class="fa fa-arrow-right"></i></td>
                                        <td style="text-align: center">
                                            @if(strpos($k,'date') and isset($new->$k))
                                                {{ @date('Y-m-d', $new->$k) }}
                                            @else
                                                {{ $new->$k }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                @else

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center; width: 50%">{{ __('admin.title') }}</th>
                            <th style="text-align: center; width: 50%">{{ __('admin.data') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($old as $k => $v)
                            @if($k != 'created_at'
                                and $k != 'updated_at'
                                and $k != 'user_id'
                                and $k != 'id'
                                and $k != 'social'
                                and $k != 'password'
                                and $k != 'status'
                                and !strpos($k,'_id')
                                and !strpos($k,'ther_')
                                )
                                <tr>
                                    <th style="text-align: center">{{ __('admin.'.$k) }}</th>
                                    <td style="text-align: center">{{ $old->$k }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection