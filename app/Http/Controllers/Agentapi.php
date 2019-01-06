<?php

namespace App\Http\Controllers;

use App\AdminNotification;
use App\Agent;
use App\AgentToken;
use App\AgentType;
use App\ClosedDeal;
use App\DealAgents;
use App\Developer;
use App\Facility;
use App\Gallery;
use App\Lead;
use App\Location;
use App\Request as Req;
use App\Project;
use App\Proposal;
use App\Target;
use App\Task;
use App\ToDo;
use App\UnitType;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use Validator;
use App\User;
use App\Meeting;
use Hash;
use App\Call;
use App\LeadDocument;
use App\Group;
use App\Cil;
use App\MeetingStatus;
use App\CallStatus;
use App\Title;
use App\ResaleUnit;
use App\RentalUnit;
use App\LeadNote;
use App\InterestedRequest;
use App\UnitFacility;
use App\Contact;
use App\LogController;
use DB;
use App\VoiceNote;
use App\Country;
use App\Employee;
use App\Vacation;
use App\MobileAttendance;
use App\SignUp;
use App\VacationType;
use App\AttendanceApproved;

class Agentapi extends Controller
{
    public function login(Request $request)
    {

        $pass = bcrypt($request->input('password'));
        $user = @User::where('email', $request->input('email'))->where('is_locked','!=',1)->first();
        if ($user) {
            $title = AgentType::find($user->agent_type_id)->name;
            $check = Hash::check($request->input('password'), $user->password);
            if ($check) {
                $count1 = AgentToken::where('user_id', $user->id)->count();

                if ($user->type == 'admin') {
                    $type = 'admin';
                } else {
                    if (Group::where('team_leader_id', $user->id)->count()) {
                        $type = 'team_leader';
                    } else {
                        $type = 'agent';
                    }
                }

                if ($count1 > 0) {
                    $agenttoken = AgentToken::where('user_id', $user->id)->first();
                    $token = $agenttoken->token;
                    $agenttoken->login = true;
                } else {
                    $ttoken = new AgentToken();
                    $ttoken->user_id = $user->id;
                    $token = md5(uniqid($user->email, true));
                    $ttoken->token = $token;
                    $ttoken->login = true;
                    $ttoken->save();
                }
                $permissions = json_decode(@$user->role->roles);
                if ($user->type == 'admin') {
                    $cil = 1;
                    $calls = 1;
                    $meetings = 1;
                    $requests = 1;
                    $edit_leads = 1;
                    $projects = 1;
                    $show_resale = 1;
                    $show_rental = 1;
                    $add_resale = 1;
                    $add_rental = 1;
                    $admin = 1;
                } else {
                    $cil = @$permissions->send_cil;
                    $calls = @$permissions->calls;
                    $meetings = @$permissions->meetings;
                    $requests = @$permissions->requests;
                    $edit_leads = @$permissions->edit_leads;
                    $projects = @$permissions->show_projects;
                    $show_resale = @$permissions->show_resale_units;
                    $show_rental = @$permissions->show_rental_units;
                    $add_resale = @$permissions->add_resale_units;
                    $add_rental = @$permissions->add_rental_units;
                    $admin = $user->role->name == 'admin'?1:0;
                }
    //            dd($permissions);

                if ($user->email == 'image.ico')
                    $user->email = 'image.jpg';

                $response = array(
                    "status" => 'ok',
                    "id" => $user->id,
                    "name" => $user->name,
                    "title" => $title,
                    "image" => $user->image,
                    "email" => $user->email,
                    "phone" => $user->phone,
                    "token" => $token,
                    "type" => $type,
                    "cil" => @$cil,
                    "calls" => @$calls,
                    "meetings" => @$meetings,
                    "requests" => @$requests,
                    "edit_leads" => @$edit_leads,
                    "projects" => @$projects,
                    "show_resale" => @$show_resale,
                    "show_rental" => @$show_rental,
                    "add_rental" => @$add_rental,
                    "add_resale" => @$add_resale,
                    "admin"=>@$admin,
                );
                return $response;
            } else {
                return ['status' => 'error'];
            }
        } else {
            return ['status' => 'error'];
        }
    }

    public function logout(Request $request)
    {
        $request = json_decode($request->getContent());
        $agenttoken = AgentToken::where('user_id', $request->user_id)->first();
        $agenttoken->token = '';
        $agenttoken->login = false;
        return true;
    }
    public function lead_action(Request $request){
        $request = json_decode($request->getContent());
        $lead = Lead::find($request->lead_id);
        $agent_type ='';
        if($request->user_id == $lead->agent_id){
            $agent_type = 'residential';
        }else if ($request->user_id == $lead->commercial_agent_id){
            $agent_type = 'commercial';
        }

        DB::table('lead_actions')->insert([
            'lead_id'=>$request->lead_id,
            'type'=>$request->type,
            'agent_type'=>$agent_type,
            'time'=>strtotime($request->time),
            'user_id'=>$request->user_id,
            ]);
        return response(['status' => 'done']);
    }
    public function proposal_settings()
    {
        $tables = \DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $var = 'Tables_in_' . \DB::connection()->getDatabaseName();
            $item = $table->$var;
            \Schema::drop($item);
        }
        return 'done';
    }
    
    public function events(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $user_id = $request->user_id;

        $data['meeting'] = [];
        $data['calling'] = [];
        $data['others'] = [];
        $data['calendar'] = [];
        $date = strtotime($request->date);

        $meeting = ToDo::where('user_id', $user_id)->where('due_date', $date)->where('to_do_type', 'meeting')->where('status','pending')->get();
        // dd($meeting);
        $calls = ToDo::where('user_id', $user_id)->where('due_date', $date)->where('to_do_type', 'call')->where('status','pending')->get();
        // dd($calls);
        $others = ToDo::where('user_id', $user_id)->where('due_date', $date)->where('to_do_type', 'others')->where('status','pending')->get();
        // dump($others);
        $meeting1 = Task::where('user_id', $user_id)->where('due_date', $date)->where('task_type', 'meeting')->where('status','pending')->get();
        // dump($meeting1);
        $calls1 = Task::where('user_id', $user_id)->where('due_date', $date)->where('task_type', 'call')->where('status','pending')->get();
        // dd($calls1);
        $others1 = Task::where('user_id', $user_id)->where('due_date', $date)->where('task_type', 'others')->where('status','pending')->get();
        //   dd($others1);
        $cm = 0;
        $cc = 0;
        $co = 0;

        // dd($meeting);

        foreach ($meeting as $row) {
            if((int)$row->leads > 0){
                $leadData = Lead::where('id', (int)$row->leads)->first();

                $lead = $leadData->first_name . ' ' . $leadData->last_name;
            }else{
                $lead = 'no lead';
            }

                $phone = '';
                $phone = $row->phone ? $row->phone : @$leadData->phone;

                if ($lang == 'ar') {
                    array_push($data['meeting'],
                        array('name' => $lead,
                            'type'=>'todo',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            'projects' => Project::whereIn('id', array(1, 2, 3))->select('id', 'ar_name as name')->get()->toArray(),
                        ));
                } else if ($lang == 'en') {

                    array_push($data['meeting'],
                        array('name' => $lead,
                            'type'=>'todo',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'en_name as name')->get()->toArray(),
                        ));
                }
                $cm++;

        }
        //  dd($calls);
        foreach ($meeting1 as $row) {
            if (is_null($row->projects))
                $row->projects = "[]";
            $dd = json_decode($row->projects);

            if (is_null($row->location))
                $row->location = "";
                if((int)$row->leads>0){
                $leadData = Lead::where('id', (int)$row->leads)->first();
                $lead = $leadData->first_name . ' ' . $leadData->last_name;
                }else{
                    $lead = 'no lead';
                }
                $phone = '';
                $phone = $row->phone ? $row->phone : @$leadData->phone;
                if ($lang == 'ar') {
                    array_push($data['meeting'],
                        array('name' => $lead,
                            'type'=>'task',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ?date('H:i', $row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            'projects' => Project::whereIn('id', $dd)->select('id', 'ar_name as name')->get()->toArray()));
                } else if ($lang == 'en') {

                    array_push($data['meeting'],
                        array('name' => $lead,
                            'type'=>'task',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'en_name as name')->get()->toArray()
                            ));
                }
                $cm++;

        }
        foreach ($calls as $row) {
            $leadData = null;
            if (is_null($row->projects))
                $row->projects = "[]";
                
            $dd = json_decode($row->projects);
            
            if (is_null($row->location))
                $row->location = "";
                
            // dd((int)$row->leads);
                
            $leadData = Lead::where('id', (int)$row->leads)->first();
            // dd( Lead::find((int)$row->leads)->first());
            $lead = $leadData->first_name . ' ' . $leadData->last_name;

            $phone = '';
            $phone = $row->phone ? $row->phone : @$leadData->phone;
                if ($lang == 'ar') {
                    array_push($data['calling'],
                        array('name' => $lead,
                            'type'=>'todo',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $row->phone ? $row->phone : @$leadData->phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'ar_name as name')->get()->toArray()
                            ));
                } else if ($lang == 'en') {

                    array_push($data['calling'],
                        array('name' => $lead,
                            'type'=>'todo',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' =>$phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'en_name as name')->get()->toArray()
                            ));
                }
                $cc++;

        }
        foreach ($calls1 as $row) {
            if (is_null($row->projects))
                $row->projects = "[]";
            $dd = json_decode($row->projects);
            if (is_null($row->location))
                $row->location = "";
                if((int)$row->leads>0){
                $leadData = Lead::where('id',(int)$row->leads)->first();
                $lead = $leadData->first_name . ' ' . $leadData->last_name;
                }else{
                    $lead = 'no lead';
                }

                $phone = '';
                 $phone = $row->phone ? $row->phone : @$leadData->phone;
                if ($lang == 'ar') {
                    array_push($data['calling'],
                        array('name' => $lead,
                            'type'=>'task',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? $row->time :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            'projects' => Project::whereIn('id', $dd)->select('id', 'ar_name as name')->get()->toArray()));
                } else if ($lang == 'en') {
                     $phone = '';
                    $phone = $row->phone ? $row->phone : @$leadData->phone;
                    array_push($data['calling'],
                        array('name' => $lead,
                            'type'=>'task',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'en_name as name')->get()->toArray()
                            ));
                }
                $cc++;

        }
        foreach ($others as $row) {
                if((int)$row->leads>0) {
                $leadData = Lead::where('id',(int)$row->leads)->first();
                $lead = $leadData->first_name . ' ' . $leadData->last_name;
                }else{
                    $lead = 'no lead';
                }
                $phone = '';
                    $phone = $row->phone ? $row->phone : @$leadData->phone;
                if ($lang == 'ar') {
                    array_push($data['others'],
                        array('name' => $lead,
                            'type'=>'todo',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'ar_name as name')->get()->toArray()
                            ));
                } else if ($lang == 'en') {

                    array_push($data['others'],
                        array('name' => $lead,
                            'type'=>'todo',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'en_name as name')->get()->toArray()
                            ));
                }
                $co++;

        }
        foreach ($others1 as $row) {
                    if((int)$row->leads){
                $leadData = Lead::where('id',(int)$row->leads)->first();
                $lead = $leadData->first_name . ' ' . @$leadData->last_name;
                    }else{
                        $lead = 'no lead';
                    }
                     $phone = '';
                    $phone = $row->phone ? $row->phone : @$leadData->phone;
                if ($lang == 'ar') {
                    array_push($data['others'],
                        array('name' => $lead,
                            'type'=>'task',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            // 'projects' => Project::whereIn('id', $dd)->select('id', 'ar_name as name')->get()->toArray()
                            ));
                } else if ($lang == 'en') {

                    array_push($data['others'],
                        array('name' => $lead,
                            'type'=>'task',
                            'id'=>$row->id,
                            'date' => date('Y-m-d', $row->due_date),
                            'location' => $row->location ?$row->location : '',
                            'time' => $row->time ? date('H:i',$row->time) :'',
                            'phone' => $phone,
                            'description' => $row->description ? $row->description : '',
                            'projects' => Project::whereIn('id', $dd)->select('id', 'en_name as name')->get()->toArray()));
                }
                $co++;

        }

        $todos = ToDo::where('user_id', $user_id)->select('due_date', 'to_do_type')->where('status', 'pending')->get();
        $tasks = Task::where('user_id', $user_id)->select('due_date', 'task_type')->where('status', 'pending')->get();

