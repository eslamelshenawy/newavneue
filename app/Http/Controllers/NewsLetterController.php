<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewsLetter;

class NewsLetterController extends Controller
{
	public function store(Request $request){
		if(count(NewsLetter::where('email',$request->email)->get()) == 0){
			$newsletter = new NewsLetter();
			$newsletter->email = $request->email;
			$newsletter->save();
		}
		return back();
	}
}
