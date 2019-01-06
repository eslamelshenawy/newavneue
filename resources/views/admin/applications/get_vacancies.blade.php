<option></option>
@foreach($vacancies as $vacancy)
    <option value="{{ $vacancy->id }}">
        {{ $vacancy->en_name }}
    </option>
@endforeach