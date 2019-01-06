<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;
use App\AdminNotification;
use App\User;

class TaskController extends Controller
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
        $sources = DB::table('tasks')->join('users', 'tasks.agent_id', '=', 'users.id')
           ->select('tasks.id','users.name', 'tasks.leads', 'tasks.due_date', 'tasks.task_type', 'tasks.status', 'tasks.description')->get();
        return view('admin.tasks.index', ['title' => trans('admin.task'), 'source' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tasks.create', ['title' => trans('admin.task')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'agent_id' => 'required|max:191',
            'leads' => 'required',
            'due_date' => 'required|max:191',
            'task_type' => 'required|max:191',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'agent_id' => trans('admin.agent'),
            'leads' => trans('admin.lead'),
            'due_date' => trans('admin.date'),
            'task_type' => trans('admin.tasks_type'),
            'status' => trans('admin.status'),
            'description' => trans('admin.description'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $task = new Task;
            $task->agent_id = $request->agent_id;
            $task->leads = $request->leads;
            $task->due_date = strtotime($request->due_date);
            $task->task_type = $request->task_type;
            $task->status = 'pending';
            $task->description =  $request->description;
            $task->user_id = Auth::user()->id;
            $task->save();

            $old_data = json_encode($task);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . __('admin.task',[],'ar'),
                __('admin.created', [], 'en') . ' ' . __('admin.task',[],'en'),
                'tasks',
                $task->id,
                'create',
                auth()->user()->id,
                $old_data
            );
              $not = new AdminNotification;
            $not->user_id = auth()->user()->id;
            $not->assigned_to = $request->agent_id;
            $not->type = 'task';
            $not->type_id = $task->id;
            $not->save();

            $tokens=User::where('refresh_token', '!=', '')->where('id',$request->agent_id)->pluck('refresh_token')->toArray();
            $msg = array(
                'title' => __('admin.task', [], 'en'),
                'body' => Auth::user()->name.' set you in '.$request->task_type.' in '.$request->due_date,
                'image' => 'myIcon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
            );

            notify1($tokens, $msg);

            session()->flash('success', trans('admin.created'));
            return redirect(adminPath().'/tasks/'. $task->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($task)
    {
        $sources = DB::table('tasks')->join('users', 'tasks.agent_id', '=', 'users.id')
            ->where('tasks.id',$task)
            ->select('tasks.id','users.name','tasks.user_id', 'tasks.leads', 'tasks.due_date', 'tasks.task_type', 'tasks.status', 'tasks.description')
            ->first();
        return view('admin.tasks.show', ['title' => trans('admin.task'), 'source' => $sources]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($task)
    {
        $sources = DB::table('tasks')->join('users', 'tasks.agent_id', '=', 'users.id')
            ->where('tasks.id',$task)
            ->select('tasks.id','users.name','tasks.agent_id' ,'tasks.leads', 'tasks.due_date', 'tasks.task_type', 'tasks.status', 'tasks.description')
            ->first();
        return view('admin.tasks.edit', ['title' => trans('admin.task'), 'data' => $sources]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $rules = [
            'agent_id' => 'required|max:191',
            'leads' => 'required',
            'due_date' => 'required|max:191',
            'task_type' => 'required|max:191',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'agent_id' => trans('admin.agent'),
            'leads' => trans('admin.lead'),
            'due_date' => trans('admin.date'),
            'task_type' => trans('admin.tasks_type'),
            'description' => trans('admin.description'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($task);
            $task->agent_id = $request->agent_id;
            $task->leads = $request->leads;
            $task->due_date = strtotime($request->due_date);
            $task->task_type = $request->task_type;
            $task->description =  $request->description;
            $task->save();

            $new_data = json_encode($task);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . __('admin.task',[],'ar'),
                __('admin.updated', [], 'en') . ' ' . __('admin.task',[],'en'),
                'tasks',
                $task->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath().'/tasks/'. $task->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $old_data = json_encode($task);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . __('admin.task',[],'ar'),
            __('admin.deleted', [], 'en') . ' ' . __('admin.task',[],'en'),
            'tasks',
            $task->id,
            'delete',
            auth()->user()->id,
            $old_data
        );
        $task->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/tasks');
    }

    public function confirm_task($id)
    {
        $task = Task::find($id);
        $task->status = 'done';
        $task->save();
        session()->flash('success', trans('admin.confirmed'));
        return back();

    }
}
