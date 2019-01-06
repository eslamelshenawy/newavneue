<table class="datatable table table-bordered table-hover">
    <thead>
    <tr>
        <th>{{ __('admin.name') }}</th>
        <th>{{ __('admin.type') }}</th>
        <th>{{ __('admin.calls') }}</th>
        <th>{{ __('admin.meetings') }}</th>
        <th>{{ __('admin.leads') }}</th>
        <th>{{ __('admin.money') }}</th>
        <th>{{ __('admin.target') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($agents as $agent)
        @php
            $target = @\App\Target::where('agent_type_id',$agent->agent_type_id)->where('month', $month)->first();

            if ($target == null) {
                $calls = 0;
                $meetings = 0;
                $leads = 0;
                $money = 0;
                $target = new stdClass();
                $target->calls = 0;
                $target->meetings = 0;
                $target->leads = 0;
                $target->money = 0;
                $target->month = 0;
            } else {
                $calls = @\App\Call::where('user_id',$agent->id)->
                where('created_at','>=', $target->month.'-01 00:00:00')->
                where('created_at','<=', $target->month.'-31 23:59:59')->
                count();

                $meetings = @\App\Meeting::where('user_id',$agent->id)->
                where('created_at','>=', $target->month.'-01 00:00:00')->
                where('created_at','<=', $target->month.'-31 23:59:59')->
                count();

                $leads = @\App\Lead::where('agent_id',$agent->id)->
                where('created_at','>=', $target->month.'-01 00:00:00')->
                where('created_at','<=', $target->month.'-31 23:59:59')->
                count();

                $money = @\App\ClosedDeal::where('agent_id',$agent->id)->
                where('created_at','>=', $target->month.'-01 00:00:00')->
                where('created_at','<=', $target->month.'-31 23:59:59')->
                sum('price');
            }
        @endphp
        <tr>
            <td>{{ $agent->name }}</td>
            <td>{{ @\App\AgentType::find(@$agent->agent_type_id)->name }}</td>
            <td @if($calls < $target->calls) style="background-color: rgba(255, 0, 0, 0.2);" @endif>{{ $calls . '/' . $target->calls }}</td>
            <td @if($meetings < $target->meetings) style="background-color: rgba(255, 0, 0, 0.2);" @endif>{{ $meetings . '/' . $target->meetings }}</td>
            <td @if($leads < $target->leads) style="background-color: rgba(255, 0, 0, 0.2);" @endif>{{ $leads . '/' . $target->leads }}</td>
            <td @if($money < $target->money) style="background-color: rgba(255, 0, 0, 0.2);" @endif>{{ $money . '/' . $target->money }}</td>
            <td>{{ $target->month }}</td>
        </tr>
    @endforeach
    </tbody>
</table>