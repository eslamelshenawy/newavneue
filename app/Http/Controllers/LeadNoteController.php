<?php

namespace App\Http\Controllers;

use App\LeadNote;
use Illuminate\Http\Request;

class LeadNoteController extends Controller
{
    public function store(Request $request)
    {
        $note          = new LeadNote;
        $note->lead_id = $request->lead_id;
        $note->user_id = $request->user_id;
        $note->note    = $request->note;
        $note->save();

        return view('admin.leads.new_comment', ['note' => $note]);
    }
}
