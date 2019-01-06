<?php

namespace App\Http\Controllers;

use App\AgentType;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;

class AgentTypeController extends Controller
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
        $sources = AgentType::all();
//        $sources=
        return view('admin.agent_types.index', ['title' => trans('admin.all_agent_types'), 'index' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.agent_types.create', ['title' => trans('admin.add_agent_type')]);
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
            'name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = new AgentType;
            $lead->name = $request->name;
            $lead->description = $request->description;
            $lead->user_id =Auth::user()->id;
            $lead->save();

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($lead);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $lead->name,
                __('admin.created', [], 'en') . ' ' . $lead->name,
                'agent_types',
                $lead->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath().'/agent_types');
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
        $show= AgentType::find($agentType);
        return view('admin.agent_types.show', ['title' => trans('admin.show_agent_type'), 'show' => $show]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgentType  $agentType
     * @return \Illuminate\Http\Response
     */
    public function edit($agentType)
    {
        $data = AgentType::find($agentType);
        return view('admin.agent_types.edit', ['title' => trans('admin.edit_agent_type'), 'data' => $data]);
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
            'name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = AgentType::find($agentType);
            $old_data = json_encode($lead);
            $lead->name = $request->name;
            $lead->description = $request->description;
            $lead->save();

            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($lead);

            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $lead->name,
                __('admin.updated', [], 'en') . ' ' . $lead->name,
                'agent_types',
                $lead->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath().'/agent_types');
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
        $data = AgentType::find($agentType);

        $old_data = json_encode($data);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $data->name,
            __('admin.deleted', [], 'en') . ' ' . $data->name,
            'agent_types',
            $data->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        $data->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/agent_types');
    }
}
