<div class="form-group col-md-12">
    <label>{{ trans('admin.lead_source') }}</label>
    <select class="form-control select2" data-placeholder="{{ __('admin.lead_source') }}" id="lead_source">
        <option value="all">{{ __('admin.all') }}</option>
        @foreach(@\App\LeadSource::get() as $src)
            <option value="{{ $src->id }}">{{ $src->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-md-6">
    <label>{{ trans('admin.from') }}</label>
    <input type="text" readonly class="form-control datepicker" id="lead_from">
</div>

<div class="form-group col-md-6">
    <label>{{ trans('admin.to') }}</label>
    <input type="text" readonly class="form-control datepicker" id="lead_to">
</div>

<div class="form-group col-md-6">
    <button type="button" id="getLeadsData" class="btn btn-primary btn-flat">
        {{ __('admin.get') }} <i class="fa fa-spinner fa-spin hidden" id="leadData"></i>
    </button>
</div>

<table class="table table-bordered table-hover datatable">
    <thead>
    <tr>
        <th>{{ __('admin.name') }}</th>
        <th>{{ __('admin.source') }}</th>
        <th>{{ __('admin.calls') }}</th>
        <th>{{ __('admin.meetings') }}</th>
        <th>{{ __('admin.buy') }}</th>
        <th>{{ __('admin.sell') }}</th>
        <th>{{ __('admin.money') }}</th>
        <th>{{ __('admin.date') }}</th>
    </tr>
    </thead>
    <tbody id="leads">
    @foreach($leads as $lead)
        <tr>
            <td>{{ $lead->first_name }}</td>
            <td>{{ @\App\LeadSource::find($lead->lead_source_id)->name }}</td>
            <td>{{ @\App\Call::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('lead_id', $lead->id)->count() }}</td>
            <td>{{ @\App\Meeting::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('lead_id', $lead->id)->count() }}</td>
            <td>{{ @\App\ClosedDeal::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('buyer_id', $lead->id)->count() }}</td>
            <td>{{ @\App\ClosedDeal::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('seller_id', $lead->id)->count() }}</td>
            <td>{{ @\App\ClosedDeal::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('buyer_id', $lead->id)->sum('price') }}</td>
            <td>{{ $lead->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    $(document).on('click', '#getLeadsData', function () {
        var lead_from = $('#lead_from').val();
        var lead_to = $('#lead_to').val();
        var source = $('#lead_source').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_leads_data')}}",
            method: 'post',
            dataType: 'html',
            data: {from: lead_from, to: lead_to, source: source, _token: _token},
            beforeSend: function () {
                $('#leadData').removeClass('hidden');
            },
            success: function (data) {
                $('#leadData').addClass('hidden');
                $('#leads').html(data);
            },
            error: function (data) {
                $('#leadData').addClass('hidden');
            }
        })
    })
</script>
