<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '128MB');

use App\Developer;
use App\Facility;
use App\Gallery;
use App\Icon;
use App\Location;
use App\Phase;
use App\Project;
use App\UnitType;
use FacebookAds\Session;
use Illuminate\Http\Request;
use App\ProjectRequest;
use Illuminate\Support\Facades\Auth;
use Validator;
use Image;
use App\Property;
use App\UnitFacility;
class ReceivedprojectController extends Controller
{
    public $url = "http://propertzcrm.com";

    public function receive(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|max:191',
            'location' => 'required|max:191',
            'developer' => 'required|max:191',
        ];
        $validator = Validator::make(array(
            'id' => $request->id,
            'name' => $request->name,
            'location' => $request->location,
            'developer' => $request->developer,
        ), $rules);

        if ($validator->fails()) {
            return ['status' => 'error'];
        } else {
            if (ProjectRequest::where('project_id', (int)$request->id)->count() > 0)
                return ['status' => 'found'];
            $project = new ProjectRequest();
            $project->name = $request->name;
            $project->project_id = $request->id;
            $project->location = $request->location;
            $project->developer = $request->developer;
            $project->portal_updated_at = strtotime($request->updated_at);
            $project->save();
            return ['status' => 'success'];
        }
    }

    public function push_project(Request $request)
    {

    }

    public function get_notification()
    {
        return view('admin.notification.get_note', ['title' => 'Notification']);
    }

    public function accept($id)
    {
        $url = $this->url . '/api/get_project';
        // dd($url);// http://propertzcrm.com/api/get_project"
        $ch = curl_init();
        $re = ProjectRequest::find($id);

        $data = array('project_id' => @$re->project_id, 'token' => '$2y$10$aJOEOQOg86C3/zRki8uMK.npVan9iGDUty0r4YSmu8oFhdEtUjWhO');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output);

        if (isset($data->status)) {
            if ($data->status == 'unauthorized') {
                session()->flash('error', 'You are not subscribers with this service');
                return back();
            }
        }
        
        if (@Project::where('portal_id', @$data->projects->id)->count() == 0) {
            session()->flash('success', 'project has been pushed');
            foreach ($data->icons as $row) {
                if (Icon::where('portal_id', $row->id)->count() == 0) {
                    $icon = new Icon();
                    $filename = $this->url . '/uploads/' . $row->icon;
                    if ($row->icon != null || $row->icon != '') {
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = "icon/" . rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $icon->icon = $name;
                        $icon->portal_id = $row->id;
                        $icon->save();
                    }
                }
            }

            if ($data->developer) {
                $row = $data->developer;
                if (Developer::where('portal_id', $row->id)->count() == 0) {
//                dd( $row);
                    $developer = new Developer();
                    $developer->en_name = $row->en_name;
                    $developer->ar_name = $row->ar_name;
                    $developer->en_description = $row->en_description;
                    $developer->ar_description = $row->ar_description;
                    $developer->phone = $row->phone;
                    $developer->email = $row->email;
                    $developer->facebook = $row->facebook;
                    $filename = $this->url . '/uploads/' . $row->logo;
                // dd($filename);
                    if ($row->logo != null || $row->logo != '') {
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $developer->logo = $row->logo;
                    }
                    $developer->portal_id = $row->id;
                    $developer->save();
                }
            }

            if ($data->location) {
                $row = $data->location;
                if (Location::where('portal_id', $row->id)->count() == 0) {
                    $location = new Location();
                    $location->en_name = $row->en_name;
                    $location->ar_name = $row->ar_name;
                    $location->lat = $row->lat;
                    $location->lng = $row->lng;
                    $location->zoom = $row->zoom;
                    $location->parent_id = 0;
                    $location->portal_id = $row->id;
                    $location->save();
                }
                $row = $data->projects;

                $project = new Project();
                $project->en_name = $row->en_name;
                $project->ar_name = $row->ar_name;
                $project->en_description = $row->en_description;
                $project->ar_description = $row->ar_description;
                $project->area = $row->area;
                $project->lat = $row->lat;
                $project->lng = $row->lng;
                $project->zoom = $row->zoom;
                $project->developer_id = @Developer::where('portal_id', $data->developer->id)->first()->id;
        // dd($data->location->id);
                $project->location_id = @Location::where('portal_id', $data->location->id)->first()->id;
                $project->down_payment = $row->down_payment;
                $project->installment_year = $row->installment_year;
                $project->commission = $row->commission;
                $project->video = $row->video;
                $project->priority = $row->priority;
                $project->user_id = 0;
                $project->type = $row->type;
                $project->meter_price = $row->meter_price;


                if ($row->map_marker != null || $row->map_marker != '') {
                    $filename = $this->url . '/uploads/' . $row->map_marker;
                    $image = Image::make($filename);
                    $ext = explode('.', $filename);
                    $name = rand(0, 99999999999) . end($ext);
                    $image->save("uploads/" . $name);
                    $project->map_marker = $name;
//                dd($project->map_marker);
                }
                
                
                if ($row->developer_pdf != '[]') {
                    $pdfs = json_decode($row->developer_pdf);
                    $pdfArr = [];
                    if($pdfs){
                    foreach($pdfs as $pdf) {
                        $file = $this->url . '/uploads/' . $pdf;
                        $ext = explode('.', $pdf);
                        $fileName = rand(0, 99999999999) . '.' . end($ext);
                        $newfile = public_path('uploads/' . $fileName);
                        
                        if (@copy($file, $newfile) ) {
                            $pdfArr[] = $fileName;
                        }
                    }
                }
                    $project->developer_pdf = json_encode($pdfArr);
                }
                

                if ($row->broker_pdf != '[]') {
                    $pdfs = json_decode($row->broker_pdf);
                    $pdfArr = [];
                    if($pdfs){
                        foreach($pdfs as $pdf) {
                            $file = $this->url . '/uploads/' . $pdf;
                            $ext = explode('.', $pdf);
                            $fileName = rand(0, 99999999999) . '.' . end($ext);
                            $newfile = public_path('uploads/' . $fileName);
                            if (@copy($file, $newfile) ) {
                                $pdfArr[] = $fileName;
                            }
                        } 
                    }
                    $project->broker_pdf = json_encode($pdfArr);
                }


//                 if ($row->developer_pdf != '[]') {
//                     $pdfs = json_decode($row->developer_pdf);
//                     $pdfsArr = [];
//                     foreach($pdfs as $pdf) {
//                         $filename = $this->url . '/uploads/' . $pdf;
//                         $image = Image::make($filename);
//                         $ext = explode('.', $filename);
//                         $name = rand(0, 99999999999) . end($ext);
//                         $image->save("uploads/" . $name);
//                         $pdfsArr[] = $name;
//                     }
//                     $project->developer_pdf = json_encode($pdfsArr);
// //                dd($project->map_marker);
//                 }

//                 if ($row->broker_pdf != '[]') {
//                     $pdfs = json_decode($row->broker_pdf);
//                     $pdfsArr = [];
//                     foreach($pdfs as $pdf) {
//                         $filename = $this->url . '/uploads/' . $pdf;
//                         $image = Image::make($filename);
//                         $ext = explode('.', $filename);
//                         $name = rand(0, 99999999999) . end($ext);
//                         $image->save("uploads/" . $name);
//                         $pdfsArr[] = $name;
//                     }
//                     $project->broker_pdf = json_encode($pdfsArr);
// //                dd($project->map_marker);
//                 }
                
                if ($row->logo != null || $row->logo != '') {
                    $filename = $this->url . '/uploads/' . $row->logo;
                    
                    // $image = Image::make($filename);
                    // dd(  __DIR__);
                    $ext = explode('.', $filename);
                    $name = rand(0, 99999999999) .'.'. end($ext);
                    file_put_contents(__DIR__ ."/../../../public/uploads/" . $name, file_get_contents($filename));
                    // $image->save("/public/uploads/" . $name);
                    $project->logo = $name;
                }

                if ($row->cover != null || $row->cover != '') {
                    $filename = $this->url . '/uploads/' . $row->cover;
                    $image = Image::make($filename);
                    $ext = explode('.', $filename);
                    $name = rand(0, 99999999999) . '.'. end($ext);
                    file_put_contents(__DIR__ ."/../../../public/uploads/" . $name, file_get_contents($filename));
                    // $image->save("uploads/" . $name);
                    $project->cover = $name;
                }
            // dd('sheno');
                $project->portal_id = $row->id;
                $project->delivery_date = $row->delivery_date;
                $project->save();

                foreach ($data->facilities as $row) {
                    $fc_id = $row->id;
                    if (Facility::where('portal_id', $row->id)->count() == 0) {
                        $facility = new Facility();
                        $icon = Icon::where('portal_id')->first();
                        $facility->en_name = $row->en_name;
                        $facility->ar_name = $row->ar_name;
                        $facility->en_description = $row->en_description;
                        $facility->ar_description = $row->ar_description;
                        $facility->icon = $icon->id;
                        $facility->portal_id = $row->id;
                        $facility->save();
                        $fc_id = $facility->id;
                    }
                    if (UnitFacility::where('unit_id', $project->id)->where('facility_id', $fc_id)->where('type', 'project')->count() == 0) {
                        $facility1 = new UnitFacility();
                        $facility1->unit_id = $project->id;
                        $facility1->facility_id = $fc_id;
                        $facility1->type = 'project';
                        $facility1->save();
                    }
                }

                foreach ($data->gallery as $row) {
                    $gallery = new Gallery();
                    if ($row->image != null || $row->image != '') {
                        $filename = $this->url . '/uploads/' . $row->image;
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = 'gallery/' . rand(0, 9999999999966) . end($ext);
                        $image->save("uploads/" . $name);
                        $gallery->image = $name;
                        $gallery->project_id = $project->id;
                        $gallery->portal_id = $row->id;
                        $gallery->save();
                    }
                }

                foreach ($data->phase as $row) {
                    if (Phase::where('portal_id', $row->id)->count() == 0) {
                        $phase = new Phase();
                        $phase->en_name = $row->en_name;
                        $phase->ar_name = $row->ar_name;
                        $phase->en_description = $row->en_description;
                        $phase->ar_description = $row->ar_description;
                        $phase->meter_price = $row->meter_price;
                        $phase->area = $row->area;
                        $filename = $this->url . '/uploads/' . $row->logo;
                        if ($row->logo != null || $row->logo != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $project->logo = $name;
                        }

                        $filename = $this->url . '/uploads/' . $row->promo;
                        if ($row->promo != null || $row->promo != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $project->promo = $name;
                        }
//                    $phase->promo=$row->promo;
                        $phase->delivery_date = $row->delivery_date;
                        $phase->project_id = $project->id;
                        $phase->user_id = Auth::user()->id;
                        $phase->portal_id = $row->id;
                        $phase->save();
                    }
                }

                //////////////////////

                foreach ($data->units as $row) {
                    if (UnitType::where('portal_id', $row->type->id)->count() == 0) {
                        $temp = $row->type;
                        $unit_type = new UnitType();
                        $unit_type->en_name = $temp->en_name;
                        $unit_type->ar_name = $temp->ar_name;
                        $unit_type->description = $temp->description;
                        $unit_type->usage = $temp->usage;
                        $unit_type->user_id = Auth::user()->id;
                        $unit_type->portal_id = $temp->id;
                        $unit_type->save();
                        $unit_type_id = $unit_type->id;
                    } else {
                        $unit_type_id = UnitType::where('portal_id', $row->type->id)->first()->id;
                    }

                    if (Property::where('portal_id', $row->unit->id)->count() == 0) {
                        $temp = $row->unit;
                        $unit = new Property();
                        $unit->en_name = $temp->en_name;
                        $unit->ar_name = $temp->ar_name;
                        $unit->en_description = $temp->en_description;
                        $unit->ar_description = $temp->ar_description;
                        $unit->code = $temp->code;
                        $unit->lead_id = 0;
                        $unit->start_price = $temp->start_price;
                        $unit->meter_price = $temp->meter_price;
                        $unit->area_from = $temp->area_from;
                        $unit->area_to = $temp->area_to;
                        $unit->type = $temp->type;
                        $unit->main = $temp->main;
                        $unit->availability = $temp->availability;
                        $unit->phase_id = Phase::where('portal_id', $temp->phase_id)->first()->id;
                        $unit->unit_id = $unit_type_id;

                        $filename = $this->url . '/uploads/' . $temp->main;
                        if ($temp->main != null || $temp->mai != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $unit->main = $name;
                        }
                        $unit->user_id = Auth::user()->id;
                        $unit->portal_id = $temp->id;
                        $unit->save();
                    }
                }
            } else {
                if ($data->developer) {
                    $row = $data->developer;
                    if (Developer::where('portal_id', $row->id)->count() == 0) {
//                dd( $row);
                        $developer = new Developer();
                    } else {
                        $developer = Developer::where('portal_id', $row->id)->first();
                    }
                    $developer->en_name = $row->en_name;
                    $developer->ar_name = $row->ar_name;
                    $developer->en_description = $row->en_description;
                    $developer->ar_description = $row->ar_description;
                    $developer->phone = $row->phone;
                    $developer->email = $row->email;
                    $developer->facebook = $row->facebook;
                    $filename = $this->url . '/uploads/' . $row->logo;
//                dd($filename);
                    if ($row->logo != null || $row->logo != '') {
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $developer->logo = $row->logo;
                    }
                    $developer->portal_id = $row->id;
                    $developer->save();


                }
                foreach ($data->icons as $row) {
                    if (Icon::where('portal_id', $row->id)->count() == 0) {
                        $icon = new Icon();
                    } else {
                        $icon = Icon::where('portal_id', $row->id)->first();
                    }
                    $filename = $this->url . '/uploads/' . $row->icon;
                    if ($row->icon != null || $row->icon != '') {
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = "icon/" . rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $icon->icon = $name;
                        $icon->portal_id = $row->id;
                        $icon->save();
                    }

                }
                if ($data->location) {
                    $row = $data->location;
                    if (Location::where('portal_id', $row->id)->count() == 0) {
                        $location = new Location();
                    } else {
                        $location = Location::where('portal_id', $row->id)->first();
                    }
                    $location->en_name = $row->en_name;
                    $location->ar_name = $row->ar_name;
                    $location->lat = $row->lat;
                    $location->lng = $row->lng;
                    $location->zoom = $row->zoom;
                    $location->parent_id = 0;
                    $location->portal_id = $row->id;
                    $location->save();

                    $row = $data->projects;

                }
                session()->flash('success', 'Project Found');
            }
            $re->delete();
        } else {
            session()->flash('success', 'Project has been updated');

            if (@$data->developer) {
                $row = $data->developer;
                if (Developer::where('portal_id', $row->id)->count() > 0) {
//                dd($row);
                    $developer = Developer::where('portal_id', $row->id)->first();
                    $developer->en_name = $row->en_name;
                    $developer->ar_name = $row->ar_name;
                    $developer->en_description = $row->en_description;
                    $developer->ar_description = $row->ar_description;
                    $developer->phone = $row->phone;
                    $developer->email = $row->email;
                    $developer->facebook = $row->facebook;
                    $filename = $this->url . '/uploads/' . $row->logo;
//                dd($filename);
                    if ($row->logo != null || $row->logo != '') {
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $developer->logo = $row->logo;
                    }
                    $developer->portal_id = $row->id;
                    $developer->save();
                }
            }

            if (@$data->location) {
                $row = $data->location;
                if (Location::where('portal_id', $row->id)->count() > 0) {
                    $location = Location::where('portal_id', $row->id)->first();
                    $location->en_name = $row->en_name;
                    $location->ar_name = $row->ar_name;
                    $location->lat = $row->lat;
                    $location->lng = $row->lng;
                    $location->zoom = $row->zoom;
                    $location->parent_id = 0;
                    $location->portal_id = $row->id;
                    $location->save();
                }
                $row = $data->projects;

                $project = Project::where('portal_id', $row->id)->first();
                $project->en_name = $row->en_name;
                $project->ar_name = $row->ar_name;
                $project->en_description = $row->en_description;
                $project->ar_description = $row->ar_description;
                $project->area = $row->area;
                $project->lat = $row->lat;
                $project->lng = $row->lng;
                $project->zoom = $row->zoom;
                $project->developer_id = @Developer::where('portal_id', $data->developer->id)->first()->id;
//        dd($data->location->id);
                $project->location_id = @Location::where('portal_id', $data->location->id)->first()->id;
                $project->down_payment = $row->down_payment;
                $project->installment_year = $row->installment_year;
                $project->commission = $row->commission;
                $project->video = $row->video;
                $project->priority = $row->priority;
                $project->user_id = 0;
                $project->type = $row->type;
                $project->meter_price = $row->meter_price;


                if ($row->map_marker != null || $row->map_marker != '') {
                    $filename = $this->url . '/uploads/' . $row->map_marker;
                    $image = Image::make($filename);
                    $ext = explode('.', $filename);
                    $name = rand(0, 99999999999) . end($ext);
                    $image->save("uploads/" . $name);
                    $project->map_marker = $name;
//                dd($project->map_marker);
                }
                if ($row->logo != null || $row->logo != '') {
                    $filename = $this->url . '/uploads/' . $row->logo;
                    $image = Image::make($filename);
                    $ext = explode('.', $filename);
                    $name = rand(0, 99999999999) . end($ext);
                    $image->save("uploads/" . $name);
                    $project->logo = $name;
                }

                if ($row->cover != null || $row->cover != '') {
                    $filename = $this->url . '/uploads/' . $row->cover;
                    $image = Image::make($filename);
                    $ext = explode('.', $filename);
                    $name = rand(0, 99999999999) . end($ext);
                    $image->save("uploads/" . $name);
                    $project->cover = $name;
                }
//            dd('sheno');

                if ($row->developer_pdf != '[]') {
                    $pdfs = json_decode($row->developer_pdf);
                    $pdfArr = [];
                    if($pdfs){
                        foreach($pdfs as $pdf) {
                            $file = $this->url . '/uploads/' . $pdf;
                            $ext = explode('.', $pdf);
                            $fileName = rand(0, 99999999999) . '.' . end($ext);
                            $newfile = public_path('uploads/' . $fileName);
                            
                            if (@copy($file, $newfile) ) {
                                $pdfArr[] = $fileName;
                            }
                        }
                    }
                    $project->developer_pdf = json_encode($pdfArr);
                }
                
                
                if ($row->broker_pdf != '[]') {
                    $pdfs = json_decode($row->broker_pdf);
                    $pdfArr = [];
                     if($pdfs){
                        foreach($pdfs as $pdf) {
                            $file = $this->url . '/uploads/' . $pdf;
                            $ext = explode('.', $pdf);
                            $fileName = rand(0, 99999999999) . '.' . end($ext);
                            $newfile = public_path('uploads/' . $fileName);
    
                            if (@copy($file, $newfile) ) {
                                $pdfArr[] = $fileName;
                            }
                        }
                    }
                    $project->broker_pdf = json_encode($pdfArr);
                }
                $project->portal_id = $row->id;
                $project->delivery_date = $row->delivery_date;
                $project->save();

                foreach ($data->facilities as $row) {
                    $fc_id = $row->id;
                    if (Facility::where('portal_id', $row->id)->count() == 0) {
                        $facility = new Facility();
                        $icon = Icon::where('portal_id')->first();
                        $facility->en_name = $row->en_name;
                        $facility->ar_name = $row->ar_name;
                        $facility->en_description = $row->en_description;
                        $facility->ar_description = $row->ar_description;
                        $facility->icon = $icon->id;
                        $facility->portal_id = $row->id;
                        $facility->save();
                        $fc_id = $facility->id;
                    }
                    if (UnitFacility::where('unit_id', $project->id)->where('facility_id', $fc_id)->where('type', 'project')->count() == 0) {
                        $facility1 = new UnitFacility();
                        $facility1->unit_id = $project->id;
                        $facility1->facility_id = $fc_id;
                        $facility1->type = 'project';
                        $facility1->save();
                    }
                }

                foreach ($data->gallery as $row) {
                    $gallery = new Gallery();
                    if ($row->image != null || $row->image != '') {
                        $filename = $this->url . '/uploads/' . $row->image;
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = 'gallery/' . rand(0, 9999999999966) . end($ext);
                        $image->save("uploads/" . $name);
                        $gallery->image = $name;
                        $gallery->project_id = $project->id;
                        $gallery->portal_id = $row->id;
                        $gallery->save();
                    }
                }

                foreach ($data->phase as $row) {
                    if (Phase::where('portal_id', $row->id)->count() == 0) {
                        $phase = new Phase();
                        $phase->en_name = $row->en_name;
                        $phase->ar_name = $row->ar_name;
                        $phase->en_description = $row->en_description;
                        $phase->ar_description = $row->ar_description;
                        $phase->meter_price = $row->meter_price;
                        $phase->area = $row->area;
                        $filename = $this->url . '/uploads/' . $row->logo;
                        if ($row->logo != null || $row->logo != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $project->logo = $name;
                        }

                        $filename = $this->url . '/uploads/' . $row->promo;
                        if ($row->promo != null || $row->promo != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $project->promo = $name;
                        }
//                    $phase->promo=$row->promo;
                        $phase->delivery_date = $row->delivery_date;
                        $phase->project_id = $project->id;
                        $phase->user_id = Auth::user()->id;
                        $phase->portal_id = $row->id;
                        $phase->save();
                    }
                }

                //////////////////////

                foreach ($data->units as $row) {
                    if (UnitType::where('portal_id', $row->type->id)->count() == 0) {
                        $temp = $row->type;
                        $unit_type = new UnitType();
                        $unit_type->en_name = $temp->en_name;
                        $unit_type->ar_name = $temp->ar_name;
                        $unit_type->description = $temp->description;
                        $unit_type->usage = $temp->usage;
                        $unit_type->user_id = Auth::user()->id;
                        $unit_type->portal_id = $temp->id;
                        $unit_type->save();
                        $unit_type_id = $unit_type->id;
                    } else {
                        $unit_type_id = UnitType::where('portal_id', $row->type->id)->first()->id;
                    }

                    if (Property::where('portal_id', $row->unit->id)->count() == 0) {
                        $temp = $row->unit;
                        $unit = new Property();
                        $unit->en_name = $temp->en_name;
                        $unit->ar_name = $temp->ar_name;
                        $unit->en_description = $temp->en_description;
                        $unit->ar_description = $temp->ar_description;
                        $unit->code = $temp->code;
                        $unit->lead_id = 0;
                        $unit->start_price = $temp->start_price;
                        $unit->meter_price = $temp->meter_price;
                        $unit->area_from = $temp->area_from;
                        $unit->area_to = $temp->area_to;
                        $unit->type = $temp->type;
                        $unit->main = $temp->main;
                        $unit->availability = $temp->availability;
                        $unit->phase_id = Phase::where('portal_id', $temp->phase_id)->first()->id;
                        $unit->unit_id = $unit_type_id;

                        $filename = $this->url . '/uploads/' . $temp->main;
                        if ($temp->main != null || $temp->mai != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $unit->main = $name;
                        }
                        $unit->user_id = Auth::user()->id;
                        $unit->portal_id = $temp->id;
                        $unit->save();
                    }
                }
            } else {
                if (@$data->developer) {
                    $row = $data->developer;
                    if (Developer::where('portal_id', $row->id)->count() == 0) {
//                dd( $row);
                        $developer = new Developer();
                    } else {
                        $developer = Developer::where('portal_id', $row->id)->first();
                    }
                    $developer->en_name = $row->en_name;
                    $developer->ar_name = $row->ar_name;
                    $developer->en_description = $row->en_description;
                    $developer->ar_description = $row->ar_description;
                    $developer->phone = $row->phone;
                    $developer->email = $row->email;
                    $developer->facebook = $row->facebook;
                    $filename = $this->url . '/uploads/' . $row->logo;
//                dd($filename);
                    if ($row->logo != null || $row->logo != '') {
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $developer->logo = $row->logo;
                    }
                    $developer->portal_id = $row->id;
                    $developer->save();


                }
                if (@$data->icons) {
                    foreach ($data->icons as $row) {
                        if (Icon::where('portal_id', $row->id)->count() == 0) {
                            $icon = new Icon();
                        } else {
                            $icon = Icon::where('portal_id', $row->id)->first();
                        }
                        $filename = $this->url . '/uploads/' . $row->icon;
                        if ($row->icon != null || $row->icon != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = "icon/" . rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $icon->icon = $name;
                            $icon->portal_id = $row->id;
                            $icon->save();
                        }
    
                    }
                }
                if (@$data->location) {
                    $row = $data->location;
                    if (Location::where('portal_id', $row->id)->count() == 0) {
                        $location = new Location();
                    } else {
                        $location = Location::where('portal_id', $row->id)->first();
                    }
                    $location->en_name = $row->en_name;
                    $location->ar_name = $row->ar_name;
                    $location->lat = $row->lat;
                    $location->lng = $row->lng;
                    $location->zoom = $row->zoom;
                    $location->parent_id = 0;
                    $location->portal_id = $row->id;
                    $location->save();

                    $row = $data->projects;

                    $project = Project::where('portal_id', $data->projects->id)->first();
                    $project->en_name = $row->en_name;
                    $project->ar_name = $row->ar_name;
                    $project->en_description = $row->en_description;
                    $project->ar_description = $row->ar_description;
                    $project->area = $row->area;
                    $project->lat = $row->lat;
                    $project->lng = $row->lng;
                    $project->zoom = $row->zoom;
                    $project->developer_id = Developer::where('portal_id', $data->developer->id)->first()->id;
//        dd($data->location->id);
                    $project->location_id = Location::where('portal_id', $data->location->id)->first()->id;
                    $project->down_payment = $row->down_payment;
                    $project->installment_year = $row->installment_year;
                    $project->commission = $row->commission;
                    $project->video = $row->video;
                    $project->priority = $row->priority;
                    $project->user_id = 0;
                    $project->type = $row->type;
                    $project->meter_price = $row->meter_price;


                    if ($row->map_marker != null || $row->map_marker != '') {
                        $filename = $this->url . '/uploads/' . $row->map_marker;
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $project->map_marker = $name;
//                dd($project->map_marker);
                    }
                    if ($row->logo != null || $row->logo != '') {
                        $filename = $this->url . '/uploads/' . $row->logo;
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $project->logo = $name;
                    }

                    if ($row->cover != null || $row->cover != '') {
                        $filename = $this->url . '/uploads/' . $row->cover;
                        $image = Image::make($filename);
                        $ext = explode('.', $filename);
                        $name = rand(0, 99999999999) . end($ext);
                        $image->save("uploads/" . $name);
                        $project->cover = $name;
                    }
                    
                    if ($row->developer_pdf != '[]') {
                        $pdfs = json_decode($row->developer_pdf);
                        $pdfArr = [];
                        foreach($pdfs as $pdf) {
                            $file = $this->url . '/uploads/' . $pdf;
                            $ext = explode('.', $pdf);
                            $fileName = rand(0, 99999999999) . '.' . end($ext);
                            $newfile = public_path('uploads/' . $fileName);
                            
                            if (@copy($file, $newfile) ) {
                                $pdfArr[] = $fileName;
                            }
                        }
                        $project->developer_pdf = json_encode($pdfArr);
                    }
                    
                    
                    if ($row->broker_pdf != '[]') {
                        $pdfs = json_decode($row->broker_pdf);
                        $pdfArr = [];
                        foreach($pdfs as $pdf) {
                            $file = $this->url . '/uploads/' . $pdf;
                            $ext = explode('.', $pdf);
                            $fileName = rand(0, 99999999999) . '.' . end($ext);
                            $newfile = public_path('uploads/' . $fileName);
                            
                            if (@copy($file, $newfile) ) {
                                $pdfArr[] = $fileName;
                            }
                        }
                        $project->broker_pdf = json_encode($pdfArr);
                    }
                
                
                    $project->portal_id = $row->id;
                    $project->delivery_date = $row->delivery_date;
                    $project->save();

                    foreach ($data->facilities as $row) {
                        $fc_id = $row->id;
                        if (Facility::where('portal_id', $row->id)->count() == 0) {
                            $facility = new Facility();
                        } else {
                            $facility = Facility::where('portal_id', $row->id)->first();
                        }
                        $icon = Icon::where('portal_id')->first();
                        $facility->en_name = $row->en_name;
                        $facility->ar_name = $row->ar_name;
                        $facility->en_description = $row->en_description;
                        $facility->ar_description = $row->ar_description;
                        $facility->icon = $icon->id;
                        $facility->portal_id = $row->id;
                        $facility->save();
                        $fc_id = $facility->id;

                        if (UnitFacility::where('unit_id', $project->id)->where('facility_id', $fc_id)->where('type', 'project')->count() == 0) {
                            $facility1 = new UnitFacility();
                        } else {
                            $facility1 = UnitFacility::where('unit_id', $project->id)->where('facility_id', $fc_id)->where('type', 'project')->first();
                        }
                        $facility1->unit_id = $project->id;
                        $facility1->facility_id = $fc_id;
                        $facility1->type = 'project';
                        $facility1->save();

                    }

                    foreach ($data->gallery as $row) {
                        $gallery = new Gallery();
                        if ($row->image != null || $row->image != '') {
                            $filename = $this->url . '/uploads/' . $row->image;
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = 'gallery/' . rand(0, 9999999999966) . end($ext);
                            $image->save("uploads/" . $name);
                            $gallery->image = $name;
                            $gallery->project_id = $project->id;
                            $gallery->portal_id = $row->id;
                            $gallery->save();
                        }
                    }

                    foreach ($data->phase as $row) {
                        if (Phase::where('portal_id', $row->id)->count() == 0) {
                            $phase = new Phase();
                        } else {
                            $phase = Phase::where('portal_id', $row->id)->first();
                        }
                        $phase->en_name = $row->en_name;
                        $phase->ar_name = $row->ar_name;
                        $phase->en_description = $row->en_description;
                        $phase->ar_description = $row->ar_description;
                        $phase->meter_price = $row->meter_price;
                        $phase->area = $row->area;
                        $filename = $this->url . '/uploads/' . $row->logo;
                        if ($row->logo != null || $row->logo != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $project->logo = $name;
                        }

                        $filename = $this->url . '/uploads/' . $row->promo;
                        if ($row->promo != null || $row->promo != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $project->promo = $name;
                        }
//                    $phase->promo=$row->promo;
                        $phase->delivery_date = $row->delivery_date;
                        $phase->project_id = $project->id;
                        $phase->user_id = Auth::user()->id;
                        $phase->portal_id = $row->id;
                        $phase->save();

                    }

                    //////////////////////

                    foreach ($data->units as $row) {
                        if (UnitType::where('portal_id', $row->type->id)->count() == 0) {
                            $unit_type = new UnitType();
                            $temp = $row->type;

                            $unit_type->en_name = $temp->en_name;
                            $unit_type->ar_name = $temp->ar_name;
                            $unit_type->description = $temp->description;
                            $unit_type->usage = $temp->usage;
                            $unit_type->user_id = Auth::user()->id;
                            $unit_type->portal_id = $temp->id;
                            $unit_type->save();
                            $unit_type_id = $unit_type->id;
                        } else {
                            $unit_type_id = UnitType::where('portal_id', $row->type->id)->first()->id;
                        }

                        if (Property::where('portal_id', $row->unit->id)->count() == 0) {
                            $temp = $row->unit;
                            $unit = new Property();
                        } else {
                            $temp = $row->unit;
                            $unit = Property::where('portal_id', $row->unit->id)->first();
                        }
                        $unit->en_name = $temp->en_name;
                        $unit->ar_name = $temp->ar_name;
                        $unit->en_description = $temp->en_description;
                        $unit->ar_description = $temp->ar_description;
                        $unit->code = $temp->code;
                        $unit->lead_id = 0;
                        $unit->start_price = $temp->start_price;
                        $unit->meter_price = $temp->meter_price;
                        $unit->area_from = $temp->area_from;
                        $unit->area_to = $temp->area_to;
                        $unit->type = $temp->type;
                        $unit->main = $temp->main;
                        $unit->availability = $temp->availability;
                        $unit->phase_id = Phase::where('portal_id', $temp->phase_id)->first()->id;
                        $unit->unit_id = $unit_type_id;

                        $filename = $this->url . '/uploads/' . $temp->main;
                        if ($temp->main != null || $temp->mai != '') {
                            $image = Image::make($filename);
                            $ext = explode('.', $filename);
                            $name = rand(0, 99999999999) . end($ext);
                            $image->save("uploads/" . $name);
                            $unit->main = $name;
                        }
                        $unit->user_id = Auth::user()->id;
                        $unit->portal_id = $temp->id;
                        $unit->save();

                    }
                }
                session()->flash('success', 'Project Found');
            }
            if (@$re)
                $re->delete();
        }
        return redirect(adminPath() . '/projects');
    }

    public function deletepush($id)
    {
        $re = ProjectRequest::find($id);
        $re->delete();
        return redirect(adminPath() . '/get_project');
    }

}
