<div class="box" id="prop'+x+'" style="margin-top: 20px">'+
    '<div class="box-header with-border">'+
        '<h3>{{ trans('admin.property') }}</h3>'+
        '<div class="box-tools pull-right">'+
            '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>'+
            ' </div>'+
        ' </div>'+
    '<div class="box-body">'+
    '<div class="form-group">' +
        '            <label> {{ trans("admin.code") }}</label>' +
        '            <input type="text" name="code[]" required class="form-control" placeholder="{{ trans("admin.code") }}">' +
        '    </div>' +
                '<div class="form-group">' +
        '            <label> {{ trans("admin.en_name") }}</label>' +
        '            <input type="text" name="en_name[]" required class="form-control" placeholder="{{ trans("admin.en_name") }}">' +
            '    </div>' +

            '<div class="form-group">' +
                '            <label> {{ trans("admin.ar_name") }}</label>' +
                '            <input type="text" required name="ar_name[]" class="form-control" placeholder="{{ trans("admin.ar_name") }}">' +
                '    </div>' +
    '<div class="row">'+
        '<div class="form-group col-md-6 {{ $errors->has("unit_id") ? "has-error" : "" }}">' +
            '    {!! Form::label(trans("admin.usage")) !!}' +
            '    <br>' +
            ' <select class="select2 form-control __unit" required name="type[]" style="width: 100%"  id="unit_' + x +'" data-placeholder="{{ trans("admin.unit_type") }}">' +
                '<option></option>'+
                '<option value="personal">{{ trans('admin.personal') }}</option>'+
                '<option value="commercial">{{ trans('admin.commercial') }}</option>'+
             '</select>' +
            '</div>' +
        '<div class="form-group col-md-6 {{ $errors->has("unit_id") ? "has-error" : "" }}">' +
            '    {!! Form::label(trans("admin.unit_type")) !!}' +
            '    <br>' +
            '    <select class="select2 form-control" required style="width: 100%" id="unit_' + x +'_type" name="unit_id[]" data-placeholder="{{ trans("admin.unit_type") }}">' +

                '    </select>' +
            '</div>' +
        '</div>' +
        '<div class="box box-danger">' +
            '    <div class="box-header with-border">' +
                '        <h3 class="box-title">{{ trans("admin.price") }}</h3>' +
                '    </div>' +
            '    <div class="box-body">' +
                '' +
                '        <div class=" col-md-6">' +
                    '            <input type="number" required min="0" name="start_price[]" class="form-control"   placeholder="{{ trans("admin.unit_price") }}">' +
                    '        </div>' +
                '        <div class=" col-md-6">' +
                    '            <input type="number" required min="0" name="meter_price[]" class="form-control"   placeholder="{{ trans("admin.meter_price") }}">' +
                    '        </div>' +
                '    </div>' +
            '    <!-- /.box-body -->' +
            '</div>' +
        '<div class="box box-danger">' +
            '    <div class="box-header with-border">' +
                '        <h3 class="box-title">{{ trans("admin.area") }}</h3>' +
                '    </div>' +
            '    <div class="box-body">' +
                '        <div class="row">' +
                    '            <div class="col-xs-6 @if($errors->has("area_from")) has-error @endif">' +
                        '                <label> {{ trans("admin.clear1") }}</label>' +
                        '                <input type="number" required min="0" name="area_from[]" class="form-control"  value="{{ old("area_from") }}" placeholder="{{ trans("admin.clear1") }}">' +
                        '            </div>' +
                    '            <div class="col-xs-6 @if($errors->has("area_to")) has-error @endif">' +
                        '                <label> {{ trans("admin.garden") }}</label>' +
                        '                <input type="number" required min="0" name="area_to[]" class="form-control"  value="{{ old("area_to") }}" placeholder="{{ trans("admin.garden") }}">' +
                        '            </div>' +
                    '        </div>' +
                '    </div>' +
            '    <!-- /.box-body -->' +
            '</div>' +
        '<div class="form-group col-md-6">'+
            '{!! Form::label(trans('admin.meta_keywords')) !!}'+
            '<input type="text" name="meta_keywords" class="form-control tagsinput" data-role="tagsinput" style="width: 100%">'+
        '</div>'+
        '<div class="form-group col-md-6">'+
            '{!! Form::label(trans('admin.meta_description')) !!}'+
            '<textarea class="form-control" name="meta_description" rows="1"></textarea>'+
        '</div>'+
        '<div class="form-group @if($errors->has("layout")) has-error @endif">' +
            '    <label>{{ trans("admin.layout") }}</label>' +
            '    <input multiple type="file" accept="image/png, image/jpeg, image/gif" class="form-control" name="layout[' + x + '][]">' +
            '</div>' +
        '<div class="form-group @if($errors->has("images")) has-error @endif">' +
            '    <label>{{ trans("admin.image") }}</label>' +
            '    <input multiple type="file" accept="image/png, image/jpeg, image/gif" class="form-control" name="images[' + x + '][]">' +
            '</div>' +
        '' +
       ' <div class="form-group @if($errors->has('down_payment')) has-error @endif">'+
           ' <label>{{ trans('admin.cover') }}</label>'+
           ' <input type="file" accept="image/png, image/jpeg, image/gif" name="main[]">'+
       ' </div>'+
        '<div class="form-group @if($errors->has("en__description")) has-error @endif">' +
            '    <label>{{ trans("admin.en_description") }}</label>' +
            '    <textarea name="en_description[]" class="form-control" placeholder="{!! trans("admin.ar_description") !!}" rows="6"></textarea>' +
            '</div>'+
    '<div class="form-group @if($errors->has("ar_description")) has-error @endif">' +
        '    <label>{{ trans("admin.ar_description") }}</label>' +
        '    <textarea name="ar_description[]" class="form-control" placeholder="{!! trans("admin.ar_description") !!}" rows="6"></textarea>' +
        '</div>'+
        '<button type="button" class="bt btn-danger removeprop  col-md-2 col-md-push-10" num="'+x+'">{{ trans('admin.remove').' '.trans('admin.property') }}</button>'+
        '</div>'+
'</div>