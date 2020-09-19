<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Proposition;

class Question extends Model
{
    protected $hidden = ['pivot'];

    function keywords() {
        return $this->belongsToMany('App\Models\Keyword');
    }

    function quiz() {
        return $this->belongsToMany('App\Models\Quiz');
    }
}
