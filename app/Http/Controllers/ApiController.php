<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventImage;
use App\Facility;
use App\Favorite;
use App\Gallery;
use App\HubPhone;
use App\HubSocial;
use App\Icon;
use App\Interested;
use App\Lead;
use App\LeadNotification;
use App\Location;
use App\Phase;
use App\Phase_Facilities;
use App\Project;
use App\Property;
use App\RecentViewed;
use App\RentalImage;
use App\RentalUnit;
use App\ResaleUnit;
use App\ResalImage;
use App\Setting;
use App\Tag;
use App\UnitFacility;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Validator;
use Mail;

class ApiController extends Controller
{

    public function restpassword(Request $request)
    {
        $token = str_random(50);
        $request1 = json_decode($request->getContent(), true);
        $email = $request1['email'];
        if (Lead::where('email', $email)->count() > 0) {
            if (DB::table('password_resets')->where('email', $email)->count() > 0) {
                $found = DB::table('password_resets')->where('email', $email)->first();
                DB::table('password_resets')->where('email', $email)->update(['token' => $token]);
            } else {
                DB::table('password_resets')->insert(['email' => $email, 'token' => $token]);
            }
            $response = array(
                "status" => 'ok',
            );
            Mail::send('mail', array('token' => $token), function ($message) use ($email) {
                $message->to($email)->subject('Reset Password');
            });
            return json_encode($response);
        } else {
            $response = array(
                "status" => 'email_not_found',
            );
            return json_encode($response);
        }
    }

    public function reset($token)
    {
        $count=DB::table('password_resets')->where('token', $token)->count();
        $found = DB::table('password_resets')->where('token', $token)->first();
        if ($count > 0)
            return view('newpassword');
        else
            return redirect('/');
    }

