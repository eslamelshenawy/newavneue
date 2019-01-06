<?php

namespace App\Http\Controllers;

use App\LeadNotification;
use Illuminate\Http\Request;
use Validator;
use App\Lead;

class LeadNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'ar_title' => 'required',
            'en_title' => 'required',
            'ar_body' => 'required',
            'en_body' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title' => trans('admin.ar_title'),
            'en_title' => trans('admin.en_title'),
            'ar_body' => trans('admin.ar_body'),
            'en_body' => trans('admin.en_body'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $leads = Lead::where('refresh_token', '!=', '')->get();
            $tokens = Lead::where('refresh_token', '!=', '')->pluck('refresh_token')->toArray();
            foreach ($leads as $lead) {
                $notification = new LeadNotification;
                $notification->ar_title = $request->ar_title;
                $notification->en_title = $request->en_title;
                $notification->ar_body = $request->ar_body;
                $notification->en_body = $request->en_body;
                $notification->type = 'others';
                $notification->type_id = 0;
                $notification->lead_id = $lead->id;
                $notification->user_id = auth()->user()->id;
                $notification->save();
            }
            $msg = array(
                'title' => $request->en_title,
                'body' => $request->en_body,
                'image' => 'myIcon',
                'sound' => 'mySound'
            );
//                dd($tokens);
            notify($tokens,$msg);
            session()->flash('success', trans('admin.sent'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeadNotification  $leadNotification
     * @return \Illuminate\Http\Response
     */
    public function show(LeadNotification $leadNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeadNotification  $leadNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(LeadNotification $leadNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeadNotification  $leadNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadNotification $leadNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeadNotification  $leadNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadNotification $leadNotification)
    {
        //
    }
}
