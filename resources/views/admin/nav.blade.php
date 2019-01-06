<style>
    .dropdown-menu .sub-menu {
        left: 100%;
        position: absolute;
        top: 0;
        visibility: hidden;
        margin-top: -1px;
    }

    .dropdown-menu li:hover .sub-menu {
        visibility: visible;
    }

    .dropdown-menu .dropdown-menu {
        display: none !important;
        margin-left: 165px;
        background: rgba(0, 0, 0, 0.8);
        border: 0;
        margin-top: -32px !important;
    }

    .dropdown-menu:hover .dropdown-menu {
        display: block !important;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .nav-tabs .dropdown-menu,
    .nav-pills .dropdown-menu,
    .navbar .dropdown-menu {
        margin-top: 0;
    }

    .navbar .sub-menu:before {
        border-bottom: 7px solid transparent;
        border-left: none;
        border-right: 7px solid rgba(0, 0, 0, 0.2);
        border-top: 7px solid transparent;
        left: -7px;
        top: 10px;
    }

    .navbar .sub-menu:after {
        border-top: 6px solid transparent;
        border-left: none;
        border-right: 6px solid #fff;
        border-bottom: 6px solid transparent;
        left: 10px;
        top: 11px;
        left: -6px;
    }

    .user-menu .dropdown-menu {
        background: rgba(255, 255, 255, 1) !important;
    }
</style>
<header class="main-header">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header col-md-2">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ url(adminPath()) }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>H</b>UB</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">
            <img src="{{ url('uploads/'.getInfo()->logo) }}" style="max-height: 50px; padding-bottom: 7px">
        </span>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse col-md-5" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    
                    @if(checkRole('add_leads')
                    or checkRole('switch_leads')
                    or checkRole('edit_leads')
                    or checkRole('show_all_leads')
                    or checkRole('send_cil')
                    or checkRole('calls')
                    or checkRole('meetings')
                    or checkRole('requests')
                    or auth()->user()->type == 'admin')
                        <li class="dropdown">
                            <a @if(Request::segment(2) == 'leads' OR Request::segment(2) =='requests' OR Request::segment(2) =='calls' OR Request::segment(2) =='meetings' ) class="gold-background"
                               @endif
                               href="# " target="_self" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true"
                               aria-expanded="false">{{ trans('admin.lead') }} </a>
                            <ul class="dropdown-menu">
                                @if(checkRole('add_leads')
                                or checkRole('switch_leads')
                                or checkRole('edit_leads')
                                or checkRole('show_all_leads')
                                or checkRole('send_cil')
                                or auth()->user()->type == 'admin')
                                    <li>
                                        <a @if(Request::segment(2) == 'leads') class="gold-background" @endif
                                        href="{{ url(adminPath().'/leads') }}"><i
                                                    class="fa fa-users"></i> {{ trans('admin.all_leads') }}

                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('calls') or auth()->user()->type == 'admin')
                                    <li class="treeview @if(Request::segment(2) == 'calls') menu-open active @endif">
                                        <a @if(Request::segment(2) == 'calls')  class="gold-background"
                                           @endif href="{{ url(adminPath().'/calls') }}">
                                            <i class="fa fa-phone"></i> <span>{{ trans('admin.calls') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('meetings') or auth()->user()->type == 'admin')
                                    <li>
                                        <a @if(Request::segment(2) == 'meetings') class="gold-background" @endif
                                        href="{{ url(adminPath().'/meetings') }}">
                                            <i class="fa fa-handshake-o"></i> <span>{{ trans('admin.meetings') }}</span>

                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('requests') or auth()->user()->type == 'admin')
                                    <li>
                                        <a @if(Request::segment(2) == 'requests') class="gold-background"
                                           @endif href="{{ url(adminPath().'/requests') }}">
                                            <i class="fa fa-edit"></i> <span>{{ trans('admin.requests') }}</span>


                                        </a>
                                    </li>
                                @endif
                            </ul>

                        </li>
                    @endif
                    @if(checkRole('add_developers')
                    or checkRole('edit_developers')
                    or checkRole('delete_developers')
                    or checkRole('show_developers')

                    or checkRole('add_projects')
                    or checkRole('edit_projects')
                    or checkRole('delete_projects')
                    or checkRole('show_projects')
                    
                    or checkRole('add_phases')
                    or checkRole('edit_phases')
                    or checkRole('delete_phases')
                    or checkRole('show_phases')
                    
                    or checkRole('add_properties')
                    or checkRole('edit_properties')
                    or checkRole('delete_properties')
                    or checkRole('show_properties')
                    
                    or checkRole('add_resale_units')
                    or checkRole('edit_resale_units')
                    or checkRole('delete_resale_units')
                    or checkRole('show_resale_units')
                    
                    or checkRole('add_rental_units')
                    or checkRole('edit_rental_units')
                    or checkRole('delete_rental_units')
                    or checkRole('show_rental_units')
                    
                    or checkRole('add_lands')
                    or checkRole('edit_lands')
                    or checkRole('delete_lands')
                    or checkRole('show_lands')
                    or auth()->user()->type == 'admin')
                        <li class="dropdown">
                            <a @if(Request::segment(2) == 'inventory' or Request::segment(2) == 'developers' or Request::segment(2) == 'projects' or Request::segment(2) == 'resale_units' or Request::segment(2) == 'rental_units' or Request::segment(2) == 'lands') class="gold-background"
                               @endif href="{{ url(adminPath().'/inventory') }}" class="dropdown-toggle"
                               data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span>{{ trans('admin.inventory') }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @if(checkRole('add_developers') or checkRole('edit_developers') or checkRole('delete_developers') or checkRole('show_developers') or auth()->user()->type == 'admin')
                                    <li>
                                        <a @if(Request::segment(2) == 'developers') class="gold-background"
                                           @endif href="{{ url(adminPath().'/developers') }}">
                                            <i class="fa fa-dashboard"></i> <span>{{ trans('admin.developers') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('add_lands') or checkRole('edit_lands') or checkRole('delete_lands') or checkRole('show_lands') or auth()->user()->type == 'admin')
                                    <li>
                                        <a @if(Request::segment(2) == 'lands') class="gold-background"
                                           @endif href="{{ url(adminPath().'/lands') }}">
                                            <i class="fa fa-crop"></i> <span>{{ trans('admin.lands') }}</span>

                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('add_projects') or checkRole('edit_projects') or checkRole('delete_projects') or checkRole('show_projects') or auth()->user()->type == 'admin')
                                    <li class="treeview @if(Request::segment(2) == 'projects') menu-open active @endif">
                                        <a @if(Request::segment(2) == 'projects') class="gold-background"
                                           @endif href="{{ url(adminPath().'/projects') }}">
                                            <i class="fa fa-dashboard"></i> <span>{{ trans('admin.projects') }}</span>

                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('add_resale_units') or checkRole('edit_resale_units') or checkRole('delete_resale_units') or checkRole('show_resale_units') or auth()->user()->type == 'admin')
                                    <li class="treeview @if(Request::segment(2) == 'resale_units') menu-open active @endif">
                                        <a @if(Request::segment(2) == 'resale_units') class="gold-background"
                                           @endif href="{{ url(adminPath().'/resale_units') }}">
                                            <i class="fa fa-home"></i> <span>{{ trans('admin.resale_units') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if(checkRole('add_rental_units') or checkRole('edit_rental_units') or checkRole('delete_rental_units') or checkRole('show_rental_units') or auth()->user()->type == 'admin')
                                    <li class="treeview @if(Request::segment(2) == 'rental_units') menu-open active @endif">
                                        <a @if(Request::segment(2) == 'rental_units') class="gold-background"
                                           @endif href="{{ url(adminPath().'/rental_units') }}">
                                            <i class="fa fa-home"></i> <span>{{ trans('admin.rental_units') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(checkRole('marketing') or auth()->user()->type == 'admin')
                        <li class="dropdown">
                            <a @if(Request::segment(2) == 'marketing') class="gold-background" @endif href="#"
                               class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                               aria-expanded="false">
                                <span>{{ trans('admin.marketing') }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown">
                                    <a @if(Request::segment(2) == 'campaigns') class="gold-background sub"
                                       @endif href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-dashboard"></i> <span>{{ trans('admin.campaigns') }} &nbsp &nbsp &nbsp ></span>

                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a @if(Request::segment(2) == 'campaign_types' and Request::segment(3) == '') class="gold-background"
                                               @endif href="{{ url(adminPath().'/campaign_types') }}"><i
                                                        class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.types') }}
                                            </a></li>
                                        <li>
                                            <a @if(Request::segment(2) == 'campaign_types' and Request::segment(3) == 'create') class="gold-background"
                                               @endif href="{{ url(adminPath().'/campaign_types/create') }}"><i
                                                        class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.type') }}
                                            </a></li>
                                        <li>
                                            <a @if(Request::segment(2) == 'campaigns' and Request::segment(3) == '') class="gold-background"
                                               @endif href="{{ url(adminPath().'/campaigns') }}"><i
                                                        class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.campaigns') }}
                                            </a></li>
                                        <li>
                                            <a @if(Request::segment(2) == 'campaigns' and Request::segment(3) == 'create') class="gold-background"
                                               @endif href="{{ url(adminPath().'/campaigns/create') }}"><i
                                                        class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.campaigns') }}
                                            </a>
                                        </li>
                                        <li>
                                            <form id="ddssa" action="{{ url(adminPath().'/export_xls') }}" method="post"
                                                  enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <a id="ddss" class="gold-background" href="#" type="submit">
                                                    <i class="fa fa-file"></i>
                                                    <span> {{ trans('admin.export_excel_file') }}</span>
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a @if(Request::segment(2) == 'developers_facebook') class="gold-background"
                                       @endif href="{{ url(adminPath().'/developers_facebook') }}">
                                        <i class="fa fa-facebook"></i> <span> {{ trans('admin.developers') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a @if(Request::segment(2) == 'projects_facebook') class="gold-background"
                                       @endif href="{{ url(adminPath().'/projects_facebook') }}">
                                        <i class="fa fa-facebook"></i> <span> {{ trans('admin.projects') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a @if(Request::segment(2) == 'competitors_facebook') class="gold-background"
                                       @endif href="{{ url(adminPath().'/competitors_facebook') }}">
                                        <i class="fa fa-facebook"></i> <span> {{ trans('admin.competitors') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a @if(Request::segment(2) == 'forms') class="gold-background"
                                       @endif href="{{ url(adminPath().'/forms') }}"><i
                                                class="fa fa-edit"></i>{{ trans('admin.forms') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a @if(Request::segment(2) == 'proposals') class="gold-background"
                               @endif href="{{ url(adminPath().'/proposals') }}">
                                <span>{{ trans('admin.proposals') }}</span>

                            </a>
                        </li>
                    @endif
                    @if(checkRole('deals') or auth()->user()->type == 'admin')
                        <li>
                            <a @if(Request::segment(2) == 'deals') class="gold-background"
                               @endif href="{{ url(adminPath().'/deals') }}">
                                <span>{{ trans('admin.closed_deals') }}</span>

                            </a>
                        </li>
                    @endif
                    @if(checkRole('finance') or auth()->user()->type == 'admin')
                        <li>
                            <a @if(Request::segment(2) == 'finances') class="gold-background"
                               @endif href="{{ url(adminPath().'/finances') }}">
                                <span>{{ trans('admin.finances') }}</span>

                            </a>{{--
                <ul class="treeview-menu" @if(Request::segment(2) == 'finances')  @endif>
            <li>
                <a @if(Request::segment(2) == 'finances' and Request::segment(3) == 'bank') class="gold-background"
                @endif href="{{ url(adminPath().'/bank') }}"><i
                    class="fa fa-circle-o"></i>{{ trans('admin.settings') }}</a></li>
            </ul> --}}
                        </li>
                    @endif
                   
                    @if(auth()->user()->type == 'admin' or auth()->user()->hr == 1)
                    <li class="dropdown">
                        <a @if(Request::segment(2) == 'Hr') class="gold-background"
                           @endif href="{{ url(adminPath().'/emp-dashboard') }}">
                            <span>{{ trans('admin.hr') }}</span>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="gold-background" href="{{ url(adminPath().'/job_categories') }}">{{ trans('admin.job_categories') }}</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/job_titles') }}">{{ trans('admin.job_titles') }}</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/vacancies') }}">{{ trans('admin.vacancies') }}</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/applications') }}">{{ trans('admin.applications') }}</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/employees') }}">{{ trans('admin.employee') }}</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/vacations') }}">{{ trans('admin.vacations') }}</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/salaries') }}">salaries</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/salaries-details') }}">salaries-details</a></li>
                            <li><a class="gold-background" href="{{ url(adminPath().'/rules-procedure') }}">{{ trans('admin.rules_of_procedure') }}</a></li>

                        </ul>
                    </li>
                    @endif
                     @if(checkRole('reports') or auth()->user()->type == 'admin')
                        <li>
                            <a @if(Request::segment(2) == 'reports') class="gold-background"
                               @endif href="{{ url(adminPath().'/reports') }}">
                                <span>{{ trans('admin.reports') }}</span>

                            </a>
                        </li>
                    @endif
                </ul>

                </ul>

            </div><!-- /.navbar-collapse -->
            <div class="col-md-4">
            <ul class="nav navbar-nav navbar-right">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu ">


                </li>

                @php $color = '#000'; @endphp
               <li class="dropdown user user-menu notifaction">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell"></i>
                            <span class="label label-danger" id="countNotifications">
                            {{ @\App\AdminNotification::where('assigned_to',auth()->user()->id)->where('status',0)->count() }}
                        </span>
                        </a>
                        <ul class="dropdown-menu">
                            <h5 style="padding: 0 20px; font-weight: bold; text-transform: uppercase">{{ __('admin.today') }}</h5>
                            <hr style="margin: 0 !important;"/>
                            @if(auth()->user()->type == 'admin')
                                @if(@\App\ProjectRequest::where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->count() > 0)
                                    <li class="user-body" style="padding: 0 15px">
                                        <div class="row">
                                            <div class="col-md-12" style="padding: 0;position: relative;">
                                                <ul id="notifications" class="admin-noti" style="list-style: none">
                                                    @foreach(@\App\ProjectRequest::where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->limit(5)->get() as $row)
                                                        {{ $row->type }}
                                                        <a href="{{ url(adminPath().'/get_project') }}">
                                                            <li>
                                                                <div class="v-bar"><i class="fa fa-circle"></i></div>
                                                                <div class="noti-icon"><img
                                                                            src="{{ url('uploads/noti/switch.png') }}">
                                                                </div>
                                                                <span>new project pushed to you "{{ $row->name }}
                                                                    "</span>
                                                            </li>
                                                        </a>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                            @if(@\App\AdminNotification::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->count() > 0)
                                <h6 style="padding: 0 20px; text-transform: uppercase; color: darkgrey;">{{ __('admin.today') }}</h6>
                                <li class="user-body" style="padding: 0 15px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul id="notifications" class="admin-noti">
                                                @foreach(@\App\AdminNotification::where('assigned_to',auth()->id())->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->limit(10)->get() as $notify)
                                                    @if($notify->type == 'switch')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/switch.png') }}">
                                                            </div>
                                                            <a href="#" class="notificationElement"
                                                               url="{{ url(adminPath().'/leads/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                            >
                                                                <span>
                                                                     {{ \App\User::find($notify->user_id)->name . ' ' . __('admin.has_switched') }}
                                                                    @if($notify->type_id != 'bulk')
                                                                        {{ @\App\Lead::find($notify->type_id)->prefix_name . ' ' . @\App\Lead::find($notify->type_id)->first_name }}
                                                                    @else
                                                                        {{ __('admin.bulk') }}
                                                                    @endif
                                                                    {{__('admin.to') . ' ' . @\App\User::find($notify->assigned_to)->name }}
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>


                                                    @elseif($notify->type == 'task')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/task.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/tasks/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    New Task has Added to You
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'finish_task')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/task.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/tasks/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    {{ @App\User::find($notify->user_id)->name }}
                                                                    finished his task

                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'to_do')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/to_do.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/todos/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    You have a to-do to finish

                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'close_deal')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/closed_deal.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/deals/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                            {{ \App\User::find($notify->user_id)->name . ' ' . __('admin.has_switched') }}
                                                                    {{ @App\User::find($notify->user_id)->name }} closed a deal
                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'favorite')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color: {{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/fav.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/lead/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    {{ App\Lead::find($notify->type_id)->first_name }}
                                                                    add a {{ App\Favorite::find($notify->type_id)->type }}
                                                                    to his favorite
                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'new_website_lead')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/new-lead.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/leads/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    {{ App\Lead::find($notify->type_id)->first_name }}
                                                                    add from website
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>
                                                     @elseif($notify->type == 'added_lead')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/task.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/leads/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    New lead has Added to You
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if(@\App\AdminNotification::where('assigned_to',auth()->id())->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->count() > 0)
                                <h6 style="padding: 0 20px; text-transform: uppercase; color: darkgrey;">{{ __('admin.earlier') }}</h6>
                                <li class="user-body" style="padding: 0 15px">
                                    <div class="row">
                                        <div class="col-md-12" style="padding: 0;position: relative;">
                                            <ul id="notifications" class="admin-noti" style="list-style: none">
                                                @foreach(@\App\AdminNotification::where('assigned_to',auth()->id())->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')->limit(10)->get() as $notify)
                                                    @if($notify->type == 'switch')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/switch.png') }}">
                                                            </div>
                                                            <a href="#" class="notificationElement"
                                                               url="{{ url(adminPath().'/leads/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                            >
                                                                <span>
                                                                     {{ \App\User::find($notify->user_id)->name . ' ' . __('admin.has_switched') }}
                                                                    @if($notify->type_id != 'bulk')
                                                                        {{ @\App\Lead::find($notify->type_id)->prefix_name . ' ' . @\App\Lead::find($notify->type_id)->first_name }}
                                                                    @else
                                                                        {{ __('admin.bulk') }}
                                                                    @endif
                                                                    {{__('admin.to') . ' ' . @\App\User::find($notify->assigned_to)->name }}
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>


                                                    @elseif($notify->type == 'task')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/task.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/tasks/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    New Task has Added to You
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'finish_task')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/task.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/tasks/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    {{ @App\User::find($notify->user_id)->name }}
                                                                    finished his task

                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'to_do')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/to_do.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/todos/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    You have a to-do to finish

                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'close_deal')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/closed_deal.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/deals/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                            {{ \App\User::find($notify->user_id)->name . ' ' . __('admin.has_switched') }}
                                                                    {{ @App\User::find($notify->user_id)->name }} closed a deal
                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'favorite')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color: {{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/fav.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/lead/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    {{ App\Lead::find($notify->type_id)->first_name }}
                                                                    add a {{ App\Favorite::find($notify->type_id)->type }}
                                                                    to his favorite
                                                                 </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @elseif($notify->type == 'new_website_lead')

                                                        <li style="border-bottom: solid 1px silver;">
                                                            <div class="v-bar"><i class="fa fa-circle"
                                                                                  id="noti-{{ $notify->id }}"
                                                                                  @if($notify->status == 0) style="color:{{ @$color }};" @endif></i>
                                                            </div>
                                                            <div class="noti-icon"><img
                                                                        src="{{ url('uploads/noti/new-lead.png') }}">
                                                            </div>
                                                            <a class="notificationElement"
                                                               url="{{ url(adminPath().'/leads/'.$notify->type_id) }}"
                                                               nid="{{ $notify->id }}"
                                                               href="#">
                                                                <span>
                                                                    {{ App\Lead::find($notify->type_id)->first_name }}
                                                                    add from website
                                                                </span>
                                                            </a>
                                                            <div class="noti-options"><i class="fa fa-circle unread"
                                                                                         noti-id="{{ $notify->id }}"
                                                                                         title="{{ $notify->status? 'mark as unread':'mark as read' }}"></i>
                                                            </div>
                                                        </li>

                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li style="background-color: black;color: white;text-align: center"><a
                                        href="{{ url(adminPath().'/notifications') }}">see more</a></li>
                        </ul>
                    </li>


                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu" style="font-size: 1.5em">
                    @if(app()->getLocale() == 'ar')
                        <a href="{{ url(adminPath().'/language/en') }}" class="">
                            <i class="fa fa-globe"></i>
                            <span class="label label-danger" style="font-size: 0.5em">en</span>
                        </a>
                    @else
                        <a href="{{ url(adminPath().'/language/ar') }}" class="">
                            <i class="fa fa-globe"></i>
                            <span class="label label-danger" style="font-size: 0.5em">ar</span>
                        </a>
                    @endif
                </li>

                @if(auth()->user()->email_password != '' and decrypt(auth()->user()->email_password) != null)
                    <li class="dropdown tasks-menu" style="font-size: 1.5em">
                        <a href="{{ url(adminPath().'/inbox') }}" class="">
                            <i class="fa fa-envelope"></i>
                            @if(Home::count_mails())
                                <span class="label label-danger"
                                      style="font-size: 0.5em">{{ Home::count_mails() }}</span>
                            @endif
                        </a>
                    </li>
                @endif
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ url('uploads/'.auth()->user()->image) }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ url('uploads/'.auth()->user()->image) }}" class="img-circle" alt="User Image">

                            <p>
                                {{ auth()->user()->name }}
                                - {{ @App\AgentType::find(auth()->user()->agent_type_id)->name }}
                                <small>{{ trans('admin.member_since') .' ' }}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body hidden">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url(adminPath().'/employees/'.@\App\Employee::where('user_id',Auth()->user()->id)->first()->id)}}"
                                   class="btn btn-default btn-flat">{{ trans('admin.profile') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url(adminPath().'/logout') }}"
                                   class="btn btn-default btn-flat">{{ trans('admin.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
                @if(checkRole('settings') or auth()->user()->type == 'admin')
                    <li class="dropdown tasks-menu" style="font-size: 1.5em">
                        <a href="{{ url(adminPath().'/settings_menu') }}" class="">
                            <i class="fa fa-gears"></i>
                        </a>
                    </li>
                @endif
            </ul>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
</header>

<div class="content-wrapper">
    <section class="content-header">