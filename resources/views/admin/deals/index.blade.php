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
            <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
               href="{{ url(adminPath().'/deals/create') }}">{{ trans('admin.add') }}</a>
            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.id') }}</th>
                    <th>{{ trans('admin.buyer') }}</th>
                    <th>{{ trans('admin.seller') }}</th>
                    <th>{{ trans('admin.unit_type') }}</th>
                    <th>{{ trans('admin.show') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($deals as $deal)
                    <tr>
                        <td>{{ $deal->id }}</td>
                        <td>{{ @\App\Lead::find($deal->buyer_id)->first_name . ' ' . @\App\Lead::find($deal->buyer_id)->last_name }}</td>
                        <td>{{ @\App\Lead::find($deal->seller_id)->first_name . ' ' . @\App\Lead::find($deal->seller_id)->last_name }}</td>
                        <td>{{ trans('admin.'.@\App\Proposal::find($deal->proposal_id)->unit_type) }}</td>
                        <td><a href="{{ url(adminPath().'/deals/'.$deal->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
            "order": [[ 0, "desc" ]],
            'autoWidth': true
        })
    </script>
@stop