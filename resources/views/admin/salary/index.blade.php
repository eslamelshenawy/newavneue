@extends('admin.index')

@section('content')
<style>
    .table .round-img img {
    width: 38px;
}

.round-img img {
    border-radius: 100px;
}
    </style>
    @include('admin.employee.hr_nav')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
        <form method='POST' action ="{{ url(adminPath() . '/salaries/slips') }}" >
            {{ csrf_field() }}
            <table class="table table-hover table-striped datatable">
            
                
                <thead>
                    <tr>
                        <th></th>
                        <th>Image</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Baisc salary</th>
                        <th>gross salary</th>
                        <th>net salary</th>
                        <th>Full salary</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($salaries as $salary)
                     <tr>
                     <td>
                         <input type="checkbox" name="foo[]" id="foo[]" class="foo" value="{{$salary->employee_id}}"></td>
                         <td>
                             @php  $emp = @\App\Employee::find($salary->employee_id) @endphp

                             @if($emp && @$emp->photos->where('code', 'profile')->first()->image)
                                 <div class="round-img">
                                     <img src="{{url('uploads/'.@\App\Employee::find($salary->employee_id)->photos->where('code', 'profile')->first()->image) }}"
                                          alt="{{ __('admin.employee') }}">
                                 </div>
                             @else
                                 <div class="round-img">
                                     <img src="{{url('uploads/website_cover_81698172832.jpg')}}"
                                          alt="{{ __('admin.employee') }}">
                                 </div>
                             @endif
                         </td>

                            <td>{{$salary->employee_id}}</td>

                            <td>{{@\App\Employee::find($salary->employee_id)->en_first_name.' '.@\App\Employee::find($salary->employee_id)->en_middle_name}}</td>
                            <td>{{$salary->basic}}</td>
                            <td>{{$salary->gross}}</td>
                            <td>{{$salary->net}}</td>
                            <td>{{$salary->full_salary}}</td>
                            <td>
                                <a href="{{route('salaries.edit', $salary->id)}}" class="btn btn-warning">
                                    <span class="fa fa-edit"></span> Edit </a>
                            </td>
                            
                        </tr>
                    @endforeach
                   </tbody>
                   <thead>
                       <tr>
                          <td colspan="4"><h4>Total</h4></td>
                       <td align="left"><h4>{{$toltal_basic_salary}}</h4></td>
                       <td align="left"><h4>{{$total_gross}}</h4></td>
                       <td align="left"><h4>{{$total_net}}</h4></td>
                       <td align="left"><h4>{{$total_full_salary}}</h4></td>
<td></td><td></td><td></td><td></td>

                        </tr>
                        
                     </thead>
              
                </table>
              
             <input name="print" type="submit" id="print" value="Salary Slip" class="btn btn-primary"><br/>
            </form>
            <input type="checkbox" onclick="toggle(this)" name="chk1" id="chk1">
                <strong>Select All</strong>
                      
                
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
    <script>
$('#chk1').click(function(event) {
    if(this.checked) {
        $(':checkbox').prop('checked', true);
    } else {
        $(':checkbox').prop('checked', false);
    }
});
</script>
@endsection

