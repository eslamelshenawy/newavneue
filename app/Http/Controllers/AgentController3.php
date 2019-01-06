<?php

namespace App\Http\Controllers;

use App\ClosedDeal;
use App\Contact;
use App\Country;
use App\Industry;
use App\Interested;
use App\LeadDocument;
use App\Location;
use App\Meeting;
use App\Project;
use App\RentalImage;
use App\ResalImage;
use App\Task;
use App\UnitType;
use App\User;
use Illuminate\Http\Request;
use App\Developer;
use Mail;
use App\Lead;
use Validator;
use App\Request as Model;
use App\Mail\Cil;
use App\RentalUnit;
use App\ResaleUnit;
use App\Favorite;
use App\AdminNotification;
use App\Setting;
use App\AgentToken;
use Image;
use App\ToDo;
use App\LeadSource;
use App\LeadNote;

class AgentController3 extends Controller
{

    public function send_cil(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead = @Lead::find($request->lead_id);
        $file = $request->document;
        $project = 0;
        foreach ($request->cil as $row) {
            $developer = @Developer::find($row->developer);
            if ($row->developer) {
                $project = $row->project;
            }
            if (filter_var($developer->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($developer->email)->queue(new Cil(['lead' => $lead, 'project' => $project, 'file' => $file]));
            }
            $cil = new \App\Cil;
            $cil->lead_id = $request->lead_id;
            $cil->developer_id = $row->developer;
            $cil->status = 'pending';
            $cil->save();
        }

        $response = array('status' => 'ok');
        return $response;

    }

    public function cil_info(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $user_id = $request->user_id;
        $lead_id = $request->lead_id;
        $data['developers'] = [];
        $data['documents'] = LeadDocument::where('lead_id', $lead_id)->select('id', 'title')->get()->toArray();
        $developers = Developer::select('id', $lang . '_name as name')->get();
        foreach ($developers as $row) {
            $projects = Project::where('developer_id', $row->id)->select('id', $lang . '_name as name')->get()->toArray();
            array_push($data['developers'], array('name' => $row->name, 'id' => $row->id, 'projects' => $projects));
        }
        return $data;

    }

    public function lead_edit_info(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $data['countries'] = Country::select('id', 'name')->get()->toArray();
        $data['industries'] = Industry::select('id', 'name')->get()->toArray();
        return $data;
    }

    public function edit_lead(Request $request)
    {
       // dd('sheno');
        $request = json_decode($request->getContent());
        $rules = [
            'user_id' => 'required|numeric',
            'lead_id' => 'required',
            'phone' => 'required|numeric',
        ];
        $validator = Validator::make(array(
            'user_id' => $request->user_id,
            'lead_id' => $request->lead_id,
            'phone' => $request->phone,
        ), $rules);
        if ($validator->fails()) {
            $response = array(
                "status" => 'error',
            );
            return $response;
        } else {
            if (trim($request->email) != '' || $request->email != null) {
                if (Lead::where('id', '!=', $request->lead_id)->where(function ($query) use ($request) {
                        $query->where('email', $request->email)->orWhere('phone', $request->phone);
                    })->count() > 0) {
                    $response = array(
                        "status" => 'Email or Phone already exist',
                    );
                    return $response;
                }
            } else {
                if (Lead::where('id', '!=', $request->lead_id)->orWhere('phone', $request->phone)->count() > 0) {
                    $response = array(
                        "status" => 'Phone already exist',
                    );
                    return $response;
                }
            }

            $lead = Lead::find($request->lead_id);
            $iid = 0;
            if(@$request->industry && $request->industry != ""){
                $industry = \App\Industry::where('name', $request->industry)->first();
                if(!$industry){
                    $industry = new \App\Industry();
                    $industry->name = $request->industry;
                    $industry->user_id = $request->user_id;
                    $industry->save();
                    $iid = $industry->id;
                }

                $iid = $industry->id;
            }
            $lead->email = $request->email;
            $lead->prefix_name = $request->title;
            $lead->phone = $request->phone;
            $lead->industry_id = $iid;
            $lead->birth_date = strtotime($request->birth_date);
            $lead->id_number = $request->id_number;
            $lead->company = $request->company;
            $lead->school = $request->school;
            $lead->club = $request->club;
            $lead->religion = $request->religion;
            $lead->address = $request->address;
            $lead->notes = $request->note;
            $lead->country_id = $request->country;
            $lead->save();
            $response = array(
                "status" => 'OK',
            );
            return $response;
        }
    }

    public function add_lead(Request $request)
    {
        $request = json_decode($request->getContent());
        $rules = [
            'user_id' => 'required|numeric',
            'phone' => 'required|numeric',
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
        ];
        $validator = Validator::make(array(
            'user_id' => $request->user_id,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ), $rules);
        if ($validator->fails()) {
            $response = array(
                "status" => 'data',
            );
            return $response;
        } else {
            if (trim($request->email) != '' || $request->email != null) {
                if (Lead::where('email', $request->email)->orWhere('phone', $request->phone)->count()) {
                    if (Lead::where('email', $request->email)->count())
                        $response = array(
                            "status" => 'email',
                        );
                    else
                        $response = array(
                            "status" => 'phone',
                        );
                    return $response;
                }
            } else {
                if (Lead::where('phone', $request->phone)->count() > 0) {
                    $response = array(
                        "status" => 'phone',
                    );
                    return $response;
                }
            }
            $lead = new Lead();
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->first_name = $request->first_name;
            $lead->last_name = $request->last_name;
            $lead->industry_id = $request->industry;
            $lead->birth_date = strtotime($request->birth_date);
            $lead->id_number = $request->id_number;
            $lead->company = $request->company;
            $lead->school = $request->school;
            $lead->club = $request->club;
            $lead->religion = $request->religion;
            $lead->address = $request->address;
            $lead->notes = $request->note;
            $lead->country_id = $request->country;
            $lead->agent_id = $request->user_id;
            $lead->user_id = $request->user_id;
            $lead->reference = $request->reference;
            $lead->image = 'lead.png';
            $lead->lead_source_id = $request->lead_source_id;
            $lead->save();

            if ($request->note != '' or $request->note != null) {
                $note = new LeadNote;
                $note->lead_id = $lead->id;
                $note->note = $request->note;
                $note->user_id = $request->user_id;
                $note->save();
            }

            $response = array(
                "status" => 'ok',
            );

            return $response;
        }
    }
    public function lead_sources(Request $request){
        $sources = LeadSource::select(['id','name'])->get();
        return $sources ;
    }
    
    public function add_meeting(Request $request)
    {

        $request = json_decode($request->getContent());
        $rules = [
            'user_id' => 'required|numeric',
            'lead_id' => 'required',
            'date' => 'required|max:191',
            // 'time' => 'required|max:191',
            'duration' => 'required|max:191',
            'location' => 'required',
            'probability' => 'required',
            'meeting_status_id' => 'required'
        ];
        $validator = Validator::make(array(
            'user_id' => $request->user_id,
            'lead_id' => $request->lead_id,
            // 'time' => $request->time,
            'date' => $request->date,
            'duration' => $request->duration,
            'location' => $request->location,
            'probability' => $request->probability,
            'meeting_status_id' => $request->meeting_status_id
        ), $rules);
        if ($validator->fails()) {
            $response = array(
                "status" => 'error',
            );
        } else {
            $meeting = new Meeting();
            $meeting->user_id = $request->user_id;
            $meeting->lead_id = $request->lead_id;
            $meeting->meeting_status_id = $request->meeting_status_id;
            $meeting->contact_id = $request->contact_id;
            $meeting->duration = $request->duration;
            $meeting->date = $request->date;
            $meeting->probability = $request->probability;
            $meeting->time = $request->time;
            $meeting->projects = json_encode($request->projects);
            $meeting->location = $request->location;
            $meeting->description = $request->description;
            $meeting->save();
            $has_next_action = $meeting->meeting_status->has_next_action;
            $response = array(
                "status" => "OK",
                "has_next_action" => $has_next_action
            );
        }
        return $response;
    }

    public function get_contact(Request $request)
    {
        $request = json_decode($request->getContent());

        $contacts = Contact::where('lead_id', $request->lead_id)->select('id', 'name')->get()->toArray();
        return $contacts;
    }

    public function getinfo(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $locations = Location::select('id', $lang . '_name as name')->get()->toArray();
        $data['location'] = $locations;
        $data['commercial'] = UnitType::where('usage', 'commercial')->select('id', $lang . '_name as name')->get()->toArray();
        $data['personal'] = UnitType::where('usage', 'personal')->select('id', $lang . '_name as name')->get()->toArray();
        $data['land'] = UnitType::where('usage', 'land')->select('id', $lang . '_name as name')->get()->toArray();
        return $data;
    }

    public function request(Request $request)
    {
        $request = json_decode($request->getContent());
        if ($request->unit_type == 'land') {
            $rules = [
                'lead' => 'required|max:191',
                'location' => 'required|max:191',
                'down_payment' => 'required|max:191',
                'unit_type' => 'required|max:191',
                'area_from' => 'required|numeric|min:0',
                'area_to' => 'required|numeric|min:' . $request->area_from,
                'price_from' => 'required|numeric|min:0',
                'price_to' => 'required|numeric|min:' . $request->price_from,
                'date' => 'required|max:191',
            ];
        } else {
            $rules = [
                'lead' => 'required|max:191',
                'location' => 'required|max:191',
                'down_payment' => 'required|max:191',
                'unit_type_id' => 'required|max:191',
                'unit_type' => 'required|max:191',
                'request_type' => 'required|max:191',
                'area_from' => 'required|numeric|min:0',
                'area_to' => 'required|numeric|min:' . $request->area_from,
                'price_from' => 'required|numeric|min:0',
                'price_to' => 'required|numeric|min:' . $request->price_from,
                'date' => 'required|max:191',
            ];
        }
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
//            dd($request->unit_type);
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
            $req->type = $request->type;
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

    public function lead_resale(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead_id = $request->lead_id;
        $lang = $request->lang;
        $data = [];
        $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
        where('availability', 'available')->where('resale_units.lead_id', $lead_id)->
        select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
            'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
            'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($resales as $resale) {
            array_push($data, array('id' => $resale->id, 'location' => $resale->{$lang . '_name'},
                'home_image' => $resale->image, 'other_images' => json_decode($resale->other_images),
                'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms
            ));
        }
        return $data;
    }

    public function lead_rental(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead_id = $request->lead_id;
        $lang = $request->lang;
        $data = [];
        $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
        where('availability', 'available')->where('rental_units.lead_id', $lead_id)->
        select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
            'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
            'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($rentals as $rental) {
            array_push($data, array('id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                'title' => $rental->{$lang . '_title'}, 'rent' => $rental->rent, 'area' => $rental->area,
                'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms
            ));
        }
        return $data;
    }

