<div class="form-group col-md-6">
    <label>{{ trans('admin.from') }}</label>
    <input type="text" readonly class="form-control datepicker" id="from">
</div>

<div class="form-group col-md-6">
    <label>{{ trans('admin.to') }}</label>
    <input type="text" readonly class="form-control datepicker" id="to">
</div>

<div class="form-group col-md-12">
    <button type="button" id="getLeadReport" class="btn btn-primary btn-flat">
        {{ __('admin.get') }} <i class="fa fa-spinner fa-spin hidden" id="leadForm"></i>
    </button>
</div>
<span id="LeadReport"></span>

<script>
    $(document).on('click', '#getLeadReport', function () {
        var from = $('#from').val();
        var to = $('#to').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_lead_report')}}",
            method: 'post',
            dataType: 'html',
            data: {from: from, to: to, _token: _token},
            beforeSend: function () {
                $('#leadForm').removeClass('hidden');
            },
            success: function (data) {
                $('#leadForm').addClass('hidden');
                $('#LeadReport').html(data);
                $('.datatable').dataTable();
                $('.select2').select2();
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                });
            },
            error: function (data) {
                $('#leadForm').addClass('hidden');
            }
        })
    })
</script>
