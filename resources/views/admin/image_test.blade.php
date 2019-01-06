{!! Form::open(['url' => 'img_post','enctype'=>'multipart/form-data']) !!}
{!! Form::file('img') !!}
<button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
{!! Form::close() !!}