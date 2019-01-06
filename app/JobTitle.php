<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    public function category(){
        return $this->belongsTo('App\JobCategory','job_category_id');
    }
    public function vacancies(){
        return $this->hasMany('App\vacancy');
    }
    public function applications(){
        return $this->hasMany('App\Application');
    }
}
