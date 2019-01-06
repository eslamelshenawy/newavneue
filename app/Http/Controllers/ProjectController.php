<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Gallery;
use App\Lead;
use App\LeadNotification;
use App\Mail\TestMail;
use App\Project;
use App\RecentViewed;
use Illuminate\Http\Request;
use App\Location;
use App\Developer;
use App\Project_Phases;
use App\Phase;
use Validator;
use Auth;
use App\Property;
use App\LayoutImage;
use App\Property_images;
use App\ProjectTag;
use App\Gallery as Gimages;
use App\MainSlider;
use App\UnitFacility;
use DB;
use Mail;
use Image;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (checkRole('show_projects') or @auth()->user()->type == 'admin') {
            $project = Project::get();
            return view('admin.projects.index', ['title' => trans('admin.projects'), 'project' => $project]);
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
        if (checkRole('add_projects') or @auth()->user()->type == 'admin') {
            $location = Location::where('parent_id', '=', 0)->select(app()->getLocale() . '_name as title', 'id', 'lat', 'lng', 'zoom')->get();
            return view('admin.projects.create', ['title' => trans('admin.project'), 'location' => $location]);
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
    private function store_facility($id, $request)
    {
        for ($i = 0; $i < count($request->tags); $i++) {
            $pt = new ProjectTag();
            $pt->phase_id = $id;
            $pt->facility_id = $request->facility[$i];
            $pt->save();
        };
    }

    public function store(Request $request)
    {
        $leads = Lead::select('email')->get();
        foreach ($leads as $lead) {
            if (filter_var($lead->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($lead->email)->queue(new TestMail(['test' => 'Happy new Year']));
            }
        }

        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
            'meter_price' => 'required|numeric',
            'area' => 'required|numeric',
            'area_to' => 'required|numeric',
            'lat' => 'required|numeric',
            'location_id' => 'required|numeric',
            'developer' => 'required|numeric',
            'logo' => 'required|image',
            'cover' => 'required|image',
            'map_marker' => 'required|image',
            'tags' => 'required',
            'facility' => 'required',
            'down_payment' => 'required|numeric',
            'ins_years' => 'required|numeric',
            'delivery_date' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'meter_price' => trans('admin.meter_price'),
            'area' => trans('admin.area'),
            'area_to' => trans('admin.area_to'),
            'lat' => trans('admin.map_location'),
            'developer' => trans('admin.developer'),
            'location_id' => trans('admin.location'),
            'logo' => trans('admin.logo'),
            'down_payment' => trans('admin.down_payment'),
            'ins_years' => trans('admin.ins_years'),
            'tags' => trans('admin.tags'),
            'cover' => trans('admin.cover'),
            'map_marker' => trans('admin.map_marker'),
            'facility' => trans('admin.facility'),
            'delivery_date' => trans('admin.delivery_date')
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->hasFile('logo')) {
                if ($request->hasFile('cover')) {
                    $project = new Project;
                    $project->en_name = $request->en_name;
                    $project->ar_name = $request->ar_name;
                    $project->en_description = $request->en_description;
                    $project->ar_description = $request->ar_description;
                    $project->meter_price = $request->meter_price;
                    $project->area = $request->area;
                    $project->area_to = $request->area_to;
                    $project->lat = $request->lat;
                    $project->lng = $request->lng;
                    $project->zoom = $request->zoom;
                    $project->developer_id = $request->developer;
                    $project->location_id = $request->location_id;
                    $project->down_payment = $request->down_payment;
                    $project->installment_year = $request->ins_years;
                    $project->featured = $request->featured;
                    $project->facebook = $request->facebook;
                    $project->vacation = $request->vacation;
                    $project->show_website = $request->show_website;
                    $project->type = $request->project_type;
                    $project->delivery_date = $request->delivery_date;
                    $project->logo = uploads($request, 'logo');
                    $project->cover = uploads($request, 'cover');
                    $project->website_cover = uploads($request, 'website_cover');
                    $project->meta_keywords = $request->meta_keywords;
                    $project->meta_description = $request->meta_description;
                    
                    $developerPDFs = [];
                    if ($request->hasFile('developer_pdf')) {
                        foreach($request->developer_pdf as $pdf) {
                            $developerPDFs[] = upload($pdf, 'developer_pdf');
                        }
                    }
                    $project->developer_pdf = json_encode($developerPDFs);
        
                    $brokerPDFs = [];
                    if ($request->hasFile('broker_pdf')) {
                        foreach($request->broker_pdf as $pdf) {
                            $brokerPDFs[] = upload($pdf, 'broker_pdf');
                        }
                    }
                    $project->broker_pdf = json_encode($brokerPDFs);
                    
                    if ($request->mobile == 'on') {
                        $project->mobile = 1;
                    } else {
                        $project->mobile = 0;
                    }
                    if ($request->hasFile('map_marker')) {
                        $project->map_marker = uploads($request, 'map_marker');
                    }
                    $project->user_id = Auth::user()->id;
                    if ($request->main_slider == 'on') {
                        $project->on_slider = 1;
                    }
                    $project->user_id = auth()->user()->id;

                    if ($request->main_slider == 'on') {
                        if ($request->has('project_slider')) {
                            $project->website_slider = uploads($request, 'project_slider');
                        }
                    }

                    $project->save();
                    $logo = Image::make('uploads/'.$project->logo)->resize(34,34);
                    $marker = Image::make('uploads/marker.png');
                    $marker->insert($logo,'margin-top',8,10);
                    $marker->save('uploads/marker/'.$project->id.'.png');
                    $project->map_marker ='marker/'.$project->id.'.png';
                    $project->save();
                    $old_data = json_encode($project);
                    LogController::add_log(
                        __('admin.created', [], 'ar') . ' ' . $project->ar_name,
                        __('admin.created', [], 'en') . ' ' . $project->en_name,
                        'projects',
                        $project->id,
                        'create',
                        auth()->user()->id,
                        $old_data
                    );


                    for ($i = 0; $i < count($request->tags); $i++) {
                        $tag = new ProjectTag;
                        $tag->tag_id = $request->tags[$i];
                        $tag->project_id = $project->id;
                        $tag->save();
                    }
                    if ($request->main_slider == 'on') {
                        $slider = new MainSlider();
                        $image = $request->project_slider;
                        $slider->image = $image->store('main_slider');
                        $slider->unit_id = $project->id;
                        $slider->type = $request->type;
                        $slider->save();
                    }

                    if ($request->has('gallery')) {
                        foreach ($request->gallery as $img) {
                            $gallery = new Gallery;
                            $gallery->image = $img->store('gallery');
                            $gallery->project_id = $project->id;
                            $gallery->save();
                        }
                    }
//                    dd($request->facility);
                    foreach ($request->facility as $facility) {
                        $this->addfacility($project->id, $facility, 'project');

                    }
                } else {
                    return back()->withInput()->withErrors('uploaded invalid logo');
                }

                $leads = Lead::where('refresh_token', '!=', '')->get();
                $tokens = Lead::where('refresh_token', '!=', '')->pluck('refresh_token')->toArray();
                foreach ($leads as $lead) {
                    $notify = new LeadNotification;
                    $notify->type = 'projects';
                    $notify->type_id = $project->id;
                    $notify->ar_title = __('admin.new_project', [], 'ar');
                    $notify->en_title = __('admin.new_project', [], 'en');
                    $notify->ar_body = $project->ar_name;
                    $notify->en_body = $project->en_name;
                    $notify->lead_id = $lead->id;
                    $notify->user_id = auth()->user()->id;
                    $notify->save();
                }
                $msg = array(
                    'title' => __('admin.new_project', [], 'en'),
                    'body' => $project->en_name,
                    'image' => 'myIcon',/*Default Icon*/
                    'sound' => 'mySound'/*Default sound*/
                );
//                dd($tokens);
                notify($tokens, $msg);

                return redirect(adminPath() . '/projects');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        if (checkRole('show_projects') or @auth()->user()->type == 'admin') {
            $location_id = $project->location_id;
            $full_location = "";
            while ($location_id != '0') {
                $location = Location::where('id', $location_id)->select(app()->getLocale() . '_name as title', 'parent_id')->first();

                $location_id = $location->parent_id;
                $full_location .= $location->title . ' -';
            }
            $phases = Project::join('phases', 'phases.project_id', 'projects.id')
                ->where('projects.id', $project->id)
                ->select('phases.en_name', 'phases.id')->get();
            return view('admin.projects.show', ['title' => trans('admin.project'), 'location' => trim($full_location, '-'), 'project' => $project, 'phases' => $phases]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        if (checkRole('edit_projects') or @auth()->user()->type == 'admin') {
            $location = Location::where('parent_id', '=', 0)->select(app()->getLocale() . '_name as title', 'id', 'lat', 'lng', 'zoom')->get();
            $facilities = UnitFacility::where('type', 'project')->where('unit_id', $project->id)->pluck('facility_id')->toArray();
            return view('admin.projects.edit', ['title' => trans('admin.project'), 'location' => $location, 'project' => $project, 'facilities' => $facilities]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $rules = [
            'en_name' => 'required|max:191',
            'ar_name' => 'required|max:191',
            'meter_price' => 'required|numeric',
            'area' => 'required|numeric',
            'area_to' => 'required|numeric',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'zoom' => 'required|numeric',
            'location_id' => 'required|numeric',
            'developer' => 'required|numeric',
            'tags' => 'required',
            'down_payment' => 'required|numeric',
            'ins_years' => 'required|numeric',
            'delivery_date' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'en_name' => trans('admin.en_name'),
            'ar_name' => trans('admin.ar_name'),
            'meter_price' => trans('admin.meter_price'),
            'area' => trans('admin.area'),
            'area_to' => trans('admin.area_to'),
            'lat' => trans('admin.lat'),
            'lng' => trans('admin.ang'),
            'zoom' => trans('admin.zoom'),
            'developer' => trans('admin.developer'),
            'location_id' => trans('admin.location'),
            'logo' => trans('admin.logo'),
            'tags' => trans('admin.tags'),
            'down_payment' => trans('admin.down_payment'),
            'ins_years' => trans('admin.ins_years'),
            'delivery_date' => trans('admin.delivery_date'),
        ]);
        $fa1 = UnitFacility::where('unit_id', $project->id)->where('type', 'project')->pluck('id');
        foreach (UnitFacility::where('unit_id', $project->id)->where('type', 'project')->get() as $f) {
            $f->delete();
        }
        if ($request->facility != null)
            foreach ($request->facility as $facility) {
                $this->addfacility($project->id, $facility, 'project');
            }
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($project);
            if ($request->has('logo')) {
                $file_path = 'uploads/' . $project->logo;
                @unlink($file_path);
                $project->logo = uploads($request, 'logo');
            }

            if ($request->has('cover')) {
                $file_path = 'uploads/' . $project->cover;
                @unlink($file_path);
                $project->cover = uploads($request, 'cover');
            }

            if ($request->has('map_marker')) {
                $file_path = 'uploads/' . $project->map_marker;
                @unlink($file_path);
                $project->map_marker = uploads($request, 'map_marker');
            }


            if ($request->has('website_cover')) {
                $file_path = 'uploads/' . $project->website_cover;
                @unlink($file_path);
                $project->website_cover = uploads($request, 'website_cover');
            } elseif ($request->has('old_website_cover')) {
                $project->website_cover = $request->old_website_cover;
            }
            $project->en_name = $request->en_name;
            $project->ar_name = $request->ar_name;
            $project->en_description = $request->en_description;
            $project->ar_description = $request->ar_description;
            $project->meter_price = $request->meter_price;
            $project->delivery_date = $request->delivery_date;
            $project->area = $request->area;
            $project->area_to = $request->area_to;
            $project->video = $request->video;
            $project->lat = $request->lat;
            $project->lng = $request->lng;
            $project->zoom = $request->zoom;
            $project->featured = $request->featured;
            $project->facebook = $request->facebook;
            $project->vacation = $request->vacation;
            $project->down_payment = $request->down_payment;
            $project->installment_year = $request->ins_years;
            $project->type = $request->project_type;
            $project->developer_id = $request->developer;
            $project->show_website = $request->show_website;
            $project->location_id = $request->location_id;
            $project->meta_keywords = $request->meta_keywords;
            $project->meta_description = $request->meta_description;
            $project->mobile = $request->mobile;
             if ($request->has('gallery')) {
                foreach ($request->gallery as $img) {
                    $gallery = new Gallery;
                    $gallery->image = upload($img, 'gallery');
                    $gallery->project_id = $project->id;
                    $gallery->save();
                }
            }
            
            if ($request->has('developer_pdf')) {
                $developerPDFs = [];
                if ($request->has('developer_pdf')) {
                    foreach($request->developer_pdf as $pdf) {
                        $developerPDFs[] = upload($pdf, 'developer_pdf');
                    }
                }
                dd($developerPDFs);
                $project->developer_pdf = json_encode($developerPDFs);
            }

            if ($request->has('developer_pdf')) {
                $brokerPDFs = [];
                if ($request->has('broker_pdf')) {
                    foreach($request->broker_pdf as $pdf) {
                        $brokerPDFs[] = upload($pdf, 'broker_pdf');
                    }
                }
                $project->broker_pdf = json_encode($brokerPDFs);
            }
            
            if ($request->main_slider == 'on') {
                $project->on_slider = 1;
            } else {
                $project->on_slider = 0;
            }

            $project->mobile = $request->mobile;

            if ($request->main_slider == 'on') {
                $project->on_slider = 1;
            } else {
                $project->on_slider = 0;
            }

            if ($request->main_slider == 'on') {
                if ($request->has('project_slider')) {
                    $project->website_slider = uploads($request, 'project_slider');
                }
            }

            $project->save();

            $new_data = json_encode($project);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $project->ar_name,
                __('admin.updated', [], 'en') . ' ' . $project->en_name,
                'projects',
                $project->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            ProjectTag::where('project_id', $project->id)->delete();
            for ($i = 0; $i < count($request->tags); $i++) {
                $tag = new ProjectTag;
                $tag->tag_id = $request->tags[$i];
                $tag->project_id = $project->id;
                $tag->save();
            }

            return redirect(adminPath() . '/projects');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if (checkRole('delete_projects') or @auth()->user()->type == 'admin') {
            $phases = Phase::where('project_id', $project->id)->get();
            if ($phases == null)
                return redirect('admin');
            foreach ($phases as $phase) {
                $properties = Property::where('phase_id', $phase->id)->get();
                foreach ($properties as $property) {
                    $images = Property_images::where('property_id', $property->id)->get();
                    $layout = LayoutImage::where('property_id', $property->id)->get();
                    foreach ($images as $image) {
                        // dd($image->images);
                        @unlink('uploads/' . $image->images);
                    }
                    Property_images::where('property_id', $property->id)->delete();
                    foreach ($layout as $image) {
                        // dd($image->images);
                        @unlink('uploads/' . $image->image);
                        $image->delete();
                    }
                    $property->delete();
                }
                $phase->delete();
                Favorite::where('unit_id', $project->id)->where('type', 'project')->delete();
                RecentViewed::where('unit_id', $project->id)->where('type', 'project')->delete();
                UnitFacility::where('unit_id', $project->id)->where('type', 'project')->delete();
            }
            $images = Gimages::where('project_id', '=', $project->id)->get();
            foreach ($images as $image) {
                @unlink('uploads/' . $image->image);
                $image->delete();
            }
            ProjectTag::where('project_id', $project->id)->delete();

            $old_data = json_encode($project);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $project->ar_name,
                __('admin.deleted', [], 'en') . ' ' . $project->en_name,
                'projects',
                $project->id,
                'delete',
                auth()->user()->id,
                $old_data
            );

            $project->delete();
            session()->flash('success', trans('admin.deleted'));
            return back();
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function project_featured($id)
    {
        $p = Project::find($id);
        $p->featured = 1;
        $p->save();
        return back();
    }

    public function project_un_featured($id)
    {
        $p = Project::find($id);
        $p->featured = 0;
        $p->save();
        return back();
    }

    public function website_show($id)
    {
        $array = explode('-', $id);
        $id = end($array);
        $project = Project::find($id);
        $home = new HomeController();
        $search = $home->search_info();
        $project_feat = new ProjectController;
        $featured = $project_feat->featured_project();
        $tags = DB::table('project_tags')->selectRaw('project_id,count(*) as count')->groupBy('project_id')->orderBy('count', 'DESC')->limit(5)->whereIn('tag_id', [1, 2, 3])->where('project_id', '!=', $id)->get();
        $share = url('project/' . $project->id);
        $keyword = $project->meta_keywords;
        $description = $project->description;
        return view('website.project', ['project' => $project, 'keyword' => $keyword, 'description' => $description, 'tags' => $tags, 'search' => $search, 'title' => $project->{app()->getLocale() . '_name'}, 'featured' => $featured, 'share_description' => $project->meta_description]);

    }

    public function addfacility($unit_id, $facility_id, $type)
    {
        $facility = new UnitFacility();
        $facility->unit_id = $unit_id;
        $facility->facility_id = $facility_id;
        $facility->type = $type;
        $facility->save();
    }

    public function featured_project()
    {
        $featured = Project::where('featured', 1)->orderby('priority')->limit(3)->get();
        return $featured;
    }

    public function get_developer_projects(Request $request)
    {
//        dd($request->all());
        $projects = new Project();
        if($request->min_price){
//            dump('1');
            $projects = $projects->where('meter_price','>=',$request->min_price);
        }
        if($request->max_price ){
//            dump('2');
            $projects = $projects->where('meter_price','<=',$request->max_price);
        }

        if($request->max_area){
//            dump('3');
            $projects = $projects->where('area_to','<=',$request->max_area);
        }
//        dd('test');
        if($request->min_area){
//            dump('4');
            $projects = $projects->where('area','>=',$request->min_area);
        }
        if($request->max_down_payment){
//            dump('5');
            $projects = $projects->where('down_payment','<=',$request->max_down_payment);
        }
        if($request->min_down_payment){
//            dump('6');
            $projects = $projects->where('down_payment','>=',$request->min_down_payment);
        }
        if($request->installment){
//            dump('7');
            $projects = $projects->where('installment_year',$request->installment);
        }
        if ($request->dev != 'all' && $request->dev) {
//            dump('8');
            $projects = $projects->where('developer_id', $request->dev);
        }
        if ($request->location != 'all' && $request->location) {
//            dump('9');
            $projects = $projects->where('location_id', $request->location);
        }
        $projects = $projects->get();
//        dd($projects);
        return view('admin.projects.get_projects', ['projects' => $projects]);
    }
    public function sort_project(){
        $featured_projects = Project::where('show_website',1)->orderby('featured_priority')->get();
        $mobile_projects = Project::where('mobile',1)->orderby('mobile_priority')->get();
        return view('admin.sort_project',compact('featured_projects','mobile_projects'));
    }
    public function save_sorted(Request $request){
        $i = 0;
        foreach($request->projects as $project){
            $i++;
            $current = Project::find($project);
            $current->featured_priority = $i;
            $current->save();
        }
    }
    public function save_sorted_mob(Request $request){
        $i = 0;
        foreach($request->projects as $project){
            $i++;
            $current = Project::find($project);
            $current->mobile_priority = $i;
            $current->save();
        }
    }
    public function change_markers(){
        $projects = Project::all();
        foreach ($projects as $project){
            if (file_exists('uploads/'.$project->logo)){
            $logo = Image::make('uploads/'.@$project->logo)->resize(34,34);
            $marker = Image::make('uploads/marker.png');
            $marker->insert($logo,'margin-top',8,10);
            $marker->save('uploads/marker/'.$project->id.'.png');
            $project->map_marker ='marker/'.$project->id.'.png';
            $project->save();
            }
        }
        return redirect(adminPath().'/projects');
    }
    
    public function image_post(Request $request)
    {
        Gallery::find($request->id)->delete();
        return ['status'=>'ok'];
    }
}
