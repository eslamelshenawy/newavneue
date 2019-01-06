<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    protected $fillable = ['employee_id','image','code'];
  
    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }


}
