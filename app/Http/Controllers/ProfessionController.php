<?php

namespace App\Http\Controllers;

use Validator;
use App\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professions = Profession::get();
        return view('admin.professions.index', ['title' => trans('admin.all_professions'), 'professions' => $professions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.professions.create', ['title' => trans('admin.add_profession')]);
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
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $profession = new Profession;
            $profession->name = $request->name;
            $profession->notes = $request->notes;
            $profession->user_id = auth()->user()->id;
            $profession->save();
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/professions/' . $profession->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function show(Profession $profession)
    {
        return view('admin.professions.show', ['title' => trans('admin.show_profession'), 'profession' => $profession]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function edit(Profession $profession)
    {
        return view('admin.professions.edit', ['title' => trans('admin.edit_profession'), 'profession' => $profession]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profession $profession)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $profession->name = $request->name;
            $profession->notes = $request->notes;
            $profession->save();
            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/professions/' . $profession->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profession $profession)
    {
        $profession->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/professions');
    }
}
