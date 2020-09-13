<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Classroom;
use Quiz;

class Activity extends Model
{
    function quiz() {
        return $this->hasOne(Quiz::class);
    }

    function classroom() {
        return $this->hasOne(Classroom::class);
    }
}
