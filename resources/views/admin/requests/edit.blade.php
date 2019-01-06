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
            <form action={{url(adminPath().'/requests/'.$data->id)}} method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group @if($errors->has('lead')) has-error @endif">
                    <label>{{ trans('admin.lead') }}</label>
                    <select name="lead" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.lead') }}">
                        <option></option>
                        @foreach(@App\Lead::getAgentLeads() as $lead)
                            <option value="{{ $lead->id }}"
                                    @if($data->lead_id == $lead->id) selected @endif>
                                {{ $lead->first_name . ' ' . $lead->last_name }}
                                -
                                @if($lead->agent_id == auth()->id())
                                    {{ __('admin.my_lead') }}
                                @else
                                    {{ __('admin.team_lead', ['agent' => @$lead->agent->name]) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group {{ $errors->has('unit_type') ? 'has-error' : '' }} col-md-3">
                    {!! Form::label(trans('admin.buyer_seller')) !!}
                    <select class="select2 form-control" id="unit_type" name="buyer_seller" style="width: 100%"
                            data-placeholder="{{ trans('admin.type') }}">
                        <option></option>
                        <option value="buyer">{{ trans('admin.buyer') }}</option>
                        <option value="seller">{{ trans('admin.seller') }}</option>
                        
                    </select>
                </div>
                <div class="form-group @if($errors->has('unit_type')) has-error @endif">
                    <label class="col-xs-12">{{ trans('admin.unit_type') }}</label>
                    <select name="unit_type" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.unit_type') }}">
                        <option></option>
                        @foreach(App\UnitType::get() as $lead)
                            <option value="{{ $lead->id }}" @if($lead->id == $data->unit_type_id) selected @endif>
                                {{ $lead->{app()->getLocale().'_name'} }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.price') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6 @if($errors->has('price_from')) has-error @endif">
                                <label> {{ trans('admin.from') }}</label>
                                <input type="number" name="price_from" class="form-control"
                                       value="{{ $data->price_from }}" placeholder="{{ trans('admin.from') }}">
                            </div>
                            <div class="col-xs-6 @if($errors->has('price_to')) has-error @endif">
                                <label> {{ trans('admin.to') }}</label>
                                <input type="number" name="price_to" class="form-control" value="{{ $data->price_to }}"
                                       placeholder="{{ trans('admin.to') }}">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="form-group">
                    <div id="reportrange1" class="reportrange1 form-control">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span></span>
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                    </div>
                </div>
                <div class="form-group @if($errors->has('description')) has-error @endif">
                    <label> {{ trans('admin.description') }}</label>
                    <textarea name="description" class="form-control"
                              value={{ $data->description }} placeholder="{!! trans('admin.description') !!}"
                              rows="6">{{ $data->description }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var start = new Date("{{ Date('Y-m-d',$data->date_from)}}");
        // console.log(start);
        var end = new Date("{{ Date('Y-m-d',$data->date_to)}}");

        start = moment(start);
        end = moment(end);

        function cb(start, end) {
            $('#reportrange1 span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            $("#start_date").val(start.toString('Y-m-d'));
            $("#end_date").val(end.toString('Y-m-d'));
        }

        $('.reportrange1').daterangepicker({

            startDate: start,
            endDate: end,
            locale: {
                format: 'DD/MM/YYYY'
            },

        }, cb);

        cb(start, end);
    </script>
@endsection
