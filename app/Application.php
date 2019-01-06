<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function vacancy(){
        return $this->belongsTo('App\vacancy');
    }
    public function job_title(){
        return $this->belongsTo('App\JobTitle','job_title_id');
    }
    public function job_category(){
        return $this->belongsTo('App\JobCategory','job_category_id');
    }
    public function proposal(){
        return $this->hasOne('App\JobProposal');
    }
    public function cv(){
        return $this->belongsTo('App\Cv');
    }
}
