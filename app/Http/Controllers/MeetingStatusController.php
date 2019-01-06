<?php

namespace App\Http\Controllers;

use App\MeetingStatus;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;

class MeetingStatusController extends Controller
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

    public function index()
    {
        $statuses = MeetingStatus::all();
//        $sources=
        return view('admin.meeting_statuses.index', ['title' => trans('admin.meeting_statuses'), 'index' => $statuses]);
    }

    public function create()
    {
        return view('admin.meeting_statuses.create', ['title' => trans('admin.meeting_status')]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $status = new MeetingStatus;
            $status->name = $request->name;
            $status->has_next_action = $request->has_next_action;
            $status->user_id = auth()->user()->id;
            $status->save();

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($status);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $status->name,
                __('admin.created', [], 'en') . ' ' . $status->name,
                'meeting_statuses',
                $status->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath().'/meeting_statuses');
        }
    }

    public function show($id)
    {
        $show = MeetingStatus::find($id);
        return view('admin.meeting_statuses.show', ['title' => trans('admin.meeting_status'), 'show' => $show]);
    }

    public function edit($id)
    {
        $edit = MeetingStatus::find($id);
        return view('admin.meeting_statuses.edit', ['title' => trans('admin.meeting_status'), 'edit' => $edit]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $status = MeetingStatus::find($id);
            $old_data = json_encode($status);
            $status->name = $request->name;
            $status->has_next_action = $request->has_next_action;
            $status->save();

            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($status);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $status->name,
                __('admin.updated', [], 'en') . ' ' . $status->name,
                'meeting_statuses',
                $status->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath().'/meeting_statuses');
        }
    }

    public function destroy($id)
    {
        $data = MeetingStatus::find($id);

        $old_data = json_encode($data);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $data->name,
            __('admin.deleted', [], 'en') . ' ' . $data->name,
            'meeting_statuses',
            $data->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        $data->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/meeting_statuses');
    }
}
