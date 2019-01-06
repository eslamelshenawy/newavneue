<?php

namespace App\Http\Controllers;

use App\JobTitle;
use App\vacancy;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VacancyController extends Controller
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
        $title = __('admin.vacancy');
        $vacancies = Vacancy::with('jobTitle')->get();
//          $vacancies = Vacancy::all();
        return view('admin.vacancy.index',compact('title','vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('admin.vacancy');
        $jobTitles = JobTitle::all();
        return view('admin.vacancy.create',compact('title','jobTitles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
            'en_description' => 'required',
            'ar_description' => 'required',
            'job_title_id' => 'required',
            'status' => 'required',
            'type' => 'required',

    ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'en_description' => trans('admin.en_description'),
            'ar_description' => trans('admin.ar_description'),
            'job_title_id' => trans('admin.job_title_id'),
            'status' => trans('admin.status'),
            'type' => trans('admin.type'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
        $vacancy = new vacancy();
        $vacancy->en_name = $request->en_name;
        $vacancy->ar_name = $request->ar_name;
        $vacancy->en_description = $request->en_description;
        $vacancy->ar_description = $request->ar_description;
        $vacancy->job_title_id = $request->job_title_id;
        $vacancy->status = $request->status;
        $vacancy->type = $request->type;
        $vacancy->save();
        return redirect(adminPath().'/vacancies');
    }}

    /**
     * Display the specified resource.
     *
     * @param  \App\vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function show(vacancy $vacancy)
    {
        $title = __('admin.vacancy');
        return view('admin.vacancy.show',compact('vacancy','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function edit(vacancy $vacancy)
    {
        $title = __('admin.vacancy');
        $jobTitles = JobTitle::all();
        return view('admin.vacancy.edit',compact('title','vacancy','jobTitles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vacancy $vacancy)
    {
          $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
            'en_description' => 'required',
            'ar_description' => 'required',
            'job_title_id' => 'required',
            'status' => 'required',
            'type' => 'required',

        ];
            $validator = Validator::make($request->all(), $rules);
            $validator->SetAttributeNames([
                'en_name' => trans('admin.en_name'),
                'ar_name' => trans('admin.ar_name'),
                'en_description' => trans('admin.en_description'),
                'ar_description' => trans('admin.ar_description'),
                'job_title_id' => trans('admin.job_title_id'),
                'status' => trans('admin.status'),
                'type' => trans('admin.type'),
            ]);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            } else {
                    $vacancy->en_name = $request->en_name;
                    $vacancy->ar_name = $request->ar_name;
                    $vacancy->en_description = $request->en_description;
                    $vacancy->ar_description = $request->ar_description;
                    $vacancy->job_title_id = $request->job_title_id;
                    $vacancy->status = $request->status;
                    $vacancy->type = $request->type;
                    $vacancy->save();

        return redirect(adminPath().'/vacancies');}
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function destroy(vacancy $vacancy)
    {
        $vacancy->delete();
        return back();
    }



    public function get_vacancy_applications($id){
        $vacancy = vacancy::find($id);
        $applications = $vacancy->applications;
        $title = __('admin.application');
        return view('admin.applications.index',compact('vacancy','title','applications'));
    }

}

