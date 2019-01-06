<?php

namespace App\Http\Controllers;

use App\JobCategory;
use App\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobTitleController extends Controller
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
        $jobTitles = JobTitle::with('category')->get();
        $title = __('admin.job_title');
        return view('admin.job_title.index',compact('title','jobTitles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobCategories = JobCategory::all();
        $title = __('admin.job_title');
        return view('admin.job_title.create',compact('title','jobCategories'));
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
            'en_name' => 'required',
            'ar_name' => 'required',
            'en_description' => 'required',
            'ar_description' => 'required',
            'category_id' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'en_description' => trans('admin.en_description'),
            'ar_description' => trans('admin.ar_description'),
            'category_id' => trans('admin.category_id'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
        $jobTitle = new JobTitle();
        $jobTitle->en_name = $request->en_name;
        $jobTitle->ar_name = $request->ar_name;
        $jobTitle->en_description = $request->en_description;
        $jobTitle->ar_description = $request->ar_description;
        $jobTitle->job_category_id = $request->category_id;
        $jobTitle->save();
        return redirect(adminPath().'/job_titles');
    }}

    /**
     * Display the specified resource.
     *
     * @param  \App\JobTitle  $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function show(JobTitle $jobTitle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobTitle  $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function edit(JobTitle $jobTitle)
    {
        $title = __('admin.job_title');
        $jobCategories = JobCategory::all();
        return view('admin.job_title.edit',compact('jobTitle','title','jobCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobTitle  $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobTitle $jobTitle)
    {
        $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
            'en_description' => 'required',
            'ar_description' => 'required',
            'job_category_id' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'en_description' => trans('admin.en_description'),
            'ar_description' => trans('admin.ar_description'),
            'job_category_id' => trans('admin.job_category_id'),

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
        $jobTitle->en_name = $request->en_name;
        $jobTitle->ar_name = $request->ar_name;
        $jobTitle->en_description = $request->en_description;
        $jobTitle->ar_description = $request->ar_description;
        $jobTitle->job_category_id = $request->category_id;
        $jobTitle->save();
        return redirect(adminPath().'/job_titles');
    }}

    public function get_titles(Request $request){
        $titles = JobTitle::where('job_category_id',$request->cat)->get();
        return view('admin.applications.titles',compact('titles'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobTitle  $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobTitle $jobTitle)
    {
        $jobTitle->delete();
        return back();
    }
}
