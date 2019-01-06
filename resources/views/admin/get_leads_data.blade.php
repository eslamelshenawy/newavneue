@foreach($leads as $lead)
    <tr>
        <td>{{ $lead->first_name }}</td>
        <td>{{ @\App\LeadSource::find($lead->lead_source_id)->name }}</td>
        <td>{{ @\App\Call::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('lead_id', $lead->id)->count() }}</td>
        <td>{{ @\App\Meeting::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('lead_id', $lead->id)->count() }}</td>
        <td>{{ @\App\ClosedDeal::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('buyer_id', $lead->id)->count() }}</td>
        <td>{{ @\App\ClosedDeal::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('seller_id', $lead->id)->count() }}</td>
        <td>{{ @\App\ClosedDeal::where('created_at', '<=', $to . ' 00:00:00')->where('created_at', '>=', $from . ' 23:59:59')->where('buyer_id', $lead->id)->sum('price') }}</td>
        <td>{{ $lead->created_at }}</td>
    </tr>
@endforeach