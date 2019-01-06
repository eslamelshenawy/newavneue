@extends('admin.index')

@section('content')

    @include('admin.employee.hr_nav')

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }} </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <label class="col-xs-12">{{ __('admin.filter') }}</label>
                <div class="form-group col-md-3">

                    <select id="categories" class="form-control col-md-6">
                        <option value="0">{{ __('admin.category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->{app()->getLocale().'_name'} }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label></label>
                    <select id="titles" class="form-control col-md-6">
                        <option value="0">{{ __('admin.job_title') }}</option>
                        @foreach($job_titles as $job_title)
                            <option value="{{ $job_title->id }}">{{ $job_title->{app()->getLocale().'_name'} }}</option>
                        @endforeach
                    </select>
                </div>
                <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
                   href="{{ url(adminPath().'/applications/create/') }}">{{ trans('admin.add') }}</a>

                <div class="tab-content" id="apps">
                    <div id="under_review" class="tab-pane fade in active">
                        <table class="table table-hover table-striped datatable">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.name') }}</th>
                                <th>{{ trans('admin.category') }}</th>
                                <th>{{ trans('admin.status') }}</th>
                                <th>{{ trans('admin.linkedin') }}</th>
                                <th>{{ trans('admin.cv') }}</th>
                                <th>{{ trans('admin.show') }}</th>
                                <th>{{ trans('admin.edit') }}</th>
                                <th>{{ trans('admin.delete') }}</th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                                    <td>{{ @$application->job_category->{app()->getLocale().'_name'} }} - {{ @$application->job_title->{app()->getLocale().'_name'} }}</td>
                                    <td>{{ __('admin.'.$application->acceptness)}}</td>
                                    <td><a href="{{ $application->linkedin }}">Linked In</a></td>
                                    <td><a href="{{ url('uploads/'.$application->cv) }}">CV</a></td>
                                    <td><a href="{{ url(adminPath().'/applications/'.$application->id) }}" class="btn btn-default">{{ __('admin.show') }}</a></td>
                                    <td><a class="btn btn-warning" href="{{ url(adminPath().'/applications/'.$application->id.'/edit') }}">{{ __('admin.edit') }}</a></td>
                                    <td><a data-toggle="modal" data-target="#delete{{ $application->id }}"
                                           class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                                </tr>
                                <div id="delete{{ $application->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('admin.delete') . ' ' . $application->{app()->getLocale().'_name'} }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                {!! Form::open(['method'=>'DELETE','route'=>['applications.destroy',$application->id]]) !!}
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

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "320px";
            document.getElementById("main").style.marginLeft = "320px";
            document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        var closeNav = function () {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            document.body.style.backgroundColor = "white";
        }
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
        })
    </script>
    <script>
        $(document).on('change','#categories',function () {
            var cat = $(this).val();
            var type = 'cat';
            var title = $('#titles').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_applications')}}",
                method: 'post',
                dataType: 'html',
                data: {cat: cat,type :type,title:title, _token: _token},
                success: function (data) {
                    console.log(data);
                    $('#apps').html(data);
                }
            })
        });
        $(document).on('change','#titles',function () {
            var title = $(this).val();
            var type = 'title';
            var cat = $('#categories').val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_applications')}}",
                method: 'post',
                dataType: 'html',
                data: {title: title,cat: cat,type :type, _token: _token},
                success: function (data) {
                    console.log(data);
                    $('#apps').html(data);
                }
            })
        });
    </script>
@stop