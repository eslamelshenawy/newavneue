@extends('admin.index')

@section('content')
    <style>
        .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%;display: inline-block; }
        .sortable li { margin: 0 3px 3px 3px;  padding-left: 1.5em; font-size: 1.4em; height: auto;display: inline-block;width: 100%;background: #eee;border: 1px solid grey;cursor: grab; }
        .sortable li span { position: absolute; margin-left: -1.3em; }
        .sortable li:active{}
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( ".sortable" ).sortable();
            $( ".sortable" ).disableSelection();

        } );
    </script>
    <div class="col-md-4">
    <h1>{{ __('admin.website_projects') }}</h1>
    <ul class="sortable" id="sortable1">
        @php($i =0)
        @foreach($featured_projects as $project)
           <li class="ui-state-default" project_id="{{ $project->id }}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $project->en_name }}</li>@php($i++)
        @endforeach
    </ul>

        <button id="save" class="btn btn-default">Save</button>
    </div>
    <div class="col-md-4">
        <h1>{{ __('admin.mobile_projects') }}</h1>
        <ul class="sortable" id="sortable2">
            @php($i =0)
            @foreach($mobile_projects as $project)
                <li class="ui-state-default" project_id="{{ $project->id }}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $project->en_name }}</li>@php($i++)
            @endforeach
        </ul>

        <button id="save_mob" class="btn btn-default">Save</button>
    </div>
    <script>
        var projects  =[];
        $('#save').on('click',function () {
            $('#sortable1 li').each(function () {
                projects.push($(this).attr('project_id'));
            });
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: "{{url('admin/save_sorted')}}",
                data: {projects: projects,_token:token },
                success: function() {
                    console.log(projects);
                }
            });
            projects = [];
        });
        $('#save_mob').on('click',function () {
            $('#sortable2 li').each(function () {
                projects.push($(this).attr('project_id'));
            });
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: "{{url('admin/save_sorted_mob')}}",
                data: {projects: projects,_token:token },
                success: function() {
                    console.log(projects);
                }
            });
            projects = [];
        });

    </script>
@endsection