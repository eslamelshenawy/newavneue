<?php

namespace App\Http\Controllers;

use App\Call;
use App\City;
use App\ClosedDeal;
use App\Contact;
use App\Developer;
use App\Lead;
use App\MainSlider;
use App\Meeting;
use App\Phase;
use App\Project;
use App\Property;
use App\Proposal;
use App\RentalUnit;
use App\Request as LeadReq;
use App\ResaleUnit;
use App\UnitType;
use App\User;
use App\Website;
use App\Land;
use App\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class AjaxController extends Controller
{
    public function get_cities(Request $request)
    {
        $id = $request->id;
        $cities = City::where('country_id', $id)->get();
        return view('admin.leads.cities', ['cities' => $cities]);
    }

    public function get_contacts(Request $request)
    {
        $id = $request->id;
        $contacts = Contact::where('lead_id', $id)->get();
        return view('admin.leads.contacts', ['contacts' => $contacts]);
    }

    public function get_calls_contacts(Request $request)
    {
        $id = $request->id;
        $contacts = Contact::where('lead_id', $id)->get();
        $lead = Lead::find($id);
        return view('admin.calls.contacts', ['contacts' => $contacts, 'lead' => $lead]);
    }

    public function get_calls(Request $request)
    {
        $id = $request->id;
        $calls = Call::where('lead_id', $id)->with('call_status')->latest()->get();
        return view('admin.calls.get_calls', ['calls' => $calls]);
    }

    public function get_meetings(Request $request)
    {
        $id = $request->id;
        $meetings = Meeting::where('lead_id', $id)->with('meeting_status')->latest()->get();
        return view('admin.meetings.get_meetings', ['meetings' => $meetings]);
    }

    public function get_requests(Request $request)
    {
        $id = $request->id;
        $requests = LeadReq::where('lead_id', $id)->latest()->get();
        return view('admin.leads.get_requests', ['reqs' => $requests]);
    }

    public function get_unit_types(Request $request)
    {
        $usage = $request->usage;
        $types = UnitType::where('usage', $usage)->get();
        return view('admin.rental.unit_types', ['types' => $types]);
    }

    public function get_phones(Request $request)
    {
//        dd($request->all());
        $id = $request->contact_id;
        $lead = $request->lead;
        if ($request->contact_id != 0) {
            $contact = Contact::find($id);
            return view('admin.calls.get_phones', ['contact' => $contact, 'type' => 'contact']);
        } else {
            $contact = Lead::find($lead);
            return view('admin.calls.get_phones', ['lead' => $contact, 'type' => 'lead']);
        }
    }

    public function get_units(Request $request)
    {
        dd($request->all());
        $personal_commercial = $request->personal_commercial;
        $type = $request->type;
        if ($request->has('unit_id')) {
            $unit_id = $request->unit_id;
        } else {
            $unit_id = 0;
        }
        if ($type == 'new_home') {
            $unit = Property::select(app()->getLocale() . '_name as title', 'id')->
            where('availability', 'available')->
            where('type', $personal_commercial)->
            get();
            return view('admin.proposals.get_units', ['units' => $unit, 'unit_id' => $unit_id]);
        } elseif ($type == 'resale') {
            $unit = ResaleUnit::select(app()->getLocale() . '_title as title', 'id')->
            where('availability', 'available')->
            where('type', $personal_commercial)->
            get();
            return view('admin.proposals.get_units', ['units' => $unit, 'unit_id' => $unit_id]);
        } elseif ($type == 'rental') {
            $unit = RentalUnit::select(app()->getLocale() . '_title as title', 'id')->
            where('availability', 'available')->
            where('type', $personal_commercial)->
            get();
            return view('admin.proposals.get_units', ['units' => $unit, 'unit_id' => $unit_id]);
        }
    }

    public function get_proposal(Request $request)
    {
        $id = $request->proposal;
        $proposal = Proposal::find($id);
        $buyer = $proposal->lead_id;
        $price = $proposal->price;
        $lead = @Lead::find($buyer);
        $agent = @\App\User::find($lead->agent_id);
        $agent_id = $agent->id;
        $agent_name = $agent->name;
        $agent_commission = $agent->commission;
        $commission = 0;
        $broker = 0;
        if ($proposal->unit_type == 'new_home') {
            $unit = Property::find($proposal->unit_id);
            $phase = Phase::find($unit->phase_id);
            $project = Project::find($phase->project_id);
            $commission = $project->commission;
            $broker = 0;
            $seller = 0;
        } elseif ($proposal->unit_type == 'rental') {
            $unit = RentalUnit::find($proposal->unit_id);
            $seller = $unit->lead_id;
            $broker = $unit->broker_id;
        } elseif ($proposal->unit_type == 'resale') {
            $unit = ResaleUnit::find($proposal->unit_id);
            $seller = $unit->lead_id;
            $broker = $unit->broker_id;
        } elseif ($proposal->unit_type == 'land') {
            $unit = Land::find($proposal->unit_id);
            $seller = $unit->lead_id;
            $broker = $unit->broker_id;
        }
        return response()->json([
            'id' => $id,
            'type' => $proposal->unit_type,
            'price' => $price,
            'commission' => $commission,
            'seller' => $seller,
            'buyer' => $buyer,
            'agent_id' => $agent_id,
            'agent_name' => $agent_name,
            'agent_commission' => $agent_commission,
            'broker' => $broker,
        ]);

    }

    public function get_proposal_html(Request $request)
    {
        $id = $request->proposal;
        $proposal = Proposal::find($id);
        return view('admin.deals.units', ['proposal' => $proposal]);
    }

    public function get_property(Request $request)
    {
        $id = $request->id;
        if ($request->type == "Property")
            $slide = Property::find($id);
        elseif ($request->type == "Project")
            $slide = Project::find($id);
        return view('admin.website_setting.ajax_prop', ['slide' => $slide, 'type' => $request->type]);
    }

    public function save_main_slider(Request $request)
    {
        return back();
    }

    public function fav_lead(Request $request)
    {
        $lead = Lead::find($request->id);
        if ($lead->favorite) {
            $lead->favorite = 0;
            $lead->save();
            $status = 0;
        } else {
            $lead->favorite = 1;
            $lead->save();
            $status = 1;
        }
        return response()->json([
            'status' => $status,
        ]);
    }

    public function hot_lead(Request $request)
    {
        $lead = Lead::find($request->id);
        if ($lead->hot) {
            $lead->hot = 0;
            $lead->save();
            $status = 0;
        } else {
            $lead->hot = 1;
            $lead->save();
            $status = 1;
        }
        return response()->json([
            'status' => $status,
        ]);
    }

    public function get_lands()
    {
        $lands = Land::get();
        return view('admin.proposals.get_lands', ['lands' => $lands]);
    }

    public function get_suggestions(Request $req)
    {
        $locationsArray = HomeController::getChildren($req->location);
        // dd($locationsArray);
        $locationsArray[] = $req->location;
        if ($req->unit_type != 'land') {
            if ($req->request_type == 'new_home') {
                $units = @Project::where('type', $req->unit_type)->
                whereBetween('meter_price', [$req->price_from, $req->price_to])->
                whereBetween('area', [$req->area_from, $req->area_to])->
                whereIn('location_id', $locationsArray)->
                get();
                $type = 'new_home';
            } elseif ($req->request_type == 'resale') {
                $units = @ResaleUnit::whereBetween('rooms', [$req->rooms_from, $req->rooms_to])->
                where('type', $req->unit_type)->
                where('unit_type_id', $req->unit_type_id)->
                whereBetween('total', [$req->price_from, $req->price_to])->
                whereBetween('area', [$req->area_from, $req->area_to])->
                whereBetween('rooms', [$req->rooms_from, $req->rooms_to])->
                whereIn('location', $locationsArray)->
                where('delivery_date', $req->date)->
                whereBetween('bathrooms', [$req->bathrooms_from, $req->bathrooms_to])->get();
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
                whereBetween('bathrooms', [$req->bathrooms_from, $req->bathrooms_to])->get();
                $type = 'rental';
            }
        } else {
            $units = @Land::whereBetween('meter_price', [$req->price_from, $req->price_to])->
            whereBetween('area', [$req->area_from, $req->area_to])->
            whereIn('location', $locationsArray)->get();
            $type = 'lands';
        }
        return view('admin.requests.get_suggestions', ['units' => $units, 'type' => $type]);
    }

    public function get_projects(Request $request)
    {
        $projects = @Project::where('developer_id', $request->id)->get();
        return view('admin.leads.get_projects', ['projects' => $projects]);
    }

    public function get_report_form(Request $request)
    {
        if ($request->report == 'leads') {
            return view('admin.leads_report_form');
        } else if ($request->report == 'agents') {
            return view('admin.agents_report_form');
        } else if ($request->report == 'developers') {
            $developers = @Developer::get();
            return view('admin.developers_report_form', ['developers' => $developers]);
        } else if ($request->report == 'sales_forecast') {
            return view('admin.sales_forecast_form');
        } else if ($request->report == 'lead_stage') {
            $evaluating = 0;
            $follow = 0;
            $negotiation = 0;
            $won = 0;
            foreach (Lead::get() as $lead) {
                if (Meeting::where('lead_id', $lead->id)->count() == 0 and Call::where('lead_id', $lead->id)->count() == 0 and Proposal::where('lead_id', $lead->id)->count() == 0 and ClosedDeal::where('buyer_id', $lead->id)->count() == 0) {
                    $evaluating++;
                } else if (Meeting::where('lead_id', $lead->id)->count() > 0 and Call::where('lead_id', $lead->id)->count() > 0 and Proposal::where('lead_id', $lead->id)->count() == 0 and ClosedDeal::where('buyer_id', $lead->id)->count() == 0) {
                    $follow++;
                } else if (Meeting::where('lead_id', $lead->id)->count() > 0 and Call::where('lead_id', $lead->id)->count() > 0 and Proposal::where('lead_id', $lead->id)->count() > 0 and ClosedDeal::where('buyer_id', $lead->id)->count() == 0) {
                    $negotiation++;
                } else if (Meeting::where('lead_id', $lead->id)->count() > 0 and Call::where('lead_id', $lead->id)->count() > 0 and Proposal::where('lead_id', $lead->id)->count() > 0 and ClosedDeal::where('buyer_id', $lead->id)->count() > 0) {
                    $won++;
                }
            }
            return view('admin.lead_stage',[
                'evaluating' => $evaluating,
                'follow' => $follow,
                'negotiation' => $negotiation,
                'won' => $won
            ]);
        }
    }

    public function get_lead_report(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $leads = Lead::get();


        return view('admin.get_lead_report', ['from' => $from, 'to' => $to, 'leads' => $leads]);
    }

    public function get_leads_data(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $src = $request->source;

        if ($src != 'all') {
            $leads = Lead::where('created_at', '<=', $to . ' 00:00:00')->
            where('created_at', '>=', $from . ' 23:59:59')->
            where('lead_source_id', $src)->
            get();
        } else {
            $leads = Lead::where('created_at', '<=', $to . ' 00:00:00')->
            where('created_at', '>=', $from . ' 23:59:59')->
            get();
        }

        return view('admin.get_leads_data', ['from' => $from, 'to' => $to, 'leads' => $leads]);
    }

    public function get_target(Request $request)
    {
        $agents = User::get();
        return view('admin.get_target', ['month' => $request->target, 'agents' => $agents]);
    }

    public function get_developer_report(Request $request)
    {
        $deals = ClosedDeal::join('proposals', 'proposals.id', '=', 'closed_deals.proposal_id')->
        join('properties', 'properties.id', '=', 'proposals.unit_id')->
        join('phases', 'properties.phase_id', '=', 'phases.id')->
        join('projects', 'projects.id', '=', 'phases.project_id')->
        join('developers', 'projects.developer_id', '=', 'developers.id')->
        where('proposals.unit_type', 'new_home')->
        where('developers.id', $request->developer)->
        where('closed_deals.created_at', '>=', $request->from . ' 00:00:00')->
        where('closed_deals.created_at', '<=', $request->to . ' 23:59:59')->
        select('closed_deals.id as id',
            'closed_deals.buyer_id as buyer_id',
            'closed_deals.seller_id as seller_id',
            'closed_deals.created_at as date',
            'projects.ar_name as ar_project_name',
            'projects.en_name as en_project_name',
            'closed_deals.price as price')->
        get();
        return view('admin.developer_deals', ['deals' => $deals, 'developer_id' => $request->developer]);
    }

    public function get_project_deals(Request $request)
    {
        if ($request->project != 'all') {
            $deals = ClosedDeal::join('proposals', 'proposals.id', '=', 'closed_deals.proposal_id')->
            join('properties', 'properties.id', '=', 'proposals.unit_id')->
            join('phases', 'properties.phase_id', '=', 'phases.id')->
            join('projects', 'projects.id', '=', 'phases.project_id')->
            join('developers', 'projects.developer_id', '=', 'developers.id')->
            where('proposals.unit_type', 'new_home')->
            where('projects.id', $request->project)->
            where('closed_deals.created_at', '>=', $request->from . ' 00:00:00')->
            where('closed_deals.created_at', '<=', $request->to . ' 23:59:59')->
            select('closed_deals.id as id',
                'closed_deals.buyer_id as buyer_id',
                'closed_deals.seller_id as seller_id',
                'closed_deals.created_at as date',
                'projects.ar_name as ar_project_name',
                'projects.en_name as en_project_name',
                'closed_deals.price as price')->
            get();
        } else {
            $deals = ClosedDeal::join('proposals', 'proposals.id', '=', 'closed_deals.proposal_id')->
            join('properties', 'properties.id', '=', 'proposals.unit_id')->
            join('phases', 'properties.phase_id', '=', 'phases.id')->
            join('projects', 'projects.id', '=', 'phases.project_id')->
            join('developers', 'projects.developer_id', '=', 'developers.id')->
            where('proposals.unit_type', 'new_home')->
            where('developers.id', $request->developer)->
            where('closed_deals.created_at', '>=', $request->from . ' 00:00:00')->
            where('closed_deals.created_at', '<=', $request->to . ' 23:59:59')->
            select('closed_deals.id as id',
                'closed_deals.buyer_id as buyer_id',
                'closed_deals.seller_id as seller_id',
                'closed_deals.created_at as date',
                'projects.ar_name as ar_project_name',
                'projects.en_name as en_project_name',
                'closed_deals.price as price')->
            get();
        }
        return view('admin.project_deals', ['deals' => $deals]);
    }

    public function get_phases(Request $request)
    {
        $phases = Phase::where('project_id', $request->id)->get();
        return view('admin.proposals.get_phases', ['phases' => $phases]);
    }

    public function get_phase_units(Request $request)
    {
        $properties = Property::where('phase_id', $request->id)->where('type', $request->type)->get();
        return view('admin.proposals.get_phase_units', ['properties' => $properties]);
    }

    public function get_sales_forecast_report(Request $request)
    {
        if ($request->agent == 'all') {
            $to = new \DateTime($request->to);
            $from = new \DateTime($request->from);

            $diff = $to->diff($from);
            $diffM = $diff->m;
            $diffY = $diff->y * 12;
            $diff = $diffM + $diffY;
//        $toS = strtotime($request->to);
            $fromS = strtotime($request->from);
            $data = [];
            for ($i = 0; $i <= $diff; $i++) {
                $date = date('Y-m', strtotime('+' . $i . ' month', $fromS));
                $deals = ClosedDeal::where('created_at', '>=', $date . '-01 00:00:00')->
                where('created_at', '<=', $date . '-31 23:59:59')->sum('price');
                $com = ClosedDeal::where('created_at', '>=', $date . '-01 00:00:00')->
                where('created_at', '<=', $date . '-31 23:59:59')->sum('company_commission');
                $data[$date] = ['total' => $deals, 'commission' => $com];
            }
        } else {
            $to = new \DateTime($request->to);
            $from = new \DateTime($request->from);

            $diff = $to->diff($from);
            $diffM = $diff->m;
            $diffY = $diff->y * 12;
            $diff = $diffM + $diffY;
//        $toS = strtotime($request->to);
            $fromS = strtotime($request->from);
            $data = [];
            for ($i = 0; $i <= $diff; $i++) {
                $date = date('Y-m', strtotime('+' . $i . ' month', $fromS));
                $deals = ClosedDeal::where('created_at', '>=', $date . '-01 00:00:00')->
                where('created_at', '<=', $date . '-31 23:59:59')->
                where('agent_id', $request->agent)->sum('price');
                $com = ClosedDeal::where('created_at', '>=', $date . '-01 00:00:00')->
                where('created_at', '<=', $date . '-31 23:59:59')->
                where('agent_id', $request->agent)->sum('company_commission');
                $data[$date] = ['total' => $deals, 'commission' => $com];
            }
        }
        return view('admin.sales_forecast_report', ['data' => $data]);
    }

    public function get_countries_cities(Request $request)
    {
        $cities = DB::table('city')->where('country_id', $request->id)->get();
        return view('admin.resale_units.cities', ['cities' => $cities]);
    }

    public function get_cities_districts(Request $request)
    {
        $districts = DB::table('district')->where('city_id', $request->id)->get();
        return view('admin.resale_units.districts', ['districts' => $districts]);
    }

    public function get_form_projects(Request $request)
    {
        $projects = Project::where('developer_id', $request->id)->get();
        return view('admin.forms.projects', ['projects' => $projects]);
    }

    public function get_form_phases(Request $request)
    {
        $phases = Phase::where('project_id', $request->id)->get();
        return view('admin.forms.phases', ['phases' => $phases]);
    }
     public function get_statics()
    {
        $date = date('m');
        $acceptedapplications = Application::where('created_at', '>=', $date)
            ->where('acceptness', '=', 'accepted')
            ->get();
        $accpts = $acceptedapplications->toArray();
        $accptsCount = count($accpts);
        $accptspercentage = (int)(($accptsCount / $appsCount) * 100);
        return response()->json([
            'accptspercentage' => $accptspercentage
        ]);

    }

    public function rate_employee(Rate $rate)
    {
        $rated_employee_id = request('rated');
        $employee_id = request('id');
        $ratedcol = $rate->where('rated_employee_id', '=', $rated_employee_id)
            ->where('employee_id', '=', $employee_id)
            ->where('Is_rated', "=", 0)
            ->first();

        if (request('rated_work') == NULl) {
            $ratedcol->work = 0;
        } else {
            $ratedcol->work = request('rated_work');
        }
        if (request('rated_apperance') == NULL) {
            $ratedcol->apperance = 0;
        } else {
            $ratedcol->apperance = request('rated_apperance');
        }
        if (request('rated_efficient') == NULL) {
            $ratedcol->effeciant = 0;
        } else {
            $ratedcol->effeciant = request('rated_efficient');
        }
        if (request('rated_target') == NULL) {
            $ratedcol->target = 0;
        } else {
            $ratedcol->target = request('rated_target');
        }
        if (request('rated_ideas') == NULL) {
            $ratedcol->ideas = 0;
        } else {
            $ratedcol->ideas = request('rated_ideas');
        }
        $ratedcol->rate_date = Carbon::now();
        $ratedcol->Is_rated = 1;
        $ratedcol->save();
        return response()->json([
            'status' => '200',
            'test' => 1002
        ]);

    }
    public function dateInterval(Request $request){

        $start = date('Y-m-d',strtotime($request->start));
        $end = date('Y-m-d',strtotime($request->end));
        $title = __('admin.attendance');

        $attendance = Attendance::
            whereBetween('date', [$start, $end])
            ->get();

        return view('admin.attendance.index',compact('attendance','title'));
    }
}
