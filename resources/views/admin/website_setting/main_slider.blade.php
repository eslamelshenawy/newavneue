@extends('admin.index')

@section('content')
    <div class="form-group">
        <select class="select2" id="add_property" data-placeholder="{{ trans('admin.property') }}" style="width: 200px">
            <option></option>
            <optgroup label="{{ __('admin.property') }}">
            @foreach(@App\Property::all() as $prop)
                <option value="{{ $prop->id }}" type="Property" @if(@App\MainSlider::where([
                ['unit_id',$prop->id],['type','Property']]
                )->first()) disabled @endif>{{ $prop->{app()->getLocale().'_name'} }}</option>
            @endforeach
            </optgroup>
            <optgroup label="{{ __('admin.project') }}">
            @foreach(@App\Project::all() as $project)
                <option value="{{ $project->id }}" type="Project" @if(@App\MainSlider::where([
                        ['unit_id',$prop->id],['type','Property']]
                    )->first()) disabled @endif>{{ $project->{app()->getLocale().'_name'} }}</option>
            @endforeach
            </optgroup>
        </select>
    </div>
    <form action="{{ url('save_menu') }}" method="post" id="formData">
        {{ csrf_field() }}
        <ul id="sortable">
            @foreach(@App\MainSlider::all() as $slide)
                <li class="prop" id="prop{{ $slide->unit_id }}" property="{{ $slide->unit_id }}">

                    <span class="col-xs-12">{{ $slide->{app()->getLocale().'_name'} }}</span>

                    <div class="form-group col-md-4">
                        <span>{{ __('admin.image') }}</span>
                        <input type="file"  name="slider_image[]" accept="image/jpeg , image/png , image/jpg"0   class="image form-control col-md-6" count="{{ $slide->unit_id }}">
                        <input type="hidden" value="{{ $slide->image }}" name="old_image[]">
                    </div>
                    <div class="col-sm-12">
                        <img src="{{ url('uploads/'.$slide->image) }}" width="100px">
                    </div>
                    <div class="form-group  col-md-4">
                        <span>{{ __('admin.title') }}</span>
                        <input type="text" name="title[]" value="{{ $slide->en_title }}" required class="title form-control col-md-6" count="{{ $slide->unit_id }}">
                    </div>
                    <div class="form-group  col-md-4">
                        <span>{{ __('admin.ar_title') }}</span>
                        <input type="text" name="ar_title[]" value="{{ $slide->ar_title }}" required class="title form-control col-md-6" count="{{ $slide->unit_id }}">
                    </div>
                    <div class="form-group  col-md-4">
                        <span>{{ __('admin.sub_title') }}</span>
                        <input type="text" name="sub_title[]" value="{{ $slide->en_sub_title }}" required class="sub_title form-control col-md-6" count="{{ $slide->unit_id }}">
                    </div>
                    <div class="form-group  col-md-4">
                        <span>{{ __('admin.sub_title') }}</span>
                        <input type="text" name="ar_sub_title[]" value="{{ $slide->ar_sub_title }}" required class="sub_title form-control col-md-6" count="{{ $slide->id }}">
                    </div>
                    <div class="form-group  col-md-4">
                        <span>{{ __('admin.description') }}</span>
                        <textarea name="description[]" required class="description form-control col-md-6" count="{{ $slide->id }}">{{ $slide->en_description }}</textarea>
                    </div>
                    <div class="form-group  col-md-4">
                        <span>{{ __('admin.description') }}</span>
                        <textarea name="ar_description[]" required class="description form-control col-md-6" count="{{ $slide->id }}">{{ $slide->ar_description }}</textarea>
                    </div>
                    <i class="delete_prop glyphicon glyphicon-trash" count="{{ $slide->unit_id }}"></i>
                    <input type="hidden" value="{{ $slide->unit_id }}" name="unit_id[]">
                    <input type="hidden" value="{{ $slide->type }}" name="type[]">

                </li>
            @endforeach
        </ul>

        <div id="result"></div>
        <button type="submit" class="btn btn-flat" id="save">{{ trans('admin.save') }}</button>
    </form>
@endsection
@section('js')
    <script>
        $(function () {
            $("#sortable").sortable();
            $("#sortable").disableSelection();
        });
    </script>
    <script>
        $('#add_property').on('change', function () {
            var id = $(this).val();
            var type = $('option:selected').attr('type');
            $("#add_property").select2('destroy');
            $("#add_property option:selected").attr('disabled', 'disabled');
            $("#add_property").select2();

            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_property')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id,type: type, _token: _token},
                success: function (data) {
                    $('#sortable').append(data);
                }
            });

        });
        $(document).on('click', '.delete_prop', function () {
            var count = $(this).attr('count');
            $('#prop' + count).remove();
            $("#add_property").select2('destroy');
            $("#add_property option[value*='" + count + "']").prop('disabled', false);
            $("#add_property").select2();
        });

        $(document).on('submit','#formData', function () {

            var data = new FormData(this);
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/save_main_slider')}}",
                method: 'post',
                dataType: 'html',
                data: data,
                async: false,
                success: function (data) {
                    $('#result').html('');
                    $('#result').append(data);
                },
                cache: false,
                contentType: false,
                processData: false,
            });
            return false;
        })
    </script>
@endsection