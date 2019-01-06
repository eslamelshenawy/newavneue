<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function lead()
    {
        return $this->belongsTo('App\Lead', 'lead_id');
    }
    
    public static function getLeadNotesSync($LastSync){
        $leadIds = LeadNote::where('updated_at', '>=', $LastSync)->pluck('lead_id')->all();
        
        return $leadIds;
    }
}