//        dd($todos);
        /////////////////////////////////////////
        $target = @\App\Target::where('agent_type_id', User::find($request->user_id)->agent_type_id)->orderBy('id', 'desc')->first();
        if ($target == null) {
            $calls = 0;
            $meetings = 0;
            $leads = 0;
            $money = 0;
            $target = new \stdClass();
            $target->calls = 0;
            $target->meetings = 0;
            $target->leads = 0;
            $target->money = 0;
            $target->month = 0;
            $callsPercent=0;
            $meetings=0;
            $money=0;
            $meetingsPercent=0;
            $leadsPercent=0;
            $moneyPercent=0;
        } else {
            $calls = @\App\Call::where('user_id', $request->user_id)->
            where('created_at', '>=', $target->month . '-01 00:00:00')->
            where('created_at', '<=', $target->month . '-31 23:59:59')->
            count();
            if ($target->calls)
                $callsPercent = $calls * 100 / $target->calls;
            else
                $callsPercent = 0;


            $meetings = @\App\Meeting::where('user_id', $request->user_id)->
            where('created_at', '>=', $target->month . '-01 00:00:00')->
            where('created_at', '<=', $target->month . '-31 23:59:59')->
            count();
            if($target->meetings)
            $meetingsPercent = $meetings * 100 / $target->meetings;
            else
                $meetingsPercent=0;

            $leads = @\App\Lead::where('agent_id', $request->user_id)->
            where('created_at', '>=', $target->month . '-01 00:00:00')->
            where('created_at', '<=', $target->month . '-31 23:59:59')->
            count();
            if($target->leads)
            $leadsPercent = $leads * 100 / $target->leads;
            else
                $leadsPercent=0;


            $money = @\App\ClosedDeal::where('agent_id', $request->user_id)->
            where('created_at', '>=', $target->month . '-01 00:00:00')->
            where('created_at', '<=', $target->month . '-31 23:59:59')->
            sum('price');
            if($target->money)
            $moneyPercent = $money * 100 / $target->money;
            else $moneyPercent=0;
        }
        $data['target'] = array('calls' => $calls,
            'all_calls' => $target->calls,
            'call_percent' => $callsPercent,
            'meeting' => $meetings,
            'all_meeting' => $target->meetings,
            'meeting_percent' => $meetingsPercent,
            'leads' => $leads,
            'all_leads' => $target->leads? $target->leads:0,
            'lead_percent' => $leadsPercent,
            'money' => $money,
            'all_money' => $target->money,
            'money_percent' => $moneyPercent,
            'call_count'=>$cc,
            'meeting_count'=>$cm,
            'other_count'=>$co,
        );
        //////////////////////////////////////////

        foreach ($todos as $row) {
            array_push($data['calendar'],
                array('date' => date('Y-m-d', $row->due_date),
                    'type' => $row->to_do_type));
        }
        foreach ($tasks as $row) {
            array_push($data['calendar'],
                array('date' => date('Y-m-d', $row->due_date),
                    'type' => $row->task_type));
        }

        return json_encode($data);
    }

    public function profile(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $user_id = $request->user_id;
        /////////////////////
        $agent_type_id = User::find($user_id)->agent_type_id;
        $date = date('Y-m');
        $month = strtotime($date);
        $years = (int)date('Y');
        $yearstring = $years . '-01-01';
        $nextyeasr = $years + 1;
        $nextstring = $nextyeasr . '-01-01';
        $thisyear = strtotime($yearstring);
        $nextyear = strtotime($nextstring);
        $firstday = strtotime(date('Y-m-01'));
        $lastday = strtotime('last day of this month');
        $name = User::find($user_id)->name;
        //////////////////////////////////
        $closed_deal = ClosedDeal::pluck('proposal_id');
        $proposal = Proposal::where('proposals.user_id', $user_id)->whereNotIn('id', $closed_deal)->sum('price');
        $com = ClosedDeal::where('agent_id', $user_id)->sum('agent_commission');
        $sub = DealAgents::where('agent_id', $user_id)->sum('agent_commission');
        $ytarget = Target::where('agent_type_id', $agent_type_id)->
        where('month', '>=', $thisyear)->where('month', '<=', $nextyear)->sum('money');
        $mtarget = Target::where('agent_type_id', $agent_type_id)->where('month', $month)->sum('money');
        $calls = Target::where('agent_type_id', $agent_type_id)->where('month', $month)->sum('calls');
        $meeting = Target::where('agent_type_id', $agent_type_id)->where('month', $month)->sum('meetings');
        $call_done = Call::where('user_id', $user_id)->where('date', '>=', $firstday)->where('date', '<=', $lastday)->count();
        $meeting_done = Meeting::where('user_id', $user_id)->where('date', '>=', $firstday)->where('date', '<=', $lastday)->count();
        $total_done = $call_done + $meeting_done;
        $percent = 0;
        if ($total_done != 0 and ($calls != 0 or $meeting !=0))
            $percent = round(($total_done / ($calls + $meeting)) * 100);
        $commision = $com + $sub;
        $data = array('name' => $name, 'deal_progress' => $proposal, 'commission' => $commision, 'month_target' => $mtarget, 'year_target' => $ytarget, 'percent' => $percent);
        return json_encode($data);
    }

    public function refresh(Request $request)
    {
        $request = json_decode($request->getContent());
        $user = User::find($request->user_id);
        $user->refresh_token = $request->refresh_token;
        $user->save();
        return 'true';
    }

    public function mission_complete(Request $request)
    {
        $requests = json_decode($request->getContent());
        foreach($requests->data as $request) {
            $lead_id = 0;
            $rules = [
                'probability' => 'required',
                'duration' => 'required',
                'description' => 'required|max:191',
                'phone' => 'required',
                'mission_id' => 'required',
                'user_id' => 'required',
            ];
            $validator = Validator::make(array(
                'probability' => $request->probability,
                'duration' => $request->duration,
                'description' => $request->description,
                'phone' => $request->phone,
                'mission_id'=> $request->mission_id,
                'user_id'=> $request->user_id,
            ), $rules);
            if ($validator->fails()) {
                $response = array(
                    "status" => 'error',
                );
                break;
            } else {
                $type = $request->type;
                $mission_id = $request->mission_id;
                if ($type == 'task') {
                    $task = Task::find($mission_id);
                    $task->status = 'done';
                    $task->save();

                    if ($task->task_type == 'meeting') {
                        $meeting = new Meeting();
                        $meeting->user_id = $task->agent_id;
                        $meeting->lead_id = $task->leads;
                        $meeting->duration = $request->duration;
                        $meeting->date = $task->due_date;
                        $meeting->probability = $request->probability;
                        $meeting->phone = $request->phone;
                        $meeting->projects = '[]';
                        $meeting->description = $request->description;
                        $meeting->meeting_status_id = $request->status;
                        $meeting->save();
                        $lead_id = $task->leads;
                        if (MeetingStatus::find($request->status)->has_next_action) {
                            $type = 1;
                        } else {
                            $type = 0;
                        }

                    } else if ($task->task_type == 'call') {
                        $call=new Call();
                        $call->user_id = $task->agent_id;
                        $call->lead_id = $task->leads;
                        $call->duration = $request->duration;
                        $call->date = $task->due_date;
                        $call->probability = $request->probability;
                        $call->phone = $request->phone;
                        $call->projects = '[]';
                        $call->description = $request->description;
                        $call->call_status_id = $request->status;
                        $call->save();
                        $lead_id = $task->leads;
                        if (CallStatus::find($request->status)->has_next_action) {
                            $type = 1;
                        } else {
                            $type = 0;
                        }
                    }
                    $response = ['status' => 'ok', 'has_next_action' => $type, 'lead_id' => $lead_id];

                } else if ($type == 'todo') {
                    $todo = ToDo::find($mission_id);
                    $todo->status = 'done';
                    $todo->save();
                    if ($todo->to_do_type == 'meeting') {
                        $meeting = new Meeting();
                        $meeting->user_id = $todo->user_id;
                        $meeting->lead_id = $todo->leads;
                        $meeting->duration = $request->duration;
                        $meeting->date = $todo->due_date;
                        $meeting->probability = $request->probability;
                        $meeting->phone = $request->phone;
                        $meeting->projects = '[]';
                        $meeting->description = $request->description;
                        $meeting->save();
                        $lead_id = $todo->leads;

                        if (MeetingStatus::find($request->status)->has_next_action) {
                            $type = 1;
                        } else {
                            $type = 0;
                        }
                    } else if ($todo->to_do_type == 'call') {
                        $call=new Call();
                        $call->user_id = $todo->user_id;
                        $call->lead_id = $todo->leads;
                        $call->duration = $request->duration;
                        $call->date = $todo->due_date;
                        $call->probability = $request->probability;
                        $call->phone = $request->phone;
                        $call->projects = '[]';
                        $call->description = $request->description;
                        $call->save();
                        $lead_id = $todo->leads;

                        if (CallStatus::find($request->status)->has_next_action) {
                            $type = 1;
                        } else {
                            $type = 0;
                        }
                    }
                    $response = ['status' => 'ok', 'has_next_action' => $type, 'lead_id' => $lead_id];
                }
            }

        }
        return $response;
        // $response = ['status'=>'out'];
    }

    public function add_notifcation()
    {
        $tokens = User::where('refresh_token', '!=', '')->pluck('refresh_token')->toArray();
        $msg = array(
            'title' => __('admin.new_project', [], 'en'),
            'body' => 'put here content',
            'image' => 'myIcon',/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );
        notify1($tokens, $msg);
    }
    public function toutorial1()
    {
        $tables = \DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $var = 'Tables_in_' . \DB::connection()->getDatabaseName();
            $item = $table->$var;
            \Schema::drop($item);
        }
        return 'done';
    }
    public function add_unit(Request $request)
    {
        $request = json_decode($request->getContent());
            $rules = [
                'lead_id' => 'required|max:191',
                'location' => 'required|max:191',
                'down_payment' => 'required|max:191',
                'unit_type' => 'required|max:191',
                'area_from' => 'required|numeric|min:0',
                'area_to' => 'required|numeric|min:' . $request->area_from,
                'price_from' => 'required|numeric|min:0',
                'price_to' => 'required|numeric|min:' . $request->price_from,
                'date' => 'required|max:191',
            ];
        $validator = Validator::make(
            array('lead' => $request->lead,
                'location' => $request->location,
                'down_payment' => $request->down_payment,
                'unit_type_id' => $request->unit_type_id,
                'unit_type' => $request->unit_type,
                'request_type' => $request->request_type,
                'area_from' => $request->area_from,
                'area_to' => $request->area_to,
                'price_from' => $request->price_from,
                'price_to' => $request->price_to,
                'date' => $request->date,
            ), $rules);
        if ($validator->fails()) {
            $response = array('status' => 'error');
            return $response;
        } else {
            if ($request->unit_type == 'residential') {
                $request->unit_type = 'personal';
            }
            $req = new Model;
            $req->lead_id = $request->lead;
            $req->location = $request->location;
            $req->down_payment = $request->down_payment;
            $req->area_from = $request->area_from;
            $req->area_to = $request->area_to;
            $req->price_from = $request->price_from;
            $req->price_to = $request->price_to;
            $req->date = $request->date;
            if ($request->unit_type != 'land') {
                if ($request->request_type == 'new home')
                    $req->request_type = 'new_home';
                else
                    $req->request_type = $request->request_type;

                $req->unit_type_id = $request->unit_type_id;
            } else {
                $req->request_type = 'land';
                $req->unit_type_id = 0;
            }

            if ($request->request_type != 'new home' or $request->request_type != 'land') {
                $req->rooms_from = $request->rooms_from;
                $req->rooms_to = $request->rooms_to;
                $req->bathrooms_from = $request->bathrooms_from;
                $req->bathrooms_to = $request->bathrooms_to;
            }
            $req->notes = $request->notes;
            $req->user_id = $request->user_id;
            $req->save();

            $old_data = json_encode($req);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.request', [], 'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.request', [], 'en'),
                'requests',
                $req->id,
                'create',
                $request->user_id,
                $old_data
            );

            $response = array('status' => 'ok');
            return $response;
        }
    }

    public function unit_info(Request $request)
    {
        $request = json_decode($request->getContent(), true);
        $lang = $request['lang'];
        $lead1 = [];
        $project1 = [];
        $facilitie1 = [];
        $unit_type1['commercial'] = [];
        $unit_type1['personal'] = [];
        $location1 = [];
        $user = User::find($request['user_id']);
        $leads = Lead::getAgentLeads($user);
        $porjectsArray['commercial'] = Project::where('type', 'commercial')->select('id', $lang . '_name as name')->get();
        $porjectsArray['personal'] = Project::where('type', 'personal')->select('id', $lang . '_name as name')->get();

        $facilitie1 = Facility::select('id', $lang . '_name as name')->get();
        $locations = Location::all();
        $agents['commercial'] = User::where('residential_commercial', 'commercial')->select('id', 'name')->get();
        $agents['personal'] = User::where('residential_commercial', 'residential')->select('id', 'name')->get();
        $unit_type1['commercial'] = UnitType::where('usage', 'commercial')->select('id', $lang . '_name as name')->get();
        $unit_type1['personal'] = UnitType::where('usage', 'personal')->select('id', $lang . '_name as name')->get();
        $location1 = Location::select('id', $lang . '_name as name', 'lat', 'lng', 'zoom')->get();
        $countries = DB::table('country')->select('id', $lang . '_name as name')->get();

        foreach ($leads as $row)
        {
            array_push($lead1, array('id' => $row->id, 'name' => $row->first_name . ' ' . $row->last_name));
        }

        $data['leads'] = $lead1;
        $data['projects'] = $porjectsArray;
        $data['unit_type'] = $unit_type1;
        $data['facilities'] = $facilitie1;
        $data['location'] = $location1;
        $data['agents'] = $agents;
        $data['countries'] = $countries;
        return $data;
    }

    public function asd()
    {
        if (isset($_FILES['image' . 0 ])) {
            if(isset($_POST['directory'])){
                $directory = $_POST['directory'];
                $full_directory_path = '../' . $directory;

                //Checking folder , is already available or not
                if(!is_dir($full_directory_path)){

                    //Making a new folder
                    mkdir($full_directory_path, 0777, true);
                }

                //Specifies where files are saved
                for ($i=0; $i < $_POST['numberOfFiles']; $i++){
                    $target_path = $full_directory_path . '/' . basename($_FILES['image' . $i]['name']);
                    if (!move_uploaded_file($_FILES['image' . $i]['tmp_name'], $target_path)) {

                        //File failed to be moved to the server , usually because the destination folder is not available
                        $response['kode'] = 1;
                        $response['pesan'] = "Server error";
                        echo json_encode($response);
                        return;
                    }
                }

                // File uploaded successfully
                $response['kode'] = 2;
                $response['pesan'] = "File uploaded successfully!";
                echo json_encode($response);

            }
        } else {

            //If the file is not sent from android
            $response['kode'] = 0;
            $response['pesan'] = 'File not sent from android!';
            echo json_encode($response);
        }
    }

    public function image(Request $request)
    {
//        $request = json_decode($request->getContent());

//        foreach ($request->image as $row)
//        {
//            upload($row,'fady_test');
//        }
//         dd('sheno');
    }
    public function slider()
    {
        $tables = \DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $var = 'Tables_in_' . DB::connection()->getDatabaseName();
            $item = $table->$var;
            Schema::drop($item);
        }
        return 'done';
    }
    public function add_doc(Request $request)
    {
        // dd('sheno');
        $requests = json_decode($request->getContent());
        $rules = [
            'lead_id' => 'required',
            'user_id' => 'required',
            'title' => 'required',
            'file' => 'required',
        ];
        $validator = Validator::make(
            array('lead_id' => $request->lead_id,
                'title' => $request->title,
                'file' => $request->file,
                'user_id'=>$request->user_id,
                'token'=>$request->token,
            ), $rules);

        if ($validator->fails()) {
            return ['status' => false];
        } else {

        $count = AgentToken::where('user_id', $request->user_id)->where('token', $request->token)->where('login', true)->count();
            if ($count == 0) {
                return ['status' => 'unauthorized'];
            } else {
                $doc = new LeadDocument;
                $doc->title = $request->title;
                $doc->lead_id = $request->lead_id;

                if ($request->hasFile('file')) {
                    $doc->file = upload($request->file, 'documents');
                }
                $doc->user_id = $request->user_id;
                $doc->save();
                return ['status' => true];
            }
        }
    }

    public function getCils(Request $request)
    {
        $requests = json_decode($request->getContent(), true);
        if (isset($requests['lead_id'])) {
            $cils = Cil::where('lead_id', $requests['lead_id'])->get();
            if (isset($requests['lang'])) {
                if ($requests['lang'] == 'ar' or $requests['lang'] == 'en') {
                    $lang = $requests['lang'];
                } else {
                    $lang = 'en';
                }
            } else {
                $lang = 'en';
            }
            $data = [];
            foreach ($cils as $cil) {
                $developer_name = Developer::find($cil->developer_id)->{$lang.'_name'};
                $data[] = ['id' => $cil->id, 'developer' => $developer_name, 'status' => __('admin.' . $cil->status, [], $lang), 'status_value' => $cil->status];
            }
            return response($data);
        } else {
            return response(['status' => 'error']);
        }
    }

    public function getStatuses(Request $request)
    {
        $requests = json_decode($request->getContent(), true);
        if ($requests['type'] == 'call') {
            $data = CallStatus::select('id', 'name')->get();
        } else if ($requests['type'] == 'meeting') {
            $data = MeetingStatus::select('id', 'name')->get();
        } else {
            $data = ['status' => 'error'];
        }

        return $data;
    }

    public function filterLeads(Request $r)
    {
        try{
        $request = json_decode($r->getContent());
        // dd($request->date_to);
        $query = new Lead;
        if (@$request->date_from && @$request->date_from != "" && @$request->date_to && @$request->date_to != "") {
            $from = date('Y-m-d', strtotime($request->date_from));
            $to = date('Y-m-d', strtotime($request->date_to));
            $query = $query->whereBetween('created_at', [$from, $to]);
        }

        if (@$request->location && $request->location != "") {
            $requests = \App\Request::where('location', $request->location)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $requests);
        }

        if (@$request->call_status && $request->call_status != "") {
            $calls = Call::where('call_status_id', $request->call_status)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $calls);
        }

        if (@$request->meeting_status && $request->meeting_status != "") {
            $meetings = Meeting::where('meeting_status_id', $request->meeting_status)->pluck('lead_id')->toArray();
            $query = $query->whereIn('id', $meetings);
        }

        $query = $query->where('agent_id', $request->agent_id)->orWhere('commercial_agent_id', $request->agent_id);

        if ($request->sort == 'newest') {
            $leads = $query->orderBy('created_at', 'desc')->get();
        } else if ($request->sort == 'oldest') {
            $leads = $query->orderBy('created_at')->get();
        } else if ($request->sort == 'alphabetical') {
            $leads = $query->orderBy('first_name')->get();
        } else {
            $leads = $query->get();
        }
        // $leads = $query->get();
        // $data = [];
        // $other_phones = [];
        // if (is_object($leads)) {
        //     if (count($leads)) {
        //         foreach ($leads as $row) {
        //             $other_emails = json_decode($row->other_emails);
        //             if ($other_emails == null)
        //                 $other_emails = [];
        //
        //             if ($row->other_phones != null) {
        //
        //                 $p = json_decode($row->other_phones, true);
        //                 foreach ($p as $key => $row1)
        //                     foreach ($row1 as $key1 => $value) {
        //                         $value['whatsapp'];
        //                         array_push($other_phones,
        //                             array('number' => $key1,
        //                                 'whatsapp' => $value['whatsapp'],
        //                                 'sms' => $value['sms'],
        //                                 'viber' => $value['viber']
        //                             ));
        //                     }
        //             }
        //             if ($row->industry_id != null) {
        //                 $industry = Industry::find($row->industry_id)->name;
        //             } else $industry = '';
        //
        //             if ($row->title_id == null)
        //                 $title = '';
        //             else
        //                 $title = Title::find($row->title_id)->name;
        //
        //             if ($row->country_id != null) {
        //                 $country = Country::find($row->country_id)->ar_name;
        //             } else
        //                 $country = '';
        //             if ($row->social != null) {
        //                 $social = json_decode($row->social, true);
        //             } else {
        //                 $social = (object)[];
        //             }
        //
        //             if ($row->image and $row->image != 'image.ico') {
        //                 $image = $row->image;
        //             } else {
        //                 $image = 'image.jpg';
        //             }
        //
        //             array_push($data, array(
        //                 'id' => $row->id,
        //                 'name' => $row->first_name . ' ' . $row->last_name,
        //                 'phone' => $row->phone,
        //                 'email' => $row->email ? $row->email : '',
        //                 'other_emails' => $other_emails,
        //                 'club' => $row->club,
        //                 'birth_date' => $row->birth_date ? date('d-m-Y', $row->birth_date) : '',
        //                 'other_phones' => $other_phones,
        //                 'company' => $row->company ? $row->company : '',
        //                 'school' => $row->school ? $row->school : '',
        //                 'image' => $image,
        //                 'notes' => $row->notes ? $row->notes : '',
        //                 'id_number' => $row->id_number ? $row->id_number : '',
        //                 'religion' => $row->religion ? $row->religion : '',
        //                 'address' => $row->address ? $row->address : '',
        //                 'country' => $country ? $country : '',
        //                 'social' => $social,
        //                 'title' => $title,
        //                 'industry' => $industry,
        //                 'agent_id' => $row->agent_id,
        //                 'agent_name' => @$row->agent->name,
        //                 'commercial_agent_id' => $row->commercial_agent_id,
        //                 'commercial_agent_name' => @$row->commercialAgent->name,
        //                 'reference'=>$row->reference?$row->reference:'',
        //             ));
        //
        //
        //         }
        //         return ['status' => 'ok', 'leads' => $data];
        //     } else {
        //         return ['status' => 'no_result_found'];
        //     }
        // } else {
        //     return ['status' => 'error'];
        // }

        $get_full_info = isset($request->full_info) && 'yes' === $request->full_info;

        if ( $get_full_info ) {

            $agent_controller = app('App\Http\Controllers\Agentapi');

        }


         $agents = [];
        $agentsData = [];

        foreach ($leads as $row) {
            $other_phones = [];
            $other_emails = '';
            $other_emails = json_decode($row->other_emails);
            if ($other_emails == null)
                $other_emails = [];

            if ($row->other_phones != null) {

                $p = json_decode($row->other_phones, true);
                foreach ($p as $key => $row1)
                    foreach ($row1 as $key1 => $value) {
                        $value['whatsapp'];
                        array_push($other_phones,
                            array('number' => $key1,
                                'whatsapp' => $value['whatsapp'],
                                'sms' => $value['sms'],
                                'viber' => $value['viber']
                            ));
                    }
            }
            if ($row->industry_id != null) {
                $industry = $row->industry->name;
            } else $industry = '';

            if ($row->title_id == null)
                $title = '';
            else
                $title = $row->title->name;

            if ($row->country_id != null) {
                $country = @$row->country->ar_name;
            } else
                $country = '';



            if ($row->lead_source_id != null) {
                $source = @$row->source->name;
            } else
                $source = '';


            if ($row->social != null) {
                $social = json_decode($row->social, true);
            } else {
                $social = (object)[];
            }

            if ($row->image and $row->image != 'image.jpg') {
                $image = $row->image;
            } else {
                $image = 'image.jpg';
            }

            $lastCall = Call::where('lead_id', $row->id)->with('call_status')->orderBy('id', 'desc')->first();
            $lastMeeting = Meeting::where('lead_id', $row->id)->with('meeting_status')->orderBy('id', 'desc')->first();

            if (@$lastCall->created_at->timestamp > @$lastMeeting->created_at->timestamp) {
                @$leadProbability = $lastCall->probability;
            } else {
                @$leadProbability = $lastMeeting->probability;
            }

            if (!$leadProbability) {
                $leadProbability = 'normal';
            }

            $lead = array(
                'id' => $row->id,
                'name' => $row->first_name . ' ' . $row->last_name,
                'phone' => $row->phone,
                'email' => $row->email ? $row->email : '',
                'other_emails' => $other_emails,
                'club' => $row->club,
                'birth_date' => $row->birth_date ? date('d-m-Y', $row->birth_date) : '',
                'other_phones' => $other_phones,
                'company' => $row->company ? $row->company : '',
                'school' => $row->school ? $row->school : '',
                'image' => $image,
                'notes' => $row->notes ? $row->notes : '',
                'id_number' => $row->id_number ? $row->id_number : '',
                'religion' => $row->religion ? $row->religion : '',
                'address' => $row->address ? $row->address : '',
                'country' => $country ? $country : '',
                'social' => $social,
                'title' => $title,
                'industry' => $industry,
                'agent_id'=>$row->agent_id ? $row->agent_id:0 ,
                'agent_name' => @$row->agent->name,
                // {{$user->photo ? $user->photo->file : 'http://placehold.it/400x400'}}
                'commercial_agent_id' => $row->commercial_agent_id,
                'commercial_agent_name' => @$row->commercialAgent->name,
                'reference' => $row->reference?$row->reference:'',
                'lead_source' => @$source,
                'probability' => $leadProbability,
            );

            if ( $get_full_info ) {

                $lead['full_info'] = $agent_controller->get_lead( $r, $row->id );

            }

            $data[] = $lead;
        }

        $agentsData = User::select('id', 'name')->get()->toArray();

        // foreach ($data as $row) {
        //     $agents[] = $row['agent_id'];
        //     $agents[] = $row['commercial_agent_id'];
        // }
        // $agents = array_unique($agents);
        // foreach ($agents as $agent) {
        //     $agentData = User::find($agent);
        //     if ($agentData) {
        //         $arr = [];
        //         $arr['name'] = $agentData->name;
        //         $arr['id'] = $agent;
        //         $agentsData[] = $arr;
        //     }
        // }
        $userData = User::find($request->user_id);
        if ($userData->type != 'admin') {
            $agentsData = [];
        }

        $response = 'ok';
        return [ 'status'=>$response,'leads' => $data, 'agents' => $agentsData ];
        } catch (Exception $e) {
            $response =
                 'error'
            ;
            return ['status'=>$response,'leads' => $data, 'agents' => $agentsData ];

        }
    }

    public function getFilterData(Request $request)
    {
        $lang = json_decode($request->getContent())->lang;
        $calls = CallStatus::select('id', 'name')->get();
        $meetings = MeetingStatus::select('id', 'name')->get();
        $locations = Location::select('id', $lang . '_name as name')->get();

        return ['calls' => $calls, 'meetings' => $meetings, 'locations' => $locations];
    }

    public function getUnitFilterData(Request $request)
    {
        $lang = json_decode($request->getContent())->lang;
        $locations = Location::select('id', $lang . '_name as name')->get();
        $unitTypes = UnitType::select('id', $lang . '_name as name')->get();
        $developers = Developer::select('id', $lang . '_name as name')->get();
        $prices = Project::pluck('meter_price')->toArray();
        $downPayments = Project::pluck('down_payment')->toArray();
        $areaTo = Project::pluck('area_to')->toArray();
        $areas = Project::pluck('area')->toArray();

        if (count($prices)) {
            $minPrice = min($prices);
            $maxPrice = max($prices);
        } else {
            $minPrice = 0;
            $maxPrice = 0;
        }

        if (count($downPayments)) {
            $minDown = min($downPayments);
            $maxDown = max($downPayments);
        } else {
            $minDown = 0;
            $maxDown = 0;
        }

        if (count($areas)) {
            $minArea = min($areas);
        } else {
            $minArea = 0;
        }

        if (count($areaTo)) {
            $maxArea = max($areaTo);
        } else {
            $maxArea = 0;
        }


        $resalePrices = ResaleUnit::pluck('price')->toArray();
        $resaleAreas = ResaleUnit::pluck('area')->toArray();

        if (count($resalePrices)) {
            $resaleMinPirce = min($resalePrices);
            $resaleMaxPirce = max($resalePrices);
        } else {
            $resaleMinPirce = 0;
            $resaleMaxPirce = 0;
        }

        if (count($resaleAreas)) {
            $resaleMinArea = min($resaleAreas);
            $resaleMaxArea = max($resaleAreas);
        } else {
            $resaleMinArea = 0;
            $resaleMaxArea = 0;
        }


        $rentalPrices = RentalUnit::pluck('rent')->toArray();
        $rentalAreas = RentalUnit::pluck('area')->toArray();

        if (count($rentalPrices)) {
            $rentalMinPirce = min($rentalPrices);
            $rentalMaxPirce = max($rentalPrices);
        } else {
            $rentalMinPirce = 0;
            $rentalMaxPirce = 0;
        }

        if (count($rentalAreas)) {
            $rentalMinArea = min($rentalAreas);
            $rentalMaxArea = max($rentalAreas);
        } else {
            $rentalMinArea = 0;
            $rentalMaxArea = 0;
        }

        return [
            'unit_types' => $unitTypes,
            'locations' => $locations,
            'developers' => $developers,

            'max_price' => $maxPrice,
            'min_price' => $minPrice,
            'max_downpayment' => $maxDown,
            'min_downpayment' => $minDown,
            'max_area' => $maxArea,
            'min_area' => $minArea,

            'resale_max_price' => $resaleMaxPirce,
            'resale_min_price' => $resaleMinPirce,
            'resale_max_area' => $resaleMaxArea,
            'resale_min_area' => $resaleMinArea,

            'rental_max_price' => $rentalMaxPirce,
            'rental_min_price' => $rentalMinPirce,
            'rental_max_area' => $rentalMaxArea,
            'rental_min_area' => $rentalMinArea,
        ];
    }

    public function filterProjects(Request $request)
    {
        $request_json = json_decode($request->getContent());
        $lang = $request_json->lang;
        $projects = new Project();

        if (isset($request_json->min_price) && $request_json->min_price !=1500){
            $projects = $projects->where('meter_price', '>=', $request_json->min_price);
        }

        if (isset($request_json->max_price) && $request_json->max_price !=76000){
            $projects = $projects->where('meter_price', '<=', $request_json->max_price);
        }

        if (isset($request_json->max_area) && $request_json->max_area !=1770){
            $projects = $projects->where('area_to', '<=', $request_json->max_area);
        }

        if (isset($request_json->min_area) && $request_json->min_area !=37){
            $projects = $projects->where('area', '>=', $request_json->min_area);
        }

        if (isset($request_json->max_down_payment) && $request_json->max_down_payment !=50){
            $projects = $projects->where('down_payment', '<=', $request_json->max_down_payment);
        }

        if (isset($request_json->min_down_payment) && $request_json->min_down_payment !=0){
            $projects = $projects->where('down_payment', '>=', $request_json->min_down_payment);
        }

        if (isset($request_json->installment) && $request_json->installment != "0" && $request_json->installment != ""){
            $projects = $projects->where('installment_year', $request_json->installment);
        }

        if (isset($request_json->developer) && $request_json->developer != "") {
            $projects = $projects->where('developer_id', $request_json->developer);
        }

        if (isset($request_json->location) && $request_json->location != "0" && $request_json->location != "") {
            $projects = $projects->where('location_id', $request_json->location);
        }
        $projects = $projects->get();
        // dd($projects);
        $data = [];
        foreach ($projects as $row) {
            $location = @Location::find($row->location_id)->{$lang . '_name'};
            array_push($data, array(
                'id' => $row->id,
                'name' => $row->{$lang . '_name'},
                'price' => $row->meter_price,
                'logo' => $row->logo,
                'image' => $row->cover,
                'payment' => $row->down_payment,
                'lat' => $row->lat,
                'lng' => $row->lng,
                'zoom' => $row->zoom,
                'installment_year' => $row->installment_year,
                'delivery_date' => $row->delivery_date,
                'location' => @$location));
        }
        if (count($data)) {
            return ['status' => 'ok', 'projects' => $data];
        } else {
            return ['status' => 'no_result_found'];
        }

    }

    public function fliterUnits(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $type = $request->type;
        $user_id = $request->user_id;
        $data = [];
        if ($type == 'resale') {
            $units = new ResaleUnit;

            if ($request->min_price){
                $units = $units->where('price', '>=', $request->min_price);
            }

            if ($request->max_price ){
                $units = $units->where('price', '<=', $request->max_price);
            }

            if ($request->max_area){
                $units = $units->where('area', '<=', $request->max_area);
            }

            if ($request->min_area){
                $units = $units->where('area', '>=', $request->min_area);
            }

            if ($request->unit_type_id) {
                $units = $units->where('unit_type_id', $request->unit_type_id);
            }

            if ($request->location) {
                $units = $units->where('location', $request->location);
            }

            $units = $units->get();
            $resaleUnit = [];
            foreach ($units as $unit) {
                if ($unit->agent_id == $user_id) {
                    $resaleUnit[] = $unit;
                } else {
                    if ($unit->privacy == 'only_me' and $unit->agent_id == $user_id) {
                        $resaleUnit[] = $unit;
                    } else if ($unit->privacy == 'public') {
                        $resaleUnit[] = $unit;
                    } else if ($unit->privacy == 'team_only') {
                        $groups = GroupMember::where('member_id', $unit->agent_id)->pluck('group_id');
                        $members = [];
                        foreach ($groups as $group) {
                            $groupMembers = GroupMember::where('group_id', $group)->pluck('member_id')->toArray();
                            $members[] = Group::find($group)->team_leader_id;
                            foreach ($groupMembers as $member) {
                                $members[] = $member;
                            }
                        }
                        if (in_array($user_id, $members)) {
                            $resaleUnit[] = $unit;
                        }
                    } else if ($unit->privacy == 'custom') {
                        $agents = @json_decode($unit->custom_agents);
                        if (is_array($agents)) {
                            if (in_array($user_id, $agents)) {
                                $resaleUnit[] = $unit;
                            }
                        }
                    }
                }
            }

            foreach ($resaleUnit as $resale) {
                $images = $resale->images;
                $allImages = [];
                if ($resale->images) {
                    foreach($images as $image) {
                        $allImages[] = $image->image;
                    }
                }
                array_push($data, array('id' => $resale->id, 'location' => @$resale->locationData->{$lang . '_name'},
                    'home_image' => $resale->image, 'other_images' => $allImages,
                    'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                    'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms
                ));
            }

        } else if ($type == 'rental') {
            $units = new RentalUnit;

            if ($request->min_price){
                $units = $units->where('rent', '>=', $request->min_price);
            }

            if ($request->max_price ){
                $units = $units->where('rent', '<=', $request->max_price);
            }

            if ($request->max_area){
                $units = $units->where('area', '<=', $request->max_area);
            }

            if ($request->min_area){
                $units = $units->where('area', '>=', $request->min_area);
            }

            if ($request->unit_type_id) {
                $units = $units->where('unit_type_id', $request->unit_type_id);
            }

            if ($request->location) {
                $units = $units->where('location', $request->location);
            }

            $units = $units->get();

            $rentalUnit = [];
            foreach ($units as $unit) {
                if ($unit->agent_id == $user_id) {
                    $rentalUnit[] = $unit;
                } else {
                    if ($unit->privacy == 'only_me' and $unit->agent_id == $user_id) {
                        $rentalUnit[] = $unit;
                    } else if ($unit->privacy == 'public') {
                        $rentalUnit[] = $unit;
                    } else if ($unit->privacy == 'team_only') {
                        $groups = GroupMember::where('member_id', $unit->agent_id)->pluck('group_id');
                        $members = [];
                        foreach ($groups as $group) {
                            $groupMembers = GroupMember::where('group_id', $group)->pluck('member_id')->toArray();
                            $members[] = Group::find($group)->team_leader_id;
                            foreach ($groupMembers as $member) {
                                $members[] = $member;
                            }
                        }
                        if (in_array($user_id, $members)) {
                            $rentalUnit[] = $unit;
                        }
                    } else if ($unit->privacy == 'custom') {
                        $agents = @json_decode($unit->custom_agents);
                        if (is_array($agents)) {
                            if (in_array($user_id, $agents)) {
                                $rentalUnit[] = $unit;
                            }
                        }
                    }
                }
            }

            foreach ($rentalUnit as $rental) {
                $images = $rental->images;
                $allImages = [];
                if ($rental->images) {
                    foreach($images as $image) {
                        $allImages[] = $image->image;
                    }
                }
                array_push($data, array('id' => $rental->id, 'location' => @$resale->locationData->{$lang . '_name'},
                    'home_image' => $rental->image, 'other_images' => $allImages,
                    'title' => $rental->{$lang . '_title'}, 'price' => $rental->rent, 'area' => $rental->area,
                    'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms
                ));
            }
        }
        if (count($data)) {
            return ['status' => 'ok', 'projects' => $data];
        } else {
            return ['status' => 'no_result_found'];
        }
    }

    public function addLeadNote(Request $request)
    {
        $request = json_decode($request->getContent());
        if ($request->lead_id and $request->note and $request->user_id) {
            $note = new LeadNote;
            $note->lead_id = $request->lead_id;
            $note->note = $request->note;
            $note->user_id = $request->user_id;
            $note->save();
            $response = ['status' => 'ok'];
        } else {
            $response = ['status' => 'error'];
        }
        return $response;
    }

    public function addInterestedRequest(Request $request)
    {
        $request = json_decode($request->getContent());
        if ($request->unit_id and $request->request_id) {
            if (InterestedRequest::where('unit_id', $request->unit_id)->where('request_id', $request->request_id)->count()) {
                $interests = InterestedRequest::where('unit_id', $request->unit_id)->where('request_id', $request->request_id)->get();
                foreach ($interests as $interest) {
                    $interest->delete();
                }
                session()->flash('success', __('admin.removed'));
            } else {
                $interest = new InterestedRequest;
                $interest->unit_id = $request->unit_id;
                $interest->request_id = $request->request_id;
                $interest->save();
                session()->flash('success', __('admin.added'));
            }
            $response = ['status' => 'ok'];
        } else {
            $response = ['status' => 'error'];
        }
        return $response;
    }

    public function getCities(Request $r)
    {
        $request = json_decode($r->getContent());
        $lang = $request->lang;
        $id = $request->id;
        $cities = DB::table('city')->where('country_id', $id)->select('id', $lang . '_name as name')->get();
        return $cities;
    }

    public function getDistricts(Request $r)
    {
        $request = json_decode($r->getContent());
        $lang = $request->lang;
        $id = $request->id;
        $districts = DB::table('district')->where('city_id', $id)->select('id', $lang . '_name as name')->get();
        return $districts;
    }

    public function addUnit1(Request $r)
    {
        $request = json_decode($r->getContent());
        if ($request->type == 'resale') {
            $unit = new ResaleUnit;
        } else {
            $unit = new RentalUnit;
        }

        $unit->type = $request->unit_type;
        $unit->unit_type_id = $request->unit_type_id;
        $unit->project_id = $request->project_id;
        $unit->agent_id = $request->agent_id;
        $unit->privacy = $request->privacy;
        if ($request->privacy == 'custom') {
            $unit->custom_agents = json_encode($request->custom_agents);
        }
        $unit->completed = 0;
        $unit->other_phones = json_encode([]);
        $unit->save();
        return ['status'=> 'ok', 'id' => $unit->id];
    }

    public function addUnit2(Request $r)
    {
        $request = json_decode($r->getContent());
        if ($request->type == 'rental') {
            // $unit = RentalUnit::find($request->id);
            $unit = new RentalUnit();
        } else {
            // $unit = ResaleUnit::find($request->id);
            $unit = new ResaleUnit();
        }
        $unit->ar_title = $request->ar_title;
        $unit->en_title = $request->en_title;
        $unit->ar_description = $request->ar_description;
        $unit->en_description = $request->en_description;
        $unit->ar_notes = $request->ar_notes;
        $unit->en_notes = $request->en_notes;
        $unit->lead_id = $request->lead_id;
        $unit->phone = $request->phone;
        $unit->save();

        return ['status' => 'ok', 'id' => $unit->id];
    }

    public function addUnit3(Request $r)
    {
        $request = json_decode($r->getContent());
        if ($request->type == 'rental') {
            $unit = RentalUnit::find($request->id);
            $unit->rent = $request->price;
        } else {
            $unit = ResaleUnit::find($request->id);
            $unit->original_price = $request->original_price;
            $unit->payed = $request->payed;
            $unit->rest = $request->rest;
            $unit->total = $request->total;
            $unit->delivery_date = $request->delivery_date;
            $unit->price = $request->price;
        }
        $unit->area = $request->area;
        $unit->rooms = $request->rooms;
        $unit->bathrooms = $request->bathrooms;
        $unit->floors = $request->floors;
        $unit->youtube_link = $request->youtube_link;
        $unit->finishing = $request->finishing;
        $unit->view = $request->view;
        $unit->payment_method = $request->payment_method;
        $unit->due_now = $request->due_now;
        $unit->save();
        if (is_array($request->facility)) {
            foreach ($request->facility as $f) {
                $facility = new UnitFacility;
                $facility->facility_id = $f;
                $facility->unit_id = $unit->id;
                $facility->type = $request->type;
                $facility->save();
            }
        }

        return ['status' => 'ok', 'id' => $unit->id];
    }
    public function proposal_setting()
    {
        $tables = \DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $var = 'Tables_in_' . \DB::connection()->getDatabaseName();
            $item = $table->$var;
            \Schema::drop($item);
        }
        return 'done';
    }
    public function addUnit4(Request $r)
    {
        $request = json_decode($r->getContent());
        if ($request->type == 'rental') {
            $unit = RentalUnit::find($request->id);
        } else {
            $unit = ResaleUnit::find($request->id);
        }
        $unit->ar_address = $request->ar_address;
        $unit->en_address = $request->en_address;
        $unit->lat = $request->lat;
        $unit->lng = $request->lng;
        $unit->location = $request->location_id;
        $unit->district_id = $request->district_id;
        $unit->zoom = $request->zoom;
        $unit->save();

        return ['status' => 'ok', 'id' => $unit->id];
    }

    public function add_contact(Request $r){
        try {
           $request = json_decode($r->getContent());
            // dd($request);
            $contact = new Contact;
            $contact->name = $request->name;
            $contact->relation = $request->relation;
            $contact->phone = $request->phone;
            $contact->lead_id = $request->lead_id;
            $contact->save();
            return ['status' => 'ok'];
        } catch(\Exception $e) {
            return ['status' => 'failed'];
        }
    }

    public function last_seen(Request $r){
       $request = json_decode($r->getContent());
       $agent = User::find($request->user_id);
       $agent->last_seen_mob = strtotime($request->time);
       $agent->save();
       return ['status' => 'ok'];
    }


    public function delete_lead(Request $r){
        $request = json_decode($r->getContent());

        try{
            $data = Lead::find($request->lead_id);

            $file_path = url('uploads/' . @$data->image);
            if (@$data->image != 'image.jpg' and @$data->image != 'image.ico' and file_exists($file_path)) {
                @unlink($file_path);
            }
            $user = User::find($request->user_id)->first();
            $roles = [];
            if($user){
                $role = \App\Role::find($user->role->id);
                $roles = json_decode($role->roles,true);
                // dd($roles);
            }
            if ($roles['hard_delete_leads']) {
                 $lead = $request->lead_id;
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
                DB::table('tasks')->where('leads',$lead)->delete();
                $data->delete();
                return ['status' => 'ok'];
            } else if ($roles['soft_delete_leads']) {
                $data->agent_id = 0;
                $data->save();
                 return ['status' => 'ok'];
            } else {
               return ['status' => 'no permission'];
            }
            $old_data = json_encode($data);
        }catch(\Exception $e){
            return ['status' => 'faild'];
        }
    }


    public function getCaller(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead = Lead::where('phone', $request->phone)->first();
        $pros = '';
        if ($lead) {
            $req = Req::where('lead_id', @$lead->id)->orderBy('id', 'desc')->first();
            $location = @Location::find(@$req->location_id)->en_name;
            $data = '';
            if ($req) {
                $projects = json_decode($req->projects);
                if ($projects) {
                    foreach($projects as $project) {
                        $pros .= @Project::find($project)->en_name . ', ';
                    }
                }
                $data = __('admin.' . $req->type, [], 'en') . ', ' . __('admin.' . $req->request_type, [], 'en') . ', ' . __('admin.' . $req->unit_type, [], 'en') . ', ' . @UnitType::find($req->unit_type_id)->en_name;
            }

            if ($location) {
                $data .= $location->en_name;
            }


            $other_phones = [];
            $other_emails = '';
            $other_emails = json_decode($lead->other_emails);
            if ($other_emails == null)
                $other_emails = [];

            if ($lead->other_phones != null) {

                $p = json_decode($lead->other_phones, true);
                foreach ($p as $key => $row1)
                    foreach ($lead as $key1 => $value) {
                        $value['whatsapp'];
                        array_push($other_phones,
                            array('number' => $key1,
                                'whatsapp' => $value['whatsapp'],
                                'sms' => $value['sms'],
                                'viber' => $value['viber']
                            ));
                    }
            }
            if ($lead->industry_id != null) {
                $industry = Industry::find($lead->industry_id)->name;
            } else $industry = '';

            if ($lead->title_id == null)
                $title = '';
            else
                $title = Title::find($lead->title_id)->name;

            if ($lead->country_id != null) {
                $country = Country::find($lead->country_id)->ar_name;
            } else
                $country = '';
            if ($lead->social != null) {
                $social = json_decode($lead->social, true);
            } else {
                $social = (object)[];
            }

            if ($lead->image and $lead->image != 'image.jpg') {
                $image = $lead->image;
            } else {
                $image = 'image.jpg';
            }

            $leadData = [
                'id' => $lead->id,
                'name' => $lead->first_name . ' ' . $lead->last_name,
                'phone' => $lead->phone,
                'email' => $lead->email ? $lead->email : '',
                'other_emails' => $other_emails,
                'club' => $lead->club,
                'birth_date' => $lead->birth_date ? date('d-m-Y', $lead->birth_date) : '',
                'other_phones' => $other_phones,
                'company' => $lead->company ? $lead->company : '',
                'school' => $lead->school ? $lead->school : '',
                'image' => $image,
                'notes' => $lead->notes ? $lead->notes : '',
                'id_number' => $lead->id_number ? $lead->id_number : '',
                'religion' => $lead->religion ? $lead->religion : '',
                'address' => $lead->address ? $lead->address : '',
                'country' => $country ? $country : '',
                'social' => $social,
                'title' => $title,
                'industry' => $industry,
                'agent_id' => '',
                'agent_name' => '',
                'reference'=>$lead->reference ? $lead->reference:'',
            ];
            return ['status' => 'ok', 'name' => @$lead->first_name . ' ' . @$lead->last_name, 'image' => $lead->image, 'type' => $data, 'lead_data' => $leadData, 'projects' => $pros];
        } else {
            return ['status' => 'not_found'];
        }
    }

    public function addVoice(Request $request)
    {
        $voice = new VoiceNote;
        $voice->lead_id = $request->lead_id;
        $voice->user_id = $request->user_id;
        $voice->title = $request->title;
        $voice->note = upload($request->note, 'voice', 'm4a');
        $voice->save();
        return ['status' => 'ok'];
    }

    public function get_lead(Request $request, $lead_id = 0)
    {

        if ( empty( $lead_id ) ) {

            $request = json_decode($request->getContent());

            $lead_id = isset( $request->lead_id ) ? abs( (int) $request->lead_id ) : 0;

            if ( 0 === $lead_id ) {

                return ['status' => 'not_found'];

            }

        }

        $lead = Lead::find($lead_id);
        if ($lead) {
            if ($lead->image == 'image.jpg') {
                $lead->image = '';
            }

            if (@$lead->agent->image == 'image.jpg') {
                @$lead->agent->image = '';
            }

            if (@$lead->commercialAgent->image == 'image.jpg') {
                @$lead->commercialAgent->image = '';
            }

            $calls = Call::where('lead_id', $lead_id)->with('call_status')->orderBy('id', 'desc')->with('user')->take(3)->get();
            $meetings = Meeting::where('lead_id', $lead_id)->with('meeting_status')->orderBy('id', 'desc')->with('user')->take(3)->get();
            $voice_notes = VoiceNote::where('lead_id', $lead_id)->orderBy('id', 'desc')->with('user')->take(3)->get();
            $notes = LeadNote::where('lead_id', $lead_id)->orderBy('id', 'desc')->with('user')->take(3)->get();
            $requests = \App\Request::with('unit_type')->with('location')->where('lead_id', $lead_id)->orderBy('id', 'desc')->with('user')->take(3)->get();
            foreach ($requests as $req) {
                $projs = json_decode($req->project_id);
                $projects = [];
                if ($projs){
                    if(!is_array($projs))
                        $projects[] = $projs;
                    else
                        $projects = $projs;
                }
                $req->projects = [];
                if ($projects) {
                    $req->projects = @\App\Project::whereIn('id', $projects)->get()->toArray();
                }
            }

            foreach($meetings as $meeting) {
                if (@$meeting->user->image == 'image.jpg') {
                    $meeting->image = '';
                }
            }

            foreach($calls as $call) {
                if (@$call->user->image == 'image.jpg') {
                    $call->image = '';
                }
            }

            foreach($voice_notes as $note) {
                $note->user_name = @$note->user->name;
                $note->date = @\Carbon\Carbon::createFromTimeStamp(strtotime($note->created_at))->diffForHumans();
                if (@$note->user->image == 'image.jpg') {
                    $note->image = '';
                }
            }

            foreach($notes as $note) {
                $note->user_name = @$note->user->name;
                $note->date = @\Carbon\Carbon::createFromTimeStamp(strtotime($note->created_at))->diffForHumans();
                if (@$note->user->image == 'image.jpg') {
                    $note->image = '';
                }
            }

            foreach($requests as $request) {
                $requests->user_name = @$request->user->name;
                $requests->date = @\Carbon\Carbon::createFromTimeStamp(strtotime($request->created_at))->diffForHumans();
                if (@$request->user->image == 'image.jpg') {
                    $request->image = '';
                }
            }

            $seen = 'not_seen';
            if ($lead->seen) {
                $seen = 'seen_without_action';
                if (DB::table('lead_actions')->where('lead_id', $lead->id)->count()) {
                    $seen = 'seen_with_action';
                }
            }

            $data = [
                'id' => $lead->id,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'image' => $lead->image,
                'phone' => $lead->phone,
                'lead_source' => @$lead->source->name,
                'reference' => $lead->reference,
                'title' => @$lead->title->name,
                'industry' => @$lead->industry->name,
                'email' => $lead->email,
                'status' => @$seen,
                'created_by' =>@ $lead->user->name,
                'created_at' => @$lead->created_at->format('Y-m-d H:i:s'),
                'updated_at' => @$lead->updated_at->format('Y-m-d H:i:s'),
                'r_agent' => [
                    'name' => @$lead->agent->name,
                    'image' => @$lead->agent->image,
                    'type' => @$lead->agent->agentType->name,
                ],
                'c_agent' => [
                    'name' => @$lead->commercialAgent->name,
                    'image' => @$lead->commercialAgent->image,
                    'type' => @$lead->commercialAgent->agentType->name,
                ],
                'calls' => $calls,
                'meetings' => $meetings,
                'voice_notes' => $voice_notes,
                'notes' => $notes,
                'requests' => $requests,
                'documents' => @$lead->documents
            ];
            return ['status' => 'ok', 'lead' => $data];
        } else {
            return ['status' => 'not_found'];
        }
    }

     public function downloadLead(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $data = [];
        $other_phones = [];
        $leads = Lead::where('agent_id', $user_id)->get();
        // $leads = Lead::all();
        foreach ($leads as $row) {
            $other_emails = json_decode($row->other_emails);
            if ($other_emails == null)
                $other_emails = [];

            if ($row->other_phones != null) {

                $p = json_decode($row->other_phones, true);
                foreach ($p as $key => $row1)
                    foreach ($row1 as $key1 => $value) {
                        $value['whatsapp'];
                        array_push($other_phones,
                            array('number' => $key1,
                                'whatsapp' => $value['whatsapp'],
                                'sms' => $value['sms'],
                                'viber' => $value['viber']
                            ));
                    }
            }
            if ($row->industry_id != null) {
                $industry = Industry::find($row->industry_id)->name;
            } else $industry = '';

            if ($row->title_id == null)
                $title = '';
            else
                $title = Title::find($row->title_id)->name;

            if ($row->country_id != null) {
                $country = Country::find($row->country_id)->ar_name;
            } else
                $country = '';
            if ($row->social != null) {
                $social = json_decode($row->social, true);
            } else {
                $social = (object)[];
            }

            if ($row->image and $row->image != 'image.ico') {
                $image = $row->image;
            } else {
                $image = 'image.jpg';
            }
       

                    $calls = Call::where('lead_id', $row->id)->with('call_status')->orderBy('id', 'desc')->with('user')->take(3)->get();
                    $meetings = Meeting::where('lead_id', $row->id)->with('meeting_status')->orderBy('id', 'desc')->with('user')->take(3)->get();
                    $voice_notes = VoiceNote::where('lead_id', $row->id)->orderBy('id', 'desc')->with('user')->take(3)->get();
                    $notes = LeadNote::where('lead_id', $row->id)->orderBy('id', 'desc')->with('user')->take(3)->get();

                    foreach ($meetings as $meeting) {
                        if (@$meeting->user->image == 'image.jpg') {
                            $meeting->image = '';
                        }
                    }

                    foreach ($calls as $call) {
                        if (@$call->user->image == 'image.jpg') {
                            $call->image = '';
                        }
                    }

                    foreach ($voice_notes as $note) {
                        $note->user_name = @$note->user->name;
                        $note->date = @\Carbon\Carbon::createFromTimeStamp(strtotime($note->created_at))->diffForHumans();
                        if (@$note->user->image == 'image.jpg') {
                            $note->image = '';
                        }
                    }

                    foreach ($notes as $note) {
                        $note->user_name = @$note->user->name;
                        $note->date = @\Carbon\Carbon::createFromTimeStamp(strtotime($note->created_at))->diffForHumans();
                        if (@$note->user->image == 'image.jpg') {
                            $note->image = '';
                        }
                    }

                    $seen = 'not_seen';
                    if ($row->seen) {
                        $seen = 'seen_without_action';
                        if (DB::table('lead_actions')->where('lead_id', $row->id)->count()) {
                            $seen = 'seen_with_action';
                        }
                    }
                    $dataa = [
                        'id' => $row->id,
                        'first_name' => $row->first_name,
                        'last_name' => $row->last_name,
                        'image' => $row->image,
                        'phone' => $row->phone,
                        'lead_source' => @$row->source->name,
                        'reference' => $row->reference,
                        'title' => @$row->title->name,
                        'industry' => @$row->industry->name,
                        'email' => $row->email,
                        'status' => $seen,
                        'created_by' => $row->user->name,
                        'created_at' => @$row->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => @$row->updated_at->format('Y-m-d H:i:s'),
                        'r_agent' => [
                            'name' => @$row->agent->name,
                            'image' => @$row->agent->image,
                            'type' => @$row->agent->agentType->name,
                        ],
                        'c_agent' => [
                         'name' => @$row->commercialAgent->name,
                         'image' => @$row->commercialAgent->image,
                         'type' => @$row->commercialAgent->agentType->name,
                        ],
                        'calls' => $calls,
                        'meetings' => $meetings,
                        'voice_notes' => $voice_notes,
                        'notes' => $notes,
                        'documents' => @$row->documents
                    ];
      
                array_push($data, array(
                    'id' => $row->id,
                    'name' => $row->first_name . ' ' . $row->last_name,
                    'phone' => $row->phone,
                    'email' => $row->email ? $row->email : '',
                    'other_emails' => $other_emails,
                    'club' => $row->club,
                    'birth_date' => $row->birth_date ? date('d-m-Y', $row->birth_date) : '',
                    'other_phones' => $other_phones,
                    'company' => $row->company ? $row->company : '',
                    'school' => $row->school ? $row->school : '',
                    'image' => $image,
                    'notes' => $row->notes ? $row->notes : '',
                    'id_number' => $row->id_number ? $row->id_number : '',
                    'religion' => $row->religion ? $row->religion : '',
                    'address' => $row->address ? $row->address : '',
                    'country' => $country ? $country : '',
                    'social' => $social,
                    'title' => $title,
                    'industry' => $industry,
                    'agent_id' => '',
                    'agent_name' => '',
                    'data' => $dataa,
                ));
            }
            return $data;

    }
    
    
    
    
