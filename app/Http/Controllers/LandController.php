<?php

namespace App\Http\Controllers;

use App\Land;
use App\LandImage;
use Illuminate\Http\Request;
use Validator;

class LandController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (checkRole('lands') or @auth()->user()->type == 'admin') {
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
        if (checkRole('show_lands') or @auth()->user()->type == 'admin') {
            return view('admin.lands.index', ['title' => __('admin.lands'), 'lands' => Land::all()]);
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
        if (checkRole('add_lands') or @auth()->user()->type == 'admin') {
            return view('admin.lands.create', ['title' => __('admin.lands')]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
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
            'ar_title'       => 'required',
            'en_title'       => 'required',
            'ar_description' => 'required',
            'en_description' => 'required',
            'image'          => 'required',
            'area'           => 'required',
            'meter_price'    => 'required',
            'location'       => 'required',
            'lat'            => 'required',
            'lng'            => 'required',
            'zoom'           => 'required',
            'lead_id'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title'       => trans('admin.ar_title'),
            'en_title'       => trans('admin.en_title'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'image'          => trans('admin.image'),
            'area'           => trans('admin.area'),
            'meter_price'    => trans('admin.meter_price'),
            'location'       => trans('admin.location'),
            'lat'            => trans('admin.lat'),
            'lng'            => trans('admin.lng'),
            'zoom'           => trans('admin.zoom'),
            'lead_id'        => trans('admin.lead'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $land                 = new Land;
            $land->ar_title       = $request->ar_title;
            $land->en_title       = $request->en_title;
            $land->ar_description = $request->ar_description;
            $land->en_description = $request->en_description;
            if ($request->hasFile('image')) {
                $land->image = $request->file('image')->store('lands');
            }
            $land->area        = $request->area;
            $land->meter_price = $request->meter_price;
            $land->location    = $request->location;
            $land->lat         = $request->lat;
            $land->lng         = $request->lng;
            $land->zoom        = $request->zoom;
            $land->lead_id     = $request->lead_id;
            $land->user_id     = auth()->user()->id;
            $land->save();

            if ($request->has('other_images')) {
                foreach ($request->other_images as $other_image) {
                    $landImages          = new LandImage;
                    $landImages->image   = $other_image->store('lands');
                    $landImages->land_id = $land->id;
                    $landImages->save();
                }
            }
            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($land);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $land->ar_title,
                __('admin.created', [], 'en') . ' ' . $land->en_title,
                'lands',
                $land->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath() . '/lands/' . $land->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Land  $land
     * @return \Illuminate\Http\Response
     */
    public function show(Land $land)
    {
        if (checkRole('show_lands') or @auth()->user()->type == 'admin') {
            return view('admin.lands.show', ['title' => __('admin.land'), 'land' => $land]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Land  $land
     * @return \Illuminate\Http\Response
     */
    public function edit(Land $land)
    {
        if (checkRole('edit_lands') or @auth()->user()->type == 'admin') {
            return view('admin.lands.edit', ['title' => __('admin.land'), 'land' => $land]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Land  $land
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Land $land)
    {
        $rules = [
            'ar_title'       => 'required',
            'en_title'       => 'required',
            'ar_description' => 'required',
            'en_description' => 'required',
            'area'           => 'required',
            'meter_price'    => 'required',
            'location'       => 'required',
            'lat'            => 'required',
            'lng'            => 'required',
            'zoom'           => 'required',
            'lead_id'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title'       => trans('admin.ar_title'),
            'en_title'       => trans('admin.en_title'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'image'          => trans('admin.image'),
            'area'           => trans('admin.area'),
            'meter_price'    => trans('admin.meter_price'),
            'location'       => trans('admin.location'),
            'lat'            => trans('admin.lat'),
            'lng'            => trans('admin.lng'),
            'zoom'           => trans('admin.zoom'),
            'lead_id'        => trans('admin.lead'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($land);
            $land->ar_title       = $request->ar_title;
            $land->en_title       = $request->en_title;
            $land->ar_description = $request->ar_description;
            $land->en_description = $request->en_description;
            if ($request->hasFile('image')) {
                $land->image = $request->file('image')->store('lands');
            }
            $land->area        = $request->area;
            $land->meter_price = $request->meter_price;
            $land->location    = $request->location;
            $land->lat         = $request->lat;
            $land->lng         = $request->lng;
            $land->zoom        = $request->zoom;
            $land->lead_id     = $request->lead_id;
            $land->user_id     = auth()->user()->id;
            $land->save();

            if ($request->has('other_images')) {
                foreach ($request->other_images as $other_image) {
                    $landImages          = new LandImage;
                    $landImages->image   = $other_image->store('lands');
                    $landImages->land_id = $land->id;
                    $landImages->save();
                }
            }
            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($land);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $land->ar_title,
                __('admin.updated', [], 'en') . ' ' . $land->en_title,
                'lands',
                $land->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/lands/' . $land->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Land  $land
     * @return \Illuminate\Http\Response
     */
    public function destroy(Land $land)
    {
        if (checkRole('delete_lands') or @auth()->user()->type == 'admin') {
            @unlink('uploads/'.$land->image);
            $images = LandImage::where('land_id',$land->id)->get();
            if (count($images) > 0) {
                foreach ($images as $image) {
                    @unlink('uploads/'.$image->image);
                    $image->delete();
                }
            }
    
            $old_data = json_encode($land);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $land->ar_title,
                __('admin.deleted', [], 'en') . ' ' . $land->en_title,
                'lands',
                $land->id,
                'delete',
                auth()->user()->id,
                $old_data
            );
    
            $land->delete();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath().'/lands');
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function delete_land_image(Request $request)
    {
        $img = LandImage::find($request->id);
        @unlink('uploads/'.$img->image);
        $img->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
