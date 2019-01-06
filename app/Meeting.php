<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public static function getLeadMeetingsSync($LastSync){
        $leadIds = Meeting::where('updated_at', '>=', $LastSync)->pluck('lead_id')->all();
        
        return $leadIds;
    }
    
    public function meeting_status(){
        return $this->belongsTo('App\MeetingStatus', 'meeting_status_id')->withDefault(['id' => null, 'name' => null, 'has_next_action' => 0]);
    }
}
