<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    public function job_title(){
        return $this->hasMany('App\JobTitle','job_category_id');
    }
    public function applications(){
        return $this->hasMany('App\Application','job_category_id');
    }

}
