<div class="form-group @if($errors->has('project_id')) has-error @endif col-md-12">
    <label>{{ trans('admin.project') }}</label>
    <select class="form-control select2" style="width: 100%" name="project_id" data-placeholder="{{ __('admin.project')}}">
        <option></option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->{app()->getLocale() . '_name'} }}</option>
        @endforeach
    </select>
</div>