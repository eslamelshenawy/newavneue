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
            <form action="{{url(adminPath().'/mail_post')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-md-6">
                    <select class="select2 form-control" name="lead_id" data-placeholder="{{ __('admin.lead') }}">
                        <option></option>
                        @foreach(@\App\Lead::get() as $lead)
                            <option value="{{ $lead->email }}">{{ $lead->first_name . ' ' . $lead->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="subject" placeholder="{{ __('admin.subject') }}">
                </div>
                <br/>
                <br/>
                <div class="col-md-12">
                    <textarea name="message" id="editor"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.send') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.7.3/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.7.3/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor();
    </script>
@endsection
