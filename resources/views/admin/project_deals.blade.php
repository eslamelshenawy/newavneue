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