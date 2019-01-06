<?php

namespace App\Http\Controllers;

use App\HubPhone;
use App\HubSocial;
use App\Setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
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

    public function index()
    {
        $settings = Setting::find(1);
        $themes = [
            'skin-blue',
            'skin-black',
            'skin-purple',
            'skin-green',
            'skin-red',
            'skin-yellow',
            'skin-blue-light',
            'skin-black-light',
            'skin-purple-light',
            'skin-green-light',
            'skin-red-light',
            'skin-yellow-light'
        ];
        return view('admin.settings', ['title' => __('admin.settings'), 'settings' => $settings, 'themes' => $themes]);
    }

    public function settings()
    {
        $settings = Setting::find(1);
        $themes = [
            'skin-blue',
            'skin-black',
            'skin-purple',
            'skin-green',
            'skin-red',
            'skin-yellow',
            'skin-blue-light',
            'skin-black-light',
            'skin-purple-light',
            'skin-green-light',
            'skin-red-light',
            'skin-yellow-light'
        ];
        return view('admin.settings', ['settings' => $settings, 'themes' => $themes, 'title' => trans('admin.settings')]);
    }

    public function update_settings(Request $request)
    {
        $rules = [
            'admin_path' => 'required|max:191',
            'title' => 'required|max:191',
            'theme' => 'required',
            'get_in_touch' => 'required',
            'address' => 'required',
            'ar_address' => 'required',
            'email' => 'required',
            'about_us' => 'required',
            'mission' => 'required',
            'vision' => 'required',
            'ar_about_us' => 'required',
            'ar_mission' => 'required',
            'ar_vision' => 'required',
            'mail_provider' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'admin_path' => trans('admin.admin_path'),
            'title' => trans('admin.title'),
            'theme' => trans('admin.theme'),
            'get_in_touch' => trans('admin.get_in_touch'),
            'address' => trans('admin.address'),
            'ar_address' => trans('admin.ar_address'),
            'email' => trans('admin.email'),
            'about_us' => trans('admin.about_us'),
            'mission' => trans('admin.mission'),
            'vision' => trans('admin.vision'),
            'watermark' => trans('admin.watermark'),
            'ar_about_us' => trans('admin.ar_about_us'),
            'ar_mission' => trans('admin.ar_mission'),
            'ar_vision' => trans('admin.ar_vision'),
            'mail_provider' => trans('admin.mail_provider'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $settings = Setting::find(1);
            $old_data = json_encode($settings);
            if ($request->hasFile('logo')) {
                $settings->logo = uploads($request, 'logo');
            }

            if ($request->hasFile('watermark')) {
                $settings->watermark = uploads($request, 'watermark');
            }
            $settings->admin_path = $request->admin_path;
            $settings->title = $request->title;
            $settings->theme = $request->theme;
            //////////////////////////////////////
            $settings->lat = $request->lat;
            $settings->lng = $request->lng;
            $settings->zoom = $request->zoom;
            $settings->get_in_touch = $request->get_in_touch;
            $settings->address = $request->address;
            $settings->ar_address = $request->ar_address;
            $settings->email = $request->email;
            $settings->about_hub = $request->about_us;
            $settings->mission = $request->mission;
            $settings->vision = $request->vision;
            $settings->ar_about_hub = $request->ar_about_us;
            $settings->ar_mission = $request->ar_mission;
            $settings->ar_vision = $request->ar_vision;
            $settings->apple_store = $request->apple_store;
            $settings->play_store = $request->play_store;
            $settings->mail_provider = $request->mail_provider;
            $settings->leads_mail  = $request->leads_mail ;
            $settings->lead_mail_password = $request->lead_mail_password;


//            dd($request->social_url);

            for ($i = 0; $i < count($request->social_url); $i++) {
                if ($request->input('social_id.' . $i) > 0) {
                    $social = HubSocial::find($request->input('social_id.' . $i));
                    if ($request->hasFile('social_mobile_icon.' . $i)) {
                        $social->mobile_icon = uploads($request, 'social_mobile_icon.'.$i);
                    } else {
                        $social->mobile_icon = $request->old_mobile_icon[$i];
                    }
//                    dd($i);
//                    dd($request->File('social_web_icon.0'));
                    if ($request->hasFile('social_web_icon.' . $i)) {
                        $social->web_icon = uploads($request, 'social_web_icon.'.$i);
                    } else {
                        $social->web_icon = $request->old_web_icon[$i];
                    }
                    $social->link = $request->social_url[$i];
                    $social->save();
                } else {
                    $social = new HubSocial();
                    if ($request->hasFile('social_mobile_icon.'.$i)){
                        $social->mobile_icon = uploads($request, 'social_mobile_icon.'.$i);
                    } else {
                        $social->mobile_icon = 'icon.png';
                    }
                    if ($request->hasFile('social_web_icon.'.$i)) {
                        $social->web_icon = uploads($request, 'social_web_icon.'.$i);
                    } else {
                        $social->web_icon = 'icon.png';
                    }
//                    dump([$i]);
                    $social->link = $request->social_url[$i];
                    $social->save();
                }
            }

            if ($request->has('phone')) {
                foreach (HubPhone::get() as $p) {
                    $p->delete();
                }
                foreach ($request->phone as $phone) {
                    $new = new HubPhone;
                    $new->phone = $phone;
                    $new->save();
                }
            }

            $settings->save();

            $new_data = json_encode($settings);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . __('admin.settings', [], 'ar'),
                __('admin.updated', [], 'en') . ' ' . __('admin.settings', [], 'en'),
                'settings',
                $settings->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );

            return redirect(adminPath() . '/');
        }
    }
    
    public function facebook(Request $request)
    {
        $settings = Setting::find(1);
        $settings->facebook_api =$request->app_id;
        $settings->fb_token =$request->fb_token;
        $settings->save();
        return back();
    }
}
