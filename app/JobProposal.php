<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProposal extends Model
{
    public function application(){
        return $this->belongsTo('App\Application');
    }
}
