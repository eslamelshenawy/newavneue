<?php

namespace App\Http\Controllers;

use App\AdminNotification;
use App\Agent;
use App\Campaign;
use App\Cil;
use App\City;
use App\Contact;
use App\Country;
use App\Developer;
use App\Facility;
use App\Favorite;
use App\Form;
use App\Setting;
use App\Industry;
use App\Interested;
use App\Lead;
use App\LeadNote;
use App\LeadSource;
use App\Location;
use App\Project;
use App\RentalUnit;
use App\ResaleUnit;
use App\Meeting;
use App\Call;
use App\User;
use Laravel\Passport\Client;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Excel;
Use Auth;
use File;
use Mail;
use Hash;
use Config;
use Intervention\Image\Facades\Image as dd;
use App\Group;
use App\Request as Req;
use App\GroupMember;
use Illuminate\Support\Facades\Input;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = new Lead;
        // return $request->all();
        if (@$request->date_from and @$request->date_to) {
            $from = date('Y-m-d', strtotime($request->date_from));
            $to = date('Y-m-d', strtotime($request->date_to));
            $query = $query->whereBetween('created_at', [$from, $to]);
        }

        if (@$request->location) {
            $requests = \App\Request::where('location', $request->location)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $requests);
        }

        if (@$request->call_status) {
            $calls = Call::where('call_status_id', $request->call_status)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $calls);
        }

        if (@$request->meeting_status) {
            $meetings = Meeting::where('meeting_status_id', $request->meeting_status)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $meetings);
        }

        if (@$request->group_id != 0) {
            $agents = GroupMember::where('group_id', $request->group_id)->pluck('member_id')->toArray();
            $query = $query->whereIn('agent_id', $agents);
        }

        $users = [];
        $user_ids = [];

        if (@$request->agent_id != 0) {
            $query = $query->where('agent_id', $request->agent_id)->orWhere('commercial_agent_id', $request->agent_id);
        } else {
            if (auth()->user()->type == 'admin' or @Group::where('team_leader_id', auth()->id())->count()) {
                $teamLeads = Lead::getAgentLeads();
                //dd($teamLeads );
                foreach ($teamLeads as $lead) {
                     if (auth()->user()->type == 'admin'){
                        $users[] = User::find($lead->agent_id);
                        $user_ids[] = $lead->agent_id;
                     }else if ($lead->agent_id != auth()->id()) {
                        $users[] = User::find($lead->agent_id);
                        $user_ids[] = $lead->agent_id;
                    }
                }
            }
            $users = array_unique($users);
            //dd($user_ids);
            $query = $query->where('agent_id','>',0)->whereIn('agent_id', $user_ids);
        }


        if (auth()->user()->type == 'admin') {
            $Agents = User::get();
        } else {
            $Agents = User::find($user_ids);
        }

        $teams = $query->paginate(10, ['*'], 'team');

        $leads = Lead::where('agent_id', auth()->id())->get();
        if (auth()->user()->type == 'admin') {
            $groups = Group::get();
        } else {
            $groups = Group::where('team_leader_id', auth()->id())->get();
        }

        // dd($users);
        return view('admin.leads.index', ['title' => trans('admin.all_leads'), 'agents' => $users, 'leads' => $leads, 'teams' => $teams->appends(Input::except('team')), 'agent_ids' => $user_ids, 'groups' => $groups, 'Agents' => $Agents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.leads.create', ['title' => trans('admin.add_lead')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'phone' => 'required|numeric|unique:leads',
            'lead_source' => 'required|max:191',
            'image' => 'image',
//            'agent_id' => 'required',
//            'commercial_agent_id' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'first_name' => trans('admin.first_name'),
            'last_name' => trans('admin.last_name'),
            'middle_name' => trans('admin.middle_name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'address' => trans('admin.address'),
            'lead_source' => trans('admin.lead_source'),
            'image' => trans('admin.image'),
            'agent_id' => trans('admin.residential_agent'),
            'commercial_agent_id' => trans('admin.commercial_agent'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = new Lead;
            $lead->prefix_name = $request->prefix_name;
            $lead->first_name = $request->first_name;
            $lead->last_name = $request->last_name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->reference = $request->reference;
            $lead->lead_source_id = $request->lead_source;
            $lead->status = 'new';
            if ($request->has('other_phones')) {
                foreach ($request->other_phones as $k => $v) {
                    $phones[] = array(
                        $request->other_phones[$k] => $request->other_socials[$k],
                    );
                }
                $lead->other_phones = json_encode($phones);
            }
            $lead->other_emails = json_encode($request->other_emails);
            $lead->notes = $request->notes;
            $lead->user_id = auth()->user()->id;
            $setting = Setting::first();
            if(auth()->user()->residential_commercial == "residential" and auth()->user()->type != 'admin'){
                 $lead->agent_id = auth()->user()->id;
            }
            elseif (auth()->user()->residential_commercial == "commercial" and auth()->user()->type != 'admin'){
                 $lead->commercial_agent_id = auth()->user()->id;
            }
            if ($request->has('agent_id')) {
                $lead->agent_id = $request->agent_id;
            } else {
                $lead->agent_id = 0;
            }

            if ($request->has('commercial_agent_id')) {
                 $lead->commercial_agent_id = $request->commercial_agent_id;

            } else {
                $lead->commercial_agent_id = 0;
            }

            if ($request->hasFile('image')) {
                $lead->image = uploads($request, 'image');
            } else {
                $lead->image = 'image.jpg';
            }
            if (!$request->has('agent_id') and !$request->has('commercial_agent_id')) {
                if(auth()->user()->residential_commercial == 'commercial'){
                     $lead->commercial_agent_id = auth()->user()->id;
                }else{
                     $lead->agent_id = auth()->user()->id;
                }
            }
            $lead->save();

            if ($request->has('notes') and $request->notes != '' and $request->notes != null) {
                $note = new LeadNote;
                $note->lead_id = $lead->id;
                $note->note = $request->notes;
                $note->user_id = auth()->id();
                $note->save();
            }

            if ($request->has('contact_name')) {
                foreach ($request->contact_name as $k => $v) {
                    $contact = new Contact;
                    $contact->lead_id = $lead->id;
                    $contact->name = $request->contact_name[$k];
                    $contact->relation = $request->contact_relation[$k];
                    $contact->nationality = $request->contact_nationality[$k];
                    $contact->title_id = $request->contact_title_id[$k];
                    $contact->email = $request->contact_email[$k];
                    $contact->other_emails = json_encode($request->contact_other_emails[$k]);
                    $contact->phone = $request->contact_phone[$k];
                    $contact->other_phones = json_encode($request->contact_other_phones[$k]);
                    $contact->social = json_encode($request->contact_social[$k]);
                    if ($request->has('contact_other_phones')) {
                        foreach ($request->contact_other_phones[$k] as $k1 => $v1) {
                            $contactPhones[] = array(
                                $request->contact_other_phones[$k][$k1] => $request->contact_other_socials[$k][$k1],
                            );
                        }
                        $contact->other_phones = json_encode($contactPhones);
                    };
                    $contact->save();
                }
            }
            $lead_request = null;
            if(isset($request->request_type)) {
                $lead_request = new Req;
                $lead_request->lead_id = $lead->id;
                $lead_request->location = $request->request_location;
                $lead_request->request_type = $request->request_type;
                $lead_request->type = $request->buyer_seller;
                $lead_request->unit_type = $request->request_unit_type;
                $lead_request->unit_type_id = $request->request_unit_type_id;
                if($request->has('request_project_id')){
                    $request_project = [];
                    foreach($request->request_project_id as $project){
                        $request_project[] = $project;
                    }
                    $request_project = json_encode($request_project);
                    $request->project_id = $request_project;
                } else {
                    $request->project_id = 0;
                }
                $lead_request->save();
                // dd($lead_request);
            }
            $source = LeadSource::find($request->lead_source)->name;
            if ($request->agent_id) {
                $notify = new AdminNotification;
                $notify->type = 'added_lead';
                $notify->type_id = $lead->id;
                $notify->status = 0;
                $notify->user_id = auth()->user()->id;
                $notify->assigned_to = $request->agent_id;
                $notify->save();
                $agent = User::find($request->agent_id);
                $lead->agent_id = $request->agent_id;
                $agent = User::find($request->agent_id);
                $sender = auth()->user();
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
                });
            }
            if ($request->commercial_agent_id) {
                $notify = new AdminNotification;
                $notify->type = 'added_lead';
                $notify->type_id = $lead->id;
                $notify->status = 0;
                $notify->user_id = auth()->user()->id;
                $notify->assigned_to = $request->commercial_agent_id;
                $notify->save();
                $agent = User::find($request->commercial_agent_id);
                $lead->commercial_agent_id = $request->commercial_agent_id;
                // dd($request->all());
                $sender = auth()->user();
                if($setting->leads_mail){
                    Config::set('mail.username', $setting->leads_mail);
                    Config::set('mail.password', $setting->leads_mail_password);
                }else{
                    Config::set('mail.username', auth()->user()->email);
                    Config::set('mail.password', auth()->user()->password);
                }
                Config::set('mail.port', 26);
                Config::set('mail.host', 'mail.newavenue-egypt.com');
                //   dd($agent);\

                Mail::send('admin.leads.new_lead_mail', ['lead' => $lead,'sender'=>$sender,'request'=>$request,'lead_request'=>$lead_request,'source'=>$source], function ($message) use ($agent,$sender,$request) {
                    $message->to($agent->email)->subject('New Lead');
                });
            }
            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($lead);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $lead->ar_first_name,
                __('admin.created', [], 'en') . ' ' . $lead->first_name,
                'leads',
                $lead->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            $notify = new AdminNotification;
            $notify->type = 'added_lead';
            $notify->type_id = $lead->id;
            $notify->status = 0;
            $notify->user_id = auth()->user()->id;
	    $notify->save();
            $this->send_notification($lead->agent_id, $lead->id);
            return redirect(adminPath() . '/leads/' . $lead->id);
        }
    }

    private function send_notification($agent_id, $lead_id)
    {
        $tokens = User::where('refresh_token', '!=', '')->where('id', $agent_id)->pluck('refresh_token')->toArray();
        // $tokens = User::where('refresh_token', '!=', '')->pluck('refresh_token')->toArray();
        $msg = array(
            'title' => __('admin.lead_added', [], 'en'),
            'body' => 'New Lead has been added to you',
            'image' => 'myIcon',/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );

        $data = [
            'lead_id' => $lead_id
        ];

        notify1($tokens, $msg, $data);
        return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        $projectPrice = Project::get()->pluck('meter_price')->toArray();
        $rentalPrice = RentalUnit::get()->pluck('rent')->toArray();
        $resalePrice = ResaleUnit::get()->pluck('price')->toArray();
        $prices = array_merge($projectPrice, $rentalPrice, $resalePrice);

        $projectAreaMin = Project::get()->pluck('area')->toArray();
        $projectAreaMax = Project::get()->pluck('area_to')->toArray();
        $rentalArea = RentalUnit::get()->pluck('area')->toArray();
        $resaleArea = ResaleUnit::get()->pluck('area')->toArray();
        $areas = array_merge($projectAreaMin, $projectAreaMax, $rentalArea, $resaleArea, $resaleArea);

        $minArea = min($areas);
        $maxArea = max($areas);

        $minPrice = min($prices);
        $maxPrice = max($prices);

        if (!$lead->seen && Lead::where('agent_id', auth()->id())->where('id',$lead->id)) {
            $lead->seen = 1;
            $lead->save();
        }

        return view('admin.leads.show', [
            'title' => trans('admin.show_lead'),
            'show' => $lead,
            'minArea' => $minArea,
            'maxArea' => $maxArea,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function edit($lead)
    {
        $data = Lead::find($lead);
        return view('admin.leads.edit', ['title' => trans('admin.edit_lead'), 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lead)
    {
        $rules = [
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'phone' => 'required|numeric|unique:leads,phone,' . $lead . ',id',
            'lead_source' => 'required|max:191',
            'image' => 'image',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'first_name' => trans('admin.first_name'),
            'last_name' => trans('admin.last_name'),
            'middle_name' => trans('admin.middle_name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'address' => trans('admin.address'),
            'lead_source' => trans('admin.lead_source'),
            'image' => trans('admin.image'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = Lead::find($lead);
            $old_data = json_encode($lead);
            $lead->prefix_name = $request->prefix_name;
            $lead->first_name = $request->first_name;
            $lead->last_name = $request->last_name;
            $lead->middle_name = $request->middle_name;
            $lead->ar_first_name = $request->ar_first_name;
            $lead->ar_last_name = $request->ar_last_name;
            $lead->ar_middle_name = $request->ar_middle_name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->nationality = $request->nationality;
            $lead->country_id = $request->country_id;
            $lead->city_id = $request->city_id;
            $lead->address = $request->address;
            $lead->club = $request->club;
            $lead->title_id = $request->title_id;
            $lead->religion = $request->religion;
            $lead->birth_date = strtotime($request->birth_date);
            $lead->lead_source_id = $request->lead_source;
            $lead->social = json_encode($request->social);
            $lead->industry_id = $request->industry_id;
            $lead->company = $request->company;
            $lead->school = $request->school;
            $lead->facebook = $request->facebook;
            $lead->id_number = $request->id_number;
            $lead->status = 'new';
            if ($request->has('other_phones')) {
                foreach ($request->other_phones as $k => $v) {
                    $phones[] = array(
                        $request->other_phones[$k] => $request->other_socials[$k],
                    );
                }
                $lead->other_phones = json_encode($phones);
            }
            $lead->other_emails = json_encode($request->other_emails);
            $lead->notes = $request->notes;
            $lead->user_id = auth()->user()->id;
            if ($request->hasFile('image')) {
                $lead->image = uploads($request, 'image');
            }
            $lead->save();
            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($lead);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $lead->ar_first_name,
                __('admin.updated', [], 'en') . ' ' . $lead->first_name,
                'leads',
                $lead->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            return redirect(adminPath() . '/leads/' . $lead->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy($lead)
    {
        $data = Lead::find($lead);



        $file_path = url('uploads/' . @$data->image);
        if (@$data->image != 'image.jpg' and @$data->image != 'image.ico' and file_exists($file_path)) {
            @unlink($file_path);
        }
        $old_data = json_encode($data);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . @$data->ar_first_name,
            __('admin.deleted', [], 'en') . ' ' . @$data->first_name,
            'leads',
            $data->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        if (checkRole('hard_delete_leads')) {
            DB::table('calls')->where('lead_id',$lead)->delete();
            DB::table('cils')->where('lead_id',$lead)->delete();
            DB::table('contacts')->where('lead_id',$lead)->delete();
            DB::table('contracts')->where('lead_id',$lead)->delete();
            DB::table('favorites')->where('lead_id',$lead)->delete();
            DB::table('lands')->where('lead_id',$lead)->delete();
            DB::table('lead_actions')->where('lead_id',$lead)->delete();
            DB::table('lead_documents')->where('lead_id',$lead)->delete();
            DB::table('lead_notes')->where('lead_id',$lead)->delete();
            DB::table('lead_notifications')->where('lead_id',$lead)->delete();
            DB::table('massages')->where('lead_id',$lead)->delete();
            DB::table('meetings')->where('lead_id',$lead)->delete();
            DB::table('proposals')->where('lead_id',$lead)->delete();
            DB::table('recent_vieweds')->where('lead_id',$lead)->delete();
            DB::table('requests')->where('lead_id',$lead)->delete();
            DB::table('to_dos')->where('leads',$lead)->delete();
            DB::table('to_dos')->where('leads',$lead)->delete();
            $data->delete();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath() . '/leads');
        } else if (checkRole('soft_delete_leads')) {
            $data->agent_id = 0;
            $data->save();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath() . '/leads');
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath() . '/leads');
    }

    public function upload_file()
    {
        return view('admin.leads.create2', ['title' => trans('admin.add_lead')]);
    }

    public function upload_excel(Request $request)
    {
        // return File::mimeType($_FILES['xls']['tmp_name']);
//        dd($request->file('xls')->getClientOriginalExtension());
        $rules1 = [
            'lead_source' => 'required',
            'xls' => 'file|max:100|mimeTypes:' .
                'application/vnd.ms-office,' .
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,' .
                'application/vnd.ms-excel',
        ];
        $validator1 = Validator::make($request->all(), $rules1);
        $validator1->SetAttributeNames([
            'lead_source' => trans('admin.lead_source'),
            'xls' => trans('admin.xls'),
        ]);
        if ($validator1->fails()) {
            return back()->withInput()->withErrors($validator1);
        } else {

            $source = $request->lead_source;
            $path = $request->file('xls')->getRealPath();
            Excel::load($path, function ($reader) use ($source, $path) {
                $array = $reader->toArray();
                foreach ($array as $item) {

                    if (isset($item['phone'])) {

                        $check1 = Lead::where('phone', $item['phone'])->get();

                        if (count($check1) < 1) {
                            $rules = [
                                'first_name' => 'required|max:191',
                                'last_name' => 'required|max:191',
                                'gender' => 'required',
                                'phone' => 'required',
                            ];

                            $validator = Validator::make($item, $rules);

                            if (!$validator->fails()) {
                                $data = new Lead();
                                if (isset($item['email'])) {

                                    $check2 = Lead::where('phone', $item['phone'])->get();
                                    if (count($check2) < 1) {
                                        $validator = Validator::make(['email'],
                                            ['email' => 'required|email']);
                                        if (!$validator->fails()) {
                                            $data->email = $item['email'];
                                        }
                                    }

                                }
                                // if (count($campaign = Campaign::where('en_name', $item['campaign'])->orWhere('ar_name', $item['campaign'])->first()) > 0) {
                                $data->first_name = $item['first_name'];
                                $data->last_name = $item['last_name'];
                                $data->phone = $item['phone'];
                                $data->lead_source_id = $source;
                                $data->campain_id = 0;
                                if ($item['gender'] == 'female') {
                                    $data->prefix_name = 'ms';
                                } else {
                                    $data->prefix_name = 'mr';
                                }
                                $data->agent_id = Auth::user()->id;
                                $data->user_id = 0;
                                $data->save();
                                // }
                            }

                        }
                    }
                }

            });

            return redirect(adminPath() . '/leads');
        }
    }

    public function login(Request $request)
    {
        $client = Client::find(3);
        $request->request->add(
            [
                'username' => $request->email,
                'password' => $request->password,
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'grant_type' => 'password',
                'response_type' => 'code',
                'scope' => '',
            ]
        );
        $leads = @Lead::where('email', $request->email)->first();
        if (count($leads) > 0) {
            $tokenRequest = Request::create(
                env('APP_URL') . '/oauth/token',
                'post'
            );
            $response = json_decode(Route::dispatch($tokenRequest)->getContent());
            if (@$response->access_token) {
                return ['status' => true, $response];
            } else {
                return ['status' => 'no token'];
            }
        } else {
            return ['status' => 'no lead'];
        }

    }

    public function send_cil(Request $request)
    {
        $lead = @Lead::find($request->lead_id);
        $file = $request->file;
        $project = 0;
//        Config::set('mail.port', 587);

        for ($x = 0; $x < count($request->developers); $x++) {

            $cil = new Cil;
            $cil->lead_id = $request->lead_id;
            $cil->developer_id = $request->developers[$x];
            $cil->status = 'pending';
            $cil->save();

            $developer = @Developer::find($request->developers[$x]);
            if ($request->projects[$x]) {
                $project = $request->projects[$x];
            }
            Mail::send('admin.leads.cil', ['lead' => $lead,
                'project' => $project,
                'file' => $file], function ($message) use ($developer) {
                $message->to($developer->email)->subject('CIL');
            });
        }
        session()->flash('success', trans('admin.sent'));
        return back();
    }

    public function website_login(Request $request)
    {
        $rules = [
            'email' => 'email|required',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if (auth()->guard('lead')->attempt(['email' => $request->email, 'password' => $request->password])) {
//              dd($request->all());
                return redirect()->intended('/');
            } else {
//              dd($request->all());
                session()->flash('error', trans('error'));
                return redirect('login');
            }
        }
    }

    public function website_logout()
    {
        auth('lead')->logout();
        return redirect('/');
    }

    public function add_lead(Request $request)
    {

        $rules = [
            'email' => 'email',
            'password' => 'required',
            'passwordConfirm' => 'same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([

        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $phone_exist = Lead::where('phone', $request->phone)->count();
            $email_exist = Lead::where('email', $request->email)->count();
            if ($phone_exist > 0 || $email_exist > 0) {
                if ($phone_exist > 0) {
                    $connected_lead = Lead::where('phone', $request->phone)->first();
                    $connected_lead->confirm = 1;
                    $connected_lead->password = bcrypt($request->password);
                    $connected_lead->last_name = $request->lName;
                    $connected_lead->middle_name = $request->mName;
                    $connected_lead->first_name = $request->fName;
                    $connected_lead->save();
                }
                if ($email_exist > 0) {
                    $connected_lead = Lead::where('email', $request->email)->first();
                    $connected_lead->confirm = 1;
                    $connected_lead->password = bcrypt($request->password);
                    $connected_lead->last_name = $request->lName;
                    $connected_lead->middle_name = $request->mName;
                    $connected_lead->first_name = $request->fName;
                    $connected_lead->save();

                }
            } else {

                $lead = new Lead;
                $lead->first_name = $request->fName;
                if ($request->mName != null) {
                    $lead->middle_name = $request->mName;
                }
                $lead->last_name = $request->lName;
                $lead->phone = $request->phone;
                $lead->email = $request->email;
                $lead->password = bcrypt($request->password);
                $lead->lead_source_id = 0;
                $lead->agent_id = 0;
                $lead->user_id = 0;
                $lead->save();
                if (auth()->guard('lead')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return redirect()->intended('/');
                } else {
                    return redirect('login');
                }
            }
            return redirect('/');
        }
    }

    public function switch_leads(Request $request)
   {
       $rules = [
           'agent_id' => 'required|max:191',
           'leads' => 'required',

       ];
       $validator = Validator::make($request->all(), $rules);
       $validator->SetAttributeNames([
           'agent_id' => trans('admin.agent'),
           'leads' => trans('admin.leads'),
       ]);
       if ($validator->fails()) {
           return back()->withInput()->withErrors($validator);
       } else {
           $leads = array_unique($request->leads);
           //dd($leads);
           foreach ($leads as $lead) {
               $m = Lead::find($lead);
               $m->agent_id = $request->agent_id;
               if($request->has('commercial_agent_id')){
                    $m->commercial_agent_id = $request->commercial_agent_id;
               }
               $m->save();
           }
           $body = "";
           if (count($leads) > 1) {
               $data = 'bulk';
               $body = "Yohoo .. New bulk leads has been switched to you";
               $dataId = '0';
               $content = [
               'type' => 'lead',
               'id'=> $leads,
               'lead_id'=>"",
               'content-available'=>1,
               ];
           } else {
               $l = Lead::find($leads[0]);
               $data = __('admin.' . $l->prefix_name) . ' ' . $l->first_name;
               $body = 'Yohoo .. New lead ( '. $data .' ) has been switched to you';
               $dataId = $leads[0];
               $content = [
                   'type' => 'lead',
                   'id'=> $leads,
                   'content-available'=>1,
                   'lead_id' => $l->id];
           }

           $agent = @User::find($request->agent_id);
           $agent_name = @$agent->name;

           $notification = auth()->user()->name . ' ' . __('admin.has_switched') . ' ' . $data . ' ' . __('admin.to') . ' ' . $agent_name;

           $not = new AdminNotification;
           $not->user_id = auth()->user()->id;
           $not->assigned_to = $request->agent_id;
           $not->type = 'switch';
           $not->type_id = $dataId;
           $not->save();
           $tokens = User::where('refresh_token', '!=', '')->where('id', $request->agent_id)->pluck('refresh_token')->toArray();
           $msg = array(
               'title' => __('admin.leads', [], 'en'),
               'body' => $body,
               'image' => 'myIcon',/*Default Icon*/
               'sound' => 'res_notif_sound'
           );

           try {
               $res = notify1($tokens, $msg, $content);
               $leadsData = @Lead::find($leads);
               $source = @LeadSource::find($leadsData[0]->lead_source_id)->name;
               foreach ($leadsData as $leadData) {
                   if ($leadData->email) {
                       if (filter_var($leadData->email, FILTER_VALIDATE_EMAIL)) {
                           Mail::send('admin.leads.lead_switch_lead', ['lead' => $leadData, 'agent' => $agent], function ($message) use ($leadData, $agent) {
                               $message->to(@$leadData->email)->subject('You have been switched to ' . $agent->name);
                           });
                       }
                   }
               }

               if (filter_var($agent->email, FILTER_VALIDATE_EMAIL)) {
                   Mail::send('admin.leads.agent_switch_lead', ['leads' => $leadsData, 'agent' => $agent,'source'=>$source], function ($message) use ($agent) {
                       $message->to(@$agent->email)->subject('These leads have been switched to you');
                   });
               }

           } catch (\Swift_TransportException $e) {
               session()->flash('success', trans('admin.switched'));
               session()->flash('notification', $notification);
               session()->flash('assigned_to', $request->agent_id);
               return back();
           }


           session()->flash('success', trans('admin.switched'));
           session()->flash('notification', $notification);
           session()->flash('assigned_to', $request->agent_id);
           return back();
       }
   }
    public function update_lead(Request $request)
    {
        $new_cities = '';
        $id = $request->id;
        $type = $request->type;
        $value = $request->value;

        if ($type == 'birth_date') {
            $value = strtotime($request->value);
        }
        $lead = Lead::find($id);
        $lead->$type = $value;
        $lead->save();
        if ($type == 'nationality') {
            $value = Country::find($value)->name;
        }
        if ($type == 'industry_id') {
            $value = Industry::find($value)->name;
        }
        if ($type == 'religion') {
            $value = __('admin.' . $value);
        }
        if ($type == 'birth_date') {
            $value = date('Y/m/d', $value);
        }
        if ($type == 'facebook') {
            $value = '<a href="https://www.facebook.com/' . $value . '" target="_blank"><b><i class="fa fa-facebook" aria-hidden="true"></i></b></a>';
        }
        if ($type == 'country_id') {
            $new_cities = '';
            $new_cities .= '<option></option>';
            foreach (City::where('country_id', $value)->get() as $country) {
                $new_cities .= '<option value="' . $country->id . '">' . $country->name . '</option>';
            }
            $value = Country::find($value)->name;
        }
        if ($type == 'city_id') {
            $value = City::find($value)->name;
        }

        return response()->json([
            'value' => $value,
            'type' => $type,
            'new_cities' => $new_cities,
        ]);
    }

    public function website_profile()
    {
        $lead = Lead::find(auth('lead')->user()->id);
        return view('website.profile', compact('lead'));
    }

    public function website_profile_update(Request $request)
    {
        $rules = [
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|email',
            'phone' => 'required|numeric|unique:leads,phone,' . auth('lead')->user()->id . ',id',
            'image' => 'image',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'first_name' => trans('admin.first_name'),
            'last_name' => trans('admin.last_name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'image' => trans('admin.image'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = Lead::find(auth('lead')->user()->id);
            $lead->first_name = $request->first_name;
            $lead->middle_name = $request->middle_name;
            $lead->last_name = $request->last_name;
            $lead->phone = $request->phone;
            $lead->email = $request->email;
            if ($request->has('image')) {
                $lead->image = $request->file('image')->store('uploads');
            }
            $lead->save();
            return back();
        }
    }

    public function change_password(Request $request)
    {
        $lead = Lead::find(auth('lead')->user()->id);
        if ($request->password == $request->confirm_password) {
            dump($request->current_password);
            if (Hash::check($request->current_password, $lead->password)) {
                $lead->password = bcrypt($request->password);
                $lead->save();
                return back();
            } else {
                return 'not';
            }
        } else {
            return back();
        }
    }

    public function lead_properties()
    {
        if (auth('lead')->user()) {
            $resale = ResaleUnit::where('lead_id', auth('lead')->user->id)->get();
            $rental = ResaleUnit::where('lead_id', auth('lead')->user->id)->get();
            return view('website.my_properties', compact('rental', 'resale'));
        } else {
            return redirect('lead_login');
        }
    }

    public function add_properties()
    {
        if (auth('lead')->user()) {
            $facilities = @Facility::all();
            $locations = Location::all();
            return view('website.add_properties', compact('facilities', 'locations'));
        } else {
            return redirect('lead_login');
        }
    }

    public function interested(Request $request)
    {
        if (auth('lead')->guest()) {
            $rules = [
                'first_name' => 'required|max:191',
                'last_name' => 'required|max:191',
                'phone' => 'required|numeric|unique:leads',
                'email' => 'required|email|unique:leads',
                'image' => 'image',

            ];
            $validator = Validator::make($request->all(), $rules);
            $validator->SetAttributeNames([
                'first_name' => trans('admin.first_name'),
                'last_name' => trans('admin.last_name'),
                'middle_name' => trans('admin.middle_name'),
                'email' => trans('admin.email'),
                'phone' => trans('admin.phone'),
            ]);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            } else {
                $lead = new Lead();
                $lead->first_name = $request->first_name;
                $lead->last_name = $request->last_name;
                $lead->phone = $request->phone;
                $lead->lead_source_id = 0;
                $lead->agent_id = $request->agent_id;
                $lead->user_id = $request->user_id;
                $lead->save();
                $interest = new Interested();
                $interest->lead_id = $lead->id;
                $interest->unit_id = $request->project_id;
                $interest->type = $request->type;
                $interest->save();
                $noti = new AdminNotification;
                $noti->user_id = 0;
                $noti->assigned_to = $lead->agent_id;
                $noti->type = 'interest';
                $noti->type_id = $interest->id;
                $noti->status = 0;
                $noti->save();
                return back();

            }
        } else {
            if (!Interested::where('lead_id', auth('lead')->user()->id)->where('unit_id', $request->project_id)->where('type', $request->type)->first()) {
                $interest = new Interested();
                $interest->lead_id = auth('lead')->user()->id;
                $interest->unit_id = $request->project_id;
                $interest->type = $request->type;
                $interest->save();
                $noti = new AdminNotification;
                $noti->user_id = 0;
                $noti->assigned_to = auth('lead')->user()->agent_id;
                $noti->type = 'interest';
                $noti->type_id = $interest->id;
                $noti->status = 0;
                $noti->save();
                return back();
            } else {
                session('error', 'already there');
                return back();
            }
        }
    }

    public function favorite()
    {
        $favorites = Favorite::where('lead_id', auth('lead')->user()->id)->get();
        return view('website.favorite_properties', compact('favorites'));
    }

    public function leads_ajax(Request $request)
    {
/*        if($request->seen=='seen'){
            $leads = Lead::where('agent_id', auth()->id())->where('seen','>',0)->get();
        }else{
            $leads = Lead::where('agent_id', auth()->id())->where('seen',0)->get();
        }*/
         $leads = Lead::where('agent_id', auth()->id())->orderBy('seen')->get();

        foreach ($leads as $lead) {
            $seen = 'not_seen';
            $sColor = 'red';

            if ($lead->seen) {
                $seen = 'seen_without_action';
                $sColor = 'orange';
                $lead->seen=1;
                if (DB::table('lead_actions')->where('lead_id', $lead->id)->count()) {
                    $seen = 'seen_with_action';
                    $sColor = 'green';
                    $lead->seen=2;
                }
            }else{
            $lead->seen=0;
            }
            //$lead->seen = '<i class="fa fa-circle" aria-hidden="true" style="color: ' . $sColor . '"></i>';


            $lastCall = Call::where('lead_id', $lead->id)->orderBy('id', 'desc')->first();
            $lastMeeting = Meeting::where('lead_id', $lead->id)->orderBy('id', 'desc')->first();

            if (@$lastCall->created_at->timestamp > @$lastMeeting->created_at->timestamp) {
                @$leadProbability = $lastCall->probability;
            } else {
                @$leadProbability = $lastMeeting->probability;
            }

            if (!$leadProbability) {
                $leadProbability = 'lowest';
            }
            $lead->probability = __('admin.' . $leadProbability);

            $lead->checkbox = '<div class="checkbox">
                                    <label>
                                        <input class="switch" name="checked_leads[]" type="checkbox"
                                               value="' . $lead->id . '">
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>';
            $commercialAgents = User::where('residential_commercial', 'commercial')->pluck('id')->toArray();
            $residentialAgents = User::where('residential_commercial', 'residential')->pluck('id')->toArray();
            $color ='';
            if (DB::table('lead_actions')->whereIn('user_id', $commercialAgents)->where('lead_id', $lead->id)->count() > 0) {
                $color =  'color:green;';
            } else {
                $color = 'color:red';
            }
            $color2 ='';
            if (DB::table('lead_actions')->whereIn('user_id', $residentialAgents)->where('lead_id', $lead->id)->count() > 0) {
                $color2 =  'color:green;';
            } else {
                $color2 = 'color:red';
            }
            $lead->commercial_status = "<i class='fa fa-circle' aria-hidden='true' style='{$color}'></i>";
            $lead->personal_status = "<i class='fa fa-circle' aria-hidden='true' style='{$color2}'></i>";
            $lead->name = $lead->first_name . ' ' . $lead->last_name;
            $lead->email = '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>';
            $lead->source = @LeadSource::find($lead->lead_source_id)->name;
            $lead->agent = @User::find($lead->agent_id)->name;

            if (@Req::where('lead_id', $lead->id)->where('unit_type', 'personal')->where('unit_type', 'commercial')->count() > 0) {
                $leadType = __('admin.personal') . ' - ' . __('admin.commercial');
            } else if (@Req::where('lead_id', $lead->id)->where('unit_type', 'personal')->where('unit_type', '!=', 'commercial')->count() > 0) {
                $leadType = __('admin.personal');
            } else if (@Req::where('lead_id', $lead->id)->where('unit_type', '!=', 'personal')->where('unit_type', 'commercial')->count() > 0) {
                $leadType = __('admin.commercial');
            } else {
                $leadType = __('admin.personal');
            }

            $lead->type = $leadType;


            if ($lead->favorite)
                $color = 'color: #caa42d';
            else
                $color = '';

            $lead->fav = '<i class="fa fa-star Fav" id="Fav' . $lead->id . '" count="' . $lead->id . '" style="' . $color . '"></i>';

            if ($lead->hot)
                $color = 'color: #dd4b39';
            else
                $color = '';

            $lead->hot = '<i class="fa fa-fire Hot" id="Hot' . $lead->id . '" count="' . $lead->id . '" style="' . $color . '"></i>';
            $lead->option='<select class="form-control"  onchange="'."if(this.value=='del'){\$('#delete$lead->id').modal();} else{location = this.value;}".'">
            <option value="#" disabled selected >Options</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id) . '">' . trans('admin.show') . '</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '">' . trans('admin.edit') . '</option>
            <option value="del" class="delete" data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</option>
            </select>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>
            ';
            /*$lead->show = '<a href="' . url(adminPath() . '/leads/' . $lead->id) . '" class="btn btn-primary btn-flat">' . trans('admin.show') . '</a>';
            $lead->edit = '<a href="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '" class="btn btn-warning btn-flat"> ' . trans('admin.edit') . '</a>';
            $lead->delete = '<a data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</a>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>';*/
            $agents = '';
            foreach (User::get() as $agent) {
                $agents .= '<option value="' . $agent->id . '">' . $agent->name . '</option>';
            }
            $lead->switch = '<a data-toggle="modal" data-target="#switch' . $lead->id . '" class="btn btn-success btn-flat">' . trans('admin.switch') . '</a>
                        <div id="switch' . $lead->id . '" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">' . trans('admin.switch') . ' ' . trans('admin.lead') . '</h4>
                                    </div>
                                    <form action="' . url(adminPath() . '/switch_leads') . '" method="post">
                                    ' . csrf_field() . '
                                    <div class="modal-body">
                                        <select class="select2" name="agent_id"
                                                data-placeholder="' . __('admin.agent') . '" style="width: 100%">
                                            <option></option>
                                            ' . $agents . '
                                        </select>
                                        <input type="hidden" value="' . $lead->id . '" name="leads[]">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">' . trans('admin.close') . '</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">' . trans('admin.switch') . '</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <script>
                        $(".select2").select2();
</script>';
        }
        return \DataTables::of($leads)->
        escapeColumns('')->
        make(true);
    }

    public function team_leads_ajax()
    {
        $leads = Lead::getTeamLeads();

        foreach ($leads as $lead) {
            $lead->checkbox = '<div class="checkbox">
                                    <label>
                                        <input class="switch" name="checked_leads[]" type="checkbox"
                                               value="' . $lead->id . '">
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>';
            $lead->name = $lead->first_name . ' ' . $lead->last_name;
            $lead->email = '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>';
            $lead->source = @LeadSource::find($lead->lead_source_id)->name;
            $lead->agent = @User::find($lead->agent_id)->name;

            if ($lead->favorite)
                $color = 'color: #caa42d';
            else
                $color = '';

            $lead->fav = '<i class="fa fa-star Fav" id="Fav' . $lead->id . '" count="' . $lead->id . '" style="' . $color . '"></i>';

            if ($lead->hot)
                $color = 'color: #dd4b39';
            else
                $color = '';

            $lead->hot = '<i class="fa fa-fire Hot" id="Hot' . $lead->id . '" count="' . $lead->id . '" style="' . $color . '"></i>';
            $lead->option='<select class="form-control"  onchange="'."if(this.value=='del'){\$('#delete$lead->id').modal();} else{location = this.value;}".'">
            <option value="#" disabled selected >Options</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id) . '">' . trans('admin.show') . '</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '">' . trans('admin.edit') . '</option>
            <option value="del" class="delete" data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</option>
            </select>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>
            ';
/*            $lead->show = '<a href="' . url(adminPath() . '/leads/' . $lead->id) . '" class="btn btn-primary btn-flat">' . trans('admin.show') . '</a>';
            $lead->edit = '<a href="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '" class="btn btn-warning btn-flat"> ' . trans('admin.edit') . '</a>';
            $lead->delete = '<a data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</a>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>';*/
            $agents = '';
            foreach (User::get() as $agent) {
                $agents .= '<option value="' . $agent->id . '">' . $agent->name . '</option>';
            }
            $lead->switch = '<a data-toggle="modal" data-target="#switch' . $lead->id . '" class="btn btn-xs btn-success btn-flat">' . trans('admin.switch') . '</a>
                        <div id="switch' . $lead->id . '" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">' . trans('admin.switch') . ' ' . trans('admin.lead') . '</h4>
                                    </div>
                                    <form action="' . url(adminPath() . '/switch_leads') . '" method="post">
                                    ' . csrf_field() . '
                                    <div class="modal-body">
                                        <select class="select2" name="agent_id"
                                                data-placeholder="' . __('admin.agent') . '" style="width: 100%">
                                            <option></option>
                                            ' . $agents . '
                                        </select>
                                        <input type="hidden" value="' . $lead->id . '" name="leads[]">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">' . trans('admin.close') . '</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">' . trans('admin.switch') . '</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <script>
                        $(".select2").select2();
        </script>';
        }
        return \DataTables::of($leads)->
        escapeColumns('')->
        make(true);
    }

    public function leads_ind_ajax()
    {
        if (auth()->user()->type == 'admin') {
            $leads = @Lead::where('agent_id', 0)->orWhere('agent_id', null)->get();

            foreach ($leads as $lead) {

                $lead->checkbox = '<div class="checkbox">
                                    <label>
                                        <input class="switch" name="checked_leads[]" type="checkbox"
                                               value="' . $lead->id . '">
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>';

                $lead->name = $lead->first_name . ' ' . $lead->last_name;
                $lead->email = '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>';
                $lead->source = @LeadSource::find($lead->lead_source_id)->name;
                $lead->option='<select class="form-control"  onchange="'."if(this.value=='del'){\$('#delete$lead->id').modal();} else{location = this.value;}".'">
                <option value="#" disabled selected >Options</option>
                <option value="' . url(adminPath() . '/leads/' . $lead->id) . '">' . trans('admin.show') . '</option>
                <option value="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '">' . trans('admin.edit') . '</option>
                <option value="del" class="delete" data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</option>
                </select>
                    <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                                </div>
                                <div class="modal-body">
                                    <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-flat"
                                            data-dismiss="modal">' . trans('admin.close') . '</button>
                                    <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                                </div>
                            </div>

                        </div>
                    </div>
                ';
/*              $lead->show = '<a href="' . url(adminPath() . '/leads/' . $lead->id) . '" class="btn btn-primary btn-flat">' . trans('admin.show') . '</a>';
                $lead->edit = '<a href="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '" class="btn btn-warning btn-flat"> ' . trans('admin.edit') . '</a>';
                $lead->delete = '<a data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</a>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>';*/
                 $agents = '';
            foreach (User::get() as $agent) {
                $agents .= '<option value="' . $agent->id . '">' . $agent->name . '</option>';
            }
            $lead->switch = '<a data-toggle="modal" data-target="#switch' . $lead->id . '" class="btn btn-xs btn-success btn-flat">' . trans('admin.switch') . '</a>
                        <div id="switch' . $lead->id . '" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">' . trans('admin.switch') . ' ' . trans('admin.lead') . '</h4>
                                    </div>
                                    <form action="' . url(adminPath() . '/switch_leads') . '" method="post">
                                    ' . csrf_field() . '
                                    <div class="modal-body">
                                        <select class="select2" name="agent_id"
                                                data-placeholder="' . __('admin.agent') . '" style="width: 100%">
                                            <option></option>
                                            ' . $agents . '
                                        </select>
                                        <input type="hidden" value="' . $lead->id . '" name="leads[]">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">' . trans('admin.close') . '</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">' . trans('admin.switch') . '</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <script>
                        $(".select2").select2();
            </script>';
            }
            return \DataTables::of($leads)->
            escapeColumns('')->
            make(true);
        }
    }

    public function leads_fav_ajax()
    {
        if (auth()->user()->type != 'admin')
            $leads = Lead::where('agent_id', auth()->user()->id)->where('favorite', 1)->get();
        else
            $leads = Lead::where('favorite', 1)->get();

        foreach ($leads as $lead) {
            $lead->checkbox = '<div class="checkbox">
                                    <label>
                                        <input class="switch" name="checked_leads[]" type="checkbox"
                                               value="' . $lead->id . '">
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>';
            $lead->name = $lead->first_name . ' ' . $lead->last_name;
            $lead->email = '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>';
            $lead->source = @LeadSource::find($lead->lead_source_id)->name;
            $lead->option='<select class="form-control"  onchange="'."if(this.value=='del'){\$('#delete$lead->id').modal();} else{location = this.value;}".'">
            <option value="#" disabled selected >Options</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id) . '">' . trans('admin.show') . '</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '">' . trans('admin.edit') . '</option>
            <option value="del" class="delete" data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</option>
            </select>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>
            ';
            /*$lead->show = '<a href="' . url(adminPath() . '/leads/' . $lead->id) . '" class="btn btn-primary btn-flat">' . trans('admin.show') . '</a>';
            $lead->edit = '<a href="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '" class="btn btn-warning btn-flat"> ' . trans('admin.edit') . '</a>';
            $lead->delete = '<a data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</a>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>';*/
            $agents = '';
            foreach (User::get() as $agent) {
                $agents .= '<option value="' . $agent->id . '">' . $agent->name . '</option>';
            }
            $lead->switch = '<a data-toggle="modal" data-target="#switch' . $lead->id . '" class="btn btn-xs btn-success btn-flat">' . trans('admin.switch') . '</a>
                        <div id="switch' . $lead->id . '" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">' . trans('admin.switch') . ' ' . trans('admin.lead') . '</h4>
                                    </div>
                                    <form action="' . url(adminPath() . '/switch_leads') . '" method="post">
                                    ' . csrf_field() . '
                                    <div class="modal-body">
                                        <select class="select2" name="agent_id"
                                                data-placeholder="' . __('admin.agent') . '" style="width: 100%">
                                            <option></option>
                                            ' . $agents . '
                                        </select>
                                        <input type="hidden" value="' . $lead->id . '" name="leads[]">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">' . trans('admin.close') . '</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">' . trans('admin.switch') . '</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <script>
                        $(".select2").select2();
        </script>';
        }
        return \DataTables::of($leads)->
        escapeColumns('')->
        make(true);
    }

    public function leads_hot_ajax()
    {
        if (auth()->user()->type != 'admin')
            $leads = Lead::where('agent_id', auth()->user()->id)->where('hot', 1)->get();
        else
            $leads = Lead::where('favorite', 1)->get();

        foreach ($leads as $lead) {
            $lead->checkbox = '<div class="checkbox">
                                    <label>
                                        <input class="switch" name="checked_leads[]" type="checkbox"
                                               value="' . $lead->id . '">
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>';
            $lead->name = $lead->first_name . ' ' . $lead->last_name;
            $lead->email = '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>';
            $lead->source = @LeadSource::find($lead->lead_source_id)->name;
            $lead->option='<select class="form-control"  onchange="'."if(this.value=='del'){\$('#delete$lead->id').modal();} else{location = this.value;}".'">
            <option value="#" disabled selected >Options</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id) . '">' . trans('admin.show') . '</option>
            <option value="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '">' . trans('admin.edit') . '</option>
            <option value="del" class="delete" data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</option>
            </select>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>
            ';
/*            $lead->show = '<a href="' . url(adminPath() . '/leads/' . $lead->id) . '" class="btn btn-primary btn-flat">' . trans('admin.show') . '</a>';
            $lead->edit = '<a href="' . url(adminPath() . '/leads/' . $lead->id . '/edit') . '" class="btn btn-warning btn-flat"> ' . trans('admin.edit') . '</a>';
            $lead->delete = '<a data-toggle="modal" data-target="#delete' . $lead->id . '" class="btn btn-danger btn-flat">' . trans('admin.delete') . '</a>
                <div id="delete' . $lead->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">' . trans('admin.delete') . ' ' . trans('admin.lead') . '</h4>
                            </div>
                            <div class="modal-body">
                                <p>' . trans('admin.delete') . ' ' . $lead->name . '</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat"
                                        data-dismiss="modal">' . trans('admin.close') . '</button>
                                <a class="btn btn-danger btn-flat" href="' . url(adminPath() . '/delete-lead/' . $lead->id) . '">' . trans('admin.delete') . '</a>
                            </div>
                        </div>

                    </div>
                </div>';*/
            $agents = '';
            foreach (User::get() as $agent) {
                $agents .= '<option value="' . $agent->id . '">' . $agent->name . '</option>';
            }
            $lead->switch = '<a data-toggle="modal" data-target="#switch' . $lead->id . '" class="btn btn-xs btn-success btn-flat">' . trans('admin.switch') . '</a>
                        <div id="switch' . $lead->id . '" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">' . trans('admin.switch') . ' ' . trans('admin.lead') . '</h4>
                                    </div>
                                    <form action="' . url(adminPath() . '/switch_leads') . '" method="post">
                                    ' . csrf_field() . '
                                    <div class="modal-body">
                                        <select class="select2" name="agent_id"
                                                data-placeholder="' . __('admin.agent') . '" style="width: 100%">
                                            <option></option>
                                            ' . $agents . '
                                        </select>
                                        <input type="hidden" value="' . $lead->id . '" name="leads[]">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">' . trans('admin.close') . '</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">' . trans('admin.switch') . '</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <script>
                        $(".select2").select2();
        </script>';
        }
        return \DataTables::of($leads)->
        escapeColumns('')->
        make(true);
    }

    public function add_lead_request(Request $request)
    {
        $rules1 = [
            'lead_source' => 'required',
            'xls' => 'file|max:100|mimeTypes:' .
                'application/vnd.ms-office,' .
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,' .
                'application/vnd.ms-excel',
        ];
        $validator1 = Validator::make($request->all(), $rules1);
        $validator1->SetAttributeNames([
            'lead_source' => trans('admin.lead_source'),
            'xls' => trans('admin.xls'),
        ]);
        if ($validator1->fails()) {
            return back()->withInput()->withErrors($validator1);
        } else {

            $source = $request->lead_source;
            $path = $request->file('xls')->getRealPath();
            Excel::load($path, function ($reader) use ($source) {
                $array = $reader->toArray();
                foreach ($array as $item) {
                    if (isset($item['phone'])) {
                        $check1 = Lead::where('phone', $item['phone'])->get();
                        if (count($check1) < 1) {
                            $rules = [
                                'first_name' => 'required|max:191',
                                'last_name' => 'required|max:191',
                                'gender' => 'required',
                                'phone' => 'required',
                                'campaign' => 'required',
                            ];
                            $validator = Validator::make($item, $rules);

                            if (!$validator->fails()) {
                                $data = new Lead();
                                if (isset($item['email'])) {

                                    $check2 = Lead::where('phone', $item['phone'])->get();
                                    if (count($check2) < 1) {
                                        $validator = Validator::make(['email'],
                                            ['email' => 'required|email']);
                                        if (!$validator->fails()) {
                                            $data->email = $item['email'];
                                        }
                                    }

                                }
                                if (count($campaign = Campaign::where('en_name', $item['campaign'])->orWhere('ar_name', $item['campaign'])->first()) > 0) {
                                    $data->first_name = $item['first_name'];
                                    $data->last_name = $item['last_name'];
                                    $data->phone = $item['phone'];
                                    $data->lead_source_id = $source;
                                    $data->campain_id = $campaign->id;
                                    if ($item['gender'] == 'female') {
                                        $data->prefix_name = 'ms';
                                    } else {
                                        $data->prefix_name = 'mr';
                                    }
                                    $data->agent_id = Auth::user()->id;
                                    $data->user_id = 0;
                                    $data->save();
                                }
                            }

                        }
                    }
                }

            });

            return redirect(adminPath() . '/leads');
        }
    }

    public function formLead(Request $request)
    {
        $form = Form::find($request->form_id);
        $fields = json_decode($form->fields);

        $rules = [
            'prefix_name' => 'required',
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'phone' => 'required|numeric',
        ];
        foreach ($fields as $field => $k) {
            if ($k) {
                if ($field == 'image') {
                    $rules[$field] = 'required|image';
                } else if ($field == 'email') {
                    $rules[$field] = 'required|email';
                } else {
                    $rules[$field] = 'required';
                }
            }
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'prefix_name' => trans('admin.prefix_name'),
            'first_name' => trans('admin.first_name'),
            'last_name' => trans('admin.last_name'),
            'middle_name' => trans('admin.middle_name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'address' => trans('admin.address'),
            'image' => trans('admin.image'),
            'club' => trans('admin.club'),
            'religion' => trans('admin.religion'),
            'birth_date' => trans('admin.birth_date'),
            'company' => trans('admin.company'),
            'school' => trans('admin.school'),
            'facebook' => trans('admin.facebook'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if (Lead::where('phone', $request->phone)->count() > 0) {
                $lead = Lead::where('phone', $request->phone)->first();
                $lead->prefix_name = $request->prefix_name;
                $lead->first_name = $request->first_name;
                $lead->last_name = $request->last_name;
                $lead->middle_name = $request->middle_name;
                $lead->email = $request->email;
                $lead->phone = $request->phone;
                $lead->address = $request->address;
                $lead->club = $request->club;
                $lead->religion = $request->religion;
                $lead->company = $request->company;
                $lead->school = $request->school;
                $lead->facebook = $request->facebook;
                if ($request->has('birth_date')) {
                    $lead->birth_date = strtotime($request->birth_date);
                }
                $lead->lead_source_id = $form->lead_source_id;
                $lead->notes = $request->notes;
                if ($request->hasFile('image')) {
                    $lead->image = uploads($request, 'image');
                } else {
                    $lead->image = 'image.jpg';
                }
                $lead->save();
            } else {
                $lead = new Lead;
                $lead->prefix_name = $request->prefix_name;
                $lead->first_name = $request->first_name;
                $lead->last_name = $request->last_name;
                $lead->middle_name = $request->middle_name;
                $lead->email = $request->email;
                $lead->phone = $request->phone;
                $lead->address = $request->address;
                $lead->club = $request->club;
                $lead->religion = $request->religion;
                $lead->company = $request->company;
                $lead->school = $request->school;
                $lead->facebook = $request->facebook;
                $lead->birth_date = strtotime($request->birth_date);
                $lead->lead_source_id = $form->lead_source_id;
                $lead->other_phones = json_encode([]);
                $lead->other_emails = json_encode([]);
                $lead->notes = $request->notes;
                $lead->user_id = 0;
                $lead->agent_id = 0;
                if ($request->hasFile('image')) {
                    $lead->image = uploads($request, 'image');
                } else {
                    $lead->image = 'image.jpg';
                }
                $lead->save();
            }
            session()->flash('success', trans('admin.created'));

            return redirect('/');
        }
    }

    public function filter_team_leads(Request $request)
    {
        $leads = Lead::where('agent_id', $request->id)->get();
        return view('admin.leads.filter_team', compact('leads'));
    }

    public function filter_leads(Request $request){
        $query = new Lead;
        // return $request->all();
        if (@$request->date_from and @$request->date_to) {
            $from = date('Y-m-d', strtotime($request->date_from));
            $to = date('Y-m-d', strtotime($request->date_to));
            $query = $query->whereBetween('created_at', [$from, $to]);
        }

        if (@$request->location) {
            $requests = \App\Request::where('location', $request->location)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $requests);
        }

        if (@$request->call_status) {
            $calls = Call::where('call_status_id', $request->call_status)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $calls);
        }

        if (@$request->meeting_status) {
            $meetings = Meeting::where('meeting_status_id', $request->meeting_status)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $meetings);
        }

        if (@$request->group_id != 0) {
            $agents = GroupMember::where('group_id', $request->group_id)->pluck('member_id')->toArray();
            $query = $query->whereIn('agent_id', $agents);
        }

        if (@$request->agent_id != 0) {
            $query = $query->where('agent_id', $request->agent_id)->orWhere('commercial_agent_id', $request->agent_id);
        } else {
            $users = [];
            $user_ids = [];
            if (auth()->user()->type == 'admin' or @Group::where('team_leader_id', auth()->id())->count()) {
                $teamLeads = Lead::getAgentLeads();

                foreach ($teamLeads as $lead) {
                    if ($lead->agent_id != auth()->id()) {
                        $users[] = User::find($lead->agent_id);
                        $user_ids[] = $lead->agent_id;
                    }
                }
            }
            $users = array_unique($users);
            // return $users;
            $query = $query->whereIn('agent_id', $user_ids);
        }

        $leads = $query->paginate(10, ['*'], 'filter');

        $commercialAgents = User::where('residential_commercial', 'commercial')->pluck('id')->toArray();
        $residentialAgents = User::where('residential_commercial', 'residential')->pluck('id')->toArray();

        if ($request->type == 'team') {
            return view('admin.leads.filter_team', compact('leads', 'commercialAgents', 'residentialAgents'));
        } else {
            return view('admin.leads.filtered',compact('leads', 'commercialAgents', 'residentialAgents'));
        }
    }

    public function searchTeam(Request $r)
    {
        $agents = json_decode($r->agents);
        $q = $r->q;
        $teams = Lead::join('users', 'users.id', '=', 'leads.agent_id')
                ->join('lead_sources', 'lead_sources.id', '=', 'leads.lead_source_id')
                ->where('leads.first_name', 'LIKE', '%' . $q . '%')
                ->orWhere('leads.last_name', 'LIKE', '%' . $q . '%')
                ->orWhere('leads.email', 'LIKE', '%' . $q . '%')
                ->orWhere('leads.phone', 'LIKE', '%' . $q . '%')
                ->orWhere('users.name', 'LIKE', '%' . $q . '%')
                ->orWhere('lead_sources.name', 'LIKE', '%' . $q . '%')
                ->select('leads.id as id', 'leads.first_name as first_name','leads.last_name as last_name', 'leads.email as email', 'leads.phone as phone', 'leads.lead_source_id', 'leads.agent_id')
                ->paginate(15);

        return view('admin.leads.team_search', compact('teams'));
    }

    public function getGroupAgents(Request $request)
    {
        $agents = GroupMember::where('group_id', $request->group_id)->get();
        return view('admin.leads.group_agents', ['agents' => $agents]);
    }
}
