<?php

namespace App\Http\Controllers;

use App\Custody;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustodiesController extends Controller
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
        $title = __('admin.custody');
        $custodies= Custody::all();
        return view ('admin.custodies.index',compact('custodies','title'));
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
        $rules = [
            'title' => 'required',
            'qr_code' => 'required',
            'status' => 'required',
            'date' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'title' => trans('admin.title'),
            'qr_code' => trans('admin.qr_code'),
            'status' => trans('admin.status'),
            'status' => trans('admin.date'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->has('employee_id')) {
                $custody = new Custody();
                $custody->title = $request->title;
                $custody->employee_id = $request->employee_id;
                $custody->qr_code = $request->qr_code;
                $custody->status = $request->status;
                $custody->date = $request->date;
                $custody->save();
                dd($custody);
                return back();


            }
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function deliverCustody(Request $request)
    {
            $custody = Custody::findorfail($request->custody_id);
            $custody->delivered= '1';
            $custody->delivered_in =$request->delivered_in;
            $custody->save();
            return back();
    }
}
