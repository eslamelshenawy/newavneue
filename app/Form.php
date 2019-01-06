<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function developer()
    {
        return $this->belongsTo('App\Developer');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('App\Phase');
    }

    public function lead_source()
    {
        return $this->belongsTo('App\LeadSource');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }
}
