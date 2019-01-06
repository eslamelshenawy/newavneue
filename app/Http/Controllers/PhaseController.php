<?php

namespace App\Http\Controllers;

use App\Phase;
use Illuminate\Http\Request;
use App\Phase_Facilities;
use Auth;
use Validator;
use App\Property;
use App\Property_images;
use App\Project;
use App\LayoutImage;
use App\PriceHistory;


class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (checkRole('show_phases') or @auth()->user()->type == 'admin') {
            $phase = Phase::all();
            return view('admin.phases.index', ['title' => trans('admin.all') . ' ' . trans('admin.phases'), 'phase' => $phase]);
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
    public function create($id)
    {
        if (checkRole('add_phases') or @auth()->user()->type == 'admin') {
            $project = Project::find($id);
            if ($project == null)
                return redirect('admin');
            return view('admin.phases.create', ['title' => trans('admin.phases'), 'project' => $project]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request

     * public function store(Request $request)
     * {
     * $phase_id = $this  kjjkk kn->store_phase($request);
     * $this->store_facility($phase_id, $request);
     * return redirect(adminPath() . '/phases/' . $phase_id);
     * }
     *
     * /**
     * Display the specified resource.
     *
     * @param  \App\Phase $phase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (checkRole('show_phases') or @auth()->user()->type == 'admin') {
            $phase = Phase::find($id);
            if ($phase == null)
                return redirect('admin');
            $property = Property::where('phase_id', $phase->id)->get();
            $facilities = Phase_Facilities::where('phase_id', $id)->get();
            return view('admin.phases.addproperty', ['title' => trans('admin.phase'), 'phase' => $phase, 'property' => $property
                , 'facilities' => $facilities]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Phase $phase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (checkRole('edit_phases') or @auth()->user()->type == 'admin') {
            $phase = Phase::find($id);
            return view('admin.phases.edit', ['title' => trans('admin.edit') . ' ' . trans('admin.phase'), 'data' => $phase]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Phase $phase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $phase = Phase::find($id);
        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
            'meter_price' => 'required|numeric',
            'area' => 'required|numeric',
            'facility' => 'required',
            'delivery_date' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.name'),
            'ar_name' => trans('admin.name'),
            'meter_price' => trans('admin.meter_price'),
            'area' => trans('admin.area'),
            'facility' => trans('admin.facility'),
            'delivery_date' => trans('admin.delivery_date')
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {

            $phase->en_name = $request->en_name;
            $phase->ar_name = $request->ar_name;
            $phase->en_description = $request->en_description;
            $phase->ar_description = $request->ar_description;
            $phase->meter_price = $request->meter_price;
            $phase->area = $request->area;
            $phase->delivery_date = $request->delivery_date;
            $phase->meta_keywords = $request->meta_keywords;
            $phase->meta_description = $request->meta_description;
            $phase->save();
            Phase_Facilities::where('phase_id', $phase->id)->delete();
            $this->store_facility($phase->id, $request);

            $old_data = json_encode($phase);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $phase->ar_name,
                __('admin.updated', [], 'en') . ' ' . $phase->en_name,
                'phases/show',
                $phase->id,
                'update',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath() . '/phases/show/' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Phase $phase
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        if (checkRole('delete_phases') or @auth()->user()->type == 'admin') {
            $phase = Phase::find($request->phase_id);
            if ($phase == null)
                return redirect('admin');
            $properties = Property::where('phase_id', $phase->id)->get();
            foreach ($properties as $property) {
                $images = Property_images::where('property_id', $property->id)->get();
                $layout = LayoutImage::where('property_id', $property->id)->get();
                foreach ($images as $image) {
                    // dd($image->images);
                    if(@$image->images && file_exists('uploads/' . $image->images)){
                        unlink('uploads/' . $image->images);
                    }
                }
                $pi = Property_images::where('property_id', $property->id);
                if($pi){
                    $pi->delete();
                }
                foreach ($layout as $image) {
                    // dd($image->images);
                    if(@$image->images && file_exists('uploads/' . $image->images)){
                        unlink('uploads/' . $image->images);
                    }
                }
                $lo = LayoutImage::where('property_id', $property->id);
                if($lo){
                    $lo->delete();
                }
                Property::destroy($property->id);
            }
            Phase::destroy($phase->id);
            session()->flash('success', trans('admin.deleted'));
            return back();
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }

    }

    public function store(Request $request)
    {
        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
            'meter_price' => 'required|numeric',
            'area' => 'required|numeric',
            'facility' => 'required',
            'project_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.name'),
            'ar_name' => trans('admin.name'),
            'meter_price' => trans('admin.meter_price'),
            'area' => trans('admin.area'),
            'facility' => trans('admin.facility'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->hasFile('logo')) {
                if ($request->hasFile('promo')) {
                    $phase = new Phase;
                    $phase->en_name = $request->en_name;
                    $phase->ar_name = $request->ar_name;
                    $phase->en_description = $request->en_description;
                    $phase->ar_description = $request->ar_description;
                    $phase->meter_price = $request->meter_price;
                    $phase->area = $request->area;
                    $phase->meta_keywords = $request->meta_keywords;
                    $phase->meta_description = $request->meta_description;
                    $phase->project_id = $request->project_id;
                    $phase->logo = uploads($request, 'logo');
                    $phase->promo = uploads($request, 'promo');
                    $phase->user_id = auth()->user()->id;
                    $phase->delivery_date = $request->delivery_date;
                    $phase->save();
                    $this->store_facility($phase->id, $request);
                } else {
                    return back()->withInput()->withErrors('uploaded invalid promo');
                }
            } else {
                return back()->withInput()->withErrors('uploaded invalid logo');
            }
        }
        return redirect(adminPath() . '/projects/' . $request->project_id);
    }

    private function store_facility($id, $request)
    {
        for ($i = 0; $i < count($request->facility); $i++) {
            $pf = new Phase_Facilities();
            $pf->phase_id = $id;
            $pf->facility_id = $request->facility[$i];
            $pf->save();
        };
    }

    private function property_item($index, $id, $request)
    {
        //dd($dd);
        $rules['code.' . $index] = 'required|max:191';
        $rules['en_name.' . $index] = 'required|max:191';
        $rules['ar_name.' . $index] = 'required|max:191';
        $rules['unit_id.' . $index] = 'required';
        $rules['start_price.' . $index] = 'required|numeric|min:0';
        $rules['meter_price.' . $index] = 'numeric|min:0';
        $rules['area_from.' . $index] = 'required|numeric|min:0';
        $rules['area_to.' . $index] = 'numeric';
        $rules['type.' . $index] = 'required';
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'code.' . $index => trans('admin.code'),
            'unit_id.' . $index => trans('admin.unit_type'),
            'start_price.' . $index => trans('admin.start_price'),
            'area_from.' . $index => trans('admin.area_from'),
            'area_to.' . $index => trans('admin.area_to'),
            'type.' . $index => trans('admin.type'),
        ]);
        if ($validator->fails()) {
            return $validator;
        } else {

            $property = new Property;
            $property->code = $request->code[$index];
            $property->phase_id = $id;
            $property->en_name = $request->en_name[$index];
            $property->ar_name = $request->ar_name[$index];
            $property->unit_id = $request->unit_id[$index];
            $property->start_price = $request->start_price[$index];
            $property->meter_price = $request->meter_price[$index];
            $property->area_from = $request->area_from[$index];
            $property->area_to = $request->area_to[$index];
            $property->en_description = $request->en_description[$index];
            $property->ar_description = $request->ar_description[$index];
            $property->type = $request->type[$index];
            if ($request->main != null) {
                $name = rand(0, 99999999999) . '.' . $request->main[$index]->getClientOriginalExtension();
                $request->main[$index]->move("uploads", $name);
                $property->main = $name;
            }
            $property->user_id = Auth::user()->id;
            $property->save();
            $price = new PriceHistory;
            $price->price = $request->start_price[$index];
            $price->property_id = $property->id;
            $price->save();

            if ($request->images != null) {
                if (count($request->images[$index]))
                    $this->store_images($index, $property->id, $request);
            }
            if ($request->layout != null) {
                if (count($request->layout[$index]))
                    $this->store_layout($index, $property->id, $request);
            }

            return 'done';
        }

    }

    public function store_property(Request $request)
    {
        if ($request->images != null) {
            $request->images = array_values($request->images);
            $request->layout = array_values($request->layout);
        }
        $id = $request->phase_id;
        $failed = 0;
        $failedmessage = '';
        for ($i = 0; $i < count($request->unit_id); $i++) {
            $validator = $this->property_item($i, $id, $request);
            if ($validator != 'done') {
                $failed++;
                $failedmessage = $validator;
                session()->flash('propertyErrors', $failed);
            } else session()->flash('propertySuccess', count($request->unit_id) - $failed);
        }
        if ($failed < 1)
            return back();
        return back()->withErrors($failedmessage);
    }

    private function store_images($index, $id, $request)
    {
        $files = $request->images[$index];
        //   dd($file)
        foreach ($files as $file) {
            $images = new Property_images;
            $rules = array('images' => 'required|image|mimes:jpeg,jpg,png');
            $validator = Validator::make(array('images' => $file), $rules);
            if ($validator->passes()) {
                $name = rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move("uploads", $name);
                $images->images = $name;
                $images->property_id = $id;
                $images->save();
            }

        }
    }

    private function store_layout($index, $id, $request)
    {
        $files = $request->layout[$index];
        //   dd($file)
        foreach ($files as $file) {
            $images = new LayoutImage;
            $rules = array('layout' => 'required|image|mimes:jpeg,jpg,png');
            $validator = Validator::make(array('layout' => $file), $rules);
            if ($validator->passes()) {
                $name = rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move("uploads", $name);
                $images->image = $name;
                $images->property_id = $id;
                $images->save();
            }

        }
    }

    public function website_show_phase($id)
    {
        $phase = Phase::find($id);
        $s = new HomeController();
        $search = $s->search_info();
        $project = new ProjectController;
        $featured = $project->featured_project();
        return view('website.phase', ['phase' => $phase, 'search' => $search, 'featured' => $featured]);
    }

}
