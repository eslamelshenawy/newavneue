<?php

namespace App\Http\Controllers;

use App\LeadSource;
use Illuminate\Http\Request;
use Validator;
use Auth;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = LeadSource::get();
        return view('admin.lead_sources.index', ['title' => trans('admin.all_lead_sources'), 'index' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.lead_sources.create', ['title' => trans('admin.add_lead_source')]);
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
            $leadSource = new LeadSource;
            $leadSource->name = $request->name;
            $leadSource->description = $request->description;
            $leadSource->user_id = Auth::user()->id;
            $leadSource->save();
            session()->flash('success', trans('admin.created'));
            $old_data = json_encode($leadSource);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $leadSource->name,
                __('admin.created', [], 'en') . ' ' . $leadSource->name,
                'lead_sources',
                $leadSource->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath() . '/lead_sources/' . $leadSource->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeadSource $leadSource
     * @return \Illuminate\Http\Response
     */
    public function show(LeadSource $leadSource)
    {
        return view('admin.lead_sources.show', ['title' => trans('admin.show_lead_source'), 'show' => $leadSource]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeadSource $leadSource
     * @return \Illuminate\Http\Response
     */
    public function edit(LeadSource $leadSource)
    {
        if ($leadSource->id != 22 and $leadSource->id != 23 and $leadSource->id != 24) {
            return view('admin.lead_sources.edit', ['title' => trans('admin.edit_lead_source'), 'show' => $leadSource]);    
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\LeadSource $leadSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadSource $leadSource)
    {
        if ($leadSource->id != 22 and $leadSource->id != 23 and $leadSource->id != 24) {
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
                $old_data = json_encode($leadSource);
                $leadSource->name = $request->name;
                $leadSource->description = $request->description;
                $leadSource->save();
                session()->flash('success', trans('admin.updated'));
    
                $new_data = json_encode($leadSource);
    
                LogController::add_log(
                    __('admin.updated', [], 'ar') . ' ' . $leadSource->name,
                    __('admin.updated', [], 'en') . ' ' . $leadSource->name,
                    'lead_sources',
                    $leadSource->id,
                    'update',
                    auth()->user()->id,
                    $old_data,
                    $new_data
                );
    
                return redirect(adminPath() . '/lead_sources/' . $leadSource->id);
            }
        } else {
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeadSource $leadSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadSource $leadSource)
    {
        if ($leadSource->id != 22 and $leadSource->id != 23 and $leadSource->id != 24) {
            $leadSource->delete();
            session()->flash('success', trans('admin.deleted'));
            $old_data = json_encode($leadSource);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $leadSource->name,
                __('admin.deleted', [], 'en') . ' ' . $leadSource->name,
                'lead_sources',
                $leadSource->id,
                'delete',
                auth()->user()->id,
                $old_data
            );
            return redirect(adminPath() . '/lead_sources');
        } else {
            return back();
        }
    }
}