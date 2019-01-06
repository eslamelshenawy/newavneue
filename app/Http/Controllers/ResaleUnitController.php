<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupMember;
use App\Project;
use App\ResalImage;
use App\Setting;
use App\UnitFacility;
use Validator;
use App\ResaleUnit;
use Illuminate\Http\Request;
use Image;

class ResaleUnitController extends Controller
{
    public function index()
    {
        if (checkRole('show_resale_units') or @auth()->user()->type == 'admin') {
            $units = ResaleUnit::get();
            $resaleUnit = [];
            foreach ($units as $unit) {
                if ($unit->agent_id == auth()->id()) {
                    $resaleUnit[] = $unit;
                } else {
                    if ($unit->privacy == 'only_me' and $unit->agent_id == auth()->id()) {
                        $resaleUnit[] = $unit;
                    } else if ($unit->privacy == 'public') {
                        $resaleUnit[] = $unit;
                    } else if ($unit->privacy == 'team_only') {
                        $groups = GroupMember::where('member_id', $unit->agent_id)->pluck('group_id');
                        $members = [];
                        foreach ($groups as $group) {
                            $groupMembers = GroupMember::where('group_id', $group)->pluck('member_id')->toArray();
                            $members[] = Group::find($group)->team_leader_id;
                            foreach ($groupMembers as $member) {
                                $members[] = $member;
                            }
                        }
                        if (in_array(auth()->id(), $members)) {
                            $resaleUnit[] = $unit;
                        }
                    } else if ($unit->privacy == 'custom') {
                        $agents = @json_decode($unit->custom_agents);
                        if (is_array($agents)) {
                            if (in_array(auth()->id(), $agents)) {
                                $resaleUnit[] = $unit;
                            }
                        }
                    }
                }
            }
//            dd($resaleUnit);
            return view('admin.resale_units.index', ['title' => trans('admin.resale_units'), 'units' => $resaleUnit]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function create()
    {
        if (checkRole('add_resale_units') or @auth()->user()->type == 'admin') {
            return view('admin.resale_units.create', ['title' => trans('admin.add_resale_unit')]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function store(Request $request)
    {
        if ($request->type == 'personal') {
            $rules = [
                'ar_title' => 'required',
                'en_title' => 'required',
                'type' => 'required',
                'unit_type_id' => 'required',
                'total' => 'required',
                'payment_method' => 'required',
                'agent_id' => 'required',
                'privacy' => 'required',
                'finishing' => 'required',
                'image' => 'required',
                // 'ar_address' => 'required',
                // 'en_address' => 'required',
                // 'ar_description' => 'required',
                // 'en_description' => 'required',
                'lng' => 'required',
                'lat' => 'required',
                'zoom' => 'required',
                'lead_id' => 'required',
                
    //             'phone' => 'required',
    //             'area' => 'required',
    //             'price' => 'required',
    //             'rooms' => 'required',
    //             'bathrooms' => 'required',
                
                
    //             'due_now' => 'required|numeric',
                
    //             'view' => 'required',
				// 'facility' => 'required',
				
                
			];
        } else {
            $rules = [
                'ar_title' => 'required',
                'en_title' => 'required',
                'type' => 'required',
                'unit_type_id' => 'required',
                'total' => 'required',
                'payment_method' => 'required',
                'agent_id' => 'required',
                'privacy' => 'required',
                'finishing' => 'required',
                'image' => 'required',
                'ar_address' => 'required',
                'en_address' => 'required',
                'ar_description' => 'required',
                'en_description' => 'required',
                'lng' => 'required',
                'lat' => 'required',
                'zoom' => 'required',
                'lead_id' => 'required'
			];
        }

        if ($request->privacy == 'custom') {
            $rules['agents'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'type' => trans('admin.type'),
            'total' => trans('admin.total'),
            'finishing' => trans('admin.finishing'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'ar_title' => trans('admin.ar_title'),
            'en_title' => trans('admin.en_title'),
            'ar_address' => trans('admin.ar_address'),
            'en_address' => trans('admin.en_address'),
            'phone' => trans('admin.phone'),
            'area' => trans('admin.area'),
            'price' => trans('admin.price'),
            'rooms' => trans('admin.rooms'),
            'bathrooms' => trans('admin.bathrooms'),
            'lng' => trans('admin.lng'),
            'lat' => trans('admin.lat'),
            'zoom' => trans('admin.zoom'),
            'image' => trans('admin.image'),
            'due_now' => trans('admin.due_now'),
            'payment_method' => trans('admin.payment_method'),
            'view' => trans('admin.view'),
			'facility' => trans('admin.facility'),
			'privacy' => trans('admin.privacy'),
			'agents' => trans('admin.agents'),
			'agent_id' => trans('admin.agent'),
			'lead_id' => trans('admin.lead_id')
		]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $unit = new ResaleUnit;
            $unit->type = $request->type;
            $unit->unit_type_id = $request->unit_type_id;
            $unit->project_id = $request->project_id;
            $unit->lead_id = $request->lead_id;
            $unit->original_price = $request->original_price;
            $unit->payed = $request->payed;
            $unit->rest = $request->rest;
            $unit->total = $request->total;
            $unit->delivery_date = $request->delivery_date;
            $unit->finishing = $request->finishing;
            $unit->location = $request->location;
            $unit->ar_notes = $request->ar_notes;
            $unit->en_notes = $request->en_notes;
            $unit->ar_description = $request->ar_description;
            $unit->en_description = $request->en_description;
            $unit->ar_title = $request->ar_title;
            $unit->en_title = $request->en_title;
            $unit->due_now = $request->due_now;
            $unit->ar_address = $request->ar_address;
            $unit->en_address = $request->en_address;
            $unit->youtube_link = $request->youtube_link;
            $unit->phone = $request->phone;
            $unit->lead_id = $request->lead_id;
            $unit->featured = $request->featured;
            $unit->meta_keywords = $request->meta_keywords;
            $unit->meta_description = $request->meta_description;
            $unit->priority = 0;
            if ($request->has('other_phones')) {
                $unit->other_phones = json_encode($request->other_phones);
            } else {
                $unit->other_phones = json_encode([]);
            }
            $unit->area = $request->area;
            $unit->price = $request->price;
            $unit->rooms = $request->rooms;
            $unit->bathrooms = $request->bathrooms;
            $unit->floors = $request->floors;
            $unit->lng = $request->lng;
            $unit->lat = $request->lat;
            $unit->zoom = $request->zoom;
            $unit->privacy = $request->privacy;
            $unit->agent_id = $request->agent_id;
            if ($request->privacy == 'custom') {
                $unit->custom_agents = json_encode($request->agents);
            }

            $set = Setting::first();

			if ($request->hasFile('image')) {
				$unit->image = upload($request->image, 'resale_unit');
				$watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
				$image = Image::make('uploads/'.$unit->image);
                $image->insert($watermark, 'bottom-right', 10, 10);
                $image->save("uploads/resale_unit/watermarked_resale".rand(0,99999999999).".jpg");
                $unit->watermarked_image = $image->dirname.'/'.$image->basename;
            }
            $unit->payment_method = $request->payment_method;
            $unit->view = $request->view;
            $unit->availability = 'available';
            $unit->user_id = auth()->user()->id;
            $unit->save();

            $old_data = json_encode($unit);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $unit->ar_title,
                __('admin.created', [], 'en') . ' ' . $unit->en_title,
                'resale_units',
                $unit->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            if ($request->has('other_images')) {
                foreach ($request->other_images as $img) {
                    $other_image_model = new ResalImage;
                    $other_image = upload($img, 'resale_unit');
                    $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                    $image = Image::make('uploads/'.$other_image);
                    $image->insert($watermark, 'bottom-right', 10, 10);
                    $image->save("uploads/resale_unit/other_watermarked_resale".rand(0,99999999999).".jpg");
                    $other_watermarked_images = $image->dirname.'/'.$image->basename;
                    $other_image_model->unit_id = $unit->id;
                    $other_image_model->image = $other_image;
                    $other_image_model->watermarked_image = $other_watermarked_images;
                    $other_image_model->save();
                }
            }
            $project = new ProjectController();
			foreach ($request->facility as $facility) {

				$project->addfacility($unit->id, $facility, 'resale');

			}
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/resale_units/' . $unit->id);
        }
    }

    public function show(ResaleUnit $resaleUnit)
    {
        if (checkRole('show_resale_units') or @auth()->user()->type == 'admin') {
            return view('admin.resale_units.show', ['title' => trans('admin.show_resale_unit'), 'unit' => $resaleUnit]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function edit(ResaleUnit $resaleUnit)
    {
        if (checkRole('edit_resale_units') or @auth()->user()->type == 'admin') {
    		$facilities = UnitFacility::where('type', 'resale')->where('unit_id', $resaleUnit->id)->pluck('facility_id')->toArray();
    		return view('admin.resale_units.edit', ['title' => trans('admin.edit_resale_unit'), 'unit' => $resaleUnit,'facilities' => $facilities]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function update(Request $request, ResaleUnit $resaleUnit)
    {
        if ($request->type == 'personal') {
            $rules = [
                'type' => 'required',
                'total' => 'required',
                'unit_type_id' => 'required',
                'finishing' => 'required',
                'ar_description' => 'required',
                'en_description' => 'required',
                'ar_title' => 'required',
                'en_title' => 'required',
                'ar_address' => 'required',
                'en_address' => 'required',
                'phone' => 'required',
                'area' => 'required',
                'price' => 'required',
                'rooms' => 'required',
                'bathrooms' => 'required',
                'lng' => 'required',
                'lat' => 'required',
                'due_now' => 'required|numeric',
                'zoom' => 'required',
                'payment_method' => 'required',
                'view' => 'required',
                'lead_id' => 'required',
            ];
        } else {
            $rules = [
                'type' => 'required',
                'total' => 'required',
                'finishing' => 'required',
                'ar_description' => 'required',
                'en_description' => 'required',
                'ar_title' => 'required',
                'en_title' => 'required',
                'ar_address' => 'required',
                'en_address' => 'required',
                'phone' => 'required',
                'due_now' => 'required|numeric',
                'area' => 'required',
                'price' => 'required',
                'lng' => 'required',
                'lat' => 'required',
                'zoom' => 'required',
                'payment_method' => 'required',
                'view' => 'required',
                'lead_id' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'type' => trans('admin.type'),
            'total' => trans('admin.total'),
            'finishing' => trans('admin.finishing'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'ar_title' => trans('admin.ar_title'),
            'en_title' => trans('admin.en_title'),
            'ar_address' => trans('admin.ar_address'),
            'en_address' => trans('admin.en_address'),
            'phone' => trans('admin.phone'),
            'area' => trans('admin.area'),
            'price' => trans('admin.price'),
            'rooms' => trans('admin.rooms'),
            'bathrooms' => trans('admin.bathrooms'),
            'lng' => trans('admin.lng'),
            'lat' => trans('admin.lat'),
            'zoom' => trans('admin.zoom'),
            'due_now' => trans('admin.due_now'),
            'payment_method' => trans('admin.payment_method'),
            'view' => trans('admin.view'),
            'lead_id' => trans('admin.lead'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($resaleUnit);
            $resaleUnit->type = $request->type;
            $resaleUnit->unit_type_id = $request->unit_type_id;
            $resaleUnit->project_id = $request->project_id;
            $resaleUnit->lead_id = $request->lead_id;
            $resaleUnit->original_price = $request->original_price;
            $resaleUnit->payed = $request->payed;
            $resaleUnit->rest = $request->rest;
            $resaleUnit->total = $request->total;
            $resaleUnit->delivery_date = $request->delivery_date;
            $resaleUnit->finishing = $request->finishing;
            $resaleUnit->location = $request->location;
            $resaleUnit->ar_notes = $request->ar_notes;
            $resaleUnit->en_notes = $request->en_notes;
            $resaleUnit->ar_description = $request->ar_description;
            $resaleUnit->en_description = $request->en_description;
            $resaleUnit->ar_title = $request->ar_title;
            $resaleUnit->en_title = $request->en_title;
            $resaleUnit->due_now = $request->due_now;
            $resaleUnit->ar_address = $request->ar_address;
            $resaleUnit->en_address = $request->en_address;
            $resaleUnit->youtube_link = $request->youtube_link;
            $resaleUnit->phone = $request->phone;
            $resaleUnit->featured = $request->featured;
            $resaleUnit->meta_keywords = $request->meta_keywords;
            $resaleUnit->meta_description = $request->meta_description;
            $resaleUnit->priority = 0;
            if ($request->has('other_phones')) {
                $resaleUnit->other_phones = json_encode($request->other_phones);
            } else {
                $resaleUnit->other_phones = '[]';
            }
            $resaleUnit->area = $request->area;
            $resaleUnit->price = $request->price;
            $resaleUnit->rooms = $request->rooms;
            $resaleUnit->bathrooms = $request->bathrooms;
            $resaleUnit->floors = $request->floors;
            $resaleUnit->lng = $request->lng;
            $resaleUnit->lat = $request->lat;
            $resaleUnit->zoom = $request->zoom;

            $set = Setting::first();
            if ($request->hasFile('image')) {
                $resaleUnit->image = upload($request->image, 'resale_unit');
                $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                $image = Image::make('uploads/'.$resaleUnit->image);
                $image->insert($watermark, 'bottom-right', 10, 10);
                $image->save("uploads/resale_unit/watermarked_resale".rand(0,99999999999).".jpg");
                $resaleUnit->watermarked_image = $watermarked = $image->dirname.'/'.$image->basename;
            }
            $resaleUnit->payment_method = $request->payment_method;
            $resaleUnit->view = $request->view;
            $resaleUnit->availability = 'available';
            $resaleUnit->agent_id = $request->agent_id;
            $resaleUnit->user_id = auth()->user()->id;
            $resaleUnit->save();

            $new_data = json_encode($resaleUnit);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $resaleUnit->ar_title,
                __('admin.updated', [], 'en') . ' ' . $resaleUnit->en_title,
                'resale_units',
                $resaleUnit->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
			$fa1 = UnitFacility::where('unit_id', $resaleUnit->id)->where('type', 'resale')->pluck('id');
			$p = new ProjectController();

			foreach (UnitFacility::where('unit_id', $resaleUnit->id)->where('type', 'resale')->get() as $f) {
				$f->delete();
			}
            if($request->facility!=null)
			foreach ($request->facility as $facility) {
				$p->addfacility($resaleUnit->id,$facility,'resale');
			}
            if ($request->has('other_images')) {
                foreach ($request->other_images as $img) {
                    $other_image_model = new ResalImage;
                    $other_image = upload($img, 'resale_unit');
                    $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                    $image = Image::make('uploads/'.$other_image);
                    $image->insert($watermark, 'bottom-right', 10, 10);
                    $image->save("uploads/resale_unit/other_watermarked_resale".rand(0,99999999999).".jpg");
                    $other_watermarked_images = $image->dirname.'/'.$image->basename;
                    $other_image_model->unit_id = $resaleUnit->id;
                    $other_image_model->image = $other_image;
                    $other_image_model->watermarked_image = $other_watermarked_images;
                    $other_image_model->save();
                }
            }
            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/resale_units/' . $resaleUnit->id);
        }
    }

    public function destroy(ResaleUnit $resaleUnit)
    {
        if (checkRole('delete_resale_units') or @auth()->user()->type == 'admin') {
            $old_data = json_encode($resaleUnit);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $resaleUnit->ar_title,
                __('admin.deleted', [], 'en') . ' ' . $resaleUnit->en_title,
                'resale_units',
                $resaleUnit->id,
                'delete',
                auth()->user()->id,
                $old_data
            );
            $resaleUnit->delete();
            session()->flash('success', trans('admin.deleted'));
    		UnitFacility::where('unit_id',$resaleUnit->id)->where('type','rental')->delete();
    		return redirect(adminPath() . '/resale_units');
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function reorder()
    {
        $units = ResaleUnit::where('featured', 1)->orderBy('priority')->get();
        $projects = Project::where('featured', 1)->orderBy('priority')->get();
        return view('admin.resale_units.reorder_units', ['title' => __('admin.reorder'), 'units' => $units, 'projects' => $projects]);
    }

    public function reorder_post(Request $request)
    {
        $i = 1;
        foreach ($request->order as $order){
            $unit = ResaleUnit::find($order);
            $unit->priority = $i;
            $unit->save();
            $i++;
        }
        return 'true';
    }

    public function reorder_projects(Request $request)
    {
        $i = 1;
        foreach ($request->order as $order){
            $project = Project::find($order);
            $project->priority = $i;
            $project->save();
            $i++;
        }
        return 'true';
    }

    public function delete_resale_image(Request $request)
    {
        $img = ResalImage::find($request->id);
        @unlink('uploads/'.$img->image);
        @unlink($img->watermarked_image);
        $img->delete();
        return response()->json([
            'status' => true,
        ]);
    }

    public function website_show($id){
		$array = explode('-', $id);
		$id = end($array);
    	$resale = ResaleUnit::find($id);
		$home = new HomeController();
		$search = $home->search_info();
		$project = new ProjectController;
		$featured = $project->featured_project();
		$keyword = $resale->meta_keywords;
		$description = $resale->description;
		return view('website.resale', ['resale' => $resale,'keyword'=>$keyword ,'search'=>$search,'description'=>$description ,'featured'=>$featured,'title'=> $resale->{app()->getLocale().'_title'}]);
	}
    public function confirm($id){
        $unit = ResaleUnit::find($id);
        $unit->confirmed = 0;
        $unit->save();
        return back();
    }
}
