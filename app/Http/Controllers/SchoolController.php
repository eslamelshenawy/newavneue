<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;
use App\Location;
use Validator;
use Auth;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = School::get();
        return view('admin.schools.index', ['title' => trans('admin.all_leads'), 'school' => $school]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $location = Location::where('parent_id', '=', 0)->select(app()->getLocale() . '_name as title', 'id')->get();
        return view('admin.schools.create', ['title' => trans('admin.all_leads'), 'location' => $location]);
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
            'name' => 'required|max:191',
            'location_id' => 'required|numeric',

        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'location_id' => trans('admin.location'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $school = new School;
            $school->name = $request->name;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->location_id = $request->location_id;
            $school->notes = $request->notes;
            $school->user_id = Auth::user()->id;
            $school->save();
            return redirect(adminPath() . '/schools');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\School $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        $location_id = $school->location_id;
        $full_location = "";
        while ($location_id != '0') {
            $location = Location::find($location_id);
            $location_id = $location->parent_id;
            $full_location .= $location->title . ' -';
        }

        return view('admin.schools.show', ['title' => trans('admin.show_target'), 'location' => trim($full_location, '-'), 'school' => $school]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\School $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        $location = Location::where('parent_id', '=', 0)->get();
        return view('admin.schools.edit', ['title' => trans('admin.all_leads'), 'location' => $location, 'school' => $school]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\School $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $school)
    {
        $rules = [
            'name' => 'required|max:191',
            'location_id' => 'required|numeric',

        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'location_id' => trans('admin.location'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $school->name = $request->name;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->location_id = $request->location_id;
            $school->notes = $request->notes;
            $school->save();
            return redirect(adminPath() . '/schools');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\School $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        $school->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath() . '/schools');
    }
}
