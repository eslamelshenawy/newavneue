<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Massage;
use Illuminate\Http\Request;

class MassageController extends Controller
{
    public function store(Request $request){
		if($request->user){
			$msg = new Massage;
			$msg->massage = $request->massage;
			$msg->lead_id = $request->user;
			$msg->save();
		}
		else{
			if(count(Lead::where('phone',$request->phone)->get())== 0 && count(Lead::where('email',$request->email)->get())== 0) {
				$lead = new Lead();
				$lead->first_name = $request->first_name;
				$lead->last_name = $request->last_name;
				$lead->phone = $request->phone;
				$lead->email = $request->email;
				$lead->save();
				$msg = new Massage;
				$msg->massage = $request->massage;
				$msg->lead_id = $lead->id;
				$msg->save();
			}
			else {
				$lead = null;
				if(count(Lead::where('phone',$request->phone)->first())> 0)
				{$lead =  Lead::where('phone',$request->phone)->first();}
				if(count(Lead::where('email',$request->email)->first())> 0)
				{$lead =  Lead::where('email',$request->email)->first();}
				$msg = new Massage;
				$msg->massage = $request->massage;
				$msg->lead_id = $lead->id;
				$msg->save();
			}
		}
		return back();
	}
}
