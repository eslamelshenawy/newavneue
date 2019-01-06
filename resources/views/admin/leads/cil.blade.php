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
        <th style="width: 30%; text-align: left">Address</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->address }}</td>
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
        <th style="width: 30%; text-align: left">Industry</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ @App\Industry::find($lead->industry_id)->name }}</td>
    </tr>
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Company</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->company }}</td>
    </tr>
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Club Membership</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->club }}</td>
    </tr>
    @if(!empty($file))
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Attatchment</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black"><a href="{{ url('uploads/'.@\App\LeadDocument::find($file)->file) }}">File</a></td>
    </tr>
    @endif
    @if($project > 0)
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Project</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ @App\Project::find($project)->en_name }}</td>
    </tr>
    @endif
</table>
<br/>
<h4 style="color: #caa42d">Kids Information:</h4>
<br/>
<table style="width: 100%">
    <tr style="height: 50px">
        <th style="width: 30%; text-align: left">Kids Schools</th>
        <td style="width: 70%; text-align: left; border-bottom: 1px solid black">{{ $lead->school }}</td>
    </tr>
</table>