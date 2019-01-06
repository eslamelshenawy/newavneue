<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventImage;
use App\Lead;
use App\LeadNotification;
use Illuminate\Http\Request;
use Validator;

class EventController extends Controller
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
        $events = Event::get();
        return view('admin.events.index', ['title' => __('admin.events'), 'events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events.create', ['title' => __('admin.event')]);
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
            'date' => 'required',
            'image' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title' => trans('admin.ar_title'),
            'en_title' => trans('admin.en_title'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'date' => trans('admin.date'),
            'image' => trans('admin.image'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $event = new Event;
            $event->ar_title = $request->ar_title;
            $event->en_title = $request->en_title;
            $event->ar_description = $request->ar_description;
            $event->en_description = $request->en_description;
            $event->event = $request->event;
            $event->launch = $request->launch;
            $event->news = $request->news;
            $event->meta_keywords = $request->meta_keywords;
            $event->meta_description = $request->meta_description;
            $event->date = strtotime($request->date);
            if ($request->hasFile('image')) {
                $event->image = $request->file('image')->store('events');
            }
            $event->user_id = auth()->user()->id;
            $event->save();

            if ($request->has('other_images')) {
                foreach ($request->other_images as $other_image) {
                    $eventImages = new EventImage;
                    $eventImages->image = $other_image->store('events');
                    $eventImages->event_id = $event->id;
                    $eventImages->save();
                }
            }

            $leads = Lead::where('refresh_token', '!=', '')->get();
            $tokens = Lead::where('refresh_token', '!=', '')->pluck('refresh_token')->toArray();
            foreach ($leads as $lead) {
                $notify = new LeadNotification;
                $notify->type = 'events';
                $notify->type_id = $event->id;
                $notify->ar_title = __('admin.new_event',[],'ar');
                $notify->en_title = __('admin.new_event',[],'en');
                $notify->ar_body = $event->en_title;
                $notify->en_body = $event->en_title;
                $notify->lead_id = $lead->id;
                $notify->user_id = auth()->user()->id;
                $notify->save();
            }

            $msg = array(
                'title' => __('admin.new_event',[],'en'),
                'body' => $event->en_title,
                'image' => 'myIcon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
            );
//                dd($tokens);
            notify($tokens,$msg);

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($event);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $event->ar_title,
                __('admin.created', [], 'en') . ' ' . $event->en_title,
                'events',
                $event->id,
                'create',
                auth()->user()->id,
                $old_data
            );

            return redirect(adminPath() . '/events/' . $event->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('admin.events.show', ['title' => __('admin.event'), 'event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', ['title' => __('admin.event'), 'event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $rules = [
            'ar_title' => 'required',
            'en_title' => 'required',
            'ar_description' => 'required',
            'en_description' => 'required',
            'date' => 'required',
//            'image' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'ar_title' => trans('admin.ar_title'),
            'en_title' => trans('admin.en_title'),
            'ar_description' => trans('admin.ar_description'),
            'en_description' => trans('admin.en_description'),
            'date' => trans('admin.date'),
//            'image' => trans('admin.image'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($event);
            $event->ar_title = $request->ar_title;
            $event->en_title = $request->en_title;
            $event->ar_description = $request->ar_description;
            $event->en_description = $request->en_description;
            $event->event = $request->event;
            $event->launch = $request->launch;
            $event->news = $request->news;
            $event->meta_keywords = $request->meta_keywords;
            $event->meta_description = $request->meta_description;
            $event->date = strtotime($request->date);
            if ($request->hasFile('image')) {
                $event->image = $request->file('image')->store('events');
            }
            $event->user_id = auth()->user()->id;
            $event->save();

            if ($request->has('other_images')) {
                foreach ($request->other_images as $other_image) {
                    $eventImages = new EventImage;
                    $eventImages->image = $other_image->store('events');
                    $eventImages->event_id = $event->id;
                    $eventImages->save();
                }
            }

            session()->flash('success', trans('admin.updated'));

            $new_data = json_encode($event);
            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $event->ar_title,
                __('admin.updated', [], 'en') . ' ' . $event->en_title,
                'events',
                $event->id,
                'update',
                auth()->user()->id,
                $old_data,
                $new_data
            );
            return redirect(adminPath() . '/events/' . $event->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        @unlink('uploads/'.$event->image);
        $images = EventImage::where('event_id',$event->id)->get();
        if (count($images) > 0) {
            foreach ($images as $image) {
                @unlink('uploads/'.$image->image);
                $image->delete();
            }
        }

        $old_data = json_encode($event);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $event->ar_title,
            __('admin.deleted', [], 'en') . ' ' . $event->en_title,
            'events',
            $event->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        $event->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect(adminPath().'/events');
    }

    public function delete_event_image(Request $request)
    {
        $img = EventImage::find($request->id);
        @unlink('uploads/'.$img->image);
        $img->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
