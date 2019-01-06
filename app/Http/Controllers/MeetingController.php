<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\ToDo;
use App\Request as LeadReq;
use Illuminate\Http\Request;
use Validator;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('meetings') or @auth()->user()->type == 'admin') {
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
        $meetings = Meeting::get();
        return view('admin.meetings.index',['title'=> trans('admin.all_meetings'),'meetings' => $meetings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.meetings.create',['title'=> trans('admin.add_meeting')]);
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
            'lead_id' => 'required',
            'contact_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'probability' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'meeting_status_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'contact_id' => trans('admin.contact'),
            'date' => trans('admin.date'),
            'time' => trans('admin.time'),
            'duration' => trans('admin.duration'),
            'location' => trans('admin.location'),
            'probability' => trans('admin.probability'),
            'description' => trans('admin.description'),
            'meeting_status_id' => trans('admin.meeting_status'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $meeting = new Meeting;
            $meeting->lead_id = $request->lead_id;
            $meeting->contact_id = $request->contact_id;
            $meeting->date = strtotime($request->date);
            $meeting->time = $request->time;
            $meeting->probability = $request->probability;
            $meeting->description = $request->description;
            $meeting->location = $request->location;
            $meeting->duration = $request->duration;
            $meeting->meeting_status_id = $request->meeting_status_id;
            $meeting->budget = $request->budget;
            $meeting->user_id =  auth()->user()->id;
            $meeting->save();

            $lead[] = $request->lead_id;

            if ($request->has('to_do_type')){
                $todo = new ToDo;
                $todo->user_id = auth()->user()->id;
                $todo->leads = json_encode($lead);
                $todo->due_date = strtotime($request->to_do_due_date);
                $todo->to_do_type = $request->to_do_type;
                $todo->description = $request->to_do_description;
                $todo->status = 'pending';

                $todo->save();
            }

            if ($request->has('req_location')) {
                $req = new LeadReq;
                $req->lead_id = $request->lead_id;
                $req->location = $request->req_location;
                $req->down_payment = $request->req_down_payment;
                $req->area_from = $request->req_area_from;
                $req->area_to = $request->req_area_to;
                $req->price_from = $request->req_price_from;
                $req->price_to = $request->req_price_to;
                $req->date = $request->req_date;
                $req->notes =  $request->req_notes;
                $req->user_id = auth()->user()->id;
                $req->save();
            }


            $old_data = json_encode($meeting);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.meeting',[],'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.meeting',[],'en'),
                'meetings',
                $meeting->id,
                'create',
                auth()->user()->id,
                $old_data
            );


            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/meetings/' . $meeting->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meeting = Meeting::find($id);
//        dd($meeting);
        return view('admin.meetings.show',['title'=> trans('admin.meeting'),'meeting' => $meeting]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        return view('admin.meetings.edit',['title'=> trans('admin.edit_meeting'),'meeting' => $meeting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meeting $meeting)
    {
        $rules = [
            'lead_id' => 'required',
            'contact_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'probability' => 'required',
            'description' => 'required',
            'duration' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'contact_id' => trans('admin.contact'),
            'date' => trans('admin.date'),
            'time' => trans('admin.time'),
            'duration' => trans('admin.duration'),
            'location' => trans('admin.location'),
            'probability' => trans('admin.probability'),
            'description' => trans('admin.description'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($meeting);
            $meeting->lead_id = $request->lead_id;
            $meeting->contact_id = $request->contact_id;
            $meeting->date = strtotime($request->date);
            $meeting->time = $request->time;
            $meeting->probability = $request->probability;
            $meeting->description = $request->description;
            if ($request->has('projects')) {
                $meeting->projects = json_encode($request->projects);
            }else{
                $meeting->projects = '[]';
            }
            $meeting->location = $request->location;
            $meeting->duration = $request->duration;
            $meeting->user_id =  auth()->user()->id;
            $meeting->save();

            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($meeting);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . __('admin.meeting',[],'ar'),
                __('admin.updated', [], 'en') . ' ' . __('admin.meeting',[],'en'),
                'meetings',
                $meeting->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/meetings/' . $meeting->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        $old_data = json_encode($meeting);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . __('admin.meeting',[],'ar'),
            __('admin.deleted', [], 'en') . ' ' . __('admin.meeting',[],'en'),
            'meetings',
            $meeting->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        $meeting->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/meetings');
    }
}
