<div class="form-group @if($errors->has('phases')) has-error @endif" id="phase_id">
    <label>{{ trans('admin.phases') }}</label>
    <select class="form-control select2" name="phase_id" id="phase"
            data-placeholder="{{ trans('admin.phases') }}">
        <option></option>
        @foreach($phases as $phase)
            <option value="{{ $phase->id }}">{{ $phase->{app()->getLocale().'_name'} }}</option>
        @endforeach
    </select>
</div>