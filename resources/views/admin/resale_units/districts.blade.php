<div class="form-group @if($errors->has('district_id')) has-error @endif">
    <label>{{ trans('admin.district') }}</label>
    <select class="select2 form-control" name="district_id" id="district_id"
            data-placeholder="{{ trans('admin.district') }}">
        <option></option>
        @foreach($districts as $district)
            <option value="{{ $district->id }}">{{ $district->{app()->getLocale().'_name'} }}</option>
        @endforeach
    </select>
</div>
