<option value="0">{{ __('admin.unknown') }}</option>
@foreach($properties as $property)
    <option value="{{ $property->id }}">{{ $property->{app()->getLocale().'_name'} }}</option>
@endforeach
