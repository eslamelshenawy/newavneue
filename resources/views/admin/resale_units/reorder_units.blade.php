@extends('admin.index')

@section('content')
    <div class="col-md-6">
        <form action="{{ url('reorder_units') }}" method="post" id="units">
        {{ csrf_field() }}
        <ul class="sortable">
            @foreach($units as $unit)
                <li class="" style="cursor: pointer" id="prop{{ $unit->unit_id }}" property="{{ $unit->unit_id }}">
                    {{ $unit->{app()->getLocale() . '_title'} }}
                    <input value="{{ $unit->id }}" name="order[]" type="hidden">
                </li>
            @endforeach
        </ul>
        <button type="submit" class="btn btn-flat" id="save"><i class="fa fa-refresh" id="spinnerU"></i> {{ __('admin.refresh') }}</button>
    </form>
    </div>
    <div class="col-md-6">
        <form action="{{ url('reorder_projects') }}" method="post" id="projects">
        {{ csrf_field() }}
        <ul class="sortable">
            @foreach($projects as $project)
                <li class="" style="cursor: pointer" id="prop{{ $project->unit_id }}" property="{{ $project->unit_id }}">
                    {{ $project->{app()->getLocale() . '_name'} }}
                    <input value="{{ $project->id }}" name="order[]" type="hidden">
                </li>
            @endforeach
        </ul>
        <button type="submit" class="btn btn-flat" id="save"><i class="fa fa-refresh" id="spinnerP"></i> {{ __('admin.refresh') }}</button>
    </form>
    </div>
@endsection
@section('js')
    <script>
        $(function () {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
        });

        $(document).on('submit','#units', function () {
            var data = new FormData(this);
            $.ajax({
                url: "{{ url(adminPath().'/reorder_units')}}",
                method: 'post',
                dataType: 'html',
                data: data,
                async: false,
                beforeSend: function () {
                    $('#spinnerU').addClass('fa-spin')
                },
                success: function (data) {
                    $('#spinnerU').removeClass('fa-spin');
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                    $('#spinnerU').removeClass('fa-spin');
                },
                contentType: false,
                processData: false
            });
            return false;
        })
    </script>

    <script>
        $(function () {
            $("#sortable").sortable();
            $("#sortable").disableSelection();
        });

        $(document).on('submit','#projects', function () {
            var data = new FormData(this);
            $.ajax({
                url: "{{ url(adminPath().'/reorder_projects')}}",
                method: 'post',
                dataType: 'html',
                data: data,
                async: false,
                beforeSend: function () {
                    $('#spinnerP').addClass('fa-spin')
                },
                success: function (data) {
                    $('#spinnerP').removeClass('fa-spin');
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                    $('#spinnerP').removeClass('fa-spin');
                },
                contentType: false,
                processData: false
            });
            return false;
        })
    </script>
@endsection