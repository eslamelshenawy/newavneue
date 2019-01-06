
@extends('admin.index')
    
@section('styles')
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/style.css') }}">
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/carousel.min.css') }}">
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/helper.css') }}">
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/pignose.calender.min.css') }}">
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/semantic.ui.min.css') }}">
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/theme.default.min.css') }}">
     <link rel="stylesheet" href="{{ url('dashboard_employee/css/icon/simple-line-icon.css') }}">
@endsection
@section('content')
@include('admin.employee.hr_nav')
     <div class="container-fluid">
         <!-- Start Page Content -->
         <div class="form-group">
             <!--<label for="sel1">Duration:</label> -->
             <select class="form-control" id="sel1" onchange='location = this.value'>
                 <option selected value="{{ url( adminPath() .'/emp-dashboard?dur=day') }}">Day</option>
                 <option value="{{ url( adminPath() .'/emp-dashboard?dur=month') }}" @if($duration=='month') {{'selected'}} @endif>Month</option>
                 <option value="{{ url( adminPath() .'/emp-dashboard?dur=year') }}"@if($duration=='year') {{'selected'}} @endif>Year</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card p-30">
                        <div class="media">
                            <div class="media-left meida media-middle">
                                <span>
                                    <i class="fa fa-user-circle f-s-40 color-primary"></i>
                                </span>
                            </div>
                            <div class="media-body media-text-right">
                                <h2>{{$appsCount}}</h2>
                                <p class="m-b-0">Total application</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-30">
                        <div class="media">
                            <div class="media-left meida media-middle">
                                <span>
                                    <i class="fa fa-users f-s-40 color-success">

                                    </i>
                                </span>
                            </div>
                            <div class="media-body media-text-right">
                                <h2>{{$employeeCount}}</h2>
                                <p class="m-b-0">Total employers</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-30">
                        <div class="media">
                            <div class="media-left meida media-middle">
                                <span>
                                    <i class="fa fa-user-plus f-s-40 color-warning">

                                    </i>
                                </span>
                            </div>
                            <div class="media-body media-text-right">
                            <h2>{{$attendance_count}}</h2>
                                <p class="m-b-0">attendant employers</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-30">
                        <div class="media">
                            <div class="media-left meida media-middle">
                                <span>
                                    <i class="fa fa-user-times f-s-40 color-danger">

                                    </i>
                                </span>
                            </div>
                            <div class="media-body media-text-right">
                            <h2>{{$absent_count }}</h2>
                                <p class="m-b-0">absent employers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white m-l-0 m-r-0 box-shadow ">
                <!-- column -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">KPI Chart</h4>
                            <div id="extra-area-chart">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- column -->
                <!-- column -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body browser">
                            <p class="f-w-600">Accepted application<span class="pull-right">{{$accptspercentage}}%</span></p>
                            <div class="progress ">
                                <div role="progressbar" style="width: {{$accptspercentage}}%; height:8px;" class="progress-bar bg-success wow animated progress-animated"> <span class="sr-only">60% Complete</span> </div>
                            </div>
                        <p class="m-t-30 f-w-600">Attendance<span class="pull-right">{{$attendance_percentage}}%</span></p>
                            <div class="progress">
                            <div role="progressbar" style="width: {{$attendance_percentage}}%; height:8px;" class="progress-bar bg-info wow animated progress-animated"> <span class="sr-only">60% Complete</span> </div>
                            </div>
                                 @if($deduct_perentage!=0)
                                <p class="m-t-30 f-w-600">Salary deduction<span class="pull-right">{{$deduct_perentage}}%</span></p>
                                <div class="progress">
                                    <div role="progressbar" style="width: {{$deduct_perentage}}%; height:8px;" class="progress-bar bg-success wow animated progress-animated"> <span class="sr-only">60% Complete</span> </div>
                                </div>
                                 @else
                                <p class="m-t-30 f-w-600">Salary gross<span class="pull-right">{{$gross_percentage}}%</span></p>
                                <div class="progress">
                                <div role="progressbar" style="width: {{$gross_percentage }}%; height:8px;" class="progress-bar bg-danger wow animated progress-animated"> <span class="sr-only">60% Complete</span> </div>
                                </div>
                                @endif
                            <p class="m-t-30 f-w-600">Vacation request<span class="pull-right">{{$vacation_percentage}}%</span></p>
                                <div class="progress m-b-30">
                                <div role="progressbar" style="width:{{$vacation_percentage}}%; height:8px;" class="progress-bar bg-warning wow animated progress-animated"> <span class="sr-only">60% Complete</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- column -->
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card bg-dark">
                            <div class="testimonial-widget-one p-17">
                                <div class="testimonial-widget-one owl-carousel owl-theme">
                                    @foreach($applications as $application)
                                    <div class="item">
                                        <div class="testimonial-content">
                                            <img class="testimonial-author-img" src="images/avatar/2.jpg" alt="" />
                                            <div class="testimonial-author">{{$application->first_name}} {{$application->last_name}}</div>
                                            <div class="testimonial-author-position">Linkedin :<a href="http:\\{{$application->linkedin}}">http:\\..{{$application->linkedin}}</a></div>

                                            <div class="testimonial-text">
                                                <i class="fa fa-quote-left"></i> Website :<a href="http:\\{{$application->website}}">http:\\..{{$application->website}}</a>
                                                <i class="fa fa-quote-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class = "card">
                            <div class ="card-title">
                                <h4>Application status </h4>
                            </div>
                           <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>LinkedIN</th>
                                                <th>Website</th>
                                                <th>take adecision</th>
                                                <th>status</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @foreach($applications as $application)
                                            <tr>
                                                <td>
                                                    <div class="round-img">
                                                        <a href=""><img src="images/avatar/4.jpg" alt=""></a>
                                                    </div>
                                                </td>
                                            <td>{{$application->first_name}} {{$application->last_name}}</td>
                                                <td><span><a href="http:\\{{$application->linkedin}}">http:\\..{{$application->linkedin}}</a></span></td>
                                                <td><span><a href="http:\\{{$application->website}}">http:\\..{{$application->website}}</a></span></td>
                                                <td><span><a href="{{url(adminPath() .'/applications/')}}">application link</a></span></td>
                                                @if($application->acceptness=="accepted")
                                                <td><span class="badge badge-success">{{$application->acceptness}}</span></td>
                                                @elseif($application->acceptness=="rejected")
                                                <td><span class="badge badge-danger">{{$application->acceptness}}</span></td>
                                                @elseif(($application->acceptness=="shortlisted"))
                                                <td><span class="badge badge-info">{{$application->acceptness}}</span></td>
                                                @elseif(($application->acceptness=="proposed"))
                                                <td><span class="badge badge-secondary">{{$application->acceptness}}</span></td>
                                                @else 
                                                <td><span class="badge badge-warning">{{$application->acceptness}}</span></td>
                                                @endif
                                                

                                            </tr> 
                                            @endforeach
                                              </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="row">
                     <div class="col-lg-9">
                        <div class="card">
                            <div class="card-title">
                                <h4>Vacation requests </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>duration</th>
                                                <th>reason</th>
                                                <th>take adecision</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         @foreach($vacations as $vacation)
                                            <tr>
                                               
                                                <td>
                                                  @if(@\App\Employee::find($vacation->employee_id)->photos->where('code', 'profile')->first()->image)
                                                    <div class="round-img">
                                                        <img src="{{url('uploads/'.\App\Employee::find($vacation->employee_id)->photos->where('code', 'profile')->first()->image) }}" alt="">
                                                    </div>
                                                    @else
                                                     <div class="round-img">
                                                        <img src="{{url('uploads/website_cover_81698172832.jpg')}}" alt="">
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>{{\App\Employee::find($vacation->employee_id)->en_first_name}} {{\App\Employee::find($vacation->employee_id)->en_middle_name}}</td>
                                                <td><span>{{$vacation->number_of_days}}</span></td>
                                                <td><span>{{$vacation->reason}}</span></td>
                                                <td><span><a href="{{url(adminPath() .'/employees/'. $vacation->employee_id)}}">Employee account</a></span></td> 
                                                 @if($vacation->is_approved=='1')
                                                <td><span class="badge badge-success">approved</span></td>
                                                @elseif($vacation->is_approved=='0')
                                                 <td><span class="badge badge-danger">disaprroved</span></td>
                                                 
                                                @else
                                                  <td><span class="badge badge-warning">waiting</span></td>
                                                @endif    
                                            </tr>
                                            
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                    </div>
                
                <div class="col-lg-3">
                        <div class="card bg-dark">
                            <div class="testimonial-widget-one p-17">
                                <div class="testimonial-widget-one owl-carousel owl-theme">
                                    @foreach($vacations as $vacation)
                                    <div class="item">
                                        <div class="testimonial-content">
                                            @if(@\App\Employee::find($vacation->employee_id)->photos->where('code', 'profile')->first()->image)
                                            
                                            <img class="testimonial-author-img" src="{{url('uploads/'. \App\Employee::find($vacation->employee_id)->photos->where('code', 'profile')->first()->image) }}" alt="" />
                                            @else
                                            <img class="testimonial-author-img" src="{{url('uploads/website_cover_81698172832.jpg')}}" alt="" />
                                            @endif
                                            <div class="testimonial-author">{{\App\Employee::find($vacation->employee_id)->en_first_name}} {{\App\Employee::find($vacation->employee_id)->en_middle_name}}</div>
                                            <div class="testimonial-author-position">Duration :{{$vacation->number_of_days}}</div>

                                            <div class="testimonial-text">
                                                <i class="fa fa-quote-left"></i> Reason :{{$vacation->reason}}
                                                <i class="fa fa-quote-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
					<div class="col-lg-12">
							<div class="card">
								<div class="card-body">
									<div class="year-calendar"></div>
								</div>
							</div>
						</div>


						</div>
					</div>
                  
                <!-- End PAge Content -->
            </div>

@endsection
@section('js')
<script>
$( function () {
	"use strict";

	// Extra chart
	Morris.Area({
		element: 'extra-area-chart',
		data:{!! json_encode($data) !!},
		lineColors: ['#26DAD2', '#fc6180', '#62d1f3', '#ffb64d', '#4680ff'],
		xkey: 'period',
		ykeys: ['work', 'Apperance', 'Target', 'Ideas', 'Efficient'],
		labels: ['work', 'Apperance', 'Target', 'Ideas', 'Efficient'],
    pointSize: 0,
		lineWidth: 0,
		resize: true,
		fillOpacity: 0.8,
		behaveLikeLine: true,
		gridLineColor: '#e0e0e0',
		hideHover: 'auto'

	});

} );



    </script>
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



@stop









