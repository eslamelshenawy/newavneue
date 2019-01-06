<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    public function member()
    {
        return $this->belongsTo('App\User', 'member_id');
    }
}
