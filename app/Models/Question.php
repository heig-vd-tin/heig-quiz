<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Proposition;

class Question extends Model
{
    function propositions() {
        return $this->belongsToMany(Proposition::class)->withPivot('is_correct');
    }
}
