<?php

namespace App\Http\Controllers;

use App\Location;
use App\Project;
use App\RentalUnit;
use App\ResaleUnit;
use Illuminate\Http\Request;
use Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::where('parent_id', '=', 0)->get();
        $alllocations = Location::get();

        return view('admin.locations.create', compact('locations', 'alllocations'));
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
        $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
            'lat' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.name'),
            'ar_name' => trans('admin.name'),
            'lat' => trans('admin.lat'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        if ($request->store == 'add') {
            $location = new Location;
            $location->en_name = $request->en_name;
            $location->ar_name = $request->ar_name;
            $location->lat = $request->lat;
            $location->lng = $request->lng;
            $location->zoom = $request->zoom;
            $location->parent_id = $request->parent_id;
            $location->save();
            return back()->with('success', trans('admin.created'));
        } elseif ($request->store == 'edit') {
            $id = $request->parent_id;
            $location = @Location::find($id);
            $location->en_name = $request->en_name;
            $location->ar_name = $request->ar_name;
            $location->lat = $request->lat;
            $location->lng = $request->lng;
            $location->zoom = $request->zoom;
            $location->save();
            return back()->with('success', trans('admin.updated'));

        } elseif ($request->store == 'move') {
            $children = HomeController::getChildren($request->parent_id);

            if (in_array($request->move_parent, $children)) {
                session()->flash('error', trans('admin.not_allowed_move'));
                return back();
            } else {
                $parent_id = $request->move_parent;
                $id = $request->parent_id;
                $location = @Location::find($id);
                $location->parent_id = $parent_id;
                $location->save();
                return back()->with('success', trans('admin.moved'));
            }


        } else {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\location $locations
     * @return \Illuminate\Http\Response
     */
    public function show(Location $locations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\location $locations
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $locations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\location $locations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    public function update1(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\location $locations
     * @return \Illuminate\Http\Response
     */

    public function destroy(Location $location)
    {

    }

    public function destroy1(Request $request)
    {
        $locations = @Location::find($request->del_loc);
        if ($locations->parent_id == 0) {
            session()->flash('error', trans('admin.not_allowed_deleted'));
            return back();
        }
        @Project::where('location_id', $locations->id)->update(['location_id' => $locations->parent_id]);
        @ResaleUnit::where('location', $locations->id)->update(['location' => $locations->parent_id]);
        @RentalUnit::where('location', $locations->id)->update(['location' => $locations->parent_id]);
        @Location::where('parent_id', $locations->id)->update(['parent_id' => $locations->parent_id]);
        $locations->delete();
        return back();
    }
	public function website_show()
	{
		$projects = Project::where('show_website',1)->get();
		$locations = Location::all();
		return view('website.locations',compact('projects','locations'));
	}
}
