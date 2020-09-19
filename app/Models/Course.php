<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;

class Course extends Model
{
    function classrooms() {
        return $this->hasMany(Classroom::class)->withCount('students');
    }
}
