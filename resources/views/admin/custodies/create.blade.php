@extends('admin.index')

@section('content')
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
                        <a href="{{url('/')}}">
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
                </ul>
            </li>
        </ul>

    </div>

    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Hr</span>

    <div id="main">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "320px";
            document.getElementById("main").style.marginLeft = "320px";
            document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        var closeNav = function () {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            document.body.style.backgroundColor = "white";
        }
    </script>
@endsection