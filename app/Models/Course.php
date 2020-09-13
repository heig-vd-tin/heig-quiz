<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Classroom;

class Course extends Model
{
    function classrooms() {
        return $this->belongsTo(Classroom::class);
    }
}
