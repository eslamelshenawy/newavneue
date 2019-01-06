@extends('admin.index')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Notification</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
         
            <div class="container">
                <ul >
                 
                    <hr style="margin: 0 !important;"/>
                    @if(@\App\ProjectRequest::where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->count() > 0)
                        <h6 style="padding: 0 20px; text-transform: uppercase; color: darkgrey;">{{ __('admin.project') }}</h6>
                        <li class="user-body" style="padding: 0 15px">
                            <div class="row">
                                <div class="col-md-12" style="padding: 0;position: relative;">
                                    <ul id="notifications" style="list-style: none">
                                        @foreach(@\App\ProjectRequest::get() as $row)
                                            <li style="border-bottom: solid 1px silver;">
                                                <a href="{{ url(adminPath().'/get_project') }}">
                                                    new project pushed to you "{{ $row->name }}"
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if(@\App\AdminNotification::count() > 0)
                        <h6 style="padding: 0 20px; text-transform: uppercase; color: darkgrey;">{{ __('admin.today') }}</h6>
                        <li class="user-body" style="padding: 0 15px">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul id="notifications">
                                        @foreach(@\App\AdminNotification::get() as $notify)
                                            @if($notify->type == 'switch')
                                                <li @if($notify->status==1) style="background-color: #86caff" @endif>
                                                    <a href="{{ url(adminPath().'/leads/'.$notify->type_id) }}">
                                                        {{ \App\User::find($notify->user_id)->name . ' ' . __('admin.has_switched') }}
                                                        @if($notify->type_id != 'bulk')
                                                            {{ @\App\Lead::find($notify->type_id)->prefix_name . ' ' . @\App\Lead::find($notify->type_id)->first_name }}
                                                        @else
                                                            {{ __('admin.bulk') }}
                                                        @endif
                                                        {{__('admin.to') . ' ' . @\App\User::find($notify->assigned_to)->name }}

                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if(@\App\AdminNotification::count() > 0)
                        <h6 style="padding: 0 20px; text-transform: uppercase; color: darkgrey;">{{ __('admin.earlier') }}</h6>
                        <li class="user-body" style="padding: 0 15px">
                            <div class="row">
                                <div class="col-md-12" style="padding: 0;position: relative;">
                                    <ul id="notifications" style="list-style: none">
                                        @foreach(@\App\AdminNotification::get() as $notify)
                                            @if($notify->type == 'switch')
                                                <li style="border-bottom: solid 1px silver;@if($notify->status==1) background-color: #86caff @endif">
                                                    <a href="{{ url(adminPath().'/leads/'.$notify->type_id) }}">
                                                        {{ \App\User::find($notify->user_id)->name . ' ' . __('admin.has_switched') }}
                                                        @if($notify->type_id != 'bulk')
                                                            {{ @\App\Lead::find($notify->type_id)->prefix_name . ' ' . @\App\Lead::find($notify->type_id)->first_name }}
                                                        @else
                                                            {{ __('admin.bulk') }}
                                                        @endif
                                                        {{__('admin.to') . ' ' . @\App\User::find($notify->assigned_to)->name }}

                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
