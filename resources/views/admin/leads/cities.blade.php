<div class="form-group @if($errors->has('city_id')) has-error @endif col-md-12">
    <label>{{ trans('admin.city') }}</label>
    <select name="city_id" class="form-control select2" id="city_id" style="width: 100%"
            data-placeholder="{{ trans('admin.city') }}">
        <option></option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}">
                {{ $city->name }}
            </option>
        @endforeach
    </select>
</div>