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
            <strong>{{ trans('admin.id') }} : </strong>{{ $property->id }}
            <br><hr>
            <strong>{{ trans('admin.code') }} : </strong>{{ $property->code }}
            <br><hr>
            <strong>{{ trans('admin.name') }} : </strong>{{ $property->{app()->getLocale().'_name'} }}
            <br><hr>
            <strong>{{ trans('admin.type') }} : </strong>{{ App\UnitType::find($property->unit_id)->en_name }}
            <br><hr>
            <strong>{{ trans('admin.en_description') }} : </strong>{{ $property->en_description }}
            <br><hr>
            <strong>{{ trans('admin.ar_description') }} : </strong>{{ $property->ar_description }}
            <br><hr>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.meter_price').' : '.$property->meter_price.'  ' }} {{ '  '.trans('admin.current_price').' : '.$property->start_price }} </h3>
                </div>
                   <div class="box-body">
                       <h4>{{ trans('admin.history') }}</h4>
                       <table class="table table-striped">
                           <thead>
                           <tr>
                               <th>{{ trans('admin.price') }}</th>
                               <th>{{ trans('admin.date') }}</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach($prices as $price)
                               <tr>
                                   <td>{{ $price->price }}</td>
                                   <td>{{ $price->created_at }}</td>
                               </tr>
                           @endforeach
                           </tbody>
                       </table>
                   </div>
                </div>
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.area') }} </h3>
                </div>
                <div class="box-body">
                    <strong>{{ trans('admin.clear1') }} : </strong>{{ $property->area_from }}
                    <br><hr>
                    <strong>{{ trans('admin.garden') }} : </strong>{{ $property->area_to }}
                    <br><hr>
                </div>
            </div>

            <div class="gallery">
                @foreach($images as $image)
                <figure>
                    <img src="{{ url('uploads/'.$image->images) }}" alt="" />
                    <figcaption>Daytona Beach <small>United States</small></figcaption>
                </figure>
                    @endforeach
                    @foreach($layout as $image)
                        <figure>
                            <img src="{{ url('uploads/'.$image->image) }}" alt="" />
                            <figcaption>Daytona Beach <small>United States</small></figcaption>
                        </figure>
                    @endforeach
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display:none;">
                <symbol id="close" viewBox="0 0 18 18">
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M9,0.493C4.302,0.493,0.493,4.302,0.493,9S4.302,17.507,9,17.507
			S17.507,13.698,17.507,9S13.698,0.493,9,0.493z M12.491,11.491c0.292,0.296,0.292,0.773,0,1.068c-0.293,0.295-0.767,0.295-1.059,0
			l-2.435-2.457L6.564,12.56c-0.292,0.295-0.766,0.295-1.058,0c-0.292-0.295-0.292-0.772,0-1.068L7.94,9.035L5.435,6.507
			c-0.292-0.295-0.292-0.773,0-1.068c0.293-0.295,0.766-0.295,1.059,0l2.504,2.528l2.505-2.528c0.292-0.295,0.767-0.295,1.059,0
			s0.292,0.773,0,1.068l-2.505,2.528L12.491,11.491z"/>
                </symbol>
            </svg>
        </div>
    </div>
        </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        popup = {
            init: function(){
                $('figure').click(function(){
                    popup.open($(this));
                });

                $(document).on('click', '.popup img', function(){
                    return false;
                }).on('click', '.popup', function(){
                    popup.close();
                })
            },
            open: function($figure) {
                $('.gallery').addClass('pop');
                $popup = $('<div class="popup" />').appendTo($('body'));
                $fig = $figure.clone().appendTo($('.popup'));
                $bg = $('<div class="bg" />').appendTo($('.popup'));
                $close = $('<div class="close"><svg><use xlink:href="#close"></use></svg></div>').appendTo($fig);
                $shadow = $('<div class="shadow" />').appendTo($fig);
                src = $('img', $fig).attr('src');
                $shadow.css({backgroundImage: 'url(' + src + ')'});
                $bg.css({backgroundImage: 'url(' + src + ')'});
                setTimeout(function(){
                    $('.popup').addClass('pop');
                }, 10);
            },
            close: function(){
                $('.gallery, .popup').removeClass('pop');
                setTimeout(function(){
                    $('.popup').remove()
                }, 100);
            }
        }

        popup.init()

    </script>
    @endsection