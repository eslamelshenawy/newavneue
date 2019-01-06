<div style="width: 100%">
    <strong>{{ trans('admin.type',[],$lang) }} : </strong>{{ trans('admin.'.$unit->type,[],$lang) }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.unit_type',[],$lang) }}
        : </strong>{{ @\App\UnitType::find($unit->unit_type_id)->{$lang.'_name'} }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.project',[],$lang) }}
        : </strong>{{ @\App\Project::find($unit->project_id)->{$lang.'_name'} }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.original_price',[],$lang) }} : </strong>{{ $unit->original_price }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.payed',[],$lang) }} : </strong>{{ $unit->payed }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.rest',[],$lang) }} : </strong>{{ $unit->rest }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.total',[],$lang) }} : </strong>{{ $unit->total }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.delivery_date',[],$lang) }} : </strong>{{ $unit->delivery_date }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.due_now',[],$lang) }} : </strong>{{ $unit->due_now }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.finishing',[],$lang) }} : </strong>{{ trans('admin.'.$unit->finishing,[],$lang) }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.location',[],$lang) }}
        : </strong>{{ @\App\Location::find($unit->location)->{$lang.'_name'} }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.description',[],$lang) }}
        : </strong>{{ $unit->{$lang.'_description'} }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.title',[],$lang) }}
        : </strong>{{ $unit->{$lang.'_title'} }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.address',[],$lang) }}
        : </strong>{{ $unit->{$lang.'_address'} }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.youtube_link',[],$lang) }}
        : </strong><a href="{{ $unit->youtube_link }}" class="fa fa-youtube" target="_blank"></a>
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.area',[],$lang) }}
        : </strong>{{ $unit->area }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.price',[],$lang) }}
        : </strong>{{ $unit->price }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.rooms',[],$lang) }}
        : </strong>{{ $unit->rooms }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.bathrooms',[],$lang) }}
        : </strong>{{ $unit->bathrooms }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.floors',[],$lang) }}
        : </strong>{{ $unit->floors }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.image',[],$lang) }}
        : </strong> <img height="50px" src="{{ url('uploads/'.$unit->image) }}">
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.payment_method',[],$lang) }} : </strong>{{ trans('admin.'.$unit->payment_method,[],$lang) }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.view',[],$lang) }} : </strong>{{ trans('admin.'.$unit->view,[],$lang) }}
    <br>
    <hr>
</div>
<div style="width: 100%">
    <strong>{{ trans('admin.availability',[],$lang) }} : </strong>{{ trans('admin.'.$unit->availability,[],$lang) }}
    <br>
    <hr>
</div>