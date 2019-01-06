<?php

namespace App\Http\Controllers;
use App\Application;
use App\Employee;
use App\Rate;
use App\Vacation;
use App\Photo;
use App\Salary;
use App\Attendance;

use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;

class EmployerDashboard extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('employees') or @auth()->user()->type == 'admin') {
                return $next($request);
            } else {
                session()->flash('error', __('admin.you_dont_have_permission'));
                return back();
            }
        });
    }



public function statics()
    {
      $duration=request('dur');
      if($duration=="month")
      {
        $date= date('m');
        $year=date('Y');
        $rate=new Rate;
        $data = array();
        $arr1 = array();
        $days_of_month = date("t");
        for($i = 1 ;$i<=$days_of_month ; $i++)
        {
          
          $work=$rate->whereYear('rate_date',$year)
          ->whereMonth('rate_date',$date)
          ->whereDay('rate_date',$i)->sum('work');
          $apperance=$rate->whereYear('rate_date',$year)
          ->whereMonth('rate_date',$date)
          ->whereDay('rate_date',$i)->sum('apperance');
          $effeciant=$rate->whereYear('rate_date',$year)
          ->whereMonth('rate_date',$date)
          ->whereDay('rate_date',$i)->sum('effeciant');
          $target=$rate->whereYear('rate_date',$year)
          ->whereMonth('rate_date',$date)
          ->whereDay('rate_date',$i)->sum('target');
          $ideas=$rate->whereYear('rate_date',$year)
          ->whereMonth('rate_date',$date)
          ->whereDay('rate_date',$i)->sum('ideas');
          $arr1['period'] = $i;
          $arr1['work']= $work;
          $arr1['Apperance']=$apperance;
          $arr1['Efficient']=$effeciant;
          $arr1['Target']=$target;
          $arr1['Ideas']=$ideas;
          array_push($data, $arr1);
        }
      }
      elseif($duration=="year")
      {
        $date= date('Y');
      
        $rate=new Rate;
        $data = array();
        $arr1 = array();
        for($i = 1 ;$i<= 12; $i++){
          $work=$rate->whereYear('rate_date',$date)
          ->whereMonth('rate_date',$i)->sum('work');
          $apperance=$rate->whereYear('rate_date',$date)
          ->whereMonth('rate_date',$i)->sum('apperance');
          $effeciant=$rate->whereYear('rate_date',$date)
          ->whereMonth('rate_date',$i)->sum('effeciant');
          $target=$rate->whereYear('rate_date',$date)
          ->whereMonth('rate_date',$i)->sum('target');
          $ideas=$rate->whereYear('rate_date',$date)
          ->whereMonth('rate_date',$i)->sum('ideas');
          $arr1['period'] = $i;
          $arr1['work']= $work;
          $arr1['Apperance']=$apperance;
          $arr1['Efficient']=$effeciant;
          $arr1['Target']=$target;
          $arr1['Ideas']=$ideas;
          array_push($data, $arr1);
        }
      }
      else
      {
        $date= date('Y-m-d');
        $month_date=date('m');
        $year_date=date('Y');
        $day_date=date('d');
        $rate=new Rate;
        $data = array();
        $arr1 = array(); 
       
          
        for($i = 1 ;$i<= 23; $i++)
        {
          
          
         
          $work=$rate->whereYear('rate_date',$year_date)
          ->whereMonth('rate_date',$month_date)
          ->whereDay('rate_date',$day_date)
          ->where(DB::raw("hour(rate_date)"),'=',$i)->sum('work');
          $apperance=$rate->whereYear('rate_date',$year_date)
          ->whereMonth('rate_date',$month_date)
          ->whereDay('rate_date',$day_date)
          ->where(DB::raw("hour(rate_date)"),'=',$i)->sum('apperance');
          $effeciant=$rate->whereYear('rate_date',$year_date)
          ->whereMonth('rate_date',$month_date)
          ->whereDay('rate_date',$day_date)
          ->where(DB::raw("hour(rate_date)"),'=',$i)->sum('effeciant');

          $target=$rate->whereYear('rate_date',$year_date)
          ->whereMonth('rate_date',$month_date)
          ->whereDay('rate_date',$day_date)
          ->where(DB::raw("hour(rate_date)"),'=',$i)->sum('target');
          $ideas=$rate->whereYear('rate_date',$year_date)
          ->whereMonth('rate_date',$month_date)
          ->whereDay('rate_date',$day_date)
          ->where(DB::raw("hour(rate_date)"),'=',$i)->sum('ideas');
          $arr1['period'] = $i;
          $arr1['work']= $work;
          $arr1['Apperance']=$apperance;
          $arr1['Efficient']=$effeciant;
          $arr1['Target']=$target;
          $arr1['Ideas']=$ideas;
          array_push($data, $arr1);
        }
      }
      $applications=Application::where('created_at','>=',$date)
      ->orderBy('id','desc')
      ->limit(5)
      ->get();
      $apps = $applications->toArray();
      $appsCount = count($apps);
      $employees = Employee::all();
      $employee=$employees->toArray();
      $employeeCount=count($employee);
      $acceptedapplications=Application::where('created_at','>=',$date)
      ->where('acceptness','=','accepted')
      ->get();
      $accpts=$acceptedapplications->toArray();
      $accptsCount=count($accpts);
      if($appsCount==0){
         $accptspercentage=0;
      }
      else
      {
      
        $accptspercentage=(int)(($accptsCount/$appsCount)*100);
      
      }
      if($employeeCount!=0){
      $employee_request_vacation=Vacation::where('created_at','>=',$date)
      ->where('type','=','request')
      ->where('is_approved','=',NULL)
      ->groupBy('employee_id')
      ->get();
      $employee_vacation_count=count($employee_request_vacation);
      
    
      $vacation_percentage=(int)(($employee_vacation_count/$employeeCount)*100);
      }
      else{
         $vacation_percentage=0;
      }
      $vacations=Vacation::where('created_at','>=',$date)
      ->where('type','=','request')
      ->orderBy('id','desc')
      ->limit(5)
      ->get();
      
    

      $total_full_salary=Salary::where('created_at','>=',$date)
      ->sum('full_salary');
      //dd($total_full_salary);
      $total_basic_salary=Salary::where('created_at','>=',$date)
      ->sum('basic');
      $salary_difference=$total_full_salary - $total_basic_salary;
      if($total_basic_salary!=0){
      if($salary_difference>0){
        $gross_percentage=(int)(($salary_difference/$total_basic_salary)*100);
        $deduct_perentage=0;

      }
      else{
        $deduct_perentage=(int)((-$salary_difference/$total_basic_salary)*100);
        $gross_percentage=0;
      }
    }
    else{
      $gross_percentage=0;
      $deduct_perentage=0;

    }
    $attendance_count=Attendance::where('is_approved','=','1')->get()->count();
        //count($attendance_count);

    $absent_count=$employeeCount-$attendance_count;
    if($employeeCount!=0){
      $attendance_percentage=(int)(($attendance_count/$employeeCount)*100);
    }
    else{
$attendance_percentage=0;
    }
    
     
//dd(json_encode($data));
     
      return view('admin.employee.dashboard',compact('applications', 'appsCount','employeeCount','accptspercentage','gross_percentage','deduct_perentage','vacation_percentage','duration','attendance_count','absent_count','attendance_percentage','vacations','data'));
      
    

}
}
