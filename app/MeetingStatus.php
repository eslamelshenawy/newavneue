<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingStatus extends Model
{
    public function meetings(){
        return $this->hasMany('App\Meeting');
    }
}
