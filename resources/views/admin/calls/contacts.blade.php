<div class="form-group @if($errors->has('contact_id')) has-error @endif" id="contact_id">
    <label>{{ trans('admin.contact') }}</label>
    <select name="contact_id" class="form-control select2" id="Contact_id" style="width: 100%"
            data-placeholder="{{ trans('admin.contact') }}">
        <option value="0">{{ trans('admin.lead') }}</option>
        @foreach($contacts as $contact)
            <option value="{{ $contact->id }}">
                {{ $contact->name }}
            </option>
        @endforeach
    </select>
</div>
<span id="getPhones">
<div class="form-group @if($errors->has('phone')) has-error @endif">
    <label>{{ trans('admin.phone') }}</label>
    <select name="phone" class="form-control select2" id="phone" style="width: 100%"
            data-placeholder="{{ trans('admin.phone') }}">
        <option value="{{ @$lead->phone }}">{{ @$lead->phone }}</option>
        @if(@$lead->other_phones != null)
            @php($allPhones = json_decode(@$lead->other_phones))
            @foreach($allPhones as $phones)
                @foreach($phones as $phone => $v)
                    <option value="{{ $phone }}">
                {{ $phone }}
            </option>
                @endforeach
            @endforeach
        @endif
    </select>
</div>
</span>