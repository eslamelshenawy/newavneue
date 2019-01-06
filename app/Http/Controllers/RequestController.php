<?php

namespace App\Http\Controllers;

use App\InterestedRequest;
use Illuminate\Http\Request as Request;
use App\Request as Model;
use Auth;
use DB;
use PhpParser\Node\Expr\AssignOp\Mod;
use Validator;
use App\Project;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('requests') or @auth()->user()->type == 'admin') {
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

        $request = Model::paginate(10);
        return view('admin.requests.index', ['title' => trans('admin.all_requests'), 'index' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.requests.create', ['title' => trans('admin.add_request')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->unit_type == 'land') {
            $rules = [
                'lead' => 'required|max:191',
                'location' => 'required|max:191',
                'down_payment' => 'required|max:191',
//                'unit_type_id' => 'required|max:191',
                'unit_type' => 'required|max:191',
//                'request_type' => 'required|max:191',
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
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead' => trans('admin.lead'),
            'location' => trans('admin.location'),
            'request_type' => trans('admin.request_type'),
            'unit_type_id' => trans('admin.type'),
            'unit_type' => trans('admin.unit_type'),
            'down_payment' => trans('admin.down_payment'),
            'area_from' => trans('admin.area_from'),
            'area_to' => trans('admin.area_to'),
            'date' => trans('admin.date'),
            'price_from' => trans('admin.price_from'),
            'price_to' => trans('admin.price_to'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $req = new Model;
            $req->lead_id = $request->lead;
            $req->location = $request->location;
            $req->down_payment = $request->down_payment;
            $req->area_from = $request->area_from;
            $req->area_to = $request->area_to;
            $req->price_from = $request->price_from;
            $req->price_to = $request->price_to;
            $req->date = $request->date;
            $req->unit_type = $request->unit_type;
            $req->project_id = $request->project_id;
            $req->lat = $request->lat;
            $req->lng = $request->lng;
            $req->zoom = $request->zoom;
            $req->type = $request->buyer_seller;
            if ($request->unit_type != 'land') {
                $req->request_type = $request->request_type;
                $req->unit_type_id = $request->unit_type_id;
            } else {
                $req->request_type = 'land';
                $req->unit_type_id = 0;
            }

            if ($request->request_type != 'new_home' or $request->request_type != 'land') {
                $req->rooms_from = $request->rooms_from;
                $req->rooms_to = $request->rooms_to;
                $req->bathrooms_from = $request->bathrooms_from;
                $req->bathrooms_to = $request->bathrooms_to;
            }
            $req->notes = $request->notes;
            $req->user_id = Auth::user()->id;
            $req->save();

            $old_data = json_encode($req);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.request', [], 'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.request', [], 'en'),
                'requests',
                $req->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/requests/' . $req->id);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Model $request)
    {
        return view('admin.requests.show', ['title' => trans('admin.all_requests'), 'req' => $request]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $request)
    {
        return view('admin.requests.edit', ['title' => trans('admin.edit_lead'), 'data' => $request]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $asd)
    {
        $rules = [
            'lead' => 'required|max:191',
            'unit_type' => 'required|max:191',
            'price_from' => 'required|numeric|min:0',
            'price_to' => 'required|numeric|min:' . $request->price_from . '',
            'start_date' => 'required|max:191',
            'end_date' => 'required|max:191',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead' => trans('admin.lead'),
            'unit_type' => trans('admin.unit_type'),
            'price_from' => trans('admin.price') . ' ' . trans('admin.from'),
            'price_to' => trans('admin.price') . ' ' . trans('admin.to'),
            'start_date' => trans('admin.date') . ' ' . trans('admin.start'),
            'end_date' => trans('admin.date') . ' ' . trans('admin.end'),
            'description' => trans('admin.description'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $lead = Model::find($asd);
            $lead->lead_id = $request->lead;
            $lead->unit_type_id = $request->unit_type;
            $lead->price_from = $request->price_from;
            $lead->price_to = $request->price_to;
            $lead->date_from = strtotime($request->start_date);
            $lead->date_to = strtotime($request->end_date);
            $lead->description = $request->description;
            $lead->type = $request->type;
            $lead->save();
            return redirect(adminPath() . '/requests');;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($request)
    {
        $data = Model::find($request);

        $old_data = json_encode($data);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . __('admin.request', [], 'ar'),
            __('admin.deleted', [], 'en') . ' ' . __('admin.request', [], 'en'),
            'requests',
            $data->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        $data->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath() . '/requests');
    }

    public function interestedRequest($unit, $req)
    {
        if (InterestedRequest::where('unit_id', $unit)->where('request_id', $req)->count()) {
            $interests = InterestedRequest::where('unit_id', $unit)->where('request_id', $req)->get();
            foreach ($interests as $interest) {
                $interest->delete();
            }
            session()->flash('success', __('admin.removed'));
        } else {
            $interest = new InterestedRequest;
            $interest->unit_id = $unit;
            $interest->request_id = $req;
            $interest->save();
            session()->flash('success', __('admin.added'));
        }

        session()->flash('return_to_suggestions', 1);
        return back();
    }
    
    public function getProjects(Request $r)
    {
        $projects = Project::where('developer_id', $r->id)->get();
        return view('admin.requests.get_projects', compact('projects'));
    }
}
