<div class="form-group @if($errors->has('contact_id')) has-error @endif" id="contact">
    <label>{{ trans('admin.contact') }}</label>
    <select name="contact_id" class="form-control select2" id="contact_id" style="width: 100%"
            data-placeholder="{{ trans('admin.contact') }}">
        <option value="0">{{ trans('admin.lead') }}</option>
        @foreach($contacts as $contact)
            <option value="{{ $contact->id }}">
                {{ $contact->name }}
            </option>
        @endforeach
    </select>
</div>