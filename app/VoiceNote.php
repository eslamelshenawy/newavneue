<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoiceNote extends Model
{
    public function agent()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public static function getLeadVoiceNotesSync($LastSync){
        $leadIds = VoiceNote::where('updated_at', '>=', $LastSync)->pluck('lead_id')->all();
        
        return $leadIds;
    }
}
