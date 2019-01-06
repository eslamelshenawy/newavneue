<div class="well col-md-12">
    <div class="col-md-6">
        <strong>{{ trans('admin.buyer') }}
            : </strong>
        <a href="{{ url(adminPath().'/leads/'.$proposal->lead_id) }}">
            {{ @\App\Lead::find($proposal->lead_id)->first_name . ' ' . @\App\Lead::find($proposal->lead_id)->last_name }}
        </a>
        <br/>
        <hr/>
    </div>
    <div class="col-md-6">
        @php
            if ($proposal->unit_type == 'new_home') {
                $unit = @\App\Property::find($proposal->unit_id);
                $phase = @\App\Phase::find($unit->phase_id);
                $project = @\App\Project::find($phase->project_id);
                $seller = $project->{app()->getLocale().'_name'};
            } elseif ($proposal->unit_type == 'resale') {
                $unit = \App\ResaleUnit::find($proposal->unit_id);
                $lead = \App\Lead::find($unit->lead_id);
                $lead_id = $unit->lead_id;
                $seller = $lead->first_name . ' ' . $lead->last_name;
            } elseif ($proposal->unit_type == 'rental') {
                $unit = \App\RentalUnit::find($proposal->unit_id);
                $lead = \App\Lead::find($unit->lead_id);
                $lead_id = $unit->lead_id;
                $seller = $lead->first_name . ' ' . $lead->last_name;
            } elseif ($proposal->unit_type == 'land') {
                $unit = \App\Land::find($proposal->unit_id);
                $lead = \App\Lead::find($unit->lead_id);
                $lead_id = $unit->lead_id;
                $seller = $lead->first_name . ' ' . $lead->last_name;
            }
        @endphp
        <strong>{{ trans('admin.seller') }} : </strong>
        @if($proposal->unit_type != 'new_home')
            <a href="{{ url(adminPath().'/leads/'.$lead_id) }}">{{ $seller }}</a>
        @else
            {{ $seller }}
        @endif
        <br/>
        <hr/>
    </div>
    <div class="col-md-6">
        <strong>{{ trans('admin.unit_type') }} : </strong> {{ trans('admin.'.$proposal->unit_type) }}
        <br/>
        <hr/>
    </div>
    <div class="col-md-6">
        <strong>{{ trans('admin.unit') }} : </strong>
        @if($proposal->unit_type == 'new_home')
            <a href="{{ url(adminPath().'/properties/'.$proposal->unit_id) }}">{{ @\App\Property::find($proposal->unit_id)->{app()->getLocale().'_name'} }}</a>
        @elseif($proposal->unit_type == 'resale')
            <a href="{{ url(adminPath().'/resale_units/'.$proposal->unit_id) }}">{{ @\App\ResaleUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}</a>
        @elseif($proposal->unit_type == 'rental')
            <a href="{{ url(adminPath().'rental_units'.$proposal->unit_id) }}">{{ @\App\RentalUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}</a>
        @elseif($proposal->unit_type == 'land')
            <a href="{{ url(adminPath().'/lands/'.$proposal->unit_id) }}">{{ @\App\Land::find($proposal->unit_id)->{app()->getLocale().'_title'} }}</a>
        @endif
        <br/>
        <hr/>
    </div>
    <div class="col-md-6">
        <strong>{{ trans('admin.price') }} : </strong>
        @if($proposal->unit_type == 'new_home')
            {{ @\App\Property::find($proposal->unit_id)->start_price }}
        @elseif($proposal->unit_type == 'resale')
            {{ @\App\ResaleUnit::find($proposal->unit_id)->price }}
        @elseif($proposal->unit_type == 'rental')
            {{ @\App\RentalUnit::find($proposal->unit_id)->rent }}
        @elseif($proposal->unit_type == 'land')
            {{ @\App\Land::find($proposal->unit_id)->meter_price . ' * ' . @\App\Land::find($proposal->unit_id)->area }}
        @endif
        <br/>
        <hr/>
    </div>
    @if($proposal->unit_type == 'new_home')
        <div class="col-md-6">
            @php
                $unit = @\App\Property::find($proposal->unit_id);
                $phase = @\App\Phase::find($unit->phase_id);
                $project = @\App\Phase::find($phase->project_id);
            @endphp
            <strong>{{ trans('admin.project') }} : </strong> <a
                    href="{{ url(adminPath().'/projects/'.$project->id) }}">{{ $project->{app()->getLocale().'_name'} }}</a>
            <br/>
            <hr/>
        </div>
    @endif
    <div class="col-md-6">
        <strong>{{ trans('admin.file') }} : </strong>
        <a href="{{ url('uploads/'.$proposal->file) }}" target="_blank" class="fa fa-file"></a>
        <br/>
        <hr/>
    </div>
</div>