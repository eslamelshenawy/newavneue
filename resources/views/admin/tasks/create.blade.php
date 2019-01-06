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
            <form action="{{ url(adminPath().'/tasks') }}" method="post" enctype="m">
                {{ csrf_field() }}
                <div class="form-group @if($errors->has('agent_id')) has-error @endif">
                    <label>{{ trans('admin.agent') }}</label>
                    <select name="agent_id" class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.agent') }}">
                        <option></option>
                        @foreach(App\User::get() as $lead)
                            <option value="{{ $lead->id }}">
                                {{ $lead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if($errors->has('leads')) has-error @endif">
                    <label>{{ trans('admin.leads') }}</label>
                    <select class="form-control select2" name="leads"
                            data-placeholder="{{ trans('admin.leads') }}" style="width: 100%">
                        <option></option>
                        @foreach(@App\Lead::getAgentLeads() as $lead)
                            <option value="{{ $lead->id }}"
                                    @if(old('lead_id') == $lead->id) selected @endif>
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
                <div class="form-group @if($errors->has('due_date')) has-error @endif">
                    <label>{{ trans('admin.date') }}</label>

                    <div class="input-group date">
                        <input type="text" readonly name="due_date"
                               @if(request()->has('date')) value="{{ request()->date }}"
                               @else value="{{ old('due_date') }}" @endif  class="form-control pull-right datepicker"
                               placeholder="{{ trans('admin.due_date') }}">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
                <div class="form-group @if($errors->has('task_type')) has-error @endif">
                    <label>{{ trans('admin.task_type') }}</label>
                    <select name="task_type" multiple class="form-control select2" style="width: 100%"
                            data-placeholder="{{ trans('admin.task_type') }}">
                        <option></option>
                        <option value="call">{{ trans('admin.call') }}</option>
                        <option value="meeting">{{ trans('admin.meeting') }}</option>
                        <option value="others">{{ trans('admin.others') }}</option>
                    </select>
                </div>
                <div class="form-group @if($errors->has('description')) has-error @endif">
                    <label> {{ trans('admin.description') }}</label>
                    <textarea name="description" class="form-control" placeholder="{!! trans('admin.description') !!}"
                              rows="6">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </form>
        </div>
    </div>
@endsection

