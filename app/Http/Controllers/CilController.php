<?php

namespace App\Http\Controllers;

use App\Cil;
use Illuminate\Http\Request;

class CilController extends Controller
{
    public function cil_change_status(Request $request,$id)
    {
        $cil = Cil::find($id);
        $cil->status = $request->type;
        $cil->save();
        return response()->json([
            'status' => $request->type,
            'id' => $id
        ]);
    }
}
