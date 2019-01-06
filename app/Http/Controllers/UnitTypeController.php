<?php

namespace App\Http\Controllers;

use App\UnitType;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
class UnitTypeController extends Controller
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
        $sources = UnitType::all();
//        $sources=
        return view('admin.unit_types.index', ['title' => trans('admin.all_unit_types'), 'index' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.unit_types.create', ['title' => trans('admin.add_unit_type')]);
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
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
			'usage'	=> 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
			'usage'	=> trans('admin.usage'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = new UnitType;
            $lead->en_name = $request->en_name;
            $lead->ar_name = $request->ar_name;
            $lead->usage = $request->usage;
            $lead->description = $request->description;
            $lead->user_id =Auth::user()->id;
            $lead->usage = $request->usage;
            $lead->save();

            $old_data = json_encode($lead);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $lead->ar_name,
                __('admin.created', [], 'en') . ' ' . $lead->en_name,
                'unit_types',
                $lead->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath().'/unit_types');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AgentType  $agentType
     * @return \Illuminate\Http\Response
     */
    public function show($agentType)
    {
        $show= UnitType::find($agentType);
        return view('admin.unit_types.show', ['title' => trans('admin.show_unit_type'), 'show' => $show]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgentType  $agentType
     * @return \Illuminate\Http\Response
     */
    public function edit($agentType)
    {
        $data = UnitType::find($agentType);
        return view('admin.unit_types.edit', ['title' => trans('admin.edit_unit_type'), 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgentType  $agentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $agentType)
    {
        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
        ]);;


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = UnitType::find($agentType);
            $old_data = json_encode($lead);
            $lead->en_name = $request->en_name;
            $lead->ar_name = $request->ar_name;
            $lead->save();

            $new_data = json_encode($lead);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $lead->ar_name,
                __('admin.updated', [], 'en') . ' ' . $lead->en_name,
                'unit_types',
                $lead->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            return redirect(adminPath().'/unit_types');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgentType  $agentType
     * @return \Illuminate\Http\Response
     */
    public function destroy($agentType)
    {
        $data = UnitType::find($agentType);
        $old_data = json_encode($data);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $data->ar_name,
            __('admin.deleted', [], 'en') . ' ' . $data->en_name,
            'unit_types',
            $data->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        $data->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/unit_types');
    }
}
