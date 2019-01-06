<?php

namespace App\Http\Controllers;

use App\Icon;
use Illuminate\Http\Request;
use Validator;
use App\Facility;
use App\Phase_Facilities;

class IconController extends Controller
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
        $icons = Icon::all();
//        $sources=
        return view('admin.icons.index', ['title' => trans('admin.all').' '.trans('admin.icons'), 'icons' => $icons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.icons.create', ['title' => trans('admin.add').' '.trans('admin.icon')]);
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
            'icon'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'icon'=>trans('admin.image'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->hasFile('icon')) {
                foreach ($request->icon as $img) {
                    $icon = new Icon;
                    $icon->icon = upload($img, 'icon');
                    $icon->save();
                }
            }
            return redirect(adminPath().'/icons');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function show(Icon $icon)
    {
        return view('admin.icons.show', ['title' => trans('admin.show').' '.trans('admin.icon'), 'icon' => $icon]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function edit(Icon $icon)
    {
        return view('admin.icons.edit', ['title' =>  trans('admin.edit').' '.trans('admin.facility'), 'icon' => $icon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Icon $icon)
    {
//        $rules = [
//            'en_name' => 'required|max:191',
//            'ar_name' => 'required|max:191',
//        ];
//        $validator = Validator::make($request->all(), $rules);
//        $validator->SetAttributeNames([
//            'en_name' => trans('admin.en_name'),
//            'ar_name' => trans('admin.ar_name'),
//        ]);
//        if ($validator->fails()) {
//            return back()->withInput()->withErrors($validator);
//        }
//        else{
//            $file_path = 'uploads/' . $icon->icon;
//            if (file_exists($file_path)) {
//                // return 'sheno';
//                if ($request->hasFile('icon')) {
//                    if ($request->file('icon')->isValid()) {
//                        unlink($file_path);
//                        $icon->icon = uploads($request, 'icon');
//                    }
//                }
//            }
//            $icon->en_name = $request->en_name;
//            $icon->ar_name = $request->ar_name;
//            $icon->save();
//        }
//        return redirect(adminPath().'/icons');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Icon $icon)
    {
        $id=$icon->id;
        $facilities=Facility::where('icon',$id)->get();
        $repeat=[];

        if(count($facilities)>0)
        {
            foreach ($facilities as $facility)
           array_push($repeat,$facility->id);
            session()->flash('facilities', $repeat);
            return back();
        }
        else
        {
        $file_path = url('/uploads/'.$icon->icon);
        if(file_exists($file_path)) {
            unlink($file_path);
        }
        $icon->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/icons');
        }
    }
}
