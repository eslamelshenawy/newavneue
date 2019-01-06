<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('settings') or @auth()->user()->type == 'admin') {
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
        $roles = Role::all();
        return view('admin.roles.index', ['title' => __('admin.roles'), 'roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = self::roles();
        return view('admin.roles.create', ['title' => __('admin.roles'), 'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'roles' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'roles' => trans('admin.roles'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $role = new Role;
            $role->name = $request->name;
            $role->roles = json_encode($request->roles);
            $role->user_id = auth()->user()->id;
            $role->save();

            $old_data = json_encode($role);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $role->name,
                __('admin.created', [], 'en') . ' ' . $role->name,
                'roles',
                $role->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/roles/' . $role->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $roles = self::roles();
        return view('admin.roles.show', ['title' => __('admin.roles'), 'role' => $role, 'roles' => $roles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $roles = self::roles();
        return view('admin.roles.edit', ['title' => __('admin.roles'), 'role' => $role, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required',
            'roles' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'roles' => trans('admin.roles'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($role);
            $role->name = $request->name;
            $role->roles = json_encode($request->roles);
            $role->user_id = auth()->user()->id;
            $role->save();

            $new_data = json_encode($role);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $role->name,
                __('admin.updated', [], 'en') . ' ' . $role->name,
                'roles',
                $role->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/roles/' . $role->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $old_data = json_encode($role);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $role->name,
            __('admin.deleted', [], 'en') . ' ' . $role->name,
            'roles',
            $role->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        $role->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath() . '/roles');
    }

    public static function roles()
    {
        return [
            'leads' => [
                'add_leads',
                'hard_delete_leads',
                'soft_delete_leads',
                'switch_leads',
                'edit_leads',
                'show_all_leads',
                'send_cil',
                'export_excel',
            ],
            'lead_actions' => [
                'calls',
                'meetings',
                'requests',
            ],
            'developers' => [
                'add_developers',
                'edit_developers',
                'delete_developers',
                'show_developers',
            ],
            'projects' => [
                'add_projects',
                'edit_projects',
                'delete_projects',
                'show_projects',
            ],
            'phases' => [
                'add_phases',
                'edit_phases',
                'delete_phases',
                'show_phases',
            ],
            'properties' => [
                'add_properties',
                'edit_properties',
                'delete_properties',
                'show_properties',
            ],
            'resale_units' => [
                'add_resale_units',
                'edit_resale_units',
                'delete_resale_units',
                'show_resale_units',
            ],
            'rental_units' => [
                'add_rental_units',
                'edit_rental_units',
                'delete_rental_units',
                'show_rental_units',
            ],
            'lands' => [
                'add_lands',
                'edit_lands',
                'delete_lands',
                'show_lands',
            ],
            'marketing' => [
                'marketing',
            ],
            'proposals' => [
                'proposals',
            ],
            'deals' => [
                'deals',
            ],
            'finance' => [
                'finance',
            ],
            'reports' => [
                'reports',
            ],
            'settings' => [
                'settings',
            ],
 ////////////hr/////////////////
            'job-categories' => [
                'job-categories',
                'add_job-categories',
                'show_job-categories',
                'edit_job-categories',
                'delete_job-categories',

            ],
            'job_titles' => [
                'job-titles',
                'add_job-titles',
                'show_job-titles',
                'edit_job-titles',
                'delete_job-titles',

            ],
            'vacancies' => [
                'vacancies',
                'add_vacancies',
                'edit_vacancies',
                'show_vacancies',
                'delete_vacancies',

            ],
            'applications' => [
                'applications',
                'add_applications',
                'show_applications',
                'edit_applications',
                'delete_applications',

            ],
            'employees' => [
                'employees',
                'add_employees',
                'edit_employees',
                'delete_employees',
                'show_employees',
            ],
            'emp-dashboard' => [
                'emp-dashboard',
            ],
            'salaries' => [
                'salaries',
                'add_salaries',
                'edit_salaries',
                'delete_salaries',
                'show_salaries',

            ],
            'salaries-details' => [
                'salaries-details',
                'add_salaries-details',
                'delete_salaries-details',
                'edit_salaries-details',
                'show_salaries-details',
            ],
            'vacations' => [
                'vacations',
                'add_vacations',
                'edit_vacations',
                'show_vacations',
                'delete_vacations',
            ],
            'xattendance' => [
                'xattendance',
            ],
            'hr-settings' => [
                'hr-settings',
            ],
            'rates'=> [
              'rates',
              'add_rates',
            ],
            'custodies'=> [
                'custodies',
                'add_custodies',
            ],

        ];
    }
}
