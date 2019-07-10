<?php

namespace App;

use app\User;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->belongsToMany('App\User', 'access_user');
    }

    public function letter()
    {
        return $this->hasOne('App\Letter');
    }
}
