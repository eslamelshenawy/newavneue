@if(count($types) >0)
@foreach($types as $type)
    <option></option>
    <option value="{{ $type->id }}" >
        {{ $type->{app()->getLocale().'_name'} }}
    </option>
 @endforeach
@endif