<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallStatus extends Model
{
    public function calls(){
        return $this->hasMany('App\Call');
    }
}
