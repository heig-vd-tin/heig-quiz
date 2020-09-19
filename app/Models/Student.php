<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $hidden = ['pivot'];

    function classrooms() {
        return $this->belongsToMany(Classroom::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
