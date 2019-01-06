<div class="form-group @if($errors->has('city_id')) has-error @endif">
    <label>{{ trans('admin.city') }}</label>
    <select class="select2 form-control" name="city_id" id="city_id"
            data-placeholder="{{ trans('admin.city') }}">
        <option></option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}">{{ $city->{app()->getLocale().'_name'} }}</option>
        @endforeach
    </select>
</div>
<span id="districts"></span>
<script>
    $(document).on('change', '#city_id', function () {
        var id = $(this).val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_cities_districts')}}",
            method: 'post',
            data: {id: id, _token: _token},
            success: function (data) {
                $('#districts').html(data);
                $('.select2').select2();
            }
        });
    })
</script>
