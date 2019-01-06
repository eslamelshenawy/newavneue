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
            <div class="form-group col-md-12">
                <label>{{ trans('admin.report') }}</label>
                <select id="report" style="width: 100%" class="form-control select2"
                        data-placeholder="{{ __('admin.report') }}">
                    <option></option>
                    <option value="sales_forecast">{{ __('admin.sales_forecast') }} {{ __('admin.report') }}</option>
                    <option value="lead_stage">{{ __('admin.lead_stage') }} {{ __('admin.report') }}</option>
                    <option value="leads">{{ __('admin.leads') }} {{ __('admin.report') }}</option>
                    <option value="agents">{{ __('admin.agents') }} {{ __('admin.report') }}</option>
                    <option value="developers">{{ __('admin.developers') }} {{ __('admin.report') }}</option>
                </select>
            </div>

            <span id="getReportForm"></span>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('change', '#report', function () {
            var report = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_report_form')}}",
                method: 'post',
                dataType: 'html',
                data: {report: report, _token: _token},
                success: function (data) {
                    $('#getReportForm').html(data);
                    $('.select2').select2();
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                    });

                }
            })
        })
    </script>
@endsection
