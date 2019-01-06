<?php

namespace App\Http\Controllers;

use App\Safe;
use Illuminate\Http\Request;

class SafeController extends Controller
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


    public function create(Request $request){
		$safe = new Safe;
		$safe->ar_name = $request->ar_name;
		$safe->en_name = $request->en_name;
		$safe->save();
		return back();
	}
	public function destroy($id){
		Safe::find($id)->delete();
		return back();

	}
	public function edit(Request $request, $id){
		$safe = Safe::find($id);
		$safe->ar_name= $request->ar_name;
		$safe->en_name= $request->en_name;
		$safe->save();
		return back();
	}
}
