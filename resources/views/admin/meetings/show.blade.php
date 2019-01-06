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
            <a class="btn btn-success" href="{{ url(adminPath().'/meetings/'.$meeting->id.'/edit') }}">{{ trans('admin.edit_meeting') }}</a>
            <br><hr>
            <strong>{{ trans('admin.lead') }} : </strong>{{ @\App\Lead::find($meeting->lead_id)->first_name . ' ' . @\App\Lead::find($meeting->lead_id)->last_name }}
            <br><hr>
            @if($meeting->contact_id > 0)
            <strong>{{ trans('admin.contact') }} : </strong>{{ @\App\Contact::find($meeting->contact_id)->name }}
            <br><hr>
            @endif
            <strong>{{ trans('admin.agent') }} : </strong>{{ @\App\User::find($meeting->user_id)->name }}
            <br><hr>
            <strong>{{ trans('admin.duration') }} : </strong>{{ $meeting->duration }}
            <br><hr>
            <strong>{{ trans('admin.date') }} : </strong>{{ date('Y-m-d',$meeting->date) }}
            <br><hr>
            <strong>{{ trans('admin.time') }} : </strong>{{ $meeting->time }}
            <br><hr>
            <strong>{{ trans('admin.location') }} : </strong>{{ $meeting->location }}
            <br><hr>
            @php($projects = json_decode($meeting->projects))
            <strong>{{ trans('admin.projects') }} : </strong>
            @if($projects != null)
            @foreach($projects as $project)
                @if(!$loop->last)
                    {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }} -
                @else
                    {{ @\App\Project::find($project)->{app()->getLocale().'_name'} }}
                @endif
            @endforeach
            @endif
            <br><hr>
            <strong>{{ trans('admin.probability') }} : </strong>{{ __('admin.' . $meeting->probability) }}
            <br><hr>
            <strong>{{ trans('admin.description') }} : </strong>{{ $meeting->description }}
        </div>
    </div>
@endsection