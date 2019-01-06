<option></option>
@foreach($job_titles as $job_title)
    <option value="{{ $job_title->id }}">
        {{ $job_title->en_name }}
    </option>
@endforeach