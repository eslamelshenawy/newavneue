<?php

namespace App\Http\Controllers;

use Validator;
use App\Company;
use App\Industry;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('settings') or @auth()->user()->type == 'admin') {
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
        $companies = Company::get();
        return view('admin.companies.index', ['title' => trans('admin.all_companies'), 'companies' => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $industries = Industry::get();
        return view('admin.companies.create', ['title' => trans('admin.add_company'), 'industries' => $industries]);
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
            'name' => 'required',
            'industry_id' => 'required',
            'email' => 'email',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'industry_id' => trans('admin.industry'),
            'email' => trans('admin.email'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $company = new Company();
            $company->name = $request->name;
            $company->notes = $request->notes;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->industry_id = $request->industry_id;
            $company->user_id = auth()->user()->id;
            $company->save();
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/companies/' . $company->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('admin.companies.show', ['title' => trans('admin.show_company'), 'company' => $company]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $industries = Industry::get();
        return view('admin.companies.edit', ['title' => trans('admin.edit_company'), 'company' => $company, 'industries' => $industries]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $rules = [
            'name' => 'required',
            'industry_id' => 'required',
            'email' => 'email',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'industry_id' => trans('admin.industry'),
            'email' => trans('admin.email'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $company->name = $request->name;
            $company->notes = $request->notes;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->industry_id = $request->industry_id;
            $company->user_id = auth()->user()->id;
            $company->save();
            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/companies/' . $company->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/companies');
    }
}
