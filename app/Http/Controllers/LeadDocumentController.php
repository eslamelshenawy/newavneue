<?php

namespace App\Http\Controllers;

use App\LeadDocument;
use Illuminate\Http\Request;
use Validator;

class LeadDocumentController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'lead_id' => 'required',
            'title' => 'required',
            'file' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'lead_id' => trans('admin.lead'),
            'title' => trans('admin.title'),
            'file' => trans('admin.file'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $doc = new LeadDocument;
            $doc->title = $request->title;
            $doc->lead_id = $request->lead_id;
            if ($request->hasFile('file')) {
                $doc->file = $request->file('file')->store('documents');
            }
            $doc->user_id = auth()->user()->id;
            $doc->save();
            session()->flash('success', trans('admin.created'));
            return back();
        }
    }
}
