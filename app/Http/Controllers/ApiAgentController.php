<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Country;
use App\Favorite;
use App\Industry;
use App\Lead;
use App\LeadDocument;
use App\LeadNote;
use App\Location;
use App\Message;
use App\Project;
use App\Property;
use App\Title;
use App\ToDo;
use App\UnitType;
use Illuminate\Http\Request;
use App\Meeting;
use App\Call;
use Validator;
use App\ResaleUnit;
use App\RentalUnit;
use App\Phase;
use App\Gallery;
use App\UnitFacility;
use App\ResalImage;
use App\RentalImage;
use App\Request as Request1;
use App\Land;
use App\GroupMember;
use App\Group;
use App\Facility;
use App\Icon;
use App\Interested;
use App\User;
use App\InterestedRequest;
use DB;
use App\VoiceNote;
use Illuminate\Support\Facades\Auth;


class ApiAgentController extends Controller
{

    public function todo_info(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $user_id = $request->user_id;
        $locations = [];
        $projects = [];
        $leads = Lead::where('agent_id',$user_id)->select('id', 'first_name', 'last_name', 'phone')->get()->toArray();
        if ($lang == 'ar') {
            $projects = Project::select('id', 'ar_name as name')->get()->toArray();
            $locations = Location::select('id', 'ar_name as name')->get()->toArray();
        } else if ($lang == 'en') {
            $projects = Project::select('id', 'en_name as name')->get()->toArray();
            $locations = Location::select('id', 'en_name as name')->get()->toArray();
        }

        $data['projects'] = $projects;
        $data['lead'] = $leads;
        $data['location'] = $locations;

        return json_encode($data);
    }

    public function add_todo(Request $request)
    {
        // dd(strtotime(date('Y-m-d')));

        $requests = json_decode($request->getContent());

        foreach($requests->data as $request) {
            // dd($request);
            if($request->type=='others')
                $rules = [
                    'user_id' => 'required|numeric',
                    'date' => 'required|max:191',
                    'description' => 'required',
                    'time'=>'required',
                    'leads' => 'required',
                    'type' => 'required',
                ];
            else
                $rules = [
                    'user_id' => 'required|numeric',
                    'leads' => 'required',
                    'date' => 'required|max:191',
                    'description' => 'required',
                    'type' => 'required',
                ];

            $validator = Validator::make(array(
                'type' => $request->type,
                'user_id' => $request->user_id,
                'leads' => $request->leads,
                'date' => $request->date,
                'time'=> $request->time,
                'description' => $request->description,
            ), $rules);
            if ($validator->fails()) {
                $response = array(
                    "status" => 'error',
                );
            } else {
                $todo = new ToDo();
                $todo->user_id = $request->user_id;
                $todo->leads = $request->leads;
                $todo->due_date = strtotime($request->date);
                $todo->time = strtotime($request->time);
                $todo->to_do_type = $request->type;
                $todo->status = 'pending';
                $todo->description = $request->description;
                $todo->save();
                $response = array(
                    "status" => 'ok',
                );
            }
        }
        return $response;
    }

     public function add_todo_ios(Request $request)
    {
        // dd(strtotime(date('Y-m-d')));

        //$requests = json_decode($request->getContent());

            if($request->type=='others')
                $rules = [
                    'user_id' => 'required|numeric',
                    'date' => 'required|max:191',
                    'description' => 'required',
                    'time'=>'required',
                    'leads' => 'required',
                    'type' => 'required',
                ];
            else
                $rules = [
                    'user_id' => 'required|numeric',
                    'leads' => 'required',
                    'date' => 'required|max:191',
                    'description' => 'required',
                    'type' => 'required',
                ];

            $validator = Validator::make(array(
                'type' => $request->type,
                'user_id' => $request->user_id,
                'leads' => $request->leads,
                'date' => $request->date,
                'time'=> $request->time,
                'description' => $request->description,
            ), $rules);
            if ($validator->fails()) {
                $response = array(
                    "status" => 'error',
                );
            } else {
                $todo = new ToDo();
                $todo->user_id = $request->user_id;
                $todo->leads = $request->leads;
                $todo->due_date = strtotime($request->date);
                $todo->time = strtotime($request->time);
                $todo->to_do_type = $request->type;
                $todo->status = 'pending';
                $todo->description = $request->description;
                $todo->save();
                $response = array(
                    "status" => 'ok',
                );
            }

        return $response;
    }

/*
    public function get_leads(Request $request)
    {

        try {
        $request_json = json_decode($request->getContent());

        if ( !isset($request_json->user_id) ) {
            return [];
        }

        $user_id = $request_json->user_id;
        $data = [];
        $other_phones = [];
        $page = isset($request_json->page) ? abs( (int) $request_json->page ) : 0;

        // $leads = Lead::where('agent_id', $user_id)->offset(($page-1)*25)->limit(25)->get() ;

        $userData = User::find($user_id);
        $leads = Lead::getAgentLeads($userData, 25, $page);

        // if(count($leads = Lead::offset(($page-1)*25)->limit(25)->get()) < 25){
        //     $page ++;
        //     $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
        // // dd(count($leads));
        // }
        // else {
            // $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
                    // dd(count($leads));

        // }



        $get_full_info = isset($request_json->full_info) && 'yes' === $request_json->full_info;

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

                $lead['full_info'] = $agent_controller->get_lead( $request, $row->id );

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

    public function get_agent_leads(Request $request)
    {

        try {
        $request_json = json_decode($request->getContent());

        if ( !isset($request_json->user_id) ) {
            return [];
        }

        $user_id = $request_json->user_id;
        $agent_id = $request_json->agent_id;
        // dd($agent_id);
        $data = [];
        $other_phones = [];
        $page = isset($request_json->page) ? abs( (int) $request_json->page ) : 0;

        // $leads = Lead::where('agent_id', $user_id)->offset(($page-1)*25)->limit(25)->get() ;

        $userData = User::find($user_id);
        $leads = Lead::getAgentLeadsByAgent($userData, 25, $page, $agent_id);

        // if(count($leads = Lead::offset(($page-1)*25)->limit(25)->get()) < 25){
        //     $page ++;
        //     $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
        // // dd(count($leads));
        // }
        // else {
            // $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
                    // dd(count($leads));

        // }



        $get_full_info = isset($request_json->full_info) && 'yes' === $request_json->full_info;

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

                $lead['full_info'] = $agent_controller->get_lead( $request, $row->id );

            }

            $data[] = $lead;
        }  foreach ($data as $row) {
            $agents[] = $row['agent_id'];
            $agents[] = $row['commercial_agent_id'];
        }
        $agents = array_unique($agents);
        foreach ($agents as $agent) {
            $agentData = User::find($agent);
            if ($agentData) {
                $arr = [];
                $arr['name'] = $agentData->name;
                $arr['id'] = $agent;
                $agentsData[] = $arr;
            }
        }

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
*/

