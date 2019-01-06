@if (count($errors) > 0)
<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
	<ul>
        @foreach($errors->all() as  $error)
                <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session()->has('success'))
    <h2 class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        {{ session()->get('success') }}
    </h2>
@endif

@if(session()->has('error'))
    <h2 class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        {{ session()->get('error') }}
    </h2>
@endif

@if(session()->has('login_error'))
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        {{ session()->get('login_error') }}
    </div>
@endif
@if(session()->has('facilities'))
    <div class="alert alert-danger">
        @foreach(session()->get('facilities') as $facility)
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        This Icon used in facility
        <a href="{{ url(adminPath().'/facilities/'.$facility.'/edit') }}">Go To It</a>
            <br>
            @endforeach
    </div>
@endif
@if(session()->has('phases'))
    <div class="alert alert-danger">
        @foreach(session()->get('phases') as $phase)
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            This Facility used in Phase
            <a href="{{ url(adminPath().'/phases/edit/'.$phase) }}">Go To It</a>
            <br>
        @endforeach
    </div>
@endif
@if(session()->has('projects'))
    <div class="alert alert-danger">
        @foreach(session()->get('projects') as $project)
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            This Tag used in projects
            <a href="{{ url(adminPath().'/projects/'.$project.'/edit') }}">Go To It</a>
            <br>
        @endforeach
    </div>
@endif