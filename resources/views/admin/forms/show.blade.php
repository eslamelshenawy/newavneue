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
                <strong>{{ trans('admin.ar_title') }} : </strong>{{ $form->ar_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_title') }} : </strong>{{ $form->en_title }}
                <br>
                <hr>
            </div>

            <div class="col-md-6">
                <strong>{{ trans('admin.lead_source') }} : </strong>{{ @$form->lead_source->name }}
                <br>
                <hr>
            </div>

            <div class="col-md-6">
                <strong>{{ trans('admin.type') }} : </strong>{{ __('admin.' . $form->type) }}
                <br>
                <hr>
            </div>
            @if($form->type == 'event')
                <div class="col-md-6">
                    <strong>{{ trans('admin.event') }} : </strong>
                    {{ $form->event->{app()->getLocale().'_title'} }}
                    <br>
                    <hr>
                </div>
            @elseif($form->type == 'project')
                <div class="col-md-6">
                    <strong>{{ trans('admin.project') }} : </strong>
                    {{ @$form->developer->{app()->getLocale().'_name'} }}
                    - {{ @$form->project->{app()->getLocale().'_name'} }}
                    - {{ @$form->phase->{app()->getLocale().'_name'} }}
                    <br>
                    <hr>
                </div>
            @elseif($form->type == 'campaign')
                <div class="col-md-6">
                    <strong>{{ trans('admin.campaign') }} : </strong>
                    {{ @$form->campaign->{app()->getLocale().'_title'} }}
                    <br>
                    <hr>
                </div>
            @endif
            <div class="col-md-6">
                <strong>{{ trans('admin.url') }} : </strong>
                <a href="{{ url('form/' . slug($form->{app()->getLocale().'_title'}) . '-' . $form->id) }}" target="_blank">
                    {{ url('form/' . slug($form->{app()->getLocale().'_title'}) . '-' . $form->id) }}
                </a>
                <br>
                <hr>
            </div>
            @php($fields = json_decode($form->fields))
            <div class="col-md-12">
                <strong>{{ trans('admin.fields') }} : </strong>
                @foreach($fields as $field => $v)
                    {{ __('admin.' . $field) }}
                    @if($v)
                        ({{ __('admin.required') }})
                    @else()
                        ({{ __('admin.not_required') }})
                    @endif
                    -
                @endforeach
                <br>
                <hr>
            </div>
            <div class="col-md-12">
                <img src="{{ url('uploads/'.$form->background) }}" style="width: 100%;">
            </div>
        </div>
    </div>
@endsection