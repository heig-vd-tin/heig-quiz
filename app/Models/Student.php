<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    function classrooms() {
        return $this->belongsToMany('App\Models\Classroom');
    }
}
