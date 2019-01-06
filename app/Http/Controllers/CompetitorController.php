<?php

namespace App\Http\Controllers;

use App\Competitor;
use Illuminate\Http\Request;
use Validator;

class CompetitorController extends Controller
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
        $competitors = Competitor::all();
        return view('admin.competitors.index', ['title' => __('admin.competitors'), 'index' => $competitors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.competitors.create', ['title' => __('admin.add_competitor')]);
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
            'ar_name' => 'required',
            'en_name' => 'required',
            'facebook' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_name' => trans('admin.ar_name'),
            'en_name' => trans('admin.en_name'),
            'facebook' => trans('admin.facebook'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $competitor = new Competitor;
            $competitor->ar_name = $request->ar_name;
            $competitor->en_name = $request->en_name;
            $competitor->facebook = $request->facebook;
            $competitor->featured = $request->featured;
            $competitor->notes = $request->notes;
            $competitor->user_id = auth()->user()->id;
            $competitor->save();
            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($competitor);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $competitor->ar_name,
                __('admin.created', [], 'en') . ' ' . $competitor->en_name,
                'competitors',
                $competitor->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            return redirect(adminPath() . '/competitors/' . $competitor->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Competitor $competitor
     * @return \Illuminate\Http\Response
     */
    public function show(Competitor $competitor)
    {
        return view('admin.competitors.show', ['title' => __('admin.competitor'), 'show' => $competitor]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Competitor $competitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Competitor $competitor)
    {
        return view('admin.competitors.edit', ['title' => __('admin.competitor'), 'edit' => $competitor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Competitor $competitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Competitor $competitor)
    {
        $rules = [
            'ar_name' => 'required',
            'en_name' => 'required',
            'facebook' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_name' => trans('admin.ar_name'),
            'en_name' => trans('admin.en_name'),
            'facebook' => trans('admin.facebook'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($competitor);
            $competitor->ar_name = $request->ar_name;
            $competitor->en_name = $request->en_name;
            $competitor->facebook = $request->facebook;
            $competitor->featured = $request->featured;
            $competitor->notes = $request->notes;
            $competitor->user_id = auth()->user()->id;
            $competitor->save();
            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($competitor);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $competitor->ar_name,
                __('admin.updated', [], 'en') . ' ' . $competitor->en_name,
                'competitors',
                $competitor->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/competitors/' . $competitor->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Competitor $competitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competitor $competitor)
    {
        $competitor->delete();
        session()->flash('success', trans('admin.deleted'));

        $old_data = json_encode($competitor);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $competitor->ar_name,
            __('admin.deleted', [], 'en') . ' ' . $competitor->en_name,
            'competitors',
            $competitor->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        return redirect(adminPath() . '/competitors');
    }
}
