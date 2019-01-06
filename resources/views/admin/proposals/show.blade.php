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
            <strong>{{ trans('admin.lead') }}
                : </strong>{{ @\App\Lead::find($proposal->lead_id)->first_name . ' ' . @\App\Lead::find($proposal->lead_id)->last_name }}
            <br>
            <hr>
            <strong>{{ trans('admin.unit_type') }} : </strong>{{ trans('admin.'.$proposal->unit_type) }}
            <br>
            <hr>
            <strong>{{ trans('admin.personal_commercial') }}
                : </strong>{{ trans('admin.'.$proposal->personal_commercial) }}
            <br>
            <hr>
            @if($proposal->unit_type == 'new_home')
                <strong>{{ trans('admin.unit') }}
                    : </strong>{{ @\App\Property::find($proposal->unit_id)->{app()->getLocale().'_name'} }}
                <br>
                <hr>
            @elseif($proposal->unit_type == 'resale')
                <strong>{{ trans('admin.unit') }}
                    : </strong>{{ @\App\ResaleUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                <br>
                <hr>
            @elseif($proposal->unit_type == 'rental')
                <strong>{{ trans('admin.unit') }}
                    : </strong>{{ @\App\RentalUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                <br>
                <hr>
            @endif
            <strong>{{ trans('admin.file') }} : </strong> <a href="{{ url('uploads/'.$proposal->file) }}"
                                                             class="fa fa-file" target="_blank"></a>
            <br>
            <hr>
            <strong>{{ trans('admin.price') }} : </strong>{{ $proposal->price }}
            <br>
            <hr>
            <strong>{{ trans('admin.description') }} : </strong>{{ $proposal->description }}
            <br>
            <hr>
            <details>
                <summary style="outline: none"><strong>{{ trans('admin.unit') }} </strong></summary>
                <div class="well col-md-12">
                    <div class="col-md-6">
                        <strong>{{ trans('admin.description') }} : </strong>{{ $proposal->description }}
                        <br>
                        <hr>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ trans('admin.description') }} : </strong>{{ $proposal->description }}
                        <br>
                        <hr>
                    </div>
                </div>
            </details>
        </div>
    </div>
@endsection