    public function get_leads(Request $request)
    {

        try {
        $request_json = json_decode($request->getContent());

        if ( !isset($request_json->user_id) ) {
            return [];
        }

        $user_id = $request_json->user_id;
        $data = [];
        $other_phones = [];
        $page = isset($request_json->page) ? abs( (int) $request_json->page ) : 0;

        // $leads = Lead::where('agent_id', $user_id)->offset(($page-1)*25)->limit(25)->get() ;

        $userData = User::find($user_id);
        $leads = Lead::getAgentLeads($userData, 25, $page);

        // if(count($leads = Lead::offset(($page-1)*25)->limit(25)->get()) < 25){
        //     $page ++;
        //     $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
        // // dd(count($leads));
        // }
        // else {
            // $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
                    // dd(count($leads));

        // }



        $get_full_info = isset($request_json->full_info) && 'yes' === $request_json->full_info;

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

                $lead['full_info'] = $agent_controller->get_lead( $request, $row->id );

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

        if ($userData->type != 'admin') {
            if (count(Group::where('team_leader_id', $userData->id)->get()) > 0) {
                $users = [];
                foreach (Group::where('team_leader_id', $userData->id)->get() as $group) {
                    if ($group->parent_id != 0) {
                        foreach (GroupMember::where('group_id', $group->id)->get() as $member) {
                            $users[] = $member->member_id;
                        }
                    }
                }
                $agentsData = User::whereIn('id', $users)->get()->toArray();
            }
            
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

    public function get_agent_leads(Request $request)
    {

        try {
        $request_json = json_decode($request->getContent());

        if ( !isset($request_json->user_id) ) {
            return [];
        }

        $user_id = $request_json->user_id;
        $agent_id = $request_json->agent_id;
        // dd($agent_id);
        $data = [];
        $other_phones = [];
        $page = isset($request_json->page) ? abs( (int) $request_json->page ) : 0;

        // $leads = Lead::where('agent_id', $user_id)->offset(($page-1)*25)->limit(25)->get() ;

        $userData = User::find($user_id);
        $leads = Lead::getAgentLeadsByAgent($userData, 25, $page, $agent_id);

        // if(count($leads = Lead::offset(($page-1)*25)->limit(25)->get()) < 25){
        //     $page ++;
        //     $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
        // // dd(count($leads));
        // }
        // else {
            // $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
                    // dd(count($leads));

        // }



        $get_full_info = isset($request_json->full_info) && 'yes' === $request_json->full_info;

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

                $lead['full_info'] = $agent_controller->get_lead( $request, $row->id );

            }

            $data[] = $lead;
        }  foreach ($data as $row) {
            $agents[] = $row['agent_id'];
            $agents[] = $row['commercial_agent_id'];
        }
        $agents = array_unique($agents);
        foreach ($agents as $agent) {
            $agentData = User::find($agent);
            if ($agentData) {
                $arr = [];
                $arr['name'] = $agentData->name;
                $arr['id'] = $agent;
                $agentsData[] = $arr;
            }
        }

        if ($userData->type != 'admin') {
            if (count(Group::where('team_leader_id', $userData->id)->get()) > 0) {
                $users = [];
                foreach (Group::where('team_leader_id', $userData->id)->get() as $group) {
                    if ($group->parent_id != 0) {
                        foreach (GroupMember::where('group_id', $group->id)->get() as $member) {
                            $users[] = $member->member_id;
                        }
                    }
                }
                $agentsData = User::whereIn('id', $users)->get()->toArray();
            }
            
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

    
    public function get_leads_sync(Request $request)
    {

            try {
                $updates = [];
                $deleteArray = [];
                $request_json = json_decode($request->getContent());
                foreach($request_json->updatesArray as $key => $lead){
                    $updates[] = json_decode($lead);
                }

                if ( !isset($request_json->user_id) ) {
                    return [];
                }

            // dd($updates[1]);
            // save coming data
            // lead

            foreach($updates as $lead){
                // lead

                $oldlead = $lead->full_info->lead;
                $agent_id = $lead->agent_id;
                $lead_id = $oldlead->id;
                $newlead = Lead::find($oldlead->id);

                    if($newlead){
                        // dd($oldlead);
                        if (isset($oldlead->prefix_name)) {
                            $newlead->prefix_name = $oldlead->prefix_name;
                        }
                        if (isset($agent_id)) {
                            $newlead->agent_id = $agent_id;
                        }
                        $newlead->first_name = $oldlead->first_name;
                        $newlead->last_name = $oldlead->last_name;
                        if (isset($oldlead->middle_name)) {
                            $newlead->middle_name = $oldlead->middle_name;
                        }
                        if (isset($oldlead->ar_first_name)) {
                            $newlead->ar_first_name = $oldlead->ar_first_name;
                        }
                        if (isset($oldlead->ar_last_name)) {
                            $newlead->ar_last_name = $oldlead->ar_last_name;
                        }
                        if (isset($oldlead->ar_middle_name)) {
                            $newlead->ar_middle_name = $oldlead->ar_middle_name;
                        }
                        $newlead->email = $oldlead->email;
                        $newlead->phone = $oldlead->phone;
                        if (isset($oldlead->nationality)) {
                            $newlead->nationality = $oldlead->nationality;
                        }
                        if (isset($oldlead->country_id)) {
                            $newlead->country_id = $oldlead->country_id;
                        }
                        if (isset($oldlead->city_id)) {
                            $newlead->city_id = $oldlead->city_id;
                        }
                        if (isset($oldlead->address)) {
                            $newlead->address = $oldlead->address;
                        }
                        if (isset($oldlead->club)) {
                            $newlead->club = $oldlead->club;
                        }
                        if (isset($oldlead->title_id)) {
                            $newlead->title_id = $oldlead->title_id;
                        }
                        if (isset($oldlead->religion)) {
                            $newlead->religion = $oldlead->religion;
                        }
                        if (isset($oldlead->birth_date)) {
                            $newlead->birth_date = $oldlead->birth_date;//strtotime($oldlead->birth_date);
                        }
                        if (isset($oldlead->lead_source_id)) {
                            $newlead->lead_source_id = $oldlead->lead_source;
                        }
                        if (isset($oldlead->social)) {
                            $newlead->social = $oldlead->social;
                        }
                        if (isset($oldlead->industry_id)) {
                            $newlead->industry_id = $oldlead->industry_id;
                        }
                        if (isset($oldlead->company)) {
                            $newlead->company = $oldlead->company;
                        }
                        if (isset($oldlead->school)) {
                            $newlead->school = $oldlead->school;
                        }
                        if (isset($oldlead->facebook)) {
                            $newlead->facebook = $oldlead->facebook;
                        }
                        if (isset($oldlead->id_number)) {
                            $newlead->id_number = $oldlead->id_number;
                        }

                        $newlead->status = $oldlead->status;//'new';
                        if (isset($oldlead->other_phones)) {
                            $newlead->other_phones = $oldlead->other_phones;
                        }
                        if (isset($oldlead->other_emails)) {
                            $newlead->other_emails = $oldlead->other_emails;
                        }

                        // $newlead->notes = $oldlead->notes;
                        if (isset($oldlead->user_id)) {
                            $newlead->user_id = $oldlead->user_id;
                        }
                        if (isset($oldlead->image)) {
                            $newlead->image = $oldlead->image;
                        }

                        // dd($newlead);
                        $newlead->save();
                    } else {
                        $newlead = new Lead();
                        // dd($oldlead);
                        if (isset($oldlead->prefix_name)) {
                            $newlead->prefix_name = $oldlead->prefix_name;
                        }
                        if (isset($agent_id)) {
                            $newlead->agent_id = $agent_id;
                        }
                        $newlead->first_name = $oldlead->first_name;
                        $newlead->last_name = $oldlead->last_name;
                        if (isset($oldlead->middle_name)) {
                            $newlead->middle_name = $oldlead->middle_name;
                        }
                        if (isset($oldlead->ar_first_name)) {
                            $newlead->ar_first_name = $oldlead->ar_first_name;
                        }
                        if (isset($oldlead->ar_last_name)) {
                            $newlead->ar_last_name = $oldlead->ar_last_name;
                        }
                        if (isset($oldlead->ar_middle_name)) {
                            $newlead->ar_middle_name = $oldlead->ar_middle_name;
                        }
                        $newlead->email = $oldlead->email;
                        $newlead->phone = $oldlead->phone;
                        if (isset($oldlead->nationality)) {
                            $newlead->nationality = $oldlead->nationality;
                        }
                        if (isset($oldlead->country_id)) {
                            $newlead->country_id = $oldlead->country_id;
                        }
                        if (isset($oldlead->city_id)) {
                            $newlead->city_id = $oldlead->city_id;
                        }
                        if (isset($oldlead->address)) {
                            $newlead->address = $oldlead->address;
                        }
                        if (isset($oldlead->club)) {
                            $newlead->club = $oldlead->club;
                        }
                        if (isset($oldlead->title_id)) {
                            $newlead->title_id = $oldlead->title_id;
                        }
                        if (isset($oldlead->religion)) {
                            $newlead->religion = $oldlead->religion;
                        }
                        if (isset($oldlead->birth_date)) {
                            $newlead->birth_date = $oldlead->birth_date;//strtotime($oldlead->birth_date);
                        }
                        if (isset($oldlead->lead_source_id)) {
                            $newlead->lead_source_id = $oldlead->lead_source;
                        }
                        if (isset($oldlead->social)) {
                            $newlead->social = $oldlead->social;
                        }
                        if (isset($oldlead->industry_id)) {
                            $newlead->industry_id = $oldlead->industry_id;
                        }
                        if (isset($oldlead->company)) {
                            $newlead->company = $oldlead->company;
                        }
                        if (isset($oldlead->school)) {
                            $newlead->school = $oldlead->school;
                        }
                        if (isset($oldlead->facebook)) {
                            $newlead->facebook = $oldlead->facebook;
                        }
                        if (isset($oldlead->id_number)) {
                            $newlead->id_number = $oldlead->id_number;
                        }

                        $newlead->status = $oldlead->status;//'new';
                        if (isset($oldlead->other_phones)) {
                            $newlead->other_phones = $oldlead->other_phones;
                        }
                        if (isset($oldlead->other_emails)) {
                            $newlead->other_emails = $oldlead->other_emails;
                        }

                        // $newlead->notes = $oldlead->notes;
                        if (isset($oldlead->user_id)) {
                            $newlead->user_id = $oldlead->user_id;
                        }
                        if (isset($oldlead->image)) {
                            $newlead->image = $oldlead->image;
                        }

                        // dd($newlead);
                        $newlead->save();
                        $lead_id = $newlead->id;
                    }

                    // calls
                    $calls = $lead->full_info->lead->calls;
                    foreach($calls as $call){
                        $newcall = \App\Call::find($call->id);
                        if($newcall){
                            // dd($newnote);
                            $newcall->user_id = $call->user_id;
                            $newcall->lead_id = $lead_id;
                            $newcall->contact_id = $call->contact_id;
                            $newcall->call_status_id = $call->call_status_id;
                            $newcall->duration = $call->duration;
                            $newcall->date = $call->date;
                            $newcall->probability = $call->probability;
                            $newcall->phone = $call->phone;
                            $newcall->projects = $call->projects;
                            $newcall->description = $call->description;
                            $newcall->budget = $call->budget;

                            $newcall->save();
                        } else {
                            $newcall = new \App\Call();
                            // dd($newnote);
                            $newcall->user_id = $call->user_id;
                            $newcall->lead_id = $lead_id;
                            $newcall->contact_id = $call->contact_id;
                            $newcall->call_status_id = $call->call_status_id;
                            $newcall->duration = $call->duration;
                            $newcall->date = $call->date;
                            $newcall->probability = $call->probability;
                            $newcall->phone = $call->phone;
                            $newcall->projects = $call->projects;
                            $newcall->description = $call->description;
                            $newcall->budget = $call->budget;

                            $newcall->save();
                        }
                    }
                    // meetings
                    $meetings = $lead->full_info->lead->meetings;
                    foreach($meetings as $meeting){
                        $newmeeting = \App\Meeting::find($meeting->id);
                        if($newmeeting){
                            // dd($newnote);
                            $newmeeting->user_id = $meeting->user_id;
                            $newmeeting->lead_id = $lead_id;
                            $newmeeting->contact_id = $meeting->contact_id;
                            $newmeeting->meeting_status_id = $meeting->meeting_status_id;
                            $newmeeting->duration = $meeting->duration;
                            $newmeeting->phone = $meeting->phone;
                            $newmeeting->date = $meeting->date;
                            $newmeeting->time = $meeting->time;
                            $newmeeting->probability = $meeting->probability;
                            $newmeeting->projects = $meeting->projects;
                            $newmeeting->location = $meeting->location;
                            $newmeeting->description = $meeting->description;
                            $newmeeting->budget = $meeting->budget;

                            $newmeeting->save();
                        } else {
                            $newmeeting = \App\Meeting();
                            // dd($newnote);
                            $newmeeting->user_id = $meeting->user_id;
                            $newmeeting->lead_id = $lead_id;
                            $newmeeting->contact_id = $meeting->contact_id;
                            $newmeeting->meeting_status_id = $meeting->meeting_status_id;
                            $newmeeting->duration = $meeting->duration;
                            $newmeeting->phone = $meeting->phone;
                            $newmeeting->date = $meeting->date;
                            $newmeeting->time = $meeting->time;
                            $newmeeting->probability = $meeting->probability;
                            $newmeeting->projects = $meeting->projects;
                            $newmeeting->location = $meeting->location;
                            $newmeeting->description = $meeting->description;
                            $newmeeting->budget = $meeting->budget;

                            $newmeeting->save();
                        }
                    }
                    // voice_notes
                    $voicenotes = $lead->full_info->lead->voice_notes;
                    foreach($voicenotes as $vnote){
                        $newvnote = \App\VoiceNote::find($vnote->id);
                        if($newvnote){
                            // dd($newnote);
                            $newvnote->lead_id = $lead_id;
                            $newvnote->user_id = $vnote->user_id;
                            $newvnote->note = $vnote->note;
                            $newvnote->title = $vnote->title;

                            $newvnote->save();
                        } else {
                            $newvnote = \App\VoiceNote();
                            // dd($newnote);
                            $newvnote->lead_id = $lead_id;
                            $newvnote->user_id = $vnote->user_id;
                            $newvnote->note = $vnote->note;
                            $newvnote->title = $vnote->title;

                            $newvnote->save();
                        }
                    }
                    // notes
                    $notes = $lead->full_info->lead->notes;
                    foreach($notes as $note){
                        $newnote = \App\LeadNote::find($note->id);
                        if($newnote){
                            // dd($newnote);
                            $newnote->lead_id = $lead_id;
                            $newnote->user_id = $note->user_id;
                            $newnote->note = $note->note;

                            $newnote->save();
                        } else {
                            $newnote = new \App\LeadNote();
                            // dd($newnote);
                            $newnote->lead_id = $lead_id;
                            $newnote->user_id = $note->user_id;
                            $newnote->note = $note->note;

                            $newnote->save();
                        }
                    }
                    // documents
                    $documents = $lead->full_info->lead->documents;
                    foreach($documents as $document){
                        $newdocument = \App\LeadDocument::find($document->id);
                        if($newdocument){
                            // dd($newnote);
                            $newdocument->lead_id = $lead_id;
                            $newdocument->title = $document->title;
                            $newdocument->file = $document->file;
                            $newdocument->contact_id = $document->contact_id;
                            $newdocument->user_id = $document->user_id;

                            $newdocument->save();
                        } else {
                            $newdocument = \App\LeadDocument();
                            // dd($newnote);
                            $newdocument->lead_id = $lead_id;
                            $newdocument->title = $document->title;
                            $newdocument->file = $document->file;
                            $newdocument->contact_id = $document->contact_id;
                            $newdocument->user_id = $document->user_id;

                            $newdocument->save();
                        }
                    }


            }
            // end save coming data
            // delete leads
            $delete = 'success';
            foreach($request_json->deleteArray as $key => $del_lead){
                // dd($del_lead);
                try{
                    $data = Lead::find($del_lead);
                    if($data == null) continue;
                    $file_path = url('uploads/' . @$data->image);
                    if (@$data->image != 'image.jpg' and @$data->image != 'image.ico' and file_exists($file_path)) {
                        @unlink($file_path);
                    }
                    $user = User::find($request_json->user_id)->first();
                    $roles = [];
                    if($user){
                        $role = \App\Role::find($user->role->id);
                        $roles = json_decode($role->roles,true);
                        // dd($roles);
                    }
                    if ($roles['hard_delete_leads']) {
                        $lead = $del_lead;
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
                        $delete = 'success';
                    } else if ($roles['soft_delete_leads']) {
                        $data->agent_id = 0;
                        $data->save();
                        $delete = 'success';
                    } else {
                        $delete = 'no permission';
                    }
                    $old_data = json_encode($data);
                }catch(\Exception $e){
                    $delete = 'failed';
                }

            }

            // end delete
            $user_id = $request_json->user_id;
            $last_sync = $request_json->last_sync;
            $data = [];
            $other_phones = [];
            $page = isset($request_json->page) ? abs( (int) $request_json->page ) : 0;

            // $leads = Lead::where('agent_id', $user_id)->offset(($page-1)*25)->limit(25)->get() ;

            $userData = User::find($user_id);
            $ids = [];

            $leadNotesIds = \App\LeadNote::getLeadNotesSync($last_sync);
            $leadDocumentsIds = \App\LeadDocument::getLeadDocumentsSync($last_sync);
            $leadCallsIds = \App\Call::getLeadCallsSync($last_sync);
            $leadMeetingsIds = \App\Meeting::getLeadMeetingsSync($last_sync);
            $leadVoiceNotesIds = \App\VoiceNote::getLeadVoiceNotesSync($last_sync);
            $leadRequestsIds = \App\Request::getLeadRequestsSync($last_sync);
            $ids = array_merge($leadNotesIds, $leadDocumentsIds, $leadCallsIds, $leadMeetingsIds, $leadVoiceNotesIds, $leadRequestsIds);
            $ids = array_unique($ids);
            // dd($ids);

            $leads = Lead::getAgentLeadsSync($userData, null, $page, $last_sync, $ids);

            // if(count($leads = Lead::offset(($page-1)*25)->limit(25)->get()) < 25){
            //     $page ++;
            //     $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
            // // dd(count($leads));
            // }
            // else {
                // $leads = Lead::offset(($page-1)*25)->limit(25)->get() ;
                        // dd(count($leads));

            // }



            $get_full_info = isset($request_json->full_info) && 'yes' === $request_json->full_info;

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

                    $lead['full_info'] = $agent_controller->get_lead( $request, $row->id );

                }

                $data[] = $lead;
            }  foreach ($data as $row) {
                $agents[] = $row['agent_id'];
                $agents[] = $row['commercial_agent_id'];
            }
            $agents = array_unique($agents);
            foreach ($agents as $agent) {
                $agentData = User::find($agent);
                if ($agentData) {
                    $arr = [];
                    $arr['name'] = $agentData->name;
                    $arr['id'] = $agent;
                    $agentsData[] = $arr;
                }
            }

            if ($userData->type != 'admin') {
                $agentsData = [];
            }

            $response = 'ok';
            return [ 'status'=>$response,'leads' => $data, 'agents' => $agentsData, 'delete' => $delete  ];
            } catch (Exception $e) {
                $response =
                     'error'
                ;
                return ['status'=>$response,'leads' => $data, 'agents' => $agentsData, 'delete' => $delete ];

            }

        }

    public function get_team_leads(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $userData = User::find($user_id);
        $data = [];
        $other_phones = [];
        $page = $request->page;
        $leads = Lead::getTeamLeads2($userData,$page);
        $agents = [];
        $agentsData = [];

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
                            )
                        );
                    }
            }
            if ($row->industry_id != null) {
                $industry = @$row->industry->name;
            } else $industry = '';

            if ($row->title_id == null)
                $title = '';
            else
                $title = @$row->title->name;

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
                'agent_id' => @$row->agent_id,
                'agent_name' => @$row->agent->name,
                'commercial_agent_id' => $row->commercial_agent_id,
                'commercial_agent_name' => @$row->commercialAgent->name,
                'reference' => $row->reference?$row->reference:'',
                'lead_source' => $source,
            ));

        }
        $data2=Lead::getTeamLeads($userData);
        foreach ($data2 as $row) {
            $agents[] = $row['agent_id'];
            $agents[] = $row['commercial_agent_id'];
        }
        $agents = array_unique($agents);
        foreach ($agents as $agent) {
            $agentData = User::find($agent);
            if ($agentData) {
                $arr = [];
                $arr['name'] = $agentData->name;
                $arr['id'] = $agent;
                $agentsData[] = $arr;
            }
        }

        return ['leads' => $data, 'agents' => $agentsData];
    }

    public function search_leads(Request $request){
        $request = json_decode($request->getContent());
        $search = $request->search;
        $group = $request->group;
        $user_id = $request->user_id;
        $page = $request->page;
        $leads = [];
        $team = [];
        $data = [];
        $other_phones =[];
        if ($group){
            $user = User::find($user_id);
            $users = [];
            if ($user->type == 'admin' or @Group::where('team_leader_id', $user->id)->count()) {
                $leads = Lead::getAgentLeads($user);
                foreach ($leads as $lead) {
                    if ($lead->agent_id != auth()->id())
                        $users[] = User::find($lead->agent_id);
                }
            }
            $team = array_unique($users);
            $leads = DB::table('leads')->whereIn('agent_id', $team)
                ->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('ar_first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('ar_middle_name', 'LIKE', '%' . $search . '%')
                ->orWhere('ar_last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('phone','LIKE', '%' . $search . '%')
                ->offset(($page - 1) * 20)
                ->limit(20)
                ->get();
        } else {
            $leads = DB::table('leads')->where('agent_id', $user_id)
                ->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('ar_first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('ar_middle_name', 'LIKE', '%' . $search . '%')
                ->orWhere('ar_last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('phone', 'LIKE', '%' . $search . '%')
                ->offset(($page - 1) * 20)
                ->limit(20)
                ->get();
        }

        foreach($leads as $row){
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
                $industry = @$row->industry->name;
            } else $industry = '';

            if ($row->title_id == null)
                $title = '';
            else
                $title = @$row->title->name;;

            if ($row->country_id != null) {
                $country = @$row->country->ar_name;
            } else {
                $country = '';
            }

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
                'reference'=>$row->reference?$row->reference:'',
            ));
        }
        return $data;
    }
    public function getAgentLeads(Request $request)
    {
        $request = json_decode($request->getContent());
        $page = $request->page;
        $leads = Lead::where('agent_id', $request->agent_id)->orWhere('commercial_agent_id', $request->agent_id)->offset(($page-1)*20)->limit(20)->get();
        $data = [];
        $other_phones = [];
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

            if ($row->image and $row->image != 'image.jpg') {
                $image = $row->image;
            } else {
                $image = 'image.jpg';
            }

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
                'agent_id' => $row->agent_id,
                'agent_name' => @$row->agent->name,
                'commercial_agent_id' => $row->commercial_agent_id,
                'commercial_agent_name' => @$row->commercialAgent->name,
                'reference'=>$row->reference?$row->reference:'',
            ));
        }

        return $data;
    }

    public function get_projects(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lang = $request->lang;
        $projects = Project::where('type',$request->type)->get();

        $data = [];
        foreach ($projects as $row) {
            $location = @Location::find(@$row->location_id)->{$lang . '_name'};
            array_push($data, array(
                "id" => @$row->id,
                "name" => @$row->{$lang . '_name'},
                'price' => @$row->meter_price,
                'logo' => @$row->logo,
                'image' => @$row->cover,
                'payment' => @$row->down_payment,
                'lat' => @$row->lat,
                'lng' => @$row->lng,
                'zoom' => @$row->zoom,
                'installment_year' => @$row->installment_year,
                'delivery_date' => @$row->delivery_date,
                'location' => @$location
            ));
        }
        return $data;
    }

    public function get_resales(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lang = $request->lang;
        $data = [];
        $selection = $request->selection;
        $type = $request->type;
        $result_units = [];
        // $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
        // join('leads','leads.id','=','resale_units.lead_id')->
        // where('availability', 'available')->where('leads.agent_id', $user_id)->
        // select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
        //     'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
        //     'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name','privacy')->get();
        $resales =  ResaleUnit::where('type',$type)->get();

            if ($selection == 'me') {
                foreach($resales as $resale) {
                    if ($resale->agent_id == $user_id) {
                        $result_units[] = $resale;
                    }
                }

            } else {
                foreach($resales as $resale) {
                    if($resale->privacy == 'public') {
                        $result_units []= $resale;
                    } elseif ($resale->privacy == 'team_only') {
                        $groups = GroupMember::where('member_id', $resale->agent_id)->pluck('group_id');
                        $members = [];
                        foreach ($groups as $group) {
                            $groupMembers = GroupMember::where('group_id', $group)->pluck('member_id')->toArray();
                            $members[] = Group::find($group)->team_leader_id;
                            foreach ($groupMembers as $member) {
                                $members[] = $member;
                            }
                        }
                        if (in_array($user_id, $members)) {
                            $result_units[] = $unit;
                        }
                    }elseif($resale->privacy == 'custom'){
                        $agents = @json_decode($resale->custom_agents);
                        if (is_array($agents)) {
                            if (in_array($user_id, $agents)) {
                                $result_units[] = $resale;
                            }
                        }
                    }

                }
            }
        $resales = $result_units;
        foreach ($resales as $resale) {
            array_push($data, array('id' => $resale->id, 'location' => $resale->{$lang . '_name'},
                'home_image' => $resale->image, 'other_images' => json_decode($resale->other_images),
                'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms
            ));
            // array_push($data,$resale);
        }
        return $data;
    }

    public function get_rentals(Request $request)
    {

        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lang = $request->lang;
        $data = [];
        $result_units = [];
        $selection = $request->selection;
        $type = $request->type;

        $rentals =  RentalUnit::where('type',$type)->get();

        if($selection == 'me'){
                foreach($rentals as $rental){
                    if($rental->agent_id == $user_id){
                        $result_units []= $rental;
                    }
                }
            }else{
                foreach($rentals as $rental){
                    if($rental->privacy == 'public'){
                        $result_units []= $rental;
                    }elseif($rental->privacy == 'team_only'){
                         $groups = GroupMember::where('member_id', $rental->agent_id)->pluck('group_id');
                        $members = [];
                        foreach ($groups as $group) {
                            $groupMembers = GroupMember::where('group_id', $group)->pluck('member_id')->toArray();
                            $members[] = Group::find($group)->team_leader_id;
                            foreach ($groupMembers as $member) {
                                $members[] = $member;
                            }
                        }
                        if (in_array($user_id, $members)) {
                            $result_units[] = $unit;
                        }
                    }elseif($rental->privacy == 'custom'){
                        $agents = @json_decode($rental->custom_agents);
                        if (is_array($agents)) {
                            if (in_array($user_id, $agents)) {
                                $result_units[] = $unit;
                            }
                        }
                    }

                }
            }
        $rentals = $result_units;
        foreach ($rentals as $rental) {
            array_push($data, array('id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                'title' => $rental->{$lang . '_title'}, 'price' => $rental->rent, 'area' => $rental->area,
                'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms
            ));
        }
        return $data;
    }

    public function single_project(Request $request)
    {
        $request = json_decode($request->getContent());
        $id = $request->id;
        $lang = $request->lang;
        $project = Project::find($id);

        $data['data'] = array();
        $data['units'] = array();
        $data['gallery'] = array();
        $data['facility'] = array();
        $data['pdfs']= array();
        $data['pdfs']['broker_pdf'] = array();
        $data['pdfs']['developer_pdf'] = array();
        $data['pdfs']['broker_pdf']=  json_decode($project->developer_pdf);
        $data['pdfs']['developer_pdf']=  json_decode($project->developer_pdf);
        // dd($project);
        $location = Location::find($project->location_id)->{$lang . '_name'};

        $phase = Phase::where('project_id', '=', $project->id)->get();

        $data['data'] = array("id" => $project->id, "name" => $project->{$lang . '_name'},
            'description' => $project->{$lang . '_description'}, 'price' => $project->meter_price,
            'logo' => $project->logo, 'image' => $project->cover, 'payment' => $project->down_payment,
            'installment_year' => $project->installment_year, 'lat' => $project->lat,
            'lng' => $project->lng, 'zoom' => $project->zoom, 'delivery_date' => $project->delivery_date,
            'location' => $location);

        foreach ($phase as $row) {
            $units = Property::leftJoin('unit_types', 'unit_types.id', '=', 'properties.unit_id')
                ->where('phase_id', '=', $row->id)->where('availability', 'available')
                ->select('properties.id as id', 'properties.en_name as en_name', 'properties.ar_name as ar_name', 'unit_types.en_name as en_type'
                    , 'unit_types.ar_name as ar_type',
                    'properties.start_price as price', 'properties.area_from as area_from', 'properties.area_to as area_to')
                ->get();

            foreach ($units as $unit) {
                array_push($data['units'], array("id" => $unit->id, "name" => $unit->{$lang . '_name'}, 'price' => $unit->price,
                    'type' => $unit->{$lang . '_type'}, 'area_from' => $unit->area_from, 'area_to' => $unit->area_to));
            }

            //---------------------------------------------------------------------------------

        }
        $data['gallery'] = Gallery::where('project_id', '=', $id)->select('image')->get()->toArray();

        $facility = UnitFacility::where('unit_id', $id)->where('type', 'project')->get();

        foreach ($facility as $row1) {
            $facility1 = Facility::find($row1->facility_id);

            $icon = Icon::find($facility1->icon);

            array_push($data['facility'], array("icon" => $icon->icon, "name" => $facility1->{$lang . '_name'}));
        }

        return $data;
    }

    public function single_resale(Request $request)
    {
        $request = json_decode($request->getContent());
        $id = $request->id;
        $lang = $request->lang;
        $unit = ResaleUnit::find($id);
        $type='';
        $name='';
        $phone='';
        $other_phone=json_decode($unit->other_phones);
        if($unit->privacy == 'only_me'){
            $type='lead';
            $lead = Lead::find($unit->lead_id);
            $name=$lead->first_name. ' '.$lead->first_name;
            $phone = $lead->phone;
        }else{
            $type= 'agent';
            $agent = User::find($unit->agent_id);
            $name = $agent->name;
            $phone = $agent->phone;
         $other_phone= [];
        }

        $location = Location::find($unit->location)->{$lang . '_name'};
        // dd('Request1');
        $otherimages = ResalImage::where('unit_id', $id)->get();
        $images = [];
        foreach ($otherimages as $row) {
            array_push($images, trim($row->watermarked_image, 'uploads/'));
        }
        $data = array('id' => $unit->id, 'location' => $location,
            'home_image' => $unit->image, 'other_images' => $images,
            'title' => $unit->{$lang . '_title'}, 'price' => $unit->price, 'area' => $unit->area,
            'rooms' => $unit->rooms, 'bathrooms' => $unit->bathrooms,
            'due_now' => $unit->due_now, 'finished' => $unit->finishing,
            'description' => $unit->{$lang . '_description'},
            'main_phone' => $phone, 'other_phone' => $other_phone,
            'type'=>$type,'name'=>$name,

        );
        return $data;

    }

    public function single_rental(Request $request)
    {
        $request = json_decode($request->getContent());
        $id = $request->id;
        $lang = $request->lang;

        $unit = RentalUnit::find($id);
        // dd($unit->other_phones);
        $type='';
        $name='';
        $phone='';
        $agent='';

        $other_phone=json_decode($unit->other_phones);
        if($unit->privacy == 'only_me'){
            $type='lead';
            $lead = Lead::find($unit->lead_id);
            $name=$lead->first_name. ' '.$lead->first_name;
            $phone = $unit->main_phone;
        }else{
            $type= 'agent';
            $agent = User::find($unit->agent_id);
            $name = $agent->name;
            $phone = $agent->phone;
             $other_phone= [];

        }

        $location = Location::find($unit->location)->{$lang . '_name'};
        $otherimages = RentalImage::where('unit_id', $id)->get();
        $images = [];
        foreach ($otherimages as $row) {
            array_push($images, trim($row->watermarked_image, 'uploads/'));
        }
        $data = array('id' => $unit->id, 'location' => $location,
            'home_image' => $unit->image, 'other_images' => $images,
            'title' => $unit->{$lang . '_title'}, 'price' => $unit->rent, 'area' => $unit->area,
            'rooms' => $unit->rooms, 'bathrooms' => $unit->bathrooms,
            'due_now' => $unit->due_now, 'finished' => $unit->finishing,
            'description' => $unit->{$lang . '_description'},
            'main_phone' => $phone, 'other_phone' => $other_phone,
            'type'=>$type,'name'=>$name,
        );
        return $data;
    }

    public function lead_contact(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lead_id = $request->lead_id;
        $data = Contact::where('lead_id', $lead_id)->get()->toArray();
        return $data;
    }

    public function lead_note(Request $request)
    {

        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lead_id = $request->lead_id;
        $leads = LeadNote::where('lead_id', $lead_id)->get();
        $voices = VoiceNote::where('lead_id', $lead_id)->get();
        $data = [];
        foreach ($leads as $row) {
            $row->date = date('d/m/Y', strtotime($row->created_at));
            $row->agent = User::find($row->user_id)->name;
            $row->voice = false;
            $data[] = $row;
        }

        foreach ($voices as $row1) {
            $row1->date = date('d/m/Y', strtotime($row1->created_at));
            $row1->agent = User::find($row1->user_id)->name;
            $row1->voice = true;
            $data[] = $row1;
        }

        return $data;

    }

    public function lead_message(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lead_id = $request->lead_id;
        $leads = Message::join('leads', 'leads.id', '=', 'messages.user_id')->where('messages.user_id', $lead_id)->
        select('leads.id as id', 'leads.first_name as first_name',
            'leads.last_name as last_name', 'messages.message as message')->get()->toArray();
        return $leads;
    }

    public function lead_document(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lead_id = $request->lead_id;
        $leads = LeadDocument::where('lead_id', $lead_id)->get()->toArray();
        return $leads;

    }

    public function lead_request(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $lang = $request->lang;
        $lead_id = $request->lead_id;
        $data = [];
        $requests = Request1::where('lead_id', $lead_id)->get();
        foreach ($requests as $row) {
            $recieve = $this->suggest($row);
            $units = $this->single_unit($recieve['unit'], $recieve['type'], $lang,$lead_id);
            $row->units = $units;
            $location = @Location::find($row->location)->{$lang . '_name'};
            $type = @UnitType::find($row->unit_type_id)->{$lang . '_name'};
            $row->location = $location;
            $row->unit_type_id = $type;
            array_push($data, $row->toArray());
        }
        return $data;
    }

    public function get_suggestion(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $requests = Request1::find($request->request_id);
        $lead_id = $requests->lead_id;
        //        dd($lead_id);
        $recieve = $this->suggest($requests);
        $units = $this->single_unit($recieve['unit'], $recieve['type'], $lang, $lead_id, $request->request_id);

        return $units;


    }

    private function suggest($req)
    {
        $data['unit'] = [];
        $data['type'] = [];
        $units=[];
        $type=[];
        $locationsArray = HomeController::getChildren($req->location);
        $locationsArray[] = $req->location;
        if ($req->request_type != 'land') {
            if ($req->request_type == 'new_home') {
                $units = @Project::where('type', $req->unit_type)->
                whereBetween('meter_price', [$req->price_from, $req->price_to])->
                whereBetween('area', [$req->area_from, $req->area_to])->
                whereIn('location_id', $locationsArray)->select('id')->
                get()->toArray();
                $type = 'project';
            } elseif ($req->request_type == 'resale') {
                $units = @ResaleUnit::whereBetween('rooms', [$req->rooms_from, $req->rooms_to])->
                where('type', $req->unit_type)->
                where('unit_type_id', $req->unit_type_id)->
                whereBetween('total', [$req->price_from, $req->price_to])->
                whereBetween('area', [$req->area_from, $req->area_to])->
                whereBetween('rooms', [$req->rooms_from, $req->rooms_to])->
                whereIn('location', $locationsArray)->
                where('delivery_date', $req->date)->
                whereBetween('bathrooms', [$req->bathrooms_from, $req->bathrooms_to])->select('id')->get()->toArray();
                $type = 'resale';

            } elseif ($req->request_type == 'rental') {
                $units = @RentalUnit::whereBetween('rooms', [$req->rooms_from, $req->rooms_to])->
                where('type', $req->unit_type)->
                where('unit_type_id', $req->unit_type_id)->
                whereBetween('rent', [$req->price_from, $req->price_to])->
                whereBetween('area', [$req->area_from, $req->area_to])->
                whereBetween('rooms', [$req->rooms_from, $req->rooms_to])->
                whereIn('location', $locationsArray)->
                where('delivery_date', $req->date)->
                whereBetween('bathrooms', [$req->bathrooms_from, $req->bathrooms_to])->select('id')->get()->toArray();
                $type = 'rental';
            }
        } else {
            $units = @Land::whereBetween('meter_price', [$req->price_from, $req->price_to])->
            whereBetween('area', [$req->area_from, $req->area_to])->
            whereIn('location', $locationsArray)->get();
            $type = 'lands';
        }
        $data['unit'] = $units;
        $data['type'] = $type;
        return $data;
    }

    private function single_unit($id, $type, $lang, $lead_id, $req_id = null)
    {
        $data = [];
        $favorite=false;

        if ($type == "rental") {
            $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
            where('availability', 'available')->whereIn('rental_units.id', $id)->
            select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
                'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
                'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();

            foreach ($rentals as $rental) {
                $images = RentalImage::where('unit_id',$rental->id)->select('image')->get()->toArray();
                $favorite = false;
                if (Interested::where('unit_id',$rental->id)->where('lead_id',$lead_id)->where('type',$type)->count() > 0)
                    $favorite = true;

                $interest = false;
                if (InterestedRequest::where('unit_id', $rental->id)->where('request_id', $req_id)->count())
                    $interest = true;

                array_push($data, array('id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                    'home_image' => $rental->image, 'other_images' => $images,
                    'title' => $rental->{$lang . '_title'}, 'price' => $rental->rent, 'area' => $rental->area,
                    'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms,'favorite'=>$favorite, 'interest' => $interest
                ));
            }
        } else if ($type == "resale") {
            $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
            where('availability', 'available')->whereIn('resale_units.id', $id)->
            select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
                'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
                'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
            foreach ($resales as $resale) {
                $favorite = false;
                if(Interested::where('unit_id',$resale->id)->where('lead_id',$lead_id)->where('type',$type)->count()>0)
                    $favorite=true;

                $interest = false;
                if (InterestedRequest::where('unit_id', $resale->id)->where('request_id', $req_id)->count())
                    $interest = true;

                array_push($data, array('id' => $resale->id, 'location' => $resale->{$lang . '_name'},
                    'home_image' => $resale->image, 'other_images' => json_decode($resale->other_images),
                    'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                    'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms,'favorite'=>$favorite, 'interest' => $interest
                ));
            }
        } else if ($type == "project") {

            $projects = Project::whereIn('id', $id)->get();
            foreach ($projects as $row) {
                $favorite=false;
                if(Interested::where('unit_id',$row->id)->where('lead_id',$lead_id)->where('type',$type)->count()>0)
                    $favorite=true;

                $interest = false;
                if (InterestedRequest::where('unit_id', $row->id)->where('request_id', $req_id)->count())
                    $interest = true;

                $location = Location::find($row->location_id)->{$lang . '_name'};
                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'logo' => $row->logo, 'image' => $row->cover, 'payment' => $row->down_payment,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'installment_year' => $row->installment_year, 'delivery_date' => $row->delivery_date,
                    'location' => $location,'favorite'=>$favorite, 'interest' => $interest));
            }
        }
        return $data;
    }

