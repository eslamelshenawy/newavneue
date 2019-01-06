<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;

class LogController extends Controller
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

    public static function add_log($ar, $en, $route, $route_id, $type, $user_id, $old_data = null, $new_data = null)
    {
        $log = new Log;
        $log->ar_title = $ar;
        $log->en_title = $en;
        $log->route = $route;
        $log->route_id = $route_id;
        $log->type = $type;
        $log->user_id = $user_id;
        if (isset($old_data)) {
            $log->old_data = $old_data;
        }
        if (isset($new_data)) {
            $log->new_data = $new_data;
        }

        $log->save();
    }

    public function index()
    {
        return view('admin.logs.index', ['title' => __('admin.logs'), 'logs' => Log::all()]);
    }

    public function show($id)
    {
        return view('admin.logs.show', ['title' => __('admin.log'), 'log' => Log::find($id)]);
    }
}
