@extends('admin.index')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_title') }} : </strong>{{ $event->ar_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_title') }} : </strong>{{ $event->en_title }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.ar_description') }} : </strong>{{ $event->ar_description }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.en_description') }} : </strong>{{ $event->en_description }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.date') }} : </strong>{{ date('Y M d',$event->date) }}
                <br/>
                <hr/>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.tags') }} : </strong>
                @if($event->event)
                    <span class="label label-primary" style="border-radius: 0; padding-top: 5px;">{{ __('admin.event') }}</span>
                @endif
                @if($event->news)
                    <span class="label label-danger" style="border-radius: 0; padding-top: 5px;">{{ __('admin.news') }}</span>
                @endif
                @if($event->launch)
                    <span class="label label-warning" style="border-radius: 0; padding-top: 5px;">{{ __('admin.launch') }}</span>
                @endif
                <br/>
                <hr/>
            </div>
            <div class="col-md-12">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="{{ url('uploads/'.$event->image) }}" alt=""
                                 style="width:100%;">
                        </div>
                        @foreach(@\App\EventImage::where('event_id',$event->id)->get() as $img)
                            <div class="item">
                                <img src="{{ url('uploads/'.$img->image) }}" alt=""
                                     style="width:100%;">
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
        </div>
    </div>
@endsection