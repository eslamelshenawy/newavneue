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
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#resale" data-toggle="tab"
                                          aria-expanded="false">{{ trans('admin.resale_units') }}</a></li>
                    <li class=""><a href="#new_homes" data-toggle="tab"
                                    aria-expanded="true">{{ trans('admin.new_homes') }}</a></li>
                    <li class=""><a href="#rental" data-toggle="tab"
                                    aria-expanded="true">{{ trans('rental_units') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="resale">
                        <table class="table table-hover table-striped datatable">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.property') }}</th>
                                <th>{{ trans('admin.title') }}</th>
                                <th>{{ trans('admin.status') }}</th>
                                <th>{{ trans('admin.location') }}</th>
                                <th>{{ trans('admin.price') }}</th>
                                <th>{{ trans('admin.rooms') }}</th>
                                <th>{{ trans('admin.bathrooms') }}</th>
                                <th>{{ trans('admin.area') }}</th>
                                <th>{{ trans('admin.delivery_date') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($resale as $resaleUnit)
                                <tr>
                                    <td><img src="{{ url('uploads/'.$resaleUnit->image) }}" width="75 px"></td>
                                    <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                    <td>{{ trans('admin.'.$resaleUnit->availability) }}</td>
                                    <td>{{ @\App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                    <td>{{ $resaleUnit->total }}</td>
                                    <td>{{ $resaleUnit->rooms }}</td>
                                    <td>{{ $resaleUnit->bathrooms }}</td>
                                    <td>{{ $resaleUnit->area }}</td>
                                    <td>{{ $resaleUnit->delivery_date }}</td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                    <div class="tab-pane" id="new_homes">
                        <table class="table table-hover table-striped datatable">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.title') }}</th>
                                <th>{{ trans('admin.start_price') }}</th>
                                <th>{{ trans('admin.area') }}</th>
                                <th>{{ trans('admin.project') }}</th>
                                <th>{{ trans('admin.phase') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($newHomes as $newHome)
                                @php($phase = @\App\Phase::find($newHome->phase_id))
                                @php($project = @\App\Project::find($phase->project_id))
                                <tr>
                                    <td>{{ $newHome->{app()->getLocale().'_name'} }}</td>
                                    <td>{{ $newHome->start_price }}</td>
                                    <td>{{ $newHome->area_from }} <i class="fa fa-arrows-h"></i> {{ $newHome->area_to }} </td>
                                    <td>{{ $project->{app()->getLocale().'_name'} }}</td>
                                    <td>{{ $phase->{app()->getLocale().'_name'} }}</td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                    <div class="tab-pane" id="rental">
                        <table class="table table-hover table-striped datatable">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.property') }}</th>
                                <th>{{ trans('admin.title') }}</th>
                                <th>{{ trans('admin.status') }}</th>
                                <th>{{ trans('admin.location') }}</th>
                                <th>{{ trans('admin.rent') }}</th>
                                <th>{{ trans('admin.rooms') }}</th>
                                <th>{{ trans('admin.bathrooms') }}</th>
                                <th>{{ trans('admin.area') }}</th>
                                <th>{{ trans('admin.delivery_date') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rental as $resaleUnit)
                                <tr>
                                    <td><img src="{{ url('/uploads/'.$resaleUnit->image) }}" width="50px"></td>
                                    <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                    <td>{{ $resaleUnit->availability }}</td>
                                    <td>{{ @App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                    <td>{{ $resaleUnit->rent }}</td>
                                    <td>{{ $resaleUnit->rooms }}</td>
                                    <td>{{ $resaleUnit->bathrooms }}</td>
                                    <td>{{ $resaleUnit->area }}</td>
                                    <td>{{ $resaleUnit->delivery_date }}</td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true
        })
    </script>
@stop