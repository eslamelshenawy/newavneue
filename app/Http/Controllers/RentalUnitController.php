<?php

namespace App\Http\Controllers;

use App\Facility;
use App\Group;
use App\GroupMember;
use App\UnitFacility;
use Image;
use App\Setting;
use App\RentalImage;
use App\RentalUnit;
use Illuminate\Http\Request;

use Validator;

class RentalUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (checkRole('show_rental_units') or @auth()->user()->type == 'admin') {
            $units = RentalUnit::all();
            $rentalUnit = [];
            foreach ($units as $unit) {
                if ($unit->agent_id == auth()->id()) {
                    $rentalUnit[] = $unit;
                } else {
                    if ($unit->privacy == 'only_me' and $unit->agent_id == auth()->id()) {
                        $rentalUnit[] = $unit;
                    } else if ($unit->privacy == 'public') {
                        $rentalUnit[] = $unit;
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
                            $rentalUnit[] = $unit;
                        }
                    } else if ($unit->privacy == 'custom') {
                        $agents = @json_decode($unit->custom_agents);
                        if (is_array($agents)) {
                            if (in_array(auth()->id(), $agents)) {
                                $rentalUnit[] = $unit;
                            }
                        }
                    }
                }
            }
            return view('admin.rental.index', ['title' => trans('admin.all') . ' ' . trans('admin.rental_unites'), 'property' => $rentalUnit]);
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
        if (checkRole('add_rental_units') or @auth()->user()->type == 'admin') {
            return view('admin.rental.create', ['title' => trans('admin.add') . ' ' . trans('admin.rental_unites')]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
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
            'ar_title' => 'required',
            'en_title' => 'required',
            'ar_description' => 'required',
            'en_description' => 'required',
            'lead_id' => 'required',
            'phone' => 'required',
            'type_id' => 'required',
            'area' => 'required',
            'rooms' => 'required',
            'delivery_date' => 'required',
            'rent' => 'required',
            'en_address' => 'required',
            'ar_address' => 'required',
            'image' => 'required',
			'facility' => 'required',
            'privacy' => 'required',
            'agent_id' => 'required',
        ];

        if ($request->privacy == 'custom') {
            $rules['agents'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title' => trans('admin.title'),
            'en_title' => trans('admin.title'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'lead_id' => trans('admin.lead'),
            'phone' => trans('admin.phone'),
            'type_id' => trans('admin.type'),
            'area' => trans('admin.area'),
            'rooms' => trans('admin.rooms'),
            'delivery_date' => trans('admin.date'),
            'rent' => trans('admin.rent'),
            'address_ar' => trans('admin.address'),
            'address_en' => trans('admin.address'),
            'image' => trans('admin.image'),
			'facility' => trans('admin.facility'),
            'privacy' => trans('admin.privacy'),
            'agents' => trans('admin.agents'),
            'agent_id' => trans('admin.agent'),
		]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $rental_unite = new RentalUnit;
            $rental_unite->type = $request->usage;
            $rental_unite->unit_type_id = $request->type_id;
            $rental_unite->project_id = $request->project_id;
            $rental_unite->lead_id = $request->lead_id;
            $rental_unite->lead_id = $request->lead_id;
            $rental_unite->rent = $request->rent;
            $rental_unite->delivery_date = $request->delivery_date;
            $rental_unite->finishing = $request->finishing;
            $rental_unite->location = $request->location;
            $rental_unite->ar_description = $request->ar_description;
            $rental_unite->en_description = $request->en_description;
            $rental_unite->ar_title = $request->ar_title;
            $rental_unite->en_title = $request->en_title;
            $rental_unite->ar_address = $request->ar_address;
            $rental_unite->en_address = $request->en_address;
            $rental_unite->en_address = $request->en_address;
            $rental_unite->phone = $request->phone;
            $rental_unite->area = $request->area;
            $rental_unite->view = $request->view;
            $rental_unite->rooms = $request->rooms;
            $rental_unite->meta_keywords = $request->meta_keywords;
            $rental_unite->meta_description = $request->meta_description;
            $rental_unite->bathrooms = $request->bathrooms;
            if ($request->has('other_phones')) {
                $rental_unite->other_phones = json_encode($request->other_phones);
            }else{
                $rental_unite->other_phones = '[]';
            }
            $rental_unite->floors = $request->floor;
            $rental_unite->availability = 'available';
            $rental_unite->lng = $request->lng;
            $rental_unite->lat = $request->lat;
            $rental_unite->zoom = $request->zoom;
            $rental_unite->user_id = auth()->user()->id;

            $rental_unite->privacy = $request->privacy;
            $rental_unite->agent_id = $request->agent_id;
            if ($request->privacy == 'custom') {
                $rental_unite->custom_agents = json_encode($request->agents);
            }


            $set = Setting::first();
            if ($request->hasFile('image')) {
                $rental_unite->image = upload($request->image, 'rental_unit');
                $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                $image = Image::make('uploads/'.$rental_unite->image);
                $image->insert($watermark, 'bottom-right', 10, 10);
                $image->save("uploads/rental_unit/watermarked_resale".rand(0,99999999999).".jpg");
                $rental_unite->watermarked_image = $watermarked = $image->dirname.'/'.$image->basename;
            }

//            $rental_unite->image = $request->file('image')->store('units');

            $rental_unite->save();

            $old_data = json_encode($rental_unite);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $rental_unite->ar_title,
                __('admin.created', [], 'en') . ' ' . $rental_unite->en_title,
                'rental_units',
                $rental_unite->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            if ($request->has('images')) {
                foreach ($request->images as $img) {
                    $other_image_model = new RentalImage;
                    $other_image = upload($img, 'rental_unit');
                    $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                    $image = Image::make('uploads/'.$other_image);
                    $image->insert($watermark, 'bottom-right', 10, 10);
                    $image->save("uploads/rental_unit/other_watermarked_resale".rand(0,99999999999).".jpg");
                    $other_watermarked_images = $image->dirname.'/'.$image->basename;
                    $other_image_model->unit_id = $rental_unite->id;
                    $other_image_model->image = $other_image;
                    $other_image_model->watermarked_image = $other_watermarked_images;
                    $other_image_model->save();
                }
            }
			$project = new ProjectController();
			foreach ($request->facility as $facility) {

				$project->addfacility($rental_unite->id, $facility, 'rental');

			}
            return redirect(adminPath() . '/rental_units/' . $rental_unite->id);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RentalUnit $rentalUnit
     * @return \Illuminate\Http\Response
     */
    public function show(RentalUnit $rentalUnit)
    {
        if (checkRole('show_rental_units') or @auth()->user()->type == 'admin') {
            return view('admin.rental.show', ['unit' => $rentalUnit, 'title' => trans('admin.show') . ' ' . trans('admin.rental_unites')]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RentalUnit $rentalUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(RentalUnit $rentalUnit)
    {
        if (checkRole('edit_rental_units') or @auth()->user()->type == 'admin') {
    		$facilities = UnitFacility::where('type', 'resale')->where('unit_id', $rentalUnit->id)->pluck('facility_id')->toArray();
    		return view('admin.rental.edit', ['title' => trans('admin.show') . ' ' . trans('admin.rental_unit'), 'unit' => $rentalUnit,'facilities' => $facilities]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\RentalUnit $rentalUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RentalUnit $rentalUnit)
    {

        $rules = [
            'ar_title' => 'required',
            'en_title' => 'required',
            'ar_description' => 'required',
            'en_description' => 'required',
            'lead_id' => 'required',
            'phone' => 'required',
            'type_id' => 'required',
            'area' => 'required',
            'rooms' => 'required',
            'delivery_date' => 'required',
            'rent' => 'required',
            'en_address' => 'required',
            'ar_address' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title' => trans('admin.title'),
            'en_title' => trans('admin.title'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'lead_id' => trans('admin.lead'),
            'phone' => trans('admin.phone'),
            'type_id' => trans('admin.type'),
            'area' => trans('admin.area'),
            'rooms' => trans('admin.rooms'),
            'delivery_date' => trans('admin.date'),
            'rent' => trans('admin.rent'),
            'address_ar' => trans('admin.address'),
            'address_en' => trans('admin.address'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($rentalUnit);
            $rentalUnit->type = $request->usage;
            $rentalUnit->unit_type_id = $request->type_id;
            $rentalUnit->project_id = $request->project_id;
            $rentalUnit->lead_id = $request->lead_id;
            $rentalUnit->lead_id = $request->lead_id;
            $rentalUnit->rent = $request->rent;
            $rentalUnit->delivery_date = $request->delivery_date;
            $rentalUnit->finishing = $request->finishing;
            $rentalUnit->location = $request->location;
            $rentalUnit->ar_description = $request->ar_description;
            $rentalUnit->en_description = $request->en_description;
            $rentalUnit->ar_title = $request->ar_title;
            $rentalUnit->en_title = $request->en_title;
            $rentalUnit->ar_address = $request->ar_address;
            $rentalUnit->en_address = $request->en_address;
            $rentalUnit->en_address = $request->en_address;
            $rentalUnit->phone = $request->phone;
            $rentalUnit->area = $request->area;
            $rentalUnit->rooms = $request->rooms;
            $rentalUnit->view = $request->view;
            $rentalUnit->meta_keywords = $request->meta_keywords;
            $rentalUnit->meta_description = $request->meta_description;
            $rentalUnit->bathrooms = $request->bathrooms;
            if ($request->has('other_phones')) {
                $rentalUnit->other_phones = json_encode($request->other_phones);
            }else{
                $rentalUnit->other_phones = '[]';
            }
            $rentalUnit->floors = $request->floor;
            $rentalUnit->availability = 'available';
            $rentalUnit->lng = $request->lng;
            $rentalUnit->lat = $request->lat;
            $rentalUnit->zoom = $request->zoom;
            $rentalUnit->user_id = auth()->user()->id;
            $set = Setting::first();
            if ($request->hasFile('image')) {
                $rentalUnit->image = upload($request->image, 'rental_unit');
                $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                $image = Image::make('uploads/'.$rentalUnit->image);
                $image->insert($watermark, 'bottom-right', 10, 10);
                $image->save("uploads/rental_unit/watermarked_resale".rand(0,99999999999).".jpg");
                $rentalUnit->watermarked_image = $watermarked = $image->dirname.'/'.$image->basename;
            }

//            $rentalUnit->image = $request->file('image')->store('units');

            $rentalUnit->save();

            $new_data = json_encode($rentalUnit);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $rentalUnit->ar_title,
                __('admin.updated', [], 'en') . ' ' . $rentalUnit->en_title,
                'rental_units',
                $rentalUnit->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            if ($request->has('images')) {
                foreach ($request->images as $img) {
                    $other_image_model = new RentalImage;
                    $other_image = upload($img, 'rental_unit');
                    $watermark = Image::make('uploads/'.$set->watermark)->resize(50, 50);
                    $image = Image::make('uploads/'.$other_image);
                    $image->insert($watermark, 'bottom-right', 10, 10);
                    $image->save("uploads/rental_unit/other_watermarked_resale".rand(0,99999999999).".jpg");
                    $other_watermarked_images = $image->dirname.'/'.$image->basename;
                    $other_image_model->unit_id = $rentalUnit->id;
                    $other_image_model->image = $other_image;
                    $other_image_model->watermarked_image = $other_watermarked_images;
                    $other_image_model->save();
                }
            }
			$fa1 = UnitFacility::where('unit_id', $rentalUnit->id)->where('type', 'resale')->pluck('id');
			$p = new ProjectController();

			foreach (UnitFacility::where('unit_id', $rentalUnit->id)->where('type', 'resale')->get() as $f) {
				$f->delete();
			}
			foreach ($request->facility as $facility) {
				$p->addfacility($rentalUnit->id,$facility,'resale');
			}
            session()->flash('success', trans('admin.created'));
            return redirect(adminPath() . '/rental_units/' . $rentalUnit->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RentalUnit $rentalUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(RentalUnit $rentalUnit)
    {
        if (checkRole('delete_rental_units') or @auth()->user()->type == 'admin') {
            $old_data = json_encode($rentalUnit);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $rentalUnit->ar_title,
                __('admin.deleted', [], 'en') . ' ' . $rentalUnit->en_title,
                'rental_units',
                $rentalUnit->id,
                'delete',
                auth()->user()->id,
                $old_data
            );
            UnitFacility::where('unit_id',$rentalUnit->id)->where('type','rental')->delete();
            $rentalUnit->delete();
            return back();
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function delete_rental_image(Request $request)
    {
        $img = RentalImage::find($request->id);
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
		$home = new HomeController();
		$search = $home->search_info();
		$rental = RentalUnit::find($id);
		$project = new ProjectController;
		$featured = $project->featured_project();
		$keyword = $rental->meta_keywords;
		$description = $rental->description;
		return view('website.rental', ['rental' => $rental,'search'=>$search,'keyword'=>$keyword ,'description'=>$description ,'featured'=>$featured]);
	}
}
