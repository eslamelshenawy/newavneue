<?php

namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use Validator;

class BankController extends Controller
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
    	$banks = Bank::all();
        return view('admin.bank.index',['title' => trans('admin.all').' '.trans('admin.bank'),'banks'=>$banks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$rules = [
			'name'=>'required',
			'account_number'=>'required',
			'open_value'=>'required',
			'currency'=>'required',
			];
		$validator = Validator::make($request->all(), $rules);
		$validator->SetAttributeNames([
			'name'=> trans('admin.name'),
			'account_number'=>trans('admin.account_number'),
			'open_value'=>trans('admin.open_value'),
			'currency'=>trans('admin.currency'),
			]);
		if ($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		}
		else {
			$bank = new Bank;
			$bank->name = $request->name;
			$bank->account_number = $request->account_number;
			$bank->open_value = $request->open_value;
			$bank->currency = $request->currency;
			$bank->save();

            $old_data = json_encode($bank);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $bank->name,
                __('admin.created', [], 'en') . ' ' . $bank->name,
                'bank',
                $bank->id,
                'create',
                auth()->user()->id,
                $old_data
            );

			return back();
		}

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Request$request, $id)
    {
        $bank = Bank::find($id);
        $old_data = json_encode($bank);
        $bank->name = $request->name;
        $bank->account_number = $request->account_number;
        $bank->open_value = $request->open_value;
        $bank->currency = $request->currency;
        $bank->save();

        $new_data = json_encode($bank);
        LogController::add_log(
            __('admin.updated', [], 'ar') . ' ' . $bank->name,
            __('admin.updated', [], 'en') . ' ' . $bank->name,
            'bank',
            $bank->id,
            'update',
            auth()->user()->id,
            $old_data,
            $new_data
        );
		return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $bank = Bank::find($id);

        $old_data = json_encode($bank);
        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $bank->name,
            __('admin.deleted', [], 'en') . ' ' . $bank->name,
            'bank',
            $bank->id,
            'delete',
            auth()->user()->id,
            $old_data
        );

        $bank->delete();
        return back();
    }
}
