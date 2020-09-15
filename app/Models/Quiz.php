<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Quiz extends Model
{
    function question() {
        return $this->belongsToMany(Question::class);
    }
}
