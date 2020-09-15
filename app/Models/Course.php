<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;

class Course extends Model
{
    function classroom() {
        return $this->hasOne(Classroom::class);
    }
}
