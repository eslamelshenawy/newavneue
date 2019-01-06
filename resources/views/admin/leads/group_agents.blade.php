<option value="0">{{ __('admin.all') }}</option>
@foreach($agents as $agent)
    <option value="{{ $agent->member_id }}">{{ @$agent->member->name }}</option>
@endforeach