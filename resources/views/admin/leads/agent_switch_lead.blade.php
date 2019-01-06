<p>These leads have been switched to you:</p>
<br/>
@foreach($leads as $lead)
    <strong>Name: </strong> {{ $lead->first_name . ' ' . $lead->last_name }}
    -
    <strong>Phone: </strong> {{ $lead->phone }}
    <br/>
    <hr/>
@endforeach