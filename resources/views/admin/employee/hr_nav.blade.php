<div id="mySidenav" class="sidenav ">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <ul>
        <li class="start ">
            <a href="javascript:;">
                <i class="fa fa-edit"></i>
                <span class="title">Dashboard</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
                <li>
                    <a href="{{url('/emp-dashboard')}}">
                        <i class="fa fa-home"></i>
                        Home</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/job_categories') }}">
                        <i class="fa fa-briefcase"></i>
                        Job Category</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/job_titles') }}">
                        <i class="fa fa-briefcase"></i>
                        Job Title</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/vacancies') }}">
                        <i class="fa fa-cogs"></i>
                        Vacancies</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/applications') }}">
                        <i class="fa fa-drivers-license"></i>
                        Applications</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/employees') }}">
                        <i class="fa fa-users"></i>
                        Employees</a>
                </li>
                <li>
                    <a href="{{ url(adminPath().'/rates/create') }}">
                        <i class="fa fa-diamond"></i>
                        Assign Rate</a>
                </li>
                <li>
                    <a href="{{ url(adminPath().'/custodies') }}">
                        <i class="fa fa-briefcase"></i>
                        Custodies</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/hr-settings') }}">
                        <i class="fa fa-cog"></i>
                        Settings</a>
                </li>

                <li>
                    <a href="{{ url(adminPath().'/xattendance') }}">
                        <i class="fa fa-users"></i>
                        Attendances</a>
                </li>
                 <li>
                    <a href="{{ url(adminPath().'/salaries') }}">
                        <i class="fa fa-money"></i>
                        Salaries</a>
                </li>
                  <li>
                    <a href="{{ url(adminPath().'/salaries-details') }}">
                        <i class="fa fa-money"></i>
                        Salaries-details</a>
                </li>
            </ul>
        </li>
    </ul>

</div>

{{--    @if(auth()->user()->type=='admin' || @\App\Employee::where('is_hr',1))--}}
{{--@if($employee->is_hr ==1)--}}
<span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; HR</span>
{{--@endif--}}

<div id="main">