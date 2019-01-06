<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function vacations(){
        return $this->hasMany('App\Vacation');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function salary()
    {
        return $this->hasMany('App\Salary','employee_id');
    }

    public function rates(){
        return $this->hasMany('App\Rate','employee_id');
    }

}
