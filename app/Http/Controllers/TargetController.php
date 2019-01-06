<?php

namespace App\Http\Controllers;

use Validator;
use App\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
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
        $targets = Target::get();
        return view('admin.targets.index', ['title' => trans('admin.all_targets'), 'index' => $targets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.targets.create', ['title' => trans('admin.add_target')]);
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
            'agent_type_id' => 'required',
            'calls' => 'required',
            'meetings' => 'required',
            'money' => 'required',
            'month' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'agent_type_id' => trans('admin.agent_type'),
            'calls' => trans('admin.calls'),
            'meetings' => trans('admin.meetings'),
            'money' => trans('admin.money'),
            'month' => trans('admin.month'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $target = new Target;
            $target->agent_type_id = $request->agent_type_id;
            $target->calls = $request->calls;
            $target->meetings = $request->meetings;
            $target->money = $request->money;
            $target->month = $request->month;
            $target->notes = $request->notes;
            $target->user_id = auth()->user()->id;
            $target->save();

            $old_data = json_encode($target);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.target',[],'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.target',[],'en'),
                'targets',
                $target->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/targets/' . $target->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function show(Target $target)
    {
        return view('admin.targets.show', ['title' => trans('admin.show_target'), 'target' => $target]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function edit(Target $target)
    {
        return view('admin.targets.edit', ['title' => trans('admin.edit_targets'), 'target' => $target]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'agent_type_id' => 'required',
            'calls' => 'required',
            'meetings' => 'required',
            'money' => 'required',
            'month' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'agent_type_id' => trans('admin.agent_type'),
            'calls' => trans('admin.calls'),
            'meetings' => trans('admin.meetings'),
            'money' => trans('admin.money'),
            'month' => trans('admin.month'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $target = Target::find($id);
            $old_data = json_encode($target);
            $target->agent_type_id = $request->agent_type_id;
            $target->calls = $request->calls;
            $target->meetings = $request->meetings;
            $target->money = $request->money;
            $target->month = $request->month;
            $target->notes = $request->notes;
            $target->user_id = auth()->user()->id;
            $target->save();

            $new_data = json_encode($target);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . __('admin.target',[],'ar'),
                __('admin.updated', [], 'en') . ' ' . __('admin.target',[],'en'),
                'targets',
                $target->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/targets/' . $target->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
        $old_data = json_encode($target);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . __('admin.target',[],'ar'),
            __('admin.deleted', [], 'en') . ' ' . __('admin.target',[],'en'),
            'targets',
            $target->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        $target->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/targets');
    }
}
