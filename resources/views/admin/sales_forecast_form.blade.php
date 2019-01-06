<div class="form-group col-md-6">
    <label>{{ trans('admin.from') }}</label>
    <input class="form-control datepicker1" id="from" readonly>
</div>

<div class="form-group col-md-6">
    <label>{{ trans('admin.to') }}</label>
    <input class="form-control datepicker1" id="to" readonly>
</div>

<div class="form-group col-md-12">
    <label>{{ trans('admin.agent') }}</label>
    <select class="form-control select2" id="agent" data-placeholder="{{ __('admin.agent') }}">
        <option value="all">{{ __('admin.all') }}</option>
        @foreach(\App\User::all() as $agent)
            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-12">
    <button type="button" id="getSalesForecastReport" class="btn btn-primary btn-flat">
        {{ __('admin.get') }} <i class="fa fa-spinner fa-spin hidden" id="salesForm"></i>
    </button>
</div>

<span id="getSalesForecast"></span>

<script>
    $('.datepicker1').datepicker({
        autoclose: true,
        format: " yyyy-mm",
        viewMode: "months",
        minViewMode: "months"
    });
</script>
<script>
    $(document).on('click', '#getSalesForecastReport', function () {
        var from = $('#from').val();
        var to = $('#to').val();
        var agent = $('#agent').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_sales_forecast_report')}}",
            method: 'post',
            dataType: 'html',
            data: {from: from, to: to, agent: agent, _token: _token},
            beforeSend: function () {
                $('#salesForm').removeClass('hidden');
            },
            success: function (data) {
                $('#salesForm').addClass('hidden');
                $('#getSalesForecast').html(data);
                $('.datatable').dataTable();
            },
            error: function (data) {
                $('#salesForm').addClass('hidden');
            }
        })
    })
</script>