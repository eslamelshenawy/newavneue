<div class="box" id="prop'+x+'" style="margin-top: 20px">'+
    '<div class="box-header with-border">'+
        '<h3>property</h3>'+
        '<div class="box-tools pull-right">'+
            '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>'+
       ' </div>'+
   ' </div>'+
   ' <div class="box-body">'+
'<div class="form-group {{ $errors->has("unit_id") ? "has-error" : "" }}">' +
    '    {!! Form::label(trans("admin.unit_type")) !!}' +
    '    <br>' +
    '    <select class="select2 form-control" style="width: 100%" name="unit_id[]" data-placeholder="{{ trans("admin.unit_type") }}">' +
        '        <option></option>' +
        '        @foreach(\App\UnitType::get() as $row)' +
        '            <option value="{{ $row->id }}">{{ $row->en_name }}</option>' +
        '        @endforeach' +
        '    </select>' +
    '</div>' +
'<div class="box box-danger">' +
    '    <div class="box-header with-border">' +
        '        <h3 class="box-title">{{ trans("admin.price") }}</h3>' +
        '    </div>' +
    '    <div class="box-body">' +
        '' +
        '        <div class=" @if($errors->has("start_price")) has-error @endif">' +
            '            <label> {{ trans("admin.from") }}</label>' +
            '            <input type="number" name="start_price[]" class="form-control"  value="{{ old("start_price") }}" placeholder="{{ trans("admin.from") }}">' +
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
                '                <label> {{ trans("admin.from") }}</label>' +
                '                <input type="number" name="area_from[]" class="form-control"  value="{{ old("area_from") }}" placeholder="{{ trans("admin.from") }}">' +
                '            </div>' +
            '            <div class="col-xs-6 @if($errors->has("area_to")) has-error @endif">' +
                '                <label> {{ trans("admin.to") }}</label>' +
                '                <input type="number" name="area_to[]" class="form-control"  value="{{ old("area_to") }}" placeholder="{{ trans("admin.to") }}">' +
                '            </div>' +
            '        </div>' +
        '    </div>' +
    '    <!-- /.box-body -->' +
    '</div>' +

'<div class="form-group @if($errors->has("description")) has-error @endif">' +
    '    <label>{{ trans("admin.description") }}</label>' +
    '    <input multiple type="file" class="form-control" name="image[' + x + '][]">' +
    '</div>' +
'' +
'<div class="form-group @if($errors->has("description")) has-error @endif">' +
    '    <label>{{ trans("admin.description") }}</label>' +
    '    <textarea name="description" class="form-control" placeholder="{!! trans("admin.description") !!}" rows="6"></textarea>' +
    '</div>'+
    '<button type="button" class="bt btn-danger removeprop" num="'+x+'">Remove This Property</button>'+
    '</div>'+
    '</div>