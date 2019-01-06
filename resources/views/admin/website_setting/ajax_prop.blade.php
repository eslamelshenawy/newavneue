<li class="prop" id="prop{{ $slide->id }}" property="{{ $slide->id }}">

    <span class="col-xs-12">{{ $slide->{app()->getLocale().'_name'} }}</span>
    <div class="form-group col-md-4">
    <span>{{ __('admin.image') }}</span>
    <input type="file" name="slider_image[]" accept="image/jpeg , image/png , image/jpg" required  class="image form-control col-md-6" count="{{ $slide->id }}">
    </div>
    <div class="form-group  col-md-4">
    <span>{{ __('admin.en_title') }}</span>
    <input type="text" name="title[]" required class="title form-control col-md-6" count="{{ $slide->id }}">
    </div>
    <div class="form-group  col-md-4">
        <span>{{ __('admin.ar_title') }}</span>
        <input type="text" name="ar_title[]" required class="title form-control col-md-6" count="{{ $slide->id }}">
    </div>
    <div class="form-group  col-md-4">
    <span>{{ __('admin.sub_title') }}</span>
    <input type="text" name="sub_title[]" required class="sub_title form-control col-md-6" count="{{ $slide->id }}">
    </div>
    <div class="form-group  col-md-4">
        <span>{{ __('admin.ar_sub_title') }}</span>
        <input type="text" name="ar_sub_title[]" required class="sub_title form-control col-md-6" count="{{ $slide->id }}">
    </div>
    <div class="form-group  col-md-4">
    <span>{{ __('admin.description') }}</span>
    <textarea name="description[]" required class="description form-control col-md-6" count="{{ $slide->id }}"></textarea>
    </div>
    <div class="form-group  col-md-4">
        <span>{{ __('admin.ar_description') }}</span>
        <textarea name="ar_description[]" required class="description form-control col-md-6" count="{{ $slide->id }}"></textarea>
    </div>
        <i class="delete_prop glyphicon glyphicon-trash" count="{{ $slide->id }}"></i>
    <input type="hidden" value="{{ $slide->id }}" name="unit_id[]">
    <input type="hidden" value="{{ $type }}" name="type[]">

</li>