<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calender()
    {
        return view('admin.calendar.calendar',['title'=>trans('admin.calendar')]);
    }
}
