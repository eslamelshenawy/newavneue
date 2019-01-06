<div class="form-group col-md-6">
    <label>{{ __('admin.from') }}</label>
    <input type="text" readonly class="form-control datepicker" id="from">
</div>

<div class="form-group col-md-6">
    <label>{{ __('admin.to') }}</label>
    <input type="text" readonly class="form-control datepicker" id="to">
</div>

<div class="form-group col-md-12">
    <label>{{ __('admin.developer') }}</label>
    <select class="form-control select2" id="developer" data-placeholder="{{ __('admin.developers') }}">
        <option></option>
        @foreach($developers as $developer)
            <option value="{{ $developer->id }}">{{ $developer->{app()->getLocale().'_name'} }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-12">
    <button type="button" id="getDevReport" class="btn btn-primary btn-flat">
        {{ __('admin.get') }} <i class="fa fa-spinner fa-spin hidden" id="developerData"></i>
    </button>
</div>
<span id="LeadReport"></span>

<script>
    $(document).on('click', '#getDevReport', function () {
        var from = $('#from').val();
        var to = $('#to').val();
        var developer = $('#developer').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_developer_report')}}",
            method: 'post',
            dataType: 'html',
            data: {from: from, to: to, developer: developer, _token: _token},
            beforeSend: function () {
                $('#developerData').removeClass('hidden');
            },
            success: function (data) {
                $('#developerData').addClass('hidden');
                $('#LeadReport').html(data);
                $('.datatable').dataTable();
                $('.select2').select2();
            },
            error: function () {
                $('#developerData').addClass('hidden');
            }
        })
    })
</script>
