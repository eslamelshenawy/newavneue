@if($type == 'lead')
<div class="form-group">
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
@else
    <div class="form-group">
        <label>{{ trans('admin.phone') }}</label>
        <select name="phone" class="form-control select2" id="phone" style="width: 100%"
                data-placeholder="{{ trans('admin.phone') }}">
            <option value="{{ @$contact->phone }}">{{ @$contact->phone }}</option>
            @if(@$contact->other_phones != null)
                @php($allPhones = json_decode(@$contact->other_phones))
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
@endif