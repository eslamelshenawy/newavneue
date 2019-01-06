<?php

namespace App\Http\Controllers;

use Validator;
use App\ToDo;
use Illuminate\Http\Request;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = ToDo::where('user_id',auth()->user()->id)->get();
        return view('admin.todos.index', ['title' => trans('admin.all_todos'), 'index' => $todos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.todos.create', ['title' => trans('admin.add_todo')]);
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
            'leads' => 'required',
            'due_date' => 'required',
            'to_do_type' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'leads' => trans('admin.leads'),
            'due_date' => trans('admin.due_date'),
            'to_do_type' => trans('admin.to_do_type'),
            'description' => trans('admin.description'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $todo = new ToDo;
            $todo->leads = $request->leads;
            $todo->description = $request->description;
            $todo->to_do_type = $request->to_do_type;
            $todo->due_date = strtotime($request->due_date);
            $todo->time = strtotime($request->time);
            $todo->user_id = auth()->user()->id;
            $todo->status = 'pending';
            $todo->save();
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/todos/' . $todo->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ToDo $toDo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $toDo = ToDo::find($id);
        return view('admin.todos.show', ['title' => trans('admin.show_todo'), 'todo' => $toDo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ToDo $toDo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $toDo = ToDo::find($id);
        return view('admin.todos.edit', ['title' => trans('admin.edit_todo'), 'todo' => $toDo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\ToDo $toDo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'leads' => 'required',
            'due_date' => 'required',
            'to_do_type' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'leads' => trans('admin.leads'),
            'due_date' => trans('admin.due_date'),
            'to_do_type' => trans('admin.to_do_type'),
            'description' => trans('admin.description'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $toDo = ToDo::find($id);
            $toDo->leads = $request->leads;
            $toDo->description = $request->description;
            $toDo->to_do_type = $request->to_do_type;
            $toDo->due_date = strtotime($request->due_date);
            $toDo->time = strtotime($request->time);
            $toDo->user_id = auth()->user()->id;
            $toDo->status = 'pending';
            $toDo->save();
            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/todos/' . $toDo->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ToDo $toDo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $toDo = ToDo::find($id);
        $toDo->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/todos');
    }

    public function confirm_to_do($id)
    {
        $toDo = ToDo::find($id);
        $toDo->status = 'done';
        $toDo->save();
        session()->flash('success', trans('admin.confirmed'));
        return back();

    }
}