    public function favorite_project(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $lead_id = $request->lead_id;
        $data = [];
        if ($lang == 'ar' or $lang == 'en') {
            $recent = Favorite::where('lead_id', $lead_id)->where('type', 'project')->pluck('unit_id');
            $projects = Project::whereIn('id', $recent)->get();
            foreach ($projects as $row) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                if ($num > 0) {
                    $favorite = 'true';
                }

                $location = Location::find($row->location_id)->{$lang . '_name'};
                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'logo' => $row->logo, 'image' => $row->cover, 'payment' => $row->down_payment,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'installment_year' => $row->installment_year, 'delivery_date' => $row->delivery_date,
                    'location' => $location, 'favorite' => $favorite));
            }
        }
        return $data;
    }

    public function favorite_resale(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead_id = $request->lead_id;
        $lang = $request->lang;
        $data = [];
        $recent = Favorite::where('lead_id', $lead_id)->where('type', 'resale')->pluck('unit_id');
        $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
        where('availability', 'available')->
        whereIn('resale_units.id', $recent)->
        select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
            'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
            'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($resales as $resale) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
            if ($num > 0) {
                $favorite = 'true';
            }

            array_push($data, array('id' => $resale->id, 'location' => $resale->{$lang . '_name'},
                'home_image' => $resale->image, 'other_images' => json_decode($resale->other_images),
                'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms, 'favorite' => $favorite,
            ));
        }
        return $data;
    }

    public function favorite_rental(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead_id = $request->lead_id;
        $lang = $request->lang;
        $data = [];
        $recent = Favorite::where('lead_id', $lead_id)->where('type', 'rental')->pluck('unit_id');
        $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')
            ->where('availability', 'available')->
            whereIn('rental_units.id', $recent)->
            select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
                'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
                'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($rentals as $rental) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
            if ($num > 0) {
                $favorite = 'true';
            }

            array_push($data, array('id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                'title' => $rental->{$lang . '_title'}, 'price' => $rental->rent, 'area' => $rental->area,
                'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms, 'favorite' => $favorite,
            ));
        }
        return $data;
    }

    public function interested_project(Request $request)
    {
        $request = json_decode($request->getContent());
        $lang = $request->lang;
        $lead_id = $request->lead_id;
        $data = [];
        if ($lang == 'ar' or $lang == 'en') {
            $recent = Interested::where('lead_id', $lead_id)->where('type', 'project')->pluck('unit_id');
            $projects = Project::whereIn('id', $recent)->get();
            foreach ($projects as $row) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                if ($num > 0) {
                    $favorite = 'true';
                }

                $location = Location::find($row->location_id)->{$lang . '_name'};
                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'logo' => $row->logo, 'image' => $row->cover, 'payment' => $row->down_payment,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'installment_year' => $row->installment_year, 'delivery_date' => $row->delivery_date,
                    'location' => $location, 'favorite' => $favorite));
            }
        }
        return $data;
    }

    public function interested_resale(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead_id = $request->lead_id;
        $lang = $request->lang;
        $data = [];
        $recent = Interested::where('lead_id', $lead_id)->where('type', 'resale')->pluck('unit_id');
        $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
        where('availability', 'available')->
        whereIn('resale_units.id', $recent)->
        select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
            'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
            'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($resales as $resale) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
            if ($num > 0) {
                $favorite = 'true';
            }

            array_push($data, array('id' => $resale->id, 'location' => $resale->{$lang . '_name'},
                'home_image' => $resale->image, 'other_images' => json_decode($resale->other_images),
                'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms, 'favorite' => $favorite,
            ));
        }
        return $data;
    }

    public function interested_rental(Request $request)
    {
        $request = json_decode($request->getContent());
        $lead_id = $request->lead_id;
        $lang = $request->lang;
        $data = [];
        $recent = Interested::where('lead_id', $lead_id)->where('type', 'rental')->pluck('unit_id');
        $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')
            ->where('availability', 'available')->
            whereIn('rental_units.id', $recent)->
            select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
                'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
                'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($rentals as $rental) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
            if ($num > 0) {
                $favorite = 'true';
            }

            array_push($data, array('id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                'title' => $rental->{$lang . '_title'}, 'price' => $rental->rent, 'area' => $rental->area,
                'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms, 'favorite' => $favorite,
            ));
        }
        return $data;
    }
    public function seen(Request $request){
        $request = json_decode($request->getContent());
        // dd($request->notiy_id);
        $notiy = AdminNotification::find($request->notiy_id);

        $notiy->status = 1;
        $notiy->save();
        if($notiy->status){
        return ['status'=>'success'];
        }else{
             return ['status'=>'faild'];
        }
    }

    public function notification(Request $request)
    {
        $request = json_decode($request->getContent());
        $switch = AdminNotification::where('assigned_to', $request->user_id)->where('type', 'switch')->orderBy('created_at', 'desc')->take(10)->get();
        $added_lead = AdminNotification::where('assigned_to', $request->user_id)->where('type', 'added_lead')->orderBy('created_at', 'desc')->take(10)->get();
        $task = AdminNotification::where('assigned_to', $request->user_id)->where('type', 'task')->get();
        $finish_task = AdminNotification::where('user_id', $request->user_id)->where('type', 'finish_task')->get();
        $to_do = AdminNotification::where('assigned_to', $request->user_id)->where('type', 'to_do')->get();
        $close_deal = AdminNotification::where('assigned_to', $request->user_id)->where('type', 'close_deal')->get();
        
        $data = [];
        $type = '';
        $name = '';
        $date = '';
        $location = '';
        $time = '';
        $phone = '';
        $description = '';
        $status = 0;
        $time = '';
        foreach ($switch as $row) {
            $type = 'lead';
            $date = date('d-m-Y', strtotime($row->created_at));
            $time = date('h:i a', strtotime($row->created_at));;
            $location = '';

            if (Lead::find($row->type_id)) {
                $lead = @Lead::find($row->type_id);
            } else {
                $name = 'group';
                $lead = @Lead::find($row->type_id);
            }
            $phone = '';
            $description = '';
            $other_phones = [];
            $status = $row->status;
            $other_emails = json_decode(@$lead->other_emails);
            if ($other_emails == null)
                $other_emails = [];

            if (@$lead->other_phones != null) {

                $p = json_decode(@$lead->other_phones, true);
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
            if (@$lead->industry_id != null) {
                $industry = Industry::find($lead->industry_id)->name;
            } else $industry = '';

            if (@$lead->title_id == null)
                $title = '';
            else
                $title = @Title::find(@$lead->title_id)->name;

            if ($row->country_id != null) {
                $country = @Country::find(@$lead->country_id)->ar_name;
            } else
                $country = '';
            if ($row->social != null) {
                $social = json_decode(@$lead->social, true);
            } else {
                $social = (object)[];
            }
// dd(User::find($row->user_id));
            array_push($data, array(
                'type' => $type,
                'title' => User::find($row->user_id)->name . ' switch  ' . @Lead::find($row->assigned_to)->first_name.' '. @Lead::find($row->assigned_to)->first_name . ' to you',
                'id' => @$lead->id,
                'name' => @$lead->first_name . ' ' . @$lead->last_name,
                'phone' => @$lead->phone,
                'email' => @$lead->email ? $lead->email : '',
                'other_emails' => $other_emails,
                'club' => @$lead->club ? $lead->club:'',
                'birth_date' => @$lead->birth_date ? date('d-m-Y', @$lead->birth_date) : '',
                'other_phones' => $other_phones,
                'company' => @$lead->company ? @$lead->company : '',
                'school' => @$lead->school ? @$lead->school : '',
                'image' => @$lead->image ? @$lead->image : 'image.jpg',
                'notes' => @$lead->notes ? @$lead->notes : '',
                'id_number' => @$lead->id_number ? @$lead->id_number : '',
                'religion' => @$lead->religion ? @$lead->religion : '',
                'address' => @$lead->address ? @$lead->address : '',
                'country' => $country ? $country : '',
                'social' => $social,
                'industry' => $industry,
                'icon' => 'noti/switch.png',
                'status' => $row->status,
                'date' =>$date,
                'time'=>$time,
                'diff'=>$row->created_at->diffForHumans(),
                'notiy_id'=>$row->id,
            ));
        }
         foreach ($added_lead as $row) {
            $type = 'lead';
            $date = date('d-m-Y', strtotime($row->created_at));
            $time = date('h:i a', strtotime($row->created_at));;
            $location = '';

            if (Lead::find($row->type_id)) {
                $lead = @Lead::find($row->type_id);
            } else {
                $name = 'group';
                $lead = @Lead::find($row->type_id);
            }
            $phone = '';
            $description = '';
            $other_phones = [];
            $status = $row->status;
            $other_emails = json_decode(@$lead->other_emails);
            if ($other_emails == null)
                $other_emails = [];

            if (@$lead->other_phones != null) {

                $p = json_decode(@$lead->other_phones, true);
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
            if (@$lead->industry_id != null) {
                $industry = Industry::find($lead->industry_id)->name;
            } else $industry = '';

            if (@$lead->title_id == null)
                $title = '';
            else
                $title = @Title::find(@$lead->title_id)->name;

            if ($row->country_id != null) {
                $country = @Country::find(@$lead->country_id)->ar_name;
            } else
                $country = '';
            if ($row->social != null) {
                $social = json_decode(@$lead->social, true);
            } else {
                $social = (object)[];
            }
// dd(User::find($row->user_id));
            array_push($data, array(
                'type' => $type,
                'title' =>  @Lead::find($row->assigned_to)->first_name.' '. @Lead::find($row->assigned_to)->first_name . 'Added  to '. User::find($row->user_id)->name ,
                'id' => @$lead->id,
                'name' => @$lead->first_name . ' ' . @$lead->last_name,
                'phone' => @$lead->phone,
                'email' => @$lead->email ? $lead->email : '',
                'other_emails' => $other_emails,
                'club' => @$lead->club ? $lead->club:'',
                'birth_date' => @$lead->birth_date ? date('d-m-Y', @$lead->birth_date) : '',
                'other_phones' => $other_phones,
                'company' => @$lead->company ? @$lead->company : '',
                'school' => @$lead->school ? @$lead->school : '',
                'image' => @$lead->image ? @$lead->image : 'image.jpg',
                'notes' => @$lead->notes ? @$lead->notes : '',
                'id_number' => @$lead->id_number ? @$lead->id_number : '',
                'religion' => @$lead->religion ? @$lead->religion : '',
                'address' => @$lead->address ? @$lead->address : '',
                'country' => $country ? $country : '',
                'social' => $social,
                'industry' => $industry,
                'icon' => 'noti/switch.png',
                'status' => $row->status,
                'date' =>$date,
                'time'=>$time,
                'diff'=>$row->created_at->diffForHumans(),
                'notiy_id'=>$row->id,
            ));
        }
        foreach ($task as $row) {
            $task = Task::find($row->type_id);
            $type = $task->task_type;
            $date = date('Y-m-d', $task->due_date);
            if ($task->phone)
                $phone = $task->phone;
            else
                $phone = '';
            if ($task->description)
                $description = $task->description;
            else $description = '';
            if ($task->location)
                $location = $task->location;
            else
                $location = '';
            if ($task->time)
                $time = $task->time;
            else
                $time = '';

            if ($task->task_type == 'call') {
                $icon = 'images/noti/call.png';
            } else if ($task->task_type == 'meeting') {
                $icon = 'noti/meeting.png';
            } else {
                $icon = 'noti/other.png';
            }
            $leads = json_decode($task->leads, true);
            if ($lead = Lead::find($leads[0])) {
                array_push($data, array(
                    'task_type'=>'task',
                    'type' => $type,
                    'id' => $row->type_id,
                    'title' => User::find($task->user_id)->name . ' set you in ' . $task->task_type . ' in ' . date('Y-m-d', $task->due_date),
                    'name' => $lead->first_name . ' ' . $lead->last_name,
                    'date' => $date, 'time' => $time,
                    'location' => $location,
                    'phone' => $lead->phone,
                    'description' => $description,
                    'icon' => $icon,
                ));
            }
        }
        foreach ($finish_task as $row) {
            $task = ToDo::find($row->type_id);
            $type = 'finish_task';
            $date = date('Y-m-d', $task->due_date);
            if ($task->phone)
                $phone = $task->phone;
            else
                $phone = '';
            if ($task->description)
                $description = $task->description;
            else $description = '';
            if ($task->location)
                $location = $task->location;
            else
                $location = '';
            if ($task->time)
                $time = $task->time;
            else $time = '';
            if ($lead = Lead::find($task->leads)) {
//                dd($task);
                array_push($data, array('type' => $type,
                    'task_type'=>'todo',
                    'id' => $row->type_id,
                    'title' => User::find($task->user_id)->name . ' set you in ' . $task->task_type . ' in ' . date('Y-m-d', $task->due_date),
                    'name' => $lead->first_name . ' ' . $lead->last_name,
                    'date' => $date, 'time' => $time != 0 ? date('h:i', $time) : '',
                    'location' => $location, 'phone' => $phone,
                    'description' => $description,
                    'icon' => 'noti/finish-task.png',
                ));
            }
        }
        return $data;
    }
    public function create_unit(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        $request = json_decode($request->getContent());

        $rules = [
            'resale_rental' => 'required',
            'type' => 'required',
            'total' => 'required',
            'unit_type_id' => 'required',
            'finishing' => 'required',
            'ar_description' => 'required',
            'en_description' => 'required',
            'ar_title' => 'required',
            'en_title' => 'required',
            'ar_address' => 'required',
            'en_address' => 'required',
            'phone' => 'required',
            'area' => 'required',
            'price' => 'required',
            'rooms' => 'required',
            'bathrooms' => 'required',
            'image' => 'required',
            'due_now' => 'required|numeric',
            'payment_method' => 'required',
            'view' => 'required',
            'facility' => 'required',
            'privacy' => 'required',
            'agent_id' => 'required',
        ];

        // $rules1 = array(
        //     "resale_rental" => $request->resale_rental,
        //     "type" => $request->type,
        //     "finishing" => $request->finishing,
        //     "ar_description" => $request->finishing,
        //     "en_description" => $request->finishing,
        //     "ar_title" => $request->finishing,
        //     "en_title" => $request->finishing,
        //     "ar_address" => $request->finishing,
        //     "en_address" => $request->finishing,
        //     "phone" => $request->finishing,
        //     "area" => $request->finishing,
        //     "price" => $request->finishing,
        //     "image" => $request->finishing,
        //     "payment_method" => $request->finishing,
        //     "view" => $request->finishing,
        //     "due_now" => $request->finishing,
        //     "facility" => $request->finishing,
        // );
        if ($request->resale_rental == 'resale') {
            $rules['price'] = 'required';
            $rules['total'] = 'required';
            $rules['rest'] = 'required';
            $rules['payed'] = 'required';
            $rules['original_price'] = 'required';
            // $rules1['price'] = $request->price;
            // $rules1['total'] = $request->total;
            // $rules1['rest'] = $request->rest;
            // $rules1['payed'] = $request->payed;
            // $rules1['original_price'] = $request->original_price;
        }

        if ($request->privacy == 'custom') {
            $rules['custom_agents'] = 'required';
        }
        $validator = Validator::make($request1, $rules);
        if (!$validator->fails()) {
            return ['status' => 'error'];
        } else {
            if ($request->resale_rental == 'resale') {
                $unit = new ResaleUnit;
                $unit->price = $request->price;
                $unit->total = $request->total;
                $unit->rest = $request->rest;
                $unit->payed = $request->payed;
                $unit->original_price = $request->original_price;
                $unit->priority = 0;
            } else if ($request->resale_rental == 'rental') {
                $unit = new RentalUnit();
                $unit->rent = $request->price;

            } else {
                return ['status' => 'select type (resale/rental)'];
            }

            if ($request->type = 'residential') {
                $unit->type = 'personal';
            } else {
                $unit->type = $request->type;
            }

            $unit->unit_type_id = $request->unit_type_id;
            $unit->project_id = $request->project_id;
            $unit->lead_id = $request->lead_id;
            $unit->delivery_date = $request->delivery_date;
            $unit->finishing = $request->finishing;
            $unit->location = $request->location;
            $unit->ar_notes = $request->ar_notes;
            $unit->en_notes = $request->en_notes;
            $unit->ar_description = $request->ar_description;
            $unit->en_description = $request->en_description;
            $unit->ar_title = $request->ar_title;
            $unit->en_title = $request->en_title;
            $unit->due_now = $request->due_now;
            $unit->ar_address = $request->ar_address;
            $unit->en_address = $request->en_address;
            $unit->youtube_link = $request->youtube_link;
            $unit->phone = $request->phone;
            $unit->other_phones = json_encode([]);

            $unit->area = $request->area;
            $unit->rooms = $request->rooms;
            $unit->bathrooms = $request->bathrooms;
            $unit->floors = $request->floors;
            $unit->lng = $request->lng;
            $unit->lat = $request->lng;
            $unit->zoom = $request->zoom;
            $unit->payment_method = $request->payment_method;
            $unit->view = $request->view;
            $unit->availability = 'available';
            $unit->user_id = $request->user_id;

            $unit->privacy = $request->privacy;
            $unit->agent_id = $request->agent_id;
            if ($request->privacy == 'custom') {
                $unit->custom_agents = json_encode($request->custom_agents);
            }

            $unit->save();

            $old_data = json_encode($unit);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $unit->ar_title,
                __('admin.created', [], 'en') . ' ' . $unit->en_title,
                'resale_units',
                $unit->id,
                'create',
                $request->user_id,
                $old_data
            );


            $project = new ProjectController();

            foreach ($request->facility as $facility) {
                $project->addfacility($unit->id, $facility, 'resale');
            }
            return response(['status' => true, 'unit_id' => $unit->id, 'type' => $request->resale_rental]);
        }
    }

    public function add_image(Request $request)
    {
        $token = $request->token;
        $user_id = $request->user_id;
        $unit_id = $request->unit_id;
        $type = $request->type;
        $count = AgentToken::where('user_id', $user_id)->where('token', $token)->where('login', true)->count();
        if ($count == 0) {
            return ['status' => 'unauthorized'];
        } else {

            if ($type == 'rental') {
                $unit = RentalUnit::find($unit_id);
                $other_image_model = new RentalImage();
                $route = 'rental_unit';
            } else {
                $unit = ResaleUnit::find($unit_id);
                $other_image_model = new ResalImage();
                $route = 'resale_unit';
            }

            $set = Setting::first();
            if ($request->hasFile('main')) {
                $unit->image = upload($request->main, $route);
                $watermark = Image::make('uploads/' . $set->watermark)->resize(50, 50);
                $image = Image::make('uploads/' . $unit->image);
                $image->insert($watermark, 'bottom-right', 10, 10);
                $image->save("uploads/" . $route . "/watermarked" . rand(0, 99999999999) . ".jpg");
                $unit->watermarked_image = $watermarked = $image->dirname . '/' . $image->basename;
                $unit->save();
            }
            if ($request->has('image')) {
                foreach ($request->image as $img) {
                    $other_image = upload($img, $route);
                    $watermark = Image::make('uploads/' . $set->watermark)->resize(50, 50);
                    $image = Image::make('uploads/' . $other_image);
                    $image->insert($watermark, 'bottom-right', 10, 10);
                    $image->save("uploads/" . $route . "/other_watermarked" . rand(0, 99999999999) . ".jpg");
                    $other_watermarked_images = $image->dirname . '/' . $image->basename;
                    $other_image_model->unit_id = $unit->id;
                    $other_image_model->image = $other_image;
                    $other_image_model->watermarked_image = $other_watermarked_images;
                    $other_image_model->save();
                }
            }
            // dd($other_image_model);
        }
        return ['status' => true];
    }

}
