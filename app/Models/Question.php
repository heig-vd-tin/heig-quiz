<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Proposition;

class Question extends Model
{
    function keyword() {
        return $this->belongsToMany('App\Models\Keyword');
    }

    function propositions() {
        return $this->belongsToMany(Proposition::class)->withPivot('is_correct');
    }
}
