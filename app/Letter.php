<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    public $guarded = [];
    protected $connection = 'mysql';
    public function access()
    {
        return $this->belongsTo('App\Access');
    }
}
