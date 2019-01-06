<?php

namespace App\Http\Controllers;

use App\Facility;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Phase_Facilities;

class FacilityController extends Controller
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
        $sources = Facility::all();
//        $sources=
        return view('admin.facilities.index', ['title' => trans('admin.all') . ' ' . trans('admin.facilities'), 'facility' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.facilities.create', ['title' => trans('admin.add') . ' ' . trans('admin.facility')]);
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
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $facility = new Facility;
            $facility->en_name = $request->en_name;
            $facility->ar_name = $request->ar_name;
            $facility->en_description = $request->en_description;
            $facility->ar_description = $request->ar_description;
            $facility->icon = $request->icon;
            $facility->user_id = Auth::user()->id;
            $facility->save();

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($facility);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $facility->ar_name,
                __('admin.created', [], 'en') . ' ' . $facility->en_name,
                'facilities',
                $facility->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            return redirect(adminPath() . '/facilities');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function show(Facility $facility)
    {
        return view('admin.facilities.show', ['title' => trans('admin.show') . ' ' . trans('admin.facility'), 'facility' => $facility]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', ['title' => trans('admin.edit') . ' ' . trans('admin.facility'), 'data' => $facility]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facility $facility)
    {
        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
            'icon' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'icon' => trans('admin.icon'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($facility);
            $facility->en_name = $request->en_name;
            $facility->ar_name = $request->ar_name;
            $facility->en_description = $request->en_description;
            $facility->ar_description = $request->ar_description;
            $facility->icon = $request->icon;
            $facility->save();

            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($facility);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $facility->ar_name,
                __('admin.updated', [], 'en') . ' ' . $facility->en_name,
                'facilities',
                $facility->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            return redirect(adminPath() . '/facilities');
        }
    }

    public function destroy(Facility $facility)
    {
        $id = $facility->id;
        $phases = Phase_Facilities::where('facility_id', $id)->get();
        $repeat = [];

        if (count($phases) > 0) {
            foreach ($phases as $phase)
                array_push($repeat, $phase->phase_id);
            session()->flash('phases', $repeat);
            return back();
        } else {
            Phase_Facilities::where('facility_id', '=', $facility->id)->delete();

            $old_data = json_encode($facility);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $facility->ar_name,
                __('admin.deleted', [], 'en') . ' ' . $facility->en_name,
                'facilities',
                $facility->id,
                'delete',
                auth()->user()->id,
                $old_data
            );

            $facility->delete();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath() . '/facilities');
        }
    }
}