    public function resetpassword(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ];
        $validator = Validator::make(array(
                "email" => $request->email,
                "password" => $request->password,
                "password_confirmation" => $request->password_confirmation,
            )
            , $rules);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $count= Lead::where('email', $request->email)->orwhere('phone', $request->email)->count();
            $user = Lead::where('email', $request->email)->orwhere('phone', $request->email)->first();
            if ($count > 0) {
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();
                DB::table('password_resets')->where('email', $request->email)->delete();
                if (auth('lead')->attempt(['email' => $request->input('email'),
                    'password' => $request->input('password'),])) {
                    return redirect('/');
                }
            } else {
                return back();
            }
        }
    }

    public function register(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        try {
            $rules = [
                'fname' => 'required|max:191',
                'lname' => 'required|max:191',
                'phone' => 'required|max:191',
                'email' => 'required|email',
                'password' => 'required',
            ];
            $validator = Validator::make(array(
                    "fname" => $request1['fname'],
                    "lname" => $request1['lname'],
                    "phone" => $request1['phone'],
                    "email" => $request1['email'],
                    "password" => $request1['password'],
                )
                , $rules);
            if ($validator->fails()) {
                $response = array(
                    "status" => 'error',
                );

                return json_encode($response);
            }
            if ( Lead::where('email', $request1['email'])->count() > 0) {
                $lead = Lead::where('email', $request1['email'])->first();
                if ($lead->confirm == true) {
                    $response = array(
                        "status" => 'email',
                    );
                    return json_encode($response);
                } elseif (Lead::where('phone', $request1['phone'])->count() > 0 and ($lead->phone != $request1['phone'])) {
                    $response = array(
                        "status" => 'phone',
                    );
                    return json_encode($response);
                }
            } else {
                if (Lead::where('phone', $request1['phone'])->count() > 0) {
                    $response = array(
                        "status" => 'phone',
                    );
                    return json_encode($response);
                }
                $lead = new Lead;
            }
            $lead->first_name = $request1['fname'];
            $lead->last_name = $request1['lname'];
            $lead->email = $request1['email'];
            if ($request1['gender'] == "male") {
                $lead->prefix_name = "mr";
            } elseif ($request1['gender'] == "female") {
                $lead->prefix_name = "ms";
            }
            else
            {
                $lead->prefix_name = "mr";
            }
            $lead->phone = $request1['phone'];
            $lead->image = 'lead.png';
            $lead->password = bcrypt($request1['password']);
            $lead->lead_source_id = $request1['lead_source_id'];
            $lead->agent_id = 0;
            $lead->user_id = 0;
            $lead->confirm = true;
            $lead->save();
            return $this->login($request);
        } catch (Exception $e) {
            $response = array(
                "status" => 'error',
            );

            return json_encode($response);
        }
    }

        public function login(Request $request)
    {
//        dd(bcrypt('123'));
        $request1 = json_decode($request->getContent(), true);
        $client = Client::find(2);
        $request->request->add(
            [
                'username' => $request1['email'],
                'password' => $request1['password'],
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'grant_type' => 'password',
                'response_type' => 'code',
                'scope' => '',
            ]
        );
// dd($request1);
        $lead = @Lead::where('email', $request1['email'])->first();
        if (@Lead::where('email', $request1['email'])->count() > 0) {
            $tokenRequest = Request::create(
                env('APP_URL') . '/oauth/token',
                'post'
            );
            $token = json_decode(Route::dispatch($tokenRequest)->getContent());
            if (@$token->access_token) {
                $response = array(
                    "status" => 'ok',
                    "id" => $lead->id,
                    "fname" => $lead->first_name,
                    "lname" => $lead->last_name,
                    "email" => $lead->email,
                    "phone" => $lead->phone,
                    "token" => $token,
                );

                return json_encode($response);
            } else {
                return ['status' => 'error'];
            }
        } else {
            return ['status' => 'error'];
        }
    }

    public function logout()
    {
        $lead = Lead::find(auth('api')->user()->id);
        $lead->refresh_token = '';
        $lead->save();
        $accessToken = auth('api')->user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();
        return ['status'=>'ok'];
    }

    public function fblogin(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        $client = Client::find(2);
        $lead = @Lead::where('facebook', $request1['fbid'])->first();

        if (Lead::where('facebook', $request1['fbid'])->count() > 0) {
            $request->request->add(
                [
                    'username' => @$lead->email,
                    'password' => $request1['fbid'],
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'grant_type' => 'password',
                    'response_type' => 'code',
                    'scope' => '',
                ]
            );
            $tokenRequest = Request::create(
                env('APP_URL') . '/oauth/token',
                'post'
            );
            $token = json_decode(Route::dispatch($tokenRequest)->getContent());
            if (@$token->access_token) {
                $response = array(
                    "status" => 'ok',
                    "id" => $lead->id,
                    "fname" => $lead->first_name,
                    "lname" => $lead->last_name,
                    "email" => $lead->email,
                    "phone" => $lead->phone,
                    "token" => $token,
                );

                return json_encode($response);
            } else {
                return ['status' => 'error'];
            }
        } else {
            return ['status' => 'not found'];
        }
    }

    public function fbregister(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        $lead = new Lead;
        try {
            $rules = [
                'fname' => 'required|max:191',
                'fbid' => 'required|max:191',
                'lname' => 'required|max:191',
                'phone' => 'required|max:191',
                'email' => 'required|email',
                // 'gender' => 'required',
            ];
            $validator = Validator::make(array(
                    "fname" => $request1['fname'],
                    "lname" => $request1['lname'],
                    "phone" => $request1['phone'],
                    "email" => $request1['email'],
                    // "gender" => $request1['gender'],
                    'fbid' => $request1['fbid'],
                )
                , $rules);
            if ($validator->fails()) {
                $response = array(
                    "status" => 'error',
                );

                return json_encode($response);
            }
            if (Lead::where('email', $request1['email'])->count() > 0) {
                $lead = Lead::where('email', $request1['email'])->first();
                if ($lead->confirm == true) {
                    $response = array(
                        "status" => 'email',
                    );
                    return json_encode($response);
                } elseif (Lead::where('phone', $request1['phone'])->count() > 0 and ($lead->phone != $request1['phone'])) {
                    $response = array(
                        "status" => 'phone',
                    );
                    return json_encode($response);
                }
            } else {
                if (Lead::where('phone', $request1['phone'])->count() > 0) {
                    $response = array(
                        "status" => 'phone',
                    );
                    return json_encode($response);
                }
                $lead = new Lead;
            }
            $lead->first_name = $request1['fname'];
            $lead->last_name = $request1['lname'];
            $lead->email = $request1['email'];
            $lead->facebook = $request1['fbid'];
            if(array_key_exists('gender', $request1)){
                if ($request1['gender'] == "male") {
                    $lead->prefix_name = "mr";
                } elseif ($request1['gender'] == "female") {
                    $lead->prefix_name = "ms";
                }
            }

            $lead->phone = $request1['phone'];
            $lead->password = bcrypt($request1['fbid']);
            if($request1['image'])
                $lead->image = $request1['image'];
            else
                $lead->image = 'lead.png';
            $lead->lead_source_id = $request1['lead_source_id'];
            $lead->agent_id = 0;
            $lead->user_id = 0;
            $lead->confirm = true;
            $lead->save();
            return $this->fblogin($request);

        } catch (Exception $e) {
            $response = array(
                "status" => 'error',
            );

            return json_encode($response);
        }
    }


    public function test(Request $request)
    {

        $lang = $request['lang'];
        if ($lang == 'ar' or $lang == 'en') {
            $property = Property::leftJoin('phases', 'properties.phase_id', '=', 'phases.id')
                ->leftJoin('projects', 'phases.project_id', '=', 'projects.id')
                ->leftJoin('locations', 'projects.location_id', '=', 'locations.id')
                ->select('properties.id as id',
                    'properties.' . $lang . '_name as name',
                    'locations.' . $lang . '_name as location',
                    'properties.start_price as price',
                    'projects.logo as logo',
                    'properties.main as image',
                    'projects.down_payment as payment',
                    'projects.installment_year as installment_year',
                    'phases.delivery_date as delivery_date')
                ->get();
            return $property;
        }

    }

    public function properties(Request $request)
    {
        $data = '';
        $lang = @$request->lang;
        $lead_id = @$request->lead_id;
        $page_id = @$request->page_id;
        $project_type = @$request->project_type;
        $page_id = ($page_id - 1) * 15;
        if (@$lang == 'ar' or @$lang == 'en') {
            $projects = Project::where('mobile','1')->where('type', $project_type)->offset($page_id)->limit(15)->get();

            $data = [];
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
                    'installment_year' => $row->installment_year, 'down_payment'=> ($row->down_payment?$row->down_payment:' '),'installment_year'=>($row->installment_year?$row->installment_year:' '),'delivery_date'=>($row->delivery_date ? $row->delivery_date :' '),
                    'location' => $location, 'favorite' => $favorite));
            }
        }
        return $data;
    }

    public function project(Request $request)
    {
        $id = $request['id'];
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
//        dd('sheno');
        if (RecentViewed::where('lead_id', $lead_id)->where('unit_id', $id)->where('type', 'project')->count() > 0) {
            $recent1 = RecentViewed::where('lead_id', $lead_id)->where('unit_id', $id)->where('type', 'project')->first();

            $recent1->count += 1;
            $recent1->save();
        } else {
            // dd($lead_id);
            $recent = new RecentViewed;
            $recent->lead_id = $lead_id;
            $recent->unit_id = $id;
            $recent->type = "project";
            $recent->save();
        }
        $favorite = 'false';
        $num = Favorite::where('unit_id', '=', $id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
        if ($num > 0) {
            $favorite = 'true';
        }

        $interested = 'false';
        $num = Interested::where('unit_id', '=', $id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
        if ($num > 0) {
            $interested = 'true';
        }

        $project = Project::find($id);

        $data['data'] = array();
        $data['units'] = array();
        $data['gallery'] = array();
        $data['suggest'] = array();
        $data['facility'] = array();
        // dd($project);
        $location = Location::find($project->location_id)->{$lang . '_name'};

        $phase = Phase::where('project_id', '=', $project->id)->get();

        $data['data'] = array("id" => $project->id, "name" => $project->{$lang . '_name'},
            'description' => $project->{$lang . '_description'}, 'price' => $project->meter_price,
            'logo' => $project->logo, 'image' => $project->cover, 'payment' => $project->down_payment,
            'installment_year' => $project->installment_year, 'lat' => $project->lat,
            'lng' => $project->lng, 'zoom' => $project->zoom, 'delivery_date' => $project->delivery_date,
            'location' => $location, 'favorite' => $favorite, 'interested' => $interested);

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
        $tags1 = Tag::select('id')->pluck('id')->toArray();
        $tags = DB::table('project_tags')->selectRaw('project_id,count(*) as count')->groupBy('project_id')->orderBy('count', 'DESC')->whereIn('tag_id', $tags1)->where('project_id', '!=', $id)->get();
$count=0;
        foreach ($tags as $tag) {
            //------------------------------------------------------------------------------------
            $project = Project::where('id',$tag->project_id)->where('mobile','1')->first();
            if($project!=null){
                $location = @Location::find($project->location_id)->{$lang . '_name'};
                array_push($data['suggest'], array("id" => @$project->id, "name" => @$project->{$lang . '_name'}, 'price' => $project->meter_price,
                    'logo' => @$project->logo, 'image' => @$project->cover, 'payment' => @$project->down_payment,
                    'installment_year' => @$project->installment_year, 'delivery_date' => @$project->delivery_date, 'location' => $location));
                    $count++;
            }
            if($count==2)
            break;


        }
        $facility = UnitFacility::where('unit_id',$id)->where('type','project')->get();

        foreach ($facility as $row1) {
            $facility1 = Facility::find($row1->facility_id);

            $icon = Icon::find($facility1->icon);

            array_push($data['facility'], array("icon" => $icon->icon, "name" => $facility1->{$lang . '_name'}));
        }
        return $data;
    }

    public function favorite(Request $request)
    {
        try {
            $project_id = $request['unit_id'];
            $lead_id = $request['lead_id'];
            $type = $request['type'];
            if (Favorite::where('unit_id', '=', $project_id)->where('lead_id', '=', $lead_id)->where('type', '=', $type)->count() > 0) {
                $favorite = Favorite::where('unit_id', '=', $project_id)->where('lead_id', '=', $lead_id)->where('type', '=', $type)->first();
                $favorite->delete();
                $response = array(
                    "status" => 'ok',
                );
            } else {
                $favorite = new Favorite;
                $favorite->unit_id = $project_id;
                $favorite->lead_id = $lead_id;
                $favorite->type = $type;
                $favorite->save();
                $response = array(
                    "status" => 'ok',
                );
            }
            return $response;
        } catch (Exception $e) {
            $response = array(
                "status" => 'error',
            );
            return $response;
        }
    }

    public function add_interested(Request $request)
    {
        try {
            $unit_id = $request['unit_id'];
            $lead_id = $request['lead_id'];
            $type = $request['type'];
            $interested = new Interested();
            $interested->unit_id = $unit_id;
            $interested->lead_id = $lead_id;
            $interested->type = $type;
            $interested->save();
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

    public function delete_interested(Request $request)
    {
        try {
            $unit_id = $request['unit_id'];
            $lead_id = $request['lead_id'];
            $type = $request['type'];
            Interested::where('unit_id', '=', $unit_id)->where('lead_id', '=', $lead_id)->where('type', '=', $type)->delete();
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

    public function favorite_project(Request $request)
    {
        $lead_id = $request['lead_id'];
        $lang = $request['lang'];
        $data = [];
        $favorite = Favorite::where('lead_id', '=', $lead_id)->where('type', '=', 'project')->select('unit_id')->get();
        if ($lang == 'ar' or $lang == 'en') {

            foreach ($favorite as $row) {
                if ($project = Project::find($row->unit_id)) {
                    $location = Location::find($project->location_id)->{$lang . '_name'};
                    array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                        'logo' => $row->logo, 'image' => $row->cover, 'payment' => $row->down_payment,
                        'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                        'installment_year' => $row->installment_year, 'delivery_date' => $row->delivery_date,
                        'location' => $location, 'favorite' => $favorite));
                }
            }
        }
        return $data;

    }

    public function resales(Request $request)
    {
        $lang = $request->lang;
        $lead_id = $request->lead_id;
        $unit_type = $request->unit_type;
        $data = [];
        $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
        where('availability', 'available')->
        where('type', $unit_type)->
        select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
            'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
            'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($resales as $resale) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
            if ($num > 0) {
                $favorite = 'true';
            }
$images=ResalImage::where('unit_id',$resale->id)->select('image')->get()->toArray();
//            dd($images);
            array_push($data, array('id' => $resale->id, 'location' => $resale->{$lang . '_name'},
                'home_image' => $resale->image, 'other_images' => $images,
                'title' => $resale->{$lang . '_title'}, 'price' => $resale->price, 'area' => $resale->area,
                'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms, 'favorite' => $favorite,
            ));
//            dd($data);
        }
        return $data;
    }

    public function resale_unit(Request $request)
    {
        $id = $request['id'];
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        if (RecentViewed::where('lead_id', $lead_id)->where('unit_id', $id)->where('type', 'resale')->count() > 0) {
            $recent1 = RecentViewed::where('lead_id', $lead_id)->where('unit_id', $id)->where('type', 'resale')->first();

            $recent1->count += 1;
            $recent1->save();
        } else {
            $recent = new RecentViewed;
            $recent->lead_id = $lead_id;
            $recent->unit_id = $id;
            $recent->type = "resale";
            $recent->save();
        }
        $favorite = 'false';
        $num = Favorite::where('unit_id', '=', $id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
        if ($num > 0) {
            $favorite = 'true';
        }

        $interested = 'false';
        $num = Interested::where('unit_id', '=', $id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
        if ($num > 0) {
            $interested = 'true';
        }

        $unit = ResaleUnit::find($id);

        $location = Location::find($unit->location)->{$lang . '_name'};
        // dd('sheno');
        $otherimages=ResalImage::where('unit_id',$id)->get();
        $images=[];
        foreach ($otherimages as $row)
        {
            array_push($images,trim($row->watermarked_image,'uploads/'));
        }
        $images=ResalImage::where('unit_id',$unit->id)->select('image')->get()->toArray();
        $data = array('id' => $unit->id, 'location' => $location,
            'home_image' => $unit->image, 'other_images' => $images,
            'title' => $unit->{$lang . '_title'}, 'price' => $unit->price, 'area' => $unit->area,
            'rooms' => $unit->rooms, 'bathrooms' => $unit->bathrooms, 'favorite' => $favorite,
            'interested' => $interested, 'due_now' => $unit->due_now, 'finished' => $unit->finishing,
            'description' => $unit->{$lang . '_description'},
            'main_phone' => $unit->phone, 'other_phone' => json_decode($unit->other_phones),
        );
        return $data;

    }

    public function project_view(Request $request)
    {

        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        $data = [];
        if ($lang == 'ar' or $lang == 'en') {
            $recent = RecentViewed::where('lead_id', $lead_id)->where('type', 'project')->pluck('unit_id');
            $projects = Project::whereIn('id', $recent)->get();
            foreach ($projects as $row) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                if ($num > 0) {
                    $favorite = 'true';
                }

                $location = Location::find($row->location_id)->{$lang . '_name'};
                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'logo' => $row->logo, 'image' => $row->cover, 'payment'=> ($row->down_payment?$row->down_payment:' '),'installment_year'=>($row->installment_year?$row->installment_year:' '),'delivery_date'=>($row->delivery_date ? $row->delivery_date :' '),
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'location' => $location, 'favorite' => $favorite));
            }
        }
        return $data;
    }

    public function resale_view(Request $request)
    {
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        $data = [];
        $recent = RecentViewed::where('lead_id', $lead_id)->where('type', 'resale')->pluck('unit_id');
        $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
        where('availability', 'available')->
        whereIn('resale_units.id', $recent)->
        select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
            'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
            'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($resales as $resale) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
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

    public function saved_project(Request $request)
    {
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
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
                    'logo' => $row->logo, 'image' => $row->cover,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                   'payment'=> ($row->down_payment?$row->down_payment:' '),'installment_year'=>($row->installment_year?$row->installment_year:' '),'delivery_date'=>($row->delivery_date ? $row->delivery_date :' '),
                    'location' => $location, 'favorite' => $favorite));
            }
        }
        return $data;
    }

    public function saved_resale(Request $request)
    {
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
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
            $num = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
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

    public function rentals(Request $request)
    {
        $lang = $request->lang;
        $lead_id = $request->lead_id;
        $unit_type = $request->unit_type;
        $data = [];
        $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
        where('availability', 'available')->
        where('type', $unit_type)->
        select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
            'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
            'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($rentals as $rental) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'rental')->count();
            if ($num > 0) {
                $favorite = 'true';
            }

            array_push($data, array('id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                'title' => $rental->{$lang . '_title'}, 'rent' => $rental->rent, 'area' => $rental->area,
                'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms, 'favorite' => $favorite,
            ));
        }
        return $data;
    }

    public function rental_unit(Request $request)
    {
        $id = $request['id'];
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        if ( RecentViewed::where('lead_id', $lead_id)->where('unit_id', $id)->where('type', 'rental')->count() > 0) {
            $recent1 = RecentViewed::where('lead_id', $lead_id)->where('unit_id', $id)->where('type', 'rental')->first();

            $recent1->count += 1;
            $recent1->save();
        } else {
            $recent = new RecentViewed;
            $recent->lead_id = $lead_id;
            $recent->unit_id = $id;
            $recent->type = "rental";
            $recent->save();
        }
        $favorite = 'false';
        $num = Favorite::where('unit_id', '=', $id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
        if ($num > 0) {
            $favorite = 'true';
        }

        $interested = 'false';
        $num = Interested::where('unit_id', '=', $id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
        if ($num > 0) {
            $interested = 'true';
        }

        $unit = RentalUnit::find($id);
        $location = Location::find($unit->location)->{$lang . '_name'};
        // dd('sheno');
        $otherimages=RentalImage::where('unit_id',$id)->get();
        $images=[];
        foreach ($otherimages as $row)
        {
            array_push($images,trim($row->watermarked_image,'uploads/'));
        }
        $data = array('id' => $unit->id, 'location' => $location,
            'home_image' => $unit->image, 'other_images' => $images,
            'title' => $unit->{$lang . '_title'}, 'price' => $unit->rent, 'area' => $unit->area,
            'rooms' => $unit->rooms, 'bathrooms' => $unit->bathrooms, 'favorite' => $favorite,
            'interested' => $interested, 'due_now' => $unit->due_now, 'finished' => $unit->finishing,
            'description' => $unit->{$lang . '_description'},
            'main_phone' => $unit->phone, 'other_phone' => json_decode($unit->other_phones),
        );
        return $data;

    }

    public function rental_view(Request $request)
    {
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        $data = [];
        $recent = RecentViewed::where('lead_id', $lead_id)->where('type', 'rental')->pluck('unit_id');
        $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
        where('availability', 'available')->
        whereIn('rental_units.id', $recent)->
        select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
            'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
            'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
        foreach ($rentals as $rental) {
            $favorite = 'false';
            $num = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'rental')->count();
            if ($num > 0) {
                $favorite = 'true';
            }

            array_push($data, array(
                'id' => $rental->id, 'location' => $rental->{$lang . '_name'},
                'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                'title' => $rental->{$lang . '_title'}, 'price' => $rental->rent, 'area' => $rental->area,
                'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms, 'favorite' => $favorite,
            ));
        }
        return $data;
    }

    public function saved_rental(Request $request)
    {
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
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
            $num = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'rental')->count();
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

    public function contact_us(Request $request)
    {
        $info = Setting::first();
        $phones = HubPhone::all();
        $socials = HubSocial::all();
        $data['data'] = [];
        $data['social'] = [];
        $lang=$request['lang'];
        $phone1 = @HubPhone::first()->phone;
        $phone2 = '';
        if (HubPhone::count() > 2) {
            for ($i = 0; $i < 2; $i++) {
                if ($i == 0) {
                    $phone1 = $phones[$i]->phone;
                } elseif ($i == 1) {
                    $phone2 = $phones[$i]->phone;
                }
            }
        }
        if($lang=='ar')
        {
            $data['data'] = array('lat' => $info->lat,
                'lng' => $info->lng,
                'zoom' => $info->zoom,
                'get_in_touch' => $info->ar_get_in_touch,
                'address' => $info->ar_address,
                'email' => $info->email,
                'phone1' => $phone1,
                'phone2' => $phone2,
            );
        }
        else {
            $data['data'] = array('lat' => $info->lat,
                'lng' => $info->lng,
                'zoom' => $info->zoom,
                'get_in_touch' => $info->get_in_touch,
                'address' => $info->address,
                'email' => $info->email,
                'phone1' => $phone1,
                'phone2' => $phone2,
            );
        }

        foreach ($socials as $social) {
            array_push($data['social'], array('icon' => $social->mobile_icon, 'link' => $social->link));
        }
        return $data;

    }

    public function about_us(Request $request)
    {
        $info = Setting::first();
        $phones = HubPhone::all();
        $socials = HubSocial::all();
        $data['data'] = [];
        $data['phones'] = [];
        $data['social'] = [];
        $lang=$request['lang'];
        $phone1 = @HubPhone::first()->phone;
        $phone2 = '';
        if($lang=='ar')
            if (HubPhone::count() > 2) {
                for ($i = 0; $i < 2; $i++) {
                    if ($i == 0) {
                        $phone1 = $phones[$i]->phone;
                    } elseif ($i == 1) {
                        $phone2 = $phones[$i]->phone;
                    }
                }
            }
        if($lang=='ar')
        {
            $data['data'] = array(
                'about_us' => $info->ar_about_hub,
                'mission' => $info->ar_mission,
                'vision' => $info->ar_vision,
                'address' => $info->ar_address,
                'email' => $info->email,
                'phone1' => $phone1,
                'phone2' => $phone2
            );
        }
        else{
            $data['data'] = array(
                'about_us' => $info->about_hub,
                'mission' => $info->mission,
                'vision' => $info->vision,
                'address' => $info->address,
                'email' => $info->email,
                'phone1' => $phone1,
                'phone2' => $phone2
            );
        }
        foreach ($socials as $social) {
            array_push($data['social'], array('icon' => $social->mobile_icon, 'link' => $social->link));
        }
        return $data;

    }

    public function events(Request $request)
    {
        $lang = $request->lang;

        $events = Event::select('id', $lang . '_title as title', 'date', 'image', $lang . '_description as description', 'event', 'news', 'launch')->get();
        $eventArr = [];
        foreach ($events as $event) {
            $images = @EventImage::where('event_id', $event->id)->pluck('image');
            $eventArr[] = [
                'id' => $event->id,
                'title' => $event->title,
                'image' => $event->image,
                'description' => $event->description,
                'date' => date('d-M-Y', $event->date),
                'event' => $event->event,
                'news' => $event->news,
                'launch' => $event->launch,
                'images' => $images
            ];
        }
        return $eventArr;
    }

    public function vacation(Request $request)
    {
        $data = '';
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        $page_id=$request['page_id'];
        $page_id=($page_id-1)*15;
        if ($lang == 'ar' or $lang == 'en') {

            $projects = Project::where('vacation', true)->offset($page_id)->limit(15)->get();
            $data = [];
            foreach ($projects as $row) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                if ($num > 0) {
                    $favorite = 'true';
                }

                $location = Location::find($row->location_id)->{$lang . '_name'};

                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'logo' => $row->logo, 'image' => $row->cover,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'payment'=> ($row->down_payment?$row->down_payment:' '),'installment_year'=>($row->installment_year?$row->installment_year:' '),'delivery_date'=>($row->delivery_date ? $row->delivery_date :' '),
                    'location' => $location, 'favorite' => $favorite));
            }
        }
        return $data;
    }

    public function refresh(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->refresh_token = $request->refresh_token;
        $lead->save();
        return 'true';
    }

    public function notification(Request $request)
    {
        $lang = $request['lang'];
        $lead_id = $request['lead_id'];
        $notification=[];
        if ($lang == 'ar' or $lang == 'en') {
            if($lang == 'ar')
                $notification=LeadNotification::where('lead_id',$lead_id)->
                select('type','type_id as id','ar_title as title','ar_body as body','created_at as date')->get()->toArray();
            else
                $notification=LeadNotification::where('lead_id',$lead_id)->
                select('type','type_id as id','en_title as title','en_body as body','created_at as date')->get()->toArray();
        }
        return json_encode($notification);

    }

    public function facebook_lead()
    {
           $challenge = isset($_REQUEST['hub_challenge']) ? $_REQUEST['hub_challenge'] : '';
$verify_token = isset($_REQUEST['hub_verify_token']) ? $_REQUEST['hub_verify_token'] : '';
$fb_access_token = "{{ App\Setting::first()->fb_token }}";
// this is used to subscribe to facebook webhook
if ($verify_token == "asd") { //
  echo $challenge;
}

    }

    public function facebook_lead1(Request $request)
    {
            $challenge = isset($_REQUEST['hub_challenge']) ? $_REQUEST['hub_challenge'] : '';
$verify_token = isset($_REQUEST['hub_verify_token']) ? $_REQUEST['hub_verify_token'] : '';
$fb_access_token = "{{ App\Setting::first()->fb_token }}";
// this is used to subscribe to facebook webhook
if ($verify_token == "asd") { //
  echo $challenge;
}
        $data=json_decode($request->getContent(),true);
        $leadgen_id = $data['entry'][0]['changes'][0]['value']['leadgen_id']; // extract leadgen ID
        if($leadgen_id){
            $ch = curl_init();
            $url = "https://graph.facebook.com/v2.8/".$leadgen_id;
            $url_query = "access_token=".$fb_access_token;
            $url_final = $url.'?'.$url_query;
            curl_setopt($ch, CURLOPT_URL, $url_final);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close ($ch);
            $data = json_decode($response);


        }
        $lead=new Lead();
      foreach ($data->field_data as $key=>$value )
      {
          switch ($value->name)
          {
              case 'email':if(Lead::where('email',$value->values['0'])->count()==0){ $lead->email=$value->values['0'];} break;
              case 'first_name':$lead->first_name =$value->values['0'];break;
              case 'last_name':$lead->last_name  =$value->values['0'];break;
              case 'phone_number': if(Lead::where('phone',$value->values['0'])->count()==0){ $lead->phone =$value->values['0']; };break;
              case 'gender':
                  if($value->values['0']=='male')
                  $lead->prefix_name  ='mr';
                  else if($value->values['0']=='female')
                      $lead->prefix_name  ='ms';
          }
      }
      if($lead->email && $lead->phone)
      $lead->save();
    }

public function ios_properties(Request $request)
    {

        if ($request->isMethod('post')) {
            $request1= json_decode($request->getContent());
        } else {
            $request1=$request;
        }

     // dd($request1);
        $data = '';
        $lang=$request1->lang;
        if ($lang == 'ar' or $lang == 'en') {

            $projects = Project::where('mobile','1')->offset(0)->limit(15)->get();

            $data = [];
            foreach ($projects as $row) {

                $location = @Location::find($row->location_id)->{$lang . '_name'};

                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                'payment'=> ($row->down_payment?$row->down_payment:' '),'installment_year'=>($row->installment_year?$row->installment_year:' '),'delivery_date'=>($row->delivery_date ? $row->delivery_date :' '),
                    'logo' => $row->logo, 'image' => $row->cover,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'location' => $location));
            }
        }
        return $data;
    }
}
