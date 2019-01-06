<?php

namespace App\Http\Controllers;

use App\Vacation;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VacationController extends Controller
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
        $vacations = Vacation::all();
        $title = __('admin.vacations');
        return view('admin.vacations.index', compact('title', 'vacations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('admin.vacations');
        return view('admin.vacations.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
            'annual_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'number_of_days' => trans('admin.number_of_days'),
            'start_date' => trans('admin.start_date'),
            'end_date' => trans('admin.end_date'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {

      
            $vacation = new Vacation;
            $vacation->en_name = $request->en_name;
            $vacation->ar_name = $request->ar_name;
            $vacation->number_of_days = $request->annual_days;
            $vacation->type = $request->type;
            $vacation->start_date = strtotime($request->start_date);
            $vacation->end_date = strtotime($request->end_date);
            $vacation->save();
            return redirect('admin/vacations');
         
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vacation $vacation
     * @return \Illuminate\Http\Response
     */
    public function show(Vacation $vacation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vacation $vacation
     * @return \Illuminate\Http\Response
     */
    public function edit(Vacation $vacation)
    {
        $title = __('admin.vacations');
        return view('admin.vacations.edit', compact('vacation', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Vacation $vacation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacation $vacation)
    {
        $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
            'number_of_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'number_of_days' => trans('admin.number_of_days'),
            'start_date' => trans('admin.start_date'),
            'end_date' => trans('admin.end_date'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
        $vacation->en_name = $request->en_name;
        $vacation->ar_name = $request->ar_name;
        $vacation->number_of_days = $request->number_of_days;
        $vacation->type = $request->type;
        $vacation->start_date = date('Y,m,d',strtotime($request->start_date));
        $vacation->end_date =date( 'Y,m,d',strtotime($request->end_date));
        $vacation->save();
        return redirect('admin/vacations');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vacation $vacation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacation $vacation)
    {
        $vacation->delete();
        return back();
    }



    public function requestVacation(Request $request)
    {
        $rules = [
            'reason' => 'required',
            'number_of_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'reason' => trans('admin.reason'),
            'number_of_days' => trans('admin.number_of_days'),
            'start_date' => trans('admin.start_date'),
            'end_date' => trans('admin.end_date'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
        if ($request->has('employee_id')&&$request->has('reason')) {
            foreach ($request->reason as $k => $v) {
                
                $vacation = new Vacation;
                $vacation->employee_id = $request->employee_id;
                $vacation_payment=$request->vacation_pay[$k];
                if($vacation_payment == '1'){
                 $vacation->vacation_payment_desire="annual vacation";
                }
                elseif($vacation_payment == '2'){
                    $vacation->vacation_payment_desire="unschedule vacation";
                }
                else{
                    $vacation->vacation_payment_desire="free";
                }
                $vacation->reason = $request->reason[$k];
                $vacation->type = "request";
                $vacation->number_of_days = $request->number_of_days [$k];
                $vacation->type="request";
                
                $vacation->start_date = date('Y,m,d',strtotime($request->start_date[$k]));
                $vacation->end_date = date('Y,m,d',strtotime($request->end_date[$k]));
                $vacation->save();
            };
          

        }
        return back();
    }}

    public function approveVacation(Request $request)
    {
         // dd($request->groupOfDefaultRadios);
        if ($request->has('employee_id')) {
                
                
            $vacation = Vacation::findorfail($request->vacation_id);
            $vacation->is_approved = '1';
            $vacation->save();
            $vacation=Vacation::where('id','=',$request->vacation_id)
            ->where('employee_id','=',$request->employee_id)->first();
            $number_of_days=$vacation->number_of_days;
            $emp=Employee::where('id','=',$request->employee_id)->first();
           if($request->groupOfDefaultRadios=="1"){
             
                
                $emp->requested_vacation =$number_of_days + $emp->requested_vacation ;
                $emp->annual_vacations =$emp->annual_vacations - $number_of_days;
                $vacation->vacation_payment="annual vacation";
                $vacation->save();
                $emp->save();
        }
            elseif($request->groupOfDefaultRadios=="2"){
                
            
            $emp->requested_vacation =$number_of_days + $emp->requested_vacation ;
            $emp->unscheduled_vacation =$emp->unscheduled_vacation - $number_of_days;
            $vacation->vacation_payment="unscheduled vacation";
            $vacation->save();     
            $emp->save();

            }
            else{

            $emp->requested_vacation =$emp->requested_vacation ;
             $vacation->vacation_payment="free";
             $vacation->save();
            $emp->save();


            }
        

        }
        return back();

    }
    public function disApproveVacation(Request $request)
    {
        if ($request->has('employee_id')) {
            $vacation = Vacation::findorfail($request->vacation_id);
            $vacation->is_approved = '0';
            $vacation->save();
        }
        return back();

    }
}