<?php

namespace App\Http\Controllers;

use App\Facility;
use App\Favorite;
use App\Location;
use App\Phase;
use App\Phase_Facilities;
use App\Project;
use App\Property;
use App\Property_images;
use App\RentalUnit;
use App\ResaleUnit;
use App\UnitType;
use Illuminate\Http\Request;

class ApiSearch extends Controller
{

    public function search(Request $request)
    {
        $unit_type=[];
        if ($request['lang'] == 'ar' or $request['lang'] == 'en') {
            $lang = $request['lang'];
        }

        $min_price = Project::min('meter_price');
        if ($min_price > RentalUnit::min('rent')) {
            $min_price = RentalUnit::min('rent');
        }

        if ($min_price > ResaleUnit::min('price')) {
            $min_price = ResaleUnit::min('price');
        }

        $max_price = Project::max('meter_price');
        if ($max_price < RentalUnit::max('rent')) {
            $max_price = RentalUnit::max('rent');
        }

        if ($max_price < ResaleUnit::max('price')) {
            $max_price = ResaleUnit::max('price');
        }

        $min_area = Project::min('area');
        if ($min_area > RentalUnit::min('area')) {
            $min_area = RentalUnit::min('area');
        }

        if ($min_area > ResaleUnit::min('area')) {
            $min_area = ResaleUnit::min('area');
        }

        $max_area = Project::max('area');
        if ($max_area < RentalUnit::max('area')) {
            $max_area = RentalUnit::max('area');
        }

        if ($max_area < ResaleUnit::max('area')) {
            $max_area = ResaleUnit::max('area');
        }

        $location   = Location::all();
        $facilities = Facility::get();
        if ($request['lang'] == 'en') {
            $unit_type  = @UnitType::select('id', 'en_name as name')->get()->toArray();
            $facilities = @Facility::select('id', 'en_name as name')->get()->toArray();
            $location   = @Location::select('id', 'en_name as name')->get()->toArray();
        }
        if ($request['lang'] == 'ar') {
            $unit_type  = @UnitType::select('id', 'ar_name as name')->get()->toArray();
            $facilities = @Facility::select('id', 'ar_name as name')->get()->toArray();
            $location   = @Location::select('id', 'ar_name as name')->get()->toArray();
        }
        if(!$min_price){$min_price =0;}
        if(!$max_price){$max_price =0;}
        if(!$min_area){$min_area =0;}
        if(!$max_area){$max_area =0;}
        $data['region']     = $location;
        $data['unit_type']  = $unit_type;
        $data['facilities'] = $facilities;
        $data['data'] = ['min_price' => $min_price, 'max_price' => $max_price,
            'min_area'=> $min_area, 'max_area'   => $max_area];
        return $data;
    }

