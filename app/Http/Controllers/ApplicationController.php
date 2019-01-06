<?php

namespace App\Http\Controllers;
use App\Application;
use App\JobCategory;
use App\JobProposal;
use App\JobTitle;
use App\vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
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

        $applications = Application::all();
        $categories = JobCategory::all();
        $job_titles = JobTitle::all();
        $title = __('admin.applications');
        return view('admin.applications.all', compact('applications', 'title', 'categories', 'job_titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($vacancy_id = 0)
    {
        $title = __('admin.applications');
        $categories = JobCategory::all();
        $job_titles = JobTitle::all();
        $vacancies = vacancy::all();
        return view('admin.applications.create', compact('title', 'vacancy_id', 'categories', 'job_titles','vacancies'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'location' => 'required',
            'cv' => 'required',
            'linkedin' => 'required',
            'website' => 'required',
            'job_category' => 'required',
            'job_titles' => 'required',
            'vacancy_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'first_name' => trans('admin.first_name'),
            'last_name' => trans('admin.last_name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'location' => trans('admin.location'),
            'cv' => trans('admin.cv'),
            'linkedin' => trans('admin.linkedin'),
            'website' => trans('admin.website'),
            'job_category' => trans('admin.job_category'),
            'job_titles' => trans('admin.job_title'),
            'vacancy_id' => trans('admin.vacancy_id'),

        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
        if ($request->vacancy_id) {
            $vacancy = vacancy::find($request->vacancy_id);

            $job_title_id = $vacancy->job_title_id;
            $job_category = $request->job_category;

        } else {

            $job_title_id = $request->job_titles;
            $job_category = $request->job_category;
        }
        $application = new Application();
        $application->first_name = $request->first_name;
        $application->last_name = $request->last_name;
        $application->email = $request->email;
        $application->phone = $request->phone;
        $application->location = $request->location;
        $application->cv = $request->file('cv')->store('CVs');
        $application->linkedin = $request->linkedin;
        $application->website = $request->website;
        $application->applied_date = $request->applied_date;
        $application->vacancy_id = $request->vacancy_id;
        $application->job_category_id = $request->job_category;
        $application->job_title_id = $request->job_titles ;
        $application->job_category_id = $job_title_id;
        $application->job_title_id =$job_category  ;
        $application->save();
        return redirect(adminPath() . '/applications/vacancy/' . $application->vacancy_id);
    }}

    /**
     * Display the specified resource.
     *
     * @param  \App\Application $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        $title = __('admin.applications');
        $proposal = JobProposal::where('application_id', $application->id)->first();
        return view('admin.applications.show', compact('application', 'title', 'proposal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        $title = __('admin.applications');
        $categories = JobCategory::all();
        $proposal = JobProposal::where('application_id', $application->id)->first();
        return view('admin.applications.edit', compact('application', 'title', 'proposal','categories'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Application $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'location' => 'required',
            'cv' => 'required',
            'linkedin' => 'required',
            'website' => 'required',
            'job_category' => 'required',
            'job_titles' => 'required',
            'vacancy_id' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'first_name' => trans('admin.first_name'),
            'last_name' => trans('admin.last_name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'location' => trans('admin.location'),
            'cv' => trans('admin.cv'),
            'linkedin' => trans('admin.linkedin'),
            'website' => trans('admin.website'),
            'job_category' => trans('admin.job_category'),
            'job_titles' => trans('admin.job_title_id'),
            'vacancy_id' => trans('admin.vacancy_id'),

        ]);
        $application->first_name = $request->first_name;
        $application->last_name = $request->last_name;
        $application->email = $request->email;
        $application->phone = $request->phone;
        $application->location = $request->location;
        if ($request->hasFile('cv')) {
            $application->cv = $request->file('cv')->store('CVs');
        } else {
            $application->cv = $request->old_cv;
        }
        $application->linkedin = $request->linkedin;
        $application->website = $request->website;
        $application->applied_date = $request->applied_date;
        $application->vacancy_id = $request->vacancy_id;
        $application->acceptness = $request->acceptness;
        $application->save();
        return redirect(adminPath() . '/applications/vacancy/' . $application->vacancy_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return redirect(adminPath().'/applications');
    }

    public function get_applications(Request $request)
    {
        if ($request->title == 0 and $request->cat == 0) {
            $applications = Application::get();
            return view('admin.applications.filtered', compact('applications'));
        }
        if ($request->type == 'cat') {
            $applications = Application::where('job_category_id', $request->cat)->get();
            return view('admin.applications.filtered', compact('applications'));
        }
        if ($request->type == 'title') {
            $applications = Application::where('job_title_id', $request->title)->get();
            return view('admin.applications.filtered', compact('applications'));
        }

    }
    public function changeSelector(Request $request)
    {
        $id = $request->id;
        $job_titles = JobTitle::where('job_category_id', $id)->get();
        return view('admin.applications.get_job_title', compact('job_titles'));
    }
    public function changeTitle(Request $request)
    {
        $id = $request->id;
        $vacancies = vacancy::where('job_title_id', $id)->get();
        return view('admin.applications.get_vacancies', compact('vacancies'));
    }

}
