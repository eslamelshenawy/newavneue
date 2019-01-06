@extends('admin.index')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@section('content')
    @foreach($data as $post)
        @if(@$post != null)
            <div class="col-md-4" style="display: block;">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a href="https://www.facebook.com/{{ @$post->id }}" target="_blank" style="color: black">
                                <img src="{{ @$post->picture->url }}" height="25px" width="25px"
                                     style="border: 1px solid #000; border-radius: 20px">
                                {{ @$post->name }}
                            </a>
                        </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        @if(isset($post->posts))
                            @foreach(@$post->posts as $fb)
                                @if(isset($fb->attachments))
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner">
                                            @foreach(@$fb->attachments[0]->subattachments as $img)
                                                <div class="item @if($loop->first) active @endif">
                                                    <img src="{{ @$img->media->image->src }}" alt="Los Angeles"
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
                                @else
                                    <img src="{{ @$fb->full_picture }}" style="width: 100%">
                                @endif
                                <br/>
                                @php($date = explode('.',$fb->created_time->date))
                                <div style="color: gray; padding-top: 10px;">
                                    <i class="fa fa-clock-o"></i> {{ @$date[0] }}
                                </div>
                                <br/>
                                {{ @$fb->message }}
                                <br/>
                                <br/>
                                <div class="@if(app()->getLocale() == 'en') pull-right @else pull-left @endif">
                                    <a href="https://www.facebook.com/{{ @$fb->id }}"
                                       target="_blank">{{ __('admin.read_more') }} >></a>
                                </div>
                                <br/>
                                <hr style="border-color: lightgrey;">
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
