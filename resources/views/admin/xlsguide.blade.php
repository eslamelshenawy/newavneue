<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export</title>
</head>
<body>
<table>
    <tr>
        <th>agent id</th>
        <th>agent name</th>
        <th></th>
        <th>location id</th>
        <th>location arabic name</th>
        <th>location english name</th>
        <th></th>
        <th>project id</th>
        <th>project arabic name</th>
        <th>project english name</th>
        <th></th>
        <th>Unit Type id</th>
        <th>Unit Type arabic name</th>
        <th>Unit Type english name</th>
        <th></th>
        <th>Lead Source id</th>
        <th>Lead Source name</th>
        <th>Request Type</th>
        <th>Request Type id</th>
        <th>Lead Type</th>
    </tr>
    @for($i=0;$i<$count;$i++)
        <tr>
            @if(isset($agents[$i]))
            <td>{{ $agents[$i]['id'] }}</td>
            <td>{{ $agents[$i]['name'] }}</td>
                @else
                <td></td>
                <td></td>
            @endif
                <td></td>
                @if(isset($location[$i]))
                    <td>{{ $location[$i]['id'] }}</td>
                    <td>{{ $location[$i]['ar_name'] }}</td>
                    <td>{{ $location[$i]['en_name'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
            <td></td>
                @if(isset($projects[$i]))
                    <td>{{ $projects[$i]['id'] }}</td>
                    <td>{{ $projects[$i]['ar_name'] }}</td>
                    <td>{{ $projects[$i]['en_name'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                <td></td>
                @if(isset($unit_type[$i]))
                    <td>{{ $unit_type[$i]['id'] }}</td>
                    <td>{{ $unit_type[$i]['ar_name'] }}</td>
                    <td>{{ $unit_type[$i]['en_name'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                <td></td>
                @if(isset($lead_source[$i]))
                    <td>{{ $lead_source[$i]['id'] }}</td>
                    <td>{{ $lead_source[$i]['name'] }}</td>
                @else
                    <td></td>
                    <td></td>
                @endif
                @if(isset($types[$i]))
                    @if($i == 0)
                        <td>1</td>
                    @endif
                    @if($i == 1)
                        <td>2</td>
                    @endif
                    @if($i == 2)
                        <td>3</td>
                    @endif
                @endif
                @if(isset($types[$i]))
                    <td>{{ $types[$i] }}</td>
                @endif
                @if(isset($lead_types[$i]))
                    <td>{{ $lead_types[$i] }}</td>
                @endif
        </tr>
        @endfor
</table>
</body>
</html>
