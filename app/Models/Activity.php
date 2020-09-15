<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Quiz;

class Activity extends Model
{
    function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    function classroom() {
        return $this->belongsTo(Classroom::class);
    }
}
