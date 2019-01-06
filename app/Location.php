<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //

    public $fillable = ['en_name',' ar_name','parent_id'];
    
    public function childs() {
        return $this->hasMany('App\Location','parent_id','id');
    }
    
    
    public function request()
    {
        return $this->hasMany('App\Request');
    }
}
