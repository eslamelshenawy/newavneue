@foreach($units as $unit)
    <option></option>
    <option value="{{ $unit->id }}" @if($unit_id == $unit->id) selected @endif>
        {{ $unit->title }}
    </option>
@endforeach
