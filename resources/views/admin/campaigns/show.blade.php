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
            <form action="{{ url(adminPath().'/export_xls') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" class="btn btn-success" value="{{ $show->id }}" name="id">
                <button type="submit">Export Excel</button>
            </form>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_name') }} : </strong>{{ $show->en_name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_name') }} : </strong>{{ $show->ar_name }}
                <br>
                <hr>
            </div>

            <div class="col-md-6">
                <strong>{{ trans('admin.campaign_type') }} : </strong>{{ \App\CampaignType::find($show->campaign_type_id)->{app()->getLocale().'_name'} }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.project') }} : </strong>{{ \App\Project::find($show->project_id)->{app()->getLocale().'_name'} }}
                <br>
                <hr>
            </div>

            <div class="col-md-6">
                <strong>{{ trans('admin.start_date') }} : </strong>{{ date('Y-m-d',$show->start_date) }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.end_date') }} : </strong>{{ date('Y-m-d',$show->end_date) }}
                <br>
                <hr>
            </div>

            <div class="col-md-6">
                <strong>{{ trans('admin.budget') }} : </strong>{{ $show->budget }}
                <br/>
                <br/>
            </div>

            <div class="col-md-6">
                <strong>{{ trans('admin.description') }} : </strong>{{ $show->description }}
                <br/>
                <br/>
            </div>
        </div>
    </div>
@endsection