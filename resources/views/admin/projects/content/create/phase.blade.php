<div class="box" id="phase'+x+'"  style="padding: 15px">'+
  '  <div class="box-header with-border">'+
       ' <h3 class="box-title">Phase</h3>'+
        '<div class="box-tools pull-right">'+
            '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>'+
           ' </button>'+
       ' </div>'+
   ' </div>'+
    '<div class="box-body">'+
'<div class="form-group @if($errors->has("en_name")) has-error @endif">' +
    '                    <label>{{ trans("admin.en_name") }}</label>' +
    '                    <input type="text" name="en_name" class="form-control" value="{{ old("en_name") }}" placeholder="{!! trans("admin.en_name") !!}">' +
    '                </div>' +
'                <div class="form-group @if($errors->has("ar_name")) has-error @endif">' +
    '                    <label>{{ trans("admin.ar_name") }}</label>' +
    '                    <input type="text" name="ar_name" class="form-control" value="{{ old("ar_name") }}" placeholder="{!! trans("admin.ar_name") !!}">' +
    '                </div>' +
'' +
'' +
'                <div class="form-group @if($errors->has("en_description")) has-error @endif">' +
    '                    <label>{{ trans("admin.en_description") }}</label>' +
    '                    <textarea name="en_description" class="form-control" placeholder="{!! trans("admin.en_description") !!}" rows="6"></textarea>' +
    '                </div>' +
'                <div class="form-group @if($errors->has("ar_description")) has-error @endif">' +
    '                    <label>{{ trans("admin.ar_description") }}</label>' +
    '                    <textarea name="ar_description" class="form-control" placeholder="{!! trans("admin.ar_description") !!}" rows="6"></textarea>' +
    '                </div>' +
'                <div class="form-group {{ $errors->has("facility") ? 'has-error' : '' }}">' +
    '                    {!! Form::label(trans("admin.facility")) !!}' +
    '                    <br>' +
    '                    <select class="select2 form-control" style="width: 100%" multiple name="facility[]" data-placeholder="{{ trans("admin.facilities") }}">' +
        '                        <option></option>' +
        '                        @foreach(App\Facility::get() as $facilty)' +
        '                            <option value="{{ $facilty->id }}">{{ $facilty->en_name }}</option>' +
        '                        @endforeach' +
        '                    </select>' +
    '                </div>' +
'                <div class="input-group image-preview">' +
    '                    <label>{{ trans("admin.logo") }}</label>' +
    '                    <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don\'t give a name === doesn\'t send on POST/GET -->' +
    '                    <span class="input-group-btn">' +
            '                    <!-- image-preview-clear button -->' +
            '                    <button type="button" class="btn btn-default image-preview-clear" style="display:none; margin-top: 25px;">' +
            '                        <span class="glyphicon glyphicon-remove"></span> {{ trans("admin.clear") }}' +
            '                    </button>' +
            '                        <!-- image-preview-input -->' +
            '                    <div class="btn btn-default image-preview-input" style="margin-top: 25px;">' +
            '                        <span class="glyphicon glyphicon-folder-open"></span>' +
            '                        <span class="image-preview-input-title">{{ trans("admin.browse") }}</span>' +
            '                        <input type="file" accept="image/png, image/jpeg, image/gif" name="logo"/> <!-- rename it -->' +
            '                    </div>' +
            '                </span>' +
    '                </div><!-- /input-group image-preview [TO HERE]-->' +
    '<div class="row">'+
'                <div id="body'+ y +'" class="col-md-10 col-md-push-1"></div>' +
        '</div>'+
'                <button type="button" class="btn btn-success addproperty" num="'+ y +'">Add Property</button>'+
    '</div>'+
    '<button type="button" class="bt btn-danger removephase" num="'+x+'">Remove This Phase</button>'+
    '</div>