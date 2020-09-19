<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    function teacher() {
        return $this->belongsTo(User::class);
    }

    function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    function answer() {
        return $this->hasMany(Answer::class);
    }
}
