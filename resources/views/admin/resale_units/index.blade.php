@extends('admin.index')

@section('content')
    <div class="filter-icon"><i class="fa fa-filter"></i></div>
    <div class="filter">
        <div class="col-xs-12">
            <label>{{ __('admin.developer') }}</label>
            <select class="select2 form-control" data-placeholder="{{ __('admin.developer') }}" id="developer">
                <option></option>
                <option value="all">{{ __('admin.all') }}</option>
                @foreach(@\App\Developer::get() as $dev)
                    <option value="{{ $dev->id }}">{{ $dev->{app()->getLocale().'_name'} }}</option>
                @endforeach
            </select>
            <div class="row">
                <div class="col-xs-6">
                    <label>{{ __('admin.min_rooms') }}</label>
                    <input type="text" class="form-control" id="min-rooms">
                </div>
                <div class="col-xs-6">
                    <label>{{ __('admin.max_rooms') }}</label>
                     <input type="text" class="form-control" id="max-rooms">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label>{{ __('admin.min_bathrooms') }}</label>
                    <input type="text" class="form-control" id="min-bathrooms">
                </div>
                <div class="col-xs-6">
                    <label>{{ __('admin.max_bathrooms') }}</label>
                     <input type="text" class="form-control" id="max-bathrooms">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label>{{ __('admin.min_price') }}</label>
                    <input type="text" class="form-control" id="min-price">
                </div>
                <div class="col-xs-6">
                    <label>{{ __('admin.max_price') }}</label>
                     <input type="text" class="form-control" id="max-price">
                </div>
            </div>
            <div class="row">
                 <div class="col-xs-12">
                    <label>{{ __('admin.locations') }}</label>
                    <select class="select2 form-control" data-placeholder="{{ __('admin.locations') }}" id="location">
                        <option></option>
                        <option value="all">{{ __('admin.all') }}</option>
                        @foreach(@\App\Location::get() as $dev)
                            <option value="{{ $dev->id }}">{{ $dev->{app()->getLocale().'_name'} }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
           
         <div class="row">
                <div class="col-xs-12">
                    <label>{{ __('admin.delivery_date') }}</label>
                    <input type="text" class="form-control" id="delivery-date">
                </div>
        </div>
        </div>
        </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
               href="{{ url(adminPath().'/resale_units/create') }}">{{ trans('admin.add') }}</a>
            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.property') }}</th>
                    <th>{{ trans('admin.title') }}</th>
                    <th>{{ trans('admin.status') }}</th>
                    <th>{{ trans('admin.location') }}</th>
                    <th>{{ trans('admin.price') }}</th>
                    <th>{{ trans('admin.rooms') }}</th>
                    <th>{{ trans('admin.bathrooms') }}</th>
                    <th>{{ trans('admin.area') }}</th>
                    <th>{{ trans('admin.delivery_date') }}</th>
                    <th>{{ trans('admin.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td><img src="{{ url('uploads/'.$unit->image) }}" width="75 px"></td>
                        <td>{{ $unit->{app()->getLocale().'_title'} }}</td>
                        <td>{{ trans('admin.'.$unit->availability) }}</td>
                        <td>{{ @\App\Location::find($unit->location)->{app()->getLocale().'_name'} }}</td>
                        <td>{{ $unit->total }}</td>
                        <td>{{ $unit->rooms }}</td>
                        <td>{{ $unit->bathrooms }}</td>
                        <td>{{ $unit->area }}</td>
                        <td>{{ $unit->delivery_date }}</td>
                        <td><a href="{{ url(adminPath().'/resale_units/'.$unit->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a>
                            <a href="{{ url(adminPath().'/resale_units/'.$unit->id.'/edit') }}"
                               class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a>
                            <a data-toggle="modal" data-target="#delete{{ $unit->id }}"
                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                    </tr>
                    <div id="delete{{ $unit->id }}" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.resale_unit') }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ trans('admin.delete') . ' ' . $unit->name }}</p>
                                </div>
                                <div class="modal-footer">
                                    {!! Form::open(['method'=>'DELETE','route'=>['resale_units.destroy',$unit->id]]) !!}
                                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    <button type="submit" class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
        <script>
        $(document).on('change , keyup', '#developer,#min_price,#max_price,#min_rooms,#max_rooms,,#min_bathrooms,#max_bathrooms,#delivery-date,#location', function () {
            var dev = $('#developer').val();
            var min_price = $('#min_price').val();
            var max_price = $('#max_price').val();
            var min_area = $('#min_area').val();
            var max_area = $('#max_area').val();
            var location = $('#location').val();
            var installment = $('#installment').val();
            var min_down_payment = $('#min_down_payment').val();
            var max_down_payment = $('#max_down_payment').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_developer_projects')}}",
                method: 'post',
                dataType: 'html',
                data: {dev: dev,min_price : min_price,installment:installment,min_down_payment:min_down_payment,max_down_payment:max_down_payment,max_price : max_price,max_area : max_area,min_area : min_area,location:location, _token: _token},
                success: function (data) {
                    $('#data').html(data);
                }
            })
        })
    </script>
    <script>
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': true,
            "order": [[ 0, "desc" ]],
            'autoWidth': true
        });
        var filter_show = 0;
        $('.filter-icon').on('click',function () {
            
            if (!filter_show){
                $('.filter').css('right',0);
                $('.filter-icon').css('right','500px');
                filter_show = 1;
            }else{
                $('.filter').css('right','-500px');
                $('.filter-icon').css('right','0');
                filter_show = 0;
            }

        });
    </script>
@stop