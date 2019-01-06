<hr>
<a class="pull-right" style="font-size: 1.4em; color: #000; text-decoration: none; margin: 0px 5px" href="{{ url(adminPath().'/groups/create') }}">
    <i class="fa fa-plus" data-toggle="tooltip" title="{{ trans('admin.add') }}"></i>
</a>
<a data-toggle="modal" data-target="#delete" class="pull-right" style="font-size: 1.4em; color: #000; text-decoration: none; margin: 0px 5px" href="#">
    <i class="fa fa-trash" data-toggle="tooltip" title="{{ trans('admin.delete') }}"></i>
</a>
<a class="pull-right" style="font-size: 1.4em; color: #000; text-decoration: none; margin: 0px 5px" href="{{ url(adminPath().'/groups/' . $group->id .'/edit') }}">
    <i class="fa fa-edit" data-toggle="tooltip" title="{{ trans('admin.edit') }}"></i>
</a>

<div id="delete" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.group') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ trans('admin.delete') . ' ' . $group->name }}</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['method'=>'DELETE','route'=>['groups.destroy',$group->id]]) !!}
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('admin.close') }}</button>
                <button type="submit" class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>

<strong>{{ trans('admin.id') }} : </strong>{{ $group->id }}
<br><hr>
<strong>{{ trans('admin.name') }} : </strong>{{ $group->name }}
<br><hr>
<strong>{{ trans('admin.parent') }} : </strong>
@if($group->parent_id != 0)
    {{ @App\Group::find($group->parent_id)->name }}
@else
    {{ trans('admin.main') }}
@endif
<br><hr>
<strong>{{ trans('admin.team_leader') }} : </strong>{{ @App\User::find($group->team_leader_id)->name }}
<br><hr>
<strong>{{ trans('admin.members') }} : </strong>
@foreach(@App\GroupMember::where('group_id',$group->id)->pluck('member_id') as $member)
    @if(!$loop->last)
        {{ @App\User::find($member)->name }} -
    @else
        {{ @App\User::find($member)->name }}
    @endif
@endforeach
<br><hr>
<strong>{{ trans('admin.notes') }} : </strong>{{ $group->notes }}
<br><hr>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>