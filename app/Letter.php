<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    public function access()
    {
        return $this->belongsTo('App\Access');
    }
}
