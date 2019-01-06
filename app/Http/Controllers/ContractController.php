<?php

namespace App\Http\Controllers;

use App\Contract;
use Illuminate\Http\Request;
use Validator;

class ContractController extends Controller
{
    public function create()
    {
        $title = __('admin.contract');
        return view('admin.leads.create_contract', compact('title'));
    }

    public function store(Request $request)
    {
        $rules = [
            'lead_id' => 'required',
            'contract' => 'required',
            'title' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'contract' => trans('admin.contract'),
            'title' => trans('admin.title'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $contract = new Contract;
            $contract->title = $request->title;
            $contract->lead_id = $request->lead_id;
            $contract->contract = $request->contract;
            if ($request->hasFile('background')) {
                $contract->background = upload($request->background, 'contract_background');
            } else {
                $contract->background = 'contract_background/bg.jpg';
            }
            $url = md5(date('dmYhisAdmYhisAdmYhisA') . rand(0, 99999999999999));
            if (Contract::where('url', $url)->count() == 0) {
                $contract->url = $url;
            } else {
                $contract->url = md5(date('dmYhisAdmYhisAdmYhisA') . rand(0, 99999999999999));
            }
            $contract->user_id = auth()->user()->id;
            $contract->save();

            session()->flash('success', trans('admin.created'));

            $old_data = json_encode($contract);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $contract->ar_name,
                __('admin.created', [], 'en') . ' ' . $contract->en_name,
                'contracts',
                $contract->id,
                'create',
                auth()->user()->id,
                $old_data
            );
            return redirect(adminPath() . '/leads/' . $contract->lead_id);
        }
    }

    public function show($id)
    {
        $contract = Contract::find($id);
        $title = __('admin.contract');
        return view('admin.leads.show_contract', compact('contract', 'title'));
    }
    
    public function delete($id)
    {
        $contract = Contract::find($id);
        @unlink(base_path('upload') . '/' . $contract->background);
        $contract->delete();
        session()->flash('success', __('admin.deleted'));
        return back();
    }
    
    public function edit($id)
    {
        $contract = Contract::find($id);
        $title = __('admin.contract');
        return view('admin.leads.edit_contract', compact('contract', 'title'));
    }
    
    public function update(Request $request, $id)
    {
        $rules = [
            'lead_id' => 'required',
            'contract' => 'required',
            'title' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'contract' => trans('admin.contract'),
            'title' => trans('admin.title'),
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $contract = Contract::find($id);
            $contract->title = $request->title;
            $contract->lead_id = $request->lead_id;
            $contract->contract = $request->contract;
            if ($request->hasFile('background')) {
                @unlink(base_path('upload') . '/' . $contract->background);
                $contract->background = upload($request->background, 'contract_background');
            }
            
            $contract->save();

            session()->flash('success', trans('admin.updated'));
            return redirect(adminPath() . '/leads/' . $contract->lead_id);
        }
    }
}
