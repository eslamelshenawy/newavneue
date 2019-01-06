<?php

namespace App\Http\Controllers;

use App\OutCat;
use Illuminate\Http\Request;
use Validator;

class OutCatController extends Controller
{
    public function index()
    {
        $cats = OutCat::get();
        $title = __('admin.out_cats');
        return view('admin.out_cats.index', compact('cats', 'title'));
    }

    public function create()
    {
        $title = __('admin.out_cats');
        return view('admin.out_cats.create', compact('title'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $cat = new OutCat;
            $cat->name = $request->name;
            $cat->notes = $request->notes;
            $cat->save();

            $old_data = json_encode($cat);
            LogController::add_log(
                __('admin.created', [], 'ar') . ' ' . $cat->name,
                __('admin.created', [], 'en') . ' ' . $cat->name,
                'out_cats',
                $cat->id,
                'create',
                auth()->id(),
                $old_data
            );

            session()->flash('success', __('admin.created'));
            return redirect(adminPath() . '/out_cats');
        }
    }

    public function show(OutCat $outCat)
    {
        return view('admin.out_cats.show', ['cat' => $outCat, 'title' => __('admin.out_cats')]);
    }

    public function edit(OutCat $outCat)
    {
        return view('admin.out_cats.edit', ['cat' => $outCat, 'title' => __('admin.out_cats')]);
    }

    public function update(Request $request, OutCat $outCat)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->SetAttributeNames([
            'name' => trans('admin.name'),
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $old_data = json_encode($outCat);

            $outCat->name = $request->name;
            $outCat->notes = $request->notes;
            $outCat->save();

            $new_data = json_encode($outCat);

            LogController::add_log(
                __('admin.updated', [], 'ar') . ' ' . $outCat->name,
                __('admin.updated', [], 'en') . ' ' . $outCat->name,
                'out_cats',
                $outCat->id,
                'update',
                auth()->id(),
                $old_data,
                $new_data
            );

            session()->flash('success', __('admin.updated'));
            return redirect(adminPath() . '/out_cats');
        }
    }

    public function destroy(OutCat $outCat)
    {
        $old_data = json_encode($outCat);

        LogController::add_log(
            __('admin.deleted', [], 'ar') . ' ' . $outCat->name,
            __('admin.deleted', [], 'en') . ' ' . $outCat->name,
            'out_cats',
            $outCat->id,
            'delete',
            auth()->id(),
            $old_data
        );

        $outCat->delete();

        session()->flash('success', __('admin.deleted'));
        return redirect(adminPath() . '/out_cats');
    }
}
