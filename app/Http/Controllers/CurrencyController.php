<?php

namespace App\Http\Controllers;

use App\currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function create(Request $r){
    	$currency = new currency;
    	$currency->ar_name= $r->ar_name;
    	$currency->en_name= $r->en_name;
    	$currency->save();
    	return back();
	}
	public function delete_currency($id){
		currency::find($id)->delete();
		return back();

	}
	public function edit_currency(Request $request, $id){
		$currency = currency::find($id);
		$currency->ar_name= $request->ar_name;
		$currency->en_name= $request->en_name;
		$currency->save();
		return back();
	}
}
