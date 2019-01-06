<div class="form-group col-md-12">
    <label>{{ trans('admin.target') }}</label>
    <select class="form-control select2" id="target" data-placeholder="{{ __('admin.target') }}">
        <option></option>
        @foreach(@\App\Target::get() as $target)
            <option value="{{ $target->month }}">{{ $target->month }}</option>
        @endforeach
    </select>
</div>

<span id="getTarget"></span>

<script>
    $(document).on('change', '#target', function () {
        var target = $(this).val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_target')}}",
            method: 'post',
            dataType: 'html',
            data: {target: target, _token: _token},
            success: function (data) {
                $('#getTarget').html(data);
                $('.datatable').dataTable();
            }
        })
    })
</script>