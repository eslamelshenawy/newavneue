<div class="form-group">
    {!! Form::label(trans('admin.out_sub_cat')) !!}
    <select class="form-control select2" style="width: 100%;" name="sub_cat_id" data-placeholder="{{ __('admin.out_sub_cat') }}">
        <option></option>
        @foreach($subs as $sub)
            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
        @endforeach
    </select>
</div>