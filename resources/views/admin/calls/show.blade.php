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
            <a class="btn btn-success pull-right" href="{{ url(adminPath().'/calls/'.$call->id.'/edit') }}">{{ trans('admin.edit_call') }}</a>
            <br>
            <strong>{{ trans('admin.lead') }} : </strong>{{ @\App\Lead::find($call->lead_id)->first_name . ' ' . @\App\Lead::find($call->lead_id)->last_name }}
            <br><hr>
            @if($call->contact_id > 0)
            <strong>{{ trans('admin.contact') }} : </strong>{{ @\App\Contact::find($call->contact_id)->name }}
            <br><hr>
            @endif
            <strong>{{ trans('admin.agent') }} : </strong>{{ @\App\User::find($call->user_id)->name }}
            <br><hr>
            <strong>{{ trans('admin.duration') }} : </strong>{{ $call->duration }}
            <br><hr>
            <strong>{{ trans('admin.date') }} : </strong>{{ date('Y-m-d',$call->date) }}
            <br><hr>
            @php
            if($call->projects){
                $projects = json_decode(@$call->projects);
            }else{
                $projects = array();
            }
            @endphp
            @endphp
            <strong>{{ trans('admin.projects') }} : </strong>
            @foreach($projects as $project)
                @if(!$loop->last)
                    {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }} -
                @else
                    {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }}
                @endif
            @endforeach
            <br><hr>
            <strong>{{ trans('admin.probability') }} : </strong>{{ __('admin.' . $call->probability) }}
            <br><hr>
            <strong>{{ trans('admin.phone') }} : </strong>{{ $call->phone }}
            <br><hr>
            <strong>{{ trans('admin.description') }} : </strong>{{ $call->description }}
            <br><hr>
        </div>
    </div>
@endsection