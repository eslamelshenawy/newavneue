<div class="form-group col-md-12">
    <label>{{ trans('admin.projects') }}</label>
    <select class="form-control select2" data-placeholder="{{ __('admin.projects') }}" id="projects">
        <option value="all">{{ __('admin.all') }}</option>
        @foreach(@\App\Project::where('developer_id', $developer_id)->get() as $project)
            <option value="{{ $project->id }}">{{ $project->{app()->getLocale().'_name'} }}</option>
        @endforeach
    </select>
</div>

<table class="table table-bordered table-hover datatable">
    <thead>
    <tr>
        <th>{{ __('admin.id') }}</th>
        <th>{{ __('admin.seller') }}</th>
        <th>{{ __('admin.buyer') }}</th>
        <th>{{ __('admin.price') }}</th>
        <th>{{ __('admin.project') }}</th>
        <th>{{ __('admin.date') }}</th>
    </tr>
    </thead>
    <tbody id="deals">
    @foreach($deals as $deal)
        <tr>
            <td>{{ $deal->id }}</td>
            <td>{{ @\App\Lead::find($deal->seller_id)->first_name }}</td>
            <td>{{ @\App\Lead::find($deal->buyer_id)->first_name }}</td>
            <td>{{ $deal->price }}</td>
            <td>{{ $deal->{app()->getLocale().'_project_name'} }}</td>
            <td>{{ $deal->date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    $(document).on('change', '#projects', function () {
        var project = $(this).val();
        var developer = '{{ $developer_id }}'
        var from = $('#from').val();
        var to = $('#to').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url(adminPath().'/get_project_deals')}}",
            method: 'post',
            dataType: 'html',
            data: {project: project, developer: developer, from: from, to: to, _token: _token},
            success: function (data) {
                $('#deals').html(data);
            }
        })
    })
</script>
