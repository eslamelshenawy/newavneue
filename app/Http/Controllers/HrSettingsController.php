<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Employee;
use App\HrSetting;
use App\Salary;
use App\SalaryDetail;
use App\Vacation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class HrSettingsController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = HrSetting::all();
        $vacations = Vacation::where('type','!=','request')->get();
        $hr_setting = HrSetting::all();
        return view('admin.employee.setting', compact('setting', 'vacations', 'hr_setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showSettings()
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function insertAttendance(Request $request)
    {
        $path = $request->file('xls')->getRealPath();
        $count = 0;
        Excel::load($path, function ($reader) use (&$count) {
            $array = $reader->toArray();

            foreach ($array as $attends) {
                if (date("Y-m-d", strtotime($attends['date'] . "-" . date("Y"))) > Attendance::pluck('date')->last()) {
                    if (!$attends['actual_t1'] == null) {
                        $attendance = new Attendance();
                        if (!$attends['actual_t2'] == null) {
                            $working_hours = (strtotime($attends['actual_t2']) - strtotime($attends['actual_t1'])) / 3600;
                            $attendance->hours = $working_hours;
                            $attendance->check_out = date("H:i:s", strtotime($attends['actual_t2']));
                        }


                        $attendance->finger_print_id = $attends['enroll_id'];
                        $attendance->full_name = $attends['name'];
                        $attendance->check_in = date("H:i:s", strtotime($attends['actual_t1']));
                        $attendance->date = date("Y-m-d", strtotime($attends['date'] . "-" . date("Y")));

                        if (date("H:i:s", strtotime($attends['planned_t1'])) < date("H:i:s", strtotime($attends['actual_t1']))) {
                            $attendance->status = 'late';
                        } elseif (date("H:i:s", strtotime($attends['planned_t2'])) > date("H:i:s", strtotime($attends['actual_t2']))) {
                            $attendance->status = 'early';
                        } elseif (date("H:i:s", strtotime($attends['actual_t2'])) > date("H:i:s", strtotime($attends['planned_t2']))) {
                            $attendance->status = 'overtime';
                        } else {
                            $attendance->status = 'intime';
                        }

                    } else {
                        $attendance = new Attendance();
                        $attendance->finger_print_id = $attends['enroll_id'];
                        $attendance->full_name = $attends['name'];
                        $attendance->status = "absent";
                        $attendance->date = date("Y-m-d", strtotime($attends['date'] . "-" . date("Y")));
                    }


                    $attendance->save();
                    $count++;


                $emps = Employee::all();
                foreach ($emps as $emp) {
                    $hours = array_sum(Attendance::where('finger_print_id', $emp->finger_print_id)->pluck('hours')->toArray());
                    $salary = Salary::where('employee_id', $emp->id)->first();
                    $salary->salary_on_hours = $hours * $salary->hour_value;
                    $salary->save();
                }}

            }


        });
        return $count .' has been added';
    }




    public function updateSetting(Request $request)
    {
        $type = $request->type;
        $value = $request->value;
        if ($type == 'weekend') {
            $value = implode(" ", $value);
        }

    
        if(HrSetting::where('name', $type)->exists()){
            $hr_settings=HrSetting::where('name', $type)->first();
            $hr_settings->value=$value;
            $hr_settings->save();

        }
        else{
            $hr_settings= new HrSetting();
            $hr_settings->name=$type;
            $hr_settings->value=$value;
            $hr_settings->save();
        }
        $emps = Employee::all();

        if ($type == 'working_days') {
            foreach ($emps as $emp) {
                $emp->day_value = $emp->salary / $value;
                $emp->save();
            }
        }

        elseif ($type == 'working_hours') {
            foreach ($emps as $emp) {
                $salary = Salary::where('employee_id', $emp->id)->first();
                $salary->hour_value = $emp->day_value / $value;
                $salary->save();
            }
        }

        elseif ($type == 'weekend') {
            $weekend_explode = $value;
            $weekend = explode(" ", $weekend_explode);

        }

        elseif ($type == 'start_work') {

            $attends = Attendance::all();
            foreach ($attends as $attend) {
                if (date("H:i:s", strtotime($value)) < date("H:i:s", strtotime($attend->check_in))) {
                    $attend->status = 'late';
                } else {
                    $attend->status = 'intime';
                }
                $attend->save();
            }

        }

        elseif ($type == 'end_work') {
            $attends = Attendance::all();
            foreach ($attends as $attend) {
                if (date("H:i:s", strtotime($value)) > date("H:i:s", strtotime($attend->check_out))) {
                    $attend->status = 'early';
                } elseif (date("H:i:s", strtotime($value)) < date("H:i:s", strtotime($attend->check_out))) {
                    $attend->status = 'overtime';
                } else {
                    $attend->status = 'intime';
                }
                $attend->save();
            }

        }

        elseif ($type == 'annual_increase'){

            $emps = Employee::all();
            foreach($emps as $emp)
            $emp->salary = $emp->salary*($value/100)+$emp->salary;
            $emp->save();
            $sal = Salary::where('employee_id',$emp->id)->first();
            $sal->basic = $emp->salary;
            $sal_details = new SalaryDetail();
            $sal_details->allowances = $emp->salary*($value/100);
            $sal_details->status = 'basic increase';
            $sal_details->details = 'annual increase';
            $sal_details->orderd_by = 'Rules';
            $sal_details->orderd_time = date("Y/m/d");
            $sal->save();

        }

        elseif ($type == 'special_reward'){
            $sals = Salary::all();
            foreach($sals as $sal){
            $sal->full_salary = $sal->basic*($value/100)+$sal->full_salary;
            $sal->save();

            }
        }

        elseif ($type == 'annual_vacation'){
           
            $emps =Employee::all();
            foreach($emps as $emp){
              $emp->annual_vacations = $value - ($emp->requested_vacation ? $emp->requested_vacation :0);
                $emp->save();
            }

        }

        elseif ($type == 'unscheduled_vacation'){
            $emps=Employee::all();
            foreach($emps as $emp){
                $emp->unscheduled_vacation = $value-($emp->vacation_request ? $emp->vacation_request :0) ;
            $emp->save();
            }
        }

        elseif ($type == 'overtime'){
            $attends = Attendance::where('hours','>',HrSetting::where('name','working_hours')->pluck('value'))->get();
            foreach($attends as $attend){
                $attend->hours = $value*($attend->hours-HrSetting::where('name','working_hours')->pluck('value')->first())+$attend->hours ;
                $attend->save();
            }
        }

        elseif ($type == 'punishment'){
            $attends = Attendance::where('hours','<=',HrSetting::where('name','working_hours')->pluck('value'))->get();
            foreach($attends as $attend)
            if((HrSetting::where('name','working_hours')->pluck('value')->first()-$attend->hours)>(HrSetting::where('name','working_hours')->pluck('value')->first())/2)
            $attend->hours =  0 ;
            else {
                $attend->hours = HrSetting::where('name', 'working_hours')->pluck('value')->first()- ((HrSetting::where('name', 'working_hours')->pluck('value')->first()-$attend->hours)*2);
            }
                $attend->save();
        }
         ;

        return response()->json([
            'value' => $value,
            'type' => $type,
        ]);
    }




}

