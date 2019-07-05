<?php

namespace App;

use app\User;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'access_user');
    }
}
