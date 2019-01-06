<?php

namespace App\Http\Controllers;

use App\Call;
use App\ToDo;
use App\Request as LeadReq;
use Illuminate\Http\Request;
use Validator;
use App\Lead;
use App\User;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('calls') or @auth()->user()->type == 'admin') {
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
        $calls = Call::all();
        return view('admin.calls.index',['title'=> trans('admin.all_calls'),'calls' => $calls]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.calls.create',['title'=> trans('admin.add_call')]);
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
            'phone' => 'required',
            'duration' => 'required',
            'date' => 'required',
            'probability' => 'required',
            'description' => 'required',
            'call_status_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'contact_id' => trans('admin.contact'),
            'phone' => trans('admin.phone'),
            'duration' => trans('admin.duration'),
            'date' => trans('admin.date'),
            'probability' => trans('admin.probability'),
            'description' => trans('admin.description'),
            'call_status_id' => trans('admin.call_status'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $leadInfo=Lead::find($request->lead_id)->first();
            $call = new Call;
            $call->lead_id = $request->lead_id;
            $call->contact_id = $request->contact_id;
            $call->duration = $request->duration;
            $call->phone = $request->phone;
            $call->date = strtotime($request->date);
            $call->probability = $request->probability;
            $call->call_status_id = $request->call_status_id;
            if ($request->has('projects')) {
                $call->projects = json_encode($request->projects);
            }else{
                $call->projects = '[]';
            }
            $call->description = $request->description;
            $call->budget = $request->budget;
            $call->user_id =  auth()->user()->id;
            $call->save();

            $lead[] = $request->lead_id;

            if ($request->has('to_do_type')){
                $todo = new ToDo;
                $todo->user_id = auth()->user()->id;
                $todo->leads = $request->lead_id;
                $todo->due_date = strtotime($request->to_do_due_date);
                $todo->to_do_type = $request->to_do_type;
                $todo->description = $request->to_do_description;
                $todo->status = 'pending';
                $todo->save();
                $this->send_notification($leadInfo->agent_id,"-New Call of lead ".$leadInfo->first_name." ".$leadInfo->last_name." is added to you.");
/*               $setting = Setting::first();
                if($setting->leads_mail){
                    Config::set('mail.username', $setting->leads_mail);
                    Config::set('mail.password', $setting->leads_mail_password);
                }else{
                    //  dd(decrypt(auth()->user()->password));
                    Config::set('mail.username', auth()->user()->email);
                    Config::set('mail.password', auth()->user()->password);
                }
                Config::set('mail.port', 26);
                Config::set('mail.host', 'mail.newavenue-egypt.com');

                Mail::send('admin.leads.new_lead_mail',['lead' => $lead,'sender'=>$sender,'request'=>$request,'lead_request'=>$lead_request,'source'=>$source], function ($message) use ($agent,$sender,$request) {
                    $message->to($agent->email)->subject('New Lead')->from(auth()->user()->email, auth()->user()->name);
                });*/
            }

            if ($request->has('req_down_payment')){
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

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($call);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.call',[],'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.call',[],'en'),
                'calls',
                $call->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            return redirect(adminPath() . '/calls/' . $call->id);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call)
    {
        return view('admin.calls.show',['title'=> trans('admin.call'),'call' => $call]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call)
    {
        return view('admin.calls.edit',['title'=> trans('admin.edit_call'),'call' => $call]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Call $call)
    {
        $rules = [
            'lead_id' => 'required',
            'duration' => 'required',
            'date' => 'required',
            'probability' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'duration' => trans('admin.duration'),
            'date' => trans('admin.date'),
            'probability' => trans('admin.probability'),
            'description' => trans('admin.description'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($call);
            $call->lead_id = $request->lead_id;
            $call->duration = $request->duration;
            $call->date = strtotime($request->date);
            $call->probability = $request->probability;
            $call->description = $request->description;
            $call->projects = json_encode($request->projects);
            $call->user_id =  auth()->user()->id;
            $call->save();
            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($call);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . __('admin.call',[],'ar'),
                __('admin.updated', [], 'en') . ' ' . __('admin.call',[],'en'),
                'calls',
                $call->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/calls/' . $call->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    private function send_notification($agent_id,$body)
    {
        $tokens = User::where('refresh_token', '!=', '')->where('id', $agent_id)->pluck('refresh_token')->toArray();
        // $tokens = User::where('refresh_token', '!=', '')->pluck('refresh_token')->toArray();
        $msg = array(
            'title' => __('admin.lead_added', [], 'en'),
            'body' => $body,
            'image' => 'myIcon',/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );

        notify1($tokens, $msg);
        return 1;
    }
    public function destroy(Call $call)
    {
        $call->delete();
        session()->flash('success', trans('admin.deleted'));

        $old_data = json_encode($call);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . __('admin.call',[],'ar'),
            __('admin.deleted', [], 'en') . ' ' . __('admin.call',[],'en'),
            'calls',
            $call->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        return redirect(adminPath().'/calls');
    }
}
