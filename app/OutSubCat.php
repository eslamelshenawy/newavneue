<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutSubCat extends Model
{
    public function cat()
    {
        return $this->belongsTo('App\OutCat', 'out_cat_id');
    }
}
