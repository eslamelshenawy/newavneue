<div class="form-group @if($errors->has('phase_id')) has-error @endif">
    <label>{{ trans('admin.phase') }}</label>
    <select class="form-control select2" name="phase_id" id="phase_id"
            data-placeholder="{{ __('admin.phase') }}">
        <option></option>
        @foreach(@$phases as $phase)
            <option value="{{ $phase->id }}">
                {{ $phase->{app()->getLocale().'_name'} }}
            </option>
        @endforeach
    </select>
</div>
