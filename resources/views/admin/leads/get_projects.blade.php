<option></option>
@foreach($projects as $project)
    <option value="{{ $project->id }}">{{ $project->{app()->getLocale().'_name'} }}</option>
@endforeach