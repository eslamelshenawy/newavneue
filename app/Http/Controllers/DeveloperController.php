<?php

namespace App\Http\Controllers;

use App\Competitor;
use App\Developer;
use App\DeveloperContact;
use App\Project;
use Auth;
use Illuminate\Http\Request;
use Validator;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (checkRole('show_developers') or @auth()->user()->type == 'admin') {
            $developer = Developer::all();
            return view('admin.developers.index', ['title' => trans('admin.all') . ' ' . trans('admin.developers'), 'developer' => $developer]);
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
        if (checkRole('add_developers') or @auth()->user()->type == 'admin') {
            return view('admin.developers.create', ['title' => trans('admin.add') . ' ' . trans('admin.developer')]);
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
            'en_name' => 'required|max:191',
            'en_description' => 'required',
            'logo' => 'required|image',
            'phone' => 'required|numeric',
            'email' => 'required|email|max:191',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'en_description' => trans('admin.description'),
            'logo' => trans('admin.logo'),
            'phone' => trans('admin.phone'),
            'email' => trans('admin.email'),
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->hasFile('logo')) {
                $developer = new Developer;
                $developer->en_name = $request->en_name;
                $developer->en_description = $request->en_description;
                $developer->ar_name = $request->ar_name;
                $developer->phone = $request->ar_name;
                $developer->ar_name = $request->ar_name;
                $developer->phone = $request->phone;
                $developer->email = $request->email;
                $developer->ar_description = $request->ar_description;
                $developer->facebook = $request->facebook;
                $developer->featured = $request->featured;
                $developer->logo = uploads($request, 'logo');
                $developer->website_cover = uploads($request, 'website_cover');
                $developer->user_id = Auth::user()->id;
                $developer->save();
                for ($i = 0; $i < count($request->contact_name); $i++) {
                    $contact = new DeveloperContact;
                    $contact->developer_id = $developer->id;
                    $contact->name = $request->contact_name[$i];
                    $contact->email = $request->contact_email[$i];
                    $contact->phone = $request->contact_phone[$i];
                    $contact->save();
                }
            } else {
                return back()->withInput()->withErrors('uploaded invalid logo');
            }

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($developer);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $developer->ar_name,
                __('admin.created', [], 'en') . ' ' . $developer->en_name,
                'developers',
                $developer->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath() . '/developers');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Developer $developer
     * @return \Illuminate\Http\Response
     */
    public function show(Developer $developer)
    {
        if (checkRole('show_developers') or @auth()->user()->type == 'admin') {
            return view('admin.developers.show', ['title' => trans('admin.show') . ' ' . trans('admin.developer'), 'developer' => $developer]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function website_show($id)
    {
        $index = explode('-', $id);
        $developer = Developer::find(end($index));
        return view('website.developer', ['developer' => $developer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Developer $developer
     * @return \Illuminate\Http\Response
     */
    public function edit(Developer $developer)
    {
        if (checkRole('edit_developers') or @auth()->user()->type == 'admin') {
            return view('admin.developers.edit', ['title' => trans('admin.developer'), 'developer' => $developer]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Developer $developer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Developer $developer)
    {

        $rules = [
            'en_name' => 'required|max:191',
            'en_description' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email|max:191',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'en_description' => trans('admin.description'),
            'image' => trans('admin.logo'),
            'phone' => trans('admin.phone'),
            'email' => trans('admin.email'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($developer);
            $file_path = 'uploads/' . $developer->logo;
            // return 'sheno';
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    if (file_exists($file_path)) {
                        @unlink($file_path);
                    }
                    $developer->logo = uploads($request, 'image');
                }
            }
            if ($request->has('website_cover')) {
                $file_path2 = 'uploads/' . $developer->website_cover;
                if ($request->hasFile('website_cover')) {
                    if ($request->file('website_cover')->isValid()) {
                        if (file_exists($file_path2)) {
                            @unlink($file_path2);
                        }
                        $developer->website_cover = uploads($request, 'website_cover');
                    }
                }
            } else {
                $developer->website_cover = $request->old_website_cover;
            }
            $developer->en_name = $request->en_name;
            $developer->en_description = $request->en_description;
            $developer->ar_name = $request->ar_name;
            $developer->ar_description = $request->ar_description;
            $developer->phone = $request->phone;
            $developer->email = $request->email;
            $developer->facebook = $request->facebook;
            $developer->featured = $request->featured;
            $developer->save();
            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($developer);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $developer->ar_name,
                __('admin.updated', [], 'en') . ' ' . $developer->en_name,
                'developers',
                $developer->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            return redirect(adminPath() . '/developers');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Developer $developer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Developer $developer)
    {
        if (checkRole('delete_developers') or @auth()->user()->type == 'admin') {
            $old_data = json_encode($developer);
            LogController::add_log(
                __('admin.deleted', [], 'ar') . ' ' . $developer->ar_name,
                __('admin.deleted', [], 'en') . ' ' . $developer->en_name,
                'developers',
                $developer->id,
                'delete',
                auth()->user()->id,
                $old_data
            );

            $file_path = url('/uploads/' . $developer->logo);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            DeveloperContact::where('developer_id', $developer->id)->delete();
            $developer->delete();
            session()->flash('success', trans('admin.deleted'));
            return redirect(adminPath() . '/developers');
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function projects_facebook()
    {
        if (checkRole('marketing') or @auth()->user()->type == 'admin') {
            $projects = Project::where('featured', 1)->get();
            $data = [];
            foreach ($projects as $project) {
                $data[] = self::get_facebook_posts($project->facebook);
            }
            return view('admin.developers.facebook', ['data' => $data]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function developers_facebook()
    {
        if (checkRole('marketing') or @auth()->user()->type == 'admin') {
            $developers = Developer::where('featured', 1)->get();
            $data = [];
            foreach ($developers as $developer) {
                $data[] = self::get_facebook_posts($developer->facebook);
            }
            return view('admin.developers.facebook', ['data' => $data]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function competitors_facebook()
    {
        if (checkRole('marketing') or @auth()->user()->type == 'admin') {
            $competitors = Competitor::where('featured', 1)->get();
            $data = [];
            foreach ($competitors as $competitor) {
                $data[] = self::get_facebook_posts($competitor->facebook);
            }
            return view('admin.developers.facebook', ['data' => $data]);
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

    public function get_facebook_posts($page)
    {
        if (checkRole('marketing') or @auth()->user()->type == 'admin') {
            $response = null;
            require_once base_path() . '/vendor/autoload.php';
            $fb = new \Facebook\Facebook([
                'app_id' => '1901406280187678',
                'app_secret' => '89a6b390fb1b32013f61d6bc1192db33',
            ]);
            try {
                // Returns a `FacebookFacebookResponse` object
                $response = $fb->get(
                    '/' . $page . '?fields=posts.limit(10){full_picture,message,created_time,attachments{subattachments}},picture{url},name',
                    '1901406280187678|89a6b390fb1b32013f61d6bc1192db33'
                );
            } catch (\Facebook\Exceptions\FacebookExceptionsFacebookResponseException $e) {
                //
            } catch (\Facebook\Exceptions\FacebookExceptionsFacebookSDKException $e) {
                //
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                //
            }
            if ($response != null) {
                $graphNode = $response->getGraphNode();
            } else {
                $graphNode = '';
            }
            $data = json_decode($graphNode);
            return $data;
        } else {
            session()->flash('error', __('admin.you_dont_have_permission'));
            return back();
        }
    }

}
