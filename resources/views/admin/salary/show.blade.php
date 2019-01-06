@extends('admin.index')

@section('content')

    @include('admin.employee.hr_nav')

       <div class="main-wrapper">
           <div class="sidebar-overlay" id="sidebar-overlay"></div>
           <article class="content responsive-tables-page">
               <div class="title-block">
                   <h1 class="title">
                        Salary statement
                    </h1>
                </div>
                <section class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-block">
                                     <section class="example">
                                         <div class="table-responsive">
                                             <div class="panel panel-default">
                                                 <div class="panel-body">
                                                      @for($i=0;$i<$ids_number;$i++)
                                                         

      
                                                     <!-- statement layout -->
                                                     <div class="table-responsive" style="align:center">
                                                         <h1 class="text-center text-uppercase">SWOT Techsolutions</h1>
                                                         <p class="text-center">New Street Main Road, Chennai</p>
                                                         <hr>
                                                         <div class="float:right">
                                                             <h3 class="text-center text-uppercase">Salary Slip</h3>
                                                             <p>
                                                                 <strong>Employee Name: </strong><span class="text-uppercase">{{\App\Employee::find($employees_ids[$i])->en_first_name}} {{\App\Employee::find($employees_ids[$i])->en_middle_name}}</span> <br>
                                                                 <strong>Designation: </strong>{{\App\JobCategory::find(\App\Employee::find($employees_ids[$i])->job_category_id)->en_name}}  <br>
                                                                 <strong>Month &amp; Year: </strong> Sep, 2016  <br>
                                                                </p>
                                                            </div>
                                                            <br>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr class="active">
                                                                        <th>Earnings</th>
                                                                        <th></th>
                                                                        <th>Deductions</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><strong>Basic &amp; DA</strong></td>
                                                                        <td>{{\App\Salary::where('employee_id',$employees_ids[$i])->first()->basic}}</td>
                                                                        <td><strong>TAX</strong></td>
                                                                        <td> 262.50 </td></tr>
                                                                        <tr>
                                                                            <td><strong>Incentives</strong></td>
                                                                            <td> 100 </td>
                                                                            <td><strong>Other Deductions</strong></td>
                                                                            <td> 105.00 </td></tr>
                                                                            <tr class="cap">
                                                                                <td>
                                                                                    </td>
                                                                                    <td>
                                                                                        </td>
                                                                                        <td>
                                                                                            </td>
                                                                                            <td>
                                                                                                </td>
                                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>Total Addition</strong></td><td>{{\App\Salary::where('employee_id',$employees_ids[$i])->first()->gross}}</td>
                                                                                <td><strong>Total Deduction</strong></td><td>{{\App\Salary::where('employee_id',$employees_ids[$i])->first()->net}}</td>
                                                                            </tr>
                                                                            <tr><td colspan="2"></td>
                                                                                <td class="active"><strong>NET Salary</strong></td><td class="active"><strong>{{\App\Salary::where('employee_id',$employees_ids[$i])->first()->full_salary}}</strong></td></tr>
                                                                            </tbody></table>
                                                                            <br>
                                                                            <p class="text-capitalize">
                                                                                Rupeesone thousand seven hundred and thirty-three only
                                                                            </p><p>
                                                                                <strong>Transaction Details:</strong>  <br>
                                                                                <strong>Transaction No:</strong>  <br>
                                                                            </p>
                                                                            <br>
                                                                            <p><i>Thank you, Happy Spending!</i></p><p>
                                                                                </p><p class="text-right">
                                                                                    </p><h4>Managing Director</h4>
                                                                                    <p></p>
                                                                                    <p align="right"><small>Computer Generated Salary Slip,needs No Signature</small></p>
                                                                                    <br>
                                                                                    <div class="break"></div>
                                                                                    <br>
                                                                                </div></div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </section>

                                            </article>
                                            @endfor
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
    <script>
@endsection

