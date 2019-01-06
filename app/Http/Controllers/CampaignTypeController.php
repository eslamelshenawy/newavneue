<?php

namespace App\Http\Controllers;

use App\CampaignType;
use Illuminate\Http\Request;
use Validator;

class CampaignTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('marketing') or @auth()->user()->type == 'admin') {
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
        $type = CampaignType::all();
        return view('admin.campaign_types.index', ['title' => trans('admin.campaign_type'), 'index' => $type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.campaign_types.create', ['title' => trans('admin.add_campaign_type')]);
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
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $campaignType = new CampaignType();
            $campaignType->ar_name = $request->ar_name;
            $campaignType->en_name = $request->en_name;
            $campaignType->notes = $request->notes;
            $campaignType->user_id = auth()->user()->id;
            $campaignType->save();
            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($campaignType);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $campaignType->ar_name,
                __('admin.created', [], 'en') . ' ' . $campaignType->en_name,
                'campaign_types',
                $campaignType->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath() . '/campaign_types/' . $campaignType->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CampaignType $campaignType
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignType $campaignType)
    {
        return view('admin.campaign_types.show', ['title' => trans('admin.campaign_type'), 'show' => $campaignType]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CampaignType $campaignType
     * @return \Illuminate\Http\Response
     */
    public function edit(CampaignType $campaignType)
    {
        return view('admin.campaign_types.edit', ['title' => trans('admin.campaign_type'), 'edit' => $campaignType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\CampaignType $campaignType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CampaignType $campaignType)
    {
        $rules = [
            'en_name' => 'required',
            'ar_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($campaignType);
            $campaignType->ar_name = $request->ar_name;
            $campaignType->en_name = $request->en_name;
            $campaignType->notes = $request->notes;
            $campaignType->user_id = auth()->user()->id;
            $campaignType->save();
            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($campaignType);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $campaignType->ar_name,
                __('admin.updated', [], 'en') . ' ' . $campaignType->en_name,
                'campaign_types',
                $campaignType->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/campaign_types/' . $campaignType->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CampaignType $campaignType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampaignType $campaignType)
    {
        $campaignType->delete();
        session()->flash('success', trans('admin.deleted'));

        $old_data = json_encode($campaignType);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $campaignType->ar_name,
            __('admin.deleted', [], 'en') . ' ' . $campaignType->en_name,
            'campaign_types',
            $campaignType->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        return redirect(adminPath() . '/campaign_types');
    }
}
