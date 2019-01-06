<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Form;
use App\LeadSource;
use Image;
use App\ClosedDeal;
use App\Event;
use App\Favorite;
use App\HubPhone;
use App\HubSocial;
use App\Income;
use App\Lead;
use App\Location;
use App\Outcome;
use App\Project;
use App\Property;
use App\Proposal;
use App\RentalUnit;
use App\ResaleUnit;
use App\Setting;
use Illuminate\Http\Request;
use Mail;
use Validator;
use App\Facility;
use App\UnitType;
use App\Phase_Facilities;
use App\Property_images;
use App\User;
use App\DealAgents;
use App\Phase;
use App\Role;
use Config;
use App\UnitFacility;
use Webklex\IMAP\Client;
use Excel;
use Auth;
use App\Request as Model;
use App\Contract;
use App\Group;
use App\AdminNotification;
use App\ProjectRequest;
use DB;
use App\LeadDocument;
use App\Employee;

//use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    private static $allLocation = [];
    
    private static $allTeam = [];

    public function login_post(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames([
            'email' => trans('admin.email'),
            'password' => trans('admin.password')
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if (auth()->attempt(['email' => $request->input('email'),
                'password' => $request->input('password'),],
                $request->input('remember'))) {
                LogController::add_log(
                    __('admin.logged_in', [], 'ar'),
                    __('admin.logged_in', [], 'en'),
                    'logged_in',
                    1,
                    'log',
                    auth()->user()->id
                );
                
                 if(auth()->user()->type=='employee'||auth()->user()->type == 'admin')

                    auth()->user()->last_seen_dash = strtotime(date('d-m-y H:i:s'));
                    auth()->user()->save();
                    // return redirect(adminPath() . '/employees/'.Employee::where('user_id',auth()->user()->id)->first()->id);
                    return redirect(adminPath() . '/');
            } else {
                session()->flash('login_error', trans('admin.fails_login'));
                return back();
            }
        }
    }

    public function login()
    {
        $title = Setting::first()->title;

        return view('admin.login', ['title' => $title]);
    }

    public function logout()
    {
        LogController::add_log(
            __('admin.logged_out', [], 'ar'),
            __('admin.logged_out', [], 'en'),
            'logged_out',
            0,
            'log',
            auth()->user()->id
        );
        auth()->logout();
        return redirect(adminPath() . '/login');
    }

    public function lang($lang)
    {
        session()->put('lang', $lang);
//        dd(app()->getLocale());
        return back();
    }

    public function inventory()
    {
        $resale = ResaleUnit::where('availability', 'available')->get();
        $newHomes = Property::all();
        $rental = RentalUnit::where('availability', 'available')->get();

        return view('admin.inventory', ['title' => trans('admin.inventory'),
            'resale' => $resale,
            'newHomes' => $newHomes,
            'rental' => $rental]);
    }

    public static function getLocations($id)
    {
        $locations = Location::where('parent_id', $id)->get();
        foreach ($locations as $location) {
            array_push(self::$allLocation, $location->id);
            self::getLocations($location->id);
        }
    }

    public static function getChildren($id)
    {
        self::getLocations($id);
        return self::$allLocation;
    }

    public function all_finances()
    {
        $incomes = Income::all();
        $outcomes = Outcome::all();
        return view('admin.finances.index', ['title' => trans('admin.finances'), 'incomes' => $incomes, 'outcomes' => $outcomes]);
    }

    public function favorite(Request $request)
    {
        $id = $request->unit_id;
        $type = $request->type;
        $lead = $request->lead;
        $var = Favorite::where('type', $type)
            ->where('unit_id', $id)
            ->where('lead_id', $lead)
            ->count();
        if ($var > 0) {
            $response = 'delete';
            $delete = Favorite::where('unit_id', $id)
                ->where('type', $type)
                ->where('lead_id', $lead)
                ->first();
            $delete->delete();
        } else {
            $response = 'add';
            $fav = new Favorite;
            $fav->unit_id = $id;
            $fav->type = $type;
            $fav->lead_id = $lead;
            $fav->save();
        }
        return response()->json([
            'status' => 'true',
            'response' => $response,
        ]);
    }

    public function unfavorite($type, $id, $lead)
    {
        $fav = Favorite:: where('unit_id', $id)->
        where('lead_id', $lead)->
        where('type', $type)->first();
        $fav->delete();
        return back();
    }

    public function settings_menu()
    {
        return view('admin.settings_menu', ['title' => __('admin.settings')]);
    }

    public function search_info()
    {
        $project_min_price = Project::min('meter_price');
        $resale_min_price = ResaleUnit::min('price');
        $rental_min_price = RentalUnit::min('rent');
        $project_max_price = Project::max('meter_price');
        $resale_max_price = ResaleUnit::max('price');
        $rental_max_price = RentalUnit::max('rent');
        $rental_personal_max_price = RentalUnit::where('type', 'personal')->max('rent');
        $rental_personal_min_price = RentalUnit::where('type', 'personal')->min('rent');
        $rental_personal_max_area = RentalUnit::where('type', 'personal')->max('area');
        $rental_personal_min_area = RentalUnit::where('type', 'personal')->min('area');
        $rental_commercial_max_price = RentalUnit::where('type', 'commercial')->max('rent');
        $rental_commercial_min_price = RentalUnit::where('type', 'commercial')->min('rent');
        $rental_commercial_max_area = RentalUnit::where('type', 'commercial')->max('area');
        $rental_commercial_min_area = RentalUnit::where('type', 'commercial')->min('area');
        $resale_personal_max_price = ResaleUnit::where('type', 'personal')->max('price');
        $resale_personal_min_price = ResaleUnit::where('type', 'personal')->min('price');
        $resale_personal_max_area = ResaleUnit::where('type', 'personal')->max('area');
        $resale_personal_min_area = ResaleUnit::where('type', 'personal')->min('area');
        $resale_commercial_max_price = ResaleUnit::where('type', 'commercial')->max('price');
        $resale_commercial_min_price = ResaleUnit::where('type', 'commercial')->min('price');
        $resale_commercial_max_area = ResaleUnit::where('type', 'commercial')->max('area');
        $resale_commercial_min_area = ResaleUnit::where('type', 'commercial')->min('area');
        $project_personal_max_price = Project::where('type', 'personal')->max('meter_price');
        $project_personal_min_price = Project::where('type', 'personal')->min('meter_price');
        $project_personal_max_area = Project::where('type', 'personal')->max('area');
        $project_personal_min_area = Project::where('type', 'personal')->min('area');
        $project_commercial_max_price = Project::where('type', 'commercial')->max('meter_price');
        $project_commercial_min_price = Project::where('type', 'commercial')->min('meter_price');
        $project_commercial_max_area = Project::where('type', 'commercial')->max('area');
        $project_commercial_min_area = Project::where('type', 'commercial')->min('area');


        $project_min_area = Project::min('area');
        $resale_min_area = ResaleUnit::min('area');
        $rental_min_area = RentalUnit::min('area');
        $project_max_area = Project::max('area');
        $resale_max_area = ResaleUnit::max('area');
        $rental_max_area = RentalUnit::max('area');

        $location = Location::all();
        $facilities = Facility::get();
        if (app()->getLocale() == 'en') {
            $unit_type = UnitType::select('id', 'en_name as name')->get()->toArray();
            $facilities = Facility::select('id', 'en_name as name')->get()->toArray();
            $location = Location::select('id', 'en_name as name')->get()->toArray();
        }
        if (app()->getLocale() == 'ar') {
            $unit_type = UnitType::select('id', 'en_name as name')->get()->toArray();
            $facilities = Facility::select('id', 'ar_name as name')->get()->toArray();
            $location = Location::select('id', 'ar_name as name')->get()->toArray();
        }
        $search['region'] = $location;
        $search['unit_type'] = $unit_type;
        $search['facilities'] = $facilities;
        $search['data'] = ['project_min_price' => $project_min_price, 'resale_min_price' => $resale_min_price,
            'rental_min_price' => $rental_min_price, 'project_max_price' => $project_max_price,
            'resale_max_price' => $resale_max_price, 'rental_max_price' => $rental_max_price, 'project_min_area' => $project_min_area,
            'resale_min_area' => $resale_min_area, 'rental_min_area' => $rental_min_area, 'project_max_area' => $project_max_area,
            'resale_max_area' => $resale_max_area, 'rental_max_area' => $rental_max_area,
            'rental_personal_max_price' => $rental_personal_max_price,
            'rental_personal_min_price' => $rental_personal_min_price,
            'rental_personal_min_area' => $rental_personal_min_area,
            'rental_personal_max_area' => $rental_personal_max_area,
            'rental_commercial_max_price' => $rental_commercial_max_price,
            'rental_commercial_min_price' => $rental_commercial_min_price,
            'rental_commercial_min_area' => $rental_commercial_min_area,
            'rental_commercial_max_area' => $rental_commercial_max_area,
            'resale_personal_max_price' => $resale_personal_max_price,
            'resale_personal_min_price' => $resale_personal_min_price,
            'resale_personal_min_area' => $resale_personal_min_area,
            'resale_personal_max_area' => $resale_personal_max_area,
            'resale_commercial_max_price' => $resale_commercial_max_price,
            'resale_commercial_min_price' => $resale_commercial_min_price,
            'resale_commercial_min_area' => $resale_commercial_min_area,
            'resale_commercial_max_area' => $resale_commercial_max_area,
            'project_personal_max_price' => $project_personal_max_price,
            'project_personal_min_price' => $project_personal_min_price,
            'project_personal_min_area' => $project_personal_min_area,
            'project_personal_max_area' => $project_personal_max_area,
            'project_commercial_max_price' => $project_commercial_max_price,
            'project_commercial_min_price' => $project_commercial_min_price,
            'project_commercial_min_area' => $project_commercial_min_area,
            'project_commercial_max_area' => $project_commercial_max_area,

        ];
        return $search;
    }

    public function home()
    {
        $search = $this->search_info();
        return view('website.home', compact('search'));
    }

    public function search(Request $request)
    {
        $location_id = $request->location;
        if ($location_id == null) {
            $location_id = 0;
        }
        $lead_id = 1;
        $min_price = (double)$request->min_price;
        $max_price = (double)$request->max_price;
        $min_area = (double)$request->min_area;
        $max_area = (double)$request->max_area;
        $locations = HomeController::getChildren($location_id);
        array_push($locations, (int)$location_id);
        $request->facility;
        $lang = app()->getLocale();
        $type = $request->type;
        $facilities = $request->facility;
        $data = [];
//dd($request->unit_type );
        if ($request->unit_type != null) {
            $unit_types = $request->unit_type;
        } else {
            $unit_types = UnitType::select('id')->get()->toArray();
        }
        if ($type == 'project') {
            $properties = Project::leftJoin('locations', 'locations.id', '=', 'projects.location_id')->
            whereIn('projects.location_id', $locations)->where(function ($query) use ($min_price, $max_price) {
                $query->where('projects.meter_price', '>=', $min_price)->
                where('projects.meter_price', '<=', $max_price);
            })->where(function ($query) use ($min_area, $max_area) {
                $query->where('projects.area', '>=', $min_area)->
                where('projects.area', '<=', $max_area);
            })->
            where(function ($query) use ($request) {
                $query->where('projects.en_name', 'like', '%' . $request->keyword . '%')->
                orwhere('projects.ar_name', 'like', '%' . $request->keyword . '%');
            })->where('projects.show_website', 1)->
            select('projects.id as id')->get();

            $ids = [];
            foreach ($properties as $row)
                array_push($ids, $row->id);
            $ids = array_unique($ids);
//            dd($facilities);
            if ($facilities == null) {
                $projects = Project::whereIn('id', $ids)->get();
            } else {
                $ids2 = [];
                $items = Project::leftJoin('unit_facilities', 'unit_facilities.unit_id', 'projects.id')->where('unit_facilities.type', 'project')->whereIn('unit_facilities.facility_id', $facilities)->select('projects.id as id')->get();
                foreach ($items as $item)
                    array_push($ids2, $item->id);
                $ids3 = array_unique($ids2);

                $projects = Project::whereIn('id', $ids3)->whereIn('id', $ids)->get();
            }
            foreach ($projects as $row) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                if ($num > 0)
                    $favorite = 'true';
                $location = Location::find($row->location_id)->{$lang . '_name'};
                $location = Location::find($row->location_id)->{$lang . '_name'};
                array_push($data, array("id" => $row->id, "title" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'home_image' => $row->logo, 'image' => $row->cover, 'payment' => $row->down_payment,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'installment_year' => $row->installment_year,
                    'location' => $location, 'favorite' => $favorite, 'area' => $row->area,
                    'description' => $row->{$lang . '_description'},
                    'marker' => $row->map_marker, 'cover' => $row->cover));
            }

            $type1 = 'project';
        } else if ($type == 'resale') {
            $resales = ResaleUnit:: leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
            whereIn('resale_units.unit_type_id', $unit_types)->
            where(function ($query) use ($locations, $location_id) {
                $query->whereIn('resale_units.location', $locations)->
                orwhere('resale_units.location', $location_id);
            })->
            where(function ($query) use ($min_price, $max_price) {
                $query->where('resale_units.price', '>=', $min_price)->
                where('resale_units.price', '<=', $max_price);
            })->
            where(function ($query) use ($min_area, $max_area) {
                $query->where('resale_units.area', '>=', $min_area)->
                where('resale_units.area', '<=', $max_area);
            })->
            where(function ($query) use ($request) {
                $query->where('resale_units.en_title', 'like', '%' . $request->keyword . '%')->
                orwhere('resale_units.ar_title', 'like', '%' . $request->keyword . '%');
            })->
            where('resale_units.availability', 'available')->
            select('resale_units.id as id', 'resale_units.en_title as en_name', 'resale_units.ar_title as ar_name'
                , 'resale_units.price as price', 'resale_units.area as area', 'locations.en_name as location_en_name'
                , 'locations.ar_name as location_ar_name'
                , 'resale_units.unit_type_id as unit_type'
                , 'resale_units.area as area', 'resale_units.rooms as rooms', 'resale_units.bathrooms as bathrooms'
                , 'resale_units.image as image', 'resale_units.other_images as other_images',
                'resale_units.lat', 'resale_units.lng', 'resale_units.zoom',
                'resale_units.en_description', 'resale_units.ar_description')
                ->get();
            foreach ($resales as $resale) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
                if ($num > 0)
                    $favorite = 'true';
                array_push($data, array('id' => $resale->id, 'location' => $resale->{'location_' . $lang . '_name'},
                    'home_image' => $resale->image, 'other_images' => json_decode($resale->other_images),
                    'title' => $resale->{$lang . '_name'}, 'price' => $resale->price, 'area' => $resale->area,
                    'rooms' => $resale->rooms, 'bathrooms' => $resale->bathrooms, 'favorite' => $favorite,
                    'lat' => $resale->lat, 'lng' => $resale->lng, 'zoom' => $resale->zoom,
                    'description' => $resale->{$lang . '_description'},
                ));
            }
            $type1 = "resale";
        } else if ($type == 'rental') {
            $rentals = RentalUnit:: leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
            whereIn('rental_units.unit_type_id', $unit_types)->
            where(function ($query) use ($locations, $location_id) {
                $query->whereIn('rental_units.location', $locations)->
                orwhere('rental_units.location', $location_id);
            })->
            where(function ($query) use ($min_price, $max_price) {
                $query->where('rental_units.rent', '>=', $min_price)->
                where('rental_units.rent', '<=', $max_price);
            })->
            where(function ($query) use ($min_area, $max_area) {
                $query->where('rental_units.area', '>=', $min_area)->
                where('rental_units.area', '<=', $max_area);
            })->
            where(function ($query) use ($request) {
                $query->where('rental_units.en_title', 'like', '%' . $request->keyword . '%')->
                orwhere('rental_units.ar_title', 'like', '%' . $request->keyword . '%');
            })->
            where('rental_units.availability', 'available')->
            select('rental_units.id as id', 'rental_units.en_title as en_name', 'rental_units.ar_title as ar_name'
                , 'rental_units.rent as price', 'rental_units.area as area', 'locations.en_name as location_en_name'
                , 'locations.ar_name as location_ar_name'
                , 'rental_units.unit_type_id as unit_type'
                , 'rental_units.area as area', 'rental_units.rooms as rooms', 'rental_units.bathrooms as bathrooms'
                , 'rental_units.image as image', 'rental_units.other_images as other_images',
                'rental_units.lat', 'rental_units.lng', 'rental_units.zoom',
                'rental_units.en_description', 'rental_units.ar_description')
                ->get();
            foreach ($rentals as $rental) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
                if ($num > 0)
                    $favorite = 'true';
                array_push($data, array('id' => $rental->id, 'location' => $rental->{'location_' . $lang . '_name'},
                    'home_image' => $rental->image, 'other_images' => json_decode($rental->other_images),
                    'title' => $rental->{$lang . '_name'}, 'price' => $rental->price, 'area' => $rental->area,
                    'rooms' => $rental->rooms, 'bathrooms' => $rental->bathrooms, 'favorite' => $favorite,
                    'lat' => $rental->lat, 'lng' => $rental->lng, 'zoom' => $rental->zoom,
                    'description' => $rental->{$lang . '_description'}
                ));
            }
            $type1 = "rental";
        }
        $search = $this->search_info();
        return view('website.search_result', compact('data', 'type1', 'search'));
    }

    public function sitemap()
    {
        $base_url = url('/');
        $beginning = '<?xml version="1.0" encoding="UTF-8"?>
        
<urlset xmlns="' . $base_url . '">';

        $events = '';

        foreach (Event::all() as $event) {
            $events .= '
   <url>

      <loc>' . $base_url . '/events/' . $event->id . '/' . str_slug($event->{app()->getLocale() . '_title'}) . '</loc>

      <lastmod>' . date('Y-m-d') . '</lastmod>

   </url>
';
        }

        $resales = '';

        foreach (ResaleUnit::all() as $resale) {
            $resales .= '
   <url>

      <loc>' . $base_url . '/resale/' . $resale->id . '/' . str_slug($resale->{app()->getLocale() . '_title'}) . '</loc>

      <lastmod>' . date('Y-m-d') . '</lastmod>

   </url>
';
        }

        $rentals = '';

        foreach (RentalUnit::all() as $rental) {
            $rentals .= '
   <url>

      <loc>' . $base_url . '/rental/' . $rental->id . '/' . str_slug($rental->{app()->getLocale() . '_title'}) . '</loc>

      <lastmod>' . date('Y-m-d') . '</lastmod>

   </url>
';
        }

        $new_homes = '';

        foreach (Property::all() as $new_home) {
            $new_homes .= '
   <url>

      <loc>' . $base_url . '/new_home/' . $new_home->id . '/' . str_slug($new_home->{app()->getLocale() . '_name'}) . '</loc>

      <lastmod>' . date('Y-m-d') . '</lastmod>

   </url>
';
        }

        $projects = '';

        foreach (Project::all() as $project) {
            $projects .= '
   <url>

      <loc>' . $base_url . '/projects/' . $project->id . '/' . str_slug($project->{app()->getLocale() . '_name'}) . '</loc>

      <lastmod>' . date('Y-m-d') . '</lastmod>

   </url>
';
        }

        $middle = $events . $resales . $rentals . $new_homes . $projects;

        $ending = '</urlset>';
        $txt = $beginning . $middle . $ending;
        $path = base_path('sitemap.xml');
        $file = fopen($path, 'wb');
        $is_written = fwrite($file, $txt);
        fclose($file);
        if ($is_written > 0) {
            $set = Setting::find(1);
            $set->refresh_sitemap = strtotime(date('Y-m-d'));
            $set->save();
            return response()->json([
                'status' => true,
                'date' => date('Y-m-d'),
            ]);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function dashboard()
    {
        if (auth()->user()->type == 'admin') {
            $month = date('Y-m');
            $day = date('Y-m-d');
            $salesM = ClosedDeal::where('created_at', '>', $month . '-01 00:00:00')->sum('price');
            $salesD = ClosedDeal::where('created_at', '>', $day . ' 00:00:00')->sum('price');
            $leadsD = Lead::where('created_at', '>', $day . ' 00:00:00')->count();
            $leads = Lead::count();
            $deals = Proposal::where('status', 'pending')->sum('price');
            $deals = number_format($deals);
            $agents = User::get();
            $agentData = [];
            $salesM = number_format($salesM);
            $salesD = number_format($salesD);
            foreach ($agents as $agent) {
                $main = ClosedDeal::where('agent_id', $agent->id)->sum('agent_commission');
                $sub = DealAgents::where('agent_id', $agent->id)->sum('agent_commission');
                $agentData[$agent->id] = $main + $sub;
            }
    
            $agentData = array_sort($agentData);
    
            $arr = [];
    
            foreach ($agentData as $k => $v) {
                $arr[] = $v . '||' . $k;
            }
            $arr = array_reverse($arr);
    
            if (count($arr) >= 10) {
                $i = 10;
            } else {
                $i = count($arr);
            }
    
            $arr1 = [];
            for ($x = 0; $x < $i; $x++) {
                $arr1[] = $arr[$x];
            }
    
    
            $maxArr = [];
            foreach ($agentData as $data) {
                $maxArr[] = $data;
            }
            $max = max($maxArr);
            //$not=DB::table('project_requests')->raw("select id,name,null as type,null as type_id, updated_at, '1' as projects  union select  id, null as name,type,type_id,updated_at, '0' as projects from admin_notifications where created_at >= ? orWhere status = 0 ",[date('Y-m-d H:i:s', strtotime('-1 month', time()))])->get();


            return view('admin.dashboard', [
                'title' => 'New Avenue',
                'salesM' => $salesM,
                'salesD' => $salesD,
                'leadsD' => $leadsD,
                'leads' => $leads,
                'deals' => $deals,
                'chart' => $arr1,
                'max' => $max,
            ]);
        } else {
            $month = date('Y-m');
            $day = date('Y-m-d');
            $salesM = ClosedDeal::where('created_at', '>', $month . '-01 00:00:00')->where('agent_id', auth()->id())->sum('price');
            $salesD = ClosedDeal::where('created_at', '>', $day . ' 00:00:00')->where('agent_id', auth()->id())->sum('price');
            $leadsD = Lead::where('created_at', '>', $day . ' 00:00:00')->where('agent_id', auth()->id())->count();
            $leads = Lead::where('agent_id', auth()->id())->count();
            return view('admin.agent_dashboard', ['title' => 'New Avenue','salesM' => $salesM, 'salesD' => $salesD, 'leadsD' => $leadsD, 'leads' => $leads,]);
        }
    }

    public function about_page()
    {
        $about = Setting::first();
        return view('website.about', ['about' => $about]);
    }

    public function new_homes_properties()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $projects = Project::where('type', 'personal')->where('show_website', 1)->orderBy('id', 'DESC')->paginate(6);
        $all_projects = Project::where('type', 'personal')->where('show_website', 1)->get();
        return view('website.new_homes_properties', ['projects' => $projects, 'search' => $search, 'featured' => $featured, 'title' => __('admin.new_homes'), 'all_projects' => $all_projects]);
    }

    public function resale_properties()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $resale = ResaleUnit::where('type', 'personal')->paginate(6);
        $all_projects = ResaleUnit::where('type', 'personal')->get();
        return view('website.resale_properties', ['resale' => $resale, 'search' => $search, 'featured' => $featured, 'title' => __('admin.resale'), 'all_projects' => $all_projects]);
    }

    public function rental_properties()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $rental = RentalUnit::where('type', 'personal')->paginate(6);
        $all_projects = RentalUnit::where('type', 'personal')->get();
        return view('website.rental_properties', ['rental' => $rental, 'search' => $search, 'featured' => $featured, 'title' => __('admin.rental'), 'all_projects' => $all_projects]);
    }

    public function new_homes_commercial()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $properties = Project::where('type', 'commercial')->where('show_website', 1)->orderBy('id', 'Desc')->paginate(6);
        $all_projects = Project::where('type', 'commercial')->where('show_website', 1)->get();
        return view('website.new_homes_commercial', ['projects' => $properties, 'search' => $search, 'featured' => $featured, 'title' => __('admin.new_homes'), 'all_projects' => $all_projects]);
    }

    public function resale_commercial()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $resale = ResaleUnit::where('type', 'commercial')->paginate(6);
        $all_projects = ResaleUnit::where('type', 'commercial')->get();
        return view('website.resale_commercial', ['resale' => $resale, 'search' => $search, 'all_projects' => $all_projects, 'featured' => $featured, 'title' => __('admin.resale')]);
    }

    public function rental_commercial()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $rental = RentalUnit::where('type', 'commercial')->paginate(6);
        $all_projects = RentalUnit::where('type', 'commercial')->get();
        return view('website.rental_commercial', ['rental' => $rental, 'search' => $search, 'featured' => $featured, 'title' => __('admin.rental'), 'all_projects' => $all_projects]);
    }

    public function location()
    {
        return view('website.locations');
    }

    public function news()
    {
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $events = Event::orderBy('id', 'asc')->paginate(5);
        return view('website.news', compact('events', 'search', 'featured'));
    }

    public function single_news($id)
    {
        $array = explode('-', $id);
        $id = end($array);
        $search = $this->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        $single_news = Event::find($id);
        return view('website.single_news', compact('single_news', 'search', 'featured'));
    }

    public function contact()
    {
        $contact = Setting::first();
        $phones = HubPhone::all();
        $socials = HubSocial::all();
        return view('website.contact', compact('contact', 'phones', 'socials'));
    }



    public static function get_roles()
    {
        if (auth()->user()->type != 'admin') {
            $role = Role::find(auth()->user()->role_id);
            return json_decode($role->roles);
        } else {
            return true;
        }

    }

    public function seo(Request $request)
    {
        $rules = [
            'seo' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            session()->flash('error', trans('admin.error'));
            return back();
        } else {
            $setting = Setting::first();
            $setting->seo = $request->seo;
            $setting->save();
            session()->flash('success', trans('admin.success'));
            return back();
        }
    }

    public function send_mail()
    {
        return view('admin.send_mail', ['title' => __('admin.send_mail')]);
    }

    public function mail_post(Request $request)
    {
        $txt = $request->message;
        $path = base_path('resources/views/mail.blade.php');
        $file = fopen($path, 'wb');
        fwrite($file, $txt);
        fclose($file);
        Config::set('mail.username', auth()->user()->email);
        Config::set('mail.password', decrypt(auth()->user()->email_password));
        $mail = auth()->user()->email;
        $name = auth()->user()->name;
        if (Setting::first()->mail_provider == 'gmail') {
            Config::set('mail.port', 587);
            Config::set('mail.host', 'smtp.gmail.com');
        } else if (Setting::first()->mail_provider == 'cpanel') {
            Config::set('mail.port', 26);
            Config::set('mail.host', 'mail.propertz.net');
            // Config::set('mail.encryption', 'ssl');
        }
        Mail::send('mail', [], function ($message) use ($request, $mail, $name) {
            $message->from($mail, $name)->to($request->lead_id)->subject($request->subject);
        });

        session()->flash('success', trans('admin.sent'));

        return back();
    }

    public function inbox()
    {
        if (auth()->user()->email_password != '') {
            try {
                $email = auth()->user()->email;
                $password = decrypt(auth()->user()->email_password);
                $data = [];
                $type = Setting::first()->mail_provider;
                if ($type == 'cpanel') {
                    $mailbox = imap_open("{mail.propertz.net:110/pop3/notls}INBOX", $email, $password);
                } else if ($type == 'gmail') {
                    $mailbox = imap_open("{imap.gmail.com:993/ssl}INBOX", $email, $password);
                }

                $messages = imap_search($mailbox, 'ALL');
                if ($messages) {
                    $arr = array_reverse($messages);

                    $data = [];
                    if (count($arr) < 10) {
                        $x = count($arr);
                    } else {
                        $x = 10;
                    }
                    for ($i = 0; $i < $x; $i++) {
                        $data[] = imap_fetch_overview($mailbox, $arr[$i], 0)[0];
                    }
                }
                imap_close($mailbox);
            } catch (\ErrorException $e) {
                return redirect(adminPath() . '/');
            }
        } else {
            return redirect(adminPath() . '/');
        }
        return view('admin.inbox', ['title' => __('admin.inbox'), 'messages' => $data]);
    }

    public static function lead_inbox($mail)
    {
        if (auth()->user()->email_password != '') {
            try {
                $email = auth()->user()->email;
                $password = decrypt(auth()->user()->email_password);
                $data = [];
                $type = Setting::first()->mail_provider;
                if ($type == 'cpanel') {
                    $mailbox = imap_open("{mail.propertz.net:110/pop3/notls}", $email, $password);
                } else if ($type == 'gmail') {
                    $mailbox = imap_open("{imap.gmail.com:993/ssl}", $email, $password);
                }
                $messages = imap_search($mailbox, 'FROM "' . $mail . '"');
                if ($messages) {
                    $arr = array_reverse($messages);

                    $data = [];
                    if (count($arr) < 10) {
                        $x = count($arr);
                    } else {
                        $x = 10;
                    }
                    for ($i = 0; $i < $x; $i++) {
                        $data[] = imap_fetch_overview($mailbox, $arr[$i], 0)[0];
                    }
                }
                imap_close($mailbox);
            } catch (\ErrorException $e) {
                return redirect(adminPath() . '/');
            }
        } else {
            $data = [];
        }
        return $data;
    }

    public function get_mail($id)
    {
        if (auth()->user()->email_password != '') {
            try {
                $yourEmail = auth()->user()->email;
                $yourEmailPassword = decrypt(auth()->user()->email_password);

                $type = Setting::first()->mail_provider;
                if ($type == 'cpanel') {
                    $mailbox = imap_open("{mail.propertz.net:110/pop3/notls}INBOX", $yourEmail, $yourEmailPassword);
                } else if ($type == 'gmail') {
                    $mailbox = imap_open("{imap.gmail.com:993/ssl}INBOX", $yourEmail, $yourEmailPassword);
                }

                $message = imap_qprint(imap_fetchbody($mailbox, $id, 2));
                imap_close($mailbox);
            } catch (\ErrorException $e) {
                return redirect(adminPath() . '/');
            }
        } else {
            $message = [];
        }
        return view('admin.mail', ['data' => $message]);
    }

    public static function count_mails()
    {
        if (auth()->user()->email_password != '' and decrypt(auth()->user()->email_password) != null) {
            try {
                $yourEmail = auth()->user()->email;
                $yourEmailPassword = decrypt(auth()->user()->email_password);
                $type = Setting::first()->mail_provider;
                if ($type == 'cpanel') {
                    $mailbox = imap_open("{mail.propertz.net:110/pop3/notls}INBOX", $yourEmail, $yourEmailPassword);
                } else if ($type == 'gmail') {
                    $mailbox = imap_open("{imap.gmail.com:993/ssl}INBOX", $yourEmail, $yourEmailPassword);
                }
                $messages = imap_search($mailbox, 'UNSEEN');
                imap_close($mailbox);
            } catch (\ErrorException $e) {
                return 0;
            }
            if (!$messages) {
                $count = 0;
            } else {
                $count = count($messages);
            }
        } else {
            $count = 0;
        }
        return $count;
    }

    public function send_unit(Request $request)
    {
        if ($request->type == 'resale') {
            Config::set('mail.username', auth()->user()->email);
            Config::set('mail.password', decrypt(auth()->user()->email_password));
            $mail = auth()->user()->email;
            $name = auth()->user()->name;
            $unit = ResaleUnit::find($request->unit_id);
            Mail::send('admin.send_resale', ['unit' => $unit, 'lang' => $request->lang], function ($message) use ($request, $mail, $name, $unit) {
                $message->from($mail, $name)->to($request->lead)->subject($unit->{$request->lang . '_title'});
            });
        }
        session()->flash('success', trans('admin.sent'));

        return back();
    }

    public function add_property(Request $request)
    {

        if ($request->type == 'personal') {
            $rules = [
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
                'lng' => 'required',
                'lat' => 'required',
                'zoom' => 'required',
                'image' => 'required',
                'due_now' => 'required|numeric',
                'payment_method' => 'required',
                'view' => 'required',
            ];
        } else {
            $rules = [
                'type' => 'required',
                'total' => 'required',
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
                'lng' => 'required',
                'lat' => 'required',
                'zoom' => 'required',
                'image' => 'required|image',
                'payment_method' => 'required',
                'view' => 'required',
                'due_now' => 'required|numeric',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'type' => trans('admin.type'),
            'total' => trans('admin.total'),
            'finishing' => trans('admin.finishing'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'ar_title' => trans('admin.ar_title'),
            'en_title' => trans('admin.en_title'),
            'ar_address' => trans('admin.ar_address'),
            'en_address' => trans('admin.en_address'),
            'phone' => trans('admin.phone'),
            'area' => trans('admin.area'),
            'price' => trans('admin.price'),
            'rooms' => trans('admin.rooms'),
            'bathrooms' => trans('admin.bathrooms'),
            'lng' => trans('admin.lng'),
            'lat' => trans('admin.lat'),
            'zoom' => trans('admin.zoom'),
            'image' => trans('admin.image'),
            'due_now' => trans('admin.due_now'),
            'payment_method' => trans('admin.payment_method'),
            'view' => trans('admin.view'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $unit = new ResaleUnit;
            $unit->type = $request->type;
            $unit->unit_type_id = $request->unit_type_id;
            $unit->project_id = $request->project_id;
            $unit->lead_id = $request->lead_id;
            $unit->original_price = $request->original_price;
            $unit->payed = $request->payed;
            $unit->rest = $request->rest;
            $unit->total = $request->total;
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
            $unit->featured = $request->featured;
            $unit->meta_keywords = $request->meta_keywords;
            $unit->meta_description = $request->meta_description;
            $unit->priority = 0;
            if ($request->has('other_phones')) {
                $unit->other_phones = json_encode($request->other_phones);
            } else {
                $unit->other_phones = '[]';
            }
            $unit->area = $request->area;
            $unit->price = $request->price;
            $unit->rooms = $request->rooms;
            $unit->bathrooms = $request->bathrooms;
            $unit->floors = $request->floors;
            $unit->lng = $request->lng;
            $unit->lat = $request->lat;
            $unit->zoom = $request->zoom;

            $set = Setting::first();
            if ($request->hasFile('image')) {
                $unit->image = upload($request->image, 'resale_unit');
                $watermark = Image::make('uploads/' . $set->watermark)->resize(50, 50);
                $image = Image::make('uploads/' . $unit->image);
                $image->insert($watermark, 'bottom-right', 10, 10);
                $image->save("uploads/resale_unit/watermarked_resale" . rand(0, 99999999999) . ".jpg");
                $unit->watermarked_image = $image->dirname . '/' . $image->basename;
            }
            $unit->payment_method = $request->payment_method;
            $unit->view = $request->view;
            $unit->availability = 'available';
            $unit->user_id = $request->user_id;
            $unit->save();

            $old_data = json_encode($unit);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $unit->ar_title,
                __('admin.created', [], 'en') . ' ' . $unit->en_title,
                'resale_units',
                $unit->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            if ($request->has('other_images')) {
                foreach ($request->other_images as $img) {
                    $other_image_model = new ResalImage;
                    $other_image = upload($img, 'resale_unit');
                    $watermark = Image::make('uploads/' . $set->watermark)->resize(50, 50);
                    $image = Image::make('uploads/' . $other_image);
                    $image->insert($watermark, 'bottom-right', 10, 10);
                    $image->save("uploads/resale_unit/other_watermarked_resale" . rand(0, 99999999999) . ".jpg");
                    $other_watermarked_images = $image->dirname . '/' . $image->basename;
                    $other_image_model->unit_id = $unit->id;
                    $other_image_model->image = $other_image;
                    $other_image_model->watermarked_image = $other_watermarked_images;
                    $other_image_model->save();
                }
            }

            session()->flash('success', trans('admin.created'));
            return redirect('/');
        }
    }

    public function test5()

    {
        $projects = Project::all();
        foreach ($projects as $row) {
            $row->featured = 0;
            $row->show_website = 0;
            $row->mobile = 0;
            $row->save();
        }
    }

    public function guide()
    {
        $agents = User::select('id', 'name')->get()->toArray();
        $location = Location::select('id', 'ar_name', 'en_name')->get()->toArray();
        $projects = Project::select('id', 'en_name', 'ar_name')->get()->toArray();
        $unit_type = UnitType::select('id', 'en_name', 'ar_name')->get()->toArray();
        $lead_source = LeadSource::select('id', 'name')->get()->toArray();
        $lead_types = array('Buyer', 'Seller');
        $types = array('Residential', 'Commercial', 'Land');
        $count = count($agents);
        if ($count < count($location))
            $count = count($location);
        if ($count < count($projects))
            $count = count($projects);
        if ($count < count($unit_type))
            $count = count($unit_type);
        if ($count < count($lead_source))
            $count = count($lead_source);
        if ($count < count($types))
            $count = count($types);
        if ($count < count($lead_types))
            $count = count($lead_types);

        Excel::create('guide', function ($excel) use ($agents, $location, $projects, $unit_type, $lead_source, $count,$types, $lead_types) {
            $excel->sheet('campaign', function ($sheet) use ($agents, $location, $projects, $unit_type, $lead_source, $count,$types, $lead_types) {
                $sheet->loadView('admin.xlsguide', ['agents' => $agents, 'location' => $location,
                    'projects' => $projects, 'unit_type' => $unit_type, 'lead_source' => $lead_source, 'types' => $types, 'lead_types' => $lead_types, 'count' => $count]);
            });
        })->export('xls');
    }

    public function xls()
    {
        Excel::create('Request', function ($excel) {
            $excel->sheet('campaign', function ($sheet) {
                $sheet->loadView('admin.xls_request');
            });
        })->export('xls');
    }

   public function xls1(Request $request)
    {
        $path = $request->file('xls')->getRealPath();
        $count = 0;
        Excel::load($path, function ($reader) use (&$count) {
            $array = $reader->toArray();
            // dd($array);
            foreach ($array as $item) {
                if (isset($item['contact'])) {
                    $lead = Lead::where('phone', $item['contact'])->first();
                    if ($lead == NULL) {
                        $first_name = 'first_name';
                        $last_name = ' .';
                        if (isset(explode(' ', $item['lead_name'])[0]))
                            $first_name = explode(' ', $item['lead_name'])[0];
                        if (isset(explode(' ', $item['lead_name'])[1]))
                            $last_name = explode(' ', $item['lead_name'])[1].' ';
                        if (isset(explode(' ', $item['lead_name'])[2]))
                            $last_name .= explode(' ', $item['lead_name'])[2].' ';
                        if (isset(explode(' ', $item['lead_name'])[3]))
                            $last_name .= explode(' ', $item['lead_name'])[3].' ';
                        if (isset(explode(' ', $item['lead_name'])[4]))
                            $last_name .= explode(' ', $item['lead_name'])[4];
                        $lead = new Lead();
                        $lead->first_name = $first_name;
                        $lead->last_name = $last_name;
                        $lead->email = ($item['lead_email'] ? $item['lead_email'] : null);
                        $lead->reference = @$item['reference'];

                        $prefix = '';
                        if (str_split($item['contact'])[0] == '1') {
                            $prefix = '0';
                        } else if (str_split($item['contact'])[0] != '0' and str_split($item['contact'])[0] != '1' and str_split($item['contact'])[0] != '+') {
                            $prefix = '00';
                        }

                        $lead->phone = $prefix . @$item['contact'];
                        $lead->lead_source_id = $item['lead_source_id'] ? $item['lead_source_id'] : 0;
                        $lead->campain_id = 0;
                        if (isset($item['date'])){
                            $checkDate = (bool) strtotime('September.13.2015');
                            if ($checkDate) {
                                $date = strtotime($item['date']);
                                $lead->created_at = date('Y-m-d H:i:s', $date);
                            } else {
                                $lead->created_at = date('Y-m-d H:i:s');
                            }

                        }
                        $lead->agent_id = @$item['agent_id'] ? @$item['agent_id'] : 0;
                        $lead->user_id = Auth::user()->id;
                        if (isset($item['title'])) {
                            $lead->title_id = @$item['title'];
                        }
                        $lead->save();

                        if ($item['notes'] != '' and $item['notes']) {
                            $note = new \App\LeadNote;
                            $note->lead_id = $lead->id;
                            $note->note = $item['notes'];
                            $note->user_id = auth()->id();
                            $note->save();
                        }
                    }
                    //if (strtolower($item['request_type']) == 'buy') {

                        $unit1 = @UnitType::find($item['unit_type_id']);

                        $location = @Location::find($item['location_id']);

                        $req = new Model;
                        $req->lead_id = $lead->id;
                        if ($location) {
                            $req->location = @$location->id;
                        }
                        if ($unit1) {
                            $req->unit_type_id = @$unit1->id;
                        }

                        if (isset($item['date'])) {
                            $req->date = @$item['date'];
                        } else {
                            $req->date = date('Y');
                        }

                        if (@$item['request_type'] == 1) {
                            $type = 'personal';
                        } else if (@$item['request_type'] == 2) {
                            $type = 'commercial';
                        } else if (@$item['request_type'] == 3) {
                            $type = 'land';
                        } else {
                            $type = 'personal';
                        }

                        $req->unit_type = $type;

                        $req->notes = @$item['description'];
                        $req->request_type = 'resale';

                        $req->user_id = Auth::user()->id;
                        $req->save();
                        $count++;


                    //}
                }
            }
        });
        return $count . ' has been added';
    }

    public function form($slug)
    {
        $id = explode('-', $slug);
        $id = end($id);
        $form = Form::find($id);
        return view('form', ['form' => $form,
            'title' => $form->{app()->getLocale() . '_title'},
            'id' => $id,
        ]);
    }

    public function contract($url)
    {
        $contract = Contract::where('url', $url);
        if ($contract->count() == 1) {
            $contract = $contract->first();
            $title = $contract->title;
            return view('contract', compact('contract', 'title'));
        } else {
            return 404;
        }
    }

    public function confirmContract(Request $request)
    {
        $img = base64_decode($request->img);
        $filename = md5(date('dmYhisA'));
        $filename = rand(0, 9999999999) . $filename;

        $fullname = 'uploads/' . $filename . '.png';
        file_put_contents($fullname, $img);
        $contract = Contract::find($request->id);
        $contract->signature = $fullname;
        $contract->save();

        return response()->json([
            'status' => 1,
            'img' => $request->files,
        ]);
    }

    public function contractForm(Request $request)
    {
        $rules = [
            'id' => 'required',
            'lead_name' => 'required|max:191',
            'signature_name' => 'required|max:191',
            'docs' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'id' => trans('admin.id'),
            'lead_name' => trans('admin.lead_name'),
            'signature_name' => trans('admin.signature_name'),
            'docs' => trans('admin.files'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $contract = Contract::find($request->id);
            $contract->lead_name = $request->lead_name;
            $contract->signature_name = $request->signature_name;
            $contract->status = 1;

            $contract->save();
            foreach ($request->docs as $doc) {
                $file = new LeadDocument;
                $file->lead_id = $contract->lead_id;
                $file->title = $contract->title;
                $file->file = upload($doc, 'contract');
                $file->user_id = 0;
                $file->contract_id = $contract->id;
                $file->save();
            }

            session()->flash('success', trans('admin.created'));

            return redirect('/');
        }
    }
    
    public static function getTeam($id)
    {
        $groups = Group::where('parent_id', $id)->get();
        foreach ($groups as $group) {
            array_push(self::$allTeam, $group->id);
            self::getTeam($group->id);
        }
    }

    public static function myTeam($id)
    {
        self::getTeam($id);
        $users = self::$allTeam;
        return array_unique($users);
    }

    public function notificationStatus(Request $request)
    {
        $not = AdminNotification::find($request->id);
        $not->status = 1;
        $not->save();

        return response([
            'status' => 1
        ]);
    }
    
    public function unread(Request $request){

        $not = AdminNotification::find($request->id);
        $status = $not->status;
        $msg = '';
        $not->status = $not->status?0:1;
        $not->save();
        $status?$msg ='read':$msg = 'unread';
        return response([
            'status' => $msg
        ]);
    }
    
    public function get_sub_cats(Request $request)
    {
        $subs = OutSubCat::where('out_cat_id', $request->cat)->get();
        return view('admin.finances.get_subs', compact('subs'));
    }
}

