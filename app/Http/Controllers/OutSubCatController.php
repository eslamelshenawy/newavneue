<?php

namespace App\Http\Controllers;

use App\OutCat;
use App\OutSubCat;
use Illuminate\Http\Request;
use Validator;

class OutSubCatController extends Controller
{
    public function index()
    {
        $subs = OutSubCat::get();
        $title = __('admin.out_sub_cats');
        return view('admin.out_sub_cats.index', compact('subs', 'title'));
    }

    public function create()
    {
        $title = __('admin.out_sub_cats');
        $cats = OutCat::get();
        return view('admin.out_sub_cats.create', compact('title', 'cats'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'out_cat_id' => 'required',
            'due_date' => 'required|min:1|max:31',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'due_date' => trans('admin.due_date'),
            'out_cat_id' => trans('admin.out_cat'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $sub = new OutSubCat();
            $sub->name = $request->name;
            $sub->due_date = $request->due_date;
            $sub->notes = $request->notes;
            $sub->out_cat_id = $request->out_cat_id;
            $sub->save();

            $old_data = json_encode($sub);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $sub->name,
                __('admin.created', [], 'en') . ' ' . $sub->name,
                'out_sub_cats',
                $sub->id,
                'create',
                auth()->id(),
                $old_data
            );

            session()->flash('success', __('admin.created'));
            return redirect(adminPath() . '/out_sub_cats');
        }
    }

    public function show(OutSubCat $outSubCat)
    {
        return view('admin.out_sub_cats.show', ['sub' => $outSubCat, 'title' => __('admin.out_sub_cats')]);
    }

    public function edit(OutSubCat $outSubCat)
    {
        $cats = OutCat::get();
        return view('admin.out_sub_cats.edit', ['sub' => $outSubCat, 'title' => __('admin.out_sub_cats'), 'cats' => $cats]);
    }

    public function update(Request $request, OutSubCat $outSubCat)
    {
        $rules = [
            'name' => 'required',
            'due_date' => 'required|min:1|max:31',
            'out_cat_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
            'due_date' => trans('admin.due_date'),
            'out_cat_id' => trans('admin.out_cat'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($outSubCat);

            $outSubCat->name = $request->name;
            $outSubCat->due_date = $request->due_date;
            $outSubCat->notes = $request->notes;
            $outSubCat->out_cat_id = $request->out_cat_id;
            $outSubCat->save();

            $new_data = json_encode($outSubCat);

            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $outSubCat->name,
                __('admin.updated', [], 'en') . ' ' . $outSubCat->name,
                'out_sub_cats',
                $outSubCat->id,
                'update',
                auth()->id(),
                $old_data,
                $new_data
            );

            session()->flash('success', __('admin.updated'));
            return redirect(adminPath() . '/out_sub_cats');
        }
    }

    public function destroy(OutSubCat $outSubCat)
    {
        $old_data = json_encode($outSubCat);

        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $outSubCat->name,
            __('admin.deleted', [], 'en') . ' ' . $outSubCat->name,
            'out_sub_cats',
            $outSubCat->id,
            'delete',
            auth()->id(),
            $old_data
        );

        $outSubCat->delete();

        session()->flash('success', __('admin.deleted'));
        return redirect(adminPath() . '/out_sub_cats');
    }
}
