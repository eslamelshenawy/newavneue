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
        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.min_price') }}</label>
            <input id="min_price" class="form-control col-xs-6">


        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.max_price') }}</label>
            <input id="max_price" class="form-control  col-xs-6">

        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.min_area') }}</label>
            <input id="min_area" class="form-control col-xs-6">


        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.max_area') }}</label>
            <input id="max_area" class="form-control  col-xs-6">
        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.min_down_payment') }}</label>
            <input id="min_down_payment" class="form-control  col-xs-6">
        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.max_down_payment') }}</label>
            <input id="max_down_payment" class="form-control  col-xs-6">
        </div>
        <div class="col-xs-12">
            <label>{{ __('admin.installment_year') }}</label>
            <input id="installment" class="form-control  col-xs-6">
        </div>
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
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>
            <div class="col-xs-12">
                <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                   href="{{ url(adminPath().'/projects/create') }}">{{ trans('admin.add') }}</a>
            </div>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">


            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.id') }}</th>
                    <th>{{ trans('admin.en_name') }}</th>
                    <th>{{ trans('admin.developer') }}</th>
                    <th>{{ trans('admin.phases') }}</th>
                    <th>{{ trans('admin.show') }}</th>
                    <th>{{ trans('admin.edit') }}</th>
                    <th>{{ trans('admin.delete') }}</th>
                </tr>
                </thead>
                <tbody id="data">
                @foreach($project as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->{app()->getLocale().'_name'} }}</td>
                        <td>
                            <a href="{{ url(adminPath().'/developers/'.$row->developer_id) }}">{{ @App\Developer::find($row->developer_id)->{app()->getLocale().'_name'} }}</a>
                        </td>
                        <td>{{ @\App\Phase::where('project_id',$row->id)->count() }}</td>
                        {{--<td>--}}
                        {{--@if($row->featured == 0)--}}
                        {{--<a href="{{ url(adminPath().'/project_featured/'.$row->id) }}"><span class="fa fa-star"></span></a>--}}
                        {{--@elseif($row->featured == 1)--}}
                        {{--<a href="{{ url(adminPath().'/project_un_featured/'.$row->id) }}"><span class="fa fa-star featured"></span></a>--}}
                        {{--@endif--}}
                        {{--</td>--}}
                        <td><a href="{{ url(adminPath().'/projects/'.$row->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                        <td><a href="{{ url(adminPath().'/projects/'.$row->id.'/edit') }}"
                               class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
                        <td><a data-toggle="modal" data-target="#delete{{ $row->id }}"
                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                    </tr>
                    <div id="delete{{ $row->id }}" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ trans('admin.delete') . ' ' . $row->name }}</p>
                                </div>
                                <div class="modal-footer">
                                    {!! Form::open(['method'=>'DELETE','route'=>['projects.destroy',$row->id]]) !!}
                                    <button type="button" class="btn btn-default btn-flat"
                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    <button type="submit"
                                            class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
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
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': true,
            "order": [[0, "desc"]],
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
    <script>
        $(document).on('change , keyup', '#developer,#min_price,#max_price,#min_area,#min_down_payment,#max_down_payment,#max_area,#location,#installment', function () {
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
@endsection