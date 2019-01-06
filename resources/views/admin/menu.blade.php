<!-- Left side column. contains the logo and sidebar -->
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar" style="float: right">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            {{--<li class="header">MAIN NAVIGATION</li>--}}
            <li class="@if(Request::segment(2) == 'settings') active @endif">
                <a href="{{ url(adminPath().'/settings') }}">
                    <i class="fa fa-gear"></i> <span>{{ trans('admin.settings') }}</span>
                </a>
            </li>
            <li class="treeview @if(Request::segment(2) == 'lead_sources') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.lead_sources') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'lead_sources') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'lead_sources' and Request::segment(3) == '') class="active" @endif>
                        <a href="{{ url(adminPath().'/lead_sources') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.all_lead_sources') }}</a></li>
                    <li @if(Request::segment(2) == 'lead_sources' and Request::segment(3) == 'create') class="active" @endif>
                        <a href="{{ url(adminPath().'/lead_sources/create') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.add_lead_source') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'leads') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.leads') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'leads') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'leads' and Request::segment(3) == '') class="active" @endif><a
                                href="{{ url(adminPath().'/leads') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.all_leads') }}</a></li>
                    <li @if(Request::segment(2) == 'leads' and Request::segment(3) == 'create') class="active" @endif><a
                                href="{{ url(adminPath().'/leads/create') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.add_lead') }}</a></li>
                    <li @if(Request::segment(2) == 'leads' and Request::segment(3) == 'uploads' and Request::segment(4) == 'excel') class="active" @endif><a
                                href="{{ url(adminPath().'/leads/uploads/excel') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.add_file') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'agent_types') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.agent_type') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'agent_types') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'agent_types' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/agent_types') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.agent_type') }}</a></li>
                    <li @if(Request::segment(2) == 'agent_types' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/agent_types/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.agent_type') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'agent') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.agent') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'agent') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'agent' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/agent') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.agent') }}</a></li>
                    <li @if(Request::segment(2) == 'agent' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/agent/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.agent') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'calls') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-phone"></i> <span>{{ trans('admin.calls') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'calls') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'calls' and Request::segment(3) == '') class="active" @endif><a
                                href="{{ url(adminPath().'/calls') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.all_calls') }}</a></li>
                    <li @if(Request::segment(2) == 'calls' and Request::segment(3) == 'create') class="active" @endif><a
                                href="{{ url(adminPath().'/calls/create') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.add_call') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'meetings') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-handshake-o"></i> <span>{{ trans('admin.meetings') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'meetings') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'meetings' and Request::segment(3) == '') class="active" @endif><a
                                href="{{ url(adminPath().'/meetings') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.all_meetings') }}</a></li>
                    <li @if(Request::segment(2) == 'meetings' and Request::segment(3) == 'create') class="active" @endif><a
                                href="{{ url(adminPath().'/meetings/create') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.add_meeting') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'groups') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-users"></i> <span>{{ trans('admin.groups') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'groups') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'groups' and Request::segment(3) == '') class="active" @endif><a
                                href="{{ url(adminPath().'/groups') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.all_groups') }}</a></li>
                    <li @if(Request::segment(2) == 'groups' and Request::segment(3) == 'create') class="active" @endif><a
                                href="{{ url(adminPath().'/groups/create') }}"><i
                                    class="fa fa-circle-o"></i> {{ trans('admin.add_group') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'unit_types') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.unit_type') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'unit_types') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'unit_types' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/unit_types') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.unit_type') }}</a></li>
                    <li @if(Request::segment(2) == 'unit_types' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/unit_types/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.unit_type') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'requests') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.requests') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'requests') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'requests' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/requests') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.requests') }}</a></li>
                    <li @if(Request::segment(2) == 'requests' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/requests/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.requests') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'tasks') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.task') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'tasks') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'tasks' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/tasks') }}"><i class="fa fa-circle-o"></i>{{ trans('all') }} {{ trans('admin.task') }}</a></li>
                    <li @if(Request::segment(2) == 'tasks' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/tasks/create') }}"><i class="fa fa-circle-o"></i>{{ trans('add') }} {{ trans('admin.task') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'todos') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.todos') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'todos') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'todos' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/todos') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.todos') }}</a></li>
                    <li @if(Request::segment(2) == 'todos' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/todos/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.todo') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'targets') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.targets') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'targets') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'targets' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/targets') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.targets') }}</a></li>
                    <li @if(Request::segment(2) == 'targets' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/targets/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.target') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'industries') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.industries') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'industries') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'industries' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/industries') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.industries') }}</a></li>
                    <li @if(Request::segment(2) == 'industries' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/industries/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.industry') }}</a></li>
                </ul>
            </li>
            <li class="@if(Request::segment(2) == 'location') active @endif">
                <a href="{{ url(adminPath().'/location') }}">
                    <i class="fa fa-thumb-tack"></i> <span>{{ trans('admin.location') }}</span>
                </a>
            </li>
            <li class="treeview @if(Request::segment(2) == 'schools') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.school') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'schools') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'schools' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/schools') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.school') }}</a></li>
                    <li @if(Request::segment(2) == 'schools' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/schools/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.school') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'companies') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.companies') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'companies') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'companies' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/companies') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.companies') }}</a></li>
                    <li @if(Request::segment(2) == 'companies' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/companies/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.company') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'professions') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.professions') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'professions') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'professions' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/professions') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.professions') }}</a></li>
                    <li @if(Request::segment(2) == 'professions' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/professions/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.profession') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'developers') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.developer') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'developers') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'developers' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/developers') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.developer') }}</a></li>
                    <li @if(Request::segment(2) == 'developers' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/developers/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.developer') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'properties') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.property') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'properties') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'properties' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/properties') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.properties') }}</a></li>
                    <li @if(Request::segment(2) == 'properties' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/properties/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.properties') }}</a></li>
                </ul>
            </li>
            <li class="treeview @if(Request::segment(2) == 'facilities') menu-open active @endif">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('admin.facilities') }}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" @if(Request::segment(2) == 'facilities') style="display: block" @endif>
                    <li @if(Request::segment(2) == 'facilities' and Request::segment(3) == '') class="active" @endif><a href="{{ url(adminPath().'/facilities') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.all') }} {{ trans('admin.facilities') }}</a></li>
                    <li @if(Request::segment(2) == 'facilities' and Request::segment(3) == 'create') class="active" @endif><a href="{{ url(adminPath().'/facilities/create') }}"><i class="fa fa-circle-o"></i>{{ trans('admin.add') }} {{ trans('admin.facilities') }}</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<div class="content-wrapper">
    <section class="content-header">
