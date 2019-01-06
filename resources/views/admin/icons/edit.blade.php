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
            <form action={{url(adminPath().'/icons/'.$icon->id)}} method="post" enctype="multipart/form-data"  >
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="input-group image-preview">
                    <label>{{ trans('admin.logo') }}</label>
                    <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
                    <span class="input-group-btn">
                    <!-- image-preview-clear button -->
                    <button type="button" class="btn btn-default image-preview-clear" style="display:none; margin-top: 25px;">
                        <span class="glyphicon glyphicon-remove"></span> {{ trans('admin.clear') }}
                    </button>
                        <!-- image-preview-input -->
                    <div class="btn btn-default image-preview-input" style="margin-top: 25px;">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span class="image-preview-input-title">{{ trans('admin.browse') }}</span>
                        <input type="file" id="imageInput" accept="image/png, image/jpeg, image/gif" name="icon"/> <!-- rename it -->
                    </div>
                </span>
                </div><!-- /input-group image-preview [TO HERE]-->
                <div class="popover fade bottom in" role="tooltip" id="oldImage" style="top: 480px; left: 532px; display: block;">
                    <div class="arrow" style="left: 50%;"></div>
                    <h3 class="popover-title">
                        <strong>Preview</strong>
                        <button type="button" id="close-preview" style="font-size: initial;" class="close pull-right">x</button>
                    </h3>
                    <div class="popover-content">
                        <img id="dynamic" src="{{ url('uploads/'.$icon->icon) }}" style="width: 250px; height: 200px;">
                    </div>
                </div>
                <br/>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
    </div>
@endsection
        @section('js')
            <script>
                $('#close-preview').on('click',function () {
                    $('#oldImage').hide(200);
                })

                $('#imageInput').on('change',function () {
                    $('#oldImage').hide(200);
                })
            </script>
@endsection