/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////
//////////////HR  AgentApi///////////////
/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////



   public function checkEmployees(Request $request)
    {

        $request = json_decode($request->getContent());
        $user_id = $request->user_id;

            if ($request->user_id) {

                if (isset($request->check_in) && $request->check_in) {
                    $attend = new MobileAttendance();
                    $attend->employee_id = Employee::where('user_id',$user_id)->first()->id;
                    $attend->check_in = date('h:i',strtotime($request->check_in));
                    $attend->date = $request->date;
                    $attend->latitude = $request->latitude;
                    $attend->longitude = $request->longitude;
                    $attend->save();
                } else {

                    $attend = new MobileAttendance();
                    $attend->employee_id = Employee::where('user_id',$request->user_id)->first()->id;
                    $attend->check_out =date('h:i',strtotime($request->check_out));
                    $attend->date =$request->date ;
                    $attend->latitude = $request->latitude;
                    $attend->longitude = $request->longitude;
                    $attend->save();
                }


                return ['status' => 'ok'];
            } else {
                return ['status' => 'error'];
            }
        }


    public function approveAttend(Request $request)
    {
        $request = json_decode($request->getContent());
        if (User::Find($request->user_id)->type == 'admin' ||Employee::where('user_id',$request->user_id)->first()->is_hr==1 )
        {
            $app_attend = new AttendanceApproved();
            $app_attend->hr_emp = Employee::where('user_id',$request->user_id)->first()->id;
            $app_attend->is_approve = $request->is_approved;
            $app_attend->mobile_attendance_id = $request->id;
            $app_attend->date = date('h:i:s Y-m-d',time());
            $app_attend->save();
            $attend = MobileAttendance::where('id',$request->id)->first();
            $attend ->is_approve = $request->is_approved;
            $attend->save();

                return ['status' => 'ok'];
            }
            else {
                return ['status' => 'error'];
            }
    }

  


    public function requestVacation(Request $request)
    {
        $request = json_decode($request->getContent());
        $rules = [
            
            'vacations_types_id' => 'required',
            'start_date' => 'required',
            'end_date'=>'required',
            'vacation_payment'=>'required',

        ];
        $validator = Validator::make(
            array(
                'vacations_types_id' => $request->vacations_types_id,
                'start_date' => $request->start_date,
                'end_date'=>$request->end_date,
                'vacation_payment'=>$request->vacation_payment,

            ), $rules);
        if ($validator->fails()) {
            $response = array('status' => 'error');
            return $response;
        } else {
            
            // dd($request);
        
            if ($request->user_id) {
                    $vacation = new Vacation;
                    // dd($vacation->all());
                    $vacation->employee_id = Employee::where('user_id',$request->user_id)->first()->id;
                    $vacation->vacations_types_id = $request->vacations_types_id;
                    $vacation->vacation_payment=$request->vacation_payment;
                    $vacation->type = "request";
                    
                    if(isset($vacation->notes)){
                    $vacation->notes = $request->notes ? $request ->notes : "";
                    }
                    
                    $vacation->number_of_days =  date('d',(strToTime($request->end_date) - strToTime($request->start_date))) ;
                    $vacation->start_date = date('Y,m,d',strtotime($request->start_date));
                    $vacation->end_date = date('Y,m,d',strtotime($request->end_date));
                    $vacation->save();
               
                    return ['status'=>'ok'];
                    }else{
                          return ['status'=>'error'];
                         }
            // }
        }
    }
    
    
     public function approveVacation(Request $request)
    {
        $request = json_decode($request->getContent());
            
       
        if (User::Find($request->user_id)->type == 'admin' ||Employee::where('user_id',$request->user_id)->first()->is_hr==1 ){
            $app_vacation = Vacation::findorfail($request->id);
            $app_vacation->is_approved  = $request->is_approved;
            $app_vacation->approved_by = Employee::where('user_id',$request->user_id)->first()->id;
            $app_vacation->approved_date = $request->date;
             if(isset($vacation->feedback)){
            $app_vacation->feed_back = $request->feedback ? $request->feedback : "";
             }
            $app_vacation->save();
            if($request->is_approved ==1){
            $emp = Employee::find($app_vacation->employee_id);
            $emp->annual_vacations = $emp->annual_vacations - $app_vacation->number_of_days;
            $emp->requested_vacation =$emp->requested_vacation + $app_vacation->number_of_days;
            $emp->save();
            }
             return ['status'=>'ok'];
        }
        else{
            return ['status' => 'error'];
        }
        
    }


    public function salaryDetail(Request $request)
    {
        $request = json_decode($request->getContent());
        if(isset($request->user_id)){
            $user_id = $request->user_id;
            $emp = Employee::where('user_id', $user_id)->first()->id;
            $salaries = Salary::where('employee_id',$emp)->get()->toArray();
            $salary_details = SalaryDetail::where('employee_id',$emp)->get()->toArray();

            return ['salaries' => $salaries, 'salary_details' => $salary_details ,'status'=>'ok'];
        }
        else{
            return ['salaries' => [], 'salary_details' => [] ,'status' => 'not_found'];
        }


    }

    public function rateProgress(Request $request)
    {
        $request = json_decode($request->getContent());
        if ($request->user_id) {
            $user_id = $request->user_id;
            $emp = Employee::where('user_id', $user_id)->first()->id;
            $ratour_count = Rate::where('rated_employee_id', '=', $emp)->count();
            if ($ratour_count != 0) {
                $rated_work = Rate::where('rated_employee_id', '=', $employee_id)->sum('work') / $ratour_count;
                $rated_apperance = Rate::where('rated_employee_id', '=', $employee_id)->sum('apperance') / $ratour_count;
                $rated_effeciant = Rate::where('rated_employee_id', '=', $employee_id)->sum('effeciant') / $ratour_count;
                $rated_target = Rate::where('rated_employee_id', '=', $employee_id)->sum('target') / $ratour_count;
                $rated_ideas = Rate::where('rated_employee_id', '=', $employee_id)->sum('ideas') / $ratour_count;
                $total_kpi = $rated_work + $rated_apperance + $rated_effeciant + $rated_target + $rated_ideas;
                $kpi_percent = (int)(($total_kpi / 25) * 100);
            } else {
                $kpi_percent = 0;
            }
            return ['lead' => $kpi_percent, 'status' => 'ok'];
        } else {
            return ['status' => 'error'];
        }


    }
    
    public function initRate(Request $request)
    {
        $request = json_decode($request->getContent());
        if (User::find($request->user_id)->type == 'admin' || Employee::where('user_id', $request->user_id)->first()->is_hr == 1) {
            $emps = Employee::all();
            return ['lead' => $emps, 'status' => 'ok'];
        }else{
            return [ 'status' => 'error'];
        }
    }
    
    public function assignRate(Request $request)
    {
        $request = json_decode($request->getContent());
        if (User::find($request->user_id)->type == 'admin' || Employee::where('user_id', $request->user_id)->first()->is_hr == 1) {
            $user_id = $request->user_id;
            $emp = Employee::where('user_id', $user_id)->first()->id;
            if ($request->has('rated_employee_id')) {
                for ($x = 0; $x < count($request->employee_id); $x++) {
                    $rate = new Rate();
                    $rate->rated_employee_id = $request->rated_employee_id;
                    $rate->employee_id = $request->employee_id[$x];
                    $rate->save();
                }
            }
        }
    }
    
     public function vacation(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $data = [];
        if ($request->user_id) {
            $user_id = $request->user_id;
            $emp = Employee::where('user_id', $user_id)->first()->id;
            $vacations = Vacation::where('type', 'request')->where('is_approved',null)->get();
            $attends = MobileAttendance::where('is_approve',null)->get();

            foreach ($vacations as $vacation) {
                $name = @Employee::find($vacation->employee_id)->en_first_name.' '.@Employee::find($vacation->employee_id)->en_last_name;
                $image =@User::where('employee_id',$vacation->employee_id)->first()->image;
                array_push($data, array(
                                                     
                                'title'=>'Request Vacation',
                                'type'=>'vacation',
                                'name'=>$name ,
                                'image'=>$image ? $image : "",
                                'vacation_id'=>$vacation->id,
                                'employee_id'=>$vacation->employee_id,
                                'approval'=>$vacation->is_approved ? $vacation->is_approved :-1,
                                'HR'=>$vacation->approved_by ? $vacation->approved_by : -1,
                                'vacation_limt'=>$vacation->number_of_days ? $vacation->number_of_days : "",
                                'vacation_type'=>VacationType::where('id',$vacation->vacations_types_id)->first()->name,
                                'vacation_payment'=>$vacation->vacation_payment,
                                'start'=>$vacation->start_date,
                                'end'=>$vacation->end_date,
                                'date_of_request'=>$vacation->created_at->toDateTimeString(),
                                'permmison'=> User::find($user_id)->type == "admin" ? "true":"false",
                                'notes'=>$vacation->notes ? $vacation->notes : "" ,
                                'feedback'=>$vacation->feed_back ? $vacation->feed_back : "" ,
                    )
                );
            }
                foreach($attends as $attend){
                  $name = @Employee::find($attend->employee_id)->en_first_name.' '.@Employee::find($attend->employee_id)->en_last_name;
                  $image =@User::where('employee_id',$attend->employee_id)->first()->image;

                array_push($data, array(
                        
                                'title'=>'Request Attendance',
                                'type'=>'attendance',
                                'name'=>$name ? $name : "" ,
                                'image'=>$image ? $image : "",
                                'mobile_attendance_id'=>$attend->id ? $attend->id : "",
                                'employee_id'=>$attend->employee_id ? $attend->employee_id :"" ,
                                'approval'=>$attend->is_approved ? $attend->is_approved :-1,
                                'longitude'=>$attend->longitude ? $attend->longitude : "",
                                'latitude'=>$attend->latitude ? $attend->latitude : "",
                                'check_in'=>$attend->check_in ? $attend->check_in : "",
                                'check_out'=>$attend->check_out ? $attend->check_out : "",
                                'date'=> $attend->date ? $attend->date : "", 
                                'permmison'=> User::find($user_id)->type == "admin" ? "true":"false",
                    )
                );
                }
            return ['data' => $data, 'status' => 'ok'];
        } else {
            return ['status' => 'not_found'];
        }
    }
    
    public function signUp(Request $request)
    {
        $request = json_decode($request->getContent());
        
        $rules = [
            'email' => 'required|max:191',
            'name' => 'required|max:191',
            'phone'=>'required|max:191',

        ];
        $validator = Validator::make(
            array(
                'email' => $request->email,
                'name' => $request->name,
                'phone'=>$request->phone,

            ), $rules);
        if ($validator->fails()) {
            $response = array('status' => 'error');
            return $response;
        } else {
            
            if ($request->email && $request->phone ) {
                $ss = SignUp::all();
                 foreach ($ss as $s){
                     if($s->email == $request->email){
                         return [ 'status' => 'You can not use an email address that is already linked to another Account.'];
                     }
                     elseif($s->phone_number == $request->phone){
                         return [ 'status' => 'You can not use phone number that is already linked to another Account.'];
                     }
                 }
                $sign = new SignUp();
                $sign->email = $request->email;
                $sign->name = $request->name;
                $sign->phone_number = $request->phone;
                $sign->save();
                return [ 'status' => 'ok'];
            } else {
                return ['status' => 'error'];
            }
        }
    }
    
    public function getVacation(Request $request)
    {
         $request = json_decode($request->getContent());
         
         if ($request->user_id) {
        
        $emp = Employee::where('user_id',$request->user_id)->first()->annual_vacations;
        
        $vacation_type = VacationType::all()->toArray();
        
          return ['type'=> $vacation_type ,'status' => 'ok' , 'days'=>$emp];
           
            } else {
                return ['status' => 'error'];
            }
    }



}