public function getinfo(Request $request)
    {
        $request = json_decode($request->getContent());
        $contacts = Contact::where('lead_id', $request->lead_id)->get();
        $lang = $request->lang;
        $data['contact'] = [];
        $projects = Project::select('id', $lang . '_name as name')->get()->toArray();
        $data['project'] = $projects;
        $call_status = \App\CallStatus::all()->toArray();
        $data['call_status'] = $call_status;
        $meeting_status = \App\MeetingStatus::all()->toArray();
        $data['meeting_status'] = $meeting_status;
        foreach ($contacts as $row) {
            if ($row->other_phones != null)
                $phone = json_decode($row->other_phones);
            else $phone = [];
            array_push($data['contact'], array('id' => $row->id, 'name' => $row->name, 'main_phone' => $row->phone, 'other_phone' => $phone));
        }
        return $data;
    }

    public function add_call(Request $request)
   {

       $request = json_decode($request->getContent());
       $rules = [
           'user_id' => 'required|numeric',
           'lead_id' => 'required',
           'date' => 'required|max:191',
           'duration' => 'required|max:191',
           'projects' => 'required',
           'contact_id' => 'required',
           'probability' => 'required',
           'phone_in_out' => 'required',
           'call_status_id' => 'required'
       ];
       $validator = Validator::make(array(
           'user_id' => $request->user_id,
           'lead_id' => $request->lead_id,
           'contact_id' => $request->contact_id,
           'date' => $request->date,
           'duration' => $request->duration,
           'projects' => $request->projects,
           'probability' => $request->probability,
           'phone_in_out' => $request->phone_in_out,
           'call_status_id' => $request->call_status_id
       ), $rules);
       if ($validator->fails()) {
           $response = array(
               "status" => 'error',
           );
           //dd($validator->errors());
       } else {
           $call = new Call();
           $call->user_id = $request->user_id;
           $call->lead_id = $request->lead_id;
           $call->call_status_id = $request->call_status_id;
           $call->contact_id = $request->contact_id;
           $call->duration = $request->duration;
           $call->date = $request->date;
           $call->probability = $request->probability;
           if($request->contact_id){
               $call->phone = Lead::where('id',$request->lead_id)->pluck('phone');
           }
           else{
               $call->phone = Contact::where('id',$request->contact_id)->pluck('phone');
           }
           $call->projects = json_encode($request->projects);
           $call->description = $request->description;
           $call->save();
           $has_next_action = $call->call_status->has_next_action;
           $response = array(
               "status" => "OK",
               "has_next_action" => $has_next_action
           );
       }
       return $response;
   }

    public function add_interested(Request $request)
    {
        try {
            $request = json_decode($request->getContent());
            $unit_id = $request->unit_id;
            $lead_id = $request->lead_id;
            $type = $request->type;
            if(Interested::where('unit_id',$unit_id)->where('lead_id',$lead_id)->where('type',$type)->count()==0) {
                $interested = new Interested();
                $interested->unit_id = $unit_id;
                $interested->lead_id = $lead_id;
                $interested->type = $type;
                $interested->save();
            }
            $response = array(
                "status" => 'ok',
            );
            return $response;
        } catch (Exception $e) {
            $response = array(
                "status" => 'error',
            );
            return $response;
        }
    }

 public function get_teamLeads(Request $request)
    {
        $request = json_decode($request->getContent());
        $user_id = $request->user_id;
        $userData = User::find($user_id);
        $data = [];
        $other_phones = [];
        // $page = $request->page;
        // $leads = Lead::getAgentLea($userData)->paginate(15);
        // $page = isset($request_json->page) ? abs( (int) $request_json->page ) : 0;
        // $leads = Lead::offset(($page-1)*20)->limit(20)->get();

        $get_full_info = isset($request_json->full_info) && 'yes' === $request_json->full_info;

        if ( $get_full_info ) {

            $agent_controller = app('App\Http\Controllers\Agentapi');

        }
        $agents = [];
        $agentsData = [];

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
                            )
                        );
                    }
            }
            if ($row->industry_id != null) {
                $industry = @$row->industry->name;
            } else $industry = '';

            if ($row->title_id == null)
                $title = '';
            else
                $title = @$row->title->name;

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
            /////////////////////////////////////////////
            /////////////////////////////////////////////
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
            /////////////////////////////////////////////
            ////////////////////////////////////////////

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
                'agent_id'=>@$row->agent_id,
                'agent_name' => @$row->agent->name,
                'commercial_agent_id' => $row->commercial_agent_id,
                'commercial_agent_name' => @$row->commercialAgent->name,
                'reference' => $row->reference?$row->reference:'',
                'lead_source' => $source,
                'data'=>$dataa
            ));

        }

        foreach ($data as $row) {
            $agents[] = $row['agent_id'];
            $agents[] = $row['commercial_agent_id'];
        }
        $agents = array_unique($agents);
        foreach ($agents as $agent) {
            $agentData = User::find($agent);
            if ($agentData) {
                $arr = [];
                $arr['name'] = $agentData->name;
                $arr['id'] = $agent;
                $agentsData[] = $arr;
            }
        }

        return ['leads' => $data, 'agents' => $agentsData];
    }

}
