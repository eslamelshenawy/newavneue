@extends('admin.index')

@section('content')

    @include('admin.employee.hr_nav')
    <style>
    .table .round-img img {
    width: 38px;
}

.round-img img {
    border-radius: 100px;
}
    </style>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                  <table class="table table-hover table-striped datatable">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Allowances</th>
                        <th>Deduction</th>
                        <th>Status</th>
                        <th>Order by</th>
                        <th>Details</th>
                        <th>Ordered time</th>
                        <th>Full salary </th> 
                    </tr>
                </thead>
                <tbody>
                     @foreach($salary_details as $salary_detail)
                     
                     <tr>
                         <td>
                             @if(isset(\App\Employee::find($salary_detail->employee_id)->photos->where('code', 'profile')->first()->image))
                                 <div class="round-img">
                                     <img src="{{url('uploads/'.@\App\Employee::find($salary_detail->employee_id)->photos->where('code', 'profile')->first()->image) }}"
                                          alt="{{ __('admin.employee') }}">
                                 </div>
                             @else
                                 <div class="round-img">
                                     <img src="{{url('uploads/website_cover_81698172832.jpg')}}"
                                          alt="{{ __('admin.employee') }}">
                                 </div>
                             @endif
                         </td>
                            
                            <td>{{$salary_detail->employee_id}}</td>
                            <td>{{@\App\Employee::find($salary_detail->employee_id)->en_first_name.' '.@\App\Employee::find($salary_detail->employee_id)->en_middle_name}}</td>
                            <td>{{$salary_detail->allowances}}</td>
                            <td>{{$salary_detail->deductions}}</td>
                            <td>{{$salary_detail->status}}</td>
                            <td>{{$salary_detail->ordered_by}}</td>
                            <td>{{$salary_detail->details}}</td>
                            <td>{{$salary_detail->ordered_time}}</td>
                            <td>{{$salary_detail->full_salary}}</td>
                        </tr>
                      
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
  <script>
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': false,
            'autoWidth': true,
            "pagingType": 'simple',

        })
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
@endsection

