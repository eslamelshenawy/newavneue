<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function children() {
        return $this->hasMany('App\Group','parent_id','id') ;
    }
}
