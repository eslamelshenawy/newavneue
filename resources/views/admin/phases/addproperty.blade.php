@extends('admin.index')
@section('content')
    <style>
        #show_image {
            display: none;
        }
    </style>
    @if(session()->has('propertySuccess'))
        <div class="alert alert-success">{{ session()->get('propertySuccess') }} Success</div>
    @endif
    @if(session()->has('propertyErrors'))
    <div class="alert alert-danger">{{ session()->get('propertyErrors') }} Failed</div>
    @endif
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
                <strong>{{ trans('admin.id') }} : </strong>{{ $phase->id }}
                <br><hr>
                <strong>{{ trans('admin.en_name') }} : </strong>{{ $phase->en_name }}
                <br><hr>
                <strong>{{  trans('admin.ar_name') }} : </strong>{{ $phase->ar_name }}
                <br><hr>
                <strong>{{ trans('admin.en_description') }} : </strong>{{ $phase->en_description }}
                <br><hr>
                <strong>{{ trans('admin.ar_description') }} : </strong>{{ $phase->ar_description }}
                <br><hr>
                <strong>{{ trans('admin.meter_price') }} : </strong>{{ $phase->meter_price }}
                <br><hr>
                <strong>{{ trans('admin.area') }} : </strong>{{ $phase->area }}
                <br><hr>
                <strong>{{ trans('admin.delivery_date') }} : </strong>{{ $phase->delivery_date }}
                <br><hr>
            <strong>{{ trans('admin.facilities') }} : </strong>
            @foreach($facilities as $facility)
                {{ '('.\App\Facility::find($facility->facility_id)->{app()->getLocale().'_name'}.') '  }}
                @endforeach
            <br><hr>
        <table class="table table-hover table-striped datatable">
            <thead>
            <tr>

                <th>{{ trans('admin.id') }}</th>
                <th>{{ trans('admin.name') }}</th>
                <th>{{ trans('admin.type') }}</th>
                <th>{{ trans('admin.price') }}</th>
                <th>{{ trans('admin.show') }}</th>
                <th>{{ trans('admin.edit') }}</th>
                <th>{{ trans('admin.delete') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($property as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->{app()->getLocale().'_name'} }}</td>
                    <td>{{ App\UnitType::find($row->unit_id)->{app()->getLocale().'_name'} }}</td>
                    <td>{{ $row->start_price }}</td>
                    <td><a href="{{ url(adminPath().'/properties/'.$row->id) }}"
                           class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                    <td><a href="{{ url(adminPath().'/properties/'.$row->id.'/edit') }}"
                           class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
                    <td><a data-toggle="modal" data-target="#delete{{ $row->id }}"
                           class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                </tr>
            </tbody>
            <div id="delete{{ $row->id }}" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                        </div>
                        <div class="modal-body">
                            <p>{{ trans('admin.delete') . ' ' . $row->en_name }}</p>
                        </div>
                        <div class="modal-footer">
                            {!! Form::open(['method'=>'DELETE','route'=>['properties.destroy',$row->id]]) !!}
                            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('admin.close') }}</button>
                            <button type="submit" class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </table>
            <form id="form1" action="{{ url(adminPath().'/phases/property') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $phase->id }}" name="phase_id">
                <div class="row">
                    <div id="body" class="col-md-10 col-md-push-1"></div>
                </div>
                <button type="button" class="btn btn-success addproperty">{{ trans('admin.add').' '.trans('admin.property')  }}</button>
                <button type="submit" id="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>

        var x = 1;
        $(document).on('click', '.addproperty', function () {

            $('#body').append('@include("admin.phases.property")');
            x++;
            $('.select2').select2();
        });
        $(document).on('click', '.removeprop', function () {
            var count = $(this).attr('num');
            console.log(count);
            $('#prop'+count).remove();
            x--;
            $('.select2').select2();
            $('.tagsinput').tagsinput('refresh');
        });
        $(document).on('change', '.__unit', function () {
            var usage= $(this).val();
            var unit_id=$(this).attr('id')+'_type';
            console.log(unit_id);
            var _token= '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_unit_types') }}",
                method: 'post',

                data: {usage: usage, _token: _token},
                success: function (data) {
                    console.log('success');
                    $('#'+unit_id).html(data);
                }
            });
        });
//        $(document).on('click','#submit',function () {
//            $("#form1").submit();
//        })
//        $("#form").submit(function(e){
//            e.preventDefault();
//            $("#form").submit();
//        });
    </script>
@endsection
