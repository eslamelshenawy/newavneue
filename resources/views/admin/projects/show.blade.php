@extends('admin.index')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <strong>{{ trans('admin.id') }} : </strong>{{ $project->id }}
            <br>
            <hr>
            <strong>{{ trans('admin.en_name') }} : </strong>{{ $project->en_name }}
            <br>
            <hr>
            <strong>{{ trans('admin.ar_name') }} : </strong>{{ $project->ar_name }}
            <br>
            <hr>
            <strong>{{ trans('admin.en_description') }} : </strong>{{ $project->en_description }}
            <br>
            <hr>
            <strong>{{ trans('admin.ar_description') }} : </strong>{{ $project->ar_description }}
            <br>
            <hr>
            @php($dPDFs = json_decode($project->developer_pdf))
            @php($bPDFs = json_decode($project->broker_pdf))
            
                @if($dPDFs)
                    <strong>{{ trans('admin.developer_pdf') }} : </strong>
                        @foreach($dPDFs as $pdf)
                            <a class="fa fa-file" href="{{ url('uploads/' . $pdf) }}" target="_blank"></a>
                        @endforeach
                    <br>
                    <hr>
                @endif
                
                @if($bPDFs)
                    <strong>{{ trans('admin.broker_pdf') }} : </strong>
                        @foreach($bPDFs as $pdf)
                            <a class="fa fa-file" href="{{ url('uploads/' . $pdf) }}" target="_blank"></a>
                        @endforeach
                    <br>
                    <hr>
                @endif
                
            <strong>{{ trans('admin.meter_price') }} : </strong>{{ $project->meter_price }}
            <br>
            <hr>
            <strong>{{ trans('admin.area') }} : </strong>
                {{ $project->area }} <i class="fa fa-arrows-h"></i> {{ $project->area_to }}
            <br>
            <hr>

            <strong>{{ trans('admin.developer') }}
                : </strong>{{ @\App\Developer::find($project->developer_id)->en_name }}
            <br>
            <hr>
            <strong>{{ trans('admin.logo') }} : </strong><br>
            <img src="/uploads/{{ $project->logo }}" class="img-thumbnail" alt="Cinque Terre" width="304" height="236">
            <br>
            <hr>
            <strong>{{ trans('admin.cover') }} : </strong><br>
            <img src="/uploads/{{ $project->cover }}" class="img-thumbnail" alt="Cinque Terre" width="304" height="236">
            <br>
            <hr>
            <strong>{{ trans('admin.location') }} : </strong>{{ $location }}
            <br>
            <hr>
            <div id="map" style="height: 500px;z-index:20"></div>
            <br>
            <hr>

            <div class="container">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner">
                        @foreach(@App\Gallery::where('project_id' ,$project->id)->get() as $img)
                            <div class="item @if($loop->first) active @endif">
                                <img src="{{ url('uploads/'.$img->image) }}" alt="Los Angeles" style="width:100%;">
                            </div>
                        @endforeach
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="fa fa-angle-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="fa fa-angle-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>

                    <th>{{ trans('admin.id') }}</th>
                    <th>{{ trans('admin.name') }}</th>
                    <th>{{ trans('admin.show') }}</th>
                    <th>{{ trans('admin.edit') }}</th>
                    <th>{{ trans('admin.delete') }}</th>
                </tr>
                </thead>
                <tbody>
                <a href="{{url(adminPath().'/add/phase/'.$project->id)}}"
                   class="btn btn-primary btn-flat">{{ trans('admin.add').' '.trans('admin.phase') }}</a>
                @foreach($phases as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->{app()->getLocale().'_name'} }}</td>
                        <td><a href="{{ url(adminPath().'/phases/show/'.$row->id) }}"
                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                        <td><a href="{{ url(adminPath().'/phases/edit/'.$row->id) }}"
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
                                <form method='post' action="{{ url(adminPath().'/phases/destroy') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="phase_id" value="{{ $row->id }}">
                                    <button type="button" class="btn btn-default btn-flat"
                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    <button type="submit"
                                            class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </table>
            <br>
            <hr>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>
        function initAutocomplete() {
            var lat = parseFloat({{ $project->lat }});
            var lng = parseFloat({{ $project->lng }});
            var zoom = parseInt({{ $project->zoom }});
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: zoom,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.


            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map
            });

        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ67H5QBLVTdO2pnmEmC2THDx95rWyC1g&libraries=places&callback=initAutocomplete"
            async defer></script>

@endsection
