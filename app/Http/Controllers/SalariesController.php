<?php

namespace App\Http\Controllers;


use App\Salary;
use App\Employee;
use App\SalaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalariesController extends Controller
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
        $title = __('admin.salary');
        $salaries =Salary::all();
        $toltal_basic_salary=Salary::all()->sum('basic');
        $total_gross=Salary::all()->sum('gross');
        $total_net=Salary::all()->sum('net');
        $total_full_salary=Salary::all()->sum('full_salary');
        return view('admin.salary.index',compact('salaries','title','toltal_basic_salary','total_gross','total_net','total_full_salary'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
     /**
     * Display the specified resource.
     *@param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function salary_slip(Request $request)
    {
        // dd($request->all());
         $employees_ids =$request->foo;
         $ids_number=count($employees_ids);
         //dd($employees_ids);
        
     
        return view('admin.salary.show',compact('employees_ids','ids_number'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $salary =Salary::find($id);
        return view('admin.salary.edit',compact('salary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
           $rules = [
                'date' => 'required',
                'by' => 'required',
                'details' => 'required',

            ];
            $validator = Validator::make($request->all(), $rules);
            $validator->SetAttributeNames([
                'date' => trans('admin.date'),
                'by' => trans('admin.by'),
                'details' => trans('admin.details'),

            ]);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            } else {

                $salary = Salary::where('employee_id', $id)->first();
                $salary_detail = new SalaryDetail();
                $salary_detail->employee_id = $id;

                if ($request->allowances) {
                    $salary_detail->allowances = $request->allowances;

                    $salary->gross = $request->allowances +  $salary->gross;
                    
                    $salary_detail->full_salary=$salary->full_salary+$request->allowances;
                    $salary->full_salary = $salary->basic +$salary->gross - $salary->net;
                    $salary_detail->status = 'increase';

                } elseif ($request->deductions) {
                    $salary_detail->deductions = $request->deductions;
                    $salary_detail->full_salary=$salary->full_salary - $request->deductions;
                    $salary->net =  $request->deductions + $salary->net;
                    $salary->full_salary = $salary->basic +$salary->gross - $salary->net;
                    $salary_detail->status = 'decrease';
                }
                

                $salary_detail->ordered_time = $request->date;
                $salary_detail->ordered_by = $request->by;
                $salary_detail->details = $request->details;
                $salary->employee_id = $id;
                $salary_detail->save();
                $salary->save();
                session()->flash('success', trans('admin.updated'));
                return redirect(adminPath() . '/salaries');
                
                
            }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function finalSalaryCalculations()
    {
        //
    }
}
