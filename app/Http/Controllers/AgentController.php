<?php

namespace App\Http\Controllers;

use App\RentalUnit;
use App\ResaleUnit;
use App\User;
use Illuminate\Http\Request;
use Validator;
use DB;
use Hash;
use Auth;

class AgentController extends Controller
{
    public function __construct()
    {
    //    $this->middleware(function ($request, $next) {
    //        if (checkRole('settings') or @auth()->user()->type == 'admin') {
    //            return $next($request);
    //        } else {
    //            session()->flash('error', __('admin.you_dont_have_permission'));
    //            return back();
    //        }
    //    });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (checkRole('settings') or @auth()->user()->type == 'admin') {
            $sources = DB::table('users')->join('agent_types', 'users.agent_type_id', '=', 'agent_types.id')->
            select('users.id', 'users.name', 'users.email', 'users.phone', 'agent_types.name as source_name')->get();
            return view('admin.agents.index', ['title' => trans('admin.all') . ' ' . trans('admin.agent'), 'index' => $sources]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (checkRole('settings') or @auth()->user()->type == 'admin') {
            return view('admin.agents.create', ['title' => trans('admin.add') . ' ' . trans('admin.agent')]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
    {
        $image = false;
        if ($request->hasFile('image')) {
            $rules = [
                'name' => 'required|max:191',
                'email' => 'email|max:191|unique:users',
                'phone' => 'required|numeric|unique:users',
                'agent_source' => 'required|max:191',
                'password' => 'required|max:191',
                'image' => 'required|image',
                'type' => 'required',
                'residential_commercial' => 'required',
            ];
            $image = true;
        } else {
            $rules = [
                'name' => 'required|max:191',
                'email' => 'email|max:191|unique:users',
                'phone' => 'required|numeric',
                'agent_source' => 'required|max:191',
                'password' => 'required|max:191',
                'type' => 'required|max:191',
                'residential_commercial' => 'required',
            ];

        }

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'password' => trans('admin.password'),
            'agent_source' => trans('admin.lead_source'),
            'type' => trans('admin.type'),
            'residential_commercial' => trans('admin.residential_commercial'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            try {
             DB::beginTransaction();
            $lead = new User;
            $lead->name = $request->name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->type = $request->type;
            $lead->role_id = $request->role_id;
            $lead->agent_type_id = $request->agent_source;
            $lead->residential_commercial = $request->residential_commercial;
                $lead->email_password = encrypt($request->email_password);
                $lead->user_id = Auth::user()->id;
                $lead->password = bcrypt($request->password);
                if ($image) {
                $lead->image = uploads($request, 'image');
            } else {
                $lead->image = "image.jpg";
            }

            $lead->save();
            $emp = new Employee();
            $emp->en_first_name = $request->name;
            $emp->personal_mail = $request->email;
            $emp->password = bcrypt($request->password);
            $emp->user_id = $lead->id;
            $emp->save();
            $ld = User::find($lead->id)->first();
            $ld->employee_id = $emp->id;
            $ld->save();

            $old_data = json_encode($lead);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $lead->name,
                __('admin.created', [], 'en') . ' ' . $lead->name,
                'agent',
                $lead->id,
                'create',
                auth()->user()->id,
                $old_data
            );
                DB::commit();
                session()->flash('success', trans('admin.created'));
            }catch (Exception $ex){
                DB::rollBack();
                session()->flash('error', trans('admin.failed'));
                return back();
            }

            return redirect(adminPath() . '/agent');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function show($agent)
    {
        if (checkRole('settings') or @auth()->user()->type == 'admin' or auth()->user()->id == $agent) {
            $show = DB::table('users')->join('agent_types', 'users.agent_type_id', '=', 'agent_types.id')->
            select('users.id', 'users.name', 'users.email', 'users.phone', 'users.image', 'users.role_id', 'users.type', 'agent_types.name as source')->
            where('users.id', '=', $agent)->first();
            $resale = ResaleUnit::join('leads', 'leads.id', 'resale_units.lead_id')->
            join('unit_types','resale_units.unit_type_id', '=', 'unit_types.id')->
            where('leads.agent_id', $agent)->
            select('resale_units.' . app()->getLocale() . '_title as title',
                'unit_types.' . app()->getLocale() . '_name as unit_type',
                'leads.first_name as first_name',
                'leads.last_name as last_name',
                'resale_units.price as price',
                'resale_units.image as image',
                'resale_units.availability as availability',
                'resale_units.id as id',
                'resale_units.type as type')->get();
            $rental = RentalUnit::join('leads', 'leads.id', 'rental_units.lead_id')->
            join('unit_types','rental_units.unit_type_id', '=', 'unit_types.id')->
            where('leads.agent_id', $agent)->
            select('rental_units.' . app()->getLocale() . '_title as title',
                'unit_types.' . app()->getLocale() . '_name as unit_type',
                'leads.first_name as first_name',
                'leads.last_name as last_name',
                'rental_units.rent as price',
                'rental_units.image as image',
                'rental_units.availability as availability',
                'rental_units.id as id',
                'rental_units.type as type')->get();
            return view('admin.agents.show',
                ['title' => trans('admin.show') . ' ' . trans('admin.agent'),
                    'show' => $show,
                    'resale' => $resale,
                    'rental' => $rental
                ]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function edit($agent)
    {
        if (checkRole('settings') or @auth()->user()->type == 'admin' or auth()->user()->id == $agent) {
            $data = User::find($agent);
            return view('admin.agents.edit', ['title' => trans('admin.edit') . ' ' . trans('admin.agent'), 'data' => $data]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $agent)
    {
        $lead = User::find($agent);

        $rules = [
            'name' => 'required|max:191',
            'email' => 'email|max:191|unique:users,email,' . $agent . ',id',
            'phone' => 'required|numeric',
            'agent_source' => 'required|max:191',
            'type' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);

        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'email' => trans('admin.email'),
            'phone' => trans('admin.phone'),
            'agent_source' => trans('admin.lead_source'),
            'type' => trans('admin.type'),
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($lead);
            $file_path = 'uploads/' . $lead->image;
            if (file_exists($file_path)) {
                if ($request->hasFile('image')) {
                    if ($request->file('image')->isValid()) {
                        if ($lead->image != 'image.jpg')
                            @unlink($file_path);
                        $lead->image = uploads($request, 'image');
                    }
                }
            }
            $lead->name = $request->name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->type = $request->type;
            $lead->role_id = $request->role_id;
            $lead->agent_type_id = $request->agent_source;
            $lead->residential_commercial = $request->residential_commercial;
            if ($request->password != '') {
                $lead->password = Hash::make($request->password);
            }

            if ($request->email_password != '') {
                $lead->email_password = encrypt($request->email_password);
            }
            $lead->save();

            session()->flash('success', trans('admin.updated'));
            $new_data = json_encode($lead);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $lead->name,
                __('admin.updated', [], 'en') . ' ' . $lead->name,
                'agent',
                $lead->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/agent');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy($agent)
    {
        if (checkRole('settings') or @auth()->user()->type == 'admin') {
            $data = User::find($agent);
    
            $old_data = json_encode($data);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $data->name,
                __('admin.deleted', [], 'en') . ' ' . $data->name,
                'agent',
                $data->id,
                'delete',
                auth()->user()->id,
                $old_data
            );
    
            if ($data->image != "image.jpg") {
                $file_path = 'uploads/' . $data->image;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $data->delete();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath() . '/agent');
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }
}
