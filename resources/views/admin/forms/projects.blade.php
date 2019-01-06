<div class="form-group @if($errors->has('project_id')) has-error @endif">
    <label>{{ trans('admin.project') }}</label>
    <select class="form-control select2" name="project_id" id="project_id"
            data-placeholder="{{ __('admin.project') }}">
        <option></option>
        @foreach(@$projects as $pro)
            <option value="{{ $pro->id }}">
                {{ $pro->{app()->getLocale().'_name'} }}
            </option>
        @endforeach
    </select>
</div>
<span id="getPhases"></span>

<script>
    $(document).on('change', '#project_id', function () {
        var id = $(this).val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_form_phases') }}",
            method: 'post',
            dataType: 'html',
            data: {id: id, _token: _token},
            success: function (data) {
                $('#getPhases').html(data);
                $('.select2').select2();
            }
        });
    })
</script>
