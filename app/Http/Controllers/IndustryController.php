<?php

namespace App\Http\Controllers;

use Validator;
use App\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
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
        $industries = Industry::get();
        return view('admin.industries.index', ['title' => trans('admin.all_industries'), 'industries' => $industries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.industries.create', ['title' => trans('admin.add_industry')]);
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
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $industry = new Industry;
            $industry->name = $request->name;
            $industry->notes = $request->notes;
            $industry->user_id = auth()->user()->id;
            $industry->save();
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/industries/' . $industry->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Industry $industry
     * @return \Illuminate\Http\Response
     */
    public function show(Industry $industry)
    {
        return view('admin.industries.show', ['title' => trans('admin.show_industry'), 'industry' => $industry]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Industry $industry
     * @return \Illuminate\Http\Response
     */
    public function edit(Industry $industry)
    {
        return view('admin.industries.edit', ['title' => trans('admin.edit_industries'), 'industry' => $industry]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Industry $industry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Industry $industry)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $industry->name = $request->name;
            $industry->notes = $request->notes;
            $industry->save();
            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/industries/' . $industry->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Industry $industry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Industry $industry)
    {
        $industry->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/industries');
    }
}
