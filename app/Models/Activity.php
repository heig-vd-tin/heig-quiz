<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\Answer;

class Activity extends Model
{
    function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    function answer() {
        return $this->hasMany(Answer::class);
    }
}
