<?php

namespace App\Http\Controllers;

use App\ProjectTag;
use App\Tag;
use Illuminate\Http\Request;
use Validator;
use Auth;

class TagController extends Controller
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
        $tags = Tag::all();
//        $sources=
        return view('admin.tags.index', ['title' => trans('admin.all') . ' ' . trans('admin.tags'), 'tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create', ['title' => trans('admin.add') . ' ' . trans('admin.tag')]);
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
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $tag = new Tag;
            $tag->en_name = $request->en_name;
            $tag->ar_name = $request->ar_name;
            $tag->user_id = Auth::user()->id;
            $tag->save();

            $old_data = json_encode($tag);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $tag->ar_name,
                __('admin.created', [], 'en') . ' ' . $tag->en_name,
                'tags',
                $tag->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            return redirect(adminPath() . '/tags');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return view('admin.tags.show', ['title' => trans('admin.show') . ' ' . trans('admin.tag'), 'tag' => $tag]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', ['title' => trans('admin.edit') . ' ' . trans('admin.tag'), 'tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($tag);
            $tag->en_name = $request->en_name;
            $tag->ar_name = $request->ar_name;
            $tag->save();

            $new_data = json_encode($tag);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $tag->ar_name,
                __('admin.updated', [], 'en') . ' ' . $tag->en_name,
                'tags',
                $tag->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            return redirect(adminPath() . '/tags');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $repeat = [];
        $id = $tag->id;
        $projects = ProjectTag::where('tag_id', $id)->get();
        if (count($projects) > 0) {
            foreach ($projects as $project)
                array_push($repeat, $project->project_id);
            session()->flash('projects', $repeat);
            return back();
        } else {
            $old_data = json_encode($tag);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $tag->ar_name,
                __('admin.deleted', [], 'en') . ' ' . $tag->en_name,
                'tags',
                $tag->id,
                'delete',
                auth()->user()->id,
                $old_data
            );
            $tag->delete();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath() . '/tags');
        }
    }
}
