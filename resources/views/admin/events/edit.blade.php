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
            {!! Form::open(['url' => adminPath().'/events/'.$event->id , 'method'=>'put','enctype'=>'multipart/form-data']) !!}
            <div class="form-group @if($errors->has('ar_title')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_title') }}</label>
                {!! Form::text('ar_title',$event->ar_title,['class' => 'form-control', 'placeholder' => trans('admin.ar_title')]) !!}
            </div>
            <div class="form-group @if($errors->has('en_title')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_title') }}</label>
                {!! Form::text('en_title',$event->en_title,['class' => 'form-control', 'placeholder' => trans('admin.en_title')]) !!}
            </div>

            <div class="form-group @if($errors->has('ar_description')) has-error @endif col-md-6">
                <label>{{ trans('admin.ar_description') }}</label>
                {!! Form::textarea('ar_description',$event->ar_description,['class' => 'form-control', 'placeholder' => trans('admin.ar_description'),'rows'=>5]) !!}
            </div>
            <div class="form-group @if($errors->has('en_description')) has-error @endif col-md-6">
                <label>{{ trans('admin.en_description') }}</label>
                {!! Form::textarea('en_description',$event->en_description,['class' => 'form-control', 'placeholder' => trans('admin.en_description'),'rows'=>5]) !!}
            </div>

            <div class="form-group @if($errors->has('image')) has-error @endif col-md-6">
                <label>{{ trans('admin.image') }}</label>
                {!! Form::file('image',['class' => 'form-control', 'placeholder' => trans('admin.image')]) !!}
            </div>
            <div class="form-group @if($errors->has('other_images')) has-error @endif col-md-6">
                <label>{{ trans('admin.other_images') }}</label>
                {!! Form::file('other_images[]',['class' => 'form-control', 'placeholder' => trans('admin.other_images'),'multiple'=>'']) !!}
            </div>

            <div class="form-group @if($errors->has('date')) has-error @endif col-md-6">
                <label>{{ trans('admin.date') }}</label>
                {!! Form::text('date',date('Y-m-d',$event->date),['class' => 'form-control datepicker', 'placeholder' => trans('admin.date'),'readonly']) !!}
            </div>
            <div class="form-group @if($errors->has('other_images')) has-error @endif col-md-6">
                <br/>
                <input type="hidden" name="event" value="0">
                <input type="checkbox" name="event" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                       @if($event->event) checked @endif
                       data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.event') }}" value="1">
                <input type="hidden" name="launch" value="0">
                <input type="checkbox" name="launch" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                       @if($event->launch) checked @endif
                       data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.launch') }}" value="1">
                <input type="hidden" name="news" value="0">
                <input type="checkbox" name="news" class="switch-box" data-on-text="{{ __('admin.yes') }}"
                       @if($event->news) checked @endif
                       data-off-text="{{ __('admin.no') }}" data-label-text="{{ __('admin.news') }}" value="1">
            </div>

            <div class="form-group col-md-6">
                {!! Form::label(trans('admin.meta_keywords')) !!}
                <input type="text" value="{{ $event->meta_keywords }}" name="meta_keywords" class="form-control" data-role="tagsinput" style="width: 100%">
            </div>
            <div class="form-group col-md-6">
                {!! Form::label(trans('admin.meta_description')) !!}
                <textarea class="form-control" name="meta_description" rows="1">{{ $event->meta_description }}</textarea>
            </div>

            <div class="col-md-12">
                <label>{{ __('admin.image') }}</label>
                <br/>
                <img src="{{ url('uploads/'.$event->image) }}" width="75">
            </div>
            <div class="col-md-12">
                <label>{{ __('admin.other_images') }}</label>
                <br/>
                @foreach(@\App\EventImage::where('event_id',$event->id)->get() as $img)
                    <div class="col-md-1 text-center" id="img{{ $img->id }}">
                        <img src="{{ url('uploads/'.$img->image) }}" width="50">
                        <br/>
                        <br/>
                        <i class="fa fa-trash removeImage" count="{{ $img->id }}" style="cursor: pointer"></i>
                    </div>
                @endforeach
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '.removeImage', function () {
            var id = $(this).attr('count');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/delete_event_image')}}",
                method: 'post',
                dataType: 'json',
                data: {id: id, _token: _token},
                beforeSend: function () {
                    $(this).addClass('fa-spin');
                },
                success: function (data) {
                    $(this).removeClass('fa-spin');
                    $('#img'+id).remove();
                },
                error: function () {
                    $(this).removeClass('fa-spin');
                    alert('{{ __('admin.error') }}')
                }
            });
        })
    </script>
@endsection