    public function search_result(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        $request1 = (object) $request1;
        //        $keyword=$request1->keyword;
        $location_id = $request1->location_id;
        $min_price   = (double) $request1->min_price;
        $max_price   = (double) $request1->max_price;
        $lead_id     = $request1->lead_id;
        $lang        = $request1->lang;
        $min_area    = $request1->min_area;
        $max_area    = $request1->max_area;
        $locations   = HomeController::getChildren($location_id);
        $request1->facility;
        $type = $request1->type;
        $data = [];
        if (count($request1->facility) > 0) {
            $facilities = Phase_Facilities::whereIn('facility_id', $request1->facility)->select('phase_id')->get()->toArray();
        }
        else {
            $facilities = Phase_Facilities::select('phase_id')->get()->toArray();
        }
        if (count($request1->unit_type) > 0) {
            $unit_types = $request1->unit_type;
        }
        else {
            $unit_types = UnitType::select('id')->get()->toArray();
        }
        if ($type == 'project') {
            $properties = Project::leftJoin('phases', 'phases.project_id', '=', 'projects.id')->
            leftJoin('locations', 'locations.id', '=', 'projects.location_id')->
            where(function ($query) use ($locations, $location_id) {
                $query->whereIn('projects.location_id', $locations)->
                orwhere('projects.location_id', $location_id);
            })->
            where(function ($query) use($min_price,$max_price){
                $query->where('projects.meter_price','>=',$min_price)->
                where('projects.meter_price','<=',$max_price);
            })->
            where(function ($query) use($min_area,$max_area){
                $query->where('projects.area','>=',$min_area)->
                where('projects.area','<=',$max_area);
            })->
            whereIn('phases.id', $facilities)->
            where(function ($query) use($request1){
                $query->where('projects.en_name', 'like', '%' . $request1->keyword . '%')->
                orwhere('projects.ar_name', 'like', '%' . $request1->keyword . '%');
            })->where('mobile','1')->
            select('projects.id as id')->
            get();
            $ids=[];
            foreach ($properties as $row)
                array_push($ids,$row->id);
            $ids= array_unique($ids);
            $projects=Project::whereIn('id',$ids)->get();
            foreach ($projects as $row) {
                $favorite = 'false';
                $num = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                if ($num > 0)
                    $favorite = 'true';
                $location = Location::find($row->location_id)->{$lang . '_name'};
                $delivery = Phase::where('project_id', '=', $row->id)->max('delivery_date');
                array_push($data, array("id" => $row->id, "name" => $row->{$lang . '_name'}, 'price' => $row->meter_price,
                    'logo' => $row->logo, 'image' => $row->cover, 'payment' => $row->down_payment,
                    'lat' => $row->lat, 'lng' => $row->lng, 'zoom' => $row->zoom,
                    'installment_year' => $row->installment_year, 'delivery_date' => $delivery,
                    'location' => $location, 'favorite' => $favorite));
            }

        }
        else if ($type == 'resale') {
            $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
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
            where(function ($query) use ($request1) {
                $query->where('resale_units.en_title', 'like', '%' . $request1->keyword . '%')->
                orwhere('resale_units.ar_title', 'like', '%' . $request1->keyword . '%');
            })->
            where('resale_units.availability', 'available')->
            select('resale_units.id as id', 'resale_units.en_title as en_name', 'resale_units.ar_title as ar_name'
                , 'resale_units.price as price', 'resale_units.area as area', 'locations.en_name as location_en_name'
                , 'locations.ar_name as location_ar_name'
                , 'resale_units.unit_type_id as unit_type'
                , 'resale_units.area as area', 'resale_units.rooms as rooms', 'resale_units.bathrooms as bathrooms'
                , 'resale_units.image as image', 'resale_units.other_images as other_images')
                ->get();
            foreach ($resales as $resale) {
                $favorite = 'false';
                $num      = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
                if ($num > 0) {
                    $favorite = 'true';
                }

                array_push($data, array(
                    'id'           => $resale->id,
                    'location'     => $resale->{'location_' . $lang . '_name'},
                    'home_image'   => $resale->image,
                    'other_images' => json_decode($resale->other_images),
                    'title'        => $resale->{$lang . '_name'},
                    'price'        => $resale->price,
                    'area'         => $resale->area,
                    'rooms'        => $resale->rooms,
                    'bathrooms'    => $resale->bathrooms,
                    'favorite'     => $favorite,
                ));
            }
        }
        else if ($type == 'rental') {
            $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
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
            where(function ($query) use ($request1) {
                $query->where('rental_units.en_title', 'like', '%' . $request1->keyword . '%')->
                orwhere('rental_units.ar_title', 'like', '%' . $request1->keyword . '%');
            })->
            where('rental_units.availability', 'available')->
            select('rental_units.id as id', 'rental_units.en_title as en_name', 'rental_units.ar_title as ar_name'
                , 'rental_units.rent as price', 'rental_units.area as area', 'locations.en_name as location_en_name'
                , 'locations.ar_name as location_ar_name'
                , 'rental_units.unit_type_id as unit_type'
                , 'rental_units.area as area', 'rental_units.rooms as rooms', 'rental_units.bathrooms as bathrooms'
                , 'rental_units.image as image', 'rental_units.other_images as other_images')
                ->get();
            foreach ($rentals as $rental) {
                $favorite = 'false';
                $num      = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
                if ($num > 0) {
                    $favorite = 'true';
                }

                array_push($data, array('id' => $rental->id, 'location'             => $rental->{'location_' . $lang . '_name'},
                    'home_image'                 => $rental->image, 'other_images'      => json_decode($rental->other_images),
                    'title'                      => $rental->{$lang . '_name'}, 'price' => $rental->price, 'area'         => $rental->area,
                    'rooms'                      => $rental->rooms, 'bathrooms'         => $rental->bathrooms, 'favorite' => $favorite,
                ));
            }
        }
        return $data;

    }

    public function get_region(Request $request)
    {
        $lang      = $request['lang'];
        $data      = [];
        $locations = Location::where('parent_id', 0)->get();
        foreach ($locations as $location) {
            array_push($data, array('id' => $location->id, 'name' => $location->{$lang . '_name'}));
        }
        return $data;

    }

