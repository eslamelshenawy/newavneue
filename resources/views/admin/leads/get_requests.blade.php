<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ trans('admin.delivery_date') }}</th>
        <th>{{ trans('admin.show') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reqs as $req)
        <tr>
            <td>
                {{ $req->date }}
            </td>
            <td><a data-toggle="modal" data-target="#getReq{{ $req->id }}"
                   class="btn btn-primary btn-flat"> {{ trans('admin.show') }} </a> </td>
        </tr>
        <div id="getReq{{ $req->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.request') }}</h4>
                    </div>
                    <div class="modal-body">
                        <strong>{{ trans('admin.id') }} : </strong>{{ $req->id }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.lead') }}
                            : </strong>{{ @\App\Lead::find($req->lead_id)->first_name . ' ' . @\App\Lead::find($req->lead_id)->last_name }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.price') }} : </strong>{{ $req->price_from }} <i
                                class="fa fa-arrows-h"></i> {{ $req->price_to }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.area') }} : </strong>{{ $req->area_from }} <i
                                class="fa fa-arrows-h"></i> {{ $req->area_to }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.location') }}: </strong>{{ @\App\Location::find($req->location)->{app()->getLocale().'_name'} }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.delivery_date') }}: </strong>{{ $req->date }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.down_payment') }}: </strong>{{ $req->down_payment }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.notes') }} : </strong>{{ $req->notes }}
                        <br>
                        <hr>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat"
                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    </tbody>
</table>