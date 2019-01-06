<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public function lead()
    {
        return $this->belongsTo('App\Lead');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function unit_type()
    {
        return $this->belongsTo('App\UnitType', 'unit_type_id')->withDefault(['id' => null,'en_name' => null,'ar_name' => null,'description' => null]);
    }

    public function location()
    {
        return $this->belongsTo('App\Location', 'location')->withDefault(['id' => null,'en_name' => null,'ar_name' => null,'parent_id' => null]);
    }

    public static function getLeadRequestsSync($LastSync){
        $reqsIds = Request::where('updated_at', '>=', $LastSync)->pluck('lead_id')->all();

        return $reqsIds;
    }
}
