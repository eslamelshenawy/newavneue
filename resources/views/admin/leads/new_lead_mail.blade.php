<div>
    <h2>Application Form</h2>
    <hr/>
</div>
<h4 style="color: #caa42d">Client Information:</h4>
<table style="width: 100%">
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Date</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ explode(' ',$lead->created_at)[0] }}</td>
    </tr>
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Name</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->first_name . ' ' . $lead->last_name }}</td>
    </tr>
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Phone</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->phone }}</td>
    </tr>
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Email</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black"><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
    </tr>
     <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Note</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->notes  }}</td>
    </tr>
     <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Refernce</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->reference }}</td>
    </tr>
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Source</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $source }}</td>
    </tr>

   @if($lead_request)
     <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Requested</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">
            @if($lead_request->type == 'buyer')
                to buy 
            @else
                to sell 
            @endif
            @if($lead_request->unit_type == "personal") Residential @else {{ $lead_request->unit_type }}@endif
            {{ $lead_request->request_type }} in 
            {{ @App\Location::find($lead_request->location)->{app()->getLocale().'_name'} }}
            in Projects 
            @foreach($request->request_project_id as  $project)
                {{ App\Project::find($project)->en_name }}  @if(!$loop->last) - @endif
            @endforeach
            </td>
    </tr>
   @endif
</table>
<br/>

<br/>
