<?php

namespace App\Http\Controllers;

use App\Proposal;
use Illuminate\Http\Request;
use Validator;

class ProposalController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('proposals') or @auth()->user()->type == 'admin') {
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
        $proposals = Proposal::get();
        return view('admin.proposals.index', ['title' => trans('admin.all_proposals'), 'proposals' => $proposals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proposals.create', ['title' => trans('admin.add_proposal')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->unit_type != 'land'){
            $rules = [
                'unit_type' => 'required',
                'unit_id' => 'required',
                'lead_id' => 'required',
                'description' => 'required',
                'price' => 'required',
                'personal_commercial' => 'required',
            ];
        } else {
            $rules = [
                'unit_type' => 'required',
                'unit_id' => 'required',
                'lead_id' => 'required',
                'description' => 'required',
                'price' => 'required',
                // 'personal_commercial' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'unit_type' => trans('admin.unit_type'),
            'unit_id' => trans('admin.unit'),
            'lead_id' => trans('admin.lead'),
            'description' => trans('admin.description'),
            'personal_commercial' => trans('admin.personal_commercial'),
            'price' => trans('admin.price'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $proposal = new Proposal;
            $proposal->unit_type = $request->unit_type;
            if ($request->unit_type == 'land') {
                $proposal->personal_commercial = 'personal';
            } else {
                $proposal->personal_commercial = $request->personal_commercial;
            }
            $proposal->unit_id = $request->unit_id;
            $proposal->lead_id = $request->lead_id;
            $proposal->price = $request->price;
            $proposal->description = $request->description;
            $proposal->developer_id = $request->developer_id;
            $proposal->project_id = $request->project_id;
            $proposal->phase_id = $request->phase_id;
            if ($request->hasFile('file')) {
                $proposal->file = $request->file('file')->store('proposal');
            }
            $proposal->user_id = auth()->user()->id ;
            $proposal->save();

            $old_data = json_encode($proposal);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.proposal', [], 'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.proposal', [], 'en'),
                'proposals',
                $proposal->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/proposals/' . $proposal->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal)
    {
        return view('admin.proposals.show', ['title' => trans('admin.proposal'), 'proposal' => $proposal]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function edit(Proposal $proposal)
    {
        return view('admin.proposals.edit', ['title' => trans('admin.proposal'), 'proposal' => $proposal]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proposal $proposal)
    {
        $rules = [
            'unit_type' => 'required',
            'unit_id' => 'required',
            'lead_id' => 'required',
            'description' => 'required',
            'price' => 'required',
            'personal_commercial' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'unit_type' => trans('admin.unit_type'),
            'unit_id' => trans('admin.unit'),
            'lead_id' => trans('admin.lead'),
            'description' => trans('admin.description'),
            'price' => trans('admin.price'),
            'personal_commercial' => trans('admin.personal_commercial'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($proposal);
            $proposal->unit_type = $request->unit_type;
            $proposal->unit_id = $request->unit_id;
            $proposal->lead_id = $request->lead_id;
            $proposal->price = $request->price;
            $proposal->personal_commercial = $request->personal_commercial;
            $proposal->description = $request->description;
            if ($request->hasFile('file')) {
                $proposal->file = $request->file('file')->store('proposal');
            }
            $proposal->user_id = auth()->user()->id ;
            $proposal->save();

            $new_data = json_encode($proposal);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . __('admin.proposal', [], 'ar'),
                __('admin.updated', [], 'en') . ' ' . __('admin.proposal', [], 'en'),
                'proposals',
                $proposal->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/proposals/' . $proposal->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal)
    {
        $old_data = json_encode($proposal);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . __('admin.proposal', [], 'ar'),
            __('admin.deleted', [], 'en') . ' ' . __('admin.proposal', [], 'en'),
            'proposals',
            $proposal->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        $proposal->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/proposals');
    }

    public function confirm_proposal($id)
    {
        $proposal = Proposal::find($id);
        $proposal->status = 'confirmed';
        $proposal->save();
        return back();
    }
}