    public function region_filter(Request $request)
    {
        $vacation    = 0;
        $location_id = $request['location_id'];
        $type        = $request['type'];
        $vacation    = $request['vacation'];
        $locations   = HomeController::getChildren($request['location_id']);
        $lang        = $request['lang'];
        $data        = [];
        $lead_id     = $request['lead_id'];
        if ($lang == 'ar' or $lang == 'en') {
            if ($type == 'project') {
                if ($vacation == 0) {
                    $projects = Project::where(function ($query) use ($locations, $location_id) {
                        $query->whereIn('location_id', $locations)->
                        orwhere('location_id', $location_id);
                    })->get();
                } else {
                    $projects = Project::where(function ($query) use ($locations, $location_id) {
                        $query->whereIn('location_id', $locations)->
                        orwhere('location_id', $location_id);
                    })->where('vacation',1)->get();
                }
                foreach ($projects as $row) {
                    $favorite = 'false';
                    $num      = Favorite::where('unit_id', '=', $row->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                    if ($num > 0) {
                        $favorite = 'true';
                    }

                    $location = Location::find($row->location_id)->{$lang . '_name'};
                    $phase    = Phase::where('project_id', '=', $row->id)->get();
                 
                    $delivery = Phase::where('project_id', '=', $row->id)->max('delivery_date');
                    array_push($data, array(
                        'id'               => $row->id,
                        'name'             => $row->{$lang . '_name'},
                        'price'            => $row->meter_price,
                        'logo'             => $row->logo,
                        'image'            => $row->cover,
                        'payment'          => $row->down_payment,
                        'lat'              => $row->lat,
                        'lng'              => $row->lng,
                        'zoom'             => $row->zoom,
                        'installment_year' => $row->installment_year,
                        'delivery_date'    => $delivery,
                        'location'         => $location,
                        'favorite'         => $favorite,
                    ));
                }
            } elseif ($type == "resale") {
                $resales = ResaleUnit::leftJoin('locations', 'locations.id', '=', 'resale_units.location')->
                where('availability', 'available')->where(function ($query) use ($locations, $location_id) {
                    $query->whereIn('location', $locations)->
                    orwhere('location', $location_id);
                })->
                select('resale_units.id', 'resale_units.image', 'resale_units.other_images', 'resale_units.en_title',
                    'resale_units.ar_title', 'resale_units.price', 'resale_units.rooms', 'resale_units.area',
                    'resale_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
                foreach ($resales as $resale) {
                    $favorite = 'false';
                    $num      = Favorite::where('unit_id', '=', $resale->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'resale')->count();
                    if ($num > 0) {
                        $favorite = 'true';
                    }

                    array_push($data, array(
                        'id'           => $resale->id,
                        'location'     => $resale->{$lang . '_name'},
                        'home_image'   => $resale->image,
                        'other_images' => json_decode($resale->other_images),
                        'title'        => $resale->{$lang . '_title'},
                        'price'        => $resale->price,
                        'area'         => $resale->area,
                        'rooms'        => $resale->rooms,
                        'bathrooms'    => $resale->bathrooms,
                        'favorite'     => $favorite,
                    ));
                }
            } elseif ($type == "rental") {
                $rentals = RentalUnit::leftJoin('locations', 'locations.id', '=', 'rental_units.location')->
                where('availability', 'available')->where(function ($query) use ($locations, $location_id) {
                    $query->whereIn('location', $locations)->
                    orwhere('location', $location_id);
                })->
                select('rental_units.id', 'rental_units.image', 'rental_units.other_images', 'rental_units.en_title',
                    'rental_units.ar_title', 'rental_units.rent', 'rental_units.rooms', 'rental_units.area',
                    'rental_units.bathrooms', 'locations.ar_name', 'locations.en_name')->get();
                foreach ($rentals as $rental) {
                    $favorite = 'false';
                    $num      = Favorite::where('unit_id', '=', $rental->id)->where('lead_id', '=', $lead_id)->where('type', '=', 'project')->count();
                    if ($num > 0) {
                        $favorite = 'true';
                    }

                    array_push($data, array(
                        'id'           => $rental->id,
                        'location'     => $rental->{$lang . '_name'},
                        'home_image'   => $rental->image,
                        'other_images' => json_decode($rental->other_images),
                        'title'        => $rental->{$lang . '_title'},
                        'rent'         => $rental->rent, 'area' => $rental->area,
                        'rooms'        => $rental->rooms,
                        'bathrooms'    => $rental->bathrooms,
                        'favorite'     => $favorite,
                    ));
                }
            }
            if (Location::find($location_id)->parent_id != 0) {
                return ['projects' => $data];
            } else {
                $location_ids = HomeController::getChildren($location_id);
                $locations    = Location::whereIn('id', $location_ids)->select('id', $lang . '_name as name')->get();
                return ['projects' => $data, 'locations' => $locations];
            }
        }
    }

}
