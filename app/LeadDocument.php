<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadDocument extends Model
{
    //
    
    public static function getLeadDocumentsSync($LastSync){
        $leadIds = LeadDocument::where('updated_at', '>=', $LastSync)->pluck('lead_id')->all();
        
        return $leadIds;
    }
